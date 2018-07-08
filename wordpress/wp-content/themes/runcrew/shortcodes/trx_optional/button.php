<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_button_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_button_theme_setup' );
	function runcrew_sc_button_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_button_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('runcrew_sc_button')) {	
	function runcrew_sc_button($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "round",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
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
		$css .= runcrew_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (runcrew_param_is_on($popup)) runcrew_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (runcrew_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	runcrew_require_shortcode('trx_button', 'runcrew_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_button_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_button_reg_shortcodes');
	function runcrew_sc_button_reg_shortcodes() {
	
		runcrew_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'runcrew'),
			"desc" => wp_kses_data( __("Button with link", 'runcrew') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'runcrew'),
					"desc" => wp_kses_data( __("Button caption", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'runcrew'),
					"desc" => wp_kses_data( __("Select button's shape", 'runcrew') ),
					"value" => "round",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'runcrew'),
						'round' => esc_html__('Round', 'runcrew')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'runcrew'),
					"desc" => wp_kses_data( __("Select button's style", 'runcrew') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'runcrew'),
						'border' => esc_html__('Border', 'runcrew')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'runcrew'),
					"desc" => wp_kses_data( __("Select button's size", 'runcrew') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'runcrew'),
						'medium' => esc_html__('Medium', 'runcrew'),
						'large' => esc_html__('Large', 'runcrew')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'runcrew'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'runcrew') ),
					"value" => "",
					"type" => "icons",
					"options" => runcrew_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'runcrew'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'runcrew') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'runcrew'),
					"desc" => wp_kses_data( __("Any color for button's background", 'runcrew') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'runcrew') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'runcrew'),
					"desc" => wp_kses_data( __("URL for link on button click", 'runcrew') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'runcrew'),
					"desc" => wp_kses_data( __("Target for link on button click", 'runcrew') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'runcrew'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'runcrew') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => runcrew_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'runcrew'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'runcrew') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'runcrew_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_button_reg_shortcodes_vc');
	function runcrew_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'runcrew'),
			"description" => wp_kses_data( __("Button with link", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'runcrew'),
					"description" => wp_kses_data( __("Button caption", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'runcrew'),
					"description" => wp_kses_data( __("Select button's shape", 'runcrew') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'runcrew') => 'square',
						esc_html__('Round', 'runcrew') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'runcrew'),
					"description" => wp_kses_data( __("Select button's style", 'runcrew') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'runcrew') => 'filled',
						esc_html__('Border', 'runcrew') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'runcrew'),
					"description" => wp_kses_data( __("Select button's size", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'runcrew') => 'small',
						esc_html__('Medium', 'runcrew') => 'medium',
						esc_html__('Large', 'runcrew') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'runcrew'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'runcrew') ),
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'runcrew'),
					"description" => wp_kses_data( __("Any color for button's caption", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'runcrew'),
					"description" => wp_kses_data( __("Any color for button's background", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'runcrew'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'runcrew') ),
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'runcrew'),
					"description" => wp_kses_data( __("URL for the link on button click", 'runcrew') ),
					"class" => "",
					"group" => esc_html__('Link', 'runcrew'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'runcrew'),
					"description" => wp_kses_data( __("Target for the link on button click", 'runcrew') ),
					"class" => "",
					"group" => esc_html__('Link', 'runcrew'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'runcrew'),
					"description" => wp_kses_data( __("Open link target in popup window", 'runcrew') ),
					"class" => "",
					"group" => esc_html__('Link', 'runcrew'),
					"value" => array(esc_html__('Open in popup', 'runcrew') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'runcrew'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'runcrew') ),
					"class" => "",
					"group" => esc_html__('Link', 'runcrew'),
					"value" => "",
					"type" => "textfield"
				),
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>