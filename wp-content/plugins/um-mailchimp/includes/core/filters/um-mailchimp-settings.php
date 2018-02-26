<?php
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@extend settings
	***/
add_filter( 'um_settings_structure', 'um_mailchimp_settings', 10, 1 );

function um_mailchimp_settings( $settings ) {

    $settings['licenses']['fields'][] = array(
        'id'      		=> 'um_mailchimp_license_key',
        'label'    		=> __( 'MailChimp License Key', 'um-mailchimp' ),
        'item_name'     => 'MailChimp',
        'author' 	    => 'Ultimate Member',
        'version' 	    => um_mailchimp_version,
    );

    $key = ! empty( $settings['extensions']['sections'] ) ? 'mailchimp' : '';
    $settings['extensions']['sections'][$key] = array(
        'title'     => __( 'MailChimp', 'um-mailchimp' ),
        'fields'    => array(
            array(
                'id'       		=> 'mailchimp_api',
                'type'     		=> 'text',
                'label'   		=> __( 'MailChimp API Key','um-mailchimp' ),
                'tooltip' 	=> __('The MailChimp API Key is required and enables you access and integration with your lists.','um-mailchimp'),
                'size' => 'medium',
            ),

            array(
                'id'       		=> 'mailchimp_real_status',
                'type'     		=> 'checkbox',
                'label'   		=> __( 'Enable Real-time Subscription Status','um-mailchimp' ),
                'tooltip' 	=> __('Careful as this option will contact the MailChimp API when you request a status of user subscription to a specific list.','um-mailchimp'),
            ),
        )
    );

    return $settings;
}

	/* Tweak parameters passed in admin email */
	add_filter('um_email_registration_data', 'um_mailchimp_email_registration_data');
	function um_mailchimp_email_registration_data( $data ) {
		if ( isset( $data['um-mailchimp'] ) ) {
			 $array_lists = array();
			foreach( $data['um-mailchimp'] as $list_id => $val ) {
					$posts = get_posts( array( 'post_type' => 'um_mailchimp', 'meta_key' => '_um_list', 'meta_value' => $list_id ) );
					if( isset( $posts[0]->post_title ) ){
						$array_lists[] = $posts[0]->post_title . '(#' . $list_id.')';
					}
			}
			$data[ __('Mailchimp Subscription','um-mailchimp') ] = implode(", ", $array_lists ); 
			unset( $data['um-mailchimp'] );
		}
		return $data;
	}