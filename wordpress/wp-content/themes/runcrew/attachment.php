<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move runcrew_set_post_views to the javascript - counter will work under cache system
	if (runcrew_get_custom_option('use_ajax_views_counter')=='no') {
		runcrew_set_post_views(get_the_ID());
	}

	runcrew_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !runcrew_param_is_off(runcrew_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>