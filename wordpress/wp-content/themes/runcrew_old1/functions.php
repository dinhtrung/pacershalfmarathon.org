<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'runcrew_theme_setup' ) ) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_theme_setup', 1 );
	function runcrew_theme_setup() {

		// Register theme menus
		add_filter( 'runcrew_filter_add_theme_menus',		'runcrew_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'runcrew_filter_add_theme_sidebars',	'runcrew_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'runcrew_filter_importer_options',		'runcrew_set_importer_options' );

		// Add theme required plugins
		add_filter( 'runcrew_filter_required_plugins',		'runcrew_add_required_plugins' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 'runcrew_body_classes' );

		// Set list of the theme required plugins
		runcrew_storage_set('required_plugins', array(
			'buddypress',		// Attention! This slug used to install both BuddyPress and bbPress
			'essgrids',
			'instagram_widget',
			'instagram_feed',
			'revslider',
			'tribe_events',
			'trx_utils',
			'visual_composer',
			'woocommerce'
			)
		);
		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'runcrew_add_theme_menus' ) ) {
	//add_filter( 'runcrew_filter_add_theme_menus', 'runcrew_add_theme_menus' );
	function runcrew_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'runcrew');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}

// Add theme specific widgetized areas
if ( !function_exists( 'runcrew_add_theme_sidebars' ) ) {
	//add_filter( 'runcrew_filter_add_theme_sidebars',	'runcrew_add_theme_sidebars' );
	function runcrew_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'runcrew' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'runcrew' ),
				'sidebar_services'	=> esc_html__( 'Services Sidebar', 'runcrew' )
			);
			if (function_exists('runcrew_exists_woocommerce') && runcrew_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'runcrew' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'runcrew_add_required_plugins' ) ) {
	//add_filter( 'runcrew_filter_required_plugins',		'runcrew_add_required_plugins' );
	function runcrew_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('RunCrew Utilities', 'runcrew' ),
			'version'	=> '2.7',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> runcrew_get_file_dir('plugins/install/trx_utils.zip'),
			'force_activation'   => true,			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => true,			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'required' 	=> true
		);
		return $plugins;
	}
}


// Add theme specified classes into the body
if ( !function_exists('runcrew_body_classes') ) {
	//add_filter( 'body_class', 'runcrew_body_classes' );
	function runcrew_body_classes( $classes ) {

		$classes[] = 'runcrew_body';
		$classes[] = 'body_style_' . trim(runcrew_get_custom_option('body_style'));
		$classes[] = 'body_' . (runcrew_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(runcrew_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(runcrew_get_custom_option('article_style'));
		
		$blog_style = runcrew_get_custom_option(is_singular() && !runcrew_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(runcrew_get_template_name($blog_style));
		
		$body_scheme = runcrew_get_custom_option('body_scheme');
		if (empty($body_scheme)  || runcrew_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = runcrew_get_custom_option('top_panel_position');
		if (!runcrew_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = runcrew_get_sidebar_class();

		if (runcrew_get_custom_option('show_video_bg')=='yes' && (runcrew_get_custom_option('video_bg_youtube_code')!='' || runcrew_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (runcrew_get_theme_option('page_preloader')!='')
			$classes[] = 'preloader';

		return $classes;
	}
}


// Set theme specific importer options
if ( !function_exists( 'runcrew_set_importer_options' ) ) {
	//add_filter( 'runcrew_filter_importer_options',	'runcrew_set_importer_options' );
	function runcrew_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['debug'] = runcrew_get_theme_option('debug_mode')=='yes';
			$options['menus'] = array(
				'menu-main'	  => esc_html__('Main menu', 'runcrew'),
				'menu-user'	  => esc_html__('User menu', 'runcrew'),
				'menu-footer' => esc_html__('Footer menu', 'runcrew'),
			);

			// Prepare demo data
			$demo_data_url = esc_url('http://runcrew.ancorathemes.com/demo/');
			
			// Main demo
			$options['files']['default'] = array(
				'title'				=> esc_html__('RunCrew demo', 'runcrew'),
				'file_with_posts'	=> $demo_data_url . 'default/posts.txt',
				'file_with_users'	=> $demo_data_url . 'default/users.txt',
				'file_with_mods'	=> $demo_data_url . 'default/theme_mods.txt',
				'file_with_options'	=> $demo_data_url . 'default/theme_options.txt',
				'file_with_templates'=>$demo_data_url . 'default/templates_options.txt',
				'file_with_widgets'	=> $demo_data_url . 'default/widgets.txt',
				'file_with_revsliders' => array(
					$demo_data_url . 'default/revsliders/home.zip',
					$demo_data_url . 'default/revsliders/home-2.zip'
				),
				'file_with_attachments' => array(),
				'attachments_by_parts'	=> true,
				'domain_dev'	=> 'runcrew.dv.ancorathemes.com',	// Developers domain ( without protocol, used only for str_replace(), not need esc_url() )
				'domain_demo'	=> 'runcrew.ancorathemes.com'	    // Demo-site domain ( without protocol, used only for str_replace(), not need esc_url() )
			);
			for ($i=1; $i<=3; $i++) {
				$options['files']['default']['file_with_attachments'][] = $demo_data_url . 'default/uploads/uploads.' . sprintf('%03u', $i);
			}
		}
		return $options;
	}
}




// Page preloader options
if ( !function_exists( 'runcrew_page_preloader_style_css' ) ) {
    function runcrew_page_preloader_style_css()    {
        if (($preloader=runcrew_get_theme_option('page_preloader'))!='') {
            $clr = runcrew_get_scheme_color('bg_color');
            ?>
            <style type="text/css">
                <!--
                #page_preloader { background-color: <?php echo esc_attr($clr); ?>; background-image:url(<?php echo esc_url($preloader); ?>);}
                -->
            </style>
            <?php
        }
    }
}

/* Include framework core files
------------------------------------------------------------------- */
	get_template_part('fw/loader');
?>