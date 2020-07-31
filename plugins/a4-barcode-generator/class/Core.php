<?php

namespace UkrSolution\WpBarcodesGenerator;

use UkrSolution\WpBarcodesGenerator\BarcodeTemplates\BarcodeTemplatesController;
use UkrSolution\WpBarcodesGenerator\Enums\CustomFieldPriority;
use UkrSolution\WpBarcodesGenerator\Makers\ManualA4BarcodesMaker;
use UkrSolution\WpBarcodesGenerator\Makers\TestA4BarcodesMaker;

class Core
{
    protected $config;
    protected $customTemplatesController;

    /**
     * class constructor with initialization of properties, creating actions.
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->customTemplatesController = new BarcodeTemplatesController();

        add_action('admin_menu', array($this, 'settingsInit'));
        add_action('admin_menu', array($this, 'addMenuPages'), 9);
        add_action('admin_menu', array($this, 'adminEnqueueScripts'), 9);
        add_filter('plugin_row_meta', array($this, 'pluginRowMeta'), 10, 2);

        // disable checking for plug-in updates
        add_filter('site_transient_update_plugins', array($this, 'disablePluginUpdates'));

        add_action('wp_ajax_a4barcode_get_barcodes_by_values', array($this, 'getBarcodesByValues'));
        add_action('wp_ajax_a4barcode_get_barcodes_test', array($this, 'getBarcodesTest'));
        add_action('wp_ajax_a4barcode_get_latest_version', array($this, 'getLatestVersion'));
        add_action('wp_ajax_a4barcode_get_all_algorithms', array($this, 'getAllAlgorithms'));
        add_action('wp_ajax_a4barcode_get_active_template', array($this, 'getActiveTemplate'));

        $woocommerceModel = new WooCommerce();
        add_action('wp_ajax_a4barcode_get_barcodes', array($woocommerceModel, 'getBarcodes'));
        add_action('wp_ajax_a4barcode_get_categories', array($woocommerceModel, 'getCategories'));
        add_action('wp_ajax_a4barcode_get_attributes', array($woocommerceModel, 'getAttributes'));
        add_action('wp_ajax_a4barcode_check_custom_field', array($woocommerceModel, 'countProductsByCustomField'));

        $formatsModel = new Formats();
        add_action('wp_ajax_a4barcode_delete_format', array($formatsModel, 'deleteFormat'));
        add_action('wp_ajax_a4barcode_save_format', array($formatsModel, 'saveFormat'));
        add_action('wp_ajax_a4barcode_get_all_formats', array($formatsModel, 'getAllFormats'));
        add_action('wp_ajax_a4barcode_get_formats_by_paper', array($formatsModel, 'getFormatsByPaper'));
        add_action('wp_ajax_a4barcode_get_format', array($formatsModel, 'getFormat'));
        add_action('wp_ajax_a4barcode_get_all_paper_formats', array($formatsModel, 'getAllPaperFormats'));
        add_action('wp_ajax_a4barcode_save_paper_format', array($formatsModel, 'savePaperFormat'));
        add_action('wp_ajax_a4barcode_delete_paper_format', array($formatsModel, 'deletePaperFormat'));

        // Custom barcodes templates routing
        add_action('admin_post_a4barcode_template_store', array($this->customTemplatesController, 'store'));
        add_action('admin_post_a4barcode_template_update', array($this->customTemplatesController, 'update'));
        add_action('admin_post_a4barcode_template_delete', array($this->customTemplatesController, 'delete'));
        add_action('admin_post_a4barcode_template_setactive', array($this->customTemplatesController, 'setactive'));
    }

    /**
     * creating plugin menu.
     */
    public function addMenuPages()
    {
        add_menu_page(
            __('Barcode Generator', 'wpbcu-barcode-generator'),
            __('Barcode Generator', 'wpbcu-barcode-generator'),
            'read',
            'wpbcu-barcode-generator',
            array($this, 'emptyPage'),
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAABlBMVEX///8AAABVwtN+AAAAE0lEQVQI12NggIGobfiQkwpEFQAAfwsHv1O1owAAAABJRU5ErkJggg=='
        );

        add_submenu_page(
            'wpbcu-barcode-generator',
            __('Create Manually', 'wpbcu-barcode-generator'),
            __('Create Manually', 'wpbcu-barcode-generator'),
            'read',
            'wpbcu-barcode-generator',
            array($this, 'emptyPage')
        );

        add_submenu_page(
            'wpbcu-barcode-generator',
            __('Import selected items', 'wpbcu-barcode-generator'),
            __('Import selected items', 'wpbcu-barcode-generator'),
            'read',
            'wpbcu-barcode-generator-import',
            array($this, 'emptyPage')
        );

        // If woocommerce active add 'Generate from categories' menu item.
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            add_submenu_page(
                'wpbcu-barcode-generator',
                __('Import categories', 'wpbcu-barcode-generator'),
                __('Import categories', 'wpbcu-barcode-generator'),
                'manage_woocommerce',
                'wpbcu-barcode-generator-generate-from-categories',
                array($this, 'emptyPage')
            );
        }

        add_submenu_page(
            'wpbcu-barcode-generator',
            __('Custom Templates', 'wpbcu-barcode-generator'),
            __('Custom Templates', 'wpbcu-barcode-generator'),
            'read',
            'wpbcu-barcode-templates-edit',
            array($this, 'pageBarcodeTemplates')
        );

        add_submenu_page(
            null,
            __('Custom Templates', 'wpbcu-barcode-generator'),
            __('Custom Templates', 'wpbcu-barcode-generator'),
            'read',
            'wpbcu-barcode-templates-create',
            array($this, 'pageBarcodeTemplatesCreate')
        );

        add_submenu_page(
            'wpbcu-barcode-generator',
            __('FAQ', 'wpbcu-barcode-generator'),
            __('FAQ', 'wpbcu-barcode-generator'),
            'read',
            'wpbcu-barcode-generator-faq',
            array($this, 'pageFAQ')
        );

        // This is the hidden page
        add_submenu_page(
            null,
            __('Barcode-Generator Page', 'wpbcu-barcode-generator'),
            __('Barcode-Generator Page', 'wpbcu-barcode-generator'),
            'read',
            'wpbcu-barcode-generator-print',
            array($this, 'emptyPage')
        );

        // Don't add support submenu if it is PREMIUM plan
        if ('PREMIUM' !== A4B_PLUGIN_PLAN) {
            add_submenu_page(
                'wpbcu-barcode-generator',
                __('Support', 'wpbcu-barcode-generator'),
                __('Support', 'wpbcu-barcode-generator'),
                'read',
                'wpbcu-barcode-generator-support',
                function () {
                    global $wp_version;
                    echo "<iframe src='https://www.ukrsolution.com/ExtensionsSupport/Support?extension=15&version=2.14.4&pversion={$wp_version}' width='100%' height='1200px'></iframe>";
                }
            );
        }
    }

    /**
     * Settings Page.
     *
     */
    public function settingsPage()
    {
        echo '<form method="post" action="options.php">';
        do_settings_sections('wpbcu-barcode-settings' );
        settings_fields( 'wpbcu_barcode_generator_settings' );

        submit_button();
        echo '</form>';
    }

    /**
     * Settings Init.
     *
     */
    public function settingsInit()
    {
        // Settings
        add_submenu_page(
            'wpbcu-barcode-generator',
            __('Settings', 'wpbcu-barcode-generator'),
            __('Settings', 'wpbcu-barcode-generator'),
            'export',
            'wpbcu-barcode-settings',
            array($this, 'settingsPage')
        );

        // Settings section
        add_settings_section('wpbcu_barcode_generator_setting_section', __('Barcode generator settings', 'wpbcu-barcode-generator'), array($this, 'settingSectionCallback'), 'wpbcu-barcode-settings');

        // Settings field
        add_settings_field('wpbcu-barcode-generator_setting_name', __('Add prefix to code:', 'wpbcu-barcode-generator'), array($this, 'settingCallback'), 'wpbcu-barcode-settings', 'wpbcu_barcode_generator_setting_section');
        add_settings_field('wpbcu-barcode-generator_setting_name_priority', __('Variation custom fields priority:', 'wpbcu-barcode-generator'), array($this, 'setting2Callback'), 'wpbcu-barcode-settings', 'wpbcu_barcode_generator_setting_section');

        // Register the settings field
        register_setting('wpbcu_barcode_generator_settings', 'wpbcu_barcode_generator_barcode_prefix');
        register_setting('wpbcu_barcode_generator_settings', 'wpbcu_barcode_generator_custom_fields_priority');
    }

    /**
     * Setting Section Callback.
     *
     */
    public function settingSectionCallback()
    {
        settings_errors();
    }

    /**
     * Setting Callback.
     */
    public function settingCallback()
    {
        echo '<input name="wpbcu_barcode_generator_barcode_prefix" id="wpbcu_barcode_generator_barcode_prefix" type="text" value="'.get_option('wpbcu_barcode_generator_barcode_prefix').'" class="code"  />';
    }
    /**
     * Setting Callback.
     */
    public function setting2Callback()
    {
        echo '<select name="wpbcu_barcode_generator_custom_fields_priority" id="wpbcu_barcode_generator_custom_fields_priority" type="text" class="code">';
        echo sprintf('<option value="%s" %s>%s</option>',
            CustomFieldPriority::VARIATION,
            'variation' === get_option('wpbcu_barcode_generator_custom_fields_priority') ? 'selected' : '',
            __('Variation', 'wpbcu-barcode-generator'));
        echo sprintf('<option value="%s" %s>%s</option>',
            CustomFieldPriority::PRODUCT,
            'product' === get_option('wpbcu_barcode_generator_custom_fields_priority') ? 'selected' : '',
            __('Product','wpbcu-barcode-generator'));
        echo '</select>';
        echo '<p class="description">'.__('If parent product and variation has the same custom field, which should be imported for variation barcodes ?','wpbcu-barcode-generator').'</p>';
    }

    /**
     * return the result of creating barcodes by values.
     */
    public function getBarcodesByValues()
    {
        $validationRules = array(
            'fields' => 'required|array',
            'format' => 'required',
            'hideCode' => 'boolean',
        );

        $data = Validator::create(PostData::get(), $validationRules, true)->validate();

        $barcodesMaker = new ManualA4BarcodesMaker($data);

        a4bJsonResponse($barcodesMaker->make());
    }

    /**
     * Creates list of test barcodes.
     */
    public function getBarcodesTest()
    {
        $barcodesMaker = new TestA4BarcodesMaker();
        $result = $barcodesMaker->make();
        a4bJsonResponse($result);
    }

    /**
     * Add type to wp links.
     *
     * @param $links
     * @param $file
     *
     * @return array
     */
    public function pluginRowMeta($links, $file)
    {
        // Check current plugin is ours
        if (A4B_PLUGIN_BASE_NAME == $file) {
            $rowMeta = ucfirst(strtolower(A4B_PLUGIN_PLAN));
            array_splice($links, 1, 0, $rowMeta);
        }

        return (array) $links;
    }

    /**
     * disable checking for plug-in updates.
     *
     * @return object
     */
    public function disablePluginUpdates($plugins)
    {
        $pluginCurrentPathFile = plugin_basename(__FILE__);
        $startCutPosition = strpos($pluginCurrentPathFile, '/');
        // get plugin folder name
        $pluginDirName = substr($pluginCurrentPathFile, 0, $startCutPosition);
        // if this plug-in is on the list
        if ($plugins && isset($plugins->response) && isset($plugins->response[$pluginDirName.'/barcode_generator.php'])) {
            // remove our plugin from Object
            unset($plugins->response[$pluginDirName.'/barcode_generator.php']);
        }
        // return plugins
        return $plugins;
    }

    /**
     * connect styles and plugin scripts.
     */
    public function adminEnqueueScripts()
    {
        // 
$tmp = time();        wp_enqueue_script("barcode_loader", A4B_PLUGIN_BASE_URL."index.js", array("jquery"), $tmp, true);        wp_enqueue_style("barcode_core_css", A4B_PLUGIN_BASE_URL."public/dist/css/app_demo_2.14.4.css", null, $tmp);        wp_enqueue_style("barcode_vendors_css", A4B_PLUGIN_BASE_URL."public/dist/css/chunk-vendors_demo_2.14.4.css", null, $tmp);$appJsPath = A4B_PLUGIN_BASE_URL."public/dist/js/app_demo_2.14.4.js";
$vendorJsPath = A4B_PLUGIN_BASE_URL."public/dist/js/chunk-vendors_demo_2.14.4.js";


        $active_template = $this->customTemplatesController->getActiveTemplate();

        wp_localize_script('barcode_loader', 'a4bjs', array(
            'pluginUrl' => A4B_PLUGIN_BASE_URL,
            'adminUrl' => get_admin_url(),
            'pluginVersion' => '2.14.4',
            'isWoocommerceActive' => is_plugin_active('woocommerce/woocommerce.php'),
            'appJsPath' => $appJsPath,
            'vendorJsPath' => $vendorJsPath,
            'active_template' => $active_template ? $active_template->template : 'default',
            'activeTemplateData' => $active_template ? $active_template : null,
            'active_template_type' => $active_template && 1 == $active_template->is_base ? 'default' : 'custom',
            'active_template_base_padding' => $active_template ? $active_template->base_padding : '8',
            'rest_root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ));

        // Load language strings into js
        $jsL10n = require_once A4B_PLUGIN_BASE_PATH.'config/jsL10n.php';
        wp_localize_script('barcode_loader', 'a4barcodesL10n', $jsL10n);
    }

    /**
     * data page faq.
     */
    public function pageFAQ()
    {
        echo '<iframe src="https://www.ukrsolution.com/FAQ/getEmbeddedForBarcodeWordpress" width="100%" height="1200px"></iframe>';
    }

    /**
     * method for displaying a blank page, if in javascript errors, then a blank page will be displayed.
     */
    public function emptyPage()
    {
    }

    /**
     * Page for manage custom barcode templates.
     */
    public function pageBarcodeTemplates()
    {
        $this->enqueueTemplatesAssets();
        $this->customTemplatesController->edit();
    }

    /**
     * Page for create custom barcode template.
     */
    public function pageBarcodeTemplatesCreate()
    {
        $this->enqueueTemplatesAssets();
        $this->customTemplatesController->create();
    }

    /**
     * method returns a list of all barcode algorithms.
     */
    public function getAllAlgorithms()
    {
        a4bJsonResponse(array(
            'list' => $this->config['listAlgorithm'],
            'success' => array(),
            'error' => array(),
        ));
    }

    /**
     * load to js latest version  number of plugin.
     *
     * @see wp_version
     */
    public function getLatestVersion()
    {
        global $wp_version;
        $lastReleaseDataFallback = array('url' => '', 'version' => '');

        $lastReleaseDataResponse = wp_remote_get('https://www.ukrsolution.com/CheckUpdates/PrintBarcodeGeneratorForWordpress');
        $lastReleaseData = is_wp_error($lastReleaseDataResponse)
            ? $lastReleaseDataFallback
            : (json_decode(wp_remote_retrieve_body($lastReleaseDataResponse), true) ?: $lastReleaseDataFallback);

        $barcodes = [
            'isLatest' => (int) version_compare('2.14.4', $lastReleaseData['version'], '>='),
            'latest' => $lastReleaseData['version'], // latest version on ukrsolution
            'version' => '2.14.4',
            'downloadUrl' => $lastReleaseData['url'],
            'pluginUrl' => A4B_PLUGIN_BASE_URL,
            'type' => strtolower(A4B_PLUGIN_PLAN),
            'wp_version' => $wp_version,
            'isWoocommerceActive' => is_plugin_active('woocommerce/woocommerce.php'),
            'active_template' => $this->customTemplatesController->getActiveTemplate(),
        ];

        a4bJsonResponse($barcodes);
    }

    /**
     * Get active template.
     */
    public function getActiveTemplate()
    {
        a4bJsonResponse($this->customTemplatesController->getActiveTemplate());
    }

    protected function enqueueTemplatesAssets()
    {
        // Custom js and css
        wp_enqueue_style('barcode_templates', A4B_PLUGIN_BASE_URL.'assets/css/barcode_templates.css', array(), time());
        wp_enqueue_script('barcode_templates', A4B_PLUGIN_BASE_URL.'assets/js/barcode_templates.js', array('jquery'), time(), true);
        wp_localize_script('barcode_templates', 'a4bBarcodeTemplates', array('pluginUrl' => A4B_PLUGIN_BASE_URL));

        // Codemirror js and css
        wp_enqueue_style('codemirror', A4B_PLUGIN_BASE_URL.'assets/js/codemirror/codemirror.css', array(), '5.45.0');
        wp_enqueue_script('codemirror', A4B_PLUGIN_BASE_URL.'assets/js/codemirror/codemirror.js', array(), '5.45.0', true);
        wp_enqueue_script('codemirror_xml', A4B_PLUGIN_BASE_URL.'assets/js/codemirror/mode/xml/xml.js', array('codemirror'), '5.45.0', true);
        wp_enqueue_script('codemirror_js', A4B_PLUGIN_BASE_URL.'assets/js/codemirror/mode/javascript/javascript.js', array('codemirror'), '5.45.0', true);
        wp_enqueue_script('codemirror_css', A4B_PLUGIN_BASE_URL.'assets/js/codemirror/mode/css/css.js', array('codemirror'), '5.45.0', true);
        wp_enqueue_script('codemirror_html', A4B_PLUGIN_BASE_URL.'assets/js/codemirror/mode/htmlmixed/htmlmixed.js', array('codemirror'), '5.45.0', true);
    }
}
