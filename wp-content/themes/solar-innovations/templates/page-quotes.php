<?php
/*
Template Name: All Quotes
*/

$args = array('post_type' => 'quote', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC');
$quotes = new WP_Query($args);

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
		<?php get_sidebar(); ?>
	</div><!--sidebar-->
	<div id="quotespec-content-wrap" class="medium-12 large-8 int-main columns">
		<?php do_action( 'foundationpress_before_content' ); ?>
		<?php if (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<ul>
			<?php
			foreach ($quotes->posts as $row) :
				//echo '<pre>'; print_r($quotes); echo '</pre>';
			?>
				<li><a href="<?=get_permalink($row->ID)?>"><?=$row->post_title?></a></li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php do_action( 'foundationpress_after_content' ); ?>
	</div><!--int-main-->
</section><!-- row int -->
<?php get_footer(); ?>
