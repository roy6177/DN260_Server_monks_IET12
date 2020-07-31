<?php
/*
Plugin Name: Print Barcodes on Label Sheets for Wordpress & WooCommerce (demo)
Description: Create and Print barcodes on label sheets using a printer. Best tool to make inventory of your stock.
Text Domain: wpbcu-barcode-generator
Version: 2.14.4
Author: UkrSolution
Plugin URI: http://www.ukrsolution.com
Author URI: http://www.ukrsolution.com
License: GPL2
WC requires at least: 2.0.0
WC tested up to: 3.9.0
*/

use UkrSolution\WpBarcodesGenerator\Api\CustomTemplatesRestController;
use UkrSolution\WpBarcodesGenerator\Api\LabelsFormatsRestController;
use UkrSolution\WpBarcodesGenerator\Api\PaperFormatsRestController;

//Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

//// DO NOT DELETE
//// On activation errors debug mode
//define('temp_file', ABSPATH.'/_temp_out.txt');
//add_action("activated_plugin", "activation_handler1");
//function activation_handler1(){
//    $cont = ob_get_contents();
//    if(!empty($cont)) file_put_contents(temp_file, $cont );
//}
//
//add_action( "pre_current_active_plugins", "pre_output1" );
//function pre_output1($action){
//    if(is_admin() && file_exists(temp_file))
//    {
//        $cont= file_get_contents(temp_file);
//        if(!empty($cont))
//        {
//            echo '<div class="error"> Error Message:' . $cont . '</div>';
//            @unlink(temp_file);
//        }
//    }
//}

// Include wp plugins functions for next check of other plugin version installation.
include_once ABSPATH.'wp-admin/includes/plugin.php';
// Check for other active versions before activate this one.
if (!is_plugin_active(plugin_basename(__FILE__))) {
    $activePlugins = is_multisite() ? get_site_option('active_sitewide_plugins') : get_option('active_plugins');
    // Iterate over active plugins to search for another our plugin version.
    foreach ($activePlugins as $sitewideActivePlugin => $activePlugin) {
        // Another version of plugin is active, so prevent activation of this one.
        if (false !== strpos($activePlugin, 'a4-barcode-generator') || false !== strpos($sitewideActivePlugin, 'a4-barcode-generator')) {
            die(__('Please deactivate any other version of the plugin before you can activate this one.', 'wpbcu-barcode-generator'));
        }
    }
}

// Check php version in not lower 5.6.0
if (version_compare(phpversion(), '5.6.0', '<')) {
    $errorMsg = '<span>'.'Barcode Generator - The minimum PHP version required for this plugin is 5.6.0.  Please update PHP version.'.'</span>';

    // Check for error_scrape page
    if (isset($_GET['action']) && 'error_scrape' == $_GET['action']) {
        die('<span>'.$errorMsg.'</span>');
    } else {
        trigger_error($errorMsg, E_USER_ERROR);
    }
}

//Define A4B_PLUGIN_BASE_NAME - current plugin name.
define('A4B_PLUGIN_BASE_NAME', plugin_basename(__FILE__));
// Define constants.
require_once plugin_dir_path(__FILE__).'constants.php';

// If it is print page - load page with minimal styles which invokes 'Print' dialog, stop the script.
if (isset($_GET['page']) && 'wpbcu-barcode-generator-print' == $_GET['page']) {
    // Get active template
    global $wpdb;
    $table = $wpdb->prefix.'a4barcode_custom_templates';
    $chosenTemplateRow = $wpdb->get_row("SELECT * FROM `{$table}` WHERE `is_active` = 1");
    $chosenTemplateRow->template = str_replace("\n", '', $chosenTemplateRow->template);

    include_once A4B_PLUGIN_BASE_PATH.'templates/printpage.php';
    die();
}

// Autoloader.
require_once __DIR__.'/lib/autoload.php';

// Define global functions.
require_once __DIR__.'/class/functions.php';

// Create tables.
register_activation_hook(__FILE__, function ($network_wide) {
    UkrSolution\WpBarcodesGenerator\Database::setupTables($network_wide);
});

// Creating table whenever a new blog is created.
add_action('wpmu_new_blog', function ($blog_id, $user_id, $domain, $path, $site_id, $meta) {
    // If plugin is active for network, create tables for new blog.
    if (is_plugin_active_for_network(A4B_PLUGIN_BASE_NAME)) {
        switch_to_blog($blog_id);
        UkrSolution\WpBarcodesGenerator\Database::createTables();
        restore_current_blog();
    }
}, 10, 6);

// Load text domain
add_action('plugins_loaded', function () {
    $pluginRelPath = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('wpbcu-barcode-generator', false, $pluginRelPath);
});

// Show notices on the page.
add_action('admin_notices', function () {
    a4bShowNotices();
});

// If php version is 7.1 and less, set more precision for fractional numbers. Don't know why.
add_action('admin_init', function () {
    // If it is our ajax call, turn on high precision
    if (defined('DOING_AJAX') && isset($_POST['action']) && false !== strpos($_POST['action'], 'a4barcode_')) {
        // if php version is 7.1 and less, set more precision for fractional numbers
        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set('precision', 17);
            ini_set('serialize_precision', -1);
        }
    }
}, 10, 2);

// Init old post data global variable.
add_action('admin_init', 'a4bOldPostInitialization');

// Add REST API for custom templates
add_action('rest_api_init', function () {
    (new CustomTemplatesRestController())->register_routes();
    (new PaperFormatsRestController())->register_routes();
    (new LabelsFormatsRestController())->register_routes();
});

// Start plugin
UkrSolution\WpBarcodesGenerator\PostData::set($_POST);
$a4bcConfig = require A4B_PLUGIN_BASE_PATH.'config/config.php';
new UkrSolution\WpBarcodesGenerator\Core($a4bcConfig);
