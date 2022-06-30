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
