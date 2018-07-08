<?php
if (!function_exists('runcrew_theme_shortcodes_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_theme_shortcodes_setup', 1 );
	function runcrew_theme_shortcodes_setup() {
		add_filter('runcrew_filter_googlemap_styles', 'runcrew_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'runcrew_theme_shortcodes_googlemap_styles' ) ) {
	function runcrew_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'runcrew');
		$list['greyscale']	= esc_html__('Greyscale', 'runcrew');
		$list['inverse']	= esc_html__('Inverse', 'runcrew');
		return $list;
	}
}
?>