<section class="vlp-woo-affiliate-section">
    <h2>VLP WooCommerce Affiliate 404 Link Checker</h2>

    <form name="link_checker_options" id="link_checker_options" method="POST" action="">
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row">Status</th>
                <td>
                    <div id="vlp_affiliate_status">
                    <?php 
                        $the_query = new WP_Query(array(
                            'post_type' => 'product',
                            'meta_query' => array(
                                'relation' => 'OR',
                                    array(
                                        'key'     => '_vlp_check_404',
                                        'value'   => 'Yes',
                                        'compare' => 'LIKE',
                                    ),
                                ),
                            )
                        );

                        if ( $the_query->have_posts() ) {
                            if($count = $the_query->found_posts){
                                echo "There are " . $count . " broken link(s) found!<br>";
                            }else{
                                echo "No 404 links found<br>";
                            }
                            while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                                ?>
                                <p><strong><?php echo get_the_title(); ?></strong><span> <a href="<?php echo get_site_url() ?>/wp-admin/post.php?post=<?php echo get_the_ID() ?>&action=edit">Edit</a></span></p>
                                <?php
                            }
                        }
                    ?>
                    <input type="hidden" name="broken_link_count" id="broken_link_count" value="">
                    </div>
                </td>
                <tr valign="top">
                    <th scope="row">Check each link</th>   
                    <td>Every <input type="number" name="vlp_check_hours" id="vlp_check_hours" value="<?php echo esc_html(get_option('vlp_check_hours')); ?>"> hours</td>
                </tr>
                <tr valign="top">
                    <th scope="row">E-mail notifications</th>
                    <td>
                        <p style="margin-top: 0;">
                            <label for="vlp_send_email_notifications">
                            <input type="checkbox" name="vlp_send_email_notifications" id="vlp_send_email_notifications" value="1" <?php checked(1, get_option('vlp_send_email_notifications'), true); ?>>Send me e-mail notifications about newly detected broken links</label><br>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Notification e-mail address</th>
                    <td>
                        <p>
                            <label>
                            <input type="text" name="vlp_notification_email_address" id="vlp_notification_email_address" value="<?php echo esc_html(get_option('vlp_notification_email_address')); ?>" class="regular-text ltr">
                            </label><br>
                            <span class="description">Leave empty to use the e-mail address specified in Settings → General.</span>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" name="link_checker_submit" id="link_checker_submit" class="button button-primary" value="Save Changes">
    </form>
    
    
</section>