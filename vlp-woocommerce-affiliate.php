<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://vlpmedia.agency
 * @since             1.0.0
 * @package           Vlp_Woocommerce_Affiliate
 *
 * @wordpress-plugin
 * Plugin Name:       VLP WooCommerce Affiliate
 * Plugin URI:        https://vlpmedia.agency
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            VLP Media
 * Author URI:        https://vlpmedia.agency
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vlp-woocommerce-affiliate
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VLP_WOOCOMMERCE_AFFILIATE_VERSION', '1.0.0' );
define( 'VLP_WOOCOMMERCE_AFFILIATE_PLUGIN', 'vlp-woocommerce-affiliate' );
define( 'VLP_WOOCOMMERCE_AFFILIATE_NAME', 'VLP WooCommerce Affiliate' );
define( 'VLP_WOOCOMMERCE_AFFILIATE_DIR', __DIR__ );
define( 'VLP_WOOCOMMERCE_AFFILIATE_PATH', dirname( __FILE__ ) );
define( 'VLP_WOOCOMMERCE_AFFILIATE_PUBLIC_DIR', __DIR__ . 'public/' );
define( 'VLP_WOOCOMMERCE_AFFILIATE_ADMIN_DIR', __DIR__ . 'admin/' );
define( 'VLP_WOOCOMMERCE_AFFILIATE_PUBLIC_URI', plugin_dir_url( __FILE__ ) . 'public/' );
define( 'VLP_WOOCOMMERCE_AFFILIATE_ADMIN_URI', plugin_dir_url( __FILE__ ) . 'admin/' );
define( 'VLP_WOOCOMMERCE_AFFILIATE_PUBLIC_PARTIALS', __DIR__ . '/public/partials/' );
define( 'VLP_WOOCOMMERCE_AFFILIATE_ADMIN_PARTIALS', __DIR__ . '/admin/partials/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vlp-woocommerce-affiliate-activator.php
 */
function activate_vlp_woocommerce_affiliate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vlp-woocommerce-affiliate-activator.php';
	Vlp_Woocommerce_Affiliate_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vlp-woocommerce-affiliate-deactivator.php
 */
function deactivate_vlp_woocommerce_affiliate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vlp-woocommerce-affiliate-deactivator.php';
	Vlp_Woocommerce_Affiliate_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vlp_woocommerce_affiliate' );
register_deactivation_hook( __FILE__, 'deactivate_vlp_woocommerce_affiliate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vlp-woocommerce-affiliate.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_vlp_woocommerce_affiliate() {

	$plugin = new Vlp_Woocommerce_Affiliate();
	$plugin->run();

}
run_vlp_woocommerce_affiliate();
