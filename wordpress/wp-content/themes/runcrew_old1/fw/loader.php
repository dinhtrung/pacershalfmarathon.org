<?php
/**
 * RunCrew Framework
 *
 * @package runcrew
 * @since runcrew 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'RUNCREW_FW_DIR' ) )			define( 'RUNCREW_FW_DIR', 'fw' );

// Theme timing
if ( ! defined( 'RUNCREW_START_TIME' ) )		define( 'RUNCREW_START_TIME', microtime(true));		// Framework start time
if ( ! defined( 'RUNCREW_START_MEMORY' ) )		define( 'RUNCREW_START_MEMORY', memory_get_usage());	// Memory usage before core loading
if ( ! defined( 'RUNCREW_START_QUERIES' ) )	define( 'RUNCREW_START_QUERIES', get_num_queries());	// DB queries used

// Include theme variables storage
get_template_part(RUNCREW_FW_DIR.'/core/core.storage');

// Theme variables storage
//$theme_slug = str_replace(' ', '_', trim(strtolower(get_stylesheet())));
//runcrew_storage_set('options_prefix', 'runcrew'.'_'.trim($theme_slug));	// Used as prefix to store theme's options in the post meta and wp options
runcrew_storage_set('options_prefix', 'runcrew');	// Used as prefix to store theme's options in the post meta and wp options
runcrew_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
runcrew_storage_set('widgets_args', array(			// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget_title">',
		'after_title'   => '</h4>',
	)
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'runcrew_loader_theme_setup', 20 );
	function runcrew_loader_theme_setup() {

		runcrew_profiler_add_point(esc_html__('After load theme required files', 'runcrew'));

		// Before init theme
		do_action('runcrew_action_before_init_theme');

		// Load current values for main theme options
		runcrew_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			runcrew_core_init_theme();
		}
	}
}

/* Include core parts
------------------------------------------------------------------------ */
// Manual load important libraries before load all rest files
// core.strings must be first - we use runcrew_str...() in the runcrew_get_file_dir()
get_template_part(RUNCREW_FW_DIR.'/core/core.strings');
// core.files must be first - we use runcrew_get_file_dir() to include all rest parts
get_template_part(RUNCREW_FW_DIR.'/core/core.files');

// Include debug and profiler
get_template_part(runcrew_get_file_slug('core/core.debug.php'));

// Include custom theme files
runcrew_autoload_folder( 'includes' );

// Include core files
runcrew_autoload_folder( 'core' );

// Include theme-specific plugins and post types
runcrew_autoload_folder( 'plugins' );

// Include theme templates
runcrew_autoload_folder( 'templates' );

// Include theme widgets
runcrew_autoload_folder( 'widgets' );
?>