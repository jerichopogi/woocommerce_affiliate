<?php 

class VLP_WooCommerce_Affiliate_Menu {

    public static function vlp_add_menu(){
        add_menu_page(
            __( 'VLP Woo Affiliate', VLP_WOOCOMMERCE_AFFILIATE_NAME ),
			__( 'VLP Woo Affiliate', VLP_WOOCOMMERCE_AFFILIATE_NAME ),
			'manage_options',
			'vlp_woo_affiliate',
			'VLP_WooCommerce_Affiliate_Menu::vlp_affiliate_logs_page',
			'',
			89,
        );

        add_submenu_page(
            'vlp_woo_affiliate',
            __( 'Affiliate Logs', VLP_WOOCOMMERCE_AFFILIATE_NAME ),
            __( 'Affiliate Logs', VLP_WOOCOMMERCE_AFFILIATE_NAME ),
            'manage_options',
            'vlp_woo_affiliate',
            'vlp_woo_affiliate',
            false,
        );

        add_submenu_page(
            'vlp_woo_affiliate', 
            __( '404 Link Checker', VLP_WOOCOMMERCE_AFFILIATE_NAME ),
            __( '404 Link Checker', VLP_WOOCOMMERCE_AFFILIATE_NAME ),
            'manage_options',
            'vlp_404_link_checker',
            'VLP_WooCommerce_Affiliate_Menu::vlp_link_checker',
            false,
        );
    }

    public static function vlp_affiliate_logs_page(){
        require_once( VLP_WOOCOMMERCE_AFFILIATE_ADMIN_PARTIALS . '/vlp-woocommerce-affiliate-logs-page.php' );
    }

    public static function vlp_link_checker(){
        require_once( VLP_WOOCOMMERCE_AFFILIATE_ADMIN_PARTIALS . '/vlp-woocommerce-affiliate-404-checker.php' ); 
    }

}