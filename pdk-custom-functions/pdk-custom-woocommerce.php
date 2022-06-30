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
add_action( 'product_cat_edit_form_fields', 'pdk_product_cat_edit_short_description_meta' );
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
