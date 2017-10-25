<?php 

 get_header();?>

<div class="row int-header">
	<div class="small-12 columns">
		<h1><?php the_title(); ?></h1>
	</div>
</div><!--int-header-->
<div class="row breadcrumbs show-for-large-up">
	<div class="small-12 columns"><?php if (function_exists('qt_custom_breadcrumbs')) qt_custom_breadcrumbs(); ?></div>
</div><!--breadcrumbs-->
<section class="row int">
	<div class="medium-12 large-4 sidebar columns show-for-large-up">
		<div id="preview_container">
			<img id="preview_spinner" src="<?php bloginfo('template_directory'); ?>/img/preview_spinner.gif" />
			<img id="preview_image" src="<?php bloginfo('template_directory'); ?>/img/preview_image.jpg" />
			<img id="overlay_image" src="<?php bloginfo('template_directory'); ?>/img/blank.png" style="margin-top:-210px" />
			<img id="overlay_image2" src="<?php bloginfo('template_directory'); ?>/img/blank.png" style="margin-top:-257px" />
			<img id="overlay_image3" src="<?php bloginfo('template_directory'); ?>/img/blank.png" style="margin-top:-303px" />
			<img id="overlay_image4" src="<?php bloginfo('template_directory'); ?>/img/blank.png" style="margin-top:-349px" />
		</div>
		<?php //get_sidebar(); ?>
	</div><!--sidebar-->
	<div class="medium-12 large-8 int-main columns">
		<?php do_action( 'foundationpress_before_content' ); ?>
		<?php if (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endif; ?>
		<?php do_action( 'foundationpress_after_content' ); ?>
	</div><!--int-main-->
</section><!-- row int -->
<div id="warrantyModal" class="reveal-modal" data-reveal aria-labelledby="warrantyModalTitle" aria-hidden="true" role="dialog">
	<h2 id="warrantyModalTitle">General Warranty</h2>
	<p>Reveal makes these very easy to summon and dismiss. The close button is simply an anchor with a unicode character icon and a class of <code>close-reveal-modal</code>. Clicking anywhere outside the modal will also dismiss it.</p>
	<p>Finally, if your modal summons another Reveal modal, the plugin will handle that for you gracefully.</p>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
<div id="termsModal" class="reveal-modal" data-reveal aria-labelledby="termsModalTitla" aria-hidden="true" role="dialog">
	<h2 id="termsModalTitle">Terms &amp; Conditions</h2>
	<p>Reveal makes these very easy to summon and dismiss. The close button is simply an anchor with a unicode character icon and a class of <code>close-reveal-modal</code>. Clicking anywhere outside the modal will also dismiss it.</p>
	<p>Finally, if your modal summons another Reveal modal, the plugin will handle that for you gracefully.</p>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
<?php get_footer(); ?>
