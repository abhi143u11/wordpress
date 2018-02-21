<?php
/**
 * Plugin Name:       Ultimate Member - Terms & Conditions
 * Plugin URI:        https://ultimatemember.com/
 * Description:       Add a terms and condition checkbox to your registration forms & require users to agree to your T&Cs before registering on your site.
 * Version:           1.0.0
 * Author:            Ultimate Member
 * Author URI:        https://ultimatemember.com/
 * Text Domain:       um-terms-conditions
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


	define('um_terms_conditions_requires', '1.3.87');

	if ( ! class_exists( 'UM_Dependencies' ) )
	    require_once 'common/class-um-dependencies.php';


	if ( ! function_exists( 'is_php_version_required' ) ) {
	    /**
	     * Check UltimateMember core required version
	     *
	     * @return bool  Larger then required - true | Less than necessary - false
	     */
	    function is_php_version_required( $version ) {
	        return UM_Dependencies::php_version_check( $version );
	    }
	}


	if ( ! is_um_active() ) {

	    /**
	     * Add dependencies admin notice
	     */
	    function um_terms_conditions_dependencies() {
	        echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'um-terms-conditions' ), 'Ultimate Member - Terms & Conditions' ) . '</p></div>';
	    }

	    add_action( 'admin_notices', 'um_terms_conditions_dependencies' );

	} elseif ( ! is_php_version_required( 5.4 ) ) {

	    /**
	     * Add dependencies admin notice
	     */
	    function um_terms_conditions_dependencies() {
	        echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires <strong>PHP 5.4 or better</strong> installed on your server.', 'um-terms-conditions' ), 'Ultimate Member - Terms & Conditions' ) . '</p></div>';
	    }

	    add_action( 'admin_notices', 'um_terms_conditions_dependencies' );

	} elseif ( ! is_um_version_required( um_terms_conditions_requires ) ) {

	    /**
	     * Add dependencies admin notice
	     */
	    function um_terms_conditions_dependencies() {
	        echo '<div class="error"><p>' . sprintf( __( 'The <strong>%s</strong> extension requires a <a href="https://wordpress.org/plugins/ultimate-member">newer version</a> of Ultimate Member to work properly.', 'um-terms-conditions' ), 'Ultimate Member - Terms & Conditions' ) . '</p></div>';
	    }

	    add_action( 'admin_notices', 'um_terms_conditions_dependencies' );

	} else {
			/**
			 * The code that runs during plugin activation.
			 * This action is documented in includes/class-um-terms-conditions-activator.php
			 */
			function activate_um_terms_conditions() {
				require_once plugin_dir_path( __FILE__ ) . 'includes/class-um-terms-conditions-activator.php';
				Um_Terms_Conditions_Activator::activate();
			}

			/**
			 * The code that runs during plugin deactivation.
			 * This action is documented in includes/class-um-terms-conditions-deactivator.php
			 */
			function deactivate_um_terms_conditions() {
				require_once plugin_dir_path( __FILE__ ) . 'includes/class-um-terms-conditions-deactivator.php';
				Um_Terms_Conditions_Deactivator::deactivate();
			}

			register_activation_hook( __FILE__, 'activate_um_terms_conditions' );
			register_deactivation_hook( __FILE__, 'deactivate_um_terms_conditions' );

			/**
			 * The core plugin class that is used to define internationalization,
			 * admin-specific hooks, and public-facing site hooks.
			 */
			require plugin_dir_path( __FILE__ ) . 'includes/class-um-terms-conditions.php';

			/**
			 * Begins execution of the plugin.
			 *
			 * Since everything within the plugin is registered via hooks,
			 * then kicking off the plugin from this point in the file does
			 * not affect the page life cycle.
			 *
			 * @since    1.0.0
			 */
			function run_um_terms_conditions() {
				global $um_terms_conditions;
				$um_terms_conditions = new Um_Terms_Conditions();
				$um_terms_conditions->run();

			}
			run_um_terms_conditions();
	}
