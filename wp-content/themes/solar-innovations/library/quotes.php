<?php
/**
* Plugin Name: SI Quotes CRM
* Plugin URI: #
* Version: 1.0
* Author: SolidDev
* Author URI: http://soliddev.us
* Description: A simple CRM system for WordPress
* License: GPL2
*/
class siQuotesCRM {
	/**
	* Constructor. Called when plugin is initialised
	*/
	function siQuotesCRM() {
		add_action('init', array(&$this, 'register_quote_custom_post_type'));
	}
	
	/**
	* Registers a Custom Post Type called Quotes
	*/
	function register_quote_custom_post_type() {
		register_post_type('quote', array(
            'labels' => array(
				'name'               => _x( 'Quotes', 'post type general name', 'siQuotesCRM' ),
				'singular_name'      => _x( 'Quote', 'post type singular name', 'siQuotesCRM' ),
				'menu_name'          => _x( 'Quotes', 'admin menu', 'siQuotesCRM' ),
				'name_admin_bar'     => _x( 'Quote', 'add new on admin bar', 'siQuotesCRM' ),
				'add_new'            => _x( 'Add New', 'quote', 'siQuotesCRM' ),
				'add_new_item'       => __( 'Add New Quote', 'siQuotesCRM' ),
				'new_item'           => __( 'New Quote', 'siQuotesCRM' ),
				'edit_item'          => __( 'Edit Quote', 'siQuotesCRM' ),
				'view_item'          => __( 'View Quote', 'siQuotesCRM' ),
				'all_items'          => __( 'All Quotes', 'siQuotesCRM' ),
				'search_items'       => __( 'Search Quotes', 'siQuotesCRM' ),
				'parent_item_colon'  => __( 'Parent Quotes:', 'siQuotesCRM' ),
				'not_found'          => __( 'No quotes found.', 'siQuotesCRM' ),
				'not_found_in_trash' => __( 'No quotes found in Trash.', 'siQuotesCRM' ),
			),
            
            // Frontend
            'has_archive' => false,
            'public' => true,
            'publicly_queryable' => true,
            'taxonomies' => array('category'),
            
            // Admin
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-clipboard',
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
	* Registers a Meta Box on our Quotes Custom Post Type, called 'Quotes Details'
	*/
	function register_meta_boxes() {
		add_meta_box('quote-details', 'Quotes Details', array(&$this, 'output_meta_box'), 'quote', 'normal', 'high');	
	}
	
	/**
	* Output a Quotes Details meta box
	*
	* @param WP_Post $post WordPress Post object
	*/
	function output_meta_box($post) {
		$quote = get_post_meta($post->ID, '_quote_name', true);
		
		// Add a nonce field so we can check for it later.
		wp_nonce_field('save_quote', 'quotes_nonce');
		
		// Output label and field
		echo ('<label for="quote_name">'.__('Quotes Name', 'siQuotesCRM').'</label>');
		echo ('<input type="text" name="quote_name" id="quote_name" value="'.esc_attr($quote).'" />');
	}
	
	/**
	* Saves the meta box field data
	*
	* @param int $post_id Post ID
	*/
	function save_meta_boxes($post_id) {
		// Check if our nonce is set.
		if (!isset($_POST['quotes_nonce'])) {
			return $post_id;	
		}
		
		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['quotes_nonce'], 'save_quote')) {
			return $post_id;
		}
		
		// Check this is the Quotes Custom Post Type
		if ($_POST['post_type'] != 'quote') {
			return $post_id;
		}
	    
		// Check the logged in user has permission to edit this post
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	    
		// OK to save meta data
		$email = sanitize_text_field($_POST['quote_name']);
		update_post_meta($post_id, '_quote_name', $email);
	}
}

$siQuotesCRM = new siQuotesCRM;
?>