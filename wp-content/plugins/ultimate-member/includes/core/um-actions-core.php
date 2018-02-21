<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

	/**
	 * Processes the requests of UM actions
	 */
	add_action('init','um_action_request_process', 10);
	function um_action_request_process(){
		if ( is_admin() ) return false;
		if ( ! is_user_logged_in() ) return false;
		if ( ! isset( $_REQUEST['um_action'] ) ) return false;
		if ( isset( $_REQUEST['uid'] ) && ! UM()->user()->user_exists_by_id( $_REQUEST['uid'] ) ) return false;
		
		if ( isset( $_REQUEST['uid'] ) ) {
			if ( is_super_admin( $_REQUEST['uid'] ) )
				wp_die( __( 'Super administrators can not be modified.','ultimate-member' ) );
		}

		if ( isset( $_REQUEST['um_action'] ) && $_REQUEST['um_action'] != "edit" && ! current_user_can( 'edit_users' ) ){
						wp_die( __( 'You do not have enough permissions to do that.','ultimate-member') );
		}
		
		if ( isset($_REQUEST['uid'])){
			$uid = $_REQUEST['uid'];
		}
		
		switch( $_REQUEST['um_action'] ) {
		
			default:
				$uid = ( isset( $_REQUEST['uid'] ) ) ? $_REQUEST['uid'] : 0;
				do_action('um_action_user_request_hook', $_REQUEST['um_action'], $uid);
				break;
		
			case 'edit':
				UM()->fields()->editing = true;
				if ( !um_can_edit_my_profile() ) {
					$url = um_edit_my_profile_cancel_uri();
					exit(  wp_redirect( $url ) ); 
				}
				break;
				
			case 'um_switch_user':
				if ( !current_user_can('delete_users') ) return;
				UM()->user()->auto_login( $_REQUEST['uid'] );
				exit( wp_redirect( UM()->permalinks()->get_current_url( true ) ) );
				break;
				
			case 'um_reject_membership':
				um_fetch_user( $uid );
				UM()->user()->reject();
				exit( wp_redirect( UM()->permalinks()->get_current_url( true ) ) );
				break;
				
			case 'um_approve_membership':
			case 'um_reenable':
				um_fetch_user( $uid );
				UM()->user()->approve();
				exit( wp_redirect( UM()->permalinks()->get_current_url( true ) ) );
				break;
				
			case 'um_put_as_pending':
				um_fetch_user( $uid );
				UM()->user()->pending();
				exit( wp_redirect( UM()->permalinks()->get_current_url( true ) ) );
				break;
				
			case 'um_resend_activation':
				um_fetch_user( $uid );
				UM()->user()->email_pending();
				exit( wp_redirect( UM()->permalinks()->get_current_url( true ) ) );
				break;
				
			case 'um_deactivate':
				um_fetch_user( $uid );
				UM()->user()->deactivate();
				exit( wp_redirect( UM()->permalinks()->get_current_url( true ) ) );
				break;
				
			case 'um_delete':
				if ( ! UM()->roles()->um_current_user_can( 'delete', $uid ) ) wp_die( __('You do not have permission to delete this user.','ultimate-member') );
				um_fetch_user( $uid );
				UM()->user()->delete();
				exit( wp_redirect( UM()->permalinks()->get_current_url( true ) ) );
				break;
				
		}
	}