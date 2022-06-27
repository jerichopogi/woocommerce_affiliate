<?php
class VLP_Count_Clicks_Product_Frontend{

    function wordpress_frontend_ajaxurl() {
        echo '<script type="text/javascript">
               var ajaxurl = "' . admin_url('admin-ajax.php') . '";
             </script>';
    }

    public function vlp_update_post_meta(){
        $post = get_post();
        $click_count = get_post_meta($post->ID, "_increment_key", true);
        wp_nonce_field( 'increment_my_count', '_nonce_count' );
        ?>
        
        <input type="hidden" id="_count" name="_count" value="<?php echo $click_count ? : 1; ?>" />
        <input type="hidden" id="_postid" name="_postid" value="<?php echo $post->ID; ?>" />
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
}