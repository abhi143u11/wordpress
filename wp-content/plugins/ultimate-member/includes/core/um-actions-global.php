<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@adds a form identifier to form
	***/
	add_action('um_after_form_fields', 'um_add_form_identifier');
	function um_add_form_identifier($args){ ?>

		<input type="hidden" name="form_id" id="form_id_<?php echo $args['form_id']; ?>" value="<?php echo $args['form_id']; ?>" />

		<?php
	}

	/***
	***	@adds a spam timestamp
	***/
	add_action('um_after_form_fields', 'um_add_security_checks');
	add_action('um_account_page_hidden_fields', 'um_add_security_checks');
	function um_add_security_checks($args){
		if ( is_admin() ) return;

		echo '<input type="hidden" name="timestamp" class="um_timestamp" value="'. current_time( 'timestamp' ) .'" />';

		?>

		<p class="<?php echo UM()->honeypot; ?>_name">
			<label for="<?php echo UM()->honeypot . '_' . $args['form_id']; ?>"><?php _e( 'Only fill in if you are not human' ); ?></label>
			<input type="text" name="<?php echo UM()->honeypot; ?>" id="<?php echo UM()->honeypot . '_' . $args['form_id']; ?>" class="input" value="" size="25" autocomplete="off" />
		</p>

		<?php

	}

	/***
	***	@makes the honeypot invisible
	***/
	add_action('wp_head', 'um_add_form_honeypot_css');
	function um_add_form_honeypot_css() { ?>

		<style type="text/css">.<?php echo UM()->honeypot; ?>_name { display: none !important; }</style>

	<?php }

	/***
	***	@empty the honeypot value
	***/
	add_action('wp_footer', 'um_add_form_honeypot_js', 99999999999999999 );
	function um_add_form_honeypot_js() { ?>

		<script type="text/javascript">jQuery( '#<?php echo UM()->honeypot; ?>' ).val( '' );</script>

	<?php
	}
