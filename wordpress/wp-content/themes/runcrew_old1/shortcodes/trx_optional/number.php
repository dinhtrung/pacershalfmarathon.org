<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_number_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_number_theme_setup' );
	function runcrew_sc_number_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_number_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_number_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('runcrew_sc_number')) {	
	function runcrew_sc_number($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < runcrew_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(runcrew_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	runcrew_require_shortcode('trx_number', 'runcrew_sc_number');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_number_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_number_reg_shortcodes');
	function runcrew_sc_number_reg_shortcodes() {
	
		runcrew_sc_map("trx_number", array(
			"title" => esc_html__("Number", 'runcrew'),
			"desc" => wp_kses_data( __("Insert number or any word as set separate characters", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"value" => array(
					"title" => esc_html__("Value", 'runcrew'),
					"desc" => wp_kses_data( __("Number or any word", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'runcrew'),
					"desc" => wp_kses_data( __("Select block alignment", 'runcrew') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('align')
				),
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
if ( !function_exists( 'runcrew_sc_number_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_number_reg_shortcodes_vc');
	function runcrew_sc_number_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_number",
			"name" => esc_html__("Number", 'runcrew'),
			"description" => wp_kses_data( __("Insert number or any word as set of separated characters", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			"class" => "trx_sc_single trx_sc_number",
			'icon' => 'icon_trx_number',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'runcrew'),
					"description" => wp_kses_data( __("Number or any word to separate", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'runcrew'),
					"description" => wp_kses_data( __("Select block alignment", 'runcrew') ),
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('align')),
					"type" => "dropdown"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('animation'),
				runcrew_get_vc_param('css'),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Number extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>