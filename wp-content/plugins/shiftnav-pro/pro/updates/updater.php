<?php

require_once( 'backup.php' );

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'SHIFTNAV_UPDATES_URL', 'http://sevenspark.com' ); 

// the name of your product. This should match the download name in EDD exactly
define( 'SHIFTNAV_UPDATES_NAME', 'ShiftNav Pro' ); 

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

function shiftnav_plugin_updater() {

	// retrieve our license key from the DB
	$license_key = trim( shiftnav_op( 'license_code' , 'updates' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( SHIFTNAV_UPDATES_URL, SHIFTNAV_FILE, array( 
			'version' 	=> SHIFTNAV_VERSION, 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => SHIFTNAV_UPDATES_NAME, 	// name of this plugin
			'author' 	=> 'Chris Mavricos, SevenSpark',  // author of this plugin
			'url'		=> home_url(),
		)
	);

}
add_action( 'admin_init', 'shiftnav_plugin_updater', 0 );





//UPDATES SETTINGS TAB

add_filter( 'shiftnav_settings_panel_sections' , 'shiftnav_updater_settings_panel' );
function shiftnav_updater_settings_panel( $sections = array() ){
	$sections[] = array(
		'id'	=> SHIFTNAV_PREFIX.'updates',
		'title'	=> __( 'Updates' , 'shiftnav' ),
	);

	return $sections;

}

add_filter( 'shiftnav_settings_panel_fields' , 'shiftnav_updater_settings_panel_fields' );
function shiftnav_updater_settings_panel_fields( $fields = array() ){

	$updates = SHIFTNAV_PREFIX.'updates';

	$desc = __( 'Enter your license code to receive updates', 'shiftnav' );

	$license_data = get_option( 'shiftnav_license_data' );
	if( $license_data ){
		$license_status = $license_data->license;

		switch( $license_status ){
			case 'invalid':
				$desc = '<span class="shiftnav-license-invalid">'.__( 'License Invalid' , 'shiftnav' ).'</span>';
				$desc.= '<span class="shiftnav-license-error">'.$license_data->error;
				if( $license_data->error == 'expired' ){
					$desc.= ' '.$license_data->expires;
				}
				$desc.= '</span>';
				break;
			case 'valid': 
				$desc = __( 'License is valid' , 'shiftnav' );
				break;
		};
	}


	


	// $fields[$updates][] = array(
	// 		'name'	=> 'backups_header',
	// 		'label' => __( 'Custom Asset Backups' , 'shiftnav' ),
	// 		'desc'	=> __( 'ShiftNav will attempt to automatically backup and restore your custom.css and custom.js files when you update', 'shiftnav' ),
	// 		'type'	=> 'header',
	// 		'group'	=> 'backups',
	//	);

	$fields[$updates][] = array(
			'name'	=> 'backup_custom_assets',
			'label'	=> __( 'Backup custom assets' , 'shiftnav' ),
			'desc'	=> __( 'Automatically backup custom.css and custom.less so that they can be restored after updating the plugin', 'shiftnav' ),
			'type'	=> 'checkbox',
			'default'	=> 'on',
			'group'	=> 'backups',
		);

	$fields[$updates][] = array(
			'name'	=> 'backup_notice',
			'label' => __( 'Automatic backups status' , 'shiftnav' ),
			'desc'	=> shiftnav_field_backup_notice(),
			'type'	=> 'html',
			'group'	=> 'backups',
		);


	// $fields[$updates][] = array(
	// 		'name'	=> 'update_settings',
	// 		'label' => __( 'Update Notifications' , 'shiftnav' ), //__( 'Automatic Updates' , 'shiftnav' ),
	// 		'desc'	=> __( 'Enter your Envato info to receive update notifications', 'shiftnav' ),
	// 		'type'	=> 'header',
	// 		'group'	=> 'updates',
	// 	);


	$fields[$updates][] = array(
			'name'	=> 'license_code',
			'label'	=> __( 'License Code' , 'shiftnav' ),
			'desc'	=> $desc,
			'type'	=> 'text',
	);

	


	return $fields;
}





/************************************
* this illustrates how to activate 
* a license key
*************************************/

// add_action( 'shiftnav_settings_panel' , 'shiftnav_update_license_activation' );
// function shiftnav_update_license_activation(){

// 	shiftp( $_POST );

// 	if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ){
// 		if( 'valid' != get_option( 'shiftnav_license_status' , false ) ){
// 			$license = shiftnav_op( 'license_key' , 'updates' );
// 			if( $license ){
// 				//shiftnav_activate_license( $license );
// 			}
// 		}
// 	}
// }

//Only runs when license value changes
function shiftnav_activate_license( $old_value  ) {

	// retrieve the license from the database
	$license = trim( shiftnav_op( 'license_code' , 'updates' ) );

	//$license = $value['license_code'];

	if( $license == '' ){
		update_option( 'shiftnav_license_status' , '' );
		update_option( 'shiftnav_license_data' , '' );
		return;
	}

	// data to send in our API request
	$api_params = array( 
		'edd_action'=> 'activate_license', 
		'license' 	=> $license, 
		'item_name' => urlencode( SHIFTNAV_UPDATES_NAME ), // the name of our product in EDD
		'url'       => home_url()
	);

	
	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, SHIFTNAV_UPDATES_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

	// make sure the response came back okay
	if ( is_wp_error( $response ) )
		return false;

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	update_option( 'shiftnav_license_data', $license_data );
		//->license (valid or invalid)
		//->error (expired)
		//->expires (2015-03-23 22:36:04)
}
add_action( 'update_option_'.SHIFTNAV_PREFIX.'updates' , 'shiftnav_activate_license' , 10 , 2 );
//add_action( 'admin_init', 'shiftnav_activate_license' , 10 , 1 );















//////////BACKUPS

function shiftnav_field_backup_notice(){

	$note = $msg = '';

	$custom_dir = trailingslashit( SHIFTNAV_DIR ).'custom/';

	//Find the Backups directory
	$uploads = wp_upload_dir();
	
	$uploads_dir = trailingslashit( $uploads['basedir'] );
	$backups_dir = $uploads_dir . 'shiftnav_backups/';

	$uploads_url = trailingslashit( $uploads['baseurl'] );
	$backups_url = $uploads_url . 'shiftnav_backups/';


	if( !is_writable( $uploads_dir ) ){
		//TODO - readd this: <strong>These files will be lost when updating if not backed up first</strong></p>  
		$note = '<p>The uploads directory is not writable by the server ( <code>'.$uploads_dir.'</code> ).  </p><p>ShiftNav will not automatically be able to back up your <strong><code>custom.css</code></strong> and <strong><code>custom.js</code></strong> if you create them.  Please make this directory writable if you wish to automatically back up these files, otherwise you can back them up and restore manually after plugin update. <p>(If you are not using <code>custom.css</code> or <code>custom.js</code>, you can safely ignore this message)</p>';

		$msg.= '<div id="setting-error-update-write" class="shiftnav-settings-notice shiftnav-settings-notice-large shiftnav-settings-error">' . 
				'<i class="shiftnav-settings-notice-icon fa fa-warning"></i>'.
				'<strong>Automatic Backups Not Available</strong>'.
				'<p>'.$note.'</p></div>';
	}
	else{

		$backups_exist = false;

		$custom_css = $backups_dir . 'custom.css';
		$custom_css_url = $backups_url . 'custom.css';
		if( file_exists( $custom_css ) ){

			$backups_exist = true;

			$msg.= '<div class="shiftnav-settings-notice shiftnav-settings-success">' . 
				'<i class="shiftnav-settings-notice-icon fa fa-check"></i>'.
				'<strong>custom.css backup available</strong>'.
				' <a href="'.$custom_css_url .'" target="_blank" download="custom.css"><i class="fa fa-download"></i></a>'.
				'</div>';
		}

		$custom_less = $backups_dir . 'custom.less';
		$custom_less_url = $backups_url . 'custom.less';
		if( file_exists( $custom_less ) ){

			$backups_exist = true;

			$msg.= '<div class="shiftnav-settings-notice shiftnav-settings-success">' . 
				'<i class="shiftnav-settings-notice-icon fa fa-check"></i>'.
				'<strong>custom.less backup available</strong>'.
				' <a href="'.$custom_less_url .'" target="_blank" download="custom.less"><i class="fa fa-download"></i></a>'.
				'</div>';
		}

		$custom_js = $backups_dir . 'custom.js';
		$custom_js_url = $backups_url . 'custom.js';
		if( file_exists( $custom_js ) ){

			$backups_exist = true;

			$msg.= '<div class="shiftnav-settings-notice shiftnav-settings-success">' . 
				'<i class="shiftnav-settings-notice-icon fa fa-check"></i>'.
				'<strong>custom.js backup available</strong>'.
				' <a href="'.$custom_js_url .'" download="custom.js" target="_blank"><i class="fa fa-download"></i></a>'.
				'</div>';
		}



		if( file_exists( $backups_dir ) ){

			if( file_exists( $custom_dir . 'custom.css' ) && !is_writable( $backups_dir . 'css' ) ){
				$msg.= '<div class="shiftnav-settings-notice shiftnav-settings-error">' . 
					'<i class="shiftnav-settings-notice-icon fa fa-warning"></i>'.
					'<strong>Daily CSS backups not writable</strong>'.
					' <p>ShiftNav attempts to save daily backups, but this directory is not writable. <code>'.$backups_dir.'css/</code></p>'.
					'</div>';
			}

			if( file_exists( $custom_dir . 'custom.less' ) && !is_writable( $backups_dir . 'less' ) ){
				$msg.= '<div class="shiftnav-settings-notice shiftnav-settings-error">' . 
					'<i class="shiftnav-settings-notice-icon fa fa-warning"></i>'.
					'<strong>Daily LESS backups not writable</strong>'.
					' <p>ShiftNav attempts to save daily backups, but this directory is not writable. <code>'.$backups_dir.'less/</code></p>'.
					'</div>';
			}

			if( file_exists( $custom_dir . 'custom.js' ) && !is_writable( $backups_dir . 'js' ) ){
				$msg.= '<div class="shiftnav-settings-notice shiftnav-settings-error">' . 
					'<i class="shiftnav-settings-notice-icon fa fa-warning"></i>'.
					'<strong>Daily JS backups not writable</strong>'.
					' <p>ShiftNav attempts to save daily backups, but this directory is not writable. <code>'.$backups_dir.'js/</code></p>'.
					'</div>';
			}

		}



		if( !$backups_exist ){

			if( file_exists( $custom_dir.'custom.css' ) ||
				file_exists( $custom_dir.'custom.less' ) ||
				file_exists( $custom_dir.'custom.js' ) ){
				$msg.= '<div class="shiftnav-settings-notice shiftnav-settings-success"><i class="fa fa-info-circle shiftnav-settings-notice-icon"></i> No backups found.  If this message is present after refreshing, please check that your <code>/uploads</code> directory is writable.</div>';
			}
			else{
				$msg.= '<div class="shiftnav-settings-notice shiftnav-settings-success"><i class="fa fa-info-circle shiftnav-settings-notice-icon"></i> No custom assets in use.</div>';
			}
		}

	}

	

	

	
	
	

	return $msg;
}