<?php
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@put users to sync in next update
	***/
	add_action('um_user_after_updating_profile', 'um_mailchimp_sync_user_update' );
	function um_mailchimp_sync_user_update( $changes ) {
		$user_id = um_user('ID');
		
		$user_lists = get_user_meta( $user_id, '_mylists', true );
		
		if ( $user_lists ) {

            UM()->Mailchimp_API()->api()->user_id = $user_id;
			
			$lists = UM()->Mailchimp_API()->api()->has_lists();
			if ( $lists ) {
				
				delete_option( "um_cache_userdata_{$user_id}" );

				foreach( $lists as $post_id ) { $list = UM()->Mailchimp_API()->api()->fetch_list($post_id);
					if ( isset( $user_lists[$list['id']] ) ) {
                        UM()->Mailchimp_API()->api()->update( $list['id'], $list['merge_vars'] );
					}
				}
				
			}
			
		}
		
	}

	/***
	*** @hook after user role is updated
	***/
	add_action('um_after_user_role_is_updated','um_mailchimp_after_user_role_is_updated', 10 ,2 );
	function um_mailchimp_after_user_role_is_updated( $user_id, $role ){
		if( is_wp_error( $user_id ) ) return;
		
		$user_lists = get_user_meta( $user_id, '_mylists', true );
		
		if ( $user_lists ) {

            UM()->Mailchimp_API()->api()->user_id = $user_id;
			
			$lists = UM()->Mailchimp_API()->api()->has_lists();
			if ( $lists ) {
				
				delete_option( "um_cache_userdata_{$user_id}" );

				foreach( $lists as $post_id ) { $list = UM()->Mailchimp_API()->api()->fetch_list($post_id);
					if ( isset( $user_lists[$list['id']] ) ) {
                        UM()->Mailchimp_API()->api()->update( $list['id'], $list['merge_vars'] );
					}
				}
				
			}
			
		}

	}
	
	/***
	***	@hook after registering users
	***/
	add_action( 'um_registration_complete', 'um_mailchimp_add_user_after_signup', 1, 2 );
	function um_mailchimp_add_user_after_signup( $user_id, $args ) {
		if ( ! isset( $args['um-mailchimp'] ) ) return;
		
		$lists = UM()->Mailchimp_API()->api()->has_lists( false, $user_id );
		
		if ( $lists ) {
			delete_option( "um_cache_userdata_{$user_id}" );
			foreach( $lists as $post_id ) { 

				$list = UM()->Mailchimp_API()->api()->fetch_list( $post_id );
				if ( isset ( $args['um-mailchimp'][ $list['id'] ] ) ) {
                        UM()->Mailchimp_API()->api()->user_id = $user_id;
                        UM()->Mailchimp_API()->api()->subscribe( $list['id'], $list['merge_vars'] );
                        UM()->Mailchimp_API()->api()->lists_subscribed[ ] = $list['id'];
				}

			}
			
		}
		
		
	}

	
	/***
	***	@hook in account update to subscribe/unsubscribe users
	***/
	add_action('um_post_account_update', 'um_mailchimp_account_update');
	function um_mailchimp_account_update() {
		$user_id = um_user('ID');

        UM()->Mailchimp_API()->api()->user_id = $user_id;
			
		$lists = UM()->Mailchimp_API()->api()->has_lists();
		$user_lists = get_user_meta( $user_id, '_mylists', true );

		if ( $lists ) {
			
			delete_option( "um_cache_userdata_{$user_id}" );

			if( UM()->options()->get('account_tab_notifications') ) {

				foreach ($lists as $post_id) {
					$list = UM()->Mailchimp_API()->api()->fetch_list( $post_id );

					if (isset ( $_POST['um-mailchimp'][$list['id']] )) {

						if (!UM()->Mailchimp_API()->api()->is_subscribed( $list['id'] )) {
							UM()->Mailchimp_API()->api()->subscribe( $list['id'], $list['merge_vars'] );
						}

					} else {

						if (isset( $user_lists[$list['id']] ) || empty( $user_lists )) { // must be a subscriber to unsubscribe
							UM()->Mailchimp_API()->api()->unsubscribe( $list['id'] );
						}

					}

				}

			}
			
			foreach( $lists as $post_id ) { $list = UM()->Mailchimp_API()->api()->fetch_list($post_id);
					if ( isset( $user_lists[$list['id']] ) ) {
                        UM()->Mailchimp_API()->api()->update( $list['id'], $list['merge_vars'] );
					}
			}
			
		}

	}