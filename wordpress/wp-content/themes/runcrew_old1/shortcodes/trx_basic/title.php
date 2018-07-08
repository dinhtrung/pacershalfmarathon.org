<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_title_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_title_theme_setup' );
	function runcrew_sc_title_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_title_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('runcrew_sc_title')) {	
	function runcrew_sc_title($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !runcrew_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !runcrew_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(runcrew_strpos($image, 'http:')!==false ? $image : runcrew_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !runcrew_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	runcrew_require_shortcode('trx_title', 'runcrew_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_title_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_title_reg_shortcodes');
	function runcrew_sc_title_reg_shortcodes() {
	
		runcrew_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'runcrew'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'runcrew') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'runcrew'),
					"desc" => wp_kses_data( __("Title content", 'runcrew') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'runcrew'),
					"desc" => wp_kses_data( __("Title type (header level)", 'runcrew') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'runcrew'),
						'2' => esc_html__('Header 2', 'runcrew'),
						'3' => esc_html__('Header 3', 'runcrew'),
						'4' => esc_html__('Header 4', 'runcrew'),
						'5' => esc_html__('Header 5', 'runcrew'),
						'6' => esc_html__('Header 6', 'runcrew'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'runcrew'),
					"desc" => wp_kses_data( __("Title style", 'runcrew') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'runcrew'),
						'underline' => esc_html__('Underline', 'runcrew'),
						'divider' => esc_html__('Divider', 'runcrew'),
						'iconed' => esc_html__('With icon (image)', 'runcrew')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'runcrew'),
					"desc" => wp_kses_data( __("Title text alignment", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'runcrew'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'runcrew'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'runcrew') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'runcrew'),
						'100' => esc_html__('Thin (100)', 'runcrew'),
						'300' => esc_html__('Light (300)', 'runcrew'),
						'400' => esc_html__('Normal (400)', 'runcrew'),
						'600' => esc_html__('Semibold (600)', 'runcrew'),
						'700' => esc_html__('Bold (700)', 'runcrew'),
						'900' => esc_html__('Black (900)', 'runcrew')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'runcrew'),
					"desc" => wp_kses_data( __("Select color for the title", 'runcrew') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'runcrew'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'runcrew') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => runcrew_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'runcrew'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'runcrew') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => runcrew_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'runcrew'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'runcrew') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'runcrew'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'runcrew') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'runcrew'),
						'medium' => esc_html__('Medium', 'runcrew'),
						'large' => esc_html__('Large', 'runcrew')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'runcrew'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'runcrew') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'runcrew'),
						'left' => esc_html__('Left', 'runcrew')
					)
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
if ( !function_exists( 'runcrew_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_title_reg_shortcodes_vc');
	function runcrew_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'runcrew'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'runcrew'),
					"description" => wp_kses_data( __("Title content", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'runcrew'),
					"description" => wp_kses_data( __("Title type (header level)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'runcrew') => '1',
						esc_html__('Header 2', 'runcrew') => '2',
						esc_html__('Header 3', 'runcrew') => '3',
						esc_html__('Header 4', 'runcrew') => '4',
						esc_html__('Header 5', 'runcrew') => '5',
						esc_html__('Header 6', 'runcrew') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'runcrew'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'runcrew') => 'regular',
						esc_html__('Underline', 'runcrew') => 'underline',
						esc_html__('Divider', 'runcrew') => 'divider',
						esc_html__('With icon (image)', 'runcrew') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'runcrew'),
					"description" => wp_kses_data( __("Title text alignment", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'runcrew'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'runcrew'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'runcrew') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'runcrew') => 'inherit',
						esc_html__('Thin (100)', 'runcrew') => '100',
						esc_html__('Light (300)', 'runcrew') => '300',
						esc_html__('Normal (400)', 'runcrew') => '400',
						esc_html__('Semibold (600)', 'runcrew') => '600',
						esc_html__('Bold (700)', 'runcrew') => '700',
						esc_html__('Black (900)', 'runcrew') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'runcrew'),
					"description" => wp_kses_data( __("Select color for the title", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'runcrew'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'runcrew') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'runcrew'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'runcrew'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'runcrew') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'runcrew'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => runcrew_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'runcrew') ),
					"group" => esc_html__('Icon &amp; Image', 'runcrew'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'runcrew'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'runcrew') ),
					"group" => esc_html__('Icon &amp; Image', 'runcrew'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'runcrew') => 'small',
						esc_html__('Medium', 'runcrew') => 'medium',
						esc_html__('Large', 'runcrew') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'runcrew'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'runcrew') ),
					"group" => esc_html__('Icon &amp; Image', 'runcrew'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'runcrew') => 'top',
						esc_html__('Left', 'runcrew') => 'left'
					),
					"type" => "dropdown"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('animation'),
				runcrew_get_vc_param('css'),
				runcrew_get_vc_param('margin_top'),
				runcrew_get_vc_param('margin_bottom'),
				runcrew_get_vc_param('margin_left'),
				runcrew_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>