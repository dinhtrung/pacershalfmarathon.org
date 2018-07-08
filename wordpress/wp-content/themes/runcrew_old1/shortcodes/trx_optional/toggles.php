<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_toggles_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_toggles_theme_setup' );
	function runcrew_sc_toggles_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_toggles_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_toggles_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('runcrew_sc_toggles')) {	
	function runcrew_sc_toggles($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
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
		runcrew_storage_set('sc_toggle_data', array(
			'counter' => 0,
            'show_counter' => runcrew_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || runcrew_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
            'icon_opened' => empty($icon_opened) || runcrew_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened
            )
        );
		runcrew_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_toggles'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (runcrew_param_is_on($counter) ? ' sc_show_counter' : '') 
					. '"'
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_toggles', $atts, $content);
	}
	runcrew_require_shortcode('trx_toggles', 'runcrew_sc_toggles');
}


if (!function_exists('runcrew_sc_toggles_item')) {	
	function runcrew_sc_toggles_item($atts, $content=null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"open" => "",
			"icon_closed" => "",
			"icon_opened" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		runcrew_storage_inc_array('sc_toggle_data', 'counter');
		if (empty($icon_closed) || runcrew_param_is_inherit($icon_closed)) $icon_closed = runcrew_storage_get_array('sc_toggles_data', 'icon_closed', '', "icon-plus");
		if (empty($icon_opened) || runcrew_param_is_inherit($icon_opened)) $icon_opened = runcrew_storage_get_array('sc_toggles_data', 'icon_opened', '', "icon-minus");
		$css .= runcrew_param_is_on($open) ? 'display:block;' : '';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_toggles_item'.(runcrew_param_is_on($open) ? ' sc_active' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (runcrew_storage_get_array('sc_toggle_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
					. (runcrew_storage_get_array('sc_toggle_data', 'counter') == 1 ? ' first' : '')
					. '">'
					. '<h5 class="sc_toggles_title'.(runcrew_param_is_on($open) ? ' ui-state-active' : '').'">'
					. (!runcrew_param_is_off($icon_closed) ? '<span class="sc_toggles_icon sc_toggles_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
					. (!runcrew_param_is_off($icon_opened) ? '<span class="sc_toggles_icon sc_toggles_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
					. (runcrew_storage_get_array('sc_toggle_data', 'show_counter') ? '<span class="sc_items_counter">'.(runcrew_storage_get_array('sc_toggle_data', 'counter')).'</span>' : '')
					. ($title) 
					. '</h5>'
					. '<div class="sc_toggles_content"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						.'>' 
						. do_shortcode($content) 
					. '</div>'
				. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_toggles_item', $atts, $content);
	}
	runcrew_require_shortcode('trx_toggles_item', 'runcrew_sc_toggles_item');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_toggles_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_toggles_reg_shortcodes');
	function runcrew_sc_toggles_reg_shortcodes() {
	
		runcrew_sc_map("trx_toggles", array(
			"title" => esc_html__("Toggles", 'runcrew'),
			"desc" => wp_kses_data( __("Toggles items", 'runcrew') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", 'runcrew'),
					"desc" => wp_kses_data( __("Display counter before each toggles title", 'runcrew') ),
					"value" => "off",
					"type" => "switch",
					"options" => runcrew_get_sc_param('on_off')
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'runcrew'),
					"desc" => wp_kses_data( __('Select icon for the closed toggles item from Fontello icons set',  'runcrew') ),
					"value" => "",
					"type" => "icons",
					"options" => runcrew_get_sc_param('icons')
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'runcrew'),
					"desc" => wp_kses_data( __('Select icon for the opened toggles item from Fontello icons set',  'runcrew') ),
					"value" => "",
					"type" => "icons",
					"options" => runcrew_get_sc_param('icons')
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
				"name" => "trx_toggles_item",
				"title" => esc_html__("Toggles item", 'runcrew'),
				"desc" => wp_kses_data( __("Toggles item", 'runcrew') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Toggles item title", 'runcrew'),
						"desc" => wp_kses_data( __("Title for current toggles item", 'runcrew') ),
						"value" => "",
						"type" => "text"
					),
					"open" => array(
						"title" => esc_html__("Open on show", 'runcrew'),
						"desc" => wp_kses_data( __("Open current toggles item on show", 'runcrew') ),
						"value" => "no",
						"type" => "switch",
						"options" => runcrew_get_sc_param('yes_no')
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'runcrew'),
						"desc" => wp_kses_data( __('Select icon for the closed toggles item from Fontello icons set',  'runcrew') ),
						"value" => "",
						"type" => "icons",
						"options" => runcrew_get_sc_param('icons')
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'runcrew'),
						"desc" => wp_kses_data( __('Select icon for the opened toggles item from Fontello icons set',  'runcrew') ),
						"value" => "",
						"type" => "icons",
						"options" => runcrew_get_sc_param('icons')
					),
					"_content_" => array(
						"title" => esc_html__("Toggles item content", 'runcrew'),
						"desc" => wp_kses_data( __("Current toggles item content", 'runcrew') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => runcrew_get_sc_param('id'),
					"class" => runcrew_get_sc_param('class'),
					"css" => runcrew_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_toggles_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_toggles_reg_shortcodes_vc');
	function runcrew_sc_toggles_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_toggles",
			"name" => esc_html__("Toggles", 'runcrew'),
			"description" => wp_kses_data( __("Toggles items", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_toggles',
			"class" => "trx_sc_collection trx_sc_toggles",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_toggles_item'),
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", 'runcrew'),
					"description" => wp_kses_data( __("Display counter before each toggles title", 'runcrew') ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'runcrew'),
					"description" => wp_kses_data( __("Select icon for the closed toggles item from Fontello icons set", 'runcrew') ),
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'runcrew'),
					"description" => wp_kses_data( __("Select icon for the opened toggles item from Fontello icons set", 'runcrew') ),
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'runcrew' ) . '"][/trx_toggles_item]
				[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'runcrew' ) . '"][/trx_toggles_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", 'runcrew').'">'.esc_html__("Add item", 'runcrew').'</button>
				</div>
			',
			'js_view' => 'VcTrxTogglesView'
		) );
		
		
		vc_map( array(
			"base" => "trx_toggles_item",
			"name" => esc_html__("Toggles item", 'runcrew'),
			"description" => wp_kses_data( __("Single toggles item", 'runcrew') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_toggles_item',
			"as_child" => array('only' => 'trx_toggles'),
			"as_parent" => array('except' => 'trx_toggles'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("Title for current toggles item", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Open on show", 'runcrew'),
					"description" => wp_kses_data( __("Open current toggle item on show", 'runcrew') ),
					"class" => "",
					"value" => array("Opened" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'runcrew'),
					"description" => wp_kses_data( __("Select icon for the closed toggles item from Fontello icons set", 'runcrew') ),
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'runcrew'),
					"description" => wp_kses_data( __("Select icon for the opened toggles item from Fontello icons set", 'runcrew') ),
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('css')
			),
			'js_view' => 'VcTrxTogglesTabView'
		) );
		class WPBakeryShortCode_Trx_Toggles extends RUNCREW_VC_ShortCodeToggles {}
		class WPBakeryShortCode_Trx_Toggles_Item extends RUNCREW_VC_ShortCodeTogglesItem {}
	}
}
?>