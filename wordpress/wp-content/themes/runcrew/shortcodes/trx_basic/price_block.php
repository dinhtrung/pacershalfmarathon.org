<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_price_block_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_price_block_theme_setup' );
	function runcrew_sc_price_block_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_price_block_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('runcrew_sc_price_block')) {
    function runcrew_sc_price_block($atts, $content=null){
        if (runcrew_in_shortcode_blogger()) return '';
        extract(runcrew_html_decode(shortcode_atts(array(
            // Individual params
            "style" => 1,
            "title" => "",
            "link" => "",
            "link_text" => "",
            "icon" => "",
            "money" => "",
            "currency" => "$",
            "period" => "",
            "align" => "",
            "scheme" => "",
            // Common params
            "id" => "",
            "class" => "",
            "animation" => "",
            "css" => "",
            "width" => "",
            "height" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $output = '';
        $class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
        $css .= runcrew_get_css_dimensions_from_values($width, $height);
        if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
        $content = do_shortcode(runcrew_sc_clear_around($content));
        $output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
            . (!empty($class) ? ' '.esc_attr($class) : '')
            . ($scheme && !runcrew_param_is_off($scheme) && !runcrew_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
            . ($align && $align!='none' ? ' align'.esc_attr($align) : '')
            . '"'
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
            . '>'
            . (!empty($title) ? '<div class="sc_price_block_title"><h3>'.($title).'</h3></div>' : '')
            . '<div class="sc_price_block_inner">'
            . '<div class="sc_price_block_money">'
            . (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
            . ($money)
            . '</div>'
            . (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
            . (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
            . '</div>'
            . '</div>';
        return apply_filters('runcrew_shortcode_output', $output, 'trx_price_block', $atts, $content);
    }
    runcrew_require_shortcode('trx_price_block', 'runcrew_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_price_block_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_price_block_reg_shortcodes');
	function runcrew_sc_price_block_reg_shortcodes() {
	
		runcrew_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'runcrew'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'runcrew') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'runcrew'),
					"desc" => wp_kses_data( __("Block title", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'runcrew'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'runcrew'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'runcrew'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'runcrew') ),
					"value" => "",
					"type" => "icons",
					"options" => runcrew_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'runcrew'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'runcrew') ),
					"divider" => true,
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
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"options" => runcrew_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'runcrew') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'runcrew'),
					"desc" => wp_kses_data( __("Description for this price block", 'runcrew') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'runcrew_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_price_block_reg_shortcodes_vc');
	function runcrew_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'runcrew'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("Block title", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'runcrew'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'runcrew'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'runcrew'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'runcrew') ),
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'runcrew'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'runcrew') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'runcrew'),
					"description" => wp_kses_data( __("Currency character", 'runcrew') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'runcrew'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'runcrew'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'runcrew') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'runcrew'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
					"group" => esc_html__('Colors and Images', 'runcrew'),
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('schemes')),
					"type" => "dropdown"
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
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'runcrew'),
					"description" => wp_kses_data( __("Description for this price block", 'runcrew') ),
					"class" => "",
					"value" => "",
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>