<?php
/*
Template Name: Homepage
*/
?>
<?php get_header(); ?>
<?php layerslider(5) ?>
<section class="row main-text">
			<?php the_field('text_left'); ?>
</section>
<section class="row three-small">
	<div class="small-12 medium-4 columns"><img src="<?php the_field('image_1'); ?>">
		<h3>
			<?php the_field('headline_1'); ?>
		</h3>		
		<p>
			<?php the_field('text_1'); ?>
		</p>
	</div>
	<div class="small-12 medium-4 columns"><img src="<?php the_field('image_2'); ?>">
		<h3>
			<?php the_field('headline_2'); ?>
		</h3>		
		<p>
			<?php the_field('text_2'); ?>
		</p>
	</div>
	<div class="small-12 medium-4 columns"><img src="<?php the_field('image_3'); ?>">
		<h3>
			<?php the_field('headline_3'); ?>
		</h3>		
		<p>
			<?php the_field('text_3'); ?>
		</p>
	</div>
</section>
<?php get_footer(); ?>
