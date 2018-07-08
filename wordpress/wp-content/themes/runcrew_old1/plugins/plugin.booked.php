<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('runcrew_booked_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_booked_theme_setup', 1 );
	function runcrew_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (runcrew_exists_booked()) {
			add_action('runcrew_action_add_styles', 					'runcrew_booked_frontend_scripts');
			add_action('runcrew_action_shortcodes_list',				'runcrew_booked_reg_shortcodes');
			if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
				add_action('runcrew_action_shortcodes_list_vc',		'runcrew_booked_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'runcrew_filter_importer_options',			'runcrew_booked_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'runcrew_filter_importer_required_plugins',	'runcrew_booked_importer_required_plugins', 10, 2);
			add_filter( 'runcrew_filter_required_plugins',				'runcrew_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'runcrew_exists_booked' ) ) {
	function runcrew_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'runcrew_booked_required_plugins' ) ) {
	//add_filter('runcrew_filter_required_plugins',	'runcrew_booked_required_plugins');
	function runcrew_booked_required_plugins($list=array()) {
		if (in_array('booked', runcrew_storage_get('required_plugins'))) {
			$path = runcrew_get_file_dir('plugins/install/booked.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Booked',
					'slug' 		=> 'booked',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'runcrew_booked_frontend_scripts' ) ) {
	//add_action( 'runcrew_action_add_styles', 'runcrew_booked_frontend_scripts' );
	function runcrew_booked_frontend_scripts() {
		if (file_exists(runcrew_get_file_dir('css/plugin.booked.css')))
			runcrew_enqueue_style( 'runcrew-plugin.booked-style',  runcrew_get_file_url('css/plugin.booked.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'runcrew_booked_importer_required_plugins' ) ) {
	//add_filter( 'runcrew_filter_importer_required_plugins',	'runcrew_booked_importer_required_plugins', 10, 2);
	function runcrew_booked_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('booked', runcrew_storage_get('required_plugins')) && !runcrew_exists_booked() )
		if (runcrew_strpos($list, 'booked')!==false && !runcrew_exists_booked() )
			$not_installed .= '<br>Booked Appointments';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'runcrew_booked_importer_set_options' ) ) {
	//add_filter( 'runcrew_filter_importer_options',	'runcrew_booked_importer_set_options', 10, 1 );
	function runcrew_booked_importer_set_options($options=array()) {
		if (in_array('booked', runcrew_storage_get('required_plugins')) && runcrew_exists_booked()) {
			$options['additional_options'][] = 'booked_%';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}


// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'runcrew_get_list_booked_calendars' ) ) {
	function runcrew_get_list_booked_calendars($prepend_inherit=false) {
		return runcrew_exists_booked() ? runcrew_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('runcrew_booked_reg_shortcodes')) {
	//add_filter('runcrew_action_shortcodes_list',	'runcrew_booked_reg_shortcodes');
	function runcrew_booked_reg_shortcodes() {
		if (runcrew_storage_isset('shortcodes')) {

			$booked_cals = runcrew_get_list_booked_calendars();

			runcrew_sc_map('booked-appointments', array(
				"title" => esc_html__("Booked Appointments", 'runcrew'),
				"desc" => esc_html__("Display the currently logged in user's upcoming appointments", 'runcrew'),
				"decorate" => true,
				"container" => false,
				"params" => array()
				)
			);

			runcrew_sc_map('booked-calendar', array(
				"title" => esc_html__("Booked Calendar", 'runcrew'),
				"desc" => esc_html__("Insert booked calendar", 'runcrew'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"calendar" => array(
						"title" => esc_html__("Calendar", 'runcrew'),
						"desc" => esc_html__("Select booked calendar to display", 'runcrew'),
						"value" => "0",
						"type" => "select",
						"options" => runcrew_array_merge(array(0 => esc_html__('- Select calendar -', 'runcrew')), $booked_cals)
					),
					"year" => array(
						"title" => esc_html__("Year", 'runcrew'),
						"desc" => esc_html__("Year to display on calendar by default", 'runcrew'),
						"value" => date("Y"),
						"min" => date("Y"),
						"max" => date("Y")+10,
						"type" => "spinner"
					),
					"month" => array(
						"title" => esc_html__("Month", 'runcrew'),
						"desc" => esc_html__("Month to display on calendar by default", 'runcrew'),
						"value" => date("m"),
						"min" => 1,
						"max" => 12,
						"type" => "spinner"
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('runcrew_booked_reg_shortcodes_vc')) {
	//add_filter('runcrew_action_shortcodes_list_vc',	'runcrew_booked_reg_shortcodes_vc');
	function runcrew_booked_reg_shortcodes_vc() {

		$booked_cals = runcrew_get_list_booked_calendars();

		// Booked Appointments
		vc_map( array(
				"base" => "booked-appointments",
				"name" => esc_html__("Booked Appointments", 'runcrew'),
				"description" => esc_html__("Display the currently logged in user's upcoming appointments", 'runcrew'),
				"category" => esc_html__('Content', 'runcrew'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_appointments",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array()
			) );
			
		class WPBakeryShortCode_Booked_Appointments extends RUNCREW_VC_ShortCodeSingle {}

		// Booked Calendar
		vc_map( array(
				"base" => "booked-calendar",
				"name" => esc_html__("Booked Calendar", 'runcrew'),
				"description" => esc_html__("Insert booked calendar", 'runcrew'),
				"category" => esc_html__('Content', 'runcrew'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_calendar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "calendar",
						"heading" => esc_html__("Calendar", 'runcrew'),
						"description" => esc_html__("Select booked calendar to display", 'runcrew'),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array_flip(runcrew_array_merge(array(0 => esc_html__('- Select calendar -', 'runcrew')), $booked_cals)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "year",
						"heading" => esc_html__("Year", 'runcrew'),
						"description" => esc_html__("Year to display on calendar by default", 'runcrew'),
						"admin_label" => true,
						"class" => "",
						"std" => date("Y"),
						"value" => date("Y"),
						"type" => "textfield"
					),
					array(
						"param_name" => "month",
						"heading" => esc_html__("Month", 'runcrew'),
						"description" => esc_html__("Month to display on calendar by default", 'runcrew'),
						"admin_label" => true,
						"class" => "",
						"std" => date("m"),
						"value" => date("m"),
						"type" => "textfield"
					)
				)
			) );
			
		class WPBakeryShortCode_Booked_Calendar extends RUNCREW_VC_ShortCodeSingle {}

	}
}
?>