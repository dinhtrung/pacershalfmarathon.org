<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'runcrew_shortcodes_is_used' ) ) {
	function runcrew_shortcodes_is_used() {
		return runcrew_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && runcrew_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('runcrew_vc_is_frontend') && runcrew_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'runcrew_shortcodes_width' ) ) {
	function runcrew_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'runcrew'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'runcrew_shortcodes_height' ) ) {
	function runcrew_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'runcrew'),
			"desc" => wp_kses_data( __("Width and height of the element", 'runcrew') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'runcrew_get_sc_param' ) ) {
	function runcrew_get_sc_param($prm) {
		return runcrew_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'runcrew_set_sc_param' ) ) {
	function runcrew_set_sc_param($prm, $val) {
		runcrew_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'runcrew_sc_map' ) ) {
	function runcrew_sc_map($sc_name, $sc_settings) {
		runcrew_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'runcrew_sc_map_after' ) ) {
	function runcrew_sc_map_after($after, $sc_name, $sc_settings='') {
		runcrew_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'runcrew_sc_map_before' ) ) {
	function runcrew_sc_map_before($before, $sc_name, $sc_settings='') {
		runcrew_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'runcrew_compare_sc_title' ) ) {
	function runcrew_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'runcrew_shortcodes_settings_theme_setup' ) ) {
//	if ( runcrew_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'runcrew_action_before_init_theme', 'runcrew_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'runcrew_action_after_init_theme', 'runcrew_shortcodes_settings_theme_setup' );
	function runcrew_shortcodes_settings_theme_setup() {
		if (runcrew_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = runcrew_storage_get('registered_templates');
			ksort($tmp);
			runcrew_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			runcrew_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'runcrew'),
					"desc" => wp_kses_data( __("ID for current element", 'runcrew') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'runcrew'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'runcrew'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'runcrew') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'runcrew'),
					'ol'	=> esc_html__('Ordered', 'runcrew'),
					'iconed'=> esc_html__('Iconed', 'runcrew')
				),

				'yes_no'	=> runcrew_get_list_yesno(),
				'on_off'	=> runcrew_get_list_onoff(),
				'dir' 		=> runcrew_get_list_directions(),
				'align'		=> runcrew_get_list_alignments(),
				'float'		=> runcrew_get_list_floats(),
				'hpos'		=> runcrew_get_list_hpos(),
				'show_hide'	=> runcrew_get_list_showhide(),
				'sorting' 	=> runcrew_get_list_sortings(),
				'ordering' 	=> runcrew_get_list_orderings(),
				'shapes'	=> runcrew_get_list_shapes(),
				'sizes'		=> runcrew_get_list_sizes(),
				'sliders'	=> runcrew_get_list_sliders(),
				'controls'	=> runcrew_get_list_controls(),
				'categories'=> runcrew_get_list_categories(),
				'columns'	=> runcrew_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), runcrew_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), runcrew_get_list_icons()),
				'locations'	=> runcrew_get_list_dedicated_locations(),
				'filters'	=> runcrew_get_list_portfolio_filters(),
				'formats'	=> runcrew_get_list_post_formats_filters(),
				'hovers'	=> runcrew_get_list_hovers(true),
				'hovers_dir'=> runcrew_get_list_hovers_directions(true),
				'schemes'	=> runcrew_get_list_color_schemes(true),
				'animations'		=> runcrew_get_list_animations_in(),
				'margins' 			=> runcrew_get_list_margins(true),
				'blogger_styles'	=> runcrew_get_list_templates_blogger(),
				'forms'				=> runcrew_get_list_templates_forms(),
				'posts_types'		=> runcrew_get_list_posts_types(),
				'googlemap_styles'	=> runcrew_get_list_googlemap_styles(),
				'field_types'		=> runcrew_get_list_field_types(),
				'label_positions'	=> runcrew_get_list_label_positions()
				)
			);

			// Common params
			runcrew_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'runcrew'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'runcrew') ),
				"value" => "none",
				"type" => "select",
				"options" => runcrew_get_sc_param('animations')
				)
			);
			runcrew_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'runcrew'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => runcrew_get_sc_param('margins')
				)
			);
			runcrew_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'runcrew'),
				"value" => "inherit",
				"type" => "select",
				"options" => runcrew_get_sc_param('margins')
				)
			);
			runcrew_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'runcrew'),
				"value" => "inherit",
				"type" => "select",
				"options" => runcrew_get_sc_param('margins')
				)
			);
			runcrew_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'runcrew'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'runcrew') ),
				"value" => "inherit",
				"type" => "select",
				"options" => runcrew_get_sc_param('margins')
				)
			);

			runcrew_storage_set('sc_params', apply_filters('runcrew_filter_shortcodes_params', runcrew_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			runcrew_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('runcrew_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = runcrew_storage_get('shortcodes');
			uasort($tmp, 'runcrew_compare_sc_title');
			runcrew_storage_set('shortcodes', $tmp);
		}
	}
}
?>