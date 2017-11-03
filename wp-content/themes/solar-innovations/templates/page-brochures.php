<?php
/**
 * Template Name: Brochures
 *
 */
get_header();
?>
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
		<?php get_sidebar(); ?>
	</div><!--sidebar-->
	<div class="medium-12 large-8 int-main columns">
		<?php do_action( 'foundationpress_before_content' ); ?>
		<?php if (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<div id="tech-docs">
				<?php if (have_rows('brochures')) : ?>
					<h2>Brochures</h2>
					<h3>Click on the brochure link to save and/or print the file.</h3>
					<ul>
						<?php while (have_rows('brochures')) : the_row(); $file = get_sub_field('file'); ?>
							<li><a href="<?=$file['url'];?>"><?=the_sub_field('name'); ?></a></li>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php do_action( 'foundationpress_after_content' ); ?>
	</div><!--int-main-->
</section><!-- row int -->
<?php get_footer(); ?>