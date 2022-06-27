<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://vlpmedia.agency
 * @since      1.0.0
 *
 * @package    Vlp_Woocommerce_Affiliate
 * @subpackage Vlp_Woocommerce_Affiliate/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Vlp_Woocommerce_Affiliate
 * @subpackage Vlp_Woocommerce_Affiliate/includes
 * @author     VLP Media <author@vlpmedia.agency>
 */
class Vlp_Woocommerce_Affiliate {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Vlp_Woocommerce_Affiliate_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'VLP_WOOCOMMERCE_AFFILIATE_VERSION' ) ) {
			$this->version = VLP_WOOCOMMERCE_AFFILIATE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'vlp-woocommerce-affiliate';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Vlp_Woocommerce_Affiliate_Loader. Orchestrates the hooks of the plugin.
	 * - Vlp_Woocommerce_Affiliate_i18n. Defines internationalization functionality.
	 * - Vlp_Woocommerce_Affiliate_Admin. Defines all hooks for the admin area.
	 * - Vlp_Woocommerce_Affiliate_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vlp-woocommerce-affiliate-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vlp-woocommerce-affiliate-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vlp-woocommerce-affiliate-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vlp-woocommerce-affiliate-woo-product.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vlp-woocommerce-affiliate-404-checker.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vlp-woocommerce-affiliate-menu.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-vlp-woocommerce-affiliate-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-count-clicks-product-front-end.php';

		$this->loader = new Vlp_Woocommerce_Affiliate_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Vlp_Woocommerce_Affiliate_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Vlp_Woocommerce_Affiliate_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Vlp_Woocommerce_Affiliate_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$vlp_woo_affiliate_product = new VLP_WooCommerce_Affiliate_Woo_Product();
		$this->loader->add_action( 'add_meta_boxes', $vlp_woo_affiliate_product, 'vlp_add_meta_box' );
		$this->loader->add_action( 'save_post', $vlp_woo_affiliate_product, 'vlp_save_affiliate_link', 10, 1 );
		$this->loader->add_action( 'wp_ajax_update_my_count', $vlp_woo_affiliate_product, 'myUpdateCount' );
		$this->loader->add_filter( 'posts_where', $vlp_woo_affiliate_product, 'vlp_affiliate_title_search', 10, 2 );

		$vlp_woo_affiliate_404_checker = new VLP_WooCommerce_Affiliate_404_Checker();
		$this->loader->add_action( 'init', $vlp_woo_affiliate_404_checker, 'save_input' );
		$this->loader->add_action( 'wp', $vlp_woo_affiliate_404_checker, 'vlp_custom_cron_job' );
		$this->loader->add_filter( 'cron_schedules', $vlp_woo_affiliate_404_checker, 'vlp_custom_cron_schedule' );
		$this->loader->add_action( 'wp', $vlp_woo_affiliate_404_checker, 'vlp_create_scheduled_event' );
		$this->loader->add_action( 'vlp_cron_hook', $vlp_woo_affiliate_404_checker, 'vlp_send_scheduled_email' );
		
		$vlp_woo_affiliate_menu = new VLP_WooCommerce_Affiliate_Menu();
		$this->loader->add_action( 'admin_menu', $vlp_woo_affiliate_menu, 'vlp_add_menu' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Vlp_Woocommerce_Affiliate_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$vlp_count_clicks_product_frontend = new VLP_Count_Clicks_Product_Frontend();
		$this->loader->add_action( 'wp', $vlp_count_clicks_product_frontend, 'vlp_update_post_meta' );
		$this->loader->add_action( 'wp_head', $vlp_count_clicks_product_frontend, 'wordpress_frontend_ajaxurl' );
		$this->loader->add_action( 'wp_ajax_my_action', $vlp_count_clicks_product_frontend, 'myUpdateCount' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Vlp_Woocommerce_Affiliate_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
