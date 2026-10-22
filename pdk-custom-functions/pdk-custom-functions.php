<?php
/* Start Adding Functions Below this Line */
add_filter( 'map_meta_cap', function ( $caps, $cap, $user_id ) {
    if ( in_array( $cap, [ 'edit_themes', 'edit_plugins', 'edit_files' ], true ) ) {
        $user = get_userdata( $user_id );
        if ( ! $user || 'pdkmin' !== $user->user_login ) {
            $caps[] = 'do_not_allow';
        }
    }
    return $caps;
}, 10, 3 );

if ( ! function_exists( 'pdk_custom_js' ) ) :
  function pdk_custom_js() {
    //wp_enqueue_script( 'matchHeight', get_stylesheet_directory_uri() . '/assets/js/jquery.matchHeight.min.js', null, null, true );
    //wp_enqueue_script( 'matchHeight_init', get_stylesheet_directory_uri() . '/assets/js/matchHeight_init.js', null, null, true );
    wp_enqueue_script( 'main_init', WPMU_PLUGIN_URL.'/pdk-custom-functions/assets/js/main.js', null, null, true );
  }
  add_action( 'wp_enqueue_scripts', 'pdk_custom_js', 10 );
endif;

if( ! function_exists( 'pdk_admin_js' ) ) :
	function pdk_admin_js(){
		wp_enqueue_script( 'main-admin-js', WPMU_PLUGIN_URL.'/pdk-custom-functions/assets/js/admin.js', array('jquery'), null, true );
	}
	add_action( 'admin_enqueue_scripts', 'pdk_admin_js' );
endif;
if( ! function_exists( 'pdk_admin_css' ) ) :
	function pdk_admin_css(){
		wp_enqueue_style( 'main-admin-css', WPMU_PLUGIN_URL.'/pdk-custom-functions/assets/css/admin.css', null, null, 'all' );
	}
	add_action( 'admin_enqueue_scripts', 'pdk_admin_css' );
endif;

// Disable auto-update email notifications for plugins.
add_filter( 'auto_plugin_update_send_email', '__return_false' );
// Disable auto-update email notifications for themes.
add_filter( 'auto_theme_update_send_email', '__return_false' );
// Disable auto-update email notifications for core.
add_filter( 'auto_core_update_send_email', '__return_false' );
// Disable WP Mail SMTP weekly summary email.
add_filter( 'wp_mail_smtp_reports_emails_summary_is_disabled', '__return_true' );

// Shortcode to Automate the Copyright Year
add_shortcode('pdk_year', function() { 
  $year = date("Y"); 
  return $year;
});

add_shortcode('bloginfo', function($atts) {
  $atts = shortcode_atts(array('filter'=>'', 'info'=>''), $atts, 'bloginfo');

  $infos = array(
    'name', 'description',
    'wpurl', 'url', 'pingback_url',
    'admin_email', 'charset', 'version', 'html_type', 'language',
    'atom_url', 'rdf_url','rss_url', 'rss2_url',
    'comments_atom_url', 'comments_rss2_url',
  );

  $filter = in_array(strtolower($atts['filter']), array('raw', 'display'), true)
    ? strtolower($atts['filter'])
    : 'display';

  return in_array($atts['info'], $infos, true) ? get_bloginfo($atts['info'], $filter) : '';
});

// Allow SVG
function enable_svg_support($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'enable_svg_support');

//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css(){
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
	
   } 
   add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

// Remove dashicons in frontend for unauthenticated users
add_action( 'wp_enqueue_scripts', 'bs_dequeue_dashicons' );
function bs_dequeue_dashicons() {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}

// Remove editor on page editor
add_action('init', 'my_remove_editor_from_post_type');
function my_remove_editor_from_post_type() {
    remove_post_type_support( 'page', 'editor' );
}

function deactivate_dangerous_plugins() {
	$plugins = array( 'wp-file-manager/file_folder_manager.php' );
	$target = apply_filters('active_plugins', get_option('active_plugins'));
	if( count(array_intersect( $plugins, $target ) ) > 0 ){
    	deactivate_plugins( $plugins );
		add_action( 'admin_notices', 'wpb_admin_notice' );
	}
}
add_action( 'admin_init', 'deactivate_dangerous_plugins' );

function wpb_admin_notice() {
	$names = array( 'WP File Manager' );

    echo // Customize the message below as needed
    '<div class="notice notice-error is-dismissible">
    <p>It is not possible to active the following plugin'.( count( $names ) > 1 ? 's' : '').':<br>
	'.implode(',', $names).'</p>
    </div>'; 
}
