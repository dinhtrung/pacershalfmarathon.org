<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_audio_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_audio_theme_setup' );
	function runcrew_sc_audio_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_audio_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_audio_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_audio url="http://trex2.themerex.dnw/wp-content/uploads/2014/12/Dream-Music-Relax.mp3" image="http://trex2.themerex.dnw/wp-content/uploads/2014/10/post_audio.jpg" title="Insert Audio Title Here" author="Lily Hunter" controls="show" autoplay="off"]
*/

if (!function_exists('runcrew_sc_audio')) {	
	function runcrew_sc_audio($atts, $content = null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"author" => "",
			"image" => "",
			"mp3" => '',
			"wav" => '',
			"src" => '',
			"url" => '',
			"align" => '',
			"controls" => "",
			"autoplay" => "",
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		if ($src=='') {
			if ($url) $src = $url;
			else if ($mp3) $src = $mp3;
			else if ($wav) $src = $wav;
		}
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$data = ($title != ''  ? ' data-title="'.esc_attr($title).'"'   : '')
				. ($author != '' ? ' data-author="'.esc_attr($author).'"' : '')
				. ($image != ''  ? ' data-image="'.esc_url($image).'"'   : '')
				. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
				. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '');
		$audio = '<audio'
			. ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_audio' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. ' src="'.esc_url($src).'"'
			. (runcrew_param_is_on($controls) ? ' controls="controls"' : '')
			. (runcrew_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '')
			. ' width="'.esc_attr($width).'" height="'.esc_attr($height).'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($data)
			. '></audio>';
		if ( runcrew_get_custom_option('substitute_audio')=='no') {
			if (runcrew_param_is_on($frame)) {
				$audio = runcrew_get_audio_frame($audio, $image, $s);
			}
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$audio = runcrew_substitute_audio($audio, false);
			}
		}
		if (runcrew_get_theme_option('use_mediaelement')=='yes')
			runcrew_enqueue_script('wp-mediaelement');
		return apply_filters('runcrew_shortcode_output', $audio, 'trx_audio', $atts, $content);
	}
	runcrew_require_shortcode("trx_audio", "runcrew_sc_audio");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_audio_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_audio_reg_shortcodes');
	function runcrew_sc_audio_reg_shortcodes() {
	
		runcrew_sc_map("trx_audio", array(
			"title" => esc_html__("Audio", 'runcrew'),
			"desc" => wp_kses_data( __("Insert audio player", 'runcrew') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for audio file", 'runcrew'),
					"desc" => wp_kses_data( __("URL for audio file", 'runcrew') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose audio', 'runcrew'),
						'action' => 'media_upload',
						'type' => 'audio',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose audio file', 'runcrew'),
							'update' => esc_html__('Select audio file', 'runcrew')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'runcrew'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'runcrew') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"title" => array(
					"title" => esc_html__("Title", 'runcrew'),
					"desc" => wp_kses_data( __("Title of the audio file", 'runcrew') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"author" => array(
					"title" => esc_html__("Author", 'runcrew'),
					"desc" => wp_kses_data( __("Author of the audio file", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Show controls", 'runcrew'),
					"desc" => wp_kses_data( __("Show controls in audio player", 'runcrew') ),
					"divider" => true,
					"size" => "medium",
					"value" => "show",
					"type" => "switch",
					"options" => runcrew_get_sc_param('show_hide')
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay audio", 'runcrew'),
					"desc" => wp_kses_data( __("Autoplay audio on page load", 'runcrew') ),
					"value" => "off",
					"type" => "switch",
					"options" => runcrew_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'runcrew'),
					"desc" => wp_kses_data( __("Select block alignment", 'runcrew') ),
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
if ( !function_exists( 'runcrew_sc_audio_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_audio_reg_shortcodes_vc');
	function runcrew_sc_audio_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_audio",
			"name" => esc_html__("Audio", 'runcrew'),
			"description" => wp_kses_data( __("Insert audio player", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_audio',
			"class" => "trx_sc_single trx_sc_audio",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for audio file", 'runcrew'),
					"description" => wp_kses_data( __("Put here URL for audio file", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", 'runcrew'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("Title of the audio file", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "author",
					"heading" => esc_html__("Author", 'runcrew'),
					"description" => wp_kses_data( __("Author of the audio file", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'runcrew'),
					"description" => wp_kses_data( __("Show/hide controls", 'runcrew') ),
					"class" => "",
					"value" => array("Hide controls" => "hide" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay", 'runcrew'),
					"description" => wp_kses_data( __("Autoplay audio on page load", 'runcrew') ),
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'runcrew'),
					"description" => wp_kses_data( __("Select block alignment", 'runcrew') ),
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
			),
		) );
		
		class WPBakeryShortCode_Trx_Audio extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>