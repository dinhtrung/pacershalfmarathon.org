<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'runcrew_template_form_2_theme_setup' ) ) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_template_form_2_theme_setup', 1 );
	function runcrew_template_form_2_theme_setup() {
		runcrew_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'runcrew')
			));
	}
}

// Template output
if ( !function_exists( 'runcrew_template_form_2_output' ) ) {
	function runcrew_template_form_2_output($post_options, $post_data) {
        $title = trim(runcrew_storage_get('title_form_2'));
        $subtitle = trim(runcrew_storage_get('subtitle_form_2'));
        $description = trim(runcrew_storage_get('description_form_2'));
        
		$address_1 = runcrew_get_theme_option('contact_address_1');
		$address_2 = runcrew_get_theme_option('contact_address_2');
		$phone = runcrew_get_theme_option('contact_phone');
		$fax = runcrew_get_theme_option('contact_fax');
		$email = runcrew_get_theme_option('contact_email');
		?>
		<div class="sc_columns">
            <div class="sc_form_fields column-left">
                <?php  if (!empty($subtitle)) { ?><h6 class="sc_form_subtitle sc_item_subtitle"><?php echo trim($subtitle) ?></h6><?php }?>
                <?php  if (!empty($title)) { ?><h2 class="sc_form_title sc_item_title"><?php echo trim($title) ?></h2><?php }?>
                <?php  if (!empty($description)){ ?><div class="sc_form_descr sc_item_descr"><?php echo trim($description) ?></div><?php }?>
                <form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
					<?php runcrew_sc_form_show_fields($post_options['fields']); ?>
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'runcrew'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'runcrew'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'runcrew'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'runcrew'); ?>"></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'runcrew'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'runcrew'); ?>"></textarea></div>
					<div class="sc_form_item sc_form_button"><button class="sc_button sc_button_round sc_button_style_filled sc_button_size_large"><?php esc_html_e('Send Message', 'runcrew'); ?></button></div>
					<div class="result sc_infobox"></div>
				</form>
			</div>
            <div class="sc_form_address column-right">
                <?php  if (!empty($subtitle)) { ?><h6  class="sc_form_subtitle sc_item_subtitle"><?php echo esc_html_e('Contact Info', 'runcrew'); ?></h6><?php }?>
                <?php  if (!empty($title)) { ?><h2 class="sc_form_title sc_item_title"><?php echo esc_html_e('Find Us', 'runcrew'); ?></h2><?php }?>
                <div class="sc_form_address_field">
                    <span class="sc_form_address_label"><?php esc_html_e('Location:', 'runcrew'); ?></span>
                    <span class="sc_form_address_data"><?php echo trim($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
                </div>
                <div class="sc_form_address_field">
                    <span class="sc_form_address_label"><?php esc_html_e('Phone:', 'runcrew'); ?></span>
                    <span class="sc_form_address_data"><?php echo trim($phone) . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax; ?></span>
                </div>
                <div class="sc_form_address_field">
                    <span class="sc_form_address_label"><?php esc_html_e('E-mail:', 'runcrew'); ?></span>
                    <span class="sc_form_address_data"><?php echo trim($email); ?></span>
                </div>
            </div>
		</div>
		<?php
	}
}
?>