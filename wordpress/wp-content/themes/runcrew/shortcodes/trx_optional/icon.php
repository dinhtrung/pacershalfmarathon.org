<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_icon_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_icon_theme_setup' );
	function runcrew_sc_icon_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_icon_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('runcrew_sc_icon')) {	
	function runcrew_sc_icon($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
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
		$css2 = ($font_weight != '' && !runcrew_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(runcrew_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || runcrew_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !runcrew_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	runcrew_require_shortcode('trx_icon', 'runcrew_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_icon_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_icon_reg_shortcodes');
	function runcrew_sc_icon_reg_shortcodes() {
	
		runcrew_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'runcrew'),
			"desc" => wp_kses_data( __("Insert icon", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'runcrew'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'runcrew') ),
					"value" => "",
					"type" => "icons",
					"options" => runcrew_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'runcrew'),
					"desc" => wp_kses_data( __("Icon's color", 'runcrew') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'runcrew'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'runcrew') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'runcrew'),
						'round' => esc_html__('Round', 'runcrew'),
						'square' => esc_html__('Square', 'runcrew')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'runcrew'),
					"desc" => wp_kses_data( __("Icon's background color", 'runcrew') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'runcrew'),
					"desc" => wp_kses_data( __("Icon's font size", 'runcrew') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'runcrew'),
					"desc" => wp_kses_data( __("Icon font weight", 'runcrew') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'runcrew'),
						'300' => esc_html__('Light (300)', 'runcrew'),
						'400' => esc_html__('Normal (400)', 'runcrew'),
						'700' => esc_html__('Bold (700)', 'runcrew')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Icon text alignment", 'runcrew') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'runcrew'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'runcrew') ),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'runcrew_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_icon_reg_shortcodes_vc');
	function runcrew_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'runcrew'),
			"description" => wp_kses_data( __("Insert the icon", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'runcrew'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'runcrew'),
					"description" => wp_kses_data( __("Icon's color", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'runcrew'),
					"description" => wp_kses_data( __("Background color for the icon", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'runcrew'),
					"description" => wp_kses_data( __("Shape of the icon background", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'runcrew') => 'none',
						esc_html__('Round', 'runcrew') => 'round',
						esc_html__('Square', 'runcrew') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'runcrew'),
					"description" => wp_kses_data( __("Icon's font size", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'runcrew'),
					"description" => wp_kses_data( __("Icon's font weight", 'runcrew') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'runcrew') => 'inherit',
						esc_html__('Thin (100)', 'runcrew') => '100',
						esc_html__('Light (300)', 'runcrew') => '300',
						esc_html__('Normal (400)', 'runcrew') => '400',
						esc_html__('Bold (700)', 'runcrew') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'runcrew'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'runcrew'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('css'),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>