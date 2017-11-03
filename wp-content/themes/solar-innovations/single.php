<?php get_header(); ?>

<div class="row int-header">
	<div class="small-12 columns">
		<h1><?php
$category = get_the_category( $custompost );
echo $category[0]->cat_name;
?></h1>
	</div>
</div><!--int-header-->
<div class="row breadcrumbs">
	<div class="small-12 columns"><?php if (function_exists('qt_custom_breadcrumbs')) qt_custom_breadcrumbs(); ?></div>
</div><!--breadcrumbs-->
<section class="row int">
	<div class="medium-12 large-4 sidebar columns">
		<?php get_sidebar(); ?>
	</div><!--sidebar-->
	<div class="medium-12 large-8 int-main columns">
<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<header>
				<h2 class="entry-title"><?php the_title(); ?></h2>
			</header>
			<?php do_action( 'foundationpress_post_before_entry_content' ); ?>
			<div class="entry-content">
			<?php if ( has_post_thumbnail() ) : ?>
						<?php if (has_post_thumbnail()) echo '<div class="post-feature-thumb">' . get_the_post_thumbnail($page->ID, 'blog-feature') . '</div>'; ?>
			<?php endif; ?>
            <?php the_time('F j, Y'); ?>
			<?php the_content(); ?>
			</div>
			<?php do_action( 'foundationpress_after_content' ); ?>
		</article>
		<div class="nav-btns">
			<div class="btn-next left"><?php next_post('%','Newer', 'no'); ?></div>
			<div class="btn-prev right"><?php previous_post('%','Older', 'no'); ?></div>
		</div>
		<!-- <div class="share">
			<h3>Share</h3>
			<?php echo do_shortcode('[ssba]'); ?>
		</div> -->
	<?php endwhile;?>

	</div><!--int-main-->
</section><!--row int-->

<?php get_footer(); ?>