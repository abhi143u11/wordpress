<?php global $um_terms_conditions;  ?>
<div class="um-admin-metabox">
	<p>
		<label><?php _e('Enable on this form','ultimate-member'); ?></label>
		<span>
			
		<select name="_um_register_use_terms_conditions" class="um-s2" style="width: 100%;">
			 <option value="1" <?php selected(1,$ultimatemember->query->get_meta_value('_um_register_use_terms_conditions', null, '' ) ); ?> ><?php echo esc_attr( __( 'Yes','um-terms-conditions') ); ?></option> 
			 <option value="0" <?php selected(0,$ultimatemember->query->get_meta_value('_um_register_use_terms_conditions', null, '' ) ); ?> ><?php echo esc_attr( __( 'No','um-terms-conditions') ); ?></option> 
		</select>
		</span>
	</p><div class="um-admin-clear"></div>
	<p>
		<label><?php _e('Content','ultimate-member'); ?></label>
		<select name="_um_register_use_terms_conditions_content_id" class="um-s2" style="width: 100%;">
			 <option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
			 <?php 
			  $pages = get_pages(); 
			  foreach ( $pages as $page ) {
			  	$option = '<option value="' . $page->ID . '" '.selected($page->ID,$ultimatemember->query->get_meta_value('_um_register_use_terms_conditions_content_id', null, '' ) ).'>';
				$option .= $page->post_title;
				$option .= '</option>';
				echo $option;
			  }
			 ?>
		</select>
	</p><div class="um-admin-clear"></div>

	<p>
		<label><?php _e('Toggle Show text','ultimate-member'); ?></label>
		<input type="text" name="_um_register_use_terms_conditions_toggle_show" value="<?php echo $um_terms_conditions->get_field_value( '_um_register_use_terms_conditions_toggle_show' ); ?>" placeholder="<?php _e('Show Terms','ultimate-member'); ?>" />
	</p><div class="um-admin-clear"></div>

	<p>
		<label><?php _e('Toggle Hide text','ultimate-member'); ?></label>
		<input type="text" name="_um_register_use_terms_conditions_toggle_hide" value="<?php echo $um_terms_conditions->get_field_value( '_um_register_use_terms_conditions_toggle_hide' ); ?>" placeholder="<?php _e('Hide Terms','ultimate-member'); ?>" />
	</p><div class="um-admin-clear"></div>

	<p>
		<label><?php _e('Checkbox agreement description','ultimate-member'); ?></label>
		<input type="text" name="_um_register_use_terms_conditions_agreement" value="<?php echo $um_terms_conditions->get_field_value( '_um_register_use_terms_conditions_agreement' ); ?>" placeholder="<?php _e('Please confirm that you agree to our terms & conditions','ultimate-member'); ?>" />
	</p><div class="um-admin-clear"></div>

	<p>
		<label><?php _e('Error Text','ultimate-member'); ?></label>
		<input type="text" name="_um_register_use_terms_conditions_error_text" value="<?php echo $um_terms_conditions->get_field_value( '_um_register_use_terms_conditions_error_text' ); ?>" placeholder="<?php _e('You must agree to our terms & conditions','ultimate-member'); ?>" />
	</p><div class="um-admin-clear"></div>
	
</div>