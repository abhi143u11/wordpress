<?php
/**
* Plugin Name: SI Projects CRM
* Plugin URI: #
* Version: 1.0
* Author: SolidDev
* Author URI: http://soliddev.us
* Description: A simple CRM system for WordPress
* License: GPL2
*/
class siProjectsCRM {
	/**
	* Constructor. Called when plugin is initialised
	*/
	function siProjectsCRM() {
		add_action('init', array(&$this, 'register_project_custom_post_type'));
	}
	
	/**
	* Registers a Custom Post Type called Projects
	*/
	function register_project_custom_post_type() {
		register_post_type('project', array(
            'labels' => array(
				'name'               => _x( 'Projects', 'post type general name', 'siProjectsCRM' ),
				'singular_name'      => _x( 'Project', 'post type singular name', 'siProjectsCRM' ),
				'menu_name'          => _x( 'Projects', 'admin menu', 'siProjectsCRM' ),
				'name_admin_bar'     => _x( 'Project', 'add new on admin bar', 'siProjectsCRM' ),
				'add_new'            => _x( 'Add New', 'project', 'siProjectsCRM' ),
				'add_new_item'       => __( 'Add New Project', 'siProjectsCRM' ),
				'new_item'           => __( 'New Project', 'siProjectsCRM' ),
				'edit_item'          => __( 'Edit Project', 'siProjectsCRM' ),
				'view_item'          => __( 'View Project', 'siProjectsCRM' ),
				'all_items'          => __( 'All Projects', 'siProjectsCRM' ),
				'search_items'       => __( 'Search Projects', 'siProjectsCRM' ),
				'parent_item_colon'  => __( 'Parent Projects:', 'siProjectsCRM' ),
				'not_found'          => __( 'No projects found.', 'siProjectsCRM' ),
				'not_found_in_trash' => __( 'No projects found in Trash.', 'siProjectsCRM' ),
			),
            
            // Frontend
            'has_archive' => false,
            'public' => true,
            'publicly_queryable' => true,
            'taxonomies' => array('category'),
            
            // Admin
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-grid-view',
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
	* Registers a Meta Box on our Project Custom Post Type, called 'Project Details'
	*/
	function register_meta_boxes() {
		add_meta_box('project-details', 'Project Details', array(&$this, 'output_meta_box'), 'project', 'normal', 'high');	
	}
	
	/**
	* Output a Project Details meta box
	*
	* @param WP_Post $post WordPress Post object
	*/
	function output_meta_box($post) {
		$project = get_post_meta($post->ID, '_project_name', true);
		
		// Add a nonce field so we can check for it later.
		wp_nonce_field('save_project', 'projects_nonce');
		
		// Output label and field
		echo ('<label for="project_name">'.__('Project Name', 'siProjectsCRM').'</label>');
		echo ('<input type="text" name="project_name" id="project_name" value="'.esc_attr($project).'" />');
	}
	
	/**
	* Saves the meta box field data
	*
	* @param int $post_id Post ID
	*/
	function save_meta_boxes($post_id) {
		// Check if our nonce is set.
		if (!isset($_POST['projects_nonce'])) {
			return $post_id;	
		}
		
		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['projects_nonce'], 'save_project')) {
			return $post_id;
		}
		
		// Check this is the Project Custom Post Type
		if ($_POST['post_type'] != 'project') {
			return $post_id;
		}
	    
		// Check the logged in user has permission to edit this post
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	    
		// OK to save meta data
		$email = sanitize_text_field($_POST['project_name']);
		update_post_meta($post_id, '_project_name', $email);
	}
}

$siProjectsCRM = new siProjectsCRM;

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

?>