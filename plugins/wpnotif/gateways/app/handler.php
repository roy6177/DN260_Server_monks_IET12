<?php

defined('ABSPATH') || exit;

WPNotif_App_Handler::instance();

class WPNotif_App_Handler
{
    const api_namespace = 'wpnotif/v1';
    const table = 'wpnotif_sms_app';
    const expiry = 1800;
    protected static $_instance = null;

    /**
     *  Constructor.
     */
    public function __construct()
    {
        $this->init_hooks();
    }

    private function init_hooks()
    {
        add_action('wpnotif_create_db', array($this, 'wpnotif_create_db'));
        add_action('wp_ajax_wpnotif_get_qrcode', array($this, 'ajax_show_qrcode'));


        add_action('rest_api_init', function () {
            register_rest_route(self::api_namespace, '/connect/', array(
                'methods' => 'GET,POST',
                'callback' => array($this, 'connect'),
            ));
        });

        add_action('rest_api_init', function () {
            register_rest_route(self::api_namespace, '/get_messages/', array(
                'methods' => 'GET,POST',
                'callback' => array($this, 'get_messages'),
            ));
        });
        add_action('rest_api_init', function () {
            register_rest_route(self::api_namespace, '/delete_wa_message/', array(
                'methods' => 'GET,POST',
                'callback' => array($this, 'delete_wa_message'),
            ));
        });

        add_action('rest_api_init', function () {
            register_rest_route(self::api_namespace, '/delete_newsletter/', array(
                'methods' => 'GET,POST',
                'callback' => array($this, 'delete_newsletter'),
            ));
        });

        add_action('rest_api_init', function () {
            register_rest_route(self::api_namespace, '/update_newsletter_status/', array(
                'methods' => 'GET,POST',
                'callback' => array($this, 'update_newsletter_status'),
            ));
        });
    }

    /*
     * 0-> connect
     * 1-> misc
     * */

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function connect()
    {
        $auth = $this->auth(true);

        if (!$auth) return;

        $auth['claimed'] = 1;
        update_option('wpnotif_app_details', $auth);

        $data = array();
        $data['code'] = get_option('wpnotif_purchasecode');
        $data['site'] = wpn_network_home_url();
        $data['site_name'] = get_option('blogname');

        wp_send_json_success($data);
    }

    public function auth($is_connect = false)
    {
        nocache_headers();

        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $auth = !empty($_REQUEST['auth']) ? $_REQUEST['auth'] : '';
        $data = $this->get_sms_creds();

        $code = get_option('wpnotif_purchasecode');

        if (empty($code)) {
            wp_send_json_error(array('message' => esc_attr__('Please enter your purchase code in WPNotif settings!', 'wpnotif')));
            die();
        }

        if (empty($data) ||
            empty($id) || empty($auth)
            ||
            $id != $data['id'] || $auth != $data['auth'] || (!$is_connect && $data['claimed'] == 0)
        ) {
            wp_send_json_error(array('message' => esc_attr__('Invalid Request!', 'wpnotif')));
            die();
        }

        if ($is_connect && $this->is_expired($data)) {
            wp_send_json_error(array('message' => esc_attr__('Please regenerate QR Code from WPNotif settings!', 'wpnotif')));
            die();
        }

        return $data;

    }

    public function get_sms_creds()
    {
        return get_option('wpnotif_app_details', array());
    }

    public function is_expired($data)
    {
        return $data['claimed'] == 0 && (time() - $data['time']) > self::expiry;
    }

    public function get_messages()
    {
        $auth = $this->auth();
        if (!$auth) return;


        $gateway_fields = get_option('wpnotif_sms_phone_app');

        $data = array();
        $data['site_name'] = get_option('blogname');
        $data['frequency'] = $gateway_fields['sending_frequency'];


        $messages = array();

        global $wpdb;
        if (WPNotif_Handler::isWhatsappWebEnabled()) {
            $tb = $wpdb->prefix . 'wpnotif_whatsapp_messages';
            $result = $wpdb->get_results(
                'SELECT * FROM ' . $tb . ' ORDER BY time ASC LIMIT 300'
            );

            $whatsapp_messages = array();
            foreach ($result as $row) {
                $message = array();
                $message['id'] = $row->id;
                $message['phone'] = $row->countrycode . $row->mobileno;
                $message['message'] = $row->message;
                $message['time'] = strtotime($row->time);
                $whatsapp_messages[$row->id] = $message;
            }

            $messages['whatsapp'] = $whatsapp_messages;
            $data['whatsapp_enabled'] = "1";
        } else {
            $data['whatsapp_enabled'] = "0";
        }

        $sms_table = $wpdb->prefix . self::table;
        $result = $wpdb->get_results(
            'SELECT * FROM ' . $sms_table . ' ORDER BY time ASC LIMIT 1500'
        );
        $delete_ids = array();
        $sms = array();

        $details = array();

        foreach ($result as $row) {
            $message = array();
            $detail = unserialize($row->details);

            $message['phone'] = $row->countrycode . $row->mobileno;
            $message['message'] = $row->message;
            $message['time'] = strtotime($row->time);
            $message['detail'] = $detail;

            if (!empty($detail) && is_array($detail)) {
                if (isset($detail['data']['nid'])) {
                    $message['nid'] = $detail['data']['nid'];
                }
                $details[] = $detail;
            }

            $sms[$row->id] = $message;

            $delete_ids[] = $row->id;

        }
        if (!empty($delete_ids)) {
            $ids = implode(',', array_map('absint', $delete_ids));
            $wpdb->query("DELETE FROM $sms_table WHERE id IN($ids)");
        }

        $messages['sms'] = $sms;

        $newsletter_instance = WPNotif_NewsLetter::instance();
        $newsletters = array();
        if (WPNotif_Handler::isGatewayEnabled(901) || WPNotif_Handler::isWhatsappWebEnabled()) {
            foreach ($newsletter_instance->get_newsletters() as $newsletter) {

                $newsletter_details = array();
                $newsletter_details['id'] = $newsletter->id;
                $newsletter_details['name'] = $newsletter->name;
                $newsletter_details['status'] = $newsletter->status;
                $newsletter_details['schedule'] = $newsletter->execution_time;

                $newsletter_details['progress'] = $newsletter->progress;
                $newsletter_details['total_users'] = $newsletter->total_users;
                $newsletters[$newsletter->id] = $newsletter_details;
            }
        }
        $data['newsletter'] = $newsletters;


        $data['messages'] = $messages;

        if (empty($messages)) {
            wp_send_json_error(array('message' => esc_attr__('No Data Found!', 'wpnotif')));
        } else {
            wp_send_json_success($data);
        }
    }

    public function delete_newsletter()
    {
        $auth = $this->auth();
        if (!$auth || empty($_REQUEST['wid'])) return;

        $nid = absint($_REQUEST['wid']);

        if (!empty($nid)) {
            WPNotif_NewsLetter::instance()->delete_newsletter(array($nid));
            wp_send_json_success(array('message' => 'Done'));
        } else {
            wp_send_json_error(array('message' => esc_attr__('Error', 'wpnotif')));
        }
    }

    public function update_newsletter_status()
    {
        $auth = $this->auth();
        if (!$auth || empty($_REQUEST['wid'])) return;

        $nid = absint($_REQUEST['wid']);
        $state = $_REQUEST['state'];

        $newsletter_instance = WPNotif_NewsLetter::instance();

        if (!empty($nid)) {
            $run_newsletter = '';
            if ($state == 'running' || $state == 'resume') {

                $new_instance = true;
                if ($state == 'resume') $new_instance = false;

                $run_newsletter = $newsletter_instance->run_newsletter($nid, $new_instance, false);
            } else {
                if ($state == 'pause') {
                    $change_status = WPNotif_NewsLetter::paused_status;
                } else {
                    $change_status = WPNotif_NewsLetter::stopped_status;
                }
                $newsletter_instance->change_status_newsletter($nid, $change_status);
            }

            if (is_wp_error($run_newsletter)) {
                wp_send_json_error(array('message' => $run_newsletter->get_error_message()));
            }

            wp_send_json_success(array('message' => 'Done'));

        } else {
            wp_send_json_error(array('message' => esc_attr__('Error', 'wpnotif')));
        }
    }

    public function delete_wa_message()
    {
        $auth = $this->auth();
        if (!$auth || empty($_REQUEST['wid'])) return;

        $id = absint($_REQUEST['wid']);
        if (!empty($id) && WPNotif_Handler::isWhatsappWebEnabled()) {
            global $wpdb;
            $tb = $wpdb->prefix . 'wpnotif_whatsapp_messages';

            $wpdb->delete($tb, array(
                'id' => $id,
            ), array(
                    '%d'
                )
            );

            wp_send_json_success(array('message' => 'Done'));
        } else {
            wp_send_json_error(array('message' => esc_attr__('Error', 'wpnotif')));
        }
    }

    public function ajax_show_qrcode()
    {
        check_ajax_referer('wpnotif_qrcode', 'nonce', true);

        if (!current_user_can('manage_options') || !is_user_logged_in()) {
            wp_send_json_error(array('message' => esc_attr__('Error! Unauthorized access', 'wpnotif')));
        }
        nocache_headers();

        WPNotif_Handler::createwhatsapp_db();

        $this->show_qr_code();
        die();
    }

    public function get_data()
    {
        $data = array();
        $data['details'] = $this->create_sms_creds();
        $data['site_url'] = get_site_url();
        $data = base64_encode(json_encode($data));
        return $data;
    }

    public function show_qr_code()
    {

        require_once plugin_dir_path(__DIR__) . '/app/qrcode.php';

        $data = $this->get_data();


        $size = 512;
        $p = -4;

        if (!empty($_REQUEST['preview'])) {
            $size = 256;
            $p = -10;
        }

        $options = array();
        $options['s'] = 'qr';
        $options['h'] = $size;
        $options['w'] = $size;
        $options['p'] = $p;
        $generator = new WAPP_QRCode($data, $options);
        $generator->output_image();

        die();
    }

    public function get_rest_api_url()
    {
        return get_site_url() . '/wp-json/';
    }

    public function create_sms_creds()
    {
        $data = get_option('wpnotif_app_details', array());


        if (empty($data)) {
            $this->init_db();

            $data = array();
            $data['id'] = wp_generate_uuid4();
            $data['auth'] = wp_generate_password(32, true, true);
            $data['claimed'] = 0;
            $data['time'] = time();
            update_option('wpnotif_app_details', $data);
        }

        if ($this->is_expired($data)) {
            delete_option('wpnotif_app_details');
            return $this->create_sms_creds();
        }

        return array(
            'id' => $data['id'],
            'auth' => $data['auth'],
            'end_point' => $this->get_rest_api_url()
        );
    }

    protected function init_db()
    {

        global $wpdb;
        $tb = $wpdb->prefix . self::table;
        if ($wpdb->get_var("SHOW TABLES LIKE '$tb'") != $tb) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $tb (
										id BIGINT UNSIGNED NOT NULL auto_increment,
		          						details TEXT NOT NULL,
		          						countrycode MEDIUMINT(8) NOT NULL,
		          						mobileno VARCHAR(20) NOT NULL,
		          						message TEXT NOT NULL,
		          						time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		          						PRIMARY KEY  (id)
	            						) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta(array($sql));

        }

    }

    public function send_sms($details, $countrycode, $mobile, $messagetemplate, $testCall)
    {
        $gateway_fields = get_option('wpnotif_sms_phone_app');

        global $wpdb;

        $this->init_db();

        $wpdb->insert($wpdb->prefix . self::table, array(
            'countrycode' => $countrycode,
            'mobileno' => $mobile,
            'message' => $messagetemplate,
            'details' => serialize($details)
        ));

        return true;
    }

    public function wpnotif_create_db()
    {
        $this->init_db();
    }
}