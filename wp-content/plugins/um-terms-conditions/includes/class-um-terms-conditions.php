<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ultimatemember.com/
 * @since      1.0.0
 *
 * @package    Um_Terms_Conditions
 * @subpackage Um_Terms_Conditions/includes
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
 * @package    Um_Terms_Conditions
 * @subpackage Um_Terms_Conditions/includes
 * @author     Ultimate Member <support@ultimatemember.com>
 */
class Um_Terms_Conditions {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Um_Terms_Conditions_Loader    $loader    Maintains and registers all hooks for the plugin.
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

	public $page_id = '';

	public $settings = array();

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

		$this->plugin_name = 'um-terms-conditions';
		$this->version = '1.0.0';
		
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_settings();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Um_Terms_Conditions_Loader. Orchestrates the hooks of the plugin.
	 * - Um_Terms_Conditions_i18n. Defines internationalization functionality.
	 * - Um_Terms_Conditions_Admin. Defines all hooks for the admin area.
	 * - Um_Terms_Conditions_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-um-terms-conditions-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-um-terms-conditions-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-um-terms-conditions-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-um-terms-conditions-public.php';

		$this->loader = new Um_Terms_Conditions_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Um_Terms_Conditions_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Um_Terms_Conditions_i18n();

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

		$plugin_admin = new Um_Terms_Conditions_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'load-post.php', $plugin_admin, 'add_metabox' );
		$this->loader->add_action( 'load-post-new.php', $plugin_admin, 'add_metabox' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Um_Terms_Conditions_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'um_after_form_fields', $plugin_public, 'display_optin' );
		$this->loader->add_action( 'um_submit_form_register', $plugin_public, 'agreement_validation', 9 );
		$this->loader->add_action( 'um_terms_conditions_set_id', $plugin_public, 'set_post_id');
		
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
	 * @return    Um_Terms_Conditions_Loader    Orchestrates the hooks of the plugin.
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

	public function define_settings(){
		global $um_terms_conditions;

		
		$this->settings['_um_register_use_terms_conditions_toggle_show'] = __('Show Terms','um-terms-conditions');
		
		$this->settings['_um_register_use_terms_conditions_toggle_hide'] = __('Hide Terms','um-terms-conditions');
		
		$this->settings['_um_register_use_terms_conditions_agreement'] = __('Please confirm that you agree to our terms & conditions','um-terms-conditions');
		
		$this->settings['_um_register_use_terms_conditions_error_text'] = __('You must agree to our terms & conditions','um-terms-conditions');

	}

	public function get_field_value( $key ){
		global $ultimatemember, $um_terms_conditions;
		$default_value = '';
		
		if( isset( $this->settings[ $key ] ) && ! empty( $this->settings[ $key ]  ) ){
				$default_value = $this->settings[ $key ] ;
		}

		if( is_admin() && isset( $_REQUEST['post'] ) ){
			$um_terms_conditions->page_id = $_REQUEST['post'];
		}

		$value = get_post_meta( $um_terms_conditions->page_id, $key, true );

		if( empty( $value ) ){
			$value = $default_value;
		}
			
		return $value;
	}

}
