<?php

/* Theme setup section
-------------------------------------------------------------------- */

// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings

runcrew_storage_set('settings', array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'no',								// no|internal|external - Generate map for .less files.
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'add_image_size'	=> false,								// Add theme's thumb sizes into WP list sizes. 
																// If false - new image thumb will be generated on demand,
																// otherwise - all thumb sizes will be generated when image is loaded

	'use_list_cache'	=> true,								// Use cache for any lists (increase theme speed, but get 15-20K memory)
	'use_post_cache'	=> true,								// Use cache for post_data (increase theme speed, decrease queries number, but get more memory - up to 300K)

	'allow_profiler'	=> true,								// Allow to show theme profiler when 'debug mode' is on

	'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
	)
);



// Default Theme Options
if ( !function_exists( 'runcrew_options_settings_theme_setup' ) ) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_options_settings_theme_setup', 2 );	// Priority 1 for add runcrew_filter handlers
	function runcrew_options_settings_theme_setup() {
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'runcrew_options_reset');

		// Settings 
		$socials_type = runcrew_get_theme_setting('socials_type');
				
		// Prepare arrays 
		runcrew_storage_set('options_params', apply_filters('runcrew_filter_theme_options_params', array(
			'list_fonts'				=> array('$runcrew_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$runcrew_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$runcrew_get_list_socials' => ''),
			'list_icons' 				=> array('$runcrew_get_list_icons' => ''),
			'list_posts_types' 			=> array('$runcrew_get_list_posts_types' => ''),
			'list_categories' 			=> array('$runcrew_get_list_categories' => ''),
			'list_menus'				=> array('$runcrew_get_list_menus' => ''),
			'list_sidebars'				=> array('$runcrew_get_list_sidebars' => ''),
			'list_positions' 			=> array('$runcrew_get_list_sidebars_positions' => ''),
			'list_skins'				=> array('$runcrew_get_list_skins' => ''),
			'list_color_schemes'		=> array('$runcrew_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$runcrew_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$runcrew_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$runcrew_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$runcrew_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$runcrew_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$runcrew_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$runcrew_get_list_blog_counters' => ''),
			'list_animations_in' 		=> array('$runcrew_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$runcrew_get_list_animations_out' => ''),
			'list_filters'				=> array('$runcrew_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$runcrew_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$runcrew_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$runcrew_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$runcrew_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$runcrew_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$runcrew_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$runcrew_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$runcrew_get_list_yesno' => ''),
			'list_on_off' 				=> array('$runcrew_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$runcrew_get_list_showhide' => ''),
			'list_sorting' 				=> array('$runcrew_get_list_sortings' => ''),
			'list_ordering' 			=> array('$runcrew_get_list_orderings' => ''),
			'list_locations' 			=> array('$runcrew_get_list_dedicated_locations' => '')
			)
		));


		// Theme options array
		runcrew_storage_set('options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'runcrew'),
					"start" => "partitions",
					"override" => "category,services_group,post,page,custom",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'runcrew'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'runcrew'),
					"desc" => wp_kses_data( __('Select body style:', 'runcrew') )
								. ' <br>' 
								. wp_kses_data( __('<b>boxed</b> - if you want use background color and/or image', 'runcrew') )
								. ',<br>'
								. wp_kses_data( __('<b>wide</b> - page fill whole window with centered content', 'runcrew') )
								. (runcrew_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses_data( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'runcrew') )
									: '')
								. (runcrew_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses_data( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'runcrew') )
									: ''),
					"info" => true,
					"override" => "category,services_group,post,page,custom",
					"std" => "wide",
					"options" => runcrew_get_options_param('list_body_styles'),
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'runcrew'),
					"desc" => wp_kses_data( __('Add paddings above and below the page content', 'runcrew') ),
					"override" => "post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'runcrew'),
					"desc" => wp_kses_data( __('Select skin for the theme decoration', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"options" => runcrew_get_options_param('list_skins'),
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the entire page', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'runcrew'),
					"desc" => wp_kses_data( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'runcrew'),
					"desc" => wp_kses_data( __('Color and image for the site background', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'runcrew'),
					"desc" => wp_kses_data( __("Use custom color and/or image as the site background", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'runcrew'),
					"desc" => wp_kses_data( __('Body background color',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'runcrew'),
					"desc" => wp_kses_data( __('Select theme background pattern (first case - without pattern)',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => runcrew_get_file_url('images/spacer.png'),
						1 => runcrew_get_file_url('images/bg/pattern_1.jpg'),
						2 => runcrew_get_file_url('images/bg/pattern_2.jpg'),
						3 => runcrew_get_file_url('images/bg/pattern_3.jpg'),
						4 => runcrew_get_file_url('images/bg/pattern_4.jpg'),
						5 => runcrew_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'runcrew'),
					"desc" => wp_kses_data( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'runcrew'),
					"desc" => wp_kses_data( __('Select theme background image (first case - without image)',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => runcrew_get_file_url('images/spacer.png'),
						1 => runcrew_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => runcrew_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => runcrew_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'runcrew'),
					"desc" => wp_kses_data( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'runcrew'),
					"desc" => wp_kses_data( __('Select custom image position',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'runcrew'),
					"desc" => wp_kses_data( __('Always load background images or only for boxed body style', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'runcrew'),
						'always' => esc_html__('Always', 'runcrew')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'runcrew'),
					"desc" => wp_kses_data( __('Parameters of the video, used as site background', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'runcrew'),
					"desc" => wp_kses_data( __("Show video as the site background", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'runcrew'),
					"desc" => wp_kses_data( __("Youtube code of video", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'runcrew'),
					"desc" => wp_kses_data( __("URL to video-file (uploaded on your site)", 'runcrew') ),
					"readonly" =>false,
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'runcrew'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'runcrew'),
															'update' => esc_html__( 'Select Video', 'runcrew')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'runcrew'),
					"desc" => wp_kses_data( __('Use overlay texture for the video background', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'runcrew'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'runcrew'),
					"desc" => wp_kses_data( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'runcrew'),
					"desc" => wp_kses_data( __('Select desired style of the page header', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "header_3",
					"options" => runcrew_get_options_param('list_header_styles'),
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'runcrew'),
					"desc" => wp_kses_data( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array(
					"title" => esc_html__('Top panel position', 'runcrew'),
					"desc" => wp_kses_data( __('Select position for the top panel with logo and main menu', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "above",
					"options" => array(
						'hide'      => esc_html__('Hide', 'runcrew'),
						'above'     => esc_html__('Above slider', 'runcrew'),
						'below'     => esc_html__('Below slider', 'runcrew'),
						'over'      => esc_html__('Over slider', 'runcrew')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the top panel', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'runcrew'),
					"desc" => wp_kses_data( __('Show post/page/category title', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'runcrew'),
					"desc" => wp_kses_data( __('Show path to current category (post, page)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'runcrew'),
					"desc" => wp_kses_data( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'runcrew') ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'runcrew'),
					"desc" => wp_kses_data( __('Select the Main menu style and position', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'runcrew'),
					"desc" => wp_kses_data( __('Select main menu for the current page',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"options" => runcrew_get_options_param('list_menus'),
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'runcrew'),
					"desc" => wp_kses_data( __('Attach main menu to top of window then page scroll down', 'runcrew') ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'runcrew'), 
						"none"=>esc_html__("Don't fix menu position", 'runcrew')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'runcrew'),
					"desc" => wp_kses_data( __('Select animation to show submenu ', 'runcrew') ),
					"std" => "fadeIn",
					"type" => "select",
					"options" => runcrew_get_options_param('list_animations_in')),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'runcrew'),
					"desc" => wp_kses_data( __('Select animation to hide submenu ', 'runcrew') ),
					"std" => "fadeOut",
					"type" => "select",
					"options" => runcrew_get_options_param('list_animations_out')),
		
		"menu_mobile" => array( 
					"title" => esc_html__('Main menu responsive', 'runcrew'),
					"desc" => wp_kses_data( __('Allow responsive version for the main menu if window width less then this value', 'runcrew') ),
					"std" => 1024,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'runcrew'),
					"desc" => wp_kses_data( __('Width for dropdown menus in main menu', 'runcrew') ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'runcrew'),
					"desc" => wp_kses_data( __("Select parts for the user's menu area", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'runcrew'),
					"desc" => wp_kses_data( __('Show user menu area on top of page', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

        "show_login" => array(
                    "title" => esc_html__('Show Login/Logout buttons', 'runcrew'),
                    "desc" => wp_kses_data( __('Show Login and Logout buttons in the user menu area', 'runcrew') ),
                    "dependency" => array(
                        'show_top_panel_top' => array('yes')
                    ),
                    "std" => "yes",
                    "options" => runcrew_get_options_param('list_yes_no'),
                    "type" => "switch"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'runcrew'),
					"desc" => wp_kses_data( __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'runcrew') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'runcrew'),
					"desc" => wp_kses_data( __('Show bookmarks selector in the user menu', 'runcrew') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		

		
		"info_header_4" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'runcrew'),
					"desc" => wp_kses_data( __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'runcrew'),
					"desc" => wp_kses_data( __('Show TOC for the current page', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'runcrew'),
						'fixed' => esc_html__('Fixed', 'runcrew'),
						'float' => esc_html__('Float', 'runcrew')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'runcrew'),
					"desc" => wp_kses_data( __('Automatically add "Home" item into table of contents - return to home page of the site', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'runcrew'),
					"desc" => wp_kses_data( __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'runcrew'),
					"desc" => wp_kses_data( __("Select or upload logos for the site's header and select it position", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'runcrew'),
					"desc" => wp_kses_data( __('Main logo image', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_retina' => array(
					"title" => esc_html__('Logo image for Retina', 'runcrew'),
					"desc" => wp_kses_data( __('Main logo image used on Retina display', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'runcrew'),
					"desc" => wp_kses_data( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'runcrew'),
					"desc" => wp_kses_data( __('Logo text - display it after logo image', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'runcrew'),
					"desc" => wp_kses_data( __('Height for the logo in the header area', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'runcrew'),
					"desc" => wp_kses_data( __('Top offset for the logo in the header area', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'runcrew'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Select parameters for main slider (you can override it in each category and page)', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'runcrew'),
					"desc" => wp_kses_data( __('Do you want to show slider on each page (post)', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'runcrew'),
					"desc" => wp_kses_data( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'runcrew'),
						"fullwide"=>esc_html__("Fullwide", 'runcrew'),
						"fullscreen"=>esc_html__("Fullscreen", 'runcrew')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'runcrew'),
					"desc" => wp_kses_data( __("Slider height (in pixels) - only if slider display with fixed height.", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'runcrew'),
					"desc" => wp_kses_data( __('What engine use to show slider?', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => runcrew_get_options_param('list_sliders'),
					"type" => "radio"),

		"slider_over_content" => array(
					"title" => esc_html__('Put content over slider',  'runcrew'),
					"desc" => wp_kses_data( __('Put content below on fixed layer over this slider',  'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "editor"),

		"slider_over_scheme" => array(
					"title" => esc_html__('Color scheme for content above', 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the content over the slider', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "dark",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'runcrew'),
					"desc" => wp_kses_data( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => runcrew_array_merge(array(0 => esc_html__('- Select category -', 'runcrew')), runcrew_get_options_param('list_categories')),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'runcrew'),
					"desc" => wp_kses_data( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'runcrew'),
					"desc" => wp_kses_data( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => runcrew_get_options_param('list_sorting'),
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'runcrew'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => runcrew_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'runcrew'),
					"desc" => wp_kses_data( __("Interval (in ms) for slides change in slider", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'runcrew'),
					"desc" => wp_kses_data( __("Choose pagination style for the slider", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'runcrew'),
						'yes'  => esc_html__('Dots', 'runcrew'), 
						'over' => esc_html__('Titles', 'runcrew')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'runcrew'),
					"desc" => wp_kses_data( __("Do you want to show post's title, reviews rating and description on slides in slider", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'runcrew'),
						'slide' => esc_html__('Slide', 'runcrew'), 
						'fixed' => esc_html__('Fixed', 'runcrew')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'runcrew'),
					"desc" => wp_kses_data( __("Do you want to show post's category on slides in slider", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'runcrew'),
					"desc" => wp_kses_data( __("Do you want to show post's reviews rating on slides in slider", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'runcrew'),
					"desc" => wp_kses_data( __("How many characters show in the post's description in slider. 0 - no descriptions", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'runcrew'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'runcrew'),
					"desc" => wp_kses_data( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'runcrew') ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'runcrew'),
					"desc" => wp_kses_data( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'runcrew') ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'runcrew'),
					"desc" => wp_kses_data( __('Show / Hide and select main sidebar', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'runcrew'),
					"desc" => wp_kses_data( __('Select position for the main sidebar or hide it',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "right",
					"options" => runcrew_get_options_param('list_positions'),
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the main sidebar', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'runcrew'),
					"desc" => wp_kses_data( __('Select main sidebar content',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => runcrew_get_options_param('list_sidebars'),
					"type" => "select"),


		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'runcrew'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'runcrew'),
					"desc" => wp_kses_data( __("Select components of the footer, set style and put the content for the user's footer area", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'runcrew'),
					"desc" => wp_kses_data( __('Select style for the footer sidebar or hide it', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the footer', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'runcrew'),
					"desc" => wp_kses_data( __('Select footer sidebar for the blog page',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => runcrew_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'runcrew'),
					"desc" => wp_kses_data( __('Select columns number for the footer sidebar',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => esc_html__('Testimonials in Footer', 'runcrew'),
					"desc" => wp_kses_data( __('Select parameters for Testimonials in the Footer', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'runcrew'),
					"desc" => wp_kses_data( __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the testimonials area', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'runcrew'),
					"desc" => wp_kses_data( __('Number testimonials to show', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Twitter in Footer', 'runcrew'),
					"desc" => wp_kses_data( __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'runcrew'),
					"desc" => wp_kses_data( __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the twitter area', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'runcrew'),
					"desc" => wp_kses_data( __('Number twitter to show', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Select parameters for Google map (you can override it in each category and page)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'runcrew'),
					"desc" => wp_kses_data( __('Do you want to show Google map on each page (post)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'runcrew'),
					"desc" => wp_kses_data( __("Map height (default - in pixels, allows any CSS units of measure)", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'runcrew'),
					"desc" => wp_kses_data( __("Enter address to show on map center", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longitude to show on map',  'runcrew'),
					"desc" => wp_kses_data( __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'runcrew'),
					"desc" => wp_kses_data( __("Enter title to show on map center", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'runcrew'),
					"desc" => wp_kses_data( __("Enter description to show on map center", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'runcrew'),
					"desc" => wp_kses_data( __("Enter desired initial zoom for Google map", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'runcrew'),
					"desc" => wp_kses_data( __("Select style to show Google map", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => runcrew_get_options_param('list_gmap_styles'),
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'runcrew'),
					"desc" => wp_kses_data( __("Select or upload png-image with Google map marker", 'runcrew') ),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'runcrew'),
					"desc" => wp_kses_data( __("Show/Hide contacts area in the footer", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'runcrew'),
					"desc" => wp_kses_data( __('Show contact information area in footer: site logo, contact info', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the contacts area', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'runcrew'),
					"desc" => wp_kses_data( __('Logo image in the footer (in the contacts area)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),

		'logo_footer_retina' => array(
					"title" => esc_html__('Logo image for footer for Retina', 'runcrew'),
					"desc" => wp_kses_data( __('Logo image in the footer (in the contacts area) used on Retina display', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'runcrew'),
					"desc" => wp_kses_data( __('Height for the logo in the footer area (in the contacts area)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),



        "info_footer_5_1" => array(
                "title" => esc_html__("Socials area", 'runcrew'),
                "desc" => wp_kses_data( __("Show/Hide contacts area in the footer", 'runcrew') ),
                "override" => "category,services_group,post,page,custom",
                "type" => "info"),

        "show_socials_in_footer" => array(
                "title" => esc_html__('Show Socials in footer', 'runcrew'),
                "desc" => wp_kses_data( __('Show socials links in footer', 'runcrew') ),
                "override" => "category,services_group,post,page,custom",
                "std" => "yes",
                "options" => runcrew_get_options_param('list_yes_no'),
                "type" => "switch"),

        "socials_scheme" => array(
                "title" => esc_html__("Color scheme", 'runcrew'),
                "desc" => wp_kses_data( __('Select predefined color scheme for the contacts area', 'runcrew') ),
                "override" => "category,services_group,post,page,custom",
                "dependency" => array(
                    'show_socials_in_footer' => array('yes')
                ),
                "std" => "original",
                "dir" => "horizontal",
                "options" => runcrew_get_options_param('list_color_schemes'),
                "type" => "checklist"),

		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'runcrew'),
					"desc" => wp_kses_data( __("Show/Hide copyright area in the footer", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'runcrew'),
					"desc" => wp_kses_data( __('Show area with copyright information, footer menu and small social icons in footer', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'runcrew'),
						'text' => esc_html__('Text', 'runcrew'),
						'menu' => esc_html__('Text and menu', 'runcrew'),
						'socials' => esc_html__('Text and Social icons', 'runcrew')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'runcrew'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the copyright area', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => runcrew_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'runcrew'),
					"desc" => wp_kses_data( __('Select footer menu for the current page',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => runcrew_get_options_param('list_menus'),
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'runcrew'),
					"desc" => wp_kses_data( __("Copyright text to show in footer area (bottom of site)", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"allow_html" => true,
					"std" => "AncoraThemes &copy; 2016 All Rights Reserved Terms of Use and Privacy Policy",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'runcrew'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Animation parameters and responsive layouts for the small screens', 'runcrew') ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'runcrew'),
					"desc" => wp_kses_data( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'runcrew'),
					"desc" => wp_kses_data( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'runcrew') ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'runcrew'),
					"desc" => wp_kses_data( __('Do you want use extended animations effects on your site?', 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'animation_on_mobile' => array(
					"title" => esc_html__('Allow CSS animations on mobile', 'runcrew'),
					"desc" => wp_kses_data( __('Do you allow extended animations effects on mobile devices?', 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'runcrew'),
					"desc" => wp_kses_data( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'runcrew'),
					"desc" => wp_kses_data( __('Do you want use responsive layouts on small screen or still use main layout?', 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'page_preloader' => array(
					"title" => esc_html__('Show page preloader',  'runcrew'),
					"desc" => wp_kses_data( __('Do you want show animated page preloader?',  'runcrew') ),
					"std" => "",
					"type" => "media"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Specify additional parameters, used to load Google fonts', 'runcrew') ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'runcrew'),
					"desc" => wp_kses_data( __('Select subset, included into used Google fonts', 'runcrew') ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'runcrew'),
						'latin-ext' => esc_html__('Latin Extended', 'runcrew'),
						'greek' => esc_html__('Greek', 'runcrew'),
						'greek-ext' => esc_html__('Greek Extended', 'runcrew'),
						'cyrillic' => esc_html__('Cyrillic', 'runcrew'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'runcrew'),
						'vietnamese' => esc_html__('Vietnamese', 'runcrew')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'runcrew'),
					"desc" => wp_kses_data( __('Put here your custom CSS and JS code', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'runcrew'),
					"desc" => wp_kses_data( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'runcrew'),
					"desc" => wp_kses_data( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'runcrew') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'runcrew'),
					"desc" => wp_kses_data( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'runcrew') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'runcrew'),
					"desc" => wp_kses_data( __('Put here your invisible html/js code: Google analitics, counters, etc',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'runcrew'),
					"desc" => wp_kses_data( __('Put here your css code to correct main theme styles',  'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'runcrew'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'runcrew'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Select desired blog streampage parameters (you can override it in each category)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'runcrew'),
					"desc" => wp_kses_data( __('Select desired blog style', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "excerpt",
					"options" => runcrew_get_options_param('list_blog_styles'),
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'runcrew'),
					"desc" => wp_kses_data( __('Select desired hover style (only for Blog style = Portfolio)', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => runcrew_get_options_param('list_hovers'),
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'runcrew'),
					"desc" => wp_kses_data( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => runcrew_get_options_param('list_hovers_dir'),
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'runcrew'),
					"desc" => wp_kses_data( __('Select article display method: boxed or stretch', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "stretch",
					"options" => runcrew_get_options_param('list_article_styles'),
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'runcrew'),
					"desc" => wp_kses_data( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "center",
					"options" => runcrew_get_options_param('list_locations'),
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'runcrew'),
					"desc" => wp_kses_data( __('What taxonomy use for filter buttons', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => runcrew_get_options_param('list_filters'),
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'runcrew'),
					"desc" => wp_kses_data( __('Select the desired sorting method for posts', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "date",
					"options" => runcrew_get_options_param('list_sorting'),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'runcrew'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "desc",
					"options" => runcrew_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'runcrew'),
					"desc" => wp_kses_data( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'runcrew'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'runcrew'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'runcrew'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'runcrew'),
					"desc" => wp_kses_data( __('Select desired style for single page', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "single-standard",
					"options" => runcrew_get_options_param('list_single_styles'),
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'runcrew'),
					"desc" => wp_kses_data( __('Select icon for output before post/category name in some layouts', 'runcrew') ),
					"override" => "services_group,post,page,custom",
					"std" => "",
					"options" => runcrew_get_options_param('list_icons'),
					"style" => "select",
					"type" => "icons"
					),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'runcrew'),
					"override" => "page,post",
					"desc" => wp_kses_data( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'runcrew') ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => runcrew_get_options_param('list_alter_sizes')
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'runcrew'),
					"desc" => wp_kses_data( __("Show featured image (if selected) before post content on single pages", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'runcrew'),
					"desc" => wp_kses_data( __('Show area with post title on single pages', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'runcrew'),
					"desc" => wp_kses_data( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'runcrew'),
					"desc" => wp_kses_data( __('Show area with post info on single pages', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'runcrew'),
					"desc" => wp_kses_data( __('Show text before "Read more" tag on single pages', 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'runcrew'),
					"desc" => wp_kses_data( __("Show post author information block on single post page", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'runcrew'),
					"desc" => wp_kses_data( __("Show tags block on single post page", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'runcrew'),
					"desc" => wp_kses_data( __("Show related posts block on single post page", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'runcrew'),
					"desc" => wp_kses_data( __("How many related posts showed on single post page", 'runcrew') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page,custom",
					"std" => "3",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'runcrew'),
					"desc" => wp_kses_data( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'runcrew'),
					"desc" => wp_kses_data( __('Select the desired sorting method for related posts', 'runcrew') ),
		//			"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => runcrew_get_options_param('list_sorting'),
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'runcrew'),
					"desc" => wp_kses_data( __('Select the desired ordering method for related posts', 'runcrew') ),
		//			"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => runcrew_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'runcrew'),
					"desc" => wp_kses_data( __("Show comments block on single post page", 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'runcrew'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Select excluded categories, substitute parameters, etc.', 'runcrew') ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'runcrew'),
					"desc" => wp_kses_data( __('Select categories, which posts are exclude from blog page', 'runcrew') ),
					"std" => "",
					"options" => runcrew_get_options_param('list_categories'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'runcrew'),
					"desc" => wp_kses_data( __('Select type of the pagination on blog streampages', 'runcrew') ),
					"std" => "pages",
					"override" => "category,services_group,page,custom",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'runcrew'),
						'slider'   => esc_html__('Slider with page numbers', 'runcrew'),
						'viewmore' => esc_html__('"View more" button', 'runcrew'),
						'infinite' => esc_html__('Infinite scroll', 'runcrew')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'runcrew'),
					"desc" => wp_kses_data( __('Select counters, displayed near the post title', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "views",
					"options" => runcrew_get_options_param('list_blog_counters'),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'runcrew'),
					"desc" => wp_kses_data( __('What category display in announce block (over posts thumb) - original or nearest parental', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'runcrew'),
						'original' => esc_html__("Original post's category", 'runcrew')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'runcrew'),
					"desc" => wp_kses_data( __('Show post date after N days (before - show post age)', 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'runcrew'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'runcrew'),
					"desc" => wp_kses_data( __('Set up list of reviews criterias. You can override it in any category.', 'runcrew') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'runcrew'),
					"desc" => wp_kses_data( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'runcrew') ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'runcrew'),
					"desc" => wp_kses_data( __("Maximum level for reviews marks", 'runcrew') ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'runcrew'), 
						'10'=>esc_html__('10 stars', 'runcrew'), 
						'100'=>esc_html__('100%', 'runcrew')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'runcrew'),
					"desc" => wp_kses_data( __("Show rating marks as text or as stars/progress bars.", 'runcrew') ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'runcrew'), 
						'stars' => esc_html__('As stars or bars', 'runcrew')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'runcrew'),
					"desc" => wp_kses_data( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'runcrew') ),
					"std" => esc_html__("bad,poor,normal,good,great", 'runcrew'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'runcrew'),
					"desc" => wp_kses_data( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'runcrew') ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'runcrew'),
						'users' => esc_html__('By visitors', 'runcrew')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'runcrew'),
					"desc" => wp_kses_data( __("Do you want hide second reviews tab in widgets and single posts?", 'runcrew') ),
					"std" => "show",
					"options" => runcrew_get_options_param('list_show_hide'),
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'runcrew'),
					"desc" => wp_kses_data( __("What visitors can vote: all or only registered", 'runcrew') ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'runcrew'), 
						'registered'=>esc_html__('Only registered', 'runcrew')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'runcrew'),
					"desc" => wp_kses_data( __('Add default reviews criterias.',  'runcrew') ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'runcrew'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'runcrew'),
					"desc" => wp_kses_data( __('Set up parameters to show images, galleries, audio and video posts', 'runcrew') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'runcrew'),
					"desc" => wp_kses_data( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'runcrew') ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'runcrew'), 
						"2" => esc_html__("Retina", 'runcrew')
					),
					"type" => "switch"),
		
		"images_quality" => array(
					"title" => esc_html__('Quality for cropped images', 'runcrew'),
					"desc" => wp_kses_data( __('Quality (1-100) to save cropped images', 'runcrew') ),
					"std" => "70",
					"min" => 1,
					"max" => 100,
					"type" => "spinner"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'runcrew'),
					"desc" => wp_kses_data( __('Substitute standard Wordpress gallery with our slider on the single pages', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'runcrew'),
					"desc" => wp_kses_data( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'runcrew'),
					"desc" => wp_kses_data( __('Maximum images number from gallery into slider', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'runcrew'),
					"desc" => wp_kses_data( __('Select engine to show popup windows with images and galleries', 'runcrew') ),
					"std" => "magnific",
					"options" => runcrew_get_options_param('list_popups'),
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'runcrew'),
					"desc" => wp_kses_data( __('Substitute audio tag with source from soundcloud to embed player', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'runcrew'),
					"desc" => wp_kses_data( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'runcrew') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'runcrew'),
					"desc" => wp_kses_data( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'runcrew'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page,custom",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'runcrew'),
					"desc" => wp_kses_data( __("Social networks list for site footer and Social widget", 'runcrew') ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'runcrew'),
					"desc" => wp_kses_data( __('Select icon and write URL to your profile in desired social networks.',  'runcrew') ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? runcrew_get_options_param('list_socials') : runcrew_get_options_param('list_icons'),
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'runcrew'),
					"desc" => wp_kses_data( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'runcrew'),
					"desc" => wp_kses_data( __("Show social share buttons block", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'runcrew'),
						'vertical'	=> esc_html__('Vertical', 'runcrew'),
						'horizontal'=> esc_html__('Horizontal', 'runcrew')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'runcrew'),
					"desc" => wp_kses_data( __("Show share counters after social buttons", 'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'runcrew'),
					"desc" => wp_kses_data( __('Caption for the block with social share buttons',  'runcrew') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'runcrew'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'runcrew'),
					"desc" => wp_kses_data( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'runcrew') ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? runcrew_get_options_param('list_socials') : runcrew_get_options_param('list_icons'),
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'runcrew'),
					"desc" => wp_kses_data( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'runcrew') ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'runcrew'),
					"desc" => wp_kses_data( __('Your login (username) in Twitter',  'runcrew') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'runcrew'),
					"desc" => wp_kses_data( __('Twitter API Consumer key',  'runcrew') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'runcrew'),
					"desc" => wp_kses_data( __('Twitter API Consumer secret',  'runcrew') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'runcrew'),
					"desc" => wp_kses_data( __('Twitter API Token key',  'runcrew') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'runcrew'),
					"desc" => wp_kses_data( __('Twitter API Token secret',  'runcrew') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'runcrew'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'runcrew'),
					"desc" => wp_kses_data( __('Company address, phones and e-mail', 'runcrew') ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'runcrew'),
					"desc" => wp_kses_data( __('String with contact info in the left side of the site header', 'runcrew') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'runcrew'),
					"desc" => wp_kses_data( __('E-mail for send contact form and user registration data', 'runcrew') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'runcrew'),
					"desc" => wp_kses_data( __('Company country, post code and city', 'runcrew') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'runcrew'),
					"desc" => wp_kses_data( __('Street and house number', 'runcrew') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'runcrew'),
					"desc" => wp_kses_data( __('Phone number', 'runcrew') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'runcrew'),
					"desc" => wp_kses_data( __('Fax number', 'runcrew') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'runcrew'),
					"desc" => wp_kses_data( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'runcrew') ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'runcrew'),
					"desc" => wp_kses_data( __("Message's maxlength in the contact form shortcode", 'runcrew') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'runcrew'),
					"desc" => wp_kses_data( __("Message's maxlength in the comments form", 'runcrew') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'runcrew'),
					"desc" => wp_kses_data( __('What function use to send mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'runcrew') ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'runcrew'),
					"desc" => wp_kses_data( __("What function use to send mail? Attention! Only wp_mail support attachment in the mail!", 'runcrew') ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'runcrew'),
						'mail' => esc_html__('PHP mail', 'runcrew')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'runcrew'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'runcrew'),
					"desc" => wp_kses_data( __('Enable/disable AJAX search and output settings for it', 'runcrew') ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'runcrew'),
					"desc" => wp_kses_data( __('Show search field in the top area and side menus', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'runcrew'),
					"desc" => wp_kses_data( __('Use incremental AJAX search for the search field in top of page', 'runcrew') ),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'runcrew'),
					"desc" => wp_kses_data( __('The minimum length of the search string',  'runcrew') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'runcrew'),
					"desc" => wp_kses_data( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'runcrew') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'runcrew'),
					"desc" => wp_kses_data( __('Select post types, what will be include in search results. If not selected - use all types.', 'runcrew') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => runcrew_get_options_param('list_posts_types'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'runcrew'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __('Number of the posts to show in search results',  'runcrew') ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'runcrew'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's thumbnail in the search results", 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'runcrew'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's publish date in the search results", 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'runcrew'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's author in the search results", 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'runcrew'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's counters (views, comments, likes) in the search results", 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'runcrew'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'runcrew'),
					"desc" => wp_kses_data( __('Basic theme functionality settings', 'runcrew') ),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => esc_html__('Notify about new registration', 'runcrew'),
					"desc" => wp_kses_data( __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'runcrew') ),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => esc_html__('No', 'runcrew'),
						'both'  => esc_html__('Both', 'runcrew'),
						'admin' => esc_html__('Admin', 'runcrew'),
						'user'  => esc_html__('User', 'runcrew')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'runcrew'),
					"desc" => wp_kses_data( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'runcrew'),
					"desc" => wp_kses_data( __("Allow authors to edit their posts in frontend area", 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'runcrew'),
					"desc" => wp_kses_data( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'runcrew'),
					"desc" => wp_kses_data( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'runcrew'),
					"desc" => wp_kses_data( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'runcrew'),
					"desc" => wp_kses_data( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'runcrew') ),
					"std" => "yes",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'runcrew'),
					"desc" => wp_kses_data( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'runcrew') ),
					"std" => 120,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'runcrew'),
					"desc" => wp_kses_data( __('Allow to use RunCrew Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => esc_html__('Enable PO Composer in the admin panel', 'runcrew'),
					"desc" => wp_kses_data( __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'runcrew'),
					"desc" => wp_kses_data( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utilities', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),


        "info_service_3" => array(
                    "title" => esc_html__('API Keys', 'runcrew'),
                    "desc" => wp_kses_data( __('API Keys for some Web services', 'runcrew') ),
                    "type" => "info"),

        'api_google' => array(
                    "title" => esc_html__('Google API Key', 'runcrew'),
                    "desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'runcrew') ),
                    "std" => "",
                    "type" => "text"),
		
		"info_service_2" => array(
					"title" => esc_html__('Wordpress cache', 'runcrew'),
					"desc" => wp_kses_data( __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'runcrew') ),
					"type" => "info"),
		
		"use_menu_cache" => array(
					"title" => esc_html__('Use menu cache', 'runcrew'),
					"desc" => wp_kses_data( __('Use cache for any menu (increase theme speed, decrease queries number). Attention! Please, clear cache after change permalink settings!', 'runcrew') ),
					"std" => "no",
					"options" => runcrew_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"clear_cache" => array(
					"title" => esc_html__('Clear cache', 'runcrew'),
					"desc" => wp_kses_data( __('Clear Wordpress cache data', 'runcrew') ),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button")
		));



		
		
		
		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		runcrew_storage_set_array('options', "custom_stylesheet_file", array(
			"title" => esc_html__('Custom stylesheet file', 'runcrew'),
			"desc" => wp_kses_data( __('Path to the custom stylesheet (stored in the uploads folder)', 'runcrew') ),
			"std" => "",
			"type" => "hidden"
			)
		);
		
		runcrew_storage_set_array('options', "custom_stylesheet_url", array(
			"title" => esc_html__('Custom stylesheet url', 'runcrew'),
			"desc" => wp_kses_data( __('URL to the custom stylesheet (stored in the uploads folder)', 'runcrew') ),
			"std" => "",
			"type" => "hidden"
			)
		);
		*/

	}
}


// Update all temporary vars (start with $runcrew_) in the Theme Options with actual lists
if ( !function_exists( 'runcrew_options_settings_theme_setup2' ) ) {
	add_action( 'runcrew_action_after_init_theme', 'runcrew_options_settings_theme_setup2', 1 );
	function runcrew_options_settings_theme_setup2() {
		if (runcrew_options_is_used()) {
			// Replace arrays with actual parameters
			$lists = array();
			$tmp = runcrew_storage_get('options');
			if (is_array($tmp) && count($tmp) > 0) {
				$prefix = '$runcrew_';
				$prefix_len = runcrew_strlen($prefix);
				foreach ($tmp as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (runcrew_substr($k1, 0, $prefix_len) == $prefix || runcrew_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = runcrew_substr(runcrew_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								unset($tmp[$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$tmp[$k]['options'] = runcrew_array_merge($tmp[$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$tmp[$k]['options'] = $lists[$list_func] = runcrew_array_merge($tmp[$k]['options'], $list_func == 'runcrew_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'runcrew'), $list_func));
								}
							}
						}
					}
				}
				runcrew_storage_set('options', $tmp);
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'runcrew_options_reset' ) ) {
	//add_action('after_switch_theme', 'runcrew_options_reset');
	function runcrew_options_reset($clear=true) {
		$theme_slug = str_replace(' ', '_', trim(runcrew_strtolower(get_stylesheet())));
		$option_name = runcrew_storage_get('options_prefix') . '_' . trim($theme_slug) . '_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "'.esc_sql(runcrew_storage_get('options_prefix')).'_%"');
				// Add Templates Options
				if (file_exists(runcrew_get_file_dir('demo/templates_options.txt'))) {
					$txt = runcrew_fgc(runcrew_get_file_dir('demo/templates_options.txt'));
					$data = runcrew_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = runcrew_replace_uploads_url(runcrew_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>