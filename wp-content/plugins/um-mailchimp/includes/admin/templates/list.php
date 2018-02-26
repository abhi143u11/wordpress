<div class="um-admin-metabox">

	<?php $fields = array();

	$lists = UM()->Mailchimp_API()->api()->get_lists();

	if ( ! empty( $lists ) ) {
		foreach ( $lists as $key => $value ) {
			$current_list = UM()->query()->get_meta_value('_um_list', $key );
		}
	}

	$current_roles = array();
	foreach ( UM()->roles()->get_roles() as $key => $value) {
		if ( UM()->query()->get_meta_value( '_um_roles', $key ) ) {
			$current_roles[] = $key;
		}
	}

	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
		$fields[] = array(
			'id'		    => 'mailing_list_id',
			'type'		    => 'info_text',
			'label'		    => __( 'Connected to Mailing List ID','um-mailchimp' ),
			'value'		    => UM()->query()->get_meta_value('_um_list'),
		);
	} else {
		$fields[] = array(
			'id'		    => '_um_list',
			'type'		    => 'select',
			'size'		    => 'medium',
			'label'		    => __( 'Choose a list','um-mailchimp' ),
			'tooltip'		=> __('Choose a list from your MailChimp account','um-mailchimp'),
			'value'		    => ! empty( $current_list ) ? $current_list : '',
			'options'		=> $lists,
		);
	}

	$fields = array_merge( $fields, array(
		array(
			'id'		    => '_um_status',
			'type'		    => 'checkbox',
			'label'		    => __( 'Enable this MailChimp list','um-mailchimp' ),
			'tooltip'		=> __( 'Turn on or off this list globally. If enabled the list will be available in user account page.','um-mailchimp' ),
			'value'		    => UM()->query()->get_meta_value( '_um_status', null, 1 ),
		),
		array(
			'id'		    => '_um_desc',
			'type'		    => 'text',
			'label'		    => __( 'List Description in Account Page','um-mailchimp' ),
			'tooltip'		=> __( 'This text will be displayed in Account > Notifications to encourage user to sign or know what this list is about','um-mailchimp' ),
			'value'		    => UM()->query()->get_meta_value('_um_desc', null, 'na'),
		),
		array(
			'id'		    => '_um_desc_reg',
			'type'		    => 'text',
			'label'		    => __( 'List Description in Registration','um-mailchimp' ),
			'tooltip'		=> __( 'This text will be displayed in register form if you enable this mailing list to be available during signup','um-mailchimp' ),
			'value'		    => UM()->query()->get_meta_value('_um_desc_reg', null, 'na'),
		),
		array(
			'id'		    => '_um_reg_status',
			'type'		    => 'checkbox',
			'label'		    => __( 'Automatically add new users to this list', 'um-mailchimp' ),
			'tooltip'		=> __( 'If turned on users will automatically be subscribed to this when they register. When turned on this list will not show on register form even if you add MailChimp field to register form.','um-mailchimp' ),
			'value'		    => UM()->query()->get_meta_value( '_um_reg_status', null, 0 ),
		),
		array(
			'id'		    => '_um_roles',
			'multi'		    => true,
			'type'		    => 'select',
			'size'		    => 'medium',
			'label'		    => __( 'Which roles can subscribe to this list' ,'um-mailchimp' ),
			'tooltip'		=> __( 'Select which roles can subscribe to this list. Users who cannot subscribe to this list will not see this list on their account page.', 'um-mailchimp'),
			'value'		    => ! empty( $current_roles ) ? $current_roles : array(),
			'options'		=> UM()->roles()->get_roles(),
		),
	) );

	UM()->admin_forms( array(
		'class'		=> 'um-form-mailchimp um-half-column',
		'prefix_id'	=> 'mailchimp',
		'fields'    => $fields
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>