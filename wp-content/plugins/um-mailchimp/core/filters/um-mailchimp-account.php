<?php

	/***
	***	@show mailchimp lists in account
	***/
	add_filter('um_account_content_hook_notifications', 'um_mailchimp_account_tab', 100 );
	function um_mailchimp_account_tab( $output ){
		global $um_mailchimp;
		
		$lists = $um_mailchimp->api->has_lists();
		if ( !$lists ) return $output;

		ob_start();
		
		?>
		
		<div class="um-field um-field-mailchimp" data-key="mailchimp">
			
			<div class="um-field-label"><label for=""><?php _e('Email Newsletters','um-mailchimp'); ?></label><div class="um-clear"></div></div>
			
			<div class="um-field-area">
				
				<?php foreach( $lists as $post_id ) { $list = $um_mailchimp->api->fetch_list($post_id); ?>
					<?php if ( $um_mailchimp->api->is_subscribed( $list['id'] ) ) { // subscribed ?>
					
					<label class="um-field-checkbox active">
						<input type="checkbox" name="um-mailchimp[<?php echo $list['id']; ?>]" value="already_subscribed" checked />
						<span class="um-field-checkbox-state"><i class="um-icon-android-checkbox-outline"></i></span>
						<span class="um-field-checkbox-option"><?php echo $list['description']; ?></span>
					</label>
					
					<?php } else { ?>
					
					<label class="um-field-checkbox">
						<input type="checkbox" name="um-mailchimp[<?php echo $list['id']; ?>]" value="1"  />
						<span class="um-field-checkbox-state"><i class="um-icon-android-checkbox-outline-blank"></i></span>
						<span class="um-field-checkbox-option"><?php echo $list['description']; ?></span>
					</label>
					
					<?php } ?>
					
				<?php } wp_reset_postdata(); ?>
				
				<div class="um-clear"></div>
				
			</div>
			
		</div>		
		
		<?php
		
		$output .= ob_get_contents();
		ob_end_clean();

		return $output;
	}

	add_filter('um_custom_error_message_handler','um_mailchimp_ustom_error_message_handler',10,2);
	function um_mailchimp_ustom_error_message_handler( $err, $msg ){
		return __( esc_html( $msg ) ,'um-mailchimp');
	}