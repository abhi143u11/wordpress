<?php
/*
AJAX Results Template Example

This is an example of a template part which can be used to customize how search
results appear when using AJAX.
*/
?>

<?php
	if ( have_posts() ): 
						while ( have_posts() ): the_post();
							$post_type = get_post_type_object($post->post_type); 
				?>
							<a href="<?=the_permalink()?>">
								<li>
									<?php
									if ( has_post_thumbnail() )
										the_post_thumbnail('thumbnail');
									echo '<span>';
									echo the_title();
									echo '</span>';
									?>
								</li>
							</a>
			    <?php 
			            endwhile; 
			
			         else :
			            echo '<p>Sorry, no results matched your search.</p>';
			         endif;
			         ?>