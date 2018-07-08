<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_price_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_price_theme_setup' );
	function runcrew_sc_price_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_price_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_price_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('runcrew_sc_price')) {
    function runcrew_sc_price($atts, $content=null){
        if (runcrew_in_shortcode_blogger()) return '';
        extract(runcrew_html_decode(shortcode_atts(array(
            // Individual params
            "money" => "",
            "currency" => "$",
            "period" => "",
            "align" => "",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $output = '';
        if (!empty($money)) {
            $class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
            $m = explode('.', str_replace(',', '.', $money));
            $output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
                . ' class="sc_price'
                . (!empty($class) ? ' '.esc_attr($class) : '')
                . ($align && $align!='none' ? ' align'.esc_attr($align) : '')
                . '"'
                . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
                . '>'
                . (!empty($period) ? '<span class="sc_price_period">'.($period).'</span>' : (!empty($m[1]) ? '<span class="sc_price_period_empty"></span>' : ''))
                . '<span class="sc_price_currency">'.($currency).'</span>'
                . '<span class="sc_price_money">'.($m[0]) . (!empty($m[1]) ? '<span class="sc_price_penny">'. (!empty($m[1]) && !empty($m[0]) ? '.' : '') .($m[1]).'</span>' : '') .'</span>'
//				. (!empty($m[1]) ? '<span class="sc_price_info">' : '')
//				. (!empty($m[1]) ? '</span>' : '')
                . '</div>';
        }
        return apply_filters('runcrew_shortcode_output', $output, 'trx_price', $atts, $content);
    }
    runcrew_require_shortcode('trx_price', 'runcrew_sc_price');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_price_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_price_reg_shortcodes');
	function runcrew_sc_price_reg_shortcodes() {
	
		runcrew_sc_map("trx_price", array(
			"title" => esc_html__("Price", 'runcrew'),
			"desc" => wp_kses_data( __("Insert price with decoration", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"money" => array(
					"title" => esc_html__("Money", 'runcrew'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'runcrew'),
					"desc" => wp_kses_data( __("Currency character", 'runcrew') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'runcrew'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('float')
				), 
				"top" => runcrew_get_sc_param('top'),
				"bottom" => runcrew_get_sc_param('bottom'),
				"left" => runcrew_get_sc_param('left'),
				"right" => runcrew_get_sc_param('right'),
				"id" => runcrew_get_sc_param('id'),
				"class" => runcrew_get_sc_param('class'),
				"css" => runcrew_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_price_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_price_reg_shortcodes_vc');
	function runcrew_sc_price_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price",
			"name" => esc_html__("Price", 'runcrew'),
			"description" => wp_kses_data( __("Insert price with decoration", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_price',
			"class" => "trx_sc_single trx_sc_price",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'runcrew'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'runcrew'),
					"description" => wp_kses_data( __("Currency character", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'runcrew'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'runcrew'),
					"description" => wp_kses_data( __("Align price to left or right side", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('float')),
					"type" => "dropdown"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('css'),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Price extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>