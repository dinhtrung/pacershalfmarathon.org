<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_countdown_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_countdown_theme_setup' );
	function runcrew_sc_countdown_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_countdown_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_countdown_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_countdown date="" time=""]

if (!function_exists('runcrew_sc_countdown')) {	
	function runcrew_sc_countdown($atts, $content = null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"date" => "",
			"time" => "",
			"style" => "1",
			"align" => "center",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if (empty($id)) $id = "sc_countdown_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width, $height);
		if (empty($interval)) $interval = 1;
		runcrew_enqueue_script( 'runcrew-jquery-plugin-script', runcrew_get_file_url('js/countdown/jquery.plugin.js'), array('jquery'), null, true );	
		runcrew_enqueue_script( 'runcrew-countdown-script', runcrew_get_file_url('js/countdown/jquery.countdown.js'), array('jquery'), null, true );	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_countdown sc_countdown_style_' . esc_attr(max(1, min(2, $style))) . (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') . (!empty($class) ? ' '.esc_attr($class) : '') .'"'
			. ($css ? ' style="'.esc_attr($css).'"' : '')
			. ' data-date="'.esc_attr(empty($date) ? date('Y-m-d') : $date).'"'
			. ' data-time="'.esc_attr(empty($time) ? '00:00:00' : $time).'"'
			. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
			. '>'
				. ($align=='center' ? '<div class="sc_countdown_inner">' : '')
				. '<div class="sc_countdown_item sc_countdown_days">'
					. '<span class="sc_countdown_digits"><span></span><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Days', 'runcrew').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_hours">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Hours', 'runcrew').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_minutes">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Minutes', 'runcrew').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_seconds">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Seconds', 'runcrew').'</span>'
				. '</div>'
				. '<div class="sc_countdown_placeholder hide"></div>'
				. ($align=='center' ? '</div>' : '')
			. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_countdown', $atts, $content);
	}
	runcrew_require_shortcode("trx_countdown", "runcrew_sc_countdown");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_countdown_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_countdown_reg_shortcodes');
	function runcrew_sc_countdown_reg_shortcodes() {
	
		runcrew_sc_map("trx_countdown", array(
			"title" => esc_html__("Countdown", 'runcrew'),
			"desc" => wp_kses_data( __("Insert countdown object", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"date" => array(
					"title" => esc_html__("Date", 'runcrew'),
					"desc" => wp_kses_data( __("Upcoming date (format: yyyy-mm-dd)", 'runcrew') ),
					"value" => "",
					"format" => "yy-mm-dd",
					"type" => "date"
				),
				"time" => array(
					"title" => esc_html__("Time", 'runcrew'),
					"desc" => wp_kses_data( __("Upcoming time (format: HH:mm:ss)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", 'runcrew'),
					"desc" => wp_kses_data( __("Countdown style", 'runcrew') ),
					"value" => "1",
					"type" => "checklist",
					"options" => runcrew_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Align counter to left, center or right", 'runcrew') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('align')
				), 
				"width" => runcrew_shortcodes_width(),
				"height" => runcrew_shortcodes_height(),
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
if ( !function_exists( 'runcrew_sc_countdown_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_countdown_reg_shortcodes_vc');
	function runcrew_sc_countdown_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_countdown",
			"name" => esc_html__("Countdown", 'runcrew'),
			"description" => wp_kses_data( __("Insert countdown object", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_countdown',
			"class" => "trx_sc_single trx_sc_countdown",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "date",
					"heading" => esc_html__("Date", 'runcrew'),
					"description" => wp_kses_data( __("Upcoming date (format: yyyy-mm-dd)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "time",
					"heading" => esc_html__("Time", 'runcrew'),
					"description" => wp_kses_data( __("Upcoming time (format: HH:mm:ss)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'runcrew'),
					"description" => wp_kses_data( __("Countdown style", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
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
				runcrew_vc_width(),
				runcrew_vc_height(),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Countdown extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>