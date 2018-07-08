<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'runcrew_template_services_5_theme_setup' ) ) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_template_services_5_theme_setup', 1 );
	function runcrew_template_services_5_theme_setup() {
		runcrew_add_template(array(
			'layout' => 'services-5',
			'template' => 'services-5',
			'mode'   => 'services',
			'need_columns' => true,
			'title'  => esc_html__('Services /Style 5/', 'runcrew'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'runcrew'),
			'w'		 => 370,
			'h'		 => 370
		));
	}
}

// Template output
if ( !function_exists( 'runcrew_template_services_5_output' ) ) {
	function runcrew_template_services_5_output($post_options, $post_data) {
		$show_title = !empty($post_data['post_title']);
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (runcrew_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_services_item sc_services_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!runcrew_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>

				<div class="sc_services_item_content">
					<?php
					if ($show_title) {
						if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
							?><h4 class="sc_services_item_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h4><?php
						} else {
							?><h4 class="sc_services_item_title"><?php echo trim($post_data['post_title']); ?></h4><?php
						}
					}
					?>

					<div class="sc_services_item_description">
						<?php
						if ($post_data['post_protected']) {
							echo trim($post_data['post_excerpt']); 
						} else {
							if ($post_data['post_excerpt']) {
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(runcrew_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : runcrew_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
							}
						}
						?>
					</div>
				</div>
			</div>
		<?php
		if (runcrew_param_is_on($post_options['slider'])) {
			?></div></div><?php
		} else if ($columns > 1) {
			?></div><?php
		}
	}
}
?>