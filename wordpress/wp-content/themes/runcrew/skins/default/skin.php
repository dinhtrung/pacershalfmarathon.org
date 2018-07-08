<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('runcrew_action_skin_theme_setup')) {
	add_action( 'runcrew_action_init_theme', 'runcrew_action_skin_theme_setup', 1 );
	function runcrew_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('runcrew_filter_used_fonts',			'runcrew_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('runcrew_filter_list_fonts',			'runcrew_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('runcrew_action_add_styles',			'runcrew_action_skin_add_styles');
		// Add skin inline styles
		add_filter('runcrew_filter_add_styles_inline',		'runcrew_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('runcrew_action_add_responsive',		'runcrew_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('runcrew_filter_add_responsive_inline',	'runcrew_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('runcrew_action_add_scripts',			'runcrew_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('runcrew_action_add_scripts_inline',	'runcrew_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('runcrew_filter_compile_less',			'runcrew_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		runcrew_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'runcrew'),

			// Accent colors
			'accent1'				=> '#0db496',
			'accent1_hover'			=> '#0db496',
			
			// Headers, text and links colors
			'text'					=> '#888585',
			'text_light'			=> '#888585',
			'text_dark'				=> '#3c3838',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#f3f3f3',
			'bg_color'				=> '#f3f3f3',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#4f4949',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#d0d0d0',
			'alter_bd_hover'		=> '#3c3838',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#383737',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);


		// Add color schemes
		runcrew_add_color_scheme('scheme_2', array(

			'title'					=> esc_html__('Scheme 2', 'runcrew'),

			// Accent colors
			'accent1'				=> '#fcbf60',
			'accent1_hover'			=> '#fcbf60',

			// Headers, text and links colors
			'text'					=> '#888585',
			'text_light'			=> '#888585',
			'text_dark'				=> '#3c3838',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',

			// Whole block border and background
			'bd_color'				=> '#f3f3f3',
			'bg_color'				=> '#f3f3f3',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',

			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#4f4949',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#d0d0d0',
			'alter_bd_hover'		=> '#3c3838',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#383737',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);


		// Add color schemes
		runcrew_add_color_scheme('scheme_3', array(

			'title'					=> esc_html__('Scheme 3', 'runcrew'),

			// Accent colors
			'accent1'				=> '#69d7ee',
			'accent1_hover'			=> '#69d7ee',

			// Headers, text and links colors
			'text'					=> '#888585',
			'text_light'			=> '#888585',
			'text_dark'				=> '#3c3838',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',

			// Whole block border and background
			'bd_color'				=> '#f3f3f3',
			'bg_color'				=> '#f3f3f3',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',

			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#4f4949',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#d0d0d0',
			'alter_bd_hover'		=> '#3c3838',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#383737',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);


		// Add color schemes
		runcrew_add_color_scheme('scheme_4', array(

			'title'					=> esc_html__('Scheme 4', 'runcrew'),

			// Accent colors
			'accent1'				=> '#fa6f6f',
			'accent1_hover'			=> '#fa6f6f',

			// Headers, text and links colors
			'text'					=> '#888585',
			'text_light'			=> '#888585',
			'text_dark'				=> '#3c3838',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',

			// Whole block border and background
			'bd_color'				=> '#f3f3f3',
			'bg_color'				=> '#f3f3f3',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',

			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#4f4949',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#d0d0d0',
			'alter_bd_hover'		=> '#3c3838',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#383737',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);


		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

        // Add Custom fonts
        runcrew_add_custom_font('p', array(
                'title'			=> esc_html__('Text', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Montserrat',
                'font-size' 	=> '15px',
                'font-weight'	=> '400',
                'font-style'	=> '',
                'line-height'	=> '1.75em',
                'margin-top'	=> '',
                'margin-bottom'	=> '1em'
            )
        );
        runcrew_add_custom_font('h1', array(
                'title'			=> esc_html__('Heading 1', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Oswald',
                'font-size' 	=> '6rem',
                'font-weight'	=> '700',
                'font-style'	=> '',
                'line-height'	=> '1.1em',
                'margin-top'	=> '',
                'margin-bottom'	=> ''
            )
        );
        runcrew_add_custom_font('h2', array(
                'title'			=> esc_html__('Heading 2', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Oswald',
                'font-size' 	=> '4.667rem',
                'font-weight'	=> '700',
                'font-style'	=> '',
                'line-height'	=> '1.1em',
                'margin-top'	=> '',
                'margin-bottom'	=> ''
            )
        );
        runcrew_add_custom_font('h3', array(
                'title'			=> esc_html__('Heading 3', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Oswald',
                'font-size' 	=> '3.333rem',
                'font-weight'	=> '700',
                'font-style'	=> '',
                'line-height'	=> '1.1em',
                'margin-top'	=> '',
                'margin-bottom'	=> ''
            )
        );
        runcrew_add_custom_font('h4', array(
                'title'			=> esc_html__('Heading 4', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Oswald',
                'font-size' 	=> '1.867rem',
                'font-weight'	=> '700',
                'font-style'	=> '',
                'line-height'	=> '1.1em',
                'margin-top'	=> '',
                'margin-bottom'	=> ''
            )
        );
        runcrew_add_custom_font('h5', array(
                'title'			=> esc_html__('Heading 5', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Oswald',
                'font-size' 	=> '1.333rem',
                'font-weight'	=> '700',
                'font-style'	=> '',
                'line-height'	=> '1.1em',
                'margin-top'	=> '',
                'margin-bottom'	=> ''
            )
        );
        runcrew_add_custom_font('h6', array(
                'title'			=> esc_html__('Heading 6', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Montserrat',
                'font-size' 	=> '0.867rem',
                'font-weight'	=> '400',
                'font-style'	=> '',
                'line-height'	=> '1.3em',
                'margin-top'	=> '',
                'margin-bottom'	=> ''
            )
        );
        runcrew_add_custom_font('logo', array(
                'title'			=> esc_html__('Logo', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Oswald',
                'font-size' 	=> '2rem',
                'font-weight'	=> '700',
                'font-style'	=> '',
                'line-height'	=> '0.8em'
            )
        );
        runcrew_add_custom_font('menu', array(
                'title'			=> esc_html__('Main menu items', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Montserrat',
                'font-size' 	=> '1rem',
                'font-weight'	=> '400',
                'font-style'	=> '',
                'line-height'	=> '0.9em'
            )
        );
        runcrew_add_custom_font('submenu', array(
                'title'			=> esc_html__('Dropdown menu items', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Montserrat',
                'font-size' 	=> '1rem',
                'font-weight'	=> '400',
                'font-style'	=> '',
                'line-height'	=> '0.9em'
            )
        );
        runcrew_add_custom_font('link', array(
                'title'			=> esc_html__('Links', 'runcrew'),
                'description'	=> '',
                'font-family'	=> ''
            )
        );
        runcrew_add_custom_font('info', array(
                'title'			=> esc_html__('Post info', 'runcrew'),
                'description'	=> '',
                'font-family'	=> ''
            )
        );
        runcrew_add_custom_font('button', array(
                'title'			=> esc_html__('Buttons', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Oswald'
            )
        );
        runcrew_add_custom_font('input', array(
                'title'			=> esc_html__('Input fields', 'runcrew'),
                'description'	=> '',
                'font-family'	=> 'Montserrat'
            )
        );

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('runcrew_filter_skin_used_fonts')) {
	//add_filter('runcrew_filter_used_fonts', 'runcrew_filter_skin_used_fonts');
	function runcrew_filter_skin_used_fonts($theme_fonts) {
        $theme_fonts['Oswald'] = 1;
        $theme_fonts['Montserrat'] = 1;
        return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('runcrew_filter_skin_list_fonts')) {
	//add_filter('runcrew_filter_list_fonts', 'runcrew_filter_skin_list_fonts');
	function runcrew_filter_skin_list_fonts($list) {
        if (!isset($list['Oswald']))	        $list['Oswald'] = array('family'=>'sans-serif', 'link'=>'Oswald');
        if (!isset($list['Montserrat']))	    $list['Montserrat'] = array('family'=>'sans-serif', 'link'=>'Montserrat:400,700');
        return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('runcrew_action_skin_add_styles')) {
	//add_action('runcrew_action_add_styles', 'runcrew_action_skin_add_styles');
	function runcrew_action_skin_add_styles() {
		// Add stylesheet files
		runcrew_enqueue_style( 'runcrew-skin-style', runcrew_get_file_url('skin.css'), array(), null );
		if (file_exists(runcrew_get_file_dir('skin.customizer.css')))
			runcrew_enqueue_style( 'runcrew-skin-customizer-style', runcrew_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('runcrew_filter_skin_add_styles_inline')) {
	//add_filter('runcrew_filter_add_styles_inline', 'runcrew_filter_skin_add_styles_inline');
	function runcrew_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = runcrew_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = runcrew_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('runcrew_action_skin_add_responsive')) {
	//add_action('runcrew_action_add_responsive', 'runcrew_action_skin_add_responsive');
	function runcrew_action_skin_add_responsive() {
		$suffix = runcrew_param_is_off(runcrew_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(runcrew_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			runcrew_enqueue_style( 'theme-skin-responsive-style', runcrew_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('runcrew_filter_skin_add_responsive_inline')) {
	//add_filter('runcrew_filter_add_responsive_inline', 'runcrew_filter_skin_add_responsive_inline');
	function runcrew_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('runcrew_filter_skin_compile_less')) {
	//add_filter('runcrew_filter_compile_less', 'runcrew_filter_skin_compile_less');
	function runcrew_filter_skin_compile_less($files) {
		if (file_exists(runcrew_get_file_dir('skin.less'))) {
		 	$files[] = runcrew_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('runcrew_action_skin_add_scripts')) {
	//add_action('runcrew_action_add_scripts', 'runcrew_action_skin_add_scripts');
	function runcrew_action_skin_add_scripts() {
		if (file_exists(runcrew_get_file_dir('skin.js')))
			runcrew_enqueue_script( 'theme-skin-script', runcrew_get_file_url('skin.js'), array(), null );
		if (runcrew_get_theme_option('show_theme_customizer') == 'yes' && file_exists(runcrew_get_file_dir('skin.customizer.js')))
			runcrew_enqueue_script( 'theme-skin-customizer-script', runcrew_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('runcrew_action_skin_add_scripts_inline')) {
	//add_action('runcrew_action_add_scripts_inline', 'runcrew_action_skin_add_scripts_inline');
	function runcrew_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
		// Example:
		// echo '<script type="text/javascript">'
		//	. 'jQuery(document).ready(function() {'
		//	. "if (RUNCREW_STORAGE['theme_font']=='') RUNCREW_STORAGE['theme_font'] = '" . runcrew_get_custom_font_settings('p', 'font-family') . "';"
		//	. "RUNCREW_STORAGE['theme_skin_color'] = '" . runcrew_get_scheme_color('accent1') . "';"
		//	. "});"
		//	. "< /script>";
	}
}
?>