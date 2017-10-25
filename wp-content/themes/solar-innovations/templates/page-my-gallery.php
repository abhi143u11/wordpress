<?php
/**
 *
 * Template Name: My Gallery
 *
 */

if (!is_user_logged_in()) :
	header('Location: ' . get_bloginfo('url'));
	die();
endif;

global $current_user;

$args1 = array(
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
			'value'		=> 'logo',
			'compare'	=> '='
		)
	)
);

$args2 = array(
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
			'value'		=> 'print',
			'compare'	=> '='
		)
	)
);

$args3 = array(
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

$logos = new WP_Query( $args1 );
$prints = new WP_Query( $args2 );
$files = new WP_Query( $args3 );

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
				
				if (!empty($logos->posts)) :
		?>
					<h2>Logos</h2> 
					<table class="actforms">
						<thead>
							<th>Filename</th>
							<th>Download</th>
						</thead>
						<tbody>
							<?php foreach ($logos->posts as $row) : $file = get_field('file',$row->ID); ?>
								<td><?=$row->post_title?></td>
								<td><a href="<?=$file['url']?>">Download</a></td>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php
				endif;
				
				if (!empty($prints->posts)) :
				?>
					<h2>Prints</h2>
					<div class="row">
						<?php foreach ($prints->posts as $row) : $file = get_field('file',$row->ID); ?>
							<div class="small-12 medium-4 large-4 columns">
								<div class="mask">
									<a href="<?=$file['url']?>">
										<img src="<?=$file['sizes']['medium']?>" height="177" width="236">
									</a>
								</div>
								<a href="<?=$file['url']?>">
									<h3><?=$row->post_title?></h3>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
		<?php
				endif;
				
				if (!empty($files->posts)) :
				?>
					<h2>Files</h2>
					<table class="actforms">
						<thead>
							<th>Filename</th>
							<th>Download</th>
						</thead>
						<tbody>
							<?php foreach ($files->posts as $row) : $file = get_field('file',$row->ID); ?>
								<td><?=$row->post_title?></td>
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