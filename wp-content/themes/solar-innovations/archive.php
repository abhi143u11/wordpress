<?php get_header(); ?>

<div class="row int-header">
	<div class="small-12 columns">
		<h1>Press Room</h1>
	</div>
</div>
<!--int-header-->
<div class="row breadcrumbs show-for-large-up">
	<div class="small-12 columns">
		<?php if (function_exists('qt_custom_breadcrumbs')) qt_custom_breadcrumbs(); ?>
	</div>
</div>
<!--breadcrumbs-->
<section class="row int">
	<div class="medium-12 large-4 sidebar columns show-for-large-up">
		<?php get_sidebar(); ?>
	</div>
	<!--sidebar-->
	<div class="medium-12 large-8 int-main columns">
		<div class="intro-blog">
        			<?php do_action( 'foundationPress_before_content' ); ?>
		<?php if ( have_posts() ) : ?>
		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<div class="post-holder">
						<h3 class="post-title"><a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
				</a></h3>
			<?php if (has_post_thumbnail()) echo '<div class="post-feature-thumb">' . get_the_post_thumbnail($page->ID, 'blog-feature') . '</div>'; ?>
				<?php the_time('F j, Y'); ?>
			</p>
			<?php the_excerpt(); ?>
			<a class="more-link" href="<?php the_permalink(); ?>" />Read More</a> </div>
		<?php endwhile; ?>
		<?php endif; // End have_posts() check. ?>
	</div>
	<!--int-main-->
	<?php /* Display navigation to next/previous pages when applicable */ ?>
	<?php if ( function_exists( 'foundationpress_pagination' ) ) { foundationpress_pagination(); } else if ( is_paged() ) { ?>
	<?php } ?>
</section>
	<?php do_action( 'foundationPress_after_content' ); ?>
<?php get_footer(); ?>
