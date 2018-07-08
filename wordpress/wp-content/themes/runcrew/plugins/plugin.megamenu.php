<?php
/* Mega Main Menu support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('runcrew_megamenu_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_megamenu_theme_setup', 1 );
	function runcrew_megamenu_theme_setup() {
		if (runcrew_exists_megamenu()) {
			if (is_admin()) {
				add_filter( 'runcrew_filter_importer_options',				'runcrew_megamenu_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'runcrew_filter_importer_required_plugins',		'runcrew_megamenu_importer_required_plugins', 10, 2 );
			add_filter( 'runcrew_filter_required_plugins',					'runcrew_megamenu_required_plugins' );
		}
	}
}

// Check if MegaMenu installed and activated
if ( !function_exists( 'runcrew_exists_megamenu' ) ) {
	function runcrew_exists_megamenu() {
		return class_exists('mega_main_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'runcrew_megamenu_required_plugins' ) ) {
	//add_filter('runcrew_filter_required_plugins',	'runcrew_megamenu_required_plugins');
	function runcrew_megamenu_required_plugins($list=array()) {
		if (in_array('mega_main_menu', runcrew_storage_get('required_plugins'))) {
			$path = runcrew_get_file_dir('plugins/install/mega_main_menu.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Mega Main Menu',
					'slug' 		=> 'mega_main_menu',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mega Menu in the required plugins
if ( !function_exists( 'runcrew_megamenu_importer_required_plugins' ) ) {
	//add_filter( 'runcrew_filter_importer_required_plugins',	'runcrew_megamenu_importer_required_plugins', 10, 2 );
	function runcrew_megamenu_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('mega_main_menu', runcrew_storage_get('required_plugins')) && !runcrew_exists_megamenu())
		if (runcrew_strpos($list, 'mega_main_menu')!==false && !runcrew_exists_megamenu())
			$not_installed .= '<br>Mega Main Menu';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'runcrew_megamenu_importer_set_options' ) ) {
	//add_filter( 'runcrew_filter_importer_options',	'runcrew_megamenu_importer_set_options' );
	function runcrew_megamenu_importer_set_options($options=array()) {
		if ( in_array('mega_main_menu', runcrew_storage_get('required_plugins')) && runcrew_exists_megamenu() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'mega_main_menu_options';

		}
		return $options;
	}
}
?>