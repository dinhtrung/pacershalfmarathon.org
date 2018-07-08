<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_content_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_content_theme_setup' );
	function runcrew_sc_content_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_content_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_content_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('runcrew_sc_content')) {	
	function runcrew_sc_content($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, '', $bottom);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !runcrew_param_is_off($scheme) && !runcrew_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	runcrew_require_shortcode('trx_content', 'runcrew_sc_content');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_content_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_content_reg_shortcodes');
	function runcrew_sc_content_reg_shortcodes() {
	
		runcrew_sc_map("trx_content", array(
			"title" => esc_html__("Content block", 'runcrew'),
			"desc" => wp_kses_data( __("Container for main content block with desired class and style (use it only on fullscreen pages)", 'runcrew') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"options" => runcrew_get_sc_param('schemes')
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'runcrew'),
					"desc" => wp_kses_data( __("Content for section container", 'runcrew') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => runcrew_get_sc_param('top'),
				"bottom" => runcrew_get_sc_param('bottom'),
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
if ( !function_exists( 'runcrew_sc_content_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_content_reg_shortcodes_vc');
	function runcrew_sc_content_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_content",
			"name" => esc_html__("Content block", 'runcrew'),
			"description" => wp_kses_data( __("Container for main content block (use it only on fullscreen pages)", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_content',
			"class" => "trx_sc_collection trx_sc_content",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'runcrew'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
					"group" => esc_html__('Colors and Images', 'runcrew'),
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Container content", 'runcrew'),
					"description" => wp_kses_data( __("Content for section container", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('animation'),
				runcrew_get_vc_param('css'),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom')
			)
		) );
		
		class WPBakeryShortCode_Trx_Content extends RUNCREW_VC_ShortCodeCollection {}
	}
}
?>