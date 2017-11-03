<?php 
if (have_posts()) : 
	the_post();
	header('Location: ' . site_url() . '/gallery/?project_id=' . get_the_ID());
	die();
endif;