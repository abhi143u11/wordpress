<?php
/**
 *
 * Template Name: My Downloads
 *
 */
global $current_user;

if (!is_user_logged_in() || $current_user->roles[0] != 'osr') :
	header('Location: ' . get_bloginfo('url'));
	die();
endif;

$args = array(
	'post_type'		=> 'download',
	'meta_query'	=> array(
		'relation'		=> 'AND',
		array(
			'key'		=> 'access',
			'value'		=> $current_user->roles[0],
			'compare'	=> 'LIKE'
		),
		array(
			'key'		=> 'type',
			'value'		=> 'file',
			'compare'	=> '='
		)
	)
);

$downloads = new WP_Query( $args );

get_header(); 
?>
<div class="row int-header">
	<div class="small-12 columns">
		<h1><?=the_title()?></h1>
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
		<?php 
		do_action( 'foundationpress_before_content' );
 
		if ( have_posts() ) : 
			while ( have_posts() ) : 
				the_post(); 
				
				if (!empty($downloads->posts)) :
		?>
					<h1>My Downloads</h1> 
					<table class="actforms">
						<thead>
							<th>Filename</th>
							<th>Created</th>
							<th>Modified</th>
							<th>Download</th>
						</thead>
						<tbody>
							<?php foreach ($downloads->posts as $row) : $file = get_field('file',$row->ID); ?>
								<td><?=$row->post_title?></td>
								<td><?=$row->post_date?></td>
								<td><?=$row->post_modified?></td>
								<td><a href="<?=$file['url']?>">Download</a></td>
							<?php endforeach; ?>
						</tbody>
					</table>
		<?php 
				endif;
			endwhile; 
		else: 
		?>
		    <p class="no-data">
		        <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
		    </p><!-- .no-data -->
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>