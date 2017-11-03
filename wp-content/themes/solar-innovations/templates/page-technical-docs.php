<?php
/**
 * Template Name: Technical Docs
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
				<?php 
				if (have_rows('groups')) :
					while (have_rows('groups')) : the_row(); 
				?>
						<div class="tech-screens">
							<h2><?=the_sub_field('group_name'); ?></h2>
							<?php 
							if (have_rows('group')) : 
								while (have_rows('group')) : the_row();
							?>
									<h3><?=the_sub_field('sub-group_name'); ?></h3>
									<?php if (have_rows('files')) : ?>
										<ul>
											<?php while (have_rows('files')) : the_row(); $file = get_sub_field('file'); ?>
												<li><a href="<?=$file['url']?>"><?=the_sub_field('name'); ?></a></li>
											<?php endwhile; ?>
										</ul>
							<?php 
									endif;
								endwhile;
							endif;
							?>
						</div>
				<?php 
					endwhile;
				endif; 
				?>
			</div>
		<?php endif; ?>
		<?php do_action( 'foundationpress_after_content' ); ?>
	</div><!--int-main-->
</section><!-- row int -->
<?php get_footer(); ?>