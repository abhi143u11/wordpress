<?php get_header(); ?>

<section class="row int">
	<div class="small-12 large-12 columns">
<div class="error">
<p><img class="alignnone size-full wp-image-37016" src="http://solarinnovations.com/wp-content/uploads/404-new.png" alt="404 Error" width="1400" height="615" /></p>
					<p style="text-align: center;" class="bottom"><?php _e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'foundationpress' ); ?></p>
				</div>
				<p style="text-align: center;"><?php _e( 'Please try the following:', 'foundationpress' ); ?><br/>
<br/><?php _e( 'Check your spelling', 'foundationpress' ); ?></li>
<br/><?php printf( __( 'Return to the <a href="%s">home page</a>', 'foundationpress' ), home_url() ); ?>
<br/><?php _e( 'Click the <a href="javascript:history.back()">Back</a> button', 'foundationpress' ); ?>

                </p>
			</div>
	</div><!--int-main-->
</section>
<?php get_footer(); ?>