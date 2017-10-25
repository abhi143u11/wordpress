<?php
/**
* Plugin Name: SI Specifications CRM
* Plugin URI: #
* Version: 1.0
* Author: SolidDev
* Author URI: http://soliddev.us
* Description: A simple CRM system for WordPress
* License: GPL2
*/
class siSpecificationsCRM {
	/**
	* Constructor. Called when plugin is initialised
	*/
	function siSpecificationsCRM() {
		add_action('init', array(&$this, 'register_specification_custom_post_type'));
	}
	
	/**
	* Registers a Custom Post Type called Specifications
	*/
	function register_specification_custom_post_type() {
		register_post_type('specification', array(
            'labels' => array(
				'name'               => _x( 'Specifications', 'post type general name', 'siSpecificationsCRM' ),
				'singular_name'      => _x( 'Specification', 'post type singular name', 'siSpecificationsCRM' ),
				'menu_name'          => _x( 'Specifications', 'admin menu', 'siSpecificationsCRM' ),
				'name_admin_bar'     => _x( 'Specification', 'add new on admin bar', 'siSpecificationsCRM' ),
				'add_new'            => _x( 'Add New', 'specification', 'siSpecificationsCRM' ),
				'add_new_item'       => __( 'Add New Specification', 'siSpecificationsCRM' ),
				'new_item'           => __( 'New Specification', 'siSpecificationsCRM' ),
				'edit_item'          => __( 'Edit Specification', 'siSpecificationsCRM' ),
				'view_item'          => __( 'View Specification', 'siSpecificationsCRM' ),
				'all_items'          => __( 'All Specifications', 'siSpecificationsCRM' ),
				'search_items'       => __( 'Search Specifications', 'siSpecificationsCRM' ),
				'parent_item_colon'  => __( 'Parent Specifications:', 'siSpecificationsCRM' ),
				'not_found'          => __( 'No specifications found.', 'siSpecificationsCRM' ),
				'not_found_in_trash' => __( 'No specifications found in Trash.', 'siSpecificationsCRM' ),
			),
            
            // Frontend
            'has_archive' => false,
            'public' => true,
            'publicly_queryable' => true,
            'taxonomies' => array('category'),
            
            // Admin
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-media-spreadsheet',
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
	* Registers a Meta Box on our Specification Custom Post Type, called 'Specification Details'
	*/
	function register_meta_boxes() {
		add_meta_box('specification-details', 'Specification Details', array(&$this, 'output_meta_box'), 'specification', 'normal', 'high');	
	}
	
	/**
	* Output a Specification Details meta box
	*
	* @param WP_Post $post WordPress Post object
	*/
	function output_meta_box($post) {
		$specification = get_post_meta($post->ID, '_specification_name', true);
		
		// Add a nonce field so we can check for it later.
		wp_nonce_field('save_specification', 'specifications_nonce');
		
		// Output label and field
		echo ('<label for="specification_name">'.__('Specification Name', 'siSpecificationsCRM').'</label>');
		echo ('<input type="text" name="specification_name" id="specification_name" value="'.esc_attr($specification).'" />');
	}
	
	/**
	* Saves the meta box field data
	*
	* @param int $post_id Post ID
	*/
	function save_meta_boxes($post_id) {
		// Check if our nonce is set.
		if (!isset($_POST['specifications_nonce'])) {
			return $post_id;	
		}
		
		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['specifications_nonce'], 'save_specification')) {
			return $post_id;
		}
		
		// Check this is the Specification Custom Post Type
		if ($_POST['post_type'] != 'specification') {
			return $post_id;
		}
	    
		// Check the logged in user has permission to edit this post
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	    
		// OK to save meta data
		$email = sanitize_text_field($_POST['specification_name']);
		update_post_meta($post_id, '_specification_name', $email);
	}
}

$siSpecificationsCRM = new siSpecificationsCRM;
/*
function acf_load_select_product( $field ) {
  // Reset choices
  $field['choices'] = array();
 
  // Get field from options page
  $products_and_types = get_field('products_and_types', 'options');
 
  // Get only products in array
  foreach ($products_and_types as $key => $value) {
    $products[] = $value['product'];
  }
 
  // Sort products alphabetically
  natsort( $products );
 
  // Populate choices
  foreach( $products as $choice ) {
    $field['choices'][ $choice ] = $choice;
  }
 
  // Return choices
  return $field;
 
}
// Populate select field using filter
add_filter('acf/load_field/key=field_52b1b7007bfa4', 'acf_load_select_product');
*/
?>