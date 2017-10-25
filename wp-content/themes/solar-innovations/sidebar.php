<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage FoundationPress
 * @since FoundationPress 1.0
 */

?>
<aside id="sidebar" class="large-12 columns">
	<?php
	do_action( 'foundationpress_before_sidebar' );
	
	if ( is_singular('specification') && (get_field('doc_dl') || get_field('pdf_dl')) ) :
		echo '<div id="spec-sidebar"><h3>Downloads</h3><div class="textwidget"><ul>';
		if (get_field('doc_dl')) :
			$file = get_field('doc_dl');
			echo '<li class="word-icon"><a href="' . $file['url'] . '" title="' . $file['title'] . '">Word Version</a></li>';
		endif;
		if (get_field('pdf_dl')) :
			$file = get_field('pdf_dl');
			echo '<li class="pdf-icon"><a href="' . $file['url'] . '" title="' . $file['title'] . '">PDF Version</a></li>';
		endif;
		echo '</ul></div></div>';
	endif;
	
	if (is_user_logged_in()) :
		global $current_user;
		if (in_array($current_user->roles[0],array('user','dealer','osr'))) : 
	?>
			<h2>My Solar Contact</h2>
			<?php
			if ($current_user->roles[0] == 'user') :
				$args = array(
					'post_type'		=> 'salesrep',
					'meta_query'	=> array(
						array(
							'key'		=> 'territory',
							'value'		=> esc_attr( get_the_author_meta( 'state', $current_user->ID ) ),
							'compare'	=> 'LIKE'
						)
					)
				);
				$query = new WP_Query( $args );
				$repid = $query->posts[0]->ID;
				$my_rep = get_post($repid);
				//echo '<pre>'; print_r($my_rep); echo '</pre>';
			elseif (in_array($current_user->roles[0],array('dealer','osr'))) :
				$rep_id = esc_attr(get_the_author_meta('sales_rep', $current_user->ID));
				$my_rep = get_post($rep_id);
			endif;
			?>
		<?=$my_rep->post_title?><br />
		<?=the_field('email',$my_rep->ID)?><br />
		<?=formatPhone(get_field('phone',$my_rep->ID))?> <?=($ext = get_field('extension',$my_rep->ID)) ? 'x' . $ext : ''?>
	<?php 
		endif;
	endif; 
	
	// Pages Menu 
	$list = array(7,51);
	$post = get_post();
	$products_menu = 0;
	if ($post->post_parent > 0 || in_array($post->ID,$list)) :
		if (in_array($post->post_parent,$list) || in_array($post->ID,$list)) :
			if (false !== $listkey = array_search($post->post_parent,$list)) :
				$products_menu = 1;
			elseif (false !== $listkey = array_search($post->ID,$list)) :
				$products_menu = 1;
			endif;
		else :
			$check = get_post($post->post_parent);
			if (false !== $listkey = array_search($check->post_parent,$list)) :
				$products_menu = 1;
			endif;
		endif;
	endif;
	
	if ($products_menu) :
		$args = array('post_type' => 'page', 'post_status' => 'publish', 'post_parent' => $list[$listkey],'orderby' => 'title','order'   => 'ASC',);
		$product_pages = new WP_Query($args);
		//echo '<pre>'; print_r($product_pages); echo '</pre>';
		if (!empty($product_pages->posts)) :
	?>
			<h2><?=get_the_title($list[$listkey])?></h2>
			<ul class="accordion" data-accordion>
				<?php
				foreach ($product_pages->posts as $first) :
					$args = array('post_type' => 'page', 'post_status' => 'publish', 'post_parent' => $first->ID);
					$children = new WP_Query($args);
					if (!empty($children->posts)) : 
				?>
						<li class="accordion-navigation <?=($post->ID == $first->ID || $post->post_parent == $first->ID) ? 'active' : ''?>">
							<a href="#nav-<?=$first->ID?>"<?=($post->ID == $first->ID || $post->post_parent == $first->ID) ? ' aria-expanded="true"' : ' aria-expanded="false"'?>><?=$first->post_title?><i class="fa fa-chevron-right closed"></i><i class="fa fa-chevron-down open"></i></a>
							<div id="nav-<?=$first->ID?>" class="content <?=($post->ID == $first->ID || $post->post_parent == $first->ID) ? 'active' : ''?>">
								<div class="row">
									<div class="small-12 columns checks">
										<ul class="a-sub-nav">
											<?php foreach ($children->posts as $second) : ?>
												<li><a href="<?=get_the_permalink($second->ID)?>"<?=($post->ID == $second->ID) ? ' class="active"' : ''?>><?=$second->post_title?></a></li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							</div>
						</li>
					<?php else : ?>
						<a href="<?=get_the_permalink($first->ID)?>"<?=($post->ID == $first->ID) ? ' class="active"' : ''?>><?=$first->post_title?></a>
				<?php 
					endif;
				endforeach; 
				?>
			</ul>
	<?php 
		endif;
	endif; 
	?>
	<?php dynamic_sidebar( 'sidebar-widgets' ); ?>
	<?php do_action( 'foundationpress_after_sidebar' ); ?>
</aside>