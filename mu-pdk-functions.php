<?php
/*
* Plugin Name:       PDK Online Succes Custom Functions
* Plugin URI:        https://pdkmarketing.nl
* Version:           1.0.0
* Author:            PDK Online Success
* Author URI:        https://pdkmarketing.nl
*/
/* Start Adding Functions Below this Line */
if (!function_exists('untrailingslashit') || !defined('WP_PLUGIN_DIR')) {
    // WordPress is probably not bootstrapped.
    exit;
}
require WPMU_PLUGIN_DIR.'/pdk-custom-functions/pdk-custom-functions.php';
require WPMU_PLUGIN_DIR.'/pdk-custom-functions/pdk-custom-woocommerce.php';
