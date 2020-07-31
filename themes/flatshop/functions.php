<?php
/**
 * FlatSHOP theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: flatshop
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the PHP file
 *
 */
 
if(is_admin()) {

	// Requires & Recommend plugins
	if(! class_exists('TGM_Plugin_Activation')) {
		require_once(get_stylesheet_directory() .'/includes/class-tgm-plugin-activation.php');
	}
	require_once(get_stylesheet_directory() .'/includes/tgm-plugin-activation.php');
	
	if(! class_exists('ThemeUpdateChecker')) {
		require_once(get_stylesheet_directory() .'/includes/theme-update-checker.php');
	}
	$theme_updater = new ThemeUpdateChecker('flatshop', 'https://woovina.com/');
	
	// Activate License Key
	if(class_exists('WooVina_Theme_Licenses')) {
		$license = new WooVina_Theme_Licenses('Child Theme', 'FlatSHOP');		
	}
}



/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function flatshop_enqueue_parent_style() {
	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme('WooVina');
	$version = $theme->get('Version');
	// Load the stylesheet
	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('woovina-style'), $version);
	
}
add_action('wp_enqueue_scripts', 'flatshop_enqueue_parent_style');


/**
 * Load the child theme demo CSS file
 *
 */
function flatshop_enqueue_style() {
	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme('FlatSHOP');
	$version = $theme->get('Version');
	
	// Set default CSS
	if(!get_theme_mod('woovina_css_file')) {
		set_theme_mod('woovina_css_file', 'flatshop-01.css');
	}
	// Load custom JS
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom-js.js', array('jquery'), $version, true);
	
	// Load the stylesheet
	wp_enqueue_style('woovina-niche', get_stylesheet_directory_uri() . '/assets/css/' . get_theme_mod('woovina_css_file'), false, $version);
}
add_action('wp_enqueue_scripts', 'flatshop_enqueue_style');


/**
 * Run copyright removal
 *
 * @since 1.4
 */
function flatshop_copyright_removal() {
	$theme 	 			= wp_get_theme();
	$license 			= get_option('edd_license_details');
	$license_details 	= (isset($license) && isset($license['woovina_flatshop'])) ? $license['woovina_flatshop'] : false;
	
	if(! empty($license_details) && is_object($license_details) && true === $license_details->success && 'FlatSHOP Free' != $license_details->item_name) {
		return false;
	}
	
	echo '<div id="woovina-copyright" class="hide-tablet hide-mobile">Powered by <br><a href="https://woovina.com/child-themes/flatshop" title="Free Multipurpose Elementor WooCommerce Theme" target="_blank">FlatSHOP Theme</a></div>';	
}
add_action('woovina_copyright_removal', 'flatshop_copyright_removal');


/**
 * Show activate notice
 *
 * @since 1.4
 */
function flatshop_activate_notice() {
	$license 			= get_option('edd_license_details');
	$license_details 	= (isset($license) && isset($license['woovina_flatshop'])) ? $license['woovina_flatshop'] : false;
	
	if(!class_exists('WooVina_Extra') || (! empty($license_details) && is_object($license_details) && true === $license_details->success)) {
		return false;
	}
	
	?>
    <div id="woovina-admin-notice" class="updated notice is-dismissible" style="padding-top: 10px;">
        <strong><?php _e('Thanks for using FlatSHOP Theme', 'flatshop'); ?></strong>
		<p><?php _e('Please activate your license to get feature updates, premium support and unlimited access to the pro demos!', 'flatshop'); ?>
			<br><?php echo sprintf(
				__('If you don\'t have any license key, you can %1$sget a FREE license here%2$s.', 'flatshop'),
				'<a href="https://woovina.com/child-themes/flatshop?ref=dashboard#child-purchase-now" target="_blank" title="Get your FREE license key!">',
				'</a>'
			); ?></p>
		<p><a class="btn button-primary" href="admin.php?page=woovina-panel-licenses"><?php _e('Activate Now', 'flatshop'); ?></a></p>
    </div>
    <?php
}
add_action('admin_notices', 'flatshop_activate_notice');


/**
 * Show upgrade notice
 *
 * @since 1.4
 */
function flatshop_upgrade_notice() {
	$license 			= get_option('edd_license_details');
	$license_details 	= (isset($license) && isset($license['woovina_flatshop'])) ? $license['woovina_flatshop'] : false;
	
	if(isset($license_details) && is_object($license_details) && true === $license_details->success && 'FlatSHOP Free' == $license_details->item_name)
	{
	?>
    <div id="woovina-admin-notice" class="updated notice is-dismissible" style="padding-top: 10px;">
        <strong><?php _e('You are using the free license of FlatSHOP Theme!', 'flatshop'); ?></strong>
		<p><?php _e('To receive automatic updates, to activate premium plugins you need to have a higher license.', 'flatshop'); ?>
			<br><?php _e('Upgrade your license today at a very discounted price <strong>$39</strong>!', 'flatshop'); ?></p>
		<p><a class="btn button-primary" href="https://woovina.com/my-account/upgrade-account?ref=dashboard" target="_blank"><?php _e('Upgrade License', 'flatshop'); ?></a> <a class="btn button-secondary" href="https://woovina.com/child-themes/flatshop?ref=dashboard#child-purchase-now" target="_blank"><?php _e('Plans & Pricing', 'flatshop'); ?></a></p>
    </div>
    <?php
	}
}
add_action('admin_notices', 'flatshop_upgrade_notice');


/**
 * Show renewal notice
 *
 * @since 1.4
 */
function flatshop_renewal_notice() {
	$license 			= get_option('edd_license_details');
	$license_details 	= (isset($license) && isset($license['woovina_flatshop'])) ? $license['woovina_flatshop'] : false;
	
	$now        	= current_time('timestamp');
	$expire_date	= isset($license_details->expires) && trim($license_details->expires) != '' ? $license_details->expires : '';
	$expiration 	= strtotime($expire_date, current_time('timestamp'));
	
	if(! empty($license_details) && is_object($license_details) && true === $license_details->success && $expiration < $now)
	{
	?>
    <div id="woovina-admin-notice" class="updated notice is-dismissible" style="padding-top: 10px;">
        <strong><?php _e('Your license has expired!', 'flatshop'); ?></strong>
		<p><?php _e('Whoops! It looks like your FlatSHOP license has expired! That means that you will not receive automatic updates.', 'flatshop'); ?>
			<br><?php _e('You can renew your license by click on the below button!', 'flatshop'); ?></p>
		<p><a class="btn button-primary" href="https://woovina.com/my-account/renew-membership?ref=dashboard" target="_blank"><?php _e('Renew License Now', 'flatshop'); ?></a></p>
    </div>
    <?php
	}
}
add_action('admin_notices', 'flatshop_renewal_notice');


/**
 * Get Flatshop demos.
 * 
 * @since   1.4
 */
function flatshop_get_demos() {	
	$theme_url  = get_stylesheet_directory_uri() . '/demos/';
	$theme_dir  = get_stylesheet_directory() . '/demos/';
	
	$data = array(
		'Demo 01 - FlatSHOP Theme' => array(
			'categories'        => array('FREE'),
			'demo_class'		=> 'free-demo',
			'xml_file'     		=> $theme_dir . 'flatshop-01/contents.xml',
			'theme_settings' 	=> $theme_url . 'flatshop-01/customizer.json',
			'widgets_file'  	=> $theme_url . 'flatshop-01/widgets.wie',
			'preview_image'		=> $theme_url . 'flatshop-01/flatshop-01.jpg',
			'form_file'  		=> $theme_url . 'flatshop-01/form.json',
			'preview_url'		=> 'https://flatshop.woovina.net/demo-01/',
			'home_title'  		=> 'Home',
			'blog_title'  		=> 'Blog',
			'posts_to_show'  	=> '12',
			'elementor_width'  	=> '1200',
			'css_file'			=> 'flatshop-01.css',
			'woo_image_size'	=> '600',
			'woo_thumb_size'	=> '300',
			'woo_crop_width'	=> '4',
			'woo_crop_height'	=> '3',
			'required_plugins'  => array(
				'free' => array(
					array(
						'slug' 		=> 'elementor',
						'init' 		=> 'elementor/elementor.php',
						'name' 		=> 'Elementor',
					),
					array(
						'slug' 		=> 'woocommerce',
						'init' 		=> 'woocommerce/woocommerce.php',
						'name' 		=> 'WooCommerce',
					),
					array(
						'slug'  	=> 'wpforms-lite',
						'init'  	=> 'wpforms-lite/wpforms.php',
						'name'  	=> 'WPForms',
					),
				),
				'premium' => array(						
					array(
						'slug' 		=> 'woovina-preloader',
						'init'  	=> 'woovina-preloader/woovina-preloader.php',
						'name' 		=> 'WooVina Preloader',
					),
					array(
						'slug' 		=> 'woovina-product-sharing',
						'init' 		=> 'woovina-product-sharing/woovina-product-sharing.php',
						'name' 		=> 'WooVina Product Sharing',
					),
					array(
						'slug' 		=> 'woovina-popup-login',
						'init' 		=> 'woovina-popup-login/woovina-popup-login.php',
						'name' 		=> 'WooVina Popup Login',
					),
					array(
						'slug' 		=> 'woovina-woo-popup',
						'init' 		=> 'woovina-woo-popup/woovina-woo-popup.php',
						'name' 		=> 'WooVina Woo Popup',
					),
					array(
						'slug'  	=> 'woovina-variation-swatches',
						'init'  	=> 'woovina-variation-swatches/woovina-variation-swatches.php',
						'name'  	=> 'WooVina Variation Swatches',
					),
				),
			),
		),

		'Demo 02 - FlatSHOP Theme' => array(
			'categories'        => array('FREE'),
			'demo_class'		=> 'free-demo',
			'xml_file'     		=> $theme_dir . 'flatshop-02/contents.xml',
			'theme_settings' 	=> $theme_url . 'flatshop-02/customizer.json',
			'widgets_file'  	=> $theme_url . 'flatshop-02/widgets.wie',
			'preview_image'		=> $theme_url . 'flatshop-02/flatshop-02.jpg',
			'form_file'  		=> $theme_url . 'flatshop-02/form.json',
			'preview_url'		=> 'https://flatshop.woovina.net/demo-02/',
			'home_title'  		=> 'Home',
			'blog_title'  		=> 'Blog',
			'posts_to_show'  	=> '12',
			'elementor_width'  	=> '1200',
			'css_file'			=> 'flatshop-02.css',
			'woo_image_size'	=> '600',
			'woo_thumb_size'	=> '300',
			'woo_crop_width'	=> '4',
			'woo_crop_height'	=> '3',
			'required_plugins'  => array(
				'free' => array(
					array(
						'slug' 		=> 'elementor',
						'init' 		=> 'elementor/elementor.php',
						'name' 		=> 'Elementor',
					),
					array(
						'slug' 		=> 'woocommerce',
						'init' 		=> 'woocommerce/woocommerce.php',
						'name' 		=> 'WooCommerce',
					),
					array(
						'slug'  	=> 'wpforms-lite',
						'init'  	=> 'wpforms-lite/wpforms.php',
						'name'  	=> 'WPForms',
					),
				),
				'premium' => array(	
					array(
						'slug' 		=> 'woovina-preloader',
						'init'  	=> 'woovina-preloader/woovina-preloader.php',
						'name' 		=> 'WooVina Preloader',
					),
					array(
						'slug' 		=> 'woovina-product-sharing',
						'init' 		=> 'woovina-product-sharing/woovina-product-sharing.php',
						'name' 		=> 'WooVina Product Sharing',
					),
					array(
						'slug' 		=> 'woovina-popup-login',
						'init' 		=> 'woovina-popup-login/woovina-popup-login.php',
						'name' 		=> 'WooVina Popup Login',
					),
					array(
						'slug' 		=> 'woovina-woo-popup',
						'init' 		=> 'woovina-woo-popup/woovina-woo-popup.php',
						'name' 		=> 'WooVina Woo Popup',
					),
					array(
						'slug'  	=> 'woovina-variation-swatches',
						'init'  	=> 'woovina-variation-swatches/woovina-variation-swatches.php',
						'name'  	=> 'WooVina Variation Swatches',
					),
				),
			),
		),

		'Demo 03 - FlatSHOP Theme' => array(
			'categories'        => array('PRO'),
			'demo_class'		=> 'pro-demo',
			'xml_file'     		=> $theme_dir . 'flatshop-03/contents.xml',
			'theme_settings' 	=> $theme_url . 'flatshop-03/customizer.json',
			'widgets_file'  	=> $theme_url . 'flatshop-03/widgets.wie',
			'preview_image'		=> $theme_url . 'flatshop-03/flatshop-03.jpg',
			'form_file'  		=> $theme_url . 'flatshop-03/form.json',
			'preview_url'		=> 'https://flatshop.woovina.net/demo-03/',
			'home_title'  		=> 'Home',
			'blog_title'  		=> 'Blog',
			'posts_to_show'  	=> '12',
			'elementor_width'  	=> '1200',
			'css_file'			=> 'flatshop-03.css',
			'woo_image_size'	=> '600',
			'woo_thumb_size'	=> '300',
			'woo_crop_width'	=> '4',
			'woo_crop_height'	=> '3',
			'required_plugins'  => array(
				'free' => array(
					array(
						'slug' 		=> 'elementor',
						'init' 		=> 'elementor/elementor.php',
						'name' 		=> 'Elementor',
					),
					array(
						'slug' 		=> 'woocommerce',
						'init' 		=> 'woocommerce/woocommerce.php',
						'name' 		=> 'WooCommerce',
					),
					array(
						'slug'  	=> 'wpforms-lite',
						'init'  	=> 'wpforms-lite/wpforms.php',
						'name'  	=> 'WPForms',
					),
				),
				'premium' => array(
					array(
						'slug' 		=> 'woovina-preloader',
						'init'  	=> 'woovina-preloader/woovina-preloader.php',
						'name' 		=> 'WooVina Preloader',
					),
					array(
						'slug' 		=> 'woovina-product-sharing',
						'init' 		=> 'woovina-product-sharing/woovina-product-sharing.php',
						'name' 		=> 'WooVina Product Sharing',
					),
					array(
						'slug' 		=> 'woovina-popup-login',
						'init' 		=> 'woovina-popup-login/woovina-popup-login.php',
						'name' 		=> 'WooVina Popup Login',
					),
					array(
						'slug' 		=> 'woovina-woo-popup',
						'init' 		=> 'woovina-woo-popup/woovina-woo-popup.php',
						'name' 		=> 'WooVina Woo Popup',
					),
					array(
						'slug'  	=> 'woovina-variation-swatches',
						'init'  	=> 'woovina-variation-swatches/woovina-variation-swatches.php',
						'name'  	=> 'WooVina Variation Swatches',
					),
				),
			),
		),

		'Demo 04 - FlatSHOP Theme' => array(
			'categories'        => array('PRO'),
			'demo_class'		=> 'pro-demo',
			'xml_file'     		=> $theme_dir . 'flatshop-04/contents.xml',
			'theme_settings' 	=> $theme_url . 'flatshop-04/customizer.json',
			'widgets_file'  	=> $theme_url . 'flatshop-04/widgets.wie',
			'preview_image'		=> $theme_url . 'flatshop-04/flatshop-04.jpg',
			'form_file'  		=> $theme_url . 'flatshop-04/form.json',
			'preview_url'		=> 'https://flatshop.woovina.net/demo-04/',
			'home_title'  		=> 'Home',
			'blog_title'  		=> 'Blog',
			'posts_to_show'  	=> '12',
			'elementor_width'  	=> '1200',
			'css_file'			=> 'flatshop-04.css',
			'woo_image_size'	=> '600',
			'woo_thumb_size'	=> '300',
			'woo_crop_width'	=> '4',
			'woo_crop_height'	=> '3',
			'required_plugins'  => array(
				'free' => array(
					array(
						'slug' 		=> 'elementor',
						'init' 		=> 'elementor/elementor.php',
						'name' 		=> 'Elementor',
					),
					array(
						'slug' 		=> 'woocommerce',
						'init' 		=> 'woocommerce/woocommerce.php',
						'name' 		=> 'WooCommerce',
					),
					array(
						'slug'  	=> 'wpforms-lite',
						'init'  	=> 'wpforms-lite/wpforms.php',
						'name'  	=> 'WPForms',
					),
				),
				'premium' => array(
					array(
						'slug' 		=> 'woovina-preloader',
						'init'  	=> 'woovina-preloader/woovina-preloader.php',
						'name' 		=> 'WooVina Preloader',
					),
					array(
						'slug' 		=> 'woovina-product-sharing',
						'init' 		=> 'woovina-product-sharing/woovina-product-sharing.php',
						'name' 		=> 'WooVina Product Sharing',
					),
					array(
						'slug' 		=> 'woovina-popup-login',
						'init' 		=> 'woovina-popup-login/woovina-popup-login.php',
						'name' 		=> 'WooVina Popup Login',
					),
					array(
						'slug' 		=> 'woovina-woo-popup',
						'init' 		=> 'woovina-woo-popup/woovina-woo-popup.php',
						'name' 		=> 'WooVina Woo Popup',
					),
					array(
						'slug'  	=> 'woovina-variation-swatches',
						'init'  	=> 'woovina-variation-swatches/woovina-variation-swatches.php',
						'name'  	=> 'WooVina Variation Swatches',
					),
				),
			),
		),

		'Demo 05 - FlatSHOP Theme' => array(
			'categories'        => array('PRO'),
			'demo_class'		=> 'pro-demo',
			'xml_file'     		=> $theme_dir . 'flatshop-05/contents.xml',
			'theme_settings' 	=> $theme_url . 'flatshop-05/customizer.json',
			'widgets_file'  	=> $theme_url . 'flatshop-05/widgets.wie',
			'preview_image'		=> $theme_url . 'flatshop-05/flatshop-05.jpg',
			'form_file'  		=> $theme_url . 'flatshop-05/form.json',
			'preview_url'		=> 'https://flatshop.woovina.net/demo-05/',
			'home_title'  		=> 'Home',
			'blog_title'  		=> 'Blog',
			'posts_to_show'  	=> '12',
			'elementor_width'  	=> '1200',
			'css_file'			=> 'flatshop-05.css',
			'woo_image_size'	=> '600',
			'woo_thumb_size'	=> '300',
			'woo_crop_width'	=> '4',
			'woo_crop_height'	=> '3',
			'required_plugins'  => array(
				'free' => array(
					array(
						'slug' 		=> 'elementor',
						'init' 		=> 'elementor/elementor.php',
						'name' 		=> 'Elementor',
					),
					array(
						'slug' 		=> 'woocommerce',
						'init' 		=> 'woocommerce/woocommerce.php',
						'name' 		=> 'WooCommerce',
					),
					array(
						'slug'  	=> 'wpforms-lite',
						'init'  	=> 'wpforms-lite/wpforms.php',
						'name'  	=> 'WPForms',
					),
				),
				'premium' => array(	
					array(
						'slug' 		=> 'woovina-preloader',
						'init'  	=> 'woovina-preloader/woovina-preloader.php',
						'name' 		=> 'WooVina Preloader',
					),
					array(
						'slug' 		=> 'woovina-product-sharing',
						'init' 		=> 'woovina-product-sharing/woovina-product-sharing.php',
						'name' 		=> 'WooVina Product Sharing',
					),
					array(
						'slug' 		=> 'woovina-popup-login',
						'init' 		=> 'woovina-popup-login/woovina-popup-login.php',
						'name' 		=> 'WooVina Popup Login',
					),
					array(
						'slug' 		=> 'woovina-woo-popup',
						'init' 		=> 'woovina-woo-popup/woovina-woo-popup.php',
						'name' 		=> 'WooVina Woo Popup',
					),
					array(
						'slug'  	=> 'woovina-variation-swatches',
						'init'  	=> 'woovina-variation-swatches/woovina-variation-swatches.php',
						'name'  	=> 'WooVina Variation Swatches',
					),
				),
			),
		),

		'Demo 06 - FlatSHOP Theme' => array(
			'categories'        => array('PRO'),
			'demo_class'		=> 'pro-demo',
			'xml_file'     		=> $theme_dir . 'flatshop-06/contents.xml',
			'theme_settings' 	=> $theme_url . 'flatshop-06/customizer.json',
			'widgets_file'  	=> $theme_url . 'flatshop-06/widgets.wie',
			'preview_image'		=> $theme_url . 'flatshop-06/flatshop-06.jpg',
			'form_file'  		=> $theme_url . 'flatshop-06/form.json',
			'preview_url'		=> 'https://flatshop.woovina.net/demo-06/',
			'home_title'  		=> 'Home',
			'blog_title'  		=> 'Blog',
			'posts_to_show'  	=> '12',
			'elementor_width'  	=> '1200',
			'css_file'			=> 'flatshop-06.css',
			'woo_image_size'	=> '600',
			'woo_thumb_size'	=> '300',
			'woo_crop_width'	=> '4',
			'woo_crop_height'	=> '3',
			'required_plugins'  => array(
				'free' => array(
					array(
						'slug' 		=> 'elementor',
						'init' 		=> 'elementor/elementor.php',
						'name' 		=> 'Elementor',
					),
					array(
						'slug' 		=> 'woocommerce',
						'init' 		=> 'woocommerce/woocommerce.php',
						'name' 		=> 'WooCommerce',
					),
					array(
						'slug'  	=> 'wpforms-lite',
						'init'  	=> 'wpforms-lite/wpforms.php',
						'name'  	=> 'WPForms',
					),
				),
				'premium' => array(
					array(
						'slug' 		=> 'woovina-preloader',
						'init'  	=> 'woovina-preloader/woovina-preloader.php',
						'name' 		=> 'WooVina Preloader',
					),
					array(
						'slug' 		=> 'woovina-product-sharing',
						'init' 		=> 'woovina-product-sharing/woovina-product-sharing.php',
						'name' 		=> 'WooVina Product Sharing',
					),
					array(
						'slug' 		=> 'woovina-popup-login',
						'init' 		=> 'woovina-popup-login/woovina-popup-login.php',
						'name' 		=> 'WooVina Popup Login',
					),
					array(
						'slug' 		=> 'woovina-woo-popup',
						'init' 		=> 'woovina-woo-popup/woovina-woo-popup.php',
						'name' 		=> 'WooVina Woo Popup',
					),
					array(
						'slug'  	=> 'woovina-variation-swatches',
						'init'  	=> 'woovina-variation-swatches/woovina-variation-swatches.php',
						'name'  	=> 'WooVina Variation Swatches',
					),
				),
			),
		),

		'Demo 07 - FlatSHOP Theme' => array(
			'categories'        => array('PRO'),
			'demo_class'		=> 'pro-demo',
			'xml_file'     		=> $theme_dir . 'flatshop-07/contents.xml',
			'theme_settings' 	=> $theme_url . 'flatshop-07/customizer.json',
			'widgets_file'  	=> $theme_url . 'flatshop-07/widgets.wie',
			'preview_image'		=> $theme_url . 'flatshop-07/flatshop-07.jpg',
			'form_file'  		=> $theme_url . 'flatshop-07/form.json',
			'preview_url'		=> 'https://flatshop.woovina.net/demo-07/',
			'home_title'  		=> 'Home',
			'blog_title'  		=> 'Blog',
			'posts_to_show'  	=> '12',
			'elementor_width'  	=> '1200',
			'css_file'			=> 'flatshop-07.css',
			'woo_image_size'	=> '600',
			'woo_thumb_size'	=> '300',
			'woo_crop_width'	=> '4',
			'woo_crop_height'	=> '3',
			'required_plugins'  => array(
				'free' => array(
					array(
						'slug' 		=> 'elementor',
						'init' 		=> 'elementor/elementor.php',
						'name' 		=> 'Elementor',
					),
					array(
						'slug' 		=> 'woocommerce',
						'init' 		=> 'woocommerce/woocommerce.php',
						'name' 		=> 'WooCommerce',
					),
					array(
						'slug'  	=> 'wpforms-lite',
						'init'  	=> 'wpforms-lite/wpforms.php',
						'name'  	=> 'WPForms',
					),
				),
				'premium' => array(
					array(
						'slug' 		=> 'woovina-preloader',
						'init'  	=> 'woovina-preloader/woovina-preloader.php',
						'name' 		=> 'WooVina Preloader',
					),
					array(
						'slug' 		=> 'woovina-product-sharing',
						'init' 		=> 'woovina-product-sharing/woovina-product-sharing.php',
						'name' 		=> 'WooVina Product Sharing',
					),
					array(
						'slug' 		=> 'woovina-popup-login',
						'init' 		=> 'woovina-popup-login/woovina-popup-login.php',
						'name' 		=> 'WooVina Popup Login',
					),
					array(
						'slug' 		=> 'woovina-woo-popup',
						'init' 		=> 'woovina-woo-popup/woovina-woo-popup.php',
						'name' 		=> 'WooVina Woo Popup',
					),
					array(
						'slug'  	=> 'woovina-variation-swatches',
						'init'  	=> 'woovina-variation-swatches/woovina-variation-swatches.php',
						'name'  	=> 'WooVina Variation Swatches',
					),
				),
			),
		),

		'Demo 08 - FlatSHOP Theme' => array(
			'categories'        => array('PRO'),
			'demo_class'		=> 'pro-demo',
			'xml_file'     		=> $theme_dir . 'flatshop-08/contents.xml',
			'theme_settings' 	=> $theme_url . 'flatshop-08/customizer.json',
			'widgets_file'  	=> $theme_url . 'flatshop-08/widgets.wie',
			'preview_image'		=> $theme_url . 'flatshop-08/flatshop-08.jpg',
			'form_file'  		=> $theme_url . 'flatshop-08/form.json',
			'preview_url'		=> 'https://flatshop.woovina.net/demo-08/',
			'home_title'  		=> 'Home',
			'blog_title'  		=> 'Blog',
			'posts_to_show'  	=> '12',
			'elementor_width'  	=> '1200',
			'css_file'			=> 'flatshop-08.css',
			'woo_image_size'	=> '600',
			'woo_thumb_size'	=> '300',
			'woo_crop_width'	=> '4',
			'woo_crop_height'	=> '3',
			'required_plugins'  => array(
				'free' => array(
					array(
						'slug' 		=> 'elementor',
						'init' 		=> 'elementor/elementor.php',
						'name' 		=> 'Elementor',
					),
					array(
						'slug' 		=> 'woocommerce',
						'init' 		=> 'woocommerce/woocommerce.php',
						'name' 		=> 'WooCommerce',
					),
					array(
						'slug'  	=> 'wpforms-lite',
						'init'  	=> 'wpforms-lite/wpforms.php',
						'name'  	=> 'WPForms',
					),
				),
				'premium' => array(
					array(
						'slug' 		=> 'woovina-preloader',
						'init'  	=> 'woovina-preloader/woovina-preloader.php',
						'name' 		=> 'WooVina Preloader',
					),
					array(
						'slug' 		=> 'woovina-product-sharing',
						'init' 		=> 'woovina-product-sharing/woovina-product-sharing.php',
						'name' 		=> 'WooVina Product Sharing',
					),
					array(
						'slug' 		=> 'woovina-popup-login',
						'init' 		=> 'woovina-popup-login/woovina-popup-login.php',
						'name' 		=> 'WooVina Popup Login',
					),
					array(
						'slug' 		=> 'woovina-woo-popup',
						'init' 		=> 'woovina-woo-popup/woovina-woo-popup.php',
						'name' 		=> 'WooVina Woo Popup',
					),
					array(
						'slug'  	=> 'woovina-variation-swatches',
						'init'  	=> 'woovina-variation-swatches/woovina-variation-swatches.php',
						'name'  	=> 'WooVina Variation Swatches',
					),
				),
			),
		),
	);
	
	// Return
	return $data;
}
add_filter('wvn_demos_data', 'flatshop_get_demos');
add_filter( 'wcfm_is_allow_policies_under_order_details', '__return_false' ); //to remove store policies from email and order


//to redirect vendor directly to vendor dashboard on log in.
add_filter('woocommerce_login_redirect', 'login_redirect', 10, 2);
function login_redirect( $redirect_to, $user ) {
if (class_exists('WCV_Vendors') && WCV_Vendors::is_vendor( $user->ID ) ) {
$redirect_to = get_permalink( get_option( 'wcvendors_vendor_dashboard_page_id' ) );
}

return $redirect_to; 
}
//to redirect vendor directly to vendor dashboard on log in.
//
//changing order status completed to picked up from seller
add_filter( 'wc_order_statuses', 'ts_rename_order_status_msg', 20, 1 );
function ts_rename_order_status_msg( $order_statuses ) {
$order_statuses['wc-completed'] = _x( 'Picked up from seller', 'Order status', 'woocommerce' );
return $order_statuses;
}
//changing order status completed to picked up from seller
//adding shipped
function register_shipped_order_status() {
    register_post_status( 'wc-shipped', array(
        'label'                     => 'Shipped',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_my_new_order_statuses' );

function register_my_new_order_statuses() {
    register_post_status( 'wc-shipped', array(
        'label'                     => _x( 'Shipped', 'Order status', 'woocommerce' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped<span class="count">(%s)</span>', 'woocommerce' )
    ) );
}

add_filter( 'wc_order_statuses', 'my_new_wc_order_statuses' );

// Register in wc_order_statuses.
function my_new_wc_order_statuses( $order_statuses ) {
    $order_statuses['wc-shipped'] = _x( 'Shipped', 'Order status', 'woocommerce' );

    return $order_statuses;
}
//custom order status shipped


//Cancel button will be shown for some order status
add_filter( 'woocommerce_valid_order_statuses_for_cancel', 'custom_valid_order_statuses_for_cancel', 10, 2 );
function custom_valid_order_statuses_for_cancel( $statuses, $order ){

    // Set HERE the order statuses where you want the cancel button to appear
    $custom_statuses    = array('pending', 'processing', 'on-hold');

}
//Cancel button will be shown for some order status
//to hide shop logo
add_filter( 'wcfmmp_is_allow_sold_by_logo', '__return_false' );
//refund button will be not shown untill order status is delieverd
add_filter( 'wcfm_refund_disable_order_status', function( $order_statuses ) {
  return array( 'failed', 'cancelled', 'refunded', 'pending', 'on-hold', 'processing','completed','shipped');
}, 50 );
//add all shipping method when free shipping is avilable
function my_hide_shipping_when_free_is_available( $rates ) {
	$free = array();

	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}

	return ! empty( $free ) ? $free : $rates;
}

add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );

////if payed vendor cannot change back to payment pending
add_filter( 'wcfm_allowed_order_status', function( $order_statuses, $order_id ) {
	if( wcfm_is_vendor() && isset( $order_statuses['wc-pending'] ) ) {
		$order = wc_get_order( $order_id );
		if( is_a( $order, 'WC_Order' ) ) {
			$status = $order->get_status();
			if( $status != 'pending' ) {
				unset( $order_statuses['wc-pending'] );
			}
		}
	}
	return $order_statuses;
}, 50, 2 );

add_filter( 'wcfm_is_allow_customer_add_note', '__return_false' );

add_action('wp_logout','my_logout_redirect');
 
function my_logout_redirect() {
  wp_redirect( 'https://inditribes.co.in' );
  exit;
}


add_filter( 'wcfm_logout_url', function( $logout_url ) {
	if( wcfm_is_vendor() ) {
		$logout_url = 'https://inditribes.co.in/shop-2';
  }
  return $logout_url;
});

add_filter( 'wcfm_is_allow_change_main_order_status_on_all_item_shipped', '__return_true' );

add_filter( 'wpseo_breadcrumb_single_link' ,'wpseo_remove_breadcrumb_link', 10 ,2);
function wpseo_remove_breadcrumb_link( $link_output , $link ){
$text_to_remove = 'Shop';

if( $link['text'] == $text_to_remove ) {
$link_output = "";
}

return $link_output;
}

////Adding GST in checkout
add_filter( 'woocommerce_countries_inc_tax_or_vat', function () {
  return __( 'inc. GST', 'woocommerce' );
});

add_filter( 'woocommerce_countries_ex_tax_or_vat', function () {
  return __( 'exc. GST', 'woocommerce' );
});
////Adding GST in checkout
//adding GST IN TOTAL LABEL
add_filter( 'woocommerce_countries_tax_or_vat', 'woo_custom_tax_label' );
function woo_custom_tax_label( $label ) {
    return 'GST';
}