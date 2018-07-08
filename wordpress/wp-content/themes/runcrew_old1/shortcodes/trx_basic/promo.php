<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_promo_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_promo_theme_setup' );
	function runcrew_sc_promo_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_promo_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_promo_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('runcrew_sc_promo')) {	
	function runcrew_sc_promo($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "large",
			"align" => "none",
			"image" => "",
			"image_position" => "left",
			"image_width" => "50%",
			"text_margins" => '',
			"text_align" => "left",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'runcrew'),
			"link" => '',
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
			"right" => "",
            // Aditional
			"content_align" => "",
		), $atts)));
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if ($image == '') {
			$image_width = '0%';
			$text_margins = '';
		}
		
		$width  = runcrew_prepare_css_value($width);
		$height = runcrew_prepare_css_value($height);
		
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width, $height);
		
		$css_image = (!empty($image) ? 'background-image:url(' . esc_url($image) . ');' : '')
				     . (!empty($image_width) ? 'width:'.trim($image_width).';' : '')
				     . (!empty($image_position) ? $image_position.': 0;' : '');
	
		$text_width = runcrew_strpos($image_width, '%')!==false
						? (100 - (int) str_replace('%', '', $image_width)).'%'
						: 'calc(100%-'.trim($image_width).')';
		$css_text = 'width: '.esc_attr($text_width).'; float: '.($image_position=='left' ? 'right' : 'left').';'.(!empty($text_margins) ? ' margin:'.esc_attr($text_margins).';' : '');
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_promo' 
						. ' '.($image_position)
						. ($class ? ' ' . esc_attr($class) : '')
						. ($scheme && !runcrew_param_is_off($scheme) && !runcrew_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						. ($size ? ' sc_promo_size_'.esc_attr($size) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. (empty($image) ? ' no_image' : '')
						. '"'
					. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
					. ($css ? 'style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_promo_inner">'
						. '<div class="sc_promo_image" style="'.esc_attr($css_image).'"></div>'
						. '<div class="sc_promo_block sc_align_'.esc_attr($text_align).'" style="'.esc_attr($css_text).'">'
							. '<div class="sc_promo_block_inner ' . (runcrew_param_is_on($content_align) ? ' content_align' : '') . '">'
									. (!empty($subtitle) ? '<h6 class="sc_promo_subtitle sc_item_subtitle">' . trim(runcrew_strmacros($subtitle)) . '</h6>' : '')
									. (!empty($title) ? '<h2 class="sc_promo_title sc_item_title">' . trim(runcrew_strmacros($title)) . '</h2>' : '')
									. (!empty($description) ? '<div class="sc_promo_descr sc_item_descr">' . trim(runcrew_strmacros($description)) . '</div>' : '')
									. (!empty($content) ? '<div class="sc_promo_content">'.do_shortcode($content).'</div>' : '')
									. (!empty($link) ? '<div class="sc_promo_button sc_item_button">'.runcrew_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
							. '</div>'
						. '</div>'
					. '</div>'
				. '</div>';
	
	
	
		return apply_filters('runcrew_shortcode_output', $output, 'trx_promo', $atts, $content);
	}
	runcrew_require_shortcode('trx_promo', 'runcrew_sc_promo');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_promo_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_promo_reg_shortcodes');
	function runcrew_sc_promo_reg_shortcodes() {
	
		runcrew_sc_map("trx_promo", array(
			"title" => esc_html__("Promo", 'runcrew'),
			"desc" => wp_kses_data( __("Insert promo diagramm in your page (post)", 'runcrew') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment of the promo block", 'runcrew'),
					"desc" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('float')
				), 
				"size" => array(
					"title" => esc_html__("Size of the promo block", 'runcrew'),
					"desc" => wp_kses_data( __("Size of the promo block: large - one in the row, small - insize two or greater columns", 'runcrew') ),
					"value" => "large",
					"type" => "switch",
					"options" => array(
						'small' => esc_html__('Small', 'runcrew'),
						'large' => esc_html__('Large', 'runcrew')
					)
				), 
				"image" => array(
					"title" => esc_html__("Image URL", 'runcrew'),
					"desc" => wp_kses_data( __("Select the promo image from the library for this section", 'runcrew') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_position" => array(
					"title" => esc_html__("Image position", 'runcrew'),
					"desc" => wp_kses_data( __("Place the image to the left or to the right from the text block", 'runcrew') ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('hpos')
				),
				"image_width" => array(
					"title" => esc_html__("Image width", 'runcrew'),
					"desc" => wp_kses_data( __("Width (in pixels or percents) of the block with image", 'runcrew') ),
					"value" => "50%",
					"type" => "text"
				),
				"text_margins" => array(
					"title" => esc_html__("Text margins", 'runcrew'),
					"desc" => wp_kses_data( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
                "content_align" => array(
                    "title" => esc_html__("Text align", 'runcrew'),
                    "desc" => wp_kses_data( __("Align the text to the width of the content", 'runcrew') ),
                    "divider" => true,
                    "value" => "no",
                    "type" => "switch",
                    "options" => runcrew_get_sc_param('yes_no')
                ),
				"text_align" => array(
					"title" => esc_html__("Text alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Align the text inside the block", 'runcrew') ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('align')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __("Select color scheme for the section with text", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"options" => runcrew_get_sc_param('schemes')
				),
				"title" => array(
					"title" => esc_html__("Title", 'runcrew'),
					"desc" => wp_kses_data( __("Title for the block", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'runcrew'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'runcrew'),
					"desc" => wp_kses_data( __("Short description for the block", 'runcrew') ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'runcrew'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'runcrew'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'runcrew') ),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'runcrew_sc_promo_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_promo_reg_shortcodes_vc');
	function runcrew_sc_promo_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_promo",
			"name" => esc_html__("Promo", 'runcrew'),
			"description" => wp_kses_data( __("Insert promo block", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_promo',
			"class" => "trx_sc_collection trx_sc_promo",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the promo block", 'runcrew'),
					"description" => wp_kses_data( __("Align whole promo block to left or right side of the page or parent container", 'runcrew') ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip(runcrew_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Size of the promo block", 'runcrew'),
					"description" => wp_kses_data( __("Size of the promo block: large - one in the row, small - insize two or greater columns", 'runcrew') ),
					"class" => "",
					"value" => array(esc_html__('Use small block', 'runcrew') => 'small'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'runcrew'),
					"description" => wp_kses_data( __("Select the promo image from the library for this section", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_position",
					"heading" => esc_html__("Image position", 'runcrew'),
					"description" => wp_kses_data( __("Place the image to the left or to the right from the text block", 'runcrew') ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip(runcrew_get_sc_param('hpos')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image_width",
					"heading" => esc_html__("Image width", 'runcrew'),
					"description" => wp_kses_data( __("Width (in pixels or percents) of the block with image", 'runcrew') ),
					"value" => '',
					"std" => "50%",
					"type" => "textfield"
				),
				array(
					"param_name" => "text_margins",
					"heading" => esc_html__("Text margins", 'runcrew'),
					"description" => wp_kses_data( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'runcrew') ),
					"value" => '',
					"type" => "textfield"
				),
                array(
                    "param_name" => "content_align",
                    "heading" => esc_html__("Text align", 'runcrew'),
                    "description" => wp_kses_data( __("Align the text to the width of the content", 'runcrew') ),
                    "class" => "",
                    "value" => array(esc_html__('Align', 'runcrew') => 'yes'),
                    "type" => "checkbox"
                ),
				array(
					"param_name" => "text_align",
					"heading" => esc_html__("Text alignment", 'runcrew'),
					"description" => wp_kses_data( __("Align text to the left or to the right side inside the block", 'runcrew') ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip(runcrew_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'runcrew'),
					"description" => wp_kses_data( __("Select color scheme for the section with text", 'runcrew') ),
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("Title for the block", 'runcrew') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'runcrew'),
					"description" => wp_kses_data( __("Subtitle for the block", 'runcrew') ),
					"group" => esc_html__('Captions', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'runcrew'),
					"description" => wp_kses_data( __("Description for the block", 'runcrew') ),
					"group" => esc_html__('Captions', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'runcrew'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'runcrew') ),
					"group" => esc_html__('Captions', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'runcrew'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'runcrew') ),
					"group" => esc_html__('Captions', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
		
		class WPBakeryShortCode_Trx_Promo extends RUNCREW_VC_ShortCodeCollection {}
	}
}
?>