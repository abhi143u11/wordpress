<?php
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@Add account privacy setting to control online status
	***/
	add_action( 'um_after_account_privacy', 'um_online_privacy_setting', 10, 1 );
	function um_online_privacy_setting( $shortcode_args ) {

		if ( isset( $shortcode_args['_hide_online_status'] ) && 0 == $shortcode_args['_hide_online_status'] )
			return;
		?>
		
		<div class="um-field" data-key="">
			
			<div class="um-field-label">
				<label for="hide_online_status"><?php _e('Show my online status?','um-online'); ?></label>
				<span class="um-tip um-tip-w" title="<?php _e('Do you want other people to see that you are online?','um-online'); ?>"><i class="um-icon-help-circled"></i></span>
				<div class="um-clear"></div>
			</div>
			
			<div class="um-field-area">
			
                <?php $active = get_user_meta( get_current_user_id(), '_hide_online_status', true ) == 1 ? true : false; ?>

                <label class="um-field-radio <?php if ( ! $active ) { ?>active<?php } ?> um-field-half">
                    <input type="radio" name="_hide_online_status" value="0" <?php checked( ! $active ) ?>/>
                    <span class="um-field-radio-state">
                        <i class="um-icon-android-radio-button-<?php if ( ! $active ) { ?>on<?php } else { ?>off<?php } ?>"></i>
                    </span>
                    <span class="um-field-radio-option"><?php _e('Yes','um-online'); ?></span>
                </label>
                <label class="um-field-radio <?php if ( $active ) { ?>active<?php } ?> um-field-half right">
                    <input type="radio" name="_hide_online_status" value="1" <?php checked( $active ) ?> />
                    <span class="um-field-radio-state">
                        <i class="um-icon-android-radio-button-<?php if ( $active ) { ?>on<?php } else { ?>off<?php } ?>"></i>
                    </span>
                    <span class="um-field-radio-option"><?php _e('No','um-online'); ?></span>
                </label>

                <div class="um-clear"></div>

				<div class="um-clear"></div>
				
			</div>
			
		</div>

		<?php

	}


	add_filter( 'um_predefined_fields_hook', 'um_online_account_privacy_fields_add' );
	function um_online_account_privacy_fields_add( $fields ) {

		$fields['_hide_online_status'] = array(
			'metakey' => '_hide_online_status',
		);

		$fields = apply_filters('um_account_secure_fields', $fields, '_hide_online_status' );
        
        unset( $fields['_hide_online_status'] );

		return $fields;
	}
