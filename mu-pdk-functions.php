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

require WPMU_PLUGIN_DIR.'/pdk-custom-functions/libraries/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/PDK-Online-Succes/custom-functions/',
	__FILE__,
	'custom-functions'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('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');