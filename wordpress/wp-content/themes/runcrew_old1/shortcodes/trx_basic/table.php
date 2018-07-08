<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_table_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_table_theme_setup' );
	function runcrew_sc_table_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_table_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_table_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_table id="unique_id" style="1"]
Table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/
[/trx_table]
*/

if (!function_exists('runcrew_sc_table')) {	
	function runcrew_sc_table($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "100%"
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width);
		$content = str_replace(
					array('<p><table', 'table></p>', '><br />'),
					array('<table', 'table>', '>'),
					html_entity_decode($content, ENT_COMPAT, 'UTF-8'));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_table' 
					. (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				.'>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_table', $atts, $content);
	}
	runcrew_require_shortcode('trx_table', 'runcrew_sc_table');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_table_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_table_reg_shortcodes');
	function runcrew_sc_table_reg_shortcodes() {
	
		runcrew_sc_map("trx_table", array(
			"title" => esc_html__("Table", 'runcrew'),
			"desc" => wp_kses_data( __("Insert a table into post (page). ", 'runcrew') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Content alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Select alignment for each table cell", 'runcrew') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('align')
				),
				"_content_" => array(
					"title" => esc_html__("Table content", 'runcrew'),
					"desc" => wp_kses_data( __("Content, created with any table-generator", 'runcrew') ),
					"divider" => true,
					"rows" => 8,
					"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
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
if ( !function_exists( 'runcrew_sc_table_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_table_reg_shortcodes_vc');
	function runcrew_sc_table_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_table",
			"name" => esc_html__("Table", 'runcrew'),
			"description" => wp_kses_data( __("Insert a table", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_table',
			"class" => "trx_sc_container trx_sc_table",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Cells content alignment", 'runcrew'),
					"description" => wp_kses_data( __("Select alignment for each table cell", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Table content", 'runcrew'),
					"description" => wp_kses_data( __("Content, created with any table-generator", 'runcrew') ),
					"class" => "",
					"value" => esc_html__("Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/", 'runcrew'),
					"type" => "textarea_html"
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
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Table extends RUNCREW_VC_ShortCodeContainer {}
	}
}
?>