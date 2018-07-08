<?php
/**
 * Single post
 */
get_header(); 

$single_style = runcrew_storage_get('single_style');
if (empty($single_style)) $single_style = runcrew_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	runcrew_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !runcrew_param_is_off(runcrew_get_custom_option('show_sidebar_main')),
			'content' => runcrew_get_template_property($single_style, 'need_content'),
			'terms_list' => runcrew_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>