<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ultimatemember.com/
 * @since      1.0.0
 *
 * @package    Um_Terms_Conditions
 * @subpackage Um_Terms_Conditions/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Um_Terms_Conditions
 * @subpackage Um_Terms_Conditions/public
 * @author     Ultimate Member <support@ultimatemember.com>
 */
class Um_Terms_Conditions_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Um_Terms_Conditions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Um_Terms_Conditions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/um-terms-conditions-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Um_Terms_Conditions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Um_Terms_Conditions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/um-terms-conditions-public.js', array( 'jquery' ), $this->version, false );

	}

	function display_optin( $args ){

		if( isset( $args['use_terms_conditions'] ) && 
			! empty( $args['use_terms_conditions'] ) && 
			$args['use_terms_conditions'] == 1 ){
		

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/um-terms-conditions-public-display.php';
		}
	}

	function agreement_validation( $args ){
		global $ultimatemember;
		if( ! isset( $args['submitted']['use_terms_conditions_agreement'] ) ){
			$ultimatemember->form->add_error('use_terms_conditions_agreement', $args['use_terms_conditions_error_text'] );
		}
	}

	public function set_post_id( $args ){
		global $um_terms_conditions;
		$um_terms_conditions->page_id = $args['form_id'];
	}

}
