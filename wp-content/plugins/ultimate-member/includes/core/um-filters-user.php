<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/***
***	@Main admin user actions
***/
add_filter('um_admin_user_actions_hook', 'um_admin_user_actions_hook', 1);
function um_admin_user_actions_hook( $actions ) {

	$actions = null;

	um_fetch_user( um_profile_id() );

	if ( current_user_can('manage_options') ) {

		if ( um_user('account_status') == 'awaiting_admin_review' ){
			$actions['um_approve_membership'] = array( 'label' => __('Approve Membership','ultimate-member') );
			$actions['um_reject_membership'] = array( 'label' => __('Reject Membership','ultimate-member') );
		}

		if ( um_user('account_status') == 'rejected' ) {
			$actions['um_approve_membership'] = array( 'label' => __('Approve Membership','ultimate-member') );
		}

		if ( um_user('account_status') == 'approved' ) {
			$actions['um_put_as_pending'] = array( 'label' => __('Put as Pending Review','ultimate-member') );
		}

		if ( um_user('account_status') == 'awaiting_email_confirmation' ) {
			$actions['um_resend_activation'] = array( 'label' => __('Resend Activation E-mail','ultimate-member') );
		}

		if (  um_user('account_status') != 'inactive' ) {
			$actions['um_deactivate'] = array( 'label' => __('Deactivate this account','ultimate-member') );
		}

		if (  um_user('account_status') == 'inactive' ) {
			$actions['um_reenable'] = array( 'label' => __('Reactivate this account','ultimate-member') );
		}

		if ( UM()->roles()->um_current_user_can( 'delete', um_profile_id() ) ) {
			$actions['um_delete'] = array( 'label' => __('Delete this user','ultimate-member') );
		}

	}

	if ( current_user_can('delete_users') ) {
		$actions['um_switch_user'] = array( 'label' => __('Login as this user','ultimate-member') );
	}



	return $actions;
}


/**
 * Filter user basename
 * @param  string $value
 * @return string
 * @hook_filter: um_clean_user_basename_filter
 */
add_filter('um_clean_user_basename_filter','um_clean_user_basename_filter',2,10);
function um_clean_user_basename_filter( $value, $raw ){
	$permalink_base = UM()->options()->get( 'permalink_base' );

	$user_query = new WP_User_Query(
			array(
				 'meta_query'    => array(
		            'relation'  => 'AND',
		            array(
		                'key'     => 'um_user_profile_url_slug_'.$permalink_base,
		                'value'   => $raw,
		                'compare' => '='
		            )
		        ),
				'fields' => array('ID')
		    )

	);

	if( $user_query->total_users > 0 ){

		 $result = current( $user_query->get_results() );
		 $slugname =  '';

		if( isset( $result->ID ) ){
			  $slugname =  get_user_meta( $result->ID, 'um_user_profile_url_slug_'.$permalink_base, true );
			  $value = $slugname;
		}
	}


	$value = apply_filters("um_permalink_base_before_filter", $value );
	$raw_value = $value;

	switch( $permalink_base ){
			case 'name':


				if( ! empty( $value ) && strrpos( $value ,"_") > -1 ){
					$value = str_replace( '_', '. ', $value );
				}

				if( ! empty( $value ) && strrpos( $value ,"_") > -1 ){
					$value = str_replace( '_', '-', $value );
				}

				if( ! empty( $value ) && strrpos( $value ,".") > -1 && strrpos( $raw_value ,"_" ) <= -1 ){
					$value = str_replace( '.', ' ', $value );
				}

				$value = apply_filters("um_permalink_base_after_filter_name", $value, $raw_value );

			break;

			case 'name_dash':

				if( ! empty( $value ) && strrpos( $value ,"-") > -1 ){
					$value = str_replace( '-', ' ', $value );
				}

				if( ! empty( $value ) && strrpos( $value ,"_") > -1 ){
					$value = str_replace( '_', '-', $value );
				}

				// Checks if last name has a dash
				if( ! empty( $value ) && strrpos( $value ,"_") > -1 ){
					$value = str_replace( '_', '-', $value );
				}

				$value = apply_filters("um_permalink_base_after_filter_name_dash", $value, $raw_value );

			break;


			case 'name_plus':

				if( ! empty( $value ) && strrpos( $value ,"+") > -1 ){
					$value = str_replace( '+', ' ', $value );
				}

				if( ! empty( $value ) && strrpos( $value ,"_") > -1 ){
					$value = str_replace( '_', '+', $value );
				}

				$value = apply_filters("um_permalink_base_after_filter_name_plus", $value, $raw_value );

			break;

			default:

				// Checks if last name has a dash
				if( ! empty( $value ) && strrpos( $value ,"_") > -1 && substr( $value , "_") == 1 ){
					$value = str_replace( '_', '-', $value );
				}

				$value = apply_filters("um_permalink_base_after_filter", $value, $raw_value );

			break;
	}

	return $value;

}


/**
 * Filter before update profile to force utf8 strings
 *
 * @param  array $changes
 * @param int $user_id
 *
 * @return array
 */
function um_before_update_profile( $changes, $user_id ) {
	if ( ! UM()->options()->get( 'um_force_utf8_strings' ) ) {
		return $changes;
	}

	foreach ( $changes as $key => $value ) {
		$changes[ $key ] = um_force_utf8_string( $value );
	}

	return $changes;
}
add_filter( 'um_before_update_profile','um_before_update_profile', 10, 2 );