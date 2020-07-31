<?php

/**
 * Plugin plan.
 */
define('A4B_PLUGIN_PLAN', 'DEMO');

// Check SSL Mode
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $_SERVER['HTTPS'] = 'on';
}

/*
 * Url to plugin root.
 */
define('A4B_PLUGIN_BASE_URL', plugin_dir_url(__FILE__));

/*
 * Path to plugin root.
 */
define('A4B_PLUGIN_BASE_PATH', plugin_dir_path(__FILE__));

/*
 * Url to site root.
 */
define('A4B_SITE_BASE_URL', get_site_url());
