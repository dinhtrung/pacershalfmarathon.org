<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_search_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_search_theme_setup' );
	function runcrew_sc_search_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_search_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('runcrew_sc_search')) {	
	function runcrew_sc_search($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'runcrew'),
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
		if (empty($ajax)) $ajax = runcrew_get_theme_option('use_ajax_search');
		// Load core messages
		runcrew_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (runcrew_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search-light" title="' . ($state=='closed' ? esc_attr__('Open search', 'runcrew') : esc_attr__('Start search', 'runcrew')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !runcrew_param_is_off($scheme) && !runcrew_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	runcrew_require_shortcode('trx_search', 'runcrew_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_search_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_search_reg_shortcodes');
	function runcrew_sc_search_reg_shortcodes() {
	
		runcrew_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'runcrew'),
			"desc" => wp_kses_data( __("Show search form", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'runcrew'),
					"desc" => wp_kses_data( __("Select style to display search field", 'runcrew') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'runcrew'),
						"rounded" => esc_html__('Rounded', 'runcrew')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'runcrew'),
					"desc" => wp_kses_data( __("Select search field initial state", 'runcrew') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'runcrew'),
						"opened" => esc_html__('Opened', 'runcrew'),
						"closed" => esc_html__('Closed', 'runcrew')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'runcrew'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'runcrew') ),
					"value" => esc_html__("Search &hellip;", 'runcrew'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'runcrew'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'runcrew') ),
					"value" => "yes",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_search_reg_shortcodes_vc');
	function runcrew_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'runcrew'),
			"description" => wp_kses_data( __("Insert search form", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'runcrew'),
					"description" => wp_kses_data( __("Select style to display search field", 'runcrew') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'runcrew') => "regular",
						esc_html__('Flat', 'runcrew') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'runcrew'),
					"description" => wp_kses_data( __("Select search field initial state", 'runcrew') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'runcrew')  => "fixed",
						esc_html__('Opened', 'runcrew') => "opened",
						esc_html__('Closed', 'runcrew') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'runcrew'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'runcrew'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'runcrew') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'runcrew') => 'yes'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>