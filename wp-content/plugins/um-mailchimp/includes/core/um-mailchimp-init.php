<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class UM_Mailchimp_API {
    private static $instance;

    static public function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }


	function __construct() {
        // Global for backwards compatibility.
        $GLOBALS['um_mailchimp'] = $this;
        add_filter( 'um_call_object_Mailchimp_API', array( &$this, 'get_this' ) );

        $this->api();

        if ( UM()->is_request( 'admin' ) )
            $this->admin();

        add_action( 'init', array( &$this, 'create_taxonomies' ), 2 );
        add_action( 'plugins_loaded', array( &$this, 'init' ), 1 );

        add_filter( 'um_settings_default_values', array( &$this, 'default_settings' ), 10, 1 );
    }


    function default_settings( $defaults ) {
        $defaults = array_merge( $defaults, $this->setup()->settings_defaults );
        return $defaults;
    }


    function get_this() {
        return $this;
    }


    /**
     * @return um_ext\um_mailchimp\core\Mailchimp_Setup()
     */
    function setup() {
        if ( empty( UM()->classes['um_mailchimp_setup'] ) ) {
            UM()->classes['um_mailchimp_setup'] = new um_ext\um_mailchimp\core\Mailchimp_Setup();
        }
        return UM()->classes['um_mailchimp_setup'];
    }


    /**
     * @return um_ext\um_mailchimp\core\Mailchimp_Func()
     */
    function api() {
        if ( empty( UM()->classes['um_mailchimp_main_api'] ) ) {
            UM()->classes['um_mailchimp_main_api'] = new um_ext\um_mailchimp\core\Mailchimp_Func();
        }
        return UM()->classes['um_mailchimp_main_api'];
    }


    /**
     * @return um_ext\um_mailchimp\core\Mailchimp_Admin()
     */
    function admin() {
        if ( empty( UM()->classes['um_mailchimp_admin'] ) ) {
            UM()->classes['um_mailchimp_admin'] = new um_ext\um_mailchimp\core\Mailchimp_Admin();
        }
        return UM()->classes['um_mailchimp_admin'];
    }


    /***
     ***	@Create a mailchimp post type
     ***/
    function create_taxonomies() {

        register_post_type( 'um_mailchimp', array(
            'labels' => array(
                'name' => __( 'MailChimp' ),
                'singular_name' => __( 'MailChimp' ),
                'add_new' => __( 'Add New List' ),
                'add_new_item' => __('Add New List' ),
                'edit_item' => __('Edit List'),
                'not_found' => __('You did not create any MailChimp lists yet'),
                'not_found_in_trash' => __('Nothing found in Trash'),
                'search_items' => __('Search MailChimp lists')
            ),
            'show_ui' => true,
            'show_in_menu' => false,
            'public' => false,
            'supports' => array('title')
        ) );

    }


    /***
	***	@Init
	***/
	function init() {

        //libs
		if ( ! class_exists('UM_MailChimp_V3') ) {
			require_once um_mailchimp_path . 'includes/lib/um-mailchimp-api-v3.php';
		}

		if ( ! class_exists('UM_MailChimp_Batch') ) {
			require_once um_mailchimp_path . 'includes/lib/um-mailchimp-batch.php';
		}
		
		require_once um_mailchimp_path . 'includes/lib/um-mailchimp-api.php';

		require_once um_mailchimp_path . 'includes/core/actions/um-mailchimp-account.php';
		require_once um_mailchimp_path . 'includes/core/actions/um-mailchimp-fields.php';

		require_once um_mailchimp_path . 'includes/core/filters/um-mailchimp-account.php';
		require_once um_mailchimp_path . 'includes/core/filters/um-mailchimp-settings.php';
		require_once um_mailchimp_path . 'includes/core/filters/um-mailchimp-fields.php';

	}

}

//create class var
add_action( 'plugins_loaded', 'um_init_mailchimp', -10, 1 );
function um_init_mailchimp() {
    if ( function_exists( 'UM' ) ) {
        UM()->set_class( 'Mailchimp_API', true );
    }
}