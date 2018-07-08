<?php
/* HTML5 jQuery Audio Player support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('runcrew_html5_jquery_audio_player_theme_setup')) {
    add_action( 'runcrew_action_before_init_theme', 'runcrew_html5_jquery_audio_player_theme_setup' );
    function runcrew_html5_jquery_audio_player_theme_setup() {
        // Add shortcode in the shortcodes list
        if (runcrew_exists_html5_jquery_audio_player()) {
			add_action('runcrew_action_add_styles',					'runcrew_html5_jquery_audio_player_frontend_scripts' );
            add_action('runcrew_action_shortcodes_list',				'runcrew_html5_jquery_audio_player_reg_shortcodes');
			if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
	            add_action('runcrew_action_shortcodes_list_vc',		'runcrew_html5_jquery_audio_player_reg_shortcodes_vc');
            if (is_admin()) {
                add_filter( 'runcrew_filter_importer_options',			'runcrew_html5_jquery_audio_player_importer_set_options', 10, 1 );
                add_action( 'runcrew_action_importer_params',			'runcrew_html5_jquery_audio_player_importer_show_params', 10, 1 );
                add_action( 'runcrew_action_importer_import',			'runcrew_html5_jquery_audio_player_importer_import', 10, 2 );
				add_action( 'runcrew_action_importer_import_fields',	'runcrew_html5_jquery_audio_player_importer_import_fields', 10, 1 );
                add_action( 'runcrew_action_importer_export',			'runcrew_html5_jquery_audio_player_importer_export', 10, 1 );
                add_action( 'runcrew_action_importer_export_fields',	'runcrew_html5_jquery_audio_player_importer_export_fields', 10, 1 );
            }
        }
        if (is_admin()) {
            add_filter( 'runcrew_filter_importer_required_plugins',	'runcrew_html5_jquery_audio_player_importer_required_plugins', 10, 2 );
            add_filter( 'runcrew_filter_required_plugins',				'runcrew_html5_jquery_audio_player_required_plugins' );
        }
    }
}

// Check if plugin installed and activated
if ( !function_exists( 'runcrew_exists_html5_jquery_audio_player' ) ) {
	function runcrew_exists_html5_jquery_audio_player() {
		return function_exists('hmp_db_create');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'runcrew_html5_jquery_audio_player_required_plugins' ) ) {
	//add_filter('runcrew_filter_required_plugins',	'runcrew_html5_jquery_audio_player_required_plugins');
        function runcrew_html5_jquery_audio_player_required_plugins($list=array()) {
            if (in_array('html5_jquery_audio_player', runcrew_storage_get('required_plugins')))
                $list[] = array(
                'name' => 'HTML5 jQuery Audio Player',
                'slug' => 'html5-jquery-audio-player',
                'required' => false
            );
		return $list;
	    }
}

// Enqueue custom styles
if ( !function_exists( 'runcrew_html5_jquery_audio_player_frontend_scripts' ) ) {
	//add_action( 'runcrew_action_add_styles', 'runcrew_html5_jquery_audio_player_frontend_scripts' );
	function runcrew_html5_jquery_audio_player_frontend_scripts() {
		if (file_exists(runcrew_get_file_dir('css/plugin.html5-jquery-audio-player.css'))) {
			runcrew_enqueue_style( 'runcrew-plugin.html5-jquery-audio-player-style',  runcrew_get_file_url('css/plugin.html5-jquery-audio-player.css'), array(), null );
		}
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check HTML5 jQuery Audio Player in the required plugins
if ( !function_exists( 'runcrew_html5_jquery_audio_player_importer_required_plugins' ) ) {
	//add_filter( 'runcrew_filter_importer_required_plugins',	'runcrew_html5_jquery_audio_player_importer_required_plugins', 10, 2 );
	function runcrew_html5_jquery_audio_player_importer_required_plugins($not_installed='', $list=null) {
		//if ($importer && in_array('html5_jquery_audio_player', $importer->options['required_plugins']) && !runcrew_exists_html5_jquery_audio_player() )
		if (runcrew_strpos($list, 'html5_jquery_audio_player')!==false && !runcrew_exists_html5_jquery_audio_player() )
			$not_installed .= '<br>HTML5 jQuery Audio Player';
		return $not_installed;
	}
}


// Set options for one-click importer
if ( !function_exists( 'runcrew_html5_jquery_audio_player_importer_set_options' ) ) {
    //add_filter( 'runcrew_filter_importer_options',	'runcrew_html5_jquery_audio_player_importer_set_options', 10, 1 );
    function runcrew_html5_jquery_audio_player_importer_set_options($options=array()) {
		if ( in_array('html5_jquery_audio_player', runcrew_storage_get('required_plugins')) && runcrew_exists_html5_jquery_audio_player() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_html5_jquery_audio_player'] = str_replace('posts', 'html5_jquery_audio_player', $v['file_with_posts']);
				}
			}
			// Add option's slugs to export options for this plugin
            $options['additional_options'][] = 'showbuy';
            $options['additional_options'][] = 'buy_text';
            $options['additional_options'][] = 'showlist';
            $options['additional_options'][] = 'autoplay';
            $options['additional_options'][] = 'tracks';
            $options['additional_options'][] = 'currency';
            $options['additional_options'][] = 'color';
            $options['additional_options'][] = 'tcolor';
        }
        return $options;
    }
}

// Add checkbox to the one-click importer
if ( !function_exists( 'runcrew_html5_jquery_audio_player_importer_show_params' ) ) {
    //add_action( 'runcrew_action_importer_params',	'runcrew_html5_jquery_audio_player_importer_show_params', 10, 1 );
    function runcrew_html5_jquery_audio_player_importer_show_params($importer) {
        ?>
        <input type="checkbox" <?php echo in_array('html5_jquery_audio_player', runcrew_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_html5_jquery_audio_player" id="import_html5_jquery_audio_player" /> <label for="import_html5_jquery_audio_player"><?php esc_html_e('Import HTML5 jQuery Audio Player', 'runcrew'); ?></label><br>
    <?php
    }
}


// Import posts
if ( !function_exists( 'runcrew_html5_jquery_audio_player_importer_import' ) ) {
    //add_action( 'runcrew_action_importer_import',	'runcrew_html5_jquery_audio_player_importer_import', 10, 2 );
    function runcrew_html5_jquery_audio_player_importer_import($importer, $action) {
		if ( $action == 'import_html5_jquery_audio_player' ) {
            $importer->response['result'] = $importer->import_dump('html5_jquery_audio_player', esc_html__('HTML5 jQuery Audio Player', 'runcrew'));
        }
    }
}

// Display import progress
if ( !function_exists( 'runcrew_html5_jquery_audio_player_importer_import_fields' ) ) {
	//add_action( 'runcrew_action_importer_import_fields',	'runcrew_html5_jquery_audio_player_importer_import_fields', 10, 1 );
	function runcrew_html5_jquery_audio_player_importer_import_fields($importer) {
		?>
		<tr class="import_html5_jquery_audio_player">
			<td class="import_progress_item"><?php esc_html_e('HTML5 jQuery Audio Player', 'runcrew'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}


// Export posts
if ( !function_exists( 'runcrew_html5_jquery_audio_player_importer_export' ) ) {
    //add_action( 'runcrew_action_importer_export',	'runcrew_html5_jquery_audio_player_importer_export', 10, 1 );
    function runcrew_html5_jquery_audio_player_importer_export($importer) {
		runcrew_storage_set('export_html5_jquery_audio_player', serialize( array(
			'hmp_playlist'	=> $importer->export_dump('hmp_playlist'),
			'hmp_rating'	=> $importer->export_dump('hmp_rating')
			) )
		);
    }
}


// Display exported data in the fields
if ( !function_exists( 'runcrew_html5_jquery_audio_player_importer_export_fields' ) ) {
    //add_action( 'runcrew_action_importer_export_fields',	'runcrew_html5_jquery_audio_player_importer_export_fields', 10, 1 );
    function runcrew_html5_jquery_audio_player_importer_export_fields($importer) {
        ?>
        <tr>
            <th align="left"><?php esc_html_e('HTML5 jQuery Audio Player', 'runcrew'); ?></th>
            <td><?php runcrew_fpc(runcrew_get_file_dir('core/core.importer/export/html5_jquery_audio_player.txt'), runcrew_storage_get('export_html5_jquery_audio_player')); ?>
                <a download="html5_jquery_audio_player.txt" href="<?php echo esc_url(runcrew_get_file_url('core/core.importer/export/html5_jquery_audio_player.txt')); ?>"><?php esc_html_e('Download', 'runcrew'); ?></a>
            </td>
        </tr>
    <?php
    }
}





// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('runcrew_html5_jquery_audio_player_reg_shortcodes')) {
    //add_filter('runcrew_action_shortcodes_list',	'runcrew_html5_jquery_audio_player_reg_shortcodes');
    function runcrew_html5_jquery_audio_player_reg_shortcodes() {
		if (runcrew_storage_isset('shortcodes')) {
			runcrew_sc_map_after('trx_audio', 'hmp_player', array(
                "title" => esc_html__("HTML5 jQuery Audio Player", 'runcrew'),
                "desc" => esc_html__("Insert HTML5 jQuery Audio Player", 'runcrew'),
                "decorate" => true,
                "container" => false,
				"params" => array()
				)
            );
        }
    }
}


// Register shortcode in the VC shortcodes list
if (!function_exists('runcrew_hmp_player_reg_shortcodes_vc')) {
    add_filter('runcrew_action_shortcodes_list_vc',	'runcrew_hmp_player_reg_shortcodes_vc');
    function runcrew_hmp_player_reg_shortcodes_vc() {

        // RunCrew HTML5 jQuery Audio Player
        vc_map( array(
            "base" => "hmp_player",
            "name" => esc_html__("HTML5 jQuery Audio Player", 'runcrew'),
            "description" => esc_html__("Insert HTML5 jQuery Audio Player", 'runcrew'),
            "category" => esc_html__('Content', 'runcrew'),
            'icon' => 'icon_trx_audio',
            "class" => "trx_sc_single trx_sc_hmp_player",
            "content_element" => true,
            "is_container" => false,
            "show_settings_on_create" => false,
            "params" => array()
        ) );

        class WPBakeryShortCode_Hmp_Player extends RUNCREW_VC_ShortCodeSingle {}

    }
}
?>