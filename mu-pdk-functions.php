<?php
/*
* Plugin Name:       PDK Online Succes Custom Functions
* Plugin URI:        https://pdk.nl
* Description:       Custom functions for PDK Online Succes
* Version:           1.0.0
* Author:            PDK Online Success
* Author URI:        https://pdk.nl
*/
/* Start Adding Functions Below this Line */
if (!function_exists('untrailingslashit') || !defined('WP_PLUGIN_DIR')) {
    // WordPress is probably not bootstrapped.
    exit;
}
require WPMU_PLUGIN_DIR.'/pdk-custom-functions/pdk-custom-functions.php';
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	require WPMU_PLUGIN_DIR.'/pdk-custom-functions/pdk-custom-woocommerce.php';
}