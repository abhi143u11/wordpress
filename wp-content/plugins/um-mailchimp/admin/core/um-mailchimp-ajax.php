<?php

	/***
	***	@unblock a user
	***/
	add_action('wp_ajax_nopriv_um_mailchimp_force_subscribe', 'um_mailchimp_force_subscribe');
	add_action('wp_ajax_um_mailchimp_force_subscribe', 'um_mailchimp_force_subscribe');
	function um_mailchimp_force_subscribe() {
		global $um_mailchimp;

		header('content-type: application/json');

		// force subscribe users
		$um_mailchimp->api->mailchimp_subscribe( true, false );

		// prepare return messages
		$return = array('success' => 1, 'progress' => 0, 'total' => 0, 'message' => rand());
		$total  = $um_mailchimp->api->queue_count( 'subscribers' );


		// update return messages
		$return['total']   = $total;
		$return['message'] = sprintf("%d unprocessed users left.", $total);

		if(!$total) {
			$return['progress'] = 100;
		}

		// display the results
		echo json_encode($return);
		die();
	}

	/***
	***	@unblock a user
	***/
	add_action('wp_ajax_nopriv_um_mailchimp_force_unsubscribe', 'um_mailchimp_force_unsubscribe');
	add_action('wp_ajax_um_mailchimp_force_unsubscribe', 'um_mailchimp_force_unsubscribe');
	function um_mailchimp_force_unsubscribe() {
		global $um_mailchimp;

		header('content-type: application/json');

		// force unsubscribe users
		$um_mailchimp->api->mailchimp_unsubscribe( true, false );

		// prepare return messages
		$return = array('success' => 1, 'progress' => 0, 'total' => 0, 'message' => rand());
		$total  = $um_mailchimp->api->queue_count( 'unsubscribers' );


		// update return messages
		$return['total']   = $total;
		$return['message'] = sprintf("%d unprocessed users left.", $total);

		if(!$total) {
			$return['progress'] = 100;
		}

		// display the results
		echo json_encode($return);
		die();
	}

	/***
	***	@unblock a user
	***/
	add_action('wp_ajax_nopriv_um_mailchimp_force_update', 'um_mailchimp_force_update');
	add_action('wp_ajax_um_mailchimp_force_update', 'um_mailchimp_force_update');
	function um_mailchimp_force_update() {
		global $um_mailchimp;

		header('content-type: application/json');

		// force update users
		$um_mailchimp->api->mailchimp_update( true, false );

		// prepare return messages
		$return = array('success' => 1, 'progress' => 0, 'total' => 0, 'message' => rand());
		$total  = $um_mailchimp->api->queue_count( 'update' );


		// update return messages
		$return['total']   = $total;
		$return['message'] = sprintf("%d unprocessed users left.", $total);

		if(!$total) {
			$return['progress'] = 100;
		}

		// display the results
		echo json_encode($return);
		die();
	}


	add_action('wp_ajax_nopriv_um_mailchimp_scan_now', 'um_mailchimp_scan_now');
	add_action('wp_ajax_um_mailchimp_scan_now', 'um_mailchimp_scan_now');
	function um_mailchimp_scan_now() {
		global $um_mailchimp;

		header('content-type: application/json');

		$um_mailchimp->api->get_profiles_not_optedin();
		$um_mailchimp->api->get_profiles_out_synced();
		
		// prepare return messages
		$return = array('success' => 1, 'progress' => 100, 'total' => 0, 'message' => rand());
		
		// display the results
		echo json_encode($return);
		die();
	}

	add_action('wp_ajax_nopriv_um_mailchimp_optin_now', 'um_mailchimp_optin_now');
	add_action('wp_ajax_um_mailchimp_optin_now', 'um_mailchimp_optin_now');
	function um_mailchimp_optin_now() {
		global $um_mailchimp, $wpdb;

		if( isset( $_POST['um_mailchimp_list'] ) ){
			$chosen_list = $_POST['um_mailchimp_list'];
			if( $chosen_list != 'all' ){
				$list = $um_mailchimp->api->fetch_list( $chosen_list );
				$chosen_list = $list['id'];
			}
		}

		$lists = $um_mailchimp->api->get_lists();
		$arr_lists = array();
		foreach( $lists as $list_id => $list_name ){
			$arr_lists[ $list_id ] = 1;
		}

		$arr_single_list = array();
		$arr_single_list[ $chosen_list ] = 1;
		
		$optedin_profiles = get_option( '_mailchimp_not_optedin_profiles' );
		header('content-type: application/json');
        
        if( $optedin_profiles ){
	        $arr_profile_ids = array();
			foreach( $optedin_profiles as $i => $user ){
				
				if( $chosen_list == 'all' ){
					update_user_meta( $user->ID, '_mylists', $arr_lists );
				}else{
					update_user_meta( $user->ID, '_mylists', $arr_single_list );
				}
				
				$arr_profile_ids[ ] = $user->ID;
			}
			
			delete_option('_mailchimp_not_optedin_profiles');
			
		}
		// prepare return messages
		$return = array('success' => 1, 'progress' => 100, 'total' => 0, 'message' => '', "debug" => true, "debug_message" => array( $arr_profile_ids, $chosen_list, $arr_lists, $arr_single_list, $param ) );
		
		// display the results
		echo json_encode($return);
		die();
	}

	add_action('wp_ajax_nopriv_um_mailchimp_optedin_sync_now', 'um_mailchimp_optedin_sync_now');
	add_action('wp_ajax_um_mailchimp_optedin_sync_now', 'um_mailchimp_optedin_sync_now');
	function um_mailchimp_optedin_sync_now() {
		global $um_mailchimp, $wpdb;

		$apikey = um_get_option('mailchimp_api');
		if ( ! $apikey ) return;
		$MailChimp 	= new UM_MailChimp_V3( $apikey );
		$Batch     	= $MailChimp->new_batch();
		
		$profiles 	= get_option( '_mailchimp_optedin_not_synced_profiles' );
		$batch_id 	= get_option('_um_mailchimp_batch_operation_id');
		$progress 	= 20;
		$message 	= 'Syncing...';
		$arr_lists  = array();
		$arr_debug 	= array();

		if( empty( $batch_id ) ){

			foreach( $profiles as $user_id => $list_id ){

				$arr_user_lists = array();
				$arr_user_lists = array_keys( $list_id );
				$arr_lists[ $user_id ] = $arr_user_lists;
				
				um_fetch_user( $user_id );

				$merge_vars = array('FNAME'=> um_user('first_name'), 'LNAME'=> um_user('last_name') );
				$user_email = um_user('user_email');

				$merge_vars = apply_filters('um_mailchimp_merge_fields', $merge_vars, $arr_user_lists, $user_id );

				foreach( $arr_user_lists as $i => $_list_id ){
					$Batch->post("op_uid_{$user_id}_list{$i}", "lists/{$_list_id}/members", array(
			                'email_address' => $user_email,
			                'status'        => 'subscribed',
			                'merge_fields'	=> $merge_vars,
			        ) );
				}

			}

			$returned_batch_id = $Batch->execute();
			update_option('_um_mailchimp_batch_operation_id', $returned_batch_id["id"] );

		}else{

			$Batch = $MailChimp->new_batch( $batch_id );
			$result = $Batch->check_status();
			
			if( $result['status'] == "pending" ){
				$progress = 40;
				$message = 'Processing on the batch operation hasn’t started yet.';
			}else if( $result['status'] == "started" ){
				$progress = 50;
				$message = 'Processing has started.';
			}else if( $result['status'] == "finished" ){
				$progress = 100;
				$message = 'Processing is done.';
				delete_option('_um_mailchimp_batch_operation_id');
				delete_option('_mailchimp_optedin_not_synced_profiles');
			}
			$arr_debug['batch_result'] = $result;

		 }
		
		$arr_debug['profiles'] = $profiles;
		$arr_debug['batch_id'] = $batch_id;
		$arr_debug['profiles_lists'] = $arr_lists;

		// prepare return messages
		$return = array(
			'success' 	=> 1, 
			'progress' 	=> $progress, 
			'total' 	=> 0, 
			'message' 	=> $message, 
			"debug" 	=> true, 
			"debug_message" => $arr_debug,
		);
		
		wp_send_json( $return );

	}