<?php 
/*
Template Name: Contact Page
*/
get_header(); ?>

<div class="row int-header">
	<div class="small-12 columns">
		<h1><?php the_title(); ?></h1>
	</div>
</div><!--int-header-->
<div class="row breadcrumbs show-for-large-up">
	<div class="small-12 columns"><?php if (function_exists('qt_custom_breadcrumbs')) qt_custom_breadcrumbs(); ?></div>
</div><!--breadcrumbs-->
<section class="row int">
	<div class="medium-12 large-4 sidebar columns">
		<?php dynamic_sidebar( 'sidebar-contact' ); ?>
	</div><!--sidebar-->
	<div class="medium-12 large-8 int-main columns">
		<?php do_action( 'foundationpress_before_content' ); ?>
		<?php if (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endif; ?>
		<?php do_action( 'foundationpress_after_content' ); ?>
	</div><!--int-main-->
</section>
<?php get_footer(); ?>
