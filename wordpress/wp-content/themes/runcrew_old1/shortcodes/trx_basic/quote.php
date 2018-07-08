<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_quote_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_quote_theme_setup' );
	function runcrew_sc_quote_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_quote_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('runcrew_sc_quote')) {	
	function runcrew_sc_quote($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"cite" => "",
            "title" => "",
            "position" => "",
			"photo" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width);
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
        $attach =  wp_get_attachment_image ($photo, array(66, 66));
        if (runcrew_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"' 
			. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($photo == '' ? '' : ('<div class="sc_quote_author"><div class="sc_quote_photo">' . trim($attach) . '</div><div class="sc_quote_info">'))
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
				. ($position == '' ? '' : ('<p class="sc_quote_position">' . ($position) . '</p>'))
                . ($photo == '' ? '' : ('</div></div>'))
			.'</blockquote>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	runcrew_require_shortcode('trx_quote', 'runcrew_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_quote_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_quote_reg_shortcodes');
	function runcrew_sc_quote_reg_shortcodes() {
	
		runcrew_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", 'runcrew'),
			"desc" => wp_kses_data( __("Quote text", 'runcrew') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", 'runcrew'),
					"desc" => wp_kses_data( __("URL for quote cite", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title (author)", 'runcrew'),
					"desc" => wp_kses_data( __("Quote title (author name)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
                "position" => array(
                    "title" => esc_html__("Author's position",  'runcrew'),
                    "desc" => wp_kses_data( __("Position of the quote author", 'runcrew') ),
                    "std" => "",
                    "type" => "text"
                ),
                "photo" => array(
                    "title" => esc_html__("Author Photo", 'runcrew'),
                    "desc" => wp_kses_data( __("Select or upload photo of quote author or write URL of photo from other site", 'runcrew') ),
                    "value" => "",
                    "type" => "media"
                ),
				"_content_" => array(
					"title" => esc_html__("Quote content", 'runcrew'),
					"desc" => wp_kses_data( __("Quote content", 'runcrew') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => runcrew_shortcodes_width(),
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
if ( !function_exists( 'runcrew_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_quote_reg_shortcodes_vc');
	function runcrew_sc_quote_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", 'runcrew'),
			"description" => wp_kses_data( __("Quote text", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", 'runcrew'),
					"description" => wp_kses_data( __("URL for the quote cite link", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", 'runcrew'),
					"description" => wp_kses_data( __("Quote title (author name)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Author's position", 'runcrew'),
					"description" => wp_kses_data( __("Position of the quote author", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
                array(
                    "param_name" => "photo",
                    "heading" => esc_html__("Author Photo", 'runcrew'),
                    "description" => wp_kses_data( __("Select or upload photo of quote author or write URL of photo from other site", 'runcrew') ),
                    "class" => "",
                    "value" => "",
                    "type" => "attach_image"
                ),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", 'runcrew'),
					"description" => wp_kses_data( __("Quote content", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('animation'),
				runcrew_get_vc_param('css'),
				runcrew_vc_width(),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Quote extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>