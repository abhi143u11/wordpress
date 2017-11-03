<?php
/**
* Plugin Name: SI Downloads CRM
* Plugin URI: #
* Version: 1.0
* Author: SolidDev
* Author URI: http://soliddev.us
* Description: A simple CRM system for WordPress
* License: GPL2
*/
class siDownloadsCRM {
	/**
	* Constructor. Called when plugin is initialised
	*/
	function siDownloadsCRM() {
		add_action('init', array(&$this, 'register_download_custom_post_type'));
	}
	
	/**
	* Registers a Custom Post Type called Downloads
	*/
	function register_download_custom_post_type() {
		register_post_type('download', array(
            'labels' => array(
				'name'               => _x( 'Downloads', 'post type general name', 'siDownloadsCRM' ),
				'singular_name'      => _x( 'Download', 'post type singular name', 'siDownloadsCRM' ),
				'menu_name'          => _x( 'Downloads', 'admin menu', 'siDownloadsCRM' ),
				'name_admin_bar'     => _x( 'Download', 'add new on admin bar', 'siDownloadsCRM' ),
				'add_new'            => _x( 'Add New', 'download', 'siDownloadsCRM' ),
				'add_new_item'       => __( 'Add New Download', 'siDownloadsCRM' ),
				'new_item'           => __( 'New Download', 'siDownloadsCRM' ),
				'edit_item'          => __( 'Edit Download', 'siDownloadsCRM' ),
				'view_item'          => __( 'View Download', 'siDownloadsCRM' ),
				'all_items'          => __( 'All Downloads', 'siDownloadsCRM' ),
				'search_items'       => __( 'Search Downloads', 'siDownloadsCRM' ),
				'parent_item_colon'  => __( 'Parent Downloads:', 'siDownloadsCRM' ),
				'not_found'          => __( 'No downloads found.', 'siDownloadsCRM' ),
				'not_found_in_trash' => __( 'No downloads found in Trash.', 'siDownloadsCRM' ),
			),
            
            // Frontend
            'has_archive' => false,
            'public' => true,
            'publicly_queryable' => true,
            'taxonomies' => array('category'),
            
            // Admin
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-images-alt',
            'menu_position' => 2,
            'query_var' => true,
            'show_in_menu' => true,
            'show_ui' => true,
            'supports' => array(
            	'title',
            	'editor',
            	'thumbnail',
            	'revisions',
            	'page-formats'
            	
            ),
        ));	
	}

	/**
	* Registers a Meta Box on our Downloads Custom Post Type, called 'Downloads Details'
	*/
	function register_meta_boxes() {
		add_meta_box('download-details', 'Downloads Details', array(&$this, 'output_meta_box'), 'download', 'normal', 'high');	
	}
	
	/**
	* Output a Downloads Details meta box
	*
	* @param WP_Post $post WordPress Post object
	*/
	function output_meta_box($post) {
		$download = get_post_meta($post->ID, '_download_name', true);
		
		// Add a nonce field so we can check for it later.
		wp_nonce_field('save_download', 'downloads_nonce');
		
		// Output label and field
		echo ('<label for="download_name">'.__('Downloads Name', 'siDownloadsCRM').'</label>');
		echo ('<input type="text" name="download_name" id="download_name" value="'.esc_attr($download).'" />');
	}
	
	/**
	* Saves the meta box field data
	*
	* @param int $post_id Post ID
	*/
	function save_meta_boxes($post_id) {
		// Check if our nonce is set.
		if (!isset($_POST['downloads_nonce'])) {
			return $post_id;	
		}
		
		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['downloads_nonce'], 'save_download')) {
			return $post_id;
		}
		
		// Check this is the Downloads Custom Post Type
		if ($_POST['post_type'] != 'download') {
			return $post_id;
		}
	    
		// Check the logged in user has permission to edit this post
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	    
		// OK to save meta data
		$email = sanitize_text_field($_POST['download_name']);
		update_post_meta($post_id, '_download_name', $email);
	}
}

$siDownloadsCRM = new siDownloadsCRM;
?>