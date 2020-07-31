<?php
/**
 * Class to handles icons functionality
 *
 * @author    Dzikri Aziz, joshuairl https://github.com/kucrut/wp-menu-icons
 * @copyright Copyright (c) 2014, Dzikri Aziz, https://kucrut.org/
 * @license   GPLv2
 */
final class WE_Menu_Icons {

	const VERSION = '1.0.0';

	/**
	 * Holds plugin data
	 * 
	 */
	protected static $data;


	/**
	 * Get plugin data
	 * 
	 */
	public static function get($name = null) {
		if(is_null($name)) {
			return self::$data;
		}

		if(isset(self::$data[ $name ])) {
			return self::$data[ $name ];
		}

		return null;
	}


	/**
	 * Load main directory
	 *
	 * 1. Load translation
	 * 2. Set plugin data (directory and URL paths)
	 * 3. Attach plugin initialization at we_icon_picker_init hook
	 * 
	 */
	public static function _load() {
		

		self::$data = array(
			'dir'   => WE_PATH .'includes/menu-icons/',
			'url'   => WE_URL .'includes/menu-icons/',
			'types' => array(),
		);

		// Load Icon Picker.
		if(! class_exists('WE_Icon_Selector')) {
			
			$ip_file = self::$data['dir'] . 'includes/library/icon-selector/icon-selector.php';

			if(file_exists($ip_file)) {
				require_once $ip_file;
			} else {
				add_action('admin_notices', array(__CLASS__, '_notice_missing_icon_picker'));
				return;
			}
		}
		WE_Icon_Selector::instance();

		require_once self::$data['dir'] . 'includes/library/compat.php';
		require_once self::$data['dir'] . 'includes/library/functions.php';
		require_once self::$data['dir'] . 'includes/meta.php';

		WE_Menu_Icons_Meta::init();

		add_action('we_icon_picker_init', array(__CLASS__, '_init'), 9);
	}


	/**
	 * Initialize
	 *
	 * 1. Get registered types from Icon Picker
	 * 2. Load settings
	 * 3. Load front-end functionalities
	 * 
	 */
	public static function _init() {
		/**
		 * Allow themes/plugins to add/remove icon types
		 * 
		 */
		self::$data['types'] = apply_filters(
			'we_menu_icons_types',
			WE_Icon_Picker_Types_Registry::instance()->types
		);

		// Nothing to do if there are no icon types registered.
		if(empty(self::$data['types'])) {
			if(WP_DEBUG) {
				trigger_error(esc_html__('Menu Icons: No registered icon types found.', 'woovina-extra'));
			}

			return;
		}

		// Load settings.
		require_once self::$data['dir'] . 'includes/settings.php';
		WE_Menu_Icons_Settings::init();

		// Load front-end functionalities.
		if(! is_admin()) {
			require_once self::$data['dir'] . '/includes/front.php';
			WE_Menu_Icons_Front_End::init();
		}

		do_action('we_menu_icons_loaded');
	}


	/**
	 * Display notice about missing Icon Picker
	 * 
	 */
	public static function _notice_missing_icon_picker() {
		?>
		<div class="error">
			<p><?php esc_html_e('Please activate Icon Picker first.', 'woovina-extra'); ?></p>
		</div>
		<?php
	}
}
add_action('init', array('WE_Menu_Icons', '_load'));
