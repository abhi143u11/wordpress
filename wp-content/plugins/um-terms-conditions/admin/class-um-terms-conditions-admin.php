<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ultimatemember.com/
 * @since      1.0.0
 *
 * @package    Um_Terms_Conditions
 * @subpackage Um_Terms_Conditions/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Um_Terms_Conditions
 * @subpackage Um_Terms_Conditions/admin
 * @author     Ultimate Member <support@ultimatemember.com>
 */
class Um_Terms_Conditions_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Um_Terms_Conditions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Um_Terms_Conditions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/um-terms-conditions-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Um_Terms_Conditions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Um_Terms_Conditions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/um-terms-conditions-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_metabox(){
			global $current_screen;

		if( $current_screen->id == 'um_form'){
			add_action( 'add_meta_boxes', array(&$this, 'add_metabox_form'), 1 );
			add_action( 'save_post', array(&$this, 'save_metabox_form'), 10, 2 );
		}
	}

	function add_metabox_form(){
		add_meta_box('um-admin-form-register_terms_conditions', __('Terms & Conditions'), array(&$this, 'load_metabox_form'), 'um_form', 'side', 'default');
	}

	function save_metabox_form(){
		add_meta_box('um-admin-form-register_terms_conditions', __('Terms & Conditions'), array(&$this, 'load_metabox_form'), 'um_form', 'side', 'default');
	}

	function load_metabox_form(){
		global $ultimatemember;
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/um-terms-conditions-admin-form-sidebar.php';

	}

		/***
	***	@add a helper tooltip
	***/
	function _tooltip( $text ){
	
		$output = '<span class="um-admin-tip n">';
		$output .= '<span class="um-admin-tipsy-n" title="'.$text.'"><i class="dashicons dashicons-editor-help"></i></span>';
		$output .= '</span>';
		
		return $output;
	
	}
	
	/***
	***	@add a helper tooltip
	***/
	function tooltip( $text, $e = false ){
	
		?>
		
		<span class="um-admin-tip">
			<?php if ($e == 'e' ) { ?>
			<span class="um-admin-tipsy-e" title="<?php echo $text; ?>"><i class="dashicons dashicons-editor-help"></i></span>
			<?php } else { ?>
			<span class="um-admin-tipsy-w" title="<?php echo $text; ?>"><i class="dashicons dashicons-editor-help"></i></span>
			<?php } ?>
		</span>
		
		<?php
	
	}
	
	/***
	***	@on/off UI
	***/
	function ui_on_off( $id, $default=0, $is_conditional=false, $cond1='', $cond1_show='', $cond1_hide='', $yes='', $no='' ) {

		$meta = (string)get_post_meta( get_the_ID(), $id, true );
		if ( $meta === '0' && $default > 0 ) {
			$default = $meta;
		}
		
		$yes = ( !empty( $yes ) ) ? $yes : __('Yes');
		$no = ( !empty( $no ) ) ? $no : __('No');
		
		if (isset($this->postmeta[$id][0]) || $meta ) {
			$active = ( isset( $this->postmeta[$id][0] ) ) ? $this->postmeta[$id][0] : $meta;
		} else {
			$active = $default;
		}
		
		if ($is_conditional == true) {
			$is_conditional = ' class="um-adm-conditional" data-cond1="'.$cond1.'" data-cond1-show="'.$cond1_show.'" data-cond1-hide="'.$cond1_hide.'"';
		}
		
		?>

		<span class="um-admin-yesno">
			<span class="btn pos-<?php echo $active; ?>"></span>
			<span class="yes" data-value="1"><?php echo $yes; ?></span>
			<span class="no" data-value="0"><?php echo $no; ?></span>
			<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $active; ?>" <?php echo $is_conditional; ?> />
		</span>
	
		<?php
	}

}
