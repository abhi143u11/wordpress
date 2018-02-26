<?php

// Exit if executed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ultimate Member Dependency Checker
 *
 * @version 1.0.0
 *
 * Checks if Ultimate Member plugin is enabled
 */
class UM_Dependencies {

	private static $active_plugins;


    /**
     * Get all active plugins
     */
	public static function init() {

		self::$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() )
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}


    /**
     * Check if UltimateMember core plugin is active
     *
     * @return bool
     */
	public static function ultimatemember_active_check() {

		if ( ! self::$active_plugins ) self::init();

		return in_array( 'ultimate-member/index.php', self::$active_plugins ) || array_key_exists( 'ultimate-member/index.php', self::$active_plugins ) || in_array( 'ultimate-member/index.php', self::$active_plugins ) || array_key_exists( 'ultimate-member/index.php', self::$active_plugins );

	}


    /**
     * Check if bbPress plugin is active
     *
     * @return bool
     */
    public static function bbpress_active_check() {

        if ( ! self::$active_plugins ) self::init();

        return in_array( 'bbpress/bbpress.php', self::$active_plugins ) || array_key_exists( 'bbpress/bbpress.php', self::$active_plugins );

    }


    /**
     * Check if myCRED plugin is active
     *
     * @return bool
     */
    public static function mycred_active_check() {

        if ( ! self::$active_plugins ) self::init();

        return in_array( 'mycred/mycred.php', self::$active_plugins ) || array_key_exists( 'mycred/mycred.php', self::$active_plugins );

    }


    /**
     * Check if Woocommerce plugin is active
     *
     * @return bool
     */
    public static function woocommerce_active_check() {

        if ( ! self::$active_plugins ) self::init();

        return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );

    }


    /**
     * @param string $extension_version Extension version
     * @return mixed
     */
	public static function ultimatemember_version_check( $extension_version ) {

        return version_compare( ultimatemember_version, $extension_version, '>=' );

	}


	/**
     * @param string $extension_version Extension version
     * @return mixed
     */
	public static function php_version_check( $extension_version ) {

        return version_compare( phpversion(), $extension_version, '>=' );

	}


    /**
     * @return mixed|void
     */
    public static function ultimatemember_reviews_setup() {

        return get_option( '__ultimatemember_reviews_setup' );

    }

}

if ( ! function_exists( 'is_um_active' ) ) {
    /**
     * Check UltimateMember core is active
     *
     * @return bool active - true | inactive - false
     */
    function is_um_active() {
        return UM_Dependencies::ultimatemember_active_check();
    }
}


if ( ! function_exists( 'is_um_version_required' ) ) {
    /**
     * Check UltimateMember core required version
     *
     * @return bool  Larger then required - true | Less than necessary - false
     */
    function is_um_version_required( $version ) {
        return UM_Dependencies::ultimatemember_version_check( $version );
    }
}
