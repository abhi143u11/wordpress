<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *
 * @package WordPress
 * @subpackage FoundationPress
 * @since FoundationPress 1.0
 */

global $current_user; 
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?> >
	<head>
    <meta name="google-site-verification" content="9BIyDawnOFdytN1y-7t5orp6xqAHDqt7cCZIV4vSoLE" />
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<title><?php if ( is_category() ) {
			echo 'Category Archive for &quot;'; single_cat_title(); echo '&quot; | '; bloginfo( 'name' );
		} elseif ( is_tag() ) {
			echo 'Tag Archive for &quot;'; single_tag_title(); echo '&quot; | '; bloginfo( 'name' );
		} elseif ( is_archive() ) {
			wp_title( '' ); echo ' Archive | '; bloginfo( 'name' );
		} elseif ( is_search() ) {
			echo 'Search for &quot;'.esc_html( $s ).'&quot; | '; bloginfo( 'name' );
		} elseif ( is_home() || is_front_page() ) {
			bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
		}  elseif ( is_404() ) {
			echo 'Error 404 Not Found | '; bloginfo( 'name' );
		} elseif ( is_single() ) {
			wp_title( '' );
		} else {
			echo wp_title( ' | ', 'false', 'right' ); bloginfo( 'name' );
		} ?></title>

<!-- CSS -->
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/style.css" type="text/css" />

<link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/favicon.png" type="image/x-icon">
<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/touch-icon-iphone.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/touch-icon-ipad.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/touch-icon-iphone-retina.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/touch-icon-ipad-retina.png">
<?php wp_head(); ?>
<?php the_field('header_script'); ?>
</head>
<body <?php body_class(); ?>>

<?php 
// find which template is rendering
// if( current_user_can( 'manage_options' ) && $current_user->display_name == "N K" ) {
// 	printf( '<div><strong>Current template:</strong> %s</div>', get_current_template() ); 
// }
?>

	<section class="row site-container">
		<header class="row header">
			<div class="small-8 medium-8 large-3 columns">
				<a href="<?php bloginfo('url'); ?>"><img class="logo show-for-large-up" src="<?php bloginfo('template_directory'); ?>/img/main-logo.png"><img class="logo hide-for-large-up" src="<?php bloginfo('template_directory'); ?>/img/mobile-logo.png"></a><p class="hphone hide-for-large-up"></p>
			</div>
			<div class="small-4 medium-4 large-9 columns">
				<a class="shiftnav-toggle hide-for-large-up m-nav" data-shiftnav-target="shiftnav-main" href=""><img src="<?php bloginfo('template_directory'); ?>/img/m-nav.png" /></a>
				<div class="row top-nav visible-for-large-up">
					<div class="medium-6 columns global-nav">
						<!-- <a href="<?php //bloginfo('url'); ?>">Home</a> -->
                        <a href="/Contact/">Contact Us</a>
                        <a href="/Careers/">Careers</a>
						<a href="/find-a-dealer/">Find a Dealer</a>
						<?php if (!is_user_logged_in()) : ?>
							<a href="#" data-reveal-id="login">Login</a>
						<?php else : ?>
							<a href="#" data-dropdown="hover1" data-options="is_hover:true; hover_timeout:50">Account</a>
							<ul id="hover1" class="f-dropdown" data-dropdown-content>
								<li><a href="<?=bloginfo('url')?>/account/account-settings#">Account Settings</a></li>
								<li><a href="<?=bloginfo('url')?>/help/">Help</a></li>								
								<!-- <li><a href="<? //bloginfo('url')?>/account/subscriptions/">Subscriptions</a></li> -->
								<!-- <li><a href="<? //=bloginfo('url')?>/account/my-specifications">My Specifications</a></li> -->
								<!-- <li><a href="<? //=bloginfo('url')?>/technical-information/technical-docs/spec-writer/">Build a Spec</a></li> -->
								<!-- <li><a href="<? //=bloginfo('url')?>/account/my-quotes">My Quotes</a></li> -->
								<!-- <li><a href="<? //=bloginfo('url')?>/why-choose-solar/quote-form">Build a Quote</a></li> -->
								<!-- <li><a href="<? //= bloginfo('url')?>/account/my-gallery">My Gallery</a></li> -->
								<?php if ($current_user->roles[0] == 'osr') : ?><li><a href="<?=bloginfo('url')?>/account/my-downloads">My Downloads</a></li><?php endif; ?>
								<li class="log"><a href="<?=wp_logout_url(get_bloginfo('url'))?>">LOGOUT</a></li>
							</ul>
						<?php endif; ?>
						<div id="login" class="reveal-modal tiny" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
							<h2 id="modalTitle">Sign In</h2>
							<?php wp_login_form(); ?>
							<a href="<?php echo wp_lostpassword_url(); ?>" title="Lost Password">Lost Password?</a>
							<a class="close-reveal-modal" aria-label="Close">&#215;</a>
						</div>
					</div>
				<div class="medium-3 columns global-search">
					<div class="small-12 right columns">
	        	<div class="search-box"><form action="<?php bloginfo('url'); ?>" method="get">
		<input type="text" name="s" id="search" value="<?php the_search_query(); ?>" />
		<span class="text-center icon"><input type="image" alt="Search" src="<?php bloginfo('template_directory'); ?>/img/search-icon.png" /></span>
</form> </div>
		    </div>
				</div>
				<div class="medium-2 columns global-contact">
					<a href="<?=bloginfo('url')?>/quick-contact/" class="button right">Quick Contact</a>
				</div>
			</div>
			<div class="row bottom-nav visible-for-large-up">
			</div>

		</div>
		</header>
                           <div class="special_menu">
				<nav class="top-bar" data-topbar role="navigation">
					<section class="top-bar-section"> 
  						<?php if( function_exists( 'ubermenu' ) ): ?>
  						<?php ubermenu( 'main' , array( 'theme_location' => 'primary' ) ); ?>
						<?php else: ?>
 						<?php display_primary_menu(); ?>
						<?php endif; ?>
						
					</section>
				</nav>
            </div>


