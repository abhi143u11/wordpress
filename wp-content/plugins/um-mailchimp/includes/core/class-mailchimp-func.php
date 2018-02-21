<?php
namespace um_ext\um_mailchimp\core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Mailchimp_Func {

	function __construct() {

		$this->user_id = get_current_user_id();

		$this->schedules();

		$this->lists_subscribed = array();

		if( isset( $_REQUEST['um_mailchimp_scan_profiles'] ) ){
			$this->get_profiles_out_synced();
		}

        add_filter( 'um_admin_enqueue_localize_data',  array( &$this, 'localize_data' ), 10, 1 );
	}


    function localize_data( $data ) {

        $data['mailchimp_force_subscribe'] = UM()->get_ajax_route( 'um_ext\um_mailchimp\core\Mailchimp_Func', 'ajax_force_subscribe' );
        $data['mailchimp_force_unsubscribe'] = UM()->get_ajax_route( 'um_ext\um_mailchimp\core\Mailchimp_Func', 'ajax_force_unsubscribe' );
        $data['mailchimp_force_update'] = UM()->get_ajax_route( 'um_ext\um_mailchimp\core\Mailchimp_Func', 'ajax_force_update' );
        $data['mailchimp_scan_now'] = UM()->get_ajax_route( 'um_ext\um_mailchimp\core\Mailchimp_Func', 'ajax_scan_now' );
        $data['mailchimp_optin_now'] = UM()->get_ajax_route( 'um_ext\um_mailchimp\core\Mailchimp_Func', 'ajax_optin_now' );
        $data['mailchimp_optedin_sync_now'] = UM()->get_ajax_route( 'um_ext\um_mailchimp\core\Mailchimp_Func', 'ajax_optedin_sync_now' );

        return $data;

    }


    function ajax_force_subscribe() {

        header('content-type: application/json');

        // force subscribe users
        $this->mailchimp_subscribe( true, false );

        // prepare return messages
        $return = array('success' => 1, 'progress' => 0, 'total' => 0, 'message' => rand());
        $total  = $this->queue_count( 'subscribers' );


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


    function ajax_force_unsubscribe() {
        header('content-type: application/json');

        // force unsubscribe users
        $this->mailchimp_unsubscribe( true, false );

        // prepare return messages
        $return = array('success' => 1, 'progress' => 0, 'total' => 0, 'message' => rand());
        $total  = $this->queue_count( 'unsubscribers' );


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


    function ajax_force_update() {
        header('content-type: application/json');

        // force update users
        $this->mailchimp_update( true, false );

        // prepare return messages
        $return = array('success' => 1, 'progress' => 0, 'total' => 0, 'message' => rand());
        $total  = $this->queue_count( 'update' );


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


    function ajax_scan_now() {
        header('content-type: application/json');

        $this->get_profiles_not_optedin();
        $this->get_profiles_out_synced();

        // prepare return messages
        $return = array('success' => 1, 'progress' => 100, 'total' => 0, 'message' => rand());

        // display the results
        echo json_encode($return);
        die();
    }


    function ajax_optin_now() {
        if( isset( $_POST['um_mailchimp_list'] ) ){
            $chosen_list = $_POST['um_mailchimp_list'];
            if( $chosen_list != 'all' ){
                $list = $this->fetch_list( $chosen_list );
                $chosen_list = $list['id'];
            }
        }

        $lists = $this->get_lists();
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


    function ajax_optedin_sync_now() {
        $apikey = UM()->options()->get( 'mailchimp_api' );
        if ( ! $apikey ) return;
        $MailChimp 	= new \UM_MailChimp_V3( $apikey );
        $Batch     	= $MailChimp->new_batch();

        $lists = $this->get_lists( true );
        $arr_all_lists = array();
        foreach( $lists as $list_id => $list_name ){
            $arr_all_lists[ ] = $list_id ;
        }

        $arr_all_lists = array_values( $arr_all_lists );

        $profiles 	= get_option( '_mailchimp_optedin_not_synced_profiles' );
        $batch_id 	= get_option('_um_mailchimp_batch_operation_id');

        $progress 	= 20;
        $message 	= 'Syncing...';
        $arr_lists  = array();
        $arr_debug 	= array();
        $arr_batch_requests = array();
        $arr_all_merge_vars = array();

        if( empty( $batch_id ) ){

            foreach( $profiles as $user_id => $list_id ){

                $arr_user_lists = array();
                $arr_user_lists = array_keys( $list_id );
                $arr_lists[ $user_id ] = $arr_user_lists;

                um_fetch_user( $user_id );
                $mylists = um_user('_mylists');

                $merge_vars = array('FNAME'=> um_user('first_name'), 'LNAME'=> um_user('last_name') );
                $merge_vars = array_filter( $merge_vars );
                $user_email = um_user('user_email');

                if( $mylists ){
                    $arr_keys = array_keys( $mylists );
                    $args = array(
                        'post_type'	=> 'um_mailchimp',
                        'meta_query' => array(
                            array(
                                'key'     => '_um_list',
                                'value'   => $arr_keys,
                                'compare' => 'IN',
                            ),
                            'posts_per_page' => 1,
                            'fields' => 'ids',
                        )

                    );

                    $um_list_query 	= new \WP_Query( $args );
                    $_merge_vars 	= array();

                    if( $um_list_query->found_posts > 0 ){
                        $_merge_vars = get_post_meta( $um_list_query->post->ID, '_um_merge', true );

                        if ( $_merge_vars ) {
                            foreach( $_merge_vars as $meta => $var ) {
                                
                                $value = um_user( $meta );

                                if( ! empty( $value ) ){
                                        if ( is_array( $value ) ) {
                                                $merge_vars[ $var ] = implode(', ', $value );
                                        }else{
                                                $merge_vars[ $var ] = $value;
                                        }
                                }
                                
                            }
                        }
                    }

                    $merge_vars = apply_filters('um_mailchimp_merge_fields', $merge_vars, $arr_user_lists, $user_id, $arr_keys );

                    if( is_array( $arr_keys ) && in_array( $list_id, $arr_keys ) ){
                        $profiles[ $user_id ]['merge_vars'] = $merge_vars;
                    }

                }


                foreach( $arr_user_lists as $i => $_list_id ){

                    if( ! empty( $_list_id ) && in_array( $_list_id, $arr_all_lists ) ){

                        $arr_to_merge = array(
                            'email_address' => $user_email,
                            'status'        => 'subscribed',
                        );

                        if( ! empty( $merge_vars ) ){
                            $arr_to_merge['merge_fields'] = $merge_vars;
                        }

                        $lists = $MailChimp->get("lists/{$list_id}/members/{$email_md5}"); 
                        if ( !$lists || ( isset( $lists['status'] ) && $lists['status'] == 'unsubscribed' ) || $lists['status'] == 404 ){
                            $Batch->post("op_uid_{$user_id}_list{$i}_{$_list_id}", "lists/{$_list_id}/members", $arr_to_merge );
                        }else{
                            $Batch->put("op_uid_{$user_id}_list{$i}_{$_list_id}", "lists/{$_list_id}/members", $arr_to_merge );
                        } 
            
                        $arr_batch_requests[  ] = "op_uid_{$user_id}_list{$i}---lists/{$_list_id}/members";

                    }

                }

            }

            $returned_batch_id = $Batch->execute();
            $arr_batch_requests[ ] = $returned_batch_id;
            update_option('_um_mailchimp_batch_operation_id', $returned_batch_id["id"] );

        }else{

            $Batch = $MailChimp->new_batch( $batch_id );
            $result = $Batch->check_status();

            update_option('_um_mailchimp_optedin_synced_latest_result',  $result );

            if( $result['status'] == "pending" ){
                $progress = 40;
                $message = 'Processing on the batch operation hasn’t started yet.';
            }else if( $result['status'] == "started" ){
                $progress = 50;
                $message = 'Processing has started.';
            }else if( $result['status'] == "finished" ){
                $progress = 100;
                $message = 'Processing is done.';

                if( isset(  $result['batch_result'] ) && isset( $result['batch_result']['errored_operations'] ) && $result['batch_result']['errored_operations']  >= 0 ){
                    update_option('_um_mailchimp_optedin_synced_errored_profiles',  $result['batch_result']['errored_operations'] );
                }

                delete_option('_um_mailchimp_batch_operation_id');
                delete_option('_mailchimp_optedin_not_synced_profiles');
            }else if( $result['status'] == 404 ){
                $progress = 100;
                $message = $result['detail'];
                delete_option('_um_mailchimp_batch_operation_id');
            }

            $arr_debug['batch_result'] = $result;

        }

        $arr_debug['profiles'] = $profiles;
        $arr_debug['batch_id'] = $batch_id;
        $arr_debug['profiles_lists'] = $arr_lists;
        $arr_debug['batch_requests'] = $arr_batch_requests;
        $arr_debug['errored_operations'] = $scanned_errored_profiles;
        $arr_debug['merge_vars'] = $arr_all_merge_vars;

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

	/***
	***	@Schedules
	***/
	function schedules() {

		add_action( 'um_daily_scheduled_events', array( $this, 'mailchimp_subscribe' ) );

		add_action( 'um_daily_scheduled_events', array( $this, 'mailchimp_unsubscribe' ) );

		add_action( 'um_daily_scheduled_events', array( $this, 'mailchimp_update' ) );

	}

	function raw(){
		
		$apikey = UM()->options()->get( 'mailchimp_api' );

		if ( !$apikey ) return null;
		$MailChimp = new \UM_MailChimp_V3( $apikey );

		return $MailChimp;
	}

	/***
	***	@Update
	***/
	function mailchimp_update( $override = false, $all = true ) {

		$last_send = $this->get_last_update();
		if( !$override && $last_send && $last_send > strtotime( '-1 day' ) )
			return;

		$array = get_option('_mailchimp_new_update');
		if ( !$array || !is_array($array) ) return;

	 	$array_unable_sync_profiles = array();

		$apikey = UM()->options()->get('mailchimp_api');

		if ( !$apikey ) return;
		$MailChimp = new \UM_MailChimp_V3( $apikey );

		// update user info for specific list
		$counter = 0;
		foreach( $array as $list_id => $data ) {

			// only update one profile at a time
			if( !$all && $counter ) break;

			if ( !empty( $data ) ) {

				foreach( $data as $user_id => $merge_vars ) {

					// only update one profile at a time
					if( !$all && $counter ) break;

					um_fetch_user( $user_id );
					$email_md5 = md5( um_user('user_email') );

					
					foreach( $merge_vars as $key => $val ) {
						if( ! empty( $val ) ){
							if ( is_array( $val ) ) {
								$merge_vars[ $key ] = implode(', ', $val );
							}else{
								$merge_vars[ $key ] = $val;
							}
						}else{
							unset( $merge_vars[ $key ] );
						}
					}

					$response = $MailChimp->patch("lists/{$list_id}/members/{$email_md5}",  array(
							'merge_fields'        => $merge_vars
					));

					unset( $array[$list_id][$user_id] );

					// update counter
					$counter++;
				}

			}

		}

		update_option('_mailchimp_unable_sync_profiles', $array_unable_sync_profiles );

		// reset new update sync
		update_option('_mailchimp_new_update', $array);

		// update last update data
		update_option( 'um_mailchimp_last_update', time() );

	}

	/***
	***	@Subscribe
	***/
	function mailchimp_subscribe( $override = false, $all = true ) {
		$last_send = $this->get_last_subscribe();
		if( !$override ){
		 	if( $last_send && $last_send > strtotime( '-1 day' ) ){
				return;
			}
		}

		$array = get_option('_mailchimp_new_subscribers');
		if ( !$array || !is_array($array) ) $array = array();

		$apikey = UM()->options()->get('mailchimp_api');

		if ( !$apikey ) return;
		$MailChimp = new \UM_MailChimp_V3( $apikey );

        $um_list_ids = array_keys( $array );

        $array_unable_sync_profiles = get_option('_mailchimp_unable_sync_profiles');
      	
		foreach ( $um_list_ids as $_list_value ) {
	      	  
	      	  $args = array(
		       		'post_type'	=> 'um_mailchimp',
		       		'meta_query' => array(
						array(
							'key'     => '_um_list',
							'value'   => $_list_value,
							'compare' => '=',
						),
					)

		       	);

		       $um_list_query = new \WP_Query( $args );

		       if( $um_list_query->post_count <= 0 ){
		       	     unset( $array[ $_list_value ] );
		       }

	    }


		// subscribe each user to the mailing list
		$counter = 0;
		foreach( $array as $list_id => $data ) {

			// only update one profile at a time
			if( !$all && $counter ) break;

			if ( !empty( $data ) ) {

				foreach( $data as $user_id => $merge_vars ) {

					// only update one profile at a time
					if( !$all && $counter ) break;

					um_fetch_user( $user_id );
					$email = um_user('user_email');
					$email_md5 = md5( $email );

					if( empty(  $merge_vars ) ) {
						$merge_vars = array();
					}
					foreach( $merge_vars as $key => $val ) {
						if( ! empty( $val ) ){
							if ( is_array( $val ) ) {
								$merge_vars[ $key ] = implode(', ', $val );
							}else{
								$merge_vars[ $key ] = $val;
							}
						}else{
							unset( $merge_vars[ $key ] );
						}
					}

					$mailchimp_status = apply_filters('um_mailchimp_default_subscription_status', 'subscribed' );

					$response = $MailChimp->put("lists/{$list_id}/members/{$email_md5}",  array(
							'email_address'     => $email,
							'merge_fields'      => $merge_vars,
							'status'      		=> $mailchimp_status,
					));

					unset( $array[ $list_id ][ $user_id ] );

					// update counter
					$counter++;
				}

			}

		}
		
		// update unable sync profiles
		update_option('_mailchimp_unable_sync_profiles', $array_unable_sync_profiles );

		// reset new subscribers sync
		update_option('_mailchimp_new_subscribers', $array);

		// update last subscribe data
		update_option( 'um_mailchimp_last_subscribe', time() );

	}

	/***
	***	@Unsubscribe
	***/
	function mailchimp_unsubscribe( $override = false, $all = true ) {

		$last_send = $this->get_last_unsubscribe();
		if( !$override ){
		 	if( $last_send && $last_send > strtotime( '-1 day' ) ){
				return;
			}
		}

		$array = get_option('_mailchimp_new_unsubscribers');
		if ( !$array || !is_array($array) ) $array = array();

		$apikey = UM()->options()->get('mailchimp_api');

		if ( !$apikey ) return;
		$MailChimp = new \UM_MailChimp_V3( $apikey );

		// unsubscribe each user to the mailing list
		$counter = 0;
		foreach( $array as $list_id => $data ) {

			// only update one profile at a time
			if( !$all && $counter ) break;

			if ( !empty( $data ) ) {

				foreach( $data as $user_id => $merge_vars ) {

					// only update one profile at a time
					if( !$all && $counter ) break;

					um_fetch_user( $user_id );
					$email_md5 = md5( um_user('user_email') );

					$response = $MailChimp->patch("lists/{$list_id}/members/{$email_md5}",  array(
							'status'  => 'unsubscribed',
					));

					unset( $array[$list_id][$user_id] );

					// update counter
					$counter++;
				}

			}

		}
		
		// reset new unsubscribers sync
		update_option('_mailchimp_new_unsubscribers', $array);

		// update last unsubscribe data
		update_option( 'um_mailchimp_last_unsubscribe', time() );

	}

	/***
	***	@Last Update
	***/
	function get_last_update() {
		return get_option( 'um_mailchimp_last_update' );
	}

	/***
	***	@Last Subscribe
	***/
	function get_last_subscribe() {
		return get_option( 'um_mailchimp_last_subscribe' );
	}

	/***
	***	@Last Unsubscribe
	***/
	function get_last_unsubscribe() {
		return get_option( 'um_mailchimp_last_unsubscribe' );
	}

	/***
	***	@update user
	***/
	function update( $list_id, $_merge_vars=null ) {

		$user_id = $this->user_id;
		um_fetch_user( $user_id );

		if ( !um_user('user_email') ) return;

				$mylists = um_user('_mylists');

				$merge_vars = array('FNAME'=> um_user('first_name'), 'LNAME'=> um_user('last_name') );
				$merge_vars = array_filter( $merge_vars );
				$user_email = um_user('user_email');
				
				if( $mylists ){
					$args = array(
				       		'post_type'	=> 'um_mailchimp',
				       		'meta_query' => array(
								array(
									'key'     => '_um_list',
									'value'   =>  $list_id,
									'compare' => '=',
								),
							'posts_per_page' => 1,
							'fields' => 'ids',
							)
					);

	 		        $um_list_query 	= new \WP_Query( $args );
	 		        $_merge_vars 	= array();

	 		        if( $um_list_query->found_posts > 0 ){
	 		        	$_merge_vars = get_post_meta( $um_list_query->post->ID, '_um_merge', true );
						
						if ( $_merge_vars ) {
							foreach( $_merge_vars as $meta => $var ) {
								if ( $var != '0' && um_user( $meta ) ) {
									$merge_vars[ $var ] = um_user( $meta );
								}
							}
						}
					}

					$merge_vars = apply_filters('um_mailchimp_single_merge_fields', $merge_vars, $user_id, $list_id );
				
				}

	

		$_new_update = get_option('_mailchimp_new_update');
		if ( !isset( $_new_update[ $list_id ][ $user_id ] ) ) {
			$_new_update[ $list_id ][ $user_id ] = $merge_vars;
		}

		update_option( '_mailchimp_new_update', $_new_update );

	}

	/***
	***	@subscribe user
	***/
	function subscribe( $list_id, $_merge_vars=null ) {

		$user_id = $this->user_id;

		um_fetch_user( $user_id );

		if ( ! um_user('user_email') ) return;

				$mylists = um_user('_mylists');

				$merge_vars = array('FNAME'=> um_user('first_name'), 'LNAME'=> um_user('last_name') );
				$merge_vars = array_filter( $merge_vars );
				$user_email = um_user('user_email');
				
				if( $mylists || $list_id ){
					$args = array(
				       		'post_type'	=> 'um_mailchimp',
				       		'meta_query' => array(
								array(
									'key'     => '_um_list',
									'value'   =>  $list_id,
									'compare' => '=',
								),
							'posts_per_page' => 1,
							'fields' => 'ids',
							)
					);

	 		        $um_list_query 	= new \WP_Query( $args );
	 		        $_merge_vars 	= array();

	 		        if( $um_list_query->found_posts > 0 ){
	 		        	$_merge_vars = get_post_meta( $um_list_query->post->ID, '_um_merge', true );
						
						if ( $_merge_vars ) {
							foreach( $_merge_vars as $meta => $var ) {
								if ( $var != '0' && um_user( $meta ) ) {
									$merge_vars[ $var ] = um_user( $meta );
								}
							}
						}
					}

					$merge_vars = apply_filters('um_mailchimp_single_merge_fields', $merge_vars, $user_id, $list_id );
				
				}

		$_mylists = isset($mylists)?$mylists:array();

		if ( !isset($_mylists[$list_id]) ) {
			$_mylists[$list_id] = 1;
		}

		update_user_meta( $user_id, '_mylists', $_mylists);
		
		$_new_unsubscribers = get_option('_mailchimp_new_unsubscribers');
		if ( isset( $_new_unsubscribers[ $list_id ][ $user_id ] ) ) {
			unset($_new_unsubscribers[$list_id][$user_id]);
		}

		$_new_subscribers = get_option('_mailchimp_new_subscribers');
		if ( !isset( $_new_subscribers[ $list_id ][ $user_id ] ) ) {
			$_new_subscribers[$list_id][$user_id] = $merge_vars;
		}

		update_option( '_mailchimp_new_subscribers', $_new_subscribers );
		update_option( '_mailchimp_new_unsubscribers', $_new_unsubscribers );

		if ( UM()->options()->get('mailchimp_real_status') ) {
			$this->mailchimp_subscribe( true );
		}

	}

	/***
	***	@unsubscribe user
	***/
	function unsubscribe( $list_id ) {

		$user_id = $this->user_id;
		um_fetch_user( $user_id );

		if ( !um_user('user_email') ) return;

		$_mylists = get_user_meta( $user_id, '_mylists', true);
		if ( isset($_mylists[$list_id]) ) {
			unset($_mylists[$list_id]);
		}
		update_user_meta( $user_id, '_mylists', $_mylists);

		$_new_subscribers = get_option('_mailchimp_new_subscribers');
		if ( isset( $_new_subscribers[ $list_id ][ $user_id ] ) ) {
			unset($_new_subscribers[$list_id][$user_id]);
		}

		$_new_unsubscribers = get_option('_mailchimp_new_unsubscribers');
		if ( !isset( $_new_unsubscribers[ $list_id ][ $user_id ] ) ) {
			$_new_unsubscribers[$list_id][$user_id] = 1;
		}

		update_option( '_mailchimp_new_subscribers', $_new_subscribers );
		update_option( '_mailchimp_new_unsubscribers', $_new_unsubscribers );

		if ( UM()->options()->get('mailchimp_real_status') ) {
			$this->mailchimp_unsubscribe( true );
		}

	}

	/***
	***	@Fetch list
	***/
	function fetch_list( $id ) {
		$setup = get_post( $id );
		if ( !isset( $setup->post_title ) ) return false;
		$list['id'] = get_post_meta( $id, '_um_list', true );
		$list['auto_register'] =  get_post_meta( $id, '_um_reg_status', true );
		$list['description'] = get_post_meta( $id, '_um_desc', true );
		$list['register_desc'] = get_post_meta( $id, '_um_desc_reg', true );
		$list['name']  = $setup->post_title;
		$list['status'] = get_post_meta( $id, '_um_status', true );
		$list['merge_vars'] = get_post_meta( $id, '_um_merge', true );
		$list['roles'] = get_post_meta( $id, '_um_roles', true);
		return $list;
	}

	/***
	***	@Check if there are active integrations
	***/
	function has_lists( $admin = false, $user_id = null ) {
		$args = array(
			'post_status'	=> array('publish'),
			'post_type' 	=> 'um_mailchimp',
			'fields'		=> 'ids',
			'posts_per_page' => -1
		);
		$args['meta_query'][] = array('relation' => 'AND');
		$args['meta_query'][] = array(
			'key' => '_um_status',
			'value' => '1',
			'compare' => '='
		);

		if( is_numeric( $user_id ) ){
			$this->user_id = $user_id;
		}

		um_fetch_user( $this->user_id );
		$lists = new \WP_Query( $args );
		if ( $lists->found_posts > 0 ) {
			$array = $lists->posts;

			// frontend-use
			if ( !$admin ) {
				foreach( $array as $k => $post_id ) {
					$roles = get_post_meta( $post_id, '_um_roles', true);
					$current_user_roles = um_user( 'roles' );
					if ( ! empty( $roles ) && ( empty( $current_user_roles ) || count( array_intersect( $current_user_roles, $roles ) ) <= 0 ) ) {
						unset( $array[$k] );
					}
				}
			} 

			if ( $array )
				return $array;
			return false;
		}
		return false;
	}

	/***
	***	@get merge vars for a specific list
	***/
	function get_vars( $list_id ) {

		$apikey = UM()->options()->get('mailchimp_api');
		if ( $apikey ) {

			$api = new \UM_MCAPI( $apikey );

			$merge_vars = $api->call('lists/merge-vars',  array(
				'id' => array( $list_id )
			));

		}

		if ( isset( $merge_vars['data'][0]['merge_vars'] ) )
			return $merge_vars['data'][0]['merge_vars'];
		return array('');
	}

	/***
	***	@subscribe status
	***/
	function is_subscribed( $list_id ) {

		$user_id = $this->user_id;

		$_mylists = get_user_meta( $user_id, '_mylists', true);

		if ( isset( $_mylists[ $list_id ] ) ) {
				return true;
		}

		if ( UM()->options()->get('mailchimp_real_status') ) {

			$apikey = UM()->options()->get('mailchimp_api');
			$MailChimp = new \UM_MailChimp_V3( $apikey );
			$email_md5 = md5( um_user('user_email') );
			$lists = $MailChimp->get("lists/{$list_id}/members/{$email_md5}"); 
			if ( !$lists || ( isset( $lists['status'] ) && $lists['status'] == 'unsubscribed' ) || $lists['status'] == 404 ) {
				return false;
			}
			
			return true;
			

		} 

		return false;

	}

	/***
	***	@Get list names
	***/
	function get_lists( $raw = true ) {
		$res = null;
			
		$apikey = UM()->options()->get('mailchimp_api');
		
		$lists = array();
		
		if ( $apikey  && $raw ) { // created from MailChimp
		
			$um_mailchimp_v3 = new \UM_MailChimp_V3( $apikey );
		
			$lists_limit = apply_filters('um_mailchimp_lists_limit', 30 );
		
			$lists = $um_mailchimp_v3->get( 'lists',  array( "count" => $lists_limit ) );
		
		} else { // created from post type 'um_mailchimp'
			
			$has_lists = $this->has_lists( true );
			
			if( isset(	$has_lists	) && is_array( $has_lists ) ){
			
				foreach ( $has_lists as $i => $list_id ){
			
					$list = $this->fetch_list( $list_id );
			
					$lists['lists'][] = array(
						'name' => $list['name'],
						'id'   => $list_id,
					);
			
				}
			
			}
		}
		
		if ( isset( $lists['lists'] ) ) {
			foreach ( $lists['lists'] as $key => $list ) {
				$res[ $list['id'] ] = $list['name'];
			}
		}

		if (!$res)
			$res[0] = __('No lists found','um-mailchimp');
		return $res;
	}

	/***
	***	@Get list subscriber count
	***/
	function get_list_member_count( $list_id ) {
		$res = null;
		$apikey = UM()->options()->get('mailchimp_api');
		if ( $apikey ) {
			$api = new \UM_MCAPI( $apikey );
			$lists = $api->call('lists/list');
		}

		if ( !isset( $lists ) ) return __('Please setup MailChimp API','um-mailchimp');
		foreach( $lists['data'] as $key => $list ) {
			if ($list['id'] == $list_id)
				return $list['stats']['member_count'];
		}
		return 0;
	}

	/***
	***	@Retrieve connection
	***/
	function account() {

		$apikey = UM()->options()->get('mailchimp_api');
		if ( !$apikey ) return;
		$um_mailchimp_v3 = new \UM_MailChimp_V3( $apikey );
		$result = $um_mailchimp_v3->get('');
		
		return $result;
	}

	/***
	***	@Queue count
	***/
	function queue_count( $type ) {
		$count = 0;
		if ( $type == 'subscribers' ) {
			$queue = get_option( '_mailchimp_new_subscribers' );
		} elseif ( $type == 'unsubscribers' ) {
			$queue = get_option( '_mailchimp_new_unsubscribers' );
		} else if ( $type == 'update' ) {
			$queue = get_option( '_mailchimp_new_update' );
		} else if ( $type == 'not_synced' ) {
			$queue = get_option( '_mailchimp_unable_sync_profiles' );
		} else if ( $type == 'not_optedin' ) {
			$queue = get_option( '_mailchimp_not_optedin_profiles' );
		} else if ( $type == 'optedin_not_synced' ) {
			$queue = get_option( '_mailchimp_optedin_not_synced_profiles' );
		} else if ( $type == 'errored_synced_profiles' ) {
			$queue = get_option( '_um_mailchimp_optedin_synced_errored_profiles' );
		}

		if ( $queue && !in_array( $type , array('not_optedin','optedin_not_synced') ) ) {
			foreach( $queue as $list_id => $data ) {
				$count = $count + count($data);
			}
		}else if( $queue ) {
			$count = count( $queue );
		}

		return $count;
	}

	function get_profiles_out_synced() {
		$apikey = UM()->options()->get( 'mailchimp_api' );
		if ( ! $apikey ) return;

		$MailChimp = new \UM_MailChimp_V3( $apikey );
		
		$lists = $this->get_lists( true );
		$arr_lists = array();
		foreach ( $lists as $list_id => $list_name ){
			$arr_lists[ ] = $list_id;
		}
		$arr_lists = array_values( $arr_lists );
					
		// Opted-in but not synced
		$args = array(
		    'fields' => array( 'user_email','ID' )
		);

		$user_role = $_POST['um_mailchimp_user_role'];
		
		if( isset( $user_role ) && ! empty( $user_role ) && $user_role != 'all' ){
			$args['role'] = $user_role;
		}

		$user_status = $_POST['um_mailchimp_user_status'];
		
		if( isset( $user_status ) && ! empty( $user_status ) && $user_status != 'all' ){
			$args['meta_query'][] = array(
				'key' => 'account_status',
				'value' => $user_status,
				'compare' => '=',
			);
		}

		$_SESSION['_um_mailchimp_selected_status'] = $user_status;
		$_SESSION['_um_mailchimp_selected_role'] = $user_role;

		$query_users = new \WP_User_Query( $args );
		$users = $query_users->get_results();
		$scanned_profiles = get_option('_um_mailchimp_scanned_profiles');
		$scanned_opted_profiles = get_option('_um_mailchimp_scanned_optedin_profiles');
		$progress = 0;

		if( !$scanned_profiles ) $scanned_profiles = array();
		if( !$scanned_opted_profiles ) $scanned_opted_profiles = array();
		
		$total_scanned_opted_profiles = count( $scanned_opted_profiles  );

		$optedin = get_option( '_mailchimp_optedin_not_synced_profiles');
		if( ! is_array( $optedin ) ){
			$optedin = array();
		}
		
		if( $query_users->total_users > 0 && count( $scanned_profiles ) <= $query_users->total_users ){
				$counter = 1;
				$counter_rand = rand( 5, 8 );
				foreach ( $users as $key => $user ) {
						
					if( $counter <= $counter_rand && ! isset( $scanned_profiles[ $user->ID ] ) ){
						$email_md5 = md5( strtolower( $user->user_email ) );
						$mylists = get_user_meta( $user->ID, "_mylists", true );

						
						if( ! empty( $mylists ) && is_array( $mylists ) ){
							$mylists = array_keys( $mylists );
							foreach ( $mylists as $list_id ) {
								$is_subscribed = $MailChimp->get("lists/{$list_id}/members/{$email_md5}" );
								
								if( in_array( $list_id , $arr_lists ) ){
									
									if( $is_subscribed['status'] == 404 ){
										$optedin[ $user->ID ][ $list_id ] = $is_subscribed['status'];
										$scanned_opted_profiles[ $user->ID ] = true;
									}
								}
								

								if( ! in_array( $list_id , $arr_lists ) ){
								 		$_mylists = get_user_meta( $user->ID, '_mylists', true);
										
										if ( isset( $_mylists[ $list_id ] ) ) {
											unset( $_mylists[ $list_id ] );
											unset( $optedin[ $user->ID ][ $list_id ] );
											unset( $scanned_opted_profiles[ $user->ID ] );
										}
										
										update_user_meta( $user->ID, '_mylists', $_mylists);
								}

							}
						}
						$scanned_profiles[ $user->ID ] = true;
						$counter++;
					}

					if( $counter == $counter_rand ) {
						break;
					}

				}

		}

		$total_scanned_users = count( $scanned_profiles );
		$progress =  intval( (  $total_scanned_users / $query_users->total_users )  * 100 );
		
		if( $total_scanned_users < $query_users->total_users && $total_scanned_users > 0 && $query_users->total_users > 0 ){
			$message = "{$total_scanned_users} of {$query_users->total_users} profiles scanned. {$progress}%";
			update_option('_um_mailchimp_scanned_profiles',$scanned_profiles);
		}else{
			$progress = 100;
			$message = "Finished.";
			delete_option('_um_mailchimp_scanned_profiles');
			delete_option('_um_mailchimp_scanned_optedin_profiles');
		}

		// prepare return messages
		$return = array(
			'success' 	=> 1, 
			'progress' 	=> $progress, 
			'total' 	=> 0, 
			'message' 	=> $message, 
			'total_users' => $query_users->total_users,
			"scanned_users" => $total_scanned_users,
			"total_scanned_optedin_users" => $total_scanned_opted_profiles,
			"total_optedin_users" => $optedin,
			"query_args" => $args,
			"user_status" => $user_status,
			"user_role" => $user_role,
			"submitted" => $_POST,
		);

		update_option( '_mailchimp_optedin_not_synced_profiles', $optedin );
		wp_send_json( $return );
	}

	function get_profiles_not_optedin(){
		
		// Not Opted-in
		$args = array(
				'meta_query' => array(
					'relation' => 'AND',
					array(
				        'relation' => 'OR',
				        array(
				            'key'     => '_mylists',
				            'compare' => 'NOT EXISTS'
				        ),
				        array(
				            'key'     => '_mylists',
				            'compare' => '=',
				            'value'	  => 'a:0:{}'
				        ),
				        array(
				            'key'     => '_mylists',
				            'compare' => '=',
				            'value'	  => ''
				        ),
				    ),

			    ),
			    'fields' => array( 'ID' )
			);

		$user_role = $_POST['um_mailchimp_user_role'];
		
		if( isset( $user_role ) && ! empty( $user_role ) && $user_role != 'all' ){
			$args['role'] = $user_role;
		}

		$user_status = $_POST['um_mailchimp_user_status'];

		if( isset( $user_status ) && ! empty( $user_status ) && $user_status != 'all' ){
			$args['meta_query'][] = array(
				'key' => 'account_status',
				'value' => $user_status,
				'compare' => '=',
			);
		}
		
		$query_users = new \WP_User_Query( $args );
		$profiles = $query_users->get_results();
		update_option( '_mailchimp_not_optedin_profiles', $profiles );
	}

}