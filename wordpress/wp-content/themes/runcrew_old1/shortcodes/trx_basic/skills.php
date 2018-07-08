<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_skills_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_skills_theme_setup' );
	function runcrew_sc_skills_theme_setup() {
		add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_skills_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_skills_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_skills id="unique_id" type="bar|pie|arc|counter" dir="horizontal|vertical" layout="rows|columns" count="" max_value="100" align="left|right"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
[/trx_skills]
*/

if (!function_exists('runcrew_sc_skills')) {	
	function runcrew_sc_skills($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "100",
			"type" => "bar",
			"layout" => "",
			"dir" => "",
			"style" => "1",
			"columns" => "",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"arc_caption" => esc_html__("Skills", 'runcrew'),
			"pie_compact" => "on",
			"pie_cutout" => 0,
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
			"right" => ""
		), $atts)));
		runcrew_storage_set('sc_skills_data', array(
			'counter' => 0,
            'columns' => 0,
            'height'  => 0,
            'type'    => $type,
            'pie_compact' => runcrew_param_is_on($pie_compact) ? 'on' : 'off',
            'pie_cutout'  => max(0, min(99, $pie_cutout)),
            'color'   => $color,
            'bg_color'=> $bg_color,
            'border_color'=> $border_color,
            'legend'  => '',
            'data'    => ''
			)
		);
		runcrew_enqueue_diagram($type);
		if ($type!='arc') {
			if ($layout=='' || ($layout=='columns' && $columns<1)) $layout = 'rows';
			if ($layout=='columns') runcrew_storage_set_array('sc_skills_data', 'columns', $columns);
			if ($type=='bar') {
				if ($dir == '') $dir = 'horizontal';
				if ($dir == 'vertical' && $height < 1) $height = 300;
			}
		}
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
		if ($max_value < 1) $max_value = 100;
		if ($style) {
			$style = max(1, min(4, $style));
			runcrew_storage_set_array('sc_skills_data', 'style', $style);
		}
		runcrew_storage_set_array('sc_skills_data', 'max', $max_value);
		runcrew_storage_set_array('sc_skills_data', 'dir', $dir);
		runcrew_storage_set_array('sc_skills_data', 'height', runcrew_prepare_css_value($height));
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= runcrew_get_css_dimensions_from_values($width);
		if (!runcrew_storage_empty('sc_skills_data', 'height') && (runcrew_storage_get_array('sc_skills_data', 'type') == 'arc' || (runcrew_storage_get_array('sc_skills_data', 'type') == 'pie' && runcrew_param_is_on(runcrew_storage_get_array('sc_skills_data', 'pie_compact')))))
			$css .= 'height: '.runcrew_storage_get_array('sc_skills_data', 'height');
		$content = do_shortcode($content);
		$output = '<div id="'.esc_attr($id).'"' 
					. ' class="sc_skills sc_skills_' . esc_attr($type) 
						. ($type=='bar' ? ' sc_skills_'.esc_attr($dir) : '') 
						. ($type=='pie' ? ' sc_skills_compact_'.esc_attr(runcrew_storage_get_array('sc_skills_data', 'pie_compact')) : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
					. ' data-type="'.esc_attr($type).'"'
					. ' data-caption="'.esc_attr($arc_caption).'"'
					. ($type=='bar' ? ' data-dir="'.esc_attr($dir).'"' : '')
				. '>'
					. (!empty($subtitle) ? '<h6 class="sc_skills_subtitle sc_item_subtitle">' . esc_html($subtitle) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_skills_title sc_item_title">' . esc_html($title) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_skills_descr sc_item_descr">' . trim($description) . '</div>' : '')
					. ($layout == 'columns' ? '<div class="columns_wrap sc_skills_'.esc_attr($layout).' sc_skills_columns_'.esc_attr($columns).'">' : '')
					. ($type=='arc' 
						? ('<div class="sc_skills_legend">'.(runcrew_storage_get_array('sc_skills_data', 'legend')).'</div>'
							. '<div id="'.esc_attr($id).'_diagram" class="sc_skills_arc_canvas"></div>'
							. '<div class="sc_skills_data" style="display:none;">' . (runcrew_storage_get_array('sc_skills_data', 'data')) . '</div>'
						  )
						: '')
					. ($type=='pie' && runcrew_param_is_on(runcrew_storage_get_array('sc_skills_data', 'pie_compact'))
						? ('<div class="sc_skills_legend">'.(runcrew_storage_get_array('sc_skills_data', 'legend')).'</div>'
							. '<div id="'.esc_attr($id).'_pie" class="sc_skills_item">'
								. '<canvas id="'.esc_attr($id).'_pie" class="sc_skills_pie_canvas"></canvas>'
								. '<div class="sc_skills_data" style="display:none;">' . (runcrew_storage_get_array('sc_skills_data', 'data')) . '</div>'
							. '</div>'
						  )
						: '')
					. ($content)
					. ($layout == 'columns' ? '</div>' : '')
					. (!empty($link) ? '<div class="sc_skills_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div>';
		return apply_filters('runcrew_shortcode_output', $output, 'trx_skills', $atts, $content);
	}
	runcrew_require_shortcode('trx_skills', 'runcrew_sc_skills');
}


if (!function_exists('runcrew_sc_skills_item')) {	
	function runcrew_sc_skills_item($atts, $content=null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"value" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"style" => "",
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		runcrew_storage_inc_array('sc_skills_data', 'counter');
		$ed = runcrew_substr($value, -1)=='%' ? '%' : '';
		$value = str_replace('%', '', $value);
		if (runcrew_storage_get_array('sc_skills_data', 'max') < $value) runcrew_storage_set_array('sc_skills_data', 'max', $value);
		$percent = round($value / runcrew_storage_get_array('sc_skills_data', 'max') * 100);
		$start = 0;
		$stop = $value;
		$steps = 100;
		$step = max(1, round(runcrew_storage_get_array('sc_skills_data', 'max')/$steps));
		$speed = mt_rand(10,40);
		$animation = round(($stop - $start) / $step * $speed);
		$title_block = '<div class="sc_skills_info"><div class="sc_skills_label">' . ($title) . '</div></div>';
		$old_color = $color;
		if (empty($color)) $color = runcrew_storage_get_array('sc_skills_data', 'color');
		if (empty($color)) $color = runcrew_get_scheme_color('accent1', $color);
		if (empty($bg_color)) $bg_color = runcrew_storage_get_array('sc_skills_data', 'bg_color');
		if (empty($bg_color)) $bg_color = runcrew_get_scheme_color('bg_color', $bg_color);
		if (empty($border_color)) $border_color = runcrew_storage_get_array('sc_skills_data', 'border_color');
		if (empty($border_color)) $border_color = runcrew_get_scheme_color('bd_color', $border_color);;
		if (empty($style)) $style = runcrew_storage_get_array('sc_skills_data', 'style');
		$style = max(1, min(4, $style));
		$output = '';
		if (runcrew_storage_get_array('sc_skills_data', 'type') == 'arc' || (runcrew_storage_get_array('sc_skills_data', 'type') == 'pie' && runcrew_param_is_on(runcrew_storage_get_array('sc_skills_data', 'pie_compact')))) {
			if (runcrew_storage_get_array('sc_skills_data', 'type') == 'arc' && empty($old_color)) {
				$rgb = runcrew_hex2rgb($color);
				$color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.(1 - 0.1*(runcrew_storage_get_array('sc_skills_data', 'counter')-1)).')';
			}
			runcrew_storage_concat_array('sc_skills_data', 'legend', 
				'<div class="sc_skills_legend_item"><span class="sc_skills_legend_marker" style="background-color:'.esc_attr($color).'"></span><span class="sc_skills_legend_title">' . ($title) . '</span><span class="sc_skills_legend_value">' . ($value) . '</span></div>'
			);
			runcrew_storage_concat_array('sc_skills_data', 'data', 
				'<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="'.esc_attr(runcrew_storage_get_array('sc_skills_data', 'type')).'"'
					. (runcrew_storage_get_array('sc_skills_data', 'type')=='pie'
						? ( ' data-start="'.esc_attr($start).'"'
							. ' data-stop="'.esc_attr($stop).'"'
							. ' data-step="'.esc_attr($step).'"'
							. ' data-steps="'.esc_attr($steps).'"'
							. ' data-max="'.esc_attr(runcrew_storage_get_array('sc_skills_data', 'max')).'"'
							. ' data-speed="'.esc_attr($speed).'"'
							. ' data-duration="'.esc_attr($animation).'"'
							. ' data-color="'.esc_attr($color).'"'
							. ' data-bg_color="'.esc_attr($bg_color).'"'
							. ' data-border_color="'.esc_attr($border_color).'"'
							. ' data-cutout="'.esc_attr(runcrew_storage_get_array('sc_skills_data', 'pie_cutout')).'"'
							. ' data-easing="easeOutCirc"'
							. ' data-ed="'.esc_attr($ed).'"'
							)
						: '')
					. '><input type="hidden" class="text" value="'.esc_attr($title).'" /><input type="hidden" class="percent" value="'.esc_attr($percent).'" /><input type="hidden" class="color" value="'.esc_attr($color).'" /></div>'
			);
		} else {
			$output .= (runcrew_storage_get_array('sc_skills_data', 'columns') > 0 
							? '<div class="sc_skills_column column-1_'.esc_attr(runcrew_storage_get_array('sc_skills_data', 'columns')).'">' 
							: '')
					. (runcrew_storage_get_array('sc_skills_data', 'type')=='bar' && runcrew_storage_get_array('sc_skills_data', 'dir')=='horizontal' ? $title_block : '')
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.esc_attr($style) : '') 
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. (runcrew_storage_get_array('sc_skills_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
							. (runcrew_storage_get_array('sc_skills_data', 'counter') == 1 ? ' first' : '') 
							. '"'
						. (runcrew_storage_get_array('sc_skills_data', 'height') !='' || $css 
							? ' style="' 
								. (runcrew_storage_get_array('sc_skills_data', 'height') !='' 
										? 'height: '.esc_attr(runcrew_storage_get_array('sc_skills_data', 'height')).';' 
										: '') 
								. ($css) 
								. '"' 
							: '')
					. '>'
					. (!empty($icon) ? '<div class="sc_skills_icon '.esc_attr($icon).'"></div>' : '');
			if (in_array(runcrew_storage_get_array('sc_skills_data', 'type'), array('bar', 'counter'))) {
				$output .= '<div class="sc_skills_count"' . (runcrew_storage_get_array('sc_skills_data', 'type')=='bar' && $color ? ' style="background-color:' . esc_attr($color) . '; border-color:' . esc_attr($color) . '"' : '') . '>'
							. '<div class="sc_skills_total"'
								. ' data-start="'.esc_attr($start).'"'
								. ' data-stop="'.esc_attr($stop).'"'
								. ' data-step="'.esc_attr($step).'"'
								. ' data-max="'.esc_attr(runcrew_storage_get_array('sc_skills_data', 'max')).'"'
								. ' data-speed="'.esc_attr($speed).'"'
								. ' data-duration="'.esc_attr($animation).'"'
								. ' data-ed="'.esc_attr($ed).'">'
								. ($start) . ($ed)
							.'</div>'
						. '</div>';
			} else if (runcrew_storage_get_array('sc_skills_data', 'type')=='pie') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<canvas id="'.esc_attr($id).'"></canvas>'
					. '<div class="sc_skills_total"'
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr(runcrew_storage_get_array('sc_skills_data', 'max')).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr(runcrew_storage_get_array('sc_skills_data', 'pie_cutout')).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>';
			}
			$output .= 
					  (runcrew_storage_get_array('sc_skills_data', 'type')=='counter' ? $title_block : '')
					. '</div>'
					. (runcrew_storage_get_array('sc_skills_data', 'type')=='bar' && runcrew_storage_get_array('sc_skills_data', 'dir')=='vertical' || runcrew_storage_get_array('sc_skills_data', 'type') == 'pie' ? $title_block : '')
					. (runcrew_storage_get_array('sc_skills_data', 'columns') > 0 ? '</div>' : '');
		}
		return apply_filters('runcrew_shortcode_output', $output, 'trx_skills_item', $atts, $content);
	}
	runcrew_require_shortcode('trx_skills_item', 'runcrew_sc_skills_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_skills_reg_shortcodes' ) ) {
	//add_action('runcrew_action_shortcodes_list', 'runcrew_sc_skills_reg_shortcodes');
	function runcrew_sc_skills_reg_shortcodes() {
	
		runcrew_sc_map("trx_skills", array(
			"title" => esc_html__("Skills", 'runcrew'),
			"desc" => wp_kses_data( __("Insert skills diagramm in your page (post)", 'runcrew') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"max_value" => array(
					"title" => esc_html__("Max value", 'runcrew'),
					"desc" => wp_kses_data( __("Max value for skills items", 'runcrew') ),
					"value" => 100,
					"min" => 1,
					"type" => "spinner"
				),
				"type" => array(
					"title" => esc_html__("Skills type", 'runcrew'),
					"desc" => wp_kses_data( __("Select type of skills block", 'runcrew') ),
					"value" => "bar",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'bar' => esc_html__('Bar', 'runcrew'),
						'pie' => esc_html__('Pie chart', 'runcrew'),
						'counter' => esc_html__('Counter', 'runcrew'),
						'arc' => esc_html__('Arc', 'runcrew')
					)
				), 
				"layout" => array(
					"title" => esc_html__("Skills layout", 'runcrew'),
					"desc" => wp_kses_data( __("Select layout of skills block", 'runcrew') ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "rows",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'rows' => esc_html__('Rows', 'runcrew'),
						'columns' => esc_html__('Columns', 'runcrew')
					)
				),
				"dir" => array(
					"title" => esc_html__("Direction", 'runcrew'),
					"desc" => wp_kses_data( __("Select direction of skills block", 'runcrew') ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "horizontal",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('dir')
				), 
				"style" => array(
					"title" => esc_html__("Counters style", 'runcrew'),
					"desc" => wp_kses_data( __("Select style of skills items (only for type=counter)", 'runcrew') ),
					"dependency" => array(
						'type' => array('counter')
					),
					"value" => 1,
					"options" => runcrew_get_list_styles(1, 4),
					"type" => "checklist"
				), 
				// "columns" - autodetect, not set manual
				"color" => array(
					"title" => esc_html__("Skills items color", 'runcrew'),
					"desc" => wp_kses_data( __("Color for all skills items", 'runcrew') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'runcrew'),
					"desc" => wp_kses_data( __("Background color for all skills items (only for type=pie)", 'runcrew') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"border_color" => array(
					"title" => esc_html__("Border color", 'runcrew'),
					"desc" => wp_kses_data( __("Border color for all skills items (only for type=pie)", 'runcrew') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Align skills block", 'runcrew'),
					"desc" => wp_kses_data( __("Align skills block to left or right side", 'runcrew') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => runcrew_get_sc_param('float')
				), 
				"arc_caption" => array(
					"title" => esc_html__("Arc Caption", 'runcrew'),
					"desc" => wp_kses_data( __("Arc caption - text in the center of the diagram", 'runcrew') ),
					"dependency" => array(
						'type' => array('arc')
					),
					"value" => "",
					"type" => "text"
				),
				"pie_compact" => array(
					"title" => esc_html__("Pie compact", 'runcrew'),
					"desc" => wp_kses_data( __("Show all skills in one diagram or as separate diagrams", 'runcrew') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => runcrew_get_sc_param('yes_no')
				),
				"pie_cutout" => array(
					"title" => esc_html__("Pie cutout", 'runcrew'),
					"desc" => wp_kses_data( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", 'runcrew') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => 0,
					"min" => 0,
					"max" => 99,
					"type" => "spinner"
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
			),
			"children" => array(
				"name" => "trx_skills_item",
				"title" => esc_html__("Skill", 'runcrew'),
				"desc" => wp_kses_data( __("Skills item", 'runcrew') ),
				"container" => false,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Title", 'runcrew'),
						"desc" => wp_kses_data( __("Current skills item title", 'runcrew') ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Value", 'runcrew'),
						"desc" => wp_kses_data( __("Current skills level", 'runcrew') ),
						"value" => 50,
						"min" => 0,
						"step" => 1,
						"type" => "spinner"
					),
					"color" => array(
						"title" => esc_html__("Color", 'runcrew'),
						"desc" => wp_kses_data( __("Current skills item color", 'runcrew') ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", 'runcrew'),
						"desc" => wp_kses_data( __("Current skills item background color (only for type=pie)", 'runcrew') ),
						"value" => "",
						"type" => "color"
					),
					"border_color" => array(
						"title" => esc_html__("Border color", 'runcrew'),
						"desc" => wp_kses_data( __("Current skills item border color (only for type=pie)", 'runcrew') ),
						"value" => "",
						"type" => "color"
					),
					"style" => array(
						"title" => esc_html__("Counter style", 'runcrew'),
						"desc" => wp_kses_data( __("Select style for the current skills item (only for type=counter)", 'runcrew') ),
						"value" => 1,
						"options" => runcrew_get_list_styles(1, 4),
						"type" => "checklist"
					), 
					"icon" => array(
						"title" => esc_html__("Counter icon",  'runcrew'),
						"desc" => wp_kses_data( __('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'runcrew') ),
						"value" => "",
						"type" => "icons",
						"options" => runcrew_get_sc_param('icons')
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
if ( !function_exists( 'runcrew_sc_skills_reg_shortcodes_vc' ) ) {
	//add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_skills_reg_shortcodes_vc');
	function runcrew_sc_skills_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_skills",
			"name" => esc_html__("Skills", 'runcrew'),
			"description" => wp_kses_data( __("Insert skills diagramm", 'runcrew') ),
			"category" => esc_html__('Content', 'runcrew'),
			'icon' => 'icon_trx_skills',
			"class" => "trx_sc_collection trx_sc_skills",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_skills_item'),
			"params" => array(
				array(
					"param_name" => "max_value",
					"heading" => esc_html__("Max value", 'runcrew'),
					"description" => wp_kses_data( __("Max value for skills items", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "100",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Skills type", 'runcrew'),
					"description" => wp_kses_data( __("Select type of skills block", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Bar', 'runcrew') => 'bar',
						esc_html__('Pie chart', 'runcrew') => 'pie',
						esc_html__('Counter', 'runcrew') => 'counter',
						esc_html__('Arc', 'runcrew') => 'arc'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "layout",
					"heading" => esc_html__("Skills layout", 'runcrew'),
					"description" => wp_kses_data( __("Select layout of skills block", 'runcrew') ),
					"admin_label" => true,
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter','bar','pie')
					),
					"class" => "",
					"value" => array(
						esc_html__('Rows', 'runcrew') => 'rows',
						esc_html__('Columns', 'runcrew') => 'columns'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", 'runcrew'),
					"description" => wp_kses_data( __("Select direction of skills block", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('dir')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counters style", 'runcrew'),
					"description" => wp_kses_data( __("Select style of skills items (only for type=counter)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_list_styles(1, 4)),
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns count", 'runcrew'),
					"description" => wp_kses_data( __("Skills columns count (required)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'runcrew'),
					"description" => wp_kses_data( __("Color for all skills items", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'runcrew'),
					"description" => wp_kses_data( __("Background color for all skills items (only for type=pie)", 'runcrew') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", 'runcrew'),
					"description" => wp_kses_data( __("Border color for all skills items (only for type=pie)", 'runcrew') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'runcrew'),
					"description" => wp_kses_data( __("Align skills block to left or right side", 'runcrew') ),
					"class" => "",
					"value" => array_flip(runcrew_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "arc_caption",
					"heading" => esc_html__("Arc caption", 'runcrew'),
					"description" => wp_kses_data( __("Arc caption - text in the center of the diagram", 'runcrew') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('arc')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "pie_compact",
					"heading" => esc_html__("Pie compact", 'runcrew'),
					"description" => wp_kses_data( __("Show all skills in one diagram or as separate diagrams", 'runcrew') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => array(esc_html__('Show separate skills', 'runcrew') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pie_cutout",
					"heading" => esc_html__("Pie cutout", 'runcrew'),
					"description" => wp_kses_data( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", 'runcrew') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
		
		
		vc_map( array(
			"base" => "trx_skills_item",
			"name" => esc_html__("Skill", 'runcrew'),
			"description" => wp_kses_data( __("Skills item", 'runcrew') ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_skills_item',
			"class" => "trx_sc_single trx_sc_skills_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_skills'),
			"as_parent" => array('except' => 'trx_skills'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'runcrew'),
					"description" => wp_kses_data( __("Title for the current skills item", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'runcrew'),
					"description" => wp_kses_data( __("Value for the current skills item", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'runcrew'),
					"description" => wp_kses_data( __("Color for current skills item", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'runcrew'),
					"description" => wp_kses_data( __("Background color for current skills item (only for type=pie)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", 'runcrew'),
					"description" => wp_kses_data( __("Border color for current skills item (only for type=pie)", 'runcrew') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counter style", 'runcrew'),
					"description" => wp_kses_data( __("Select style for the current skills item (only for type=counter)", 'runcrew') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(runcrew_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Counter icon", 'runcrew'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set, placed before counter (only for type=counter)", 'runcrew') ),
					"class" => "",
					"value" => runcrew_get_sc_param('icons'),
					"type" => "dropdown"
				),
				runcrew_get_vc_param('id'),
				runcrew_get_vc_param('class'),
				runcrew_get_vc_param('css'),
			)
		) );
		
		class WPBakeryShortCode_Trx_Skills extends RUNCREW_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Skills_Item extends RUNCREW_VC_ShortCodeSingle {}
	}
}
?>