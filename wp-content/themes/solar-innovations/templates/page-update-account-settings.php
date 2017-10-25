<?php
/**
 * Template Name: Update Account Settings
 *
 * Allow users to update their profiles from Frontend.
 *
 */

if (!is_user_logged_in()) :
	header('Location: ' . get_bloginfo('url'));
	die();
endif;

global $current_user, $wp_roles;
get_currentuserinfo();

$error = array();    
/* If profile was saved, update profile. */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user') {

    /* Update user password. */
    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
        if ( $_POST['pass1'] == $_POST['pass2'] )
            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
        else
            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }

    /* Update user information. */
    if ( !empty( $_POST['email'] ) ){
        if (!is_email(esc_attr( $_POST['email'] )))
            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
        elseif(email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
        else{
            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
        }
    }

    if ( !empty( $_POST['first-name'] ) )
        update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
    if ( !empty( $_POST['last-name'] ) )
        update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );

    /* Redirect so the page will show updated info.*/
  /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
    if ( count($error) == 0 ) {
        //action hook for plugins and extra fields saving
        do_action('edit_user_profile_update', $current_user->ID);
        wp_redirect( get_bloginfo('url').'/account/account-settings/' );
        exit;
    }
}

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
		?>
				<h1>Update Account Settings</h1>
		<?php
				the_content();
		?>
			    <div id="post-<?php the_ID(); ?>">
			        <div class="entry-content entry">
			            <?php 
				        the_content();
				        if (count($error) > 0) 
				        	echo '<p class="error">' . implode("<br />", $error) . '</p>'; 
				        ?>
		                <form method="post" id="updateuser" action="<?php the_permalink(); ?>">
			                <h2>Account Information</h2>
		                    <p class="form-username">
		                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
		                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
		                    </p><!-- .form-username -->
		                    <p class="form-username">
		                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
		                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
		                    </p><!-- .form-username -->
							<p class="form-email">
		                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
		                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
		                    </p><!-- .form-email -->
		                    
		                    <?php do_action('edit_user_profile',$current_user); ?>
		                    
							<h2>Change Password</h2>
			                <p class="form-password">
		                        <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
		                        <input class="text-input" name="pass1" type="password" id="pass1" />
		                    </p><!-- .form-password -->
		                    <p class="form-password">
		                        <label for="pass2"><?php _e('Repeat Password *', 'profile'); ?></label>
		                        <input class="text-input" name="pass2" type="password" id="pass2" />
		                    </p><!-- .form-password -->
							<p class="form-submit">
		                        <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Save Changes', 'profile'); ?>" />
		                        <?php wp_nonce_field( 'update-user' ) ?>
		                        <input name="action" type="hidden" id="action" value="update-user" />
		                    </p><!-- .form-submit -->
		                </form>
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