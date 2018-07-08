<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_chat_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_chat_theme_setup' );
	function runcrew_sc_chat_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_chat_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_chat_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
...
*/

if (!function_exists('runcrew_sc_chat')) {	
	function runcrew_sc_chat($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"photo" => "",
			"title" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width, $height);
		$title = $title=='' ? $link : $title;
		if (!empty($photo)) {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = runcrew_get_resized_image_tag($photo, 75, 75);
		}
		$content = do_shortcode($content);
		if (runcrew_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_chat' . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. ($css ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
					. '<div class="sc_chat_inner">'
                        . ($photo ? '<div class="sc_chat_avatar">'.($photo).'</div>' : '')
                        . '<div class="sc_chat_field">'
                            . ($title == '' ? '' : ('<div class="sc_chat_title">' . ($link!='' ? '<a href="'.esc_url($link).'">' : '') . ($title) . ($link!='' ? '</a>' : '') . '</div>'))
                            . '<div class="sc_chat_content">'.($content).'</div>'
	    				. '</div>'
					. '</div>'
				. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_chat', $atts, $content);
	}
	runcrew_require_shortcode('trx_chat', 'runcrew_sc_chat');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_chat_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_chat_reg_shortcodes');
	function runcrew_sc_chat_reg_shortcodes() {
	
		runcrew_sc_map("trx_chat", array(
			"title" => esc_html__("Chat", 'runcrew'),
			"desc" => wp_kses_data( __("Chat message", 'runcrew') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Item title", 'runcrew'),
					"desc" => wp_kses_data( __("Chat item title", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"photo" => array(
					"title" => esc_html__("Item photo", 'runcrew'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the item photo (avatar)", 'runcrew') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"link" => array(
					"title" => esc_html__("Item link", 'runcrew'),
					"desc" => wp_kses_data( __("Chat item link", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Chat item content", 'runcrew'),
					"desc" => wp_kses_data( __("Current chat item content", 'runcrew') ),
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
if ( !function_exists( 'runcrew_sc_chat_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_chat_reg_shortcodes_vc');
	function runcrew_sc_chat_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_chat",
			"name" => esc_html__("Chat", 'runcrew'),
			"description" => wp_kses_data( __("Chat message", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_chat',
			"class" => "trx_sc_container trx_sc_chat",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Item title", 'runcrew'),
					"description" => wp_kses_data( __("Title for current chat item", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "photo",
					"heading" => esc_html__("Item photo", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the item photo (avatar)", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'runcrew'),
					"description" => wp_kses_data( __("URL for the link on chat title click", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Chat item content", 'runcrew'),
					"description" => wp_kses_data( __("Current chat item content", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
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
		
		class WPBakeryShortCode_Trx_Chat extends RUNCREW_VC_ShortCodeContainer {}
	}
}
?>