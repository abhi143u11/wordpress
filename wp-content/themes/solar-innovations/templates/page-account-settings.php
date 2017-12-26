<?php
/**
 *
 * Template Name: Account Settings
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
	
	<div class="medium-12 large-12 int-main columns">
		<?php 
		do_action( 'foundationpress_before_content' );
 
		if ( have_posts() ) : 
			while ( have_posts() ) : 
				the_post(); 
		?>
				<h1>Account Settings</h1>
				
			    <div id="post-<?php the_ID(); ?>">
			        <div class="entry-content entry">
			            <?php the_content(); ?>
	                    <p class="form-username">
	                        <span>Username</span> <?=$current_user->user_login?>
	                    </p>
	                    <p class="form-password">
	                        <span>Password</span> **********
	                    </p>
	                    <p class="form-company">
	                        <span>Company</span> <?php the_author_meta( 'company', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-name">
	                        <span>Name</span> <?=$current_user->user_firstname . ' ' . $current_user->user_lastname?>
	                    </p>
						<p class="form-email">
	                        <span>Email</span> <?=$current_user->user_email?>
	                    </p>
	                    <p class="form-phone">
	                        <span>Phone</span> <?php the_author_meta( 'phone', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-fax">
	                        <span>Fax</span> <?php the_author_meta( 'fax', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-address">
	                        <span>Address</span> <?php the_author_meta( 'address', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-address2">
	                        <span>Address 2</span> <?php the_author_meta( 'address2', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-city">
	                        <span>City</span> <?php the_author_meta( 'city', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-state">
	                        <span>State</span> <?php the_author_meta( 'state', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-zip">
	                        <span>Zip</span> <?php the_author_meta( 'zip', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-country">
	                        <span>Country</span> <?php the_author_meta( 'country', $current_user->ID ); ?>
	                    </p>
	                    <p class="form-referrer">
	                        <span>Referrer</span> <?php the_author_meta( 'referrer', $current_user->ID ); ?>
	                    </p>
		                <a href="<?=bloginfo('url')?>/account/update-account-settings">Edit Account &raquo;</a>
			        </div><!-- .entry-content -->
			    </div><!-- .hentry .post -->
		    <?php endwhile; ?>
		<?php else: ?>
		    <p class="no-data">
		        <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
		    </p><!-- .no-data -->
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>