<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_popup_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_popup_theme_setup' );
	function runcrew_sc_popup_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_popup_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_popup_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('runcrew_sc_popup')) {	
	function runcrew_sc_popup($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		runcrew_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	runcrew_require_shortcode('trx_popup', 'runcrew_sc_popup');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_popup_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_popup_reg_shortcodes');
	function runcrew_sc_popup_reg_shortcodes() {
	
		runcrew_sc_map("trx_popup", array(
			"title" => esc_html__("Popup window", 'runcrew'),
			"desc" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'runcrew') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Container content", 'runcrew'),
					"desc" => wp_kses_data( __("Content for section container", 'runcrew') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => runcrew_get_sc_param('top'),
				"bottom" => runcrew_get_sc_param('bottom'),
				"left" => runcrew_get_sc_param('left'),
				"right" => runcrew_get_sc_param('right'),
				"id" => runcrew_get_sc_param('id'),
				"class" => runcrew_get_sc_param('class'),
				"css" => runcrew_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_popup_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_popup_reg_shortcodes_vc');
	function runcrew_sc_popup_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_popup",
			"name" => esc_html__("Popup window", 'runcrew'),
			"description" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_popup',
			"class" => "trx_sc_collection trx_sc_popup",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Container content", 'runcrew'),
					"description" => wp_kses_data( __("Content for popup container", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('css'),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Popup extends RUNCREW_VC_ShortCodeCollection {}
	}
}
?>