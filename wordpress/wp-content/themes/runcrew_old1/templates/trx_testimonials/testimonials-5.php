<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'runcrew_template_testimonials_5_theme_setup' ) ) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_template_testimonials_5_theme_setup', 1 );
	function runcrew_template_testimonials_5_theme_setup() {
		runcrew_add_template(array(
			'layout' => 'testimonials-5',
			'template' => 'testimonials-5',
			'mode'   => 'testimonials',
			/*'container_classes' => 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom',*/
			'title'  => esc_html__('Testimonials (Without author)', 'runcrew')
		));
	}
}

// Template output
if ( !function_exists( 'runcrew_template_testimonials_5_output' ) ) {
	function runcrew_template_testimonials_5_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (runcrew_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?> class="sc_testimonial_item<?php echo (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"<?php echo !empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '';?>>
				<div class="sc_testimonial_content"><?php echo trim($post_data['post_content']); ?></div>
			</div>
		<?php
		if (runcrew_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>