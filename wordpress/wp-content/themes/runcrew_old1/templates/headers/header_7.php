<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'runcrew_template_header_7_theme_setup' ) ) {
    add_action( 'runcrew_action_before_init_theme', 'runcrew_template_header_7_theme_setup', 1 );
    function runcrew_template_header_7_theme_setup() {
        runcrew_add_template(array(
            'layout' => 'header_7',
            'mode'   => 'header',
            'title'  => esc_html__('Header 7', 'runcrew'),
            'icon'   => runcrew_get_file_url('templates/headers/images/7.jpg'),
            'thumb_title'  => esc_html__('Original image', 'runcrew'),
            'w'		 => null,
            'h_crop' => null,
            'h'      => null
        ));
    }
}

// Template output
if ( !function_exists( 'runcrew_template_header_7_output' ) ) {
    function runcrew_template_header_7_output($post_options, $post_data) {

        // Get custom image (for blog) or featured image (for single)
        $header_css = '';
        if (empty($header_image))
            $header_image = runcrew_get_custom_option('top_panel_image');
        if (empty($header_image))
            $header_image = get_header_image();
        if (!empty($header_image)) {
            $header_css = ' style="background-image: url('.esc_url($header_image).')"';
        }
        ?>

        <div class="top_panel_fixed_wrap"></div>

        <header class="top_panel_wrap top_panel_style_7 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
            <div class="top_panel_wrap_inner top_panel_inner_style_7 top_panel_position_<?php echo esc_attr(runcrew_get_custom_option('top_panel_position')); ?>">

                <div class="top_panel_middle">
                    <div class="content_wrap">
                        <div class="column-1_4 contact_logo">
                            <?php runcrew_show_logo(true, true); ?>
                        </div>
                        <div class="column-3_4 menu_main_wrap">
                            <nav class="menu_main_nav_area">
                                <?php
                                $menu_main = runcrew_get_nav_menu('menu_main');
                                if (empty($menu_main)) $menu_main = runcrew_get_nav_menu();
                                echo trim($menu_main);
                                ?>
                            </nav>
                        </div>
                    </div>
                </div>


            </div>
        </header>

        <section class="top_panel_image" <?php echo trim($header_css); ?>>
            <div class="top_panel_image_hover"></div>
            <div class="top_panel_image_header">
                <h2 itemprop="headline" class="top_panel_image_title entry-title"><?php echo strip_tags(runcrew_get_blog_title()); ?></h2>
                <div class="breadcrumbs">
                    <?php if (!is_404()) runcrew_show_breadcrumbs(); ?>
                </div>
            </div>
        </section>
        <?php
        runcrew_storage_set('header_mobile', array(
                'open_hours' => false,
                'login' => false,
                'socials' => false,
                'bookmarks' => false,
                'contact_address' => false,
                'contact_phone_email' => false,
                'woo_cart' => true,
                'search' => true
            )
        );
    }
}
?>