<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_highlight_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_highlight_theme_setup' );
	function runcrew_sc_highlight_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_highlight_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('runcrew_sc_highlight')) {	
	function runcrew_sc_highlight($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
        if($type == 3) {
            $tag = 'div';
        }else{
            $tag = 'span';
        };
        $css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(runcrew_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<'. $tag . ' ' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</'. $tag .'>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	runcrew_require_shortcode('trx_highlight', 'runcrew_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_highlight_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_highlight_reg_shortcodes');
	function runcrew_sc_highlight_reg_shortcodes() {
	
		runcrew_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'runcrew'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'runcrew') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'runcrew'),
					"desc" => wp_kses_data( __("Highlight type", 'runcrew') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'runcrew'),
						1 => esc_html__('Type 1', 'runcrew'),
						2 => esc_html__('Type 2', 'runcrew'),
						3 => esc_html__('Type 3 (Block)', 'runcrew')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'runcrew'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'runcrew') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'runcrew'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'runcrew') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'runcrew'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'runcrew'),
					"desc" => wp_kses_data( __("Content for highlight", 'runcrew') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => runcrew_get_sc_param('id'),
				"class" => runcrew_get_sc_param('class'),
				"css" => runcrew_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_highlight_reg_shortcodes_vc');
	function runcrew_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'runcrew'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'runcrew'),
					"description" => wp_kses_data( __("Highlight type", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'runcrew') => 0,
							esc_html__('Type 1', 'runcrew') => 1,
							esc_html__('Type 2', 'runcrew') => 2,
							esc_html__('Type 3 (Block)', 'runcrew') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'runcrew'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'runcrew'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'runcrew'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'runcrew'),
					"description" => wp_kses_data( __("Content for highlight", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>