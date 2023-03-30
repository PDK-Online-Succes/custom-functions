<?php
/* Start Adding Functions Below this Line */

if ( ! function_exists( 'pdk_custom_js' ) ) :
  function pdk_custom_js() {
    //wp_enqueue_script( 'matchHeight', get_stylesheet_directory_uri() . '/assets/js/jquery.matchHeight.min.js', null, null, true );
    //wp_enqueue_script( 'matchHeight_init', get_stylesheet_directory_uri() . '/assets/js/matchHeight_init.js', null, null, true );
    wp_enqueue_script( 'main_init', WPMU_PLUGIN_URL.'/pdk-custom-functions/assets/js/main.js', null, null, true );
  }
  add_action( 'wp_enqueue_scripts', 'pdk_custom_js', 10 );
endif;

// Shortcode to Automate the Copyright Year
function pdk_copyright_year_shortcode() { 
      $year = date("Y"); 
return $year;
}
add_shortcode('pdk_year', 'pdk_copyright_year_shortcode');

// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }
  $filetype = wp_check_filetype( $filename, $mimes );
  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];
}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

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
