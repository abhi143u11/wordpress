<?php

class UM_Mailchimp_Func {

	function __construct() {

		$this->user_id = get_current_user_id();

		$this->schedules();

		if( isset( $_REQUEST['um_mailchimp_scan_profiles'] ) ){
			$this->get_out_synced_profiles();
		}

	}

	/***
	***	@Schedules
	***/
	function schedules() {

		add_action( 'um_daily_scheduled_events', array( $this, 'mailchimp_subscribe' ) );

		add_action( 'um_daily_scheduled_events', array( $this, 'mailchimp_unsubscribe' ) );

		add_action( 'um_daily_scheduled_events', array( $this, 'mailchimp_update' ) );

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

		$apikey = um_get_option('mailchimp_api');

		if ( !$apikey ) return;
		$MailChimp = new UM_MailChimp_V3( $apikey );

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
						if ( is_array( $val ) ) {
							$merge_vars[$key] = implode(', ', $val );
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
		global $ultimatemember;

		$last_send = $this->get_last_subscribe();
		if( !$override && $last_send && $last_send > strtotime( '-1 day' ) )
			return;

		$array = get_option('_mailchimp_new_subscribers');
		if ( !$array || !is_array($array) ) $array = array();

		$apikey = um_get_option('mailchimp_api');

		if ( !$apikey ) return;
		$MailChimp = new UM_MailChimp_V3( $apikey );

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

		       $um_list_query = new WP_Query( $args );

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

					foreach( $merge_vars as $key => $val ) {
						if ( is_array( $val ) ) {
							$merge_vars[$key] = implode(', ', $val );
						}
					}

					$response = $MailChimp->put("lists/{$list_id}/members/{$email_md5}",  array(
							'email_address'     => $email,
							'merge_fields'      => $merge_vars,
							'status'      		=> 'subscribed',
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
		if( !$override && $last_send && $last_send > strtotime( '-1 day' ) )
			return;

		$array = get_option('_mailchimp_new_unsubscribers');
		if ( !$array || !is_array($array) ) $array = array();

		$apikey = um_get_option('mailchimp_api');

		if ( !$apikey ) return;
		$MailChimp = new UM_MailChimp_V3( $apikey );

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

		$merge_vars = array('FNAME'=> um_user('first_name'), 'LNAME' => um_user('last_name') );

		if ( $_merge_vars ) {
			foreach( $_merge_vars as $meta => $var ) {
				if ( $var != '0' ) {
					$merge_vars[ $var ] = um_user( $meta );
				}
			}
		}

		$_new_update = get_option('_mailchimp_new_update');
		if ( !isset( $_new_update[ $list_id ][ $user_id ] ) ) {
			$_new_update[$list_id][$user_id] = $merge_vars;
		}

		update_option( '_mailchimp_new_update', $_new_update );

	}

	/***
	***	@subscribe user
	***/
	function subscribe( $list_id, $_merge_vars=null ) {

		$user_id = $this->user_id;

		um_fetch_user( $user_id );

		if ( !um_user('user_email') ) return;

		$merge_vars = array('FNAME'=> um_user('first_name'), 'LNAME'=> um_user('last_name') );

		if ( $_merge_vars ) {
			foreach( $_merge_vars as $meta => $var ) {
				if ( $var != '0' ) {
					$merge_vars[ $var ] = um_user( $meta );
				}
			}
		}

		$_mylists = get_user_meta( $user_id, '_mylists', true);
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

		if ( um_get_option('mailchimp_real_status') ) {
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

		if ( um_get_option('mailchimp_real_status') ) {
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
		return $list;
	}

	/***
	***	@Check if there are active integrations
	***/
	function has_lists( $admin = false ) {
		global $ultimatemember;

		$args = array(
			'post_status'	=> array('publish'),
			'post_type' 	=> 'um_mailchimp',
			'fields'		=> 'ids'
		);
		$args['meta_query'][] = array('relation' => 'AND');
		$args['meta_query'][] = array(
			'key' => '_um_status',
			'value' => '1',
			'compare' => '='
		);

		$lists = new WP_Query( $args );
		if ( $lists->found_posts > 0 ) {
			$array = $lists->posts;

			// frontend-use
			if ( !$admin ) {
				foreach( $array as $k => $post_id ) {
					$roles = get_post_meta($post_id, '_um_roles', true);
					if ( $roles && !in_array( $ultimatemember->query->get_role_by_userid( $this->user_id ), $roles ) ) {
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

		$apikey = um_get_option('mailchimp_api');
		if ( $apikey ) {

			$api = new UM_MCAPI( $apikey );

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

		if ( um_get_option('mailchimp_real_status') ) {

			$apikey = um_get_option('mailchimp_api');
			$MailChimp = new UM_MailChimp_V3( $apikey );
			$email_md5 = md5( um_user('user_email') );
			$lists = $MailChimp->get("lists/{$list_id}/members/{$email_md5}");

			if ( !$lists || ( isset( $lists['status'] ) && $lists['status'] == 'unsubscribed' ) || $lists['status'] == 404 ) {
				return false;
			}
			
			return true;
			

		} else {

			$_mylists = get_user_meta( $user_id, '_mylists', true);

			if ( isset( $_mylists[ $list_id ] ) ) {
				return true;
			}

		}

		return false;

	}

	/***
	***	@Get list names
	***/
	function get_lists( $raw = true ) {
		global $um_mailchimp;

		$res = null;
		$apikey = um_get_option('mailchimp_api');
		$lists = array();
		if ( $apikey  && $raw ) { // created from MailChimp
			$um_mailchimp_v3 = new UM_MailChimp_V3( $apikey );
			$lists = $um_mailchimp_v3->get('lists');
		}else{ // created from post type 'um_mailchimp'
			$has_lists = $um_mailchimp->api->has_lists( true );
			foreach( $has_lists as $i => $list_id ){
				$list = $um_mailchimp->api->fetch_list( $list_id );
				$lists['lists'][] = array(
					'name' => $list['name'],
					'id'   => $list_id,
				);
			}
		}
		
		if ( isset( $lists['lists'] ) ) {
			foreach( $lists['lists'] as $key => $list ) {
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
		$apikey = um_get_option('mailchimp_api');
		if ( $apikey ) {
		$api = new UM_MCAPI( $apikey );
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

		$apikey = um_get_option('mailchimp_api');
		if ( !$apikey ) return;
		$um_mailchimp_v3 = new UM_MailChimp_V3( $apikey );
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
		}

		if ( $queue && !in_array( $type , array('not_optedin') ) ) {
			foreach( $queue as $list_id => $data ) {
				$count = $count + count($data);
			}
		}else if( $queue ) {
			$count = count( $queue );
		}

		return $count;
	}

	function get_profiles_out_synced(){
		
		$apikey = um_get_option('mailchimp_api');
		if ( ! $apikey ) return;

		$MailChimp = new UM_MailChimp_V3( $apikey );
		
		
		// Opted-in but not synced
		$args = array(
			'meta_query' => array(
				'relation' => 'AND',
				array(
			        'relation' => 'OR',
			        array(
			            'key'     => '_mylists',
			            'value'   => 'a:0:{}',
			            'compare' => 'NOT LIKE'
			        ),
			        array(
			            'key'     => '_mylists',
			            'value'   => 'a:0:{}',
			            'compare' => '!='
			        ),
			        array(
			            'key'     => '_mylists',
			            'value'   => '',
			            'compare' => '!='
			        )
			    )
		    ),
		    'fields' => array( 'user_email','ID' )
		);

		$user_role = $_POST['um_mailchimp_user_role'];
		
		if( isset( $user_role ) && ! empty( $user_role ) && $user_role != 'all' ){
			$args['meta_query'][] = array(
				'key' => 'role',
				'value' => $user_role,
				'compare' => '=',
			);
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

		$query_users = new WP_User_Query( $args ); 
		$users = $query_users->get_results();
		$scanned_profiles = get_option('_um_mailchimp_scanned_profiles');
		$scanned_opted_profiles = get_option('_um_mailchimp_scanned_optedin_profiles');
		$progress = 0;

		if( !$scanned_profiles ) $scanned_profiles = array();
		if( !$scanned_opted_profiles ) $scanned_opted_profiles = array();

		$total_scanned_opted_profiles = count( $scanned_opted_profiles  );

		if( $total_scanned_opted_profiles <= 0 ){
			delete_option( '_mailchimp_optedin_not_synced_profiles');
		}
		
		$optedin = get_option( '_mailchimp_optedin_not_synced_profiles');
		if( ! $optedin || ! is_array( $optedin ) ){
			$optedin = array();
		}
		
		if( $query_users->total_users > 0 && count( $scanned_profiles ) <= $query_users->total_users ){
				$lists = $this->get_lists();
				$counter = 1;
				$counter_rand = rand( 5, 8 );
				foreach ( $users as $key => $user ) {

					if( $counter <= $counter_rand && ! isset( $scanned_profiles[ $user->ID ] ) ){
						$email_md5 = md5( $user->user_email );
						$mylists = get_user_meta( $user->ID, "_mylists", true );
						if( ! empty( $mylists ) ){
							foreach ( $mylists as $list_id => $value ) {
								$is_subscribed = $MailChimp->get("lists/{$list_id}/members/{$email_md5}" );
								if( $is_subscribed['status'] == 404  ){
									$optedin[ $user->ID ][ $list_id ] = 1;
									$scanned_opted_profiles[ $user->ID ] = true;
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
				    ),

			    ),
			    'fields' => array( 'ID' )
			);

		$user_role = $_POST['um_mailchimp_user_role'];
		
		if( isset( $user_role ) && ! empty( $user_role ) && $user_role != 'all' ){
			$args['meta_query'][] = array(
				'key' => 'role',
				'value' => $user_role,
				'compare' => '=',
			);
		}

		$user_status = $_POST['um_mailchimp_user_status'];

		if( isset( $user_status ) && ! empty( $user_status ) && $user_status != 'all' ){
			$args['meta_query'][] = array(
				'key' => 'account_status',
				'value' => $user_status,
				'compare' => '=',
			);
		}
		
		$query_users = new WP_User_Query( $args ); 
		$profiles = $query_users->get_results();
		update_option( '_mailchimp_not_optedin_profiles', $profiles );
	}

}
