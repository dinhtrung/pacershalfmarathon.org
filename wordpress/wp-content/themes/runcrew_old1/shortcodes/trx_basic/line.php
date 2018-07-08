<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_line_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_line_theme_setup' );
	function runcrew_sc_line_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_line_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_line_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_line id="unique_id" style="none|solid|dashed|dotted|double|groove|ridge|inset|outset" top="margin_in_pixels" bottom="margin_in_pixels" width="width_in_pixels_or_percent" height="line_thickness_in_pixels" color="line_color's_name_or_#rrggbb"]
*/

if (!function_exists('runcrew_sc_line')) {	
	function runcrew_sc_line($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"color" => "",
			"title" => "",
			"position" => "",
			"image" => "",
			"repeat" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if (empty($style)) $style = 'solid';
		if (empty($position)) $position = 'center center';
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$block_height = '';
		if ($style=='image' && !empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$attr = runcrew_getimagesize($image);
			if (is_array($attr) && $attr[1] > 0)
				$block_height = $attr[1];
		} else if (!empty($title) && empty($height) && !in_array($position, array('left center', 'center center', 'right center'))) {
			$block_height = '1.5em';
		}
		$border_pos = in_array($position, array('left top', 'center top', 'right top')) ? 'bottom' : 'top';

		$css .= runcrew_get_css_dimensions_from_values($width, $block_height)
			. ($style=='image' && !empty($image)
				? ( 'background-image: url(' . esc_url($image) . ');'
					. (runcrew_param_is_on($repeat) ? 'background-repeat: repeat-x;' : '')
					)
				: ( ($height !='' ? 'border-'.esc_attr($border_pos).'-width:' . esc_attr(runcrew_prepare_css_value($height)) . ';' : '')
					. ($style != '' ? 'border-'.esc_attr($border_pos).'-style:' . esc_attr($style) . ';' : '')
					. ($color != '' ? 'border-'.esc_attr($border_pos).'-color:' . esc_attr($color) . ';' : '')
					)
				);
		$output = '<div' . ($id ? ' id="'.esc_attr($id) . '"' : '') 
				. ' class="sc_line sc_line_position_'.esc_attr(str_replace(' ', '_', $position)) . ' sc_line_style_'.esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. (!empty($title) ? '<span class="sc_line_title">' . trim($title) . '</span>' : '')
				. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_line', $atts, $content);
	}
	runcrew_require_shortcode('trx_line', 'runcrew_sc_line');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_line_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_line_reg_shortcodes');
	function runcrew_sc_line_reg_shortcodes() {
	
		runcrew_sc_map("trx_line", array(
			"title" => esc_html__("Line", 'runcrew'),
			"desc" => wp_kses_data( __("Insert Line into your post (page)", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'runcrew'),
					"desc" => wp_kses_data( __("Line style", 'runcrew') ),
					"value" => "solid",
					"dir" => "horizontal",
					"options" => runcrew_get_list_line_styles(),
					"type" => "checklist"
				),
				"image" => array(
					"title" => esc_html__("Image as separator", 'runcrew'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site to use it as separator", 'runcrew') ),
					"readonly" => false,
					"dependency" => array(
						'style' => array('image')
					),
					"value" => "",
					"type" => "media"
				),
				"repeat" => array(
					"title" => esc_html__("Repeat image", 'runcrew'),
					"desc" => wp_kses_data( __("To repeat an image or to show single picture", 'runcrew') ),
					"dependency" => array(
						'style' => array('image')
					),
					"value" => "no",
					"type" => "switch",
					"options" => runcrew_get_sc_param('yes_no')
				),
				"color" => array(
					"title" => esc_html__("Color", 'runcrew'),
					"desc" => wp_kses_data( __("Line color", 'runcrew') ),
					"dependency" => array(
						'style' => array('solid', 'dashed', 'dotted', 'double')
					),
					"value" => "",
					"type" => "color"
				),
				"title" => array(
					"title" => esc_html__("Title", 'runcrew'),
					"desc" => wp_kses_data( __("Title that is going to be placed in the center of the line (if not empty)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"position" => array(
					"title" => esc_html__("Title position", 'runcrew'),
					"desc" => wp_kses_data( __("Title position", 'runcrew') ),
					"dependency" => array(
						'title' => array('not_empty')
					),
					"value" => "center center",
					"options" => runcrew_get_list_bg_image_positions(),
					"type" => "select"
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
if ( !function_exists( 'runcrew_sc_line_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_line_reg_shortcodes_vc');
	function runcrew_sc_line_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_line",
			"name" => esc_html__("Line", 'runcrew'),
			"description" => wp_kses_data( __("Insert line (delimiter)", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			"class" => "trx_sc_single trx_sc_line",
			'icon' => 'icon_trx_line',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'runcrew'),
					"description" => wp_kses_data( __("Line style", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"std" => "solid",
					"value" => array_flip(runcrew_get_list_line_styles()),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image as separator", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site to use it as separator", 'runcrew') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('image')
					),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "repeat",
					"heading" => esc_html__("Repeat image", 'runcrew'),
					"description" => wp_kses_data( __("To repeat an image or to show single picture", 'runcrew') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('image')
					),
					"class" => "",
					"value" => array("Repeat image" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Line color", 'runcrew'),
					"description" => wp_kses_data( __("Line color", 'runcrew') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('solid','dotted','dashed','double')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("Title that is going to be placed in the center of the line (if not empty)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Title position", 'runcrew'),
					"description" => wp_kses_data( __("Title position", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"std" => "center center",
					"value" => array_flip(runcrew_get_list_bg_image_positions()),
					"type" => "dropdown"
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Line extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>