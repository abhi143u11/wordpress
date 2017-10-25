<?php
/**
 * Template Name: Project Gallery
 *
 */
get_header(); 
$project_gallery_filter_form = new WP_Advanced_Search('project-gallery-filter-form');

if (have_posts()) :
	while (have_posts()) : 
		the_post();
?>
<div class="row int-header">
	<div class="small-12 columns">
		<h1>
			<?php the_title(); ?>
		</h1>
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
	<?php do_action( 'foundationpress_before_content' ); ?>
	<?php
	if (!isset($_GET['wpas_submit']) && !isset($_GET['project_id'])) : //Display Categories
		if (!isset($_GET['product'])) : //Show All Product Categories
			$args = array(  'type'=> 'category',
							'child_of' => '',
							'parent' => 37,
							'orderby' => 'name',
							'order' => 'ASC',
							'hide_empty' => 0,
							'hierarchical' => 1,
							'exclude' => '',
							'include' => '',
							'number' => '',
							'taxonomy' => 'category',
							'pad_counts' => false 
						);
			$categories = get_categories($args);
	?>
			<div class="medium-12 large-4 sidebar columns">
				<div class="row">
					<div class="row intro hide-for-large-up">
						<div class="small-12 columns">
							<h2><?=the_title()?></h2>
							<p><?=the_content()?></p>
						</div>
					</div>
					<div class="large-12 columns">
				     <?php $project_gallery_filter_form->the_form(); ?>
					</div>
				</div>
			</div>
			<!--row--> 
			<!--sidebar-->
			<div class="medium-12 large-8 int-main columns">
				<div class="row intro">
					<div class="small-12 show-for-large-up columns">
						<h2><?=the_title()?></h2>
						<p><?=the_content()?></p>
					</div>
				</div>
				<div class="row">
					<div id="project-thumbnails" class="small-12 columns">	
						<?php
						foreach ($categories as $category) :
							$image = get_field('image', 'category_'.$category->cat_ID);
						?>
							<div class="small-12 medium-4 large-4 columns project-thumb">
								<div class="mask">
									<a href="<?=get_page_link() . '?product=' . $category->cat_ID?>" class="port-img">
										<?php if (!empty($image)) : ?>
											<img src="<?=$image['sizes']['medium']?>" height="177" width="236">
										<?php else : ?> 
											<img src="/wp-content/uploads/2015/08/placeholder.png" height="177" width="236">
										<?php endif; ?>
									</a>
								</div>
								<a href="<?=get_page_link() . '?product=' . $category->cat_ID?>" class="port-heading">
									<h3><?=$category->name?></h3>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<!--main-->
		<?php 
		else : //Show Selected Product Sub Categories 
			$parent = new WP_Query( 'cat='.$_GET['product'] );
			$args = array(  'type'=> 'category',
							'child_of' => '',
							'parent' => $_GET['product'],
							'orderby' => 'name',
							'order' => 'ASC',
							'hide_empty' => 0,
							'hierarchical' => 1,
							'exclude' => '',
							'include' => '',
							'number' => '',
							'taxonomy' => 'category',
							'pad_counts' => false 
						);
			$categories = get_categories($args);
		?>
			<div class="medium-12 large-4 sidebar columns">
				<div class="row">
					<div class="row intro hide-for-large-up">
						<div class="small-12 columns">
							<h2><?=get_cat_name($_GET['product'])?></h2>
							<p><?=category_description($_GET['product'])?></p>
						</div>
					</div>
					<div class="large-12 columns">
				     <?php $project_gallery_filter_form->the_form(); ?>
					</div>
				</div>
			</div>
			<!--row--> 
			<!--sidebar-->
			<div class="medium-12 large-8 int-main columns">
				<div class="row intro">
					<div class="small-12 show-for-large-up columns">
						<h2><?=get_cat_name($_GET['product'])?></h2>
						<p><?=category_description($_GET['product'])?></p>
					</div>
				</div>
				<div class="row">
					<div id="project-thumbnails" class="small-12 columns">
						<?php
						foreach ($categories as $category) :
							$image = get_field('image', 'category_'.$category->cat_ID);
						?>
							<div class="small-12 medium-4 large-4 columns project-thumb">
								<div class="mask">
									<a href="<?=get_page_link() . '?search_query=&meta_products=' . str_replace('&amp;','%26',str_replace('/','%2F',str_replace(' ','+',get_cat_name($parent->query_vars['cat'])))) . '&meta_'  .str_replace('-','_',$parent->query_vars['category_name']) . '_product_types%5B%5D=' . str_replace('&amp;','%26',str_replace('/','%2F',str_replace(' ','+',$category->name))) . '&wpas_id=my-form&wpas_submit=1'?>">
										<?php if (!empty($image)) : ?>
											<img src="<?=$image['sizes']['medium']?>" height="177" width="236">
										<?php else : ?>
											<img src="/wp-content/uploads/2015/08/placeholder.png" height="177" width="236">
										<?php endif; ?>
									</a>
								</div>
								<a href="<?=get_page_link() . '?meta_products=' . urlencode(get_cat_name($parent->query_vars['cat'])) . '&meta_'  .str_replace('-','_',$parent->query_vars['category_name']) . '_product_types%5B%5D=' . urlencode($category->name) . '&wpas_id=my-form&wpas_submit=1'?>">
									<h3><?=$category->name?></h3>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php 
				endif;
			elseif (!isset($_GET['project_id'])) : //Display WPAS Results
				$project_gallery_filter_form = new WP_Advanced_Search('project-gallery-filter-form');
				$query = $project_gallery_filter_form->query();
			?>	
			<div class="medium-12 large-4 sidebar columns">
				<div class="row">
					<div class="large-12 columns">
				    	<?php $project_gallery_filter_form->the_form(); ?>
					</div>
				</div>
			</div>
			<!--row--> 
			<!--sidebar-->
		
			<div class="medium-12 large-8 int-main columns">
				<?php if ($query->have_posts()) :?>
					<div class="row">
						<div id="project-thumbnails" class="small-12 columns">
						<?php 
						while ($query->have_posts()) : 
							$query->the_post();
							global $post; 
						?>
							<div class="small-12 medium-4 large-4 columns project-thumb">
								<div class="mask">
									<a href="?project_id=<?=$post->ID?>">
										<?php 
										if (has_post_thumbnail()) :
											the_post_thumbnail('medium');
										else :
										?>
											<img src="/wp-content/uploads/2015/08/placeholder.png" height="177" width="236">
										<?php endif; ?>
									</a>
								</div>
								<a href="?project_id=<?=$post->ID?>">
									<h3><?=the_title()?></h3>
								</a>
							</div>
							
						<?php endwhile; ?>
						</div>
					</div>
					<div class="displaying">
						<?php
			            //echo 'Found : ' . $query->found_posts . '<br />';
			            //echo 'Per Page : ' . $query->query['posts_per_page'] . '<br />';
			            echo 'Displaying results '; 
			            echo (intval($query->found_posts) >= intval($query->query['posts_per_page'])) ? $project_gallery_filter_form->results_range() : '1-' . $query->found_posts;
			            echo ' of ' . $query->found_posts;
			            $project_gallery_filter_form->pagination(array('prev_text' => '«','next_text' => '»'));
			            //echo '<pre>'; print_r($query); echo '</pre>';
			            ?>
					</div>
				<?php else: ?>
					<p>Sorry, no results matched your search.</p>
				<?php endif; ?>
			</div>
	
			<?php 
			else : //Display Single Product Details
			    $args = array('p' => $_GET['project_id'],'post_type' => 'project');
			    $query = new WP_Query($args);
			    if ($query->have_posts()) : 
					while ($query->have_posts()) : 
						$query->the_post();
						global $post;
			?>
				<div class="medium-12 large-4 sidebar columns">
					<div class="row">
						<div class="large-12 columns">
					     <?php $project_gallery_filter_form->the_form(); ?>
						</div>
					</div>
				</div>
				<!--row--> 
				<!--sidebar--><!--THIS IS THE INDIVIDUAL SLIDESHOW PROJECT PAGE STARTING POINT-->
				<div class="medium-12 large-8 int-main columns">
					<div class="row project-info project-wrap">
						<div class="small-12 columns">
							<h2>Project: <?=the_title()?></h2>
							<?php if (have_rows('images')) : ?>
								<div class="gallery-holder">
									<div id="gallery-slider" class="royalSlider heroSlider rsMinW">
										<?php
										while (have_rows('images') ) : 
											the_row();
											$img = get_sub_field('image');
											$caption = get_sub_field('caption');
										?>
											
											<div class="rsContent">
												<img src="<?=$img?>" alt="<?=$caption?>">
											</div>
										<?php endwhile;	?>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<div class="info-holder">
							<?=the_content()?>
						</div>
						<?php if ($loc = get_field('location')) : ?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Location</p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=$loc?></p>
								</div>
							</div>
						<?php 
						endif;
						
						if ($app = get_field('application')) :
							$ia = 0;
							$string = '';
							if (is_array($app)) :
								foreach ($app as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $app;
							endif;
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Application<?=$sa?></p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;
							
						if ($ext = get_field('exterior_color')) :
							$ia = 0;
							$string = '';
							if (is_array($ext)) :
								foreach ($ext as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $ext;
							endif;
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Exterior Color<?=$sa?></p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;
							
						if ($int = get_field('interior_color')) :
							$string = '';
							$ia = 0;
							if (is_array($int)) :
								foreach ($int as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else : 
								$string = $int;
							endif;
							
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Interior Color<?=$sa?></p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-3"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;
							
						if ($glz = get_field('glaze')) :
							$string = '';
							$ia = 0;
							if (is_array($glz)) :
								foreach ($glz as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $glz;
							endif;
							
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Glaze<?=$sa?></p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php
						endif;
						
						if ($w = get_field('width')) :
						?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Width</p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=$w?></p>
								</div>
							</div>
						<?php 
						endif;
						
						if ($l = get_field('length_/_projection')) :
						?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Length / Projection</p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=$l?></p>
								</div>
							</div>
						<?php
						endif;
						
						if ($h = get_field('ridge_height')) :
						?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Ridge Height</p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=$h?></p>
								</div>
							</div>
						<?php
						endif; 
							
						if ($prod = get_field('products')) :
							$ia = 0;
							$ib = 0;
							$ic = 0;
							$string = '';
							$stringb = '';
							$stringc = '';
							$stringd = '';
							//project has more than one product
							if (is_array($prod)) :
								foreach ($prod as $a) :
									$string .= $a . ', ';
									$ia++;
									if ($type = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$a))) . '_product_types')) :
										if (is_array($type)) :
											foreach ($type as $b) :
												$stringb .= $b . ', ';
												$ib++;
											endforeach;
										else : 
											$stringb = $type;
										endif;
									endif;
									
									if ($detail = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$a))) . '_product_details')) :
										if (is_array($detail)) :
											foreach ($detail as $c) :
												$stringc .= $c . ', ';
												$ic++;
											endforeach;
										else :
											$stringc = $detail;
										endif;
									endif;
								endforeach;
								$string = rtrim($string, ', ');
								$stringb = rtrim($stringb, ', ');
								$stringc = rtrim($stringc, ', ');
							else : //if project has only one product defined (default)
								$string = $prod;
								if ($type = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$prod))) . '_product_types')) :
									if (is_array($type)) :
										foreach ($type as $b) :
											$stringb .= $b . ', ';
											$ib++;
										endforeach;
									else :
										$stringb = $type;
									endif;
								endif;
								
								if ($detail = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$prod))) . '_product_details')) :
									if (is_array($detail)) :
										foreach ($detail as $c) :
											$stringc .= $c . ', ';
											$ic++;
										endforeach;
									else :
										$stringc = $detail;
									endif;
								endif;
								
								if ($glass = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$prod))) . '_product_glass')) :
									if (is_array($glass)) :
										foreach ($glass as $d) :
											$stringd .= $d . ', ';
										endforeach;
									else :
										$stringd = $glass;
									endif;
								endif;
								$string = rtrim($string, ', ');
								$stringb = rtrim($stringb, ', ');
								$stringc = rtrim($stringc, ', ');
								$stringd = rtrim($stringd, ', ');
							endif;
							
							$sa = ($ia > 1) ? 's' : '';
							$sb = ($ib > 1) ? 's' : '';
							$sc = ($ic > 1) ? 's' : '';
							?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Product<?=$sa?></p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Product Type<?=$sb?></p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=' ' . $stringb?></p>
								</div>
							</div>
							<?php if (strlen($stringc) > 0) : ?>
								<div class="row small-collapse">
									<div class="small-12 medium-4 columns">
										<p class="info-text-1">Product Detail<?=$sc?></p>
									</div>
									<div class="small-12 medium-8 columns">
										<p class="info-text-2"><?=' ' . $stringc?></p>
									</div>
								</div>
							<?php endif; ?>
							<div class="row small-collapse">
								<div class="small-12 medium-4 columns">
									<p class="info-text-1">Product Glass SqFt.</p>
								</div>
								<div class="small-12 medium-8 columns">
									<p class="info-text-2"><?=' ' . $stringd?></p>
								</div>
							</div>
						<?php endif; ?>
						<div class="row small-collapse">
							<div class="small-12 medium-4 columns">
								<p class="info-text-1">PDF Download</p>
							</div>
							<div class="small-12 medium-8 columns">
								<p class="info-text-2"><a href="<?='?download-project=true&project=' . $post->ID . '&type=pdf'?>">DOWNLOAD GALLERY</a></p>
							</div>
						</div>
					</div>
					<?php // Related Projects
					$cats = get_the_category();
					$category__in = array();
					foreach ($cats as $cat) :
						if ($cat->parent == 2)
							$category__in[] = $cat->term_id;
					endforeach;
	
					$args = array(  'category__in' => $category__in,
									'post_type' => 'project',
									'posts_per_page' => '6',
									'orderby' => 'rand',
									'post__not_in' => array(get_the_ID()),
								);
					$related = new WP_Query($args);
					if ($related->have_posts()) : 
					?>
						<div class="row related-section">
							<div class="small-12 columns related-section-container">
								<h2 class="related">Related Projects</h2>
							</div>
							<?php
							while ($related->have_posts()) : 
								$related->the_post();
								global $post;
							?>
								<div class="small-12 medium-4 large-4 columns related-project-thumb">
									<div class="mask">
										<a href="?project_id=<?=$post->ID?>" class="port-img">
											<?php
											if (has_post_thumbnail()) :
												the_post_thumbnail('medium');
											else :
											?>
												<img src="/wp-content/uploads/2015/08/placeholder.png" height="177" width="236">
											<?php endif; ?>
										</a>
									</div>
									<a href="?project_id=<?=$post->ID?>" class="port-heading">
										<h3><?=the_title()?></h3>
									</a>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
<?php				
			endwhile;
		endif;
	endif; 
	?>
</section>
<div style="clear:both;"></div>
<script type="text/javascript">var upload_dir_baseurl = '<?php $upload_dir = wp_upload_dir(); echo $upload_dir['baseurl']; ?>';</script>
<script type="text/javascript">
function toggleProduct(product) {
	jQuery('#wpas-meta_'+product+'_product_types input[type=checkbox], #wpas-meta_'+product+'_product_details input[type=checkbox]').attr('checked', false);
	jQuery('#wpas-meta_'+product+'_product_types, #wpas-meta_'+product+'_product_details').toggle();
}

function uncheckProduct(product) {
	jQuery('#wpas-meta_'+product+'_product_types, #wpas-meta_'+product+'_product_details').hide();
}

jQuery(function() {
	
	/*
	//If using checkboxes for each product
	var products = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33];
	products.forEach(function(product) {
		var name = '#wpas-meta_products-checkbox-'+product;
		if (!jQuery(name).is(':checked'))
			uncheckProduct(jQuery(name).val().replace(/\W+/g, '_').toLowerCase());
		
		jQuery(document).on('click',name,function(){
			toggleProduct(jQuery(this).val().replace(/\W+/g, '_').toLowerCase());
		});
	});
	*/
	console.log("<?=$log?>");
	//If using select input for products
	var selected = jQuery('select#meta_products option:selected').val();
	selected = selected.replace(/\W+/g, '_').toLowerCase();
	var items = ['types','details','glass'];
	items.forEach(function(item) {
		jQuery('.product_' + item + '_group').each(function(){
			if (jQuery(this).attr('id') != 'wpas-meta_' + selected + '_product_' + item)
			{
				jQuery(this).children('input[type=checkbox]').attr('checked', false);
				jQuery(this).hide();
			}
		});
	});
	
	jQuery(document).on('change','select#meta_products',function(){
		var newChoice = jQuery('select#meta_products option:selected').val();
		newChoice = newChoice.replace(/\W+/g, '_').toLowerCase();
		var items = ['types','details','glass'];
		items.forEach(function(item) {
			jQuery('.product_' + item + '_group').each(function(){
				if (jQuery(this).attr('id') != 'wpas-meta_' + newChoice + '_product_' + item)
				{
					var thisId = jQuery(this).attr('id');
					jQuery('#' + thisId + ' input[type=checkbox]').attr('checked', false);
					jQuery(this).hide();
				} else {
					jQuery(this).show();
				}
			});
		});
	});
});
</script>
<?php 
	endwhile;
endif; 
get_footer();
?>