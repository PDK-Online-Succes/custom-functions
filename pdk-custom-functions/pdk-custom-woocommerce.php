<?php
/* Start Adding Functions Below this Line */

// CATEGORY TEXT EDITOR --- START
// IF RankMath not Installed
/*if( is_admin() ) {
	// LETS REMOVE THE HTML FILTERING
	remove_filter( 'pre_term_description', 'wp_filter_kses' );
	remove_filter( 'term_description', 'wp_kses_data' );

	// LETS ADD OUR NEW CAT DESCRIPTION BOX
	add_filter('product_cat_edit_form_fields', 'filter_wordpress_category_editor');
	function filter_wordpress_category_editor($tag) {
	    ?>
	        <tr class="form-field">
	            <th scope="row" valign="top"><label for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
	            <td>
	            <?php
	                $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' );
	          wp_editor(html_entity_decode($tag->description , ENT_QUOTES, 'UTF-8'), 'description1', $settings);
	            ?>
	            <br />
	            <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
	            </td>
	        </tr>
	    <?php
	}

	// HIDE THE DEFAULT CAT DESCRIPTION BOX USING JQUERY
	add_action('admin_head', 'remove_default_category_description');
	function remove_default_category_description()
	{
	    global $current_screen;
	    if ( $current_screen->id == 'edit-product_cat' )
	    {
	    ?>
	        <script type="text/javascript">
	        jQuery(function($) {
	            $('textarea#description').closest('tr.form-field').remove();
	        });
	        </script>
	    <?php
	    }
	}
}*/
// CATEGORY TEXT EDITOR --- END

/********************************************Add a custom filed in the categoy*************************/
add_action( 'init', 'pdk_product_cat_register_meta' );
/**
 * Register short_description product_cat meta.
 * Register the short_description metabox for WooCommerce product categories.
 */
function pdk_product_cat_register_meta() {
	register_meta( 'term', 'short_description', 'pdk_sanitize_short_description' );
}
/**
 * Sanitize the short_description custom meta field.
 * @param  string $short_description The existing short_description field.
 * @return string          The sanitized short_description field
 */
function pdk_sanitize_short_description( $short_description ) {
	return wp_kses_post( $short_description );
}

add_action( 'product_cat_add_form_fields', 'pdk_product_cat_add_short_description_meta' );
/**
 * Add a short_description metabox to the Add New Product Category page.
 * For adding a short_description metabox to the WordPress admin when
 * creating new product categories in WooCommerce.
 */
function pdk_product_cat_add_short_description_meta() {
	wp_nonce_field( basename( __FILE__ ), 'pdk_product_cat_short_description_nonce' );
	?>
	<div class="form-field">
		<label for="pdk-product-cat-short-description"><?php esc_html_e( 'Short description', 'wpm' ); ?></label>
		<textarea name="pdk-product-cat-short-description" id="pdk-product-cat-short-description" rows="5" cols="40"></textarea>
		<!-- <p class="description"><?php esc_html_e( 'Detailed category info to appear below the product list', 'wpm' ); ?></p> -->
	</div>
	<?php
}
add_action( 'product_cat_edit_form_fields', 'pdk_product_cat_edit_short_description_meta', 1 );
/**
 * Add a short_description metabox to the Edit Product Category page.
 * For adding a short_description metabox to the WordPress admin when
 * editing an existing product category in WooCommerce.
 * @param  object $term The existing term object.
 */
function pdk_product_cat_edit_short_description_meta( $term ) {
	$product_cat_short_description = get_term_meta( $term->term_id, 'short_description', true );
	if ( ! $product_cat_short_description ) {
		$product_cat_short_description = '';
	}
	$settings = array( 'textarea_name' => 'pdk-product-cat-short-description' );
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="pdk-product-cat-short-description"><?php esc_html_e( 'Short description', 'wpm' ); ?></label></th>
		<td>
			<?php wp_nonce_field( basename( __FILE__ ), 'pdk_product_cat_short_description_nonce' ); ?>
			<?php wp_editor( pdk_sanitize_short_description( $product_cat_short_description ), 'product_cat_short_description', $settings ); ?>
		</td>
	</tr>
	<?php
}
add_action( 'create_product_cat', 'pdk_product_cat_short_description_meta_save' );
add_action( 'edit_product_cat', 'pdk_product_cat_short_description_meta_save' );
/**
 * Save Product Category short_description meta.
 *
 * Save the product_cat short_description meta POSTed from the
 * edit product_cat page or the add product_cat page.
 *
 * @param  int $term_id The term ID of the term to update.
 */
function pdk_product_cat_short_description_meta_save( $term_id ) {
	if ( ! isset( $_POST['pdk_product_cat_short_description_nonce'] ) || ! wp_verify_nonce( $_POST['pdk_product_cat_short_description_nonce'], basename( __FILE__ ) ) ) {
		return;
	}
	$old_short_description = get_term_meta( $term_id, 'short_description', true );
	$new_short_description = isset( $_POST['pdk-product-cat-short-description'] ) ? $_POST['pdk-product-cat-short-description'] : '';
	if ( $old_short_description && '' === $new_short_description ) {
		delete_term_meta( $term_id, 'short_description' );
	} else if ( $old_short_description !== $new_short_description ) {
		update_term_meta(
			$term_id,
			'short_description',
			pdk_sanitize_short_description( $new_short_description )
		);
	}
}
/**
 * Display short_description meta on Product Category archives.
 */

function display_short_description() {
	if ( ! is_tax( 'product_cat' ) ) {
		return;
	}
	$t_id = get_queried_object()->term_id;
	$short_description = get_term_meta( $t_id, 'short_description', true );
	if ( '' !== $short_description ) {
		return apply_filters( 'the_content', wp_kses_post( $short_description ) );
	}
}

function display_attribute($attr) {
    global $product;

	if ($product->get_attribute("$attr")){ return $product->get_attribute("$attr"); }
}

/**
 * Remove "Category:" by Archive Title dynamic field.
 */
add_filter( 'get_the_archive_title', 'my_theme_archive_title' );
/**
 * Remove archive labels.
 * 
 * @param  string $title Current archive title to be displayed.
 * @return string        Modified archive title to be displayed.
 */
function my_theme_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '&lt;span class="vcard"&gt;' . get_the_author() . '&lt;/span&gt;';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }
 
    return $title;
}

// Disable WooCommerce block styles (back-end).
function themesharbor_disable_woocommerce_block_editor_styles() {
	wp_deregister_style( 'wc-block-editor' );
	wp_deregister_style( 'wc-blocks-style' );
  }
add_action( 'enqueue_block_assets', 'themesharbor_disable_woocommerce_block_editor_styles', 1, 1 );

// disable flexslider js
function flex_dequeue_script() {
    wp_dequeue_script( 'flexslider' );
}
// add_action( 'wp_print_scripts', 'flex_dequeue_script', 100 );

// disable zoom jquery js file
function zoom_dequeue_script() {
    wp_dequeue_script( 'zoom' );
}
// add_action( 'wp_print_scripts', 'zoom_dequeue_script', 100 );

// disable photoswipe js file
function photoswipe_dequeue_script() {
    wp_dequeue_script( 'photoswipe-ui-default' );
}
// add_action( 'wp_print_scripts', 'photoswipe_dequeue_script', 100 );

// Controleer of het adresveld het huisnummer bevat
add_action( 'woocommerce_after_checkout_validation', 'validate_checkout', 10, 2); 
 
function validate_checkout( $data, $errors ){ 
    if (  ! preg_match('/[0-9]/', $data[ 'billing_address_1' ] ) ){ 
        $errors->add( 'address', 'Sorry, maar het door u opgegeven adres bevat geen huisnummer.' ); 
    }
}
