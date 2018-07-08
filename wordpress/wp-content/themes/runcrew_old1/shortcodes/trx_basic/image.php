<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('runcrew_sc_image_theme_setup')) {
    add_action( 'runcrew_action_before_init_theme', 'runcrew_sc_image_theme_setup' );
    function runcrew_sc_image_theme_setup() {
        add_action('runcrew_action_shortcodes_list', 		'runcrew_sc_image_reg_shortcodes');
        if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
            add_action('runcrew_action_shortcodes_list_vc','runcrew_sc_image_reg_shortcodes_vc');
    }
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('runcrew_sc_image')) {
    function runcrew_sc_image($atts, $content=null){
        if (runcrew_in_shortcode_blogger()) return '';
        extract(runcrew_html_decode(shortcode_atts(array(
            // Individual params
            "title" => "",
            "align" => "",
            "shape" => "square",
            "src" => "",
            "url" => "",
            "icon" => "",
            "link" => "",
            // Common params
            "id" => "",
            "class" => "",
            "animation" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => "",
            "width" => "",
            "height" => "",
            "popup" => ""
        ), $atts)));
        $class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);
        $css .= runcrew_get_css_dimensions_from_values($width, $height);
        $src = $src!='' ? $src : $url;
        if ($src > 0) {
            $attach = wp_get_attachment_image_src( $src, 'full' );
            if (isset($attach[0]) && $attach[0]!='')
                $src = $attach[0];
        }
        if (!empty($width) || !empty($height)) {
            $w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
            $h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
            if ($w || $h) $src = runcrew_get_resized_image_url($src, $w, $h);
        }
        if (trim($link)) runcrew_enqueue_popup();
        if (runcrew_param_is_on($popup)) runcrew_enqueue_popup('magnific');

        $output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
            . (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . '>'
            . (trim($link) ? '<a href="'.esc_url($link).'" '.(runcrew_param_is_on($popup) ? 'class="sc_popup_link"' : ''). '>' : '')
            . '<img src="'.esc_url($src).'" alt="" />'
            . (trim($link) ? (trim($title) || trim($icon) ? '<div class="figcaption"><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span>' . ($title) . '</div>' : '') : '')
            . (trim($link) ? '</a>' : '')
            . (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
            . '</figure>');
        return apply_filters('runcrew_shortcode_output', $output, 'trx_image', $atts, $content);
    }
    runcrew_require_shortcode('trx_image', 'runcrew_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_image_reg_shortcodes' ) ) {
    //add_action('runcrew_action_shortcodes_list', 'runcrew_sc_image_reg_shortcodes');
    function runcrew_sc_image_reg_shortcodes() {

        runcrew_sc_map("trx_image", array(
            "title" => esc_html__("Image", 'runcrew'),
            "desc" => wp_kses_data( __("Insert image into your post (page)", 'runcrew') ),
            "decorate" => false,
            "container" => false,
            "params" => array(
                "url" => array(
                    "title" => esc_html__("URL for image file", 'runcrew'),
                    "desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'runcrew') ),
                    "readonly" => false,
                    "value" => "",
                    "type" => "media",
                    "before" => array(
                        'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
                    )
                ),
                "title" => array(
                    "title" => esc_html__("Title", 'runcrew'),
                    "desc" => wp_kses_data( __("Image title (if need)", 'runcrew') ),
                    "value" => "",
                    "type" => "text"
                ),
                "icon" => array(
                    "title" => esc_html__("Icon before title",  'runcrew'),
                    "desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'runcrew') ),
                    "value" => "",
                    "type" => "icons",
                    "options" => runcrew_get_sc_param('icons')
                ),
                "align" => array(
                    "title" => esc_html__("Float image", 'runcrew'),
                    "desc" => wp_kses_data( __("Float image to left or right side", 'runcrew') ),
                    "value" => "",
                    "type" => "checklist",
                    "dir" => "horizontal",
                    "options" => runcrew_get_sc_param('float')
                ),
                "shape" => array(
                    "title" => esc_html__("Image Shape", 'runcrew'),
                    "desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", 'runcrew') ),
                    "value" => "square",
                    "type" => "checklist",
                    "dir" => "horizontal",
                    "options" => array(
                        "square" => esc_html__('Square', 'runcrew'),
                        "round" => esc_html__('Round', 'runcrew')
                    )
                ),
                "link" => array(
                    "title" => esc_html__("Link", 'runcrew'),
                    "desc" => wp_kses_data( __("The link URL from the image", 'runcrew') ),
                    "value" => "",
                    "type" => "text"
                ),
                "popup" => array(
                    "title" => esc_html__("Open link in popup", 'runcrew'),
                    "desc" => wp_kses_data( __("Open link target in popup window", 'runcrew') ),
                    "value" => "no",
                    "type" => "switch",
                    "options" => runcrew_get_sc_param('yes_no')
                ),
                "width" => runcrew_shortcodes_width(),
                "height" => runcrew_shortcodes_height(),
                "top" => runcrew_get_sc_param('top'),
                "bottom" => runcrew_get_sc_param('bottom'),
                "left" => runcrew_get_sc_param('left'),
                "right" => runcrew_get_sc_param('right'),
                "id" => runcrew_get_sc_param('id'),
                "class" => runcrew_get_sc_param('class'),
                "animation" => runcrew_get_sc_param('animation'),
                "css" => runcrew_get_sc_param('css')
            )
        ));
    }
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'runcrew_sc_image_reg_shortcodes_vc' ) ) {
    //add_action('runcrew_action_shortcodes_list_vc', 'runcrew_sc_image_reg_shortcodes_vc');
    function runcrew_sc_image_reg_shortcodes_vc() {

        vc_map( array(
            "base" => "trx_image",
            "name" => esc_html__("Image", 'runcrew'),
            "description" => wp_kses_data( __("Insert image", 'runcrew') ),
            "category" => esc_html__('Content', 'runcrew'),
            'icon' => 'icon_trx_image',
            "class" => "trx_sc_single trx_sc_image",
            "content_element" => true,
            "is_container" => false,
            "show_settings_on_create" => true,
            "params" => array(
                array(
                    "param_name" => "url",
                    "heading" => esc_html__("Select image", 'runcrew'),
                    "description" => wp_kses_data( __("Select image from library", 'runcrew') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "attach_image"
                ),
                array(
                    "param_name" => "align",
                    "heading" => esc_html__("Image alignment", 'runcrew'),
                    "description" => wp_kses_data( __("Align image to left or right side", 'runcrew') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => array_flip(runcrew_get_sc_param('float')),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "shape",
                    "heading" => esc_html__("Image shape", 'runcrew'),
                    "description" => wp_kses_data( __("Shape of the image: square or round", 'runcrew') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => array(
                        esc_html__('Square', 'runcrew') => 'square',
                        esc_html__('Round', 'runcrew') => 'round'
                    ),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "title",
                    "heading" => esc_html__("Title", 'runcrew'),
                    "description" => wp_kses_data( __("Image's title", 'runcrew') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "icon",
                    "heading" => esc_html__("Title's icon", 'runcrew'),
                    "description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'runcrew') ),
                    "class" => "",
                    "value" => runcrew_get_sc_param('icons'),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "link",
                    "heading" => esc_html__("Link", 'runcrew'),
                    "description" => wp_kses_data( __("The link URL from the image", 'runcrew') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "popup",
                    "heading" => esc_html__("Open link in popup", 'runcrew'),
                    "description" => wp_kses_data( __("Open link target in popup window", 'runcrew') ),
                    "class" => "",
                    "value" => array(esc_html__('Open in popup', 'runcrew') => 'yes'),
                    "type" => "checkbox"
                ),
                runcrew_get_vc_param('id'),
                runcrew_get_vc_param('class'),
                runcrew_get_vc_param('animation'),
                runcrew_get_vc_param('css'),
                runcrew_vc_width(),
                runcrew_vc_height(),
                runcrew_get_vc_param('margin_top'),
                runcrew_get_vc_param('margin_bottom'),
                runcrew_get_vc_param('margin_left'),
                runcrew_get_vc_param('margin_right')
            )
        ) );

        class WPBakeryShortCode_Trx_Image extends RUNCREW_VC_ShortCodeSingle {}
    }
}
?>