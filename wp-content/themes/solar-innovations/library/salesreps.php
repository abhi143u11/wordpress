<?php
/**
* Plugin Name: SI Sales Reps CRM
* Plugin URI: #
* Version: 1.0
* Author: SolidDev
* Author URI: http://soliddev.us
* Description: A simple CRM system for WordPress
* License: GPL2
*/
class siSalesRepsCRM {
	/**
	* Constructor. Called when plugin is initialised
	*/
	function siSalesRepsCRM() {
		add_action('init', array(&$this, 'register_salesrep_custom_post_type'));
	}
	
	/**
	* Registers a Custom Post Type called Sales Reps
	*/
	function register_salesrep_custom_post_type() {
		register_post_type('salesrep', array(
            'labels' => array(
				'name'               => _x( 'Sales Reps', 'post type general name', 'siSalesRepsCRM' ),
				'singular_name'      => _x( 'Sales Rep', 'post type singular name', 'siSalesRepsCRM' ),
				'menu_name'          => _x( 'Sales Reps', 'admin menu', 'siSalesRepsCRM' ),
				'name_admin_bar'     => _x( 'Sales Rep', 'add new on admin bar', 'siSalesRepsCRM' ),
				'add_new'            => _x( 'Add New', 'salesrep', 'siSalesRepsCRM' ),
				'add_new_item'       => __( 'Add New Sales Rep', 'siSalesRepsCRM' ),
				'new_item'           => __( 'New Sales Rep', 'siSalesRepsCRM' ),
				'edit_item'          => __( 'Edit Sales Rep', 'siSalesRepsCRM' ),
				'view_item'          => __( 'View Sales Rep', 'siSalesRepsCRM' ),
				'all_items'          => __( 'All Sales Reps', 'siSalesRepsCRM' ),
				'search_items'       => __( 'Search Sales Reps', 'siSalesRepsCRM' ),
				'parent_item_colon'  => __( 'Parent Sales Reps:', 'siSalesRepsCRM' ),
				'not_found'          => __( 'No sales reps found.', 'siSalesRepsCRM' ),
				'not_found_in_trash' => __( 'No sales reps found in Trash.', 'siSalesRepsCRM' ),
			),
            
            // Frontend
            'has_archive' => false,
            'public' => true,
            'publicly_queryable' => true,
            'taxonomies' => array('category'),
            
            // Admin
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-businessman',
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
	* Registers a Meta Box on our Sales Reps Custom Post Type, called 'Sales Reps Details'
	*/
	function register_meta_boxes() {
		add_meta_box('salesrep-details', 'Sales Reps Details', array(&$this, 'output_meta_box'), 'salesrep', 'normal', 'high');	
	}
	
	/**
	* Output a Sales Reps Details meta box
	*
	* @param WP_Post $post WordPress Post object
	*/
	function output_meta_box($post) {
		$salesrep = get_post_meta($post->ID, '_salesrep_name', true);
		
		// Add a nonce field so we can check for it later.
		wp_nonce_field('save_salesrep', 'salesreps_nonce');
		
		// Output label and field
		echo ('<label for="salesrep_name">'.__('Sales Reps Name', 'siSalesRepsCRM').'</label>');
		echo ('<input type="text" name="salesrep_name" id="salesrep_name" value="'.esc_attr($salesrep).'" />');
	}
	
	/**
	* Saves the meta box field data
	*
	* @param int $post_id Post ID
	*/
	function save_meta_boxes($post_id) {
		// Check if our nonce is set.
		if (!isset($_POST['salesreps_nonce'])) {
			return $post_id;	
		}
		
		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['salesreps_nonce'], 'save_salesrep')) {
			return $post_id;
		}
		
		// Check this is the Sales Reps Custom Post Type
		if ($_POST['post_type'] != 'salesrep') {
			return $post_id;
		}
	    
		// Check the logged in user has permission to edit this post
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	    
		// OK to save meta data
		$email = sanitize_text_field($_POST['salesrep_name']);
		update_post_meta($post_id, '_salesrep_name', $email);
	}
}

$siSalesRepsCRM = new siSalesRepsCRM;
?>