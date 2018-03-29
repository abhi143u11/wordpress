<?php
namespace um_ext\um_online\core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Online_Shortcode {

	function __construct() {
	
		add_shortcode( 'ultimatemember_online', array( &$this, 'ultimatemember_online' ) );

	}
	
	function setup( &$user ) {
		
		$ID = $user;
		$user = array();
		
		$user['ID'] = $ID;
		
		um_fetch_user( $ID );
		
		$user['url'] = um_user_profile_url();
		$user['name'] = um_user('display_name');
		$user['role'] = get_user_meta( $ID, 'role', true );

		return $user;
	}

	/***
	***	@Shortcode
	***/
	function ultimatemember_online( $args = array() ) {
		$defaults = array(
			'max' => 11,
			'role' => 'all'
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		ob_start();
		
		$template = null;
		$online = UM()->Online_API()->get_users();
		
		if ( $online ) {
			$template = um_online_path . 'templates/online.php';
		} else {
			$template = um_online_path . 'templates/nobody.php';
		}
		
		include $template;

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

}