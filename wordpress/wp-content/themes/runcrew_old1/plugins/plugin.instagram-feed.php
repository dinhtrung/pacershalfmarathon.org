<?php
/* Instagram Feed support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('runcrew_instagram_feed_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_instagram_feed_theme_setup', 1 );
	function runcrew_instagram_feed_theme_setup() {
		if (runcrew_exists_instagram_feed()) {
			if (is_admin()) {
				add_filter( 'runcrew_filter_importer_options',				'runcrew_instagram_feed_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'runcrew_filter_importer_required_plugins',		'runcrew_instagram_feed_importer_required_plugins', 10, 2 );
			add_filter( 'runcrew_filter_required_plugins',					'runcrew_instagram_feed_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'runcrew_exists_instagram_feed' ) ) {
	function runcrew_exists_instagram_feed() {
		return defined('SBIVER');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'runcrew_instagram_feed_required_plugins' ) ) {
	//add_filter('runcrew_filter_required_plugins',	'runcrew_instagram_feed_required_plugins');
	function runcrew_instagram_feed_required_plugins($list=array()) {
		if (in_array('instagram_feed', runcrew_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Instagram Feed',
					'slug' 		=> 'instagram-feed',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Feed in the required plugins
if ( !function_exists( 'runcrew_instagram_feed_importer_required_plugins' ) ) {
	//add_filter( 'runcrew_filter_importer_required_plugins',	'runcrew_instagram_feed_importer_required_plugins', 10, 2 );
	function runcrew_instagram_feed_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('instagram_feed', runcrew_storage_get('required_plugins')) && !runcrew_exists_instagram_feed() )
		if (runcrew_strpos($list, 'instagram_feed')!==false && !runcrew_exists_instagram_feed() )
			$not_installed .= '<br>Instagram Feed';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'runcrew_instagram_feed_importer_set_options' ) ) {
	//add_filter( 'runcrew_filter_importer_options',	'runcrew_instagram_feed_importer_set_options' );
	function runcrew_instagram_feed_importer_set_options($options=array()) {
		if ( in_array('instagram_feed', runcrew_storage_get('required_plugins')) && runcrew_exists_instagram_feed() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'sb_instagram_settings';
		}
		return $options;
	}
}
?>