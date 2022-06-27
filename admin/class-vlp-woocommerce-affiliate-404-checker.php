<?php 
class VLP_WooCommerce_Affiliate_404_Checker {
    
    public function save_input(){
        $submit = $_POST['link_checker_submit'];
        if(isset( $submit )){
            $check_hours = $_POST['vlp_check_hours'];
            $email = $_POST['vlp_notification_email_address'];
            $checkbox = $_POST['vlp_send_email_notifications'];
            $is_checked = get_option('vlp_send_email_notifications');
            update_option('vlp_check_hours', $check_hours);
            update_option('vlp_notification_email_address', $email);
            update_option('vlp_send_email_notifications', $checkbox);


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
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                }
                if($count = $the_query->found_posts){
                    //user posted variables
                $name = 'Admin';
                $email = get_option('admin_email');
                $message = "There are " . $count . " broken link(s) found! " .get_site_url()."/wp-admin/admin.php?page=vlp_404_link_checker";
    
                //php mailer variables
                $to = get_option('vlp_notification_email_address');
                $subject = "Broken Links found on you affiliate products!";
                $headers = 'From: '. $email . "\r\n" .
                           'Reply-To: ' . $email . "\r\n";
    
                    if($is_checked == 1){
                        $sent = wp_mail($to, $subject, strip_tags($message), $headers);
                        if($sent) {
        
                        }//message sent!
                        else {
        
                        }//message wasn't sent
                    }
    
                } else {
                    // $message_body = "No 404 links found";
                }
            }
        }
    }

    public function vlp_custom_cron_schedule( $schedules ) {
        $check_hours = get_option('vlp_check_hours');
        $hours_to_seconds = $check_hours * 3600;
        //echo $hours_to_seconds;
        $schedules['every_n_hours'] = array(
            'interval' => $hours_to_seconds, // Every N hours
            'display'  => __( 'Every N hours' ),
        );
        return $schedules;
    }


    // create a scheduled event (if it does not exist already)
    function vlp_create_scheduled_event() {
        if( !wp_next_scheduled( 'vlp_cron_hook' ) ) {  
        wp_schedule_event( time(), 'every_n_hours', 'vlp_cron_hook' );  
        }
    }

    
    //create your function, that runs on cron
    function vlp_send_scheduled_email() {
        // do here what needs to be done automatically as per your schedule
        // in this example we're sending an email

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
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
            }
            if($count = $the_query->found_posts){
                //user posted variables
            $name = 'Admin';
            $email = get_option('admin_email');
            $message = "There are " . $count . " broken link(s) found!";

            //php mailer variables
            $to = get_option('vlp_notification_email_address');
            $subject = "Broken Links found on you affiliate products!";
            $headers = 'From: '. $email . "\r\n" .
                       'Reply-To: ' . $email . "\r\n";

                if($is_checked == 1){
                    $sent = wp_mail($to, $subject, strip_tags($message), $headers);
                    if($sent) {
    
                    } //message sent!
                    else {
    
                    } //message wasn't sent
                }

            } else {
                // $message_body = "No 404 links found";
            }
        }
    }
}