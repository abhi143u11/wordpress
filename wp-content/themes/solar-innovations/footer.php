<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package WordPress
 * @subpackage FoundationPress
 * @since FoundationPress 1.0
 */

?>
	<footer>
			<div class="footer-main">
            <div class="footer-body">
				<div class="row">
					<div class="small-12 medium-12 large-6 columns">
						<p><?php the_field('company_text', 'option'); ?></p>
					</div>
					<div class="small-12 medium-6 large-3 columns">
						<h3><?php the_field('loaction', 'option'); ?></h3>
						<p class="phone"><?php the_field('phone_number', 'option'); ?></p>
						<p><?php the_field('full_address', 'option'); ?></p>
					</div>
					<div class="small-12 medium-6 large-3 columns">
						<h3>Follow Us</h3>
						<ul class="small-block-grid-3 foot-socials">
						<li><a target="_blank" href="<?php the_field('twitter', 'option'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/twitter.png" alt="twitter" height="33" width="33"></a></li>
						<li><a target="_blank" href="<?php the_field('facebook', 'option'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/facebook.png" alt="facebook" height="33" width="33"></a></li>
						<li><a target="_blank" href="<?php the_field('pinterest', 'option'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/pintrest.png" alt="pintrest" height="33" width="33"></a></li>
                        <li><a target="_blank" href="<?php the_field('google_+', 'option'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/google.png" alt="google plus" height="33" width="33"></a></li>
						<li><a target="_blank" href="<?php the_field('linkedin', 'option'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/linkedin.png" alt="linkedin" height="33" width="33"></a></li>
						<li><a target="_blank" href="<?php the_field('green', 'option'); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/green.png"></a></li>
						</ul>
					</div>
				</div>
				<div class="row icons show-for-medium-up">
					<div class="medium-2 columns"><a target="_blank" href="<?php the_field('association_link_1', 'option'); ?>"><img src="<?php the_field('association_image_1', 'option'); ?>" width="120" height="120" ></a></div>
					<div class="medium-2 columns"><a target="_blank" href="<?php the_field('association_link_2', 'option'); ?>"><img src="<?php the_field('association_image_2', 'option'); ?>" width="120" height="120" ></a></div>
					<div class="medium-2 columns"><a target="_blank" href="<?php the_field('association_link_3', 'option'); ?>"><img src="<?php the_field('association_image_3', 'option'); ?>" width="120" height="120"></a></div>
					<div class="medium-2 columns"><a target="_blank" href="<?php the_field('association_link_4', 'option'); ?>"><img src="<?php the_field('association_image_4', 'option'); ?>" width="120" height="120" ></a></div>
					<div class="medium-2 columns"><a target="_blank" href="<?php the_field('association_link_5', 'option'); ?>"><img src="<?php the_field('association_image_5', 'option'); ?>" width="120" height="120" ></a></div>
					<div class="medium-2 columns"><a target="_blank" href="<?php the_field('association_link_6', 'option'); ?>"><img src="<?php the_field('association_image_6', 'option'); ?>" width="120" height="120" ></a></div>
				</div>
				<div class="row small-collapse copy">
					<div class="medium-12 large-8 columns">
						<?php display_footer_menu(); ?>
					</div>
				</div>
				<div class="row">
				<div class="small-12 columns">
					<p class="legal"><?php the_field('legal', 'option'); ?></p>
				</div>
				</div>
              </div>  
			</div>
	</footer>
</section>

<?php wp_footer(); ?>

<!-- JS -->
<script type="text/javascript">
  $(document).foundation();
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
  $('#full-width-slider').royalSlider({
    loop: true,
    keyboardNavEnabled: false,
    controlsInside: false,
    imageScaleMode: 'none',
    arrowsNavAutoHide: false,
    autoScaleSlider: true, 
    autoScaleSliderWidth: 1400,     
    arrowsNav:false,
    arrowsNavAutoHide: false,
    arrowsNavHideOnTouch: true,
    thumbsFitInViewport: false,
    navigateByClick: true,
    startSlideId: 0,
    autoPlay: {
    	enabled: true,
    	pauseOnHover: true,
    	delay: 5000
    },
    transitionType:'fade',
    controlNavigation: 'bullets',
    globalCaption: false,
    imgWidth: 1400,
    imgHeight: 'auto',
    thumbs: {
      spacing: 0,
    }
  });
  $('#gallery-slider').royalSlider({
    arrowsNav: false,
    loop: false,
    keyboardNavEnabled: true,
    controlsInside: false,
    imageScaleMode: 'fill',
    arrowsNavAutoHide: false,
    autoScaleSlider: true, 
    autoScaleSliderWidth: 800,     
    autoScaleSliderHeight: 350,
    controlNavigation: 'bullets',
    thumbsFitInViewport: false,
    navigateByClick: true,
    startSlideId: 0,
    autoPlay: false,
    transitionType:'move',
    imgWidth: 800,
    imgHeight: 600,
    });
});
</script>
<?php if (wp_script_is( 'si_quote_script', 'enqueued' )) : ?>
	<script type="text/javascript">
	functionOne('<?=$post->post_title?>');
	</script>
<?php endif; ?>

</body>
</html>