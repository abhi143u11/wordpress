<?php

function shiftnav_pro_menu_item_settings( $settings ){
	
	

	$settings['general'][30] = array(
		'id' 		=> 'icon',
		'title'		=> __( 'Icon', 'shiftnav' ),
		'type'		=> 'icon',
		'default' 	=> '',
		'desc'		=> '',
		'ops'		=> shiftnav_get_icon_ops()
	);

	$settings['general'][50] = array(
		'id' 		=> 'custom_url',
		'title'		=> __( 'Custom URL' , 'shiftnav' ),
		'type'		=> 'text',
		'default' 	=> '',
		'desc'		=> __( 'Customize your link URL - you can use shortcodes here.  Your setting will be escaped with the esc_url() function', 'shiftnav' ),
	);

	$settings['submenu'][20] = array(
		'id' 		=> 'submenu_type',
		'title'		=> __( 'Submenu Type', 'shiftnav' ),
		'type'		=> 'select',
		'default'	=> 'default',
		'desc'		=>  __( 'Overrides the default submenu type, which can be set in the Control Panel for each menu. ' , 'shiftnav' ),
		'ops'		=> array(
						'default'	=>  __( 'Menu Default', 'shiftnav' ),
						'always'	=>	__( 'Always visible', 'shiftnav' ),
						'accordion'	=>	__( 'Accordion', 'shiftnav' ),
						'shift'		=>	__( 'Shift', 'shiftnav' ),
					),
	);

	return $settings;
}
add_filter( 'shiftnav_menu_item_settings' , 'shiftnav_pro_menu_item_settings' );