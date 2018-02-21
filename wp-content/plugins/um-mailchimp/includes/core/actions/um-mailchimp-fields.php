<?php
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@modal field settings
	***/
	add_action('um_admin_field_edit_hook_mailchimp_list', 'um_admin_field_edit_hook_mailchimp_list');
	function um_admin_field_edit_hook_mailchimp_list( $val ) {
		
		$lists = UM()->Mailchimp_API()->api()->has_lists( true );
		 
		if ( !$lists ) return;
		
		?>
		
			<p><label for="_mailchimp_list"><?php _e('Select a List','um-mailchimp'); ?> <?php UM()->tooltip( __('You can set up lists or integrations in Ultimate Member > MailChimp','um-mailchimp') ); ?></label>
				<select name="_mailchimp_list" id="_mailchimp_list" style="width: 100%">
					
					<?php foreach( $lists as $post_id ) { $list = UM()->Mailchimp_API()->api()->fetch_list( $post_id ); ?>
					<option value="<?php echo $post_id; ?>" <?php selected( $post_id, $val ); ?>><?php echo $list['name']; ?></option>
					<?php } ?>
					
				</select>
			</p>

		<?php
		
	}