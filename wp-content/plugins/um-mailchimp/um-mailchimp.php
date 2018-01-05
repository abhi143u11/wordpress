<?php
/*
Plugin Name: Ultimate Member - MailChimp
Plugin URI: http://ultimatemember.com/
Description: Integrates Ultimate Member with MailChimp newsletters.
Version: 1.1.8
Author: Ultimate Member
Author URI: http://ultimatemember.com/
*/

	require_once(ABSPATH.'wp-admin/includes/plugin.php');
	
	$plugin_data = get_plugin_data( __FILE__ );

	define('um_mailchimp_url',plugin_dir_url(__FILE__ ));
	define('um_mailchimp_path',plugin_dir_path(__FILE__ ));
	define('um_mailchimp_plugin', plugin_basename( __FILE__ ) );
	define('um_mailchimp_extension', $plugin_data['Name'] );
	define('um_mailchimp_version', $plugin_data['Version'] );
	
	define('um_mailchimp_requires', '1.3.32');
	
	$plugin = um_mailchimp_plugin;

	/***
	***	@Init
	***/
	require_once um_mailchimp_path . 'core/um-mailchimp-init.php';
	
	function um_mailchimp_plugins_loaded() {
		load_plugin_textdomain( 'um-mailchimp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	add_action( 'plugins_loaded', 'um_mailchimp_plugins_loaded', 0 );
	
	/* Licensing */

	if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
	}

	
	if( ! function_exists('um_mailchimp_get_licensey_key') ){
		function um_mailchimp_get_licensey_key(){
			global $ultimatemember;
			$license_key = '';

			if( ! is_admin() ) return;

			$um_options = get_option("um_options");

			if( isset( $um_options["um_mailchimp_license_key"] ) ){
				$license_key = trim( $um_options["um_mailchimp_license_key"] );
			}

			return $license_key;
		}
	}

	$edd_params = array( 
				'version' 	=> '1.1.8', 		// current version number
				'license' 	=>  um_mailchimp_get_licensey_key(), 	// license key 
				'item_name' => 'MailChimp', 	// name of this plugin
				'author' 	=> 'Ultimate Member',  // author of this plugin
	);
		
	// setup the updater
	$um_edd_enable = apply_filters("um_enable_edd_sl_plugin_updater", true, __FILE__, $edd_params );
	if( $um_edd_enable ){
		$edd_updater = new EDD_SL_Plugin_Updater( 'https://ultimatemember.com/', __FILE__, $edd_params );
	}

	// add license key field
	add_filter('um_licensed_products_settings', 'um_mailchimp_license_key');
	function um_mailchimp_license_key( $array ) {
		
		if ( !function_exists( 'um_get_option' ) ) return;
		
		$array[] = array(
				'id'       		=> "um_mailchimp_license_key",
				'type'     		=> 'text',
				'title'   		=> "MailChimp License Key",
				'compiler' 		=> true,
				'validate_callback' => "um_mailchimp_validate_license_key",
				'class'			=> 'field-warning',
		);
		
		return $array;

	}
