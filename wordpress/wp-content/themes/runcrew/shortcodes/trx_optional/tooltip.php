<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_tooltip_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_tooltip_theme_setup' );
	function runcrew_sc_tooltip_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('runcrew_sc_tooltip')) {	
	function runcrew_sc_tooltip($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	runcrew_require_shortcode('trx_tooltip', 'runcrew_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_tooltip_reg_shortcodes');
	function runcrew_sc_tooltip_reg_shortcodes() {
	
		runcrew_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'runcrew'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'runcrew') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'runcrew'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'runcrew'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'runcrew') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => runcrew_get_sc_param('id'),
				"class" => runcrew_get_sc_param('class'),
				"css" => runcrew_get_sc_param('css')
			)
		));
	}
}
?>