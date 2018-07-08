<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_zoom_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_zoom_theme_setup' );
	function runcrew_sc_zoom_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_zoom_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_zoom_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_zoom id="unique_id" border="none|light|dark"]
*/

if (!function_exists('runcrew_sc_zoom')) {	
	function runcrew_sc_zoom($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"effect" => "zoom",
			"src" => "",
			"url" => "",
			"over" => "",
			"align" => "",
			"bg_image" => "",
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
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
	
		runcrew_enqueue_script( 'runcrew-elevate-zoom-script', runcrew_get_file_url('js/jquery.elevateZoom-3.0.4.js'), array(), null, true );
	
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css_dim = runcrew_get_css_dimensions_from_values($width, $height);
		$css_bg = runcrew_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
		$width  = runcrew_prepare_css_value($width);
		$height = runcrew_prepare_css_value($height);
		if (empty($id)) $id = 'sc_zoom_'.str_replace('.', '', mt_rand());
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if ($over > 0) {
			$attach = wp_get_attachment_image_src( $over, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$over = $attach[0];
		}
		if ($effect=='lens' && ((int) $width > 0 && runcrew_substr($width, -2, 2)=='px') || ((int) $height > 0 && runcrew_substr($height, -2, 2)=='px')) {
			if ($src)
				$src = runcrew_get_resized_image_url($src, (int) $width > 0 && runcrew_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && runcrew_substr($height, -2, 2)=='px' ? (int) $height : null);
			if ($over)
				$over = runcrew_get_resized_image_url($over, (int) $width > 0 && runcrew_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && runcrew_substr($height, -2, 2)=='px' ? (int) $height : null);
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
		$output = empty($src) 
				? '' 
				: (
					(!empty($bg_image) 
						? '<div class="sc_zoom_wrap'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
								. '"'
							. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
							. ($css_bg!='' ? ' style="'.esc_attr($css_bg).'"' : '') 
							. '>' 
						: '')
					.'<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_zoom' 
								. (empty($bg_image) && !empty($class) ? ' '.esc_attr($class) : '') 
								. (empty($bg_image) && $align && $align!='none' ? ' align'.esc_attr($align) : '')
								. '"'
							. (empty($bg_image) && !runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. '>'
							. '<img src="'.esc_url($src).'"' . ($css_dim!='' ? ' style="'.esc_attr($css_dim).'"' : '') . ' data-zoom-image="'.esc_url($over).'" alt="" />'
					. '</div>'
					. (!empty($bg_image) 
						? '</div>' 
						: '')
				);
		return apply_filters('runcrew_shortcode_output', $output, 'trx_zoom', $atts, $content);
	}
	runcrew_require_shortcode('trx_zoom', 'runcrew_sc_zoom');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_zoom_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_zoom_reg_shortcodes');
	function runcrew_sc_zoom_reg_shortcodes() {
	
		runcrew_sc_map("trx_zoom", array(
			"title" => esc_html__("Zoom", 'runcrew'),
			"desc" => wp_kses_data( __("Insert the image with zoom/lens effect", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"effect" => array(
					"title" => esc_html__("Effect", 'runcrew'),
					"desc" => wp_kses_data( __("Select effect to display overlapping image", 'runcrew') ),
					"value" => "lens",
					"size" => "medium",
					"type" => "switch",
					"options" => array(
						"lens" => esc_html__('Lens', 'runcrew'),
						"zoom" => esc_html__('Zoom', 'runcrew')
					)
				),
				"url" => array(
					"title" => esc_html__("Main image", 'runcrew'),
					"desc" => wp_kses_data( __("Select or upload main image", 'runcrew') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"over" => array(
					"title" => esc_html__("Overlaping image", 'runcrew'),
					"desc" => wp_kses_data( __("Select or upload overlaping image", 'runcrew') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"align" => array(
					"title" => esc_html__("Float zoom", 'runcrew'),
					"desc" => wp_kses_data( __("Float zoom to left or right side", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('float')
				), 
				"bg_image" => array(
					"title" => esc_html__("Background image", 'runcrew'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", 'runcrew') ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_top" => array(
					"title" => esc_html__("Top offset", 'runcrew'),
					"desc" => wp_kses_data( __("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", 'runcrew') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_bottom" => array(
					"title" => esc_html__("Bottom offset", 'runcrew'),
					"desc" => wp_kses_data( __("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", 'runcrew') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_left" => array(
					"title" => esc_html__("Left offset", 'runcrew'),
					"desc" => wp_kses_data( __("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", 'runcrew') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_right" => array(
					"title" => esc_html__("Right offset", 'runcrew'),
					"desc" => wp_kses_data( __("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", 'runcrew') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
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
if ( !function_exists( 'runcrew_sc_zoom_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_zoom_reg_shortcodes_vc');
	function runcrew_sc_zoom_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_zoom",
			"name" => esc_html__("Zoom", 'runcrew'),
			"description" => wp_kses_data( __("Insert the image with zoom/lens effect", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_zoom',
			"class" => "trx_sc_single trx_sc_zoom",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "effect",
					"heading" => esc_html__("Effect", 'runcrew'),
					"description" => wp_kses_data( __("Select effect to display overlapping image", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"std" => "zoom",
					"value" => array(
						esc_html__('Lens', 'runcrew') => 'lens',
						esc_html__('Zoom', 'runcrew') => 'zoom'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Main image", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload main image", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "over",
					"heading" => esc_html__("Overlaping image", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload overlaping image", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'runcrew'),
					"description" => wp_kses_data( __("Float zoom to left or right side", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'runcrew') ),
					"group" => esc_html__('Background', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_top",
					"heading" => esc_html__("Top offset", 'runcrew'),
					"description" => wp_kses_data( __("Top offset (padding) from background image to zoom block (in percent). For example: 3%", 'runcrew') ),
					"group" => esc_html__('Background', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_bottom",
					"heading" => esc_html__("Bottom offset", 'runcrew'),
					"description" => wp_kses_data( __("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", 'runcrew') ),
					"group" => esc_html__('Background', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_left",
					"heading" => esc_html__("Left offset", 'runcrew'),
					"description" => wp_kses_data( __("Left offset (padding) from background image to zoom block (in percent). For example: 20%", 'runcrew') ),
					"group" => esc_html__('Background', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_right",
					"heading" => esc_html__("Right offset", 'runcrew'),
					"description" => wp_kses_data( __("Right offset (padding) from background image to zoom block (in percent). For example: 12%", 'runcrew') ),
					"group" => esc_html__('Background', 'runcrew'),
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Zoom extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>