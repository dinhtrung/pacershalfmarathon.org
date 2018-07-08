<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_socials_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_socials_theme_setup' );
	function runcrew_sc_socials_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_socials_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('runcrew_sc_socials')) {	
	function runcrew_sc_socials($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => runcrew_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"show_name" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		runcrew_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? runcrew_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
                    );
				}
			}
			if (count($list) > 0) runcrew_storage_set_array('sc_social_data', 'icons', $list);
		} else if (runcrew_param_is_off($custom))
			$content = do_shortcode($content);
		if (runcrew_storage_get_array('sc_social_data', 'icons')===false) runcrew_storage_set_array('sc_social_data', 'icons', runcrew_get_custom_option('social_icons'));
		$output = runcrew_prepare_socials($show_name, runcrew_storage_get_array('sc_social_data', 'icons'));
        $output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	runcrew_require_shortcode('trx_socials', 'runcrew_sc_socials');
}


if (!function_exists('runcrew_sc_social_item')) {	
	function runcrew_sc_social_item($atts, $content=null){
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = runcrew_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(runcrew_get_socials_dir($name.'.png')))
					$icon = runcrew_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (runcrew_storage_get_array('sc_social_data', 'icons')===false) runcrew_storage_set_array('sc_social_data', 'icons', array());
			runcrew_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
                )
			);
		}
		return '';
	}
	runcrew_require_shortcode('trx_social_item', 'runcrew_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_socials_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_socials_reg_shortcodes');
	function runcrew_sc_socials_reg_shortcodes() {
	
		runcrew_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'runcrew'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'runcrew') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'runcrew'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'runcrew') ),
					"value" => runcrew_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'runcrew'),
						'images' => esc_html__('Images', 'runcrew')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'runcrew'),
					"desc" => wp_kses_data( __("Size of the icons", 'runcrew') ),
					"value" => "small",
					"options" => runcrew_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'runcrew'),
					"desc" => wp_kses_data( __("Shape of the icons", 'runcrew') ),
					"value" => "square",
					"options" => runcrew_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'runcrew'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'runcrew') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'runcrew'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'runcrew') ),
					"divider" => true,
					"value" => "no",
					"options" => runcrew_get_sc_param('yes_no'),
					"type" => "switch"
				),
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'runcrew'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'runcrew') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'runcrew'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'runcrew') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'runcrew'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'runcrew') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'runcrew'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'runcrew') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_socials_reg_shortcodes_vc');
	function runcrew_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'runcrew'),
			"description" => wp_kses_data( __("Custom social icons", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'runcrew'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'runcrew') ),
					"class" => "",
					"std" => runcrew_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'runcrew') => 'icons',
						esc_html__('Images', 'runcrew') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'runcrew'),
					"description" => wp_kses_data( __("Size of the icons", 'runcrew') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(runcrew_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'runcrew'),
					"description" => wp_kses_data( __("Shape of the icons", 'runcrew') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(runcrew_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'runcrew'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'runcrew'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'runcrew') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'runcrew') => 'yes'),
					"type" => "checkbox"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('animation'),
				runcrew_get_vc_param('css'),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'runcrew'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'runcrew') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'runcrew'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'runcrew'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends RUNCREW_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>