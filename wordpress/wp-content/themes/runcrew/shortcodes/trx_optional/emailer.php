<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_emailer_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_emailer_theme_setup' );
	function runcrew_sc_emailer_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_emailer_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_emailer_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_emailer group=""]

if (!function_exists('runcrew_sc_emailer')) {	
	function runcrew_sc_emailer($atts, $content = null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"group" => "",
			"open" => "yes",
			"icon" => "",
			"align" => "",
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
			"height" => "",
			"sc_emailer_title" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width, $height);
		// Load core messages
		runcrew_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_emailer' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (runcrew_param_is_on($open) ? ' sc_emailer_opened' : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
					. ($css ? ' style="'.esc_attr($css).'"' : '') 
					. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
					. '>'
                . (!empty($sc_emailer_title) ? '<h4 class="sc_emailer_title ' . ($icon=='yes' ? 'icon-icon3' : '') . '">' . esc_attr($sc_emailer_title) . '</h4><div class="delimiter"></div>' : '')
				. '<form class="sc_emailer_form">'
				. '<input type="text" class="sc_emailer_input" name="email" value="" placeholder="'.esc_attr__('Enter your email', 'runcrew').'">'
				. '<a href="#" class="sc_emailer_button sc_button sc_button_round sc_button_style_filled sc_button_size_large" title="'.esc_attr__('Sign up', 'runcrew').'" data-group="'.esc_attr($group ? $group : esc_html__('E-mailer subscription', 'runcrew')).'">'. esc_html__('Sign up', 'runcrew') .'</a>'
				. '</form>'
			. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_emailer', $atts, $content);
	}
	runcrew_require_shortcode("trx_emailer", "runcrew_sc_emailer");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_emailer_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_emailer_reg_shortcodes');
	function runcrew_sc_emailer_reg_shortcodes() {
	
		runcrew_sc_map("trx_emailer", array(
			"title" => esc_html__("E-mail collector", 'runcrew'),
			"desc" => wp_kses_data( __("Collect the e-mail address into specified group", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sc_emailer_title" => array(
					"title" => esc_html__("Title", 'runcrew'),
					"desc" => wp_kses_data( __("The title of E-mail collector", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
                "icon" => array(
                    "title" => esc_html__("Icon", 'runcrew'),
                    "desc" => wp_kses_data( __("Show icon", 'runcrew') ),
//                    "dependency" => array(
//                        'sc_emailer_title' => array('is_empty')
//                    ),
                    "divider" => true,
                    "value" => "yes",
                    "type" => "switch",
                    "options" => runcrew_get_sc_param('yes_no')
                ),
                "group" => array(
					"title" => esc_html__("Group", 'runcrew'),
					"desc" => wp_kses_data( __("The name of group to collect e-mail address", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"open" => array(
					"title" => esc_html__("Open", 'runcrew'),
					"desc" => wp_kses_data( __("Initially open the input field on show object", 'runcrew') ),
					"divider" => true,
					"value" => "yes",
					"type" => "switch",
					"options" => runcrew_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Align object to left, center or right", 'runcrew') ),
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
if ( !function_exists( 'runcrew_sc_emailer_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_emailer_reg_shortcodes_vc');
	function runcrew_sc_emailer_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_emailer",
			"name" => esc_html__("E-mail collector", 'runcrew'),
			"description" => wp_kses_data( __("Collect e-mails into specified group", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_emailer',
			"class" => "trx_sc_single trx_sc_emailer",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "sc_emailer_title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("The title of E-mail collector", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
                array(
                    "param_name" => "icon",
                    "heading" => esc_html__("Icon", 'runcrew'),
                    "description" => wp_kses_data( __("Show icon", 'runcrew') ),
//                    'dependency' => array(
//                        'element' => 'sc_emailer_title',
//                        'is_empty' => true
//                    ),
                    "class" => "",
                    "value" => array(esc_html__('Show icon', 'runcrew') => 'yes'),
                    "type" => "checkbox"
                ),
                array(
					"param_name" => "group",
					"heading" => esc_html__("Group", 'runcrew'),
					"description" => wp_kses_data( __("The name of group to collect e-mail address", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Opened", 'runcrew'),
					"description" => wp_kses_data( __("Initially open the input field on show object", 'runcrew') ),
					"class" => "",
					"value" => array(esc_html__('Initially opened', 'runcrew') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'runcrew'),
					"description" => wp_kses_data( __("Align field to left, center or right", 'runcrew') ),
					"admin_label" => true,
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
		
		class WPBakeryShortCode_Trx_Emailer extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>