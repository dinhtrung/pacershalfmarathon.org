<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'runcrew_template_events_4_theme_setup' ) ) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_template_events_4_theme_setup', 1 );
	function runcrew_template_events_4_theme_setup() {
		runcrew_add_template(array(
			'layout' => 'events-4',
			'template' => 'events-4',
			'mode'   => 'events',
			'title'  => esc_html__('Events /Style 4/', 'runcrew')
		));
	}
}

// Template output
if ( !function_exists( 'runcrew_template_events_4_output' ) ) {
	function runcrew_template_events_4_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		$start_date = explode('|', tribe_get_start_date(null, true, 'm,d|'.get_option('time_format')));
        $end_date = explode('|', tribe_get_end_date(null, true, 'm,d|'.get_option('time_format')));
        $sd = explode(',', $start_date[0]);
        $address = tribe_get_address();
        if (tribe_event_is_all_day()) $start_date[1] = $end_date[1] = '';
        ?>
		<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
			class="sc_events_item sc_events_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
			<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
				. (!runcrew_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>
			>
            <div class="column-left">
                <span class="sc_events_item_date"><?php echo trim($sd[1]) . '/' . trim($sd[0]); ?></span>
                <span class="sc_events_item_time">
                    <?php
                        echo (trim($start_date[1]) ? $start_date[1] : esc_html__('Whole day', 'runcrew'))
                        . ($start_date[0]==$end_date[0] && trim($start_date[1]) && trim($end_date[1]) ? ' - ' . $end_date[1] : '');
                    ?>
                </span>
            </div>
            <div class="column-center">
                <?php
                if ($show_title) {
                    if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
                        ?><h4 class="sc_events_item_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h4><?php
                    } else {
                        ?><h4 class="sc_events_item_title"><?php echo trim($post_data['post_title']); ?></h4><?php
                    }
                }
			    ?>
                <span class="sc_events_item_location">
                    <?php echo (esc_html__('LOCATION: ', 'runcrew') . trim($address)); ?>
                </span>
            </div>
            <div class="column-right">
                <span class="sc_events_item_details"><?php
                    if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
                        echo trim(runcrew_sc_button(array('link'=>$post_data['post_link']), $post_options['readmore']));
                    } ?>
                </span>
            </div>
        </div>
		<?php
	}
}
?>