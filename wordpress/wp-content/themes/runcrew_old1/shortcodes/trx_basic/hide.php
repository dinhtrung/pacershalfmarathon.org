<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_hide_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_hide_theme_setup' );
	function runcrew_sc_hide_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('runcrew_sc_hide')) {	
	function runcrew_sc_hide($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	runcrew_require_shortcode('trx_hide', 'runcrew_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_hide_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_hide_reg_shortcodes');
	function runcrew_sc_hide_reg_shortcodes() {
	
		runcrew_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'runcrew'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'runcrew'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'runcrew'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'runcrew') ),
					"value" => "yes",
					"size" => "small",
					"options" => runcrew_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>