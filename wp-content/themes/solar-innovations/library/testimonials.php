<?php
/**
* Plugin Name: SI Testimonials CRM
* Plugin URI: #
* Version: 1.0
* Author: SolidDev
* Author URI: http://soliddev.us
* Description: A simple CRM system for WordPress
* License: GPL2
*/
class siTestimonialsCRM {
	/**
	* Constructor. Called when plugin is initialised
	*/
	function siTestimonialsCRM() {
		add_action('init', array(&$this, 'register_testimonial_custom_post_type'));
	}
	
	/**
	* Registers a Custom Post Type called Testimonials
	*/
	function register_testimonial_custom_post_type() {
		register_post_type('testimonial', array(
            'labels' => array(
				'name'               => _x( 'Testimonials', 'post type general name', 'siTestimonialsCRM' ),
				'singular_name'      => _x( 'Testimonial', 'post type singular name', 'siTestimonialsCRM' ),
				'menu_name'          => _x( 'Testimonials', 'admin menu', 'siTestimonialsCRM' ),
				'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'siTestimonialsCRM' ),
				'add_new'            => _x( 'Add New', 'testimonial', 'siTestimonialsCRM' ),
				'add_new_item'       => __( 'Add New Testimonial', 'siTestimonialsCRM' ),
				'new_item'           => __( 'New Testimonial', 'siTestimonialsCRM' ),
				'edit_item'          => __( 'Edit Testimonial', 'siTestimonialsCRM' ),
				'view_item'          => __( 'View Testimonial', 'siTestimonialsCRM' ),
				'all_items'          => __( 'All Testimonials', 'siTestimonialsCRM' ),
				'search_items'       => __( 'Search Testimonials', 'siTestimonialsCRM' ),
				'parent_item_colon'  => __( 'Parent Testimonials:', 'siTestimonialsCRM' ),
				'not_found'          => __( 'No testimonials found.', 'siTestimonialsCRM' ),
				'not_found_in_trash' => __( 'No testimonials found in Trash.', 'siTestimonialsCRM' ),
			),
            
            // Frontend
            'has_archive' => false,
            'public' => true,
            'publicly_queryable' => true,
            'taxonomies' => array('category'),
            
            // Admin
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-format-chat',
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
	* Registers a Meta Box on our Testimonials Custom Post Type, called 'Testimonials Details'
	*/
	function register_meta_boxes() {
		add_meta_box('testimonial-details', 'Testimonials Details', array(&$this, 'output_meta_box'), 'testimonial', 'normal', 'high');	
	}
	
	/**
	* Output a Testimonials Details meta box
	*
	* @param WP_Post $post WordPress Post object
	*/
	function output_meta_box($post) {
		$testimonial = get_post_meta($post->ID, '_testimonial_name', true);
		
		// Add a nonce field so we can check for it later.
		wp_nonce_field('save_testimonial', 'testimonials_nonce');
		
		// Output label and field
		echo ('<label for="testimonial_name">'.__('Testimonials Name', 'siTestimonialsCRM').'</label>');
		echo ('<input type="text" name="testimonial_name" id="testimonial_name" value="'.esc_attr($testimonial).'" />');
	}
	
	/**
	* Saves the meta box field data
	*
	* @param int $post_id Post ID
	*/
	function save_meta_boxes($post_id) {
		// Check if our nonce is set.
		if (!isset($_POST['testimonials_nonce'])) {
			return $post_id;	
		}
		
		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['testimonials_nonce'], 'save_testimonial')) {
			return $post_id;
		}
		
		// Check this is the Testimonials Custom Post Type
		if ($_POST['post_type'] != 'testimonial') {
			return $post_id;
		}
	    
		// Check the logged in user has permission to edit this post
		if (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	    
		// OK to save meta data
		$email = sanitize_text_field($_POST['testimonial_name']);
		update_post_meta($post_id, '_testimonial_name', $email);
	}
}

$siTestimonialsCRM = new siTestimonialsCRM;
?>