<?php
namespace um_ext\um_terms_conditions\core;

if ( ! defined( 'ABSPATH' ) ) exit;


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
class Terms_Conditions_Public {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
        add_action( 'um_after_form_fields', array( &$this, 'display_option' ) );
        add_action( 'um_submit_form_register', array( &$this, 'agreement_validation' ), 9 );
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

		wp_enqueue_style( 'um-terms-conditions', um_terms_conditions_url . 'assets/css/um-terms-conditions-public.css', array(), um_terms_conditions_version, 'all' );

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

		wp_enqueue_script( 'um-terms-conditions', um_terms_conditions_url . 'assets/js/um-terms-conditions-public.js', array( 'jquery' ), um_terms_conditions_version, false );

	}

	function display_option( $args ) {
		if ( isset( $args['use_terms_conditions'] ) && $args['use_terms_conditions'] == 1 ) {
			require_once um_terms_conditions_path . 'templates/um-terms-conditions-public-display.php';
		}
	}

	function agreement_validation( $args ) {
        $terms_conditions = get_post_meta( $args['form_id'], '_um_register_use_terms_conditions', true );

		if ( $terms_conditions && ! isset( $args['submitted']['use_terms_conditions_agreement'] ) ){
			UM()->form()->add_error('use_terms_conditions_agreement', isset( $args['use_terms_conditions_error_text'] ) ? $args['use_terms_conditions_error_text'] : '' );
		}
	}

}
