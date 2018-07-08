<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_anchor_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_anchor_theme_setup' );
	function runcrew_sc_anchor_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_anchor_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('runcrew_sc_anchor')) {	
	function runcrew_sc_anchor($atts, $content = null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(runcrew_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (runcrew_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	runcrew_require_shortcode("trx_anchor", "runcrew_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_anchor_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_anchor_reg_shortcodes');
	function runcrew_sc_anchor_reg_shortcodes() {
	
		runcrew_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'runcrew'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'runcrew'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'runcrew') ),
					"value" => "",
					"type" => "icons",
					"options" => runcrew_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'runcrew'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'runcrew'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'runcrew'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'runcrew'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'runcrew') ),
					"value" => "no",
					"type" => "switch",
					"options" => runcrew_get_sc_param('yes_no')
				),
				"id" => runcrew_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_anchor_reg_shortcodes_vc');
	function runcrew_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'runcrew'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'runcrew'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'runcrew') ),
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'runcrew'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'runcrew'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'runcrew'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'runcrew'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'runcrew') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				runcrew_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>