<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_br_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_br_theme_setup' );
	function runcrew_sc_br_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_br_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('runcrew_sc_br')) {	
	function runcrew_sc_br($atts, $content = null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	runcrew_require_shortcode("trx_br", "runcrew_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_br_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_br_reg_shortcodes');
	function runcrew_sc_br_reg_shortcodes() {
	
		runcrew_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'runcrew'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'runcrew'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'runcrew'),
						'left' => esc_html__('Left', 'runcrew'),
						'right' => esc_html__('Right', 'runcrew'),
						'both' => esc_html__('Both', 'runcrew')
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_br_reg_shortcodes_vc');
	function runcrew_sc_br_reg_shortcodes_vc() {
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", 'runcrew'),
			"description" => wp_kses_data( __("Line break or Clear Floating", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", 'runcrew'),
					"description" => wp_kses_data( __("Select clear side (if need)", 'runcrew') ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'runcrew') => 'none',
						esc_html__('Left', 'runcrew') => 'left',
						esc_html__('Right', 'runcrew') => 'right',
						esc_html__('Both', 'runcrew') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends RUNCREW_VC_ShortCodeSingle {}
*/
	}
}
?>