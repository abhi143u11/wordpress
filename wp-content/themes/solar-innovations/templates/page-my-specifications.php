<?php
/**
 *
 * Template Name: My Specifications
 *
 */

if (!is_user_logged_in()) :
	header('Location: ' . get_bloginfo('url'));
	die();
endif;

global $current_user, $wp_roles;
get_currentuserinfo();

get_header(); 
?>
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
	<div class="medium-12 large-8 int-main columns">
		<?php 
		do_action( 'foundationpress_before_content' );
 
		if ( have_posts() ) : 
			while ( have_posts() ) : 
				the_post(); 
			
				$search_criteria['field_filters'] = array(
					array('key' => 'created_by', 'value' => get_current_user_id()),
					array('key' => 'status', 'value' => 'active'),
				);
				// get all entry ids
				$entries = GFAPI::get_entries(array(3) /* This is an array of ALL specification form ids */, $search_criteria);
				$total_space = 0;
		?>
				<h1><?=the_title()?></h1>
				<?php
				the_content();
				
				if (count($entries) > 0) :
				?>
					<table class="actforms">
						<thead>
							<th>Specification Name</th>
							<th>Created</th>
							<th>Action</th>
							<th>Download</th>
						</thead>
						<tbody>
				<?php
						foreach ($entries as $entry) :
							$form = GFAPI::get_form($entry['form_id']);
							
							$filename = $form['title'] . '_' . $entry['form_id'] . $entry['id'];
							$pdfsize = filesize(getcwd() . '/wp-content/uploads/PDF_EXTENDED_TEMPLATES/output/' . $entry['form_id'] . $entry['id'] . '/' . $filename . '.pdf');
							$docxsize = filesize(getcwd() . '/wp-content/uploads/PDF_EXTENDED_TEMPLATES/output/' . $entry['form_id'] . $entry['id'] . '/' . $filename . '.docx');
				
							$total_space += $pdfsize;
							$total_space += $docxsize;
							$company_name_field_id = 0;
							$project_name_field_id = 0;
							foreach ($form['fields'] as $field) :
								if (in_array($field['label'],array('Company Name', 'Project Name'))) :
									switch ($field['label'])
									{
										case 'Company Name':
											$company_name_field_id = $field['id'];
											break;
										case 'Project Name':
											$project_name_field_id = $field['id'];
											break;
									}
								endif;
							endforeach;
							
							if ($company_name_field_id == 0 || $project_name_field_id == 0)
								continue;
							
							// Create row
				?>
							<tr>
								<td><?=$form['title'] . '<br /><span style="font-size:0.9em">' . $entry[$company_name_field_id] . '<br />' . $entry[$project_name_field_id]?></span></td>
								<td><?=$entry['date_created']?></td>
								<td><a href="<?=get_bloginfo('url')?>/<?=$form['fields'][0]['defaultValue']?>/<?=strtolower(str_replace(' ','-',$form['title']))?>/?edit-form=<?=$entry['id']?>">Copy As New</a><br /><a href="?delete-form=true&entry=<?=$entry['id']?>">Delete</a></td>
								<td><a href="?download-form=true&entry=<?=$entry['id']?>&type=pdf">PDF</a> <a href="?download-form=true&entry=<?=$entry['id']?>&type=docx">DOC</a></td>
							</tr>
				<?php endforeach; ?>
						</tbody>
					</table>
				<?php
					
					if ($total_space > 1000000 /* (1mb) */ ) :
						echo '<br /><strong>You are using ' . FileSizeConvert($total_space) . '. Please delete some entries.</strong>';			
					else :
						echo '<br />You are using ' . FileSizeConvert($total_space) . ' of 1 MB.';
					endif;
				else :
				?>
					<p>No Specifications Found</p>
		<?php
				endif;
		?>
			<br /><a href="<?=bloginfo('url')?>/technical-information/technical-docs/spec-writer/">Start New Specification &raquo;</a>
		<?php
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