<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://vlpmedia.agency
 * @since      1.0.0
 *
 * @package    Vlp_Woocommerce_Affiliate
 * @subpackage Vlp_Woocommerce_Affiliate/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Vlp_Woocommerce_Affiliate
 * @subpackage Vlp_Woocommerce_Affiliate/includes
 * @author     VLP Media <author@vlpmedia.agency>
 */
class Vlp_Woocommerce_Affiliate_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'vlp-woocommerce-affiliate',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
