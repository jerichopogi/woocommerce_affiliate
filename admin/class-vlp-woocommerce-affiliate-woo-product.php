<?php 

class VLP_WooCommerce_Affiliate_Woo_Product {

    
    /**
     * Adds the meta box container.
     */
    public function vlp_add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'product' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'vlp_affiliate_link',
                __( 'VLP Affiliate Product', 'textdomain' ),
                array( $this, 'vlp_render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }


     /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function vlp_render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'vlp_inner_affiliate_field', 'vlp_inner_affiliate_field_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta( $post->ID, '_vlp_woo_affiliate', true );
        $affiliate_clicks = get_post_meta( $post->ID, '_vlp_affiliate_click', true);

        $array = @get_headers($value);
        $string = $array[0];

        $current_count = get_post_meta($post->ID, '_increment_key', true) ? : 1;
        wp_nonce_field( 'increment_my_count', '_nonce_count' );
 
        // Display the form, using the current value.
        ?>
      
        <input type="hidden" id="_count" name="_count" value="<?php echo $current_count ? : 1; ?>" />
        <input type="hidden" id="_postid" name="_postid" value="<?php echo $post->ID; ?>" />

        <label class="vlp-woo-affiliate-label" for="vlp_woo_affiliate">
            <?php _e( 'Affiliate Product Link', 'textdomain' ); ?>
        </label>
        <input type="hidden" id="_vlp_check_404" name="_vlp_check_404" value="<?php if(strpos($string, "200")) { echo 'No'; } else if(empty($value)){ echo "Not Affilliate"; } else { echo 'Yes'; } ?>"/>
        <input placeholder="https://affiliate-product-link.com" class="vlp-woo-affiliate-field" type="text" id="_vlp_woo_affiliate" name="_vlp_woo_affiliate" value="<?php echo esc_attr( $value ); ?>" />
        <small>Add <code>_vlp_woo_affiliate</code> to post custom field dynamic tags on Elementor</small>
        <?php
    }

    public function myUpdateCount() {
        if ( ! check_admin_referer( 'increment_my_count', '_nonce_count' ) ) die();
        $postid = isset( $_POST['postid'] ) && $_POST['postid'] ? $_POST['postid'] : null;
        if ( ! $postid ) die();
        if ( ! current_user_can('edit_posts', $postid) ) die();
        $new_count = isset( $_POST['_count'] ) && $_POST['_count'] ? $_POST['_count'] + 1 : 1;
        update_post_meta($postid, '_increment_key', $new_count);
        die( (string)$new_count );
    }


     /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function vlp_save_affiliate_link( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['vlp_inner_affiliate_field_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['vlp_inner_affiliate_field_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'vlp_inner_affiliate_field' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $vlp_woo_affiliate = sanitize_text_field( $_POST['_vlp_woo_affiliate'] );
        $vlp_check_404 = sanitize_text_field( $_POST['_vlp_check_404'] );
 
        // Update the meta field.
        update_post_meta( $post_id, '_vlp_woo_affiliate', $vlp_woo_affiliate );
        update_post_meta( $post_id, '_vlp_check_404', $vlp_check_404 );
    }

    public function vlp_affiliate_title_search( $where, &$wp_query )
    {
        global $wpdb;
        if ( $vlp_affiliate_title = $wp_query->get( 'vlp_affiliate_title' ) ) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( $wpdb->esc_like( $vlp_affiliate_title ) ) . '%\'';
        }
        return $where;
    }

}