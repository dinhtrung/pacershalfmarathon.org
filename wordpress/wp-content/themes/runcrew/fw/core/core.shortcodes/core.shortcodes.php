<?php
/**
 * RunCrew Framework: shortcodes manipulations
 *
 * @package	runcrew
 * @since	runcrew 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('runcrew_sc_theme_setup')) {
	add_action( 'runcrew_action_init_theme', 'runcrew_sc_theme_setup', 1 );
	function runcrew_sc_theme_setup() {
		// Add sc stylesheets
		add_action('runcrew_action_add_styles', 'runcrew_sc_add_styles', 1);
	}
}

if (!function_exists('runcrew_sc_theme_setup2')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_theme_setup2' );
	function runcrew_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'runcrew_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('runcrew_sc_prepare_content')) runcrew_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('runcrew_shortcode_output', 'runcrew_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'runcrew_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'runcrew_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'runcrew_sc_selector_add_in_toolbar', 11);

	}
}


// Register shortcodes styles
if ( !function_exists( 'runcrew_sc_add_styles' ) ) {
	//add_action('runcrew_action_add_styles', 'runcrew_sc_add_styles', 1);
	function runcrew_sc_add_styles() {
		// Shortcodes
		runcrew_enqueue_style( 'runcrew-shortcodes-style',	runcrew_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Register shortcodes init scripts
if ( !function_exists( 'runcrew_sc_add_scripts' ) ) {
	//add_filter('runcrew_shortcode_output', 'runcrew_sc_add_scripts', 10, 4);
	function runcrew_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		if (runcrew_storage_empty('shortcodes_scripts_added')) {
			runcrew_storage_set('shortcodes_scripts_added', true);
			//runcrew_enqueue_style( 'runcrew-shortcodes-style', runcrew_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
			runcrew_enqueue_script( 'runcrew-shortcodes-script', runcrew_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );	
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('runcrew_sc_prepare_content')) {
	function runcrew_sc_prepare_content() {
		if (function_exists('runcrew_sc_clear_around')) {
			$filters = array(
				array('runcrew', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('runcrew_exists_woocommerce') && runcrew_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'runcrew_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('runcrew_sc_excerpt_shortcodes')) {
	function runcrew_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('runcrew_sc_clear_around')) {
	function runcrew_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// RunCrew shortcodes load scripts
if (!function_exists('runcrew_sc_load_scripts')) {
	function runcrew_sc_load_scripts() {
		runcrew_enqueue_script( 'runcrew-shortcodes_admin-script', runcrew_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		runcrew_enqueue_script( 'runcrew-selection-script',  runcrew_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
		wp_localize_script( 'runcrew-shortcodes_admin-script', 'RUNCREW_SHORTCODES_DATA', runcrew_storage_get('shortcodes') );
	}
}

// RunCrew shortcodes prepare scripts
if (!function_exists('runcrew_sc_prepare_scripts')) {
	function runcrew_sc_prepare_scripts() {
		if (!runcrew_storage_isset('shortcodes_prepared')) {
			runcrew_storage_set('shortcodes_prepared', true);
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					RUNCREW_STORAGE['shortcodes_cp'] = '<?php echo is_admin() ? (!runcrew_storage_empty('to_colorpicker') ? runcrew_storage_get('to_colorpicker') : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('runcrew_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','runcrew_sc_selector_add_in_toolbar', 11);
	function runcrew_sc_selector_add_in_toolbar(){

		if ( !runcrew_options_is_used() ) return;

		runcrew_sc_load_scripts();
		runcrew_sc_prepare_scripts();

		$shortcodes = runcrew_storage_get('shortcodes');
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'runcrew').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo trim($shortcodes_list);
	}
}

// RunCrew shortcodes builder settings
get_template_part(runcrew_get_file_slug('core/core.shortcodes/shortcodes_settings.php'));

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	get_template_part(runcrew_get_file_slug('core/core.shortcodes/shortcodes_vc.php'));
}

// RunCrew shortcodes implementation
runcrew_autoload_folder( 'shortcodes/trx_basic' );
runcrew_autoload_folder( 'shortcodes/trx_optional' );
?>