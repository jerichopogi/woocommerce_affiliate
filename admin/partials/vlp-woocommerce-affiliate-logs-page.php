<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://vlpmedia.agency
 * @since      1.0.0
 *
 * @package    Vlp_Woocommerce_Affiliate
 * @subpackage Vlp_Woocommerce_Affiliate/admin/partials
 */

$site_url = get_site_url();
$vlp_search_product = $_POST['vlp_search_product'];
$vlp_check_404 = $_POST['check_404'];
?>

<section class="vlp-woo-affiliate-section">
    <h2>VLP WooCommerce Affiliate Logs</h2>
    <form class="vlp-woo-affiliate-log-form" method="POST" action="<?php echo $site_url; ?>/wp-admin/admin.php?page=vlp_woo_affiliate">
        <input type="text" placeholder="Search" name="vlp_search_product" >
        <input class="button button-primary" name="search_affiliate" type="submit" value="Search">
        <label for="check_404">Show all 404 Links</label>
        <input type="checkbox" onChange="this.form.submit()" name="check_404" value="Yes" <?php if(isset($_POST['check_404'])) echo "checked='checked'"; ?>  />
    </form>
    <table class="vlp-woo-affiliate-table">
        <tr class="vlp-tbl-row-heading">
            <th>Product</th>
            <th>Link</th>
            <th>Clicks</th>
            <th>404</th>
        </tr>
        <?php
            $the_query = new WP_Query(array(
                'post_type' => 'product',
                'vlp_affiliate_title' => $vlp_search_product,
                'meta_query' => array(
                    'relation' => 'OR',
                        array(
                            'key'     => '_vlp_check_404',
                            'value'   => $vlp_check_404,
                            'compare' => 'LIKE',
                        )
                    ),
                )
            );
            
            if ( $the_query->have_posts() ) {
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    $product_name = get_the_title();
                    $product_id = get_the_ID();
                    $check_if_404 = get_post_meta(get_the_ID(), '_vlp_check_404', true);
                    $product_link = get_post_meta(get_the_ID(), '_vlp_woo_affiliate', true);
                    $click_count = get_post_meta(get_the_ID(), '_increment_key', true);
                    ?>
                    <tr class="vlp-tbl-row">
                    <td><?php echo $product_name; ?></td>
                    <td>
                        <?php
                        if(!empty($product_link)){
                            echo "<a target='_blank' href=" . $product_link . ">See Link</a> ";
                            echo "<a href='".get_site_url()."/wp-admin/post.php?post=".get_the_ID()."&action=edit' id='vlp_" . $product_id . "'class='vlp-edit-affiliate-link'>Edit Link</a>";
                        }
                        else{
                            echo "Not Affiliate";
                        }
                        ?>
                    </td>
                    <td><?php echo $click_count; ?></td>
                    <td><?php echo $check_if_404; ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td>No Products Found</td>
                </tr>
                <?php 
            }
            /* Restore original Post Data */
            wp_reset_postdata();
        ?>
    </table>
</section>