<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@dynamic profile page title
	***/
	add_filter('wp_title', 'um_dynamic_user_profile_pagetitle', 100000, 2 );
	add_filter('pre_get_document_title', 'um_dynamic_user_profile_pagetitle', 100000, 2 );
	/**
	 * fix for plugin "The SEO Framework"
	 * @link https://ru.wordpress.org/plugins/autodescription/
	 */
	add_filter('the_seo_framework_pro_add_title', 'um_dynamic_user_profile_pagetitle', 100000, 2 );
	function um_dynamic_user_profile_pagetitle( $title, $sep = '' ) {

		$profile_title = UM()->options()->get( 'profile_title' );

		if ( um_is_core_page('user') && um_get_requested_user() ) {

			um_fetch_user( um_get_requested_user() );

			$profile_title = um_convert_tags( $profile_title );

			$title = $profile_title;

			um_reset_user();

		}

		return $title;
	}

	/***
	***	@try and modify the page title in page
	***/
	add_filter('the_title', 'um_dynamic_user_profile_title', 100000, 2 );
	function um_dynamic_user_profile_title( $title, $id = '' ) {

		if( is_admin() ){
			return $title;
		}

		if (  $id == UM()->config()->permalinks['user'] && in_the_loop() ) {
			if ( um_is_core_page('user') && um_get_requested_user() ) {
				$title = um_get_display_name( um_get_requested_user() );
			} else if ( um_is_core_page('user') && is_user_logged_in() ) {
				$title = um_get_display_name( get_current_user_id() );
			}
		}


		if( ! function_exists('utf8_decode') ){
			return $title;
		}

		return (strlen($title)!==strlen(utf8_decode($title))) ? $title : utf8_encode($title);
	}



	/***
	*** @Add cover photo label of file size limit
	***/
	add_filter('um_predefined_fields_hook','um_change_profile_cover_photo_label',10,1);
	function um_change_profile_cover_photo_label( $args ){
		$max_size =  UM()->files()->format_bytes( $args['cover_photo']['max_size'] );
		list( $file_size, $unit ) = explode(' ', $max_size );

		if( $file_size >= 999999999  ){

		}else{
			$args['cover_photo']['upload_text'] .= '<small class=\'um-max-filesize\'>( '.__('max','ultimate-member').': <span>'.$file_size.$unit.'</span> )</small>';
		}
		return $args;
	}


	/***
	*** @Add profile photo label of file size limit
	***/
	add_filter('um_predefined_fields_hook','um_change_profile_photo_label',10,1);
	function um_change_profile_photo_label( $args ){
		$max_size =  UM()->files()->format_bytes( $args['profile_photo']['max_size'] );
		list( $file_size, $unit ) = explode(' ', $max_size );

		if( $file_size >= 999999999  ){

		}else{
			$args['profile_photo']['upload_text'] .= '<small class=\'um-max-filesize\'>( '.__('max','ultimate-member').': <span>'.$file_size.$unit.'</span> )</small>';
		}
		return $args;
	}
