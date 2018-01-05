<?php global $ultimatemember; ?>
<?php $key = um_get_option('mailchimp_api'); ?>


<p class="sub"><?php _e('Connection status','um-mailchimp'); ?></p>

<?php if ( !$key ) { ?>

	<p><?php printf(__('<a href="%s"><strong>Please enter your valid API key</strong></a> in settings.','um-mailchimp'), admin_url('admin.php?page=um_options&tab=' . $um_mailchimp->tab_id ) ); ?></p>

<?php } else { $result = $um_mailchimp->api->account();
     
    if ( ! $result ) { ?>

		<p><span class="red"><?php echo $result['error']; ?></span> <?php printf(__('<a href="%s"><strong>Please enter your valid API key</strong></a> in settings.','um-mailchimp'), admin_url('admin.php?page=um_options&tab=' . $um_mailchimp->tab_id ) ); ?></p>

	<?php } else { ?>

		<?php if ( isset( $result['account_name'] ) ){ ?>
		<p><?php printf(__('Your site is successfully <strong><span class="ok">linked</span></strong> to <strong>%s</strong> MailChimp account.','um-mailchimp'), $result['account_name']); ?>
		<?php } ?>
		<p class="sub"><?php _e('Account status','um-mailchimp'); ?></p>
			<p><?php printf( _n('%d subscriber',' %d subscribers',$result['total_subscribers'],'um-mailchimp' ), $result['total_subscribers'] ); ?>
			<?php $lists = $um_mailchimp->api->get_lists( ); ?>
			<?php if( is_array( $lists ) ){ $count_lists = count( $lists ); } ?>
			<p><?php printf( _n('%d Mailchimp list','%d Mailchimp lists',$count_lists, 'um-mailchimp'), $count_lists ); ?></p>
			<?php $lists = $um_mailchimp->api->get_lists( false ); ?>
			<?php if( is_array( $lists ) ){ $count_lists = count( $lists ); } ?>
			<p><?php printf( _n('%d UM Mailchimp list','%d UM Mailchimp lists',$count_lists, 'um-mailchimp'), $count_lists ); ?></p>
			<?php 
			$print_select = "<select name='um_mailchimp_list' class='um_mailchimp_list'>";
			$print_select .= "<option value=\"all\">All Lists</option>";
			foreach ( $lists as $list_id => $list_name ) {
				$print_select .= "<option value=\"{$list_id}\">{$list_name}</option>";
			}
			$print_select .= "</select>";
			?>

		<p class="sub"><?php _e('In queue (updated daily)','um-mailchimp'); ?></p>
			<?php $new_subscribers = $um_mailchimp->api->queue_count('subscribers'); ?>
			<p><?php printf( _n('%s new subscriber','%s new subscribers', $new_subscribers, 'um-mailchimp'), $new_subscribers ); ?>

			<?php if ( $um_mailchimp->api->queue_count('subscribers') > 0 ) { ?>
			<a href="#force_mailchimp_subscribe" data-action="um_mailchimp_force_subscribe" id="btn_force_mailchimp_subscribe" class="um-btn-mailchimp-progress-start button"><?php _e('Sync Now','um-mailchimp'); ?></a>
			<?php } ?>

			</p>
			<?php $new_unsubscribers = $um_mailchimp->api->queue_count('unsubscribers'); ?>
			<p><?php printf(_n('%d new unsubscriber','%d new unsubscribers',$new_unsubscribers,'um-mailchimp'), $new_unsubscribers ); ?>

			<?php if ( $um_mailchimp->api->queue_count('unsubscribers') > 0 ) { ?>
			<a href="#force_mailchimp_unsubscribe" data-action="um_mailchimp_force_unsubscribe" id="btn_force_mailchimp_unsubscribe" class="um-btn-mailchimp-progress-start button"><?php _e('Sync Now','um-mailchimp'); ?></a>
			<?php } ?>

			</p>
			<?php $new_profile_updates = $um_mailchimp->api->queue_count('update'); ?>
			<p><?php printf(_n('%d new profile update','%d new profile updates',$new_profile_updates,'um-mailchimp'), $new_profile_updates ); ?>

			<?php if ( $um_mailchimp->api->queue_count('update') > 0 ) { ?>
			<a href="#force_mailchimp_update" data-action="um_mailchimp_force_update" id="btn_force_mailchimp_update" class="um-btn-mailchimp-progress-start button"><?php _e('Sync Now','um-mailchimp'); ?></a>
			<?php } ?>

			</p>

		<p class="sub"><?php _e('Not synced yet','um-mailchimp'); ?></p>
		
		<?php $not_optedin = $um_mailchimp->api->queue_count('not_optedin'); ?>
		<?php $optedin_not_synced = $um_mailchimp->api->queue_count('optedin_not_synced'); ?>
		
		<div class="um_mailchimp_metabox">
		<select name="um_mailchimp_user_role" class="um_mailchimp_user_role"  style="width:100px;">
			<option value="all">All Roles</option>
			<?php $um_selected_role = isset( $_SESSION['_um_mailchimp_selected_role'] ) ? $_SESSION['_um_mailchimp_selected_role']:'all'; ?>
			<?php foreach( $ultimatemember->query->get_roles() as $key => $value ) { ?>
				<option value="<?php echo $key; ?>" <?php selected( $um_selected_role, $key ); ?> ><?php echo $value; ?></option>
			<?php } ?>
		</select>
		<select name="um_mailchimp_user_status" class="um_mailchimp_user_status" style="width:100px;">
			<option value="all">All Status</option>
		<?php 
			$arr_status = array(
				'approved' => __('Approved','ultimatemember'),
				'awaiting_admin_review' => __('Awaiting Admin Review','ultimatemember'),
				'awaiting_email_confirmation' => __('Awaiting Email Confirmation','ultimatemember'),
				'inactive' => __('Inactive','ultimatemember'),
				'rejected' => __('Rejected','ultimatemember'),
			);
		?>
		<?php $um_selected_status = isset( $_SESSION['_um_mailchimp_selected_status'] ) ? $_SESSION['_um_mailchimp_selected_status']:'all'; ?>
		<?php foreach( $arr_status  as $key => $value ) { ?>
				<option value="<?php echo $key; ?>" <?php selected( $um_selected_status, $key ); ?> ><?php echo $value; ?></option>
		<?php } ?>
		</select>
		<a href="#um_mailchimp_scan_now" data-param="um_mailchimp_user_role,um_mailchimp_user_status" data-action="um_mailchimp_scan_now" id="btn_um_mailchimp_scan_now" class="um-btn-mailchimp-progress-start button" data-start-message="Checking subscription status..."><?php _e('Scan Now','um-mailchimp'); ?></a>
		</div>
		


		<?php if( $not_optedin > 0 ):?>
		<div class="um_mailchimp_metabox">
		<p><?php printf(_n('%s profile not opted-in','%s profiles not opted-in',$not_optedin,'um-mailchimp'), $not_optedin ); ?>
		<?php echo $print_select;?>	<a href="#um_mailchimp_optin_now" data-action="um_mailchimp_optin_now" id="btn_um_mailchimp_optin_now" data-param="um_mailchimp_list" data-trigger-finish="#btn_um_mailchimp_scan_now" class="um-btn-mailchimp-progress-start button"><?php _e('Opt-in Now','um-mailchimp'); ?></a>
		</div>
		<?php endif; ?>

		
		<?php if( $optedin_not_synced > 0 ):?>
		<div class="um_mailchimp_metabox">
		<p><?php printf(_n('%s profile opted-in','%s profiles opted-in',$optedin_not_synced,'um-mailchimp'), $optedin_not_synced ); ?>
		<a href="#um_mailchimp_optedin_sync_now" data-action="um_mailchimp_optedin_sync_now" id="btn_um_mailchimp_optedin_sync_now" class="um-btn-mailchimp-progress-start button"><?php _e('Sync Now','um-mailchimp'); ?></a>
		</div>
		<?php endif; ?>

		<?php if( $optedin_not_synced <= 0 &&  $not_optedin <= 0 ):?>
		<p>All users are synced.</p>
		<?php endif; ?>
		<script>
			var UM_mailchimp_request_failed = function(button, status, error) {
				var button = jQuery(button);

				var msgDiv = button.next();
				if(msgDiv.is('.um-progress-message-area')) {
					msgDiv.find('.um-progress-message').html('Done. Reloading page..');
				}
				console.log( status, error );
				window.location.reload();
			}

			var UM_mailchimp_send_request = function(button) {
				var button = jQuery(button);
				var param = '';
				var $has_param = button.data('param');
				var action = button.data('action');
				var triggerNextButton = button.data("trigger-finish");

				var ajax_params =  {
					action: action,
					param: [],
				};

				if( $has_param ){

					var params = $has_param.split(",");
					if( params.length > 0 ){
						
						jQuery.each( params, function(i,d){
							ajax_params[ d ] = jQuery('.'+d).val();
						});

					}
					
				}
				
				jQuery.post(ultimatemember_ajax_url, ajax_params , function (json) {

					console.log( json );

					if( typeof json === 'object' && typeof json.debug !== 'undefined' && json.debug == true ){
							console.log( json.debug_message,json.progress );
					}

					if( json.message ) {
							var msgDiv = button.next();

							if( msgDiv.is('.um-progress-message-area') ) {
								msgDiv.find('.um-progress-message').html(json.message);
							}
					}

					// if it's not yet done, loop to the next request
					if(typeof json === 'object' && json.progress < 100) {
						
						setTimeout(function(){
							UM_mailchimp_send_request(button);
						}, 1000);

					} else {
						if( triggerNextButton ){

							var msgDiv = button.next();

							if( msgDiv.is('.um-progress-message-area') ) {
								msgDiv.find('.um-progress-message').html("Done.");
							}

							button.parent().fadeOut();
							
							jQuery( triggerNextButton ).removeClass("disabled");
							jQuery( triggerNextButton ).click();

						}else{
							setTimeout(function(){
								UM_mailchimp_request_failed(button,'','');
							}, 1000);
						}
					}


				}).fail(function(xhr, status, error) {
					UM_mailchimp_request_failed(button, status, error);
				});
			}

			jQuery(document).on('click', '.um-btn-mailchimp-progress-start:not(.disabled)', function(e) {
				e.preventDefault();

				var msgDiv  = jQuery('<span class="um-progress-message-area"><span class="spinner"></span> <span class="um-progress-message"></span></span>');
				var msgSpan = msgDiv.find('.um-progress-message');
				var button  = jQuery(this);
				var startMsg = jQuery(this).attr("start-message");



				// disable all progress buttons to prevent conflicts
				jQuery('.um-btn-mailchimp-progress-start').addClass('disabled');

				if( startMsg ){
					msgSpan.html(startMsg);
				}else{
					msgSpan.html('Starting..');
				}
				button.after(msgDiv);
				msgDiv.show();
				UM_mailchimp_send_request(button,'','');
			});
		</script>

<?php

	}

}

?>
