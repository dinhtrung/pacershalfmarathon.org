<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_googlemap_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_googlemap_theme_setup' );
	function runcrew_sc_googlemap_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_googlemap_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('runcrew_sc_googlemap')) {	
	function runcrew_sc_googlemap($atts, $content = null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = runcrew_get_custom_option('googlemap_style');
        $api_key = runcrew_get_theme_option('api_google');
        runcrew_enqueue_script( 'googlemap', runcrew_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		runcrew_enqueue_script( 'runcrew-googlemap-script', runcrew_get_file_url('js/core.googlemap.js'), array(), null, true );
		runcrew_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = runcrew_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => runcrew_get_custom_option('googlemap_title'),
				'description' => runcrew_strmacros(runcrew_get_custom_option('googlemap_description')),
				'latlng' => runcrew_get_custom_option('googlemap_latlng'),
				'address' => runcrew_get_custom_option('googlemap_address'),
				'point' => runcrew_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !runcrew_param_is_off($scheme) && !runcrew_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(runcrew_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('runcrew_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	runcrew_require_shortcode("trx_googlemap", "runcrew_sc_googlemap");
}


if (!function_exists('runcrew_sc_googlemap_marker')) {	
	function runcrew_sc_googlemap_marker($atts, $content = null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		runcrew_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : runcrew_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	runcrew_require_shortcode("trx_googlemap_marker", "runcrew_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_googlemap_reg_shortcodes');
	function runcrew_sc_googlemap_reg_shortcodes() {
	
		runcrew_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'runcrew'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'runcrew') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'runcrew'),
					"desc" => wp_kses_data( __("Map zoom factor", 'runcrew') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'runcrew'),
					"desc" => wp_kses_data( __("Select map style", 'runcrew') ),
					"value" => "default",
					"type" => "checklist",
					"options" => runcrew_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"options" => runcrew_get_sc_param('schemes')
				),
				"width" => runcrew_shortcodes_width('100%'),
				"height" => runcrew_shortcodes_height(240),
				"top" => runcrew_get_sc_param('top'),
				"bottom" => runcrew_get_sc_param('bottom'),
				"left" => runcrew_get_sc_param('left'),
				"right" => runcrew_get_sc_param('right'),
				"id" => runcrew_get_sc_param('id'),
				"class" => runcrew_get_sc_param('class'),
				"animation" => runcrew_get_sc_param('animation'),
				"css" => runcrew_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'runcrew'),
				"desc" => wp_kses_data( __("Google map marker", 'runcrew') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'runcrew'),
						"desc" => wp_kses_data( __("Address of this marker", 'runcrew') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'runcrew'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'runcrew') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'runcrew'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'runcrew') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'runcrew'),
						"desc" => wp_kses_data( __("Title for this marker", 'runcrew') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'runcrew'),
						"desc" => wp_kses_data( __("Description for this marker", 'runcrew') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => runcrew_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_googlemap_reg_shortcodes_vc');
	function runcrew_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'runcrew'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'runcrew'),
					"description" => wp_kses_data( __("Map zoom factor", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'runcrew'),
					"description" => wp_kses_data( __("Map custom style", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'runcrew'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('animation'),
				runcrew_get_vc_param('css'),
				runcrew_vc_width('100%'),
				runcrew_vc_height(240),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'runcrew'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'runcrew') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'runcrew'),
					"description" => wp_kses_data( __("Address of this marker", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'runcrew'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("Title for this marker", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				runcrew_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends RUNCREW_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends RUNCREW_VC_ShortCodeCollection {}
	}
}
?>