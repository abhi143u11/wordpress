<?php get_header(); ?>

<div class="row int-header">
	<div class="small-12 columns">
		<h1><?php _e( 'Search Results for', 'foundationpress' ); ?> "<?php echo get_search_query(); ?>"</h1>
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
		<?php do_action( 'foundationPress_before_content' ); ?>

		<h2><?php _e( 'Search Results for', 'FoundationPress' ); ?> "<?php echo get_search_query(); ?>"</h2>

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>

	<?php endif;?>

	<?php do_action( 'foundationPress_before_pagination' ); ?>

	<?php if ( function_exists( 'foundationpress_pagination' ) ) { foundationpress_pagination(); } else if ( is_paged() ) { ?>

		<nav id="post-nav">
			<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'FoundationPress' ) ); ?></div>
			<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'FoundationPress' ) ); ?></div>
		</nav>
	<?php } ?>

	<?php do_action( 'foundationPress_after_content' ); ?>
	</div><!--int-main-->
</section>
<?php get_footer(); ?>
