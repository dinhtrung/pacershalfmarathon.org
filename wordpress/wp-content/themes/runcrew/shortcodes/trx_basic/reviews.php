<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_reviews_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_reviews_theme_setup' );
	function runcrew_sc_reviews_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_reviews_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_reviews_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_reviews]
*/

if (!function_exists('runcrew_sc_reviews')) {	
	function runcrew_sc_reviews($atts, $content = null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "right",
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
		$output = runcrew_param_is_off(runcrew_get_custom_option('show_sidebar_main'))
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_reviews'
							. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class ? ' '.esc_attr($class) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
						. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
						. '>'
					. trim(runcrew_get_reviews_placeholder())
					. '</div>'
			: '';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_reviews', $atts, $content);
	}
	runcrew_require_shortcode("trx_reviews", "runcrew_sc_reviews");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_reviews_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_reviews_reg_shortcodes');
	function runcrew_sc_reviews_reg_shortcodes() {
	
		runcrew_sc_map("trx_reviews", array(
			"title" => esc_html__("Reviews", 'runcrew'),
			"desc" => wp_kses_data( __("Insert reviews block in the single post", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Align counter to left, center or right", 'runcrew') ),
					"divider" => true,
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
if ( !function_exists( 'runcrew_sc_reviews_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_reviews_reg_shortcodes_vc');
	function runcrew_sc_reviews_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_reviews",
			"name" => esc_html__("Reviews", 'runcrew'),
			"description" => wp_kses_data( __("Insert reviews block in the single post", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_reviews',
			"class" => "trx_sc_single trx_sc_reviews",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'runcrew'),
					"description" => wp_kses_data( __("Align counter to left, center or right", 'runcrew') ),
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
		
		class WPBakeryShortCode_Trx_Reviews extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>