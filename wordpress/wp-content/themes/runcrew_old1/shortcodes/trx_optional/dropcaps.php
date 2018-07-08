<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_dropcaps_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_dropcaps_theme_setup' );
	function runcrew_sc_dropcaps_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_dropcaps_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_dropcaps_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('runcrew_sc_dropcaps')) {	
	function runcrew_sc_dropcaps($atts, $content=null){
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width, $height);
		$style = min(2, max(1, $style));
		$content = do_shortcode(str_replace(array('[vc_column_text]', '[/vc_column_text]'), array('', ''), $content));
		$output = runcrew_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(runcrew_substr($content, 0, 1)) . '</span>' . trim(runcrew_substr($content, 1))
			. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	runcrew_require_shortcode('trx_dropcaps', 'runcrew_sc_dropcaps');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_dropcaps_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_dropcaps_reg_shortcodes');
	function runcrew_sc_dropcaps_reg_shortcodes() {
	
		runcrew_sc_map("trx_dropcaps", array(
			"title" => esc_html__("Dropcaps", 'runcrew'),
			"desc" => wp_kses_data( __("Make first letter as dropcaps", 'runcrew') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'runcrew'),
					"desc" => wp_kses_data( __("Dropcaps style", 'runcrew') ),
					"value" => "1",
					"type" => "checklist",
					"options" => runcrew_get_list_styles(1, 2)
				),
				"_content_" => array(
					"title" => esc_html__("Paragraph content", 'runcrew'),
					"desc" => wp_kses_data( __("Paragraph with dropcaps content", 'runcrew') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => runcrew_shortcodes_width(),
				"height" => runcrew_shortcodes_height(),
				"top" => runcrew_get_sc_param('top'),
				"bottom" => runcrew_get_sc_param('bottom'),
				"left" => runcrew_get_sc_param('left'),
				"right" => runcrew_get_sc_param('right'),
				"id" => runcrew_get_sc_param('id'),
				"class" => runcrew_get_sc_param('class'),
				"animation" => runcrew_get_sc_param('animation'),
				"css" => runcrew_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_dropcaps_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_dropcaps_reg_shortcodes_vc');
	function runcrew_sc_dropcaps_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_dropcaps",
			"name" => esc_html__("Dropcaps", 'runcrew'),
			"description" => wp_kses_data( __("Make first letter of the text as dropcaps", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_dropcaps',
			"class" => "trx_sc_container trx_sc_dropcaps",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'runcrew'),
					"description" => wp_kses_data( __("Dropcaps style", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Paragraph text", 'runcrew'),
					"description" => wp_kses_data( __("Paragraph with dropcaps content", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('animation'),
				runcrew_get_vc_param('css'),
				runcrew_vc_width(),
				runcrew_vc_height(),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_Dropcaps extends RUNCREW_VC_ShortCodeContainer {}
	}
}
?>