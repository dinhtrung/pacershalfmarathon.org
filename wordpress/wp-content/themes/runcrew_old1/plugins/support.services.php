<?php
/**
 * RunCrew Framework: Services support
 *
 * @package	runcrew
 * @since	runcrew 1.0
 */

// Theme init
if (!function_exists('runcrew_services_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_services_theme_setup',1 );
	function runcrew_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('runcrew_filter_get_blog_type',			'runcrew_services_get_blog_type', 9, 2);
		add_filter('runcrew_filter_get_blog_title',		'runcrew_services_get_blog_title', 9, 2);
		add_filter('runcrew_filter_get_current_taxonomy',	'runcrew_services_get_current_taxonomy', 9, 2);
		add_filter('runcrew_filter_is_taxonomy',			'runcrew_services_is_taxonomy', 9, 2);
		add_filter('runcrew_filter_get_stream_page_title',	'runcrew_services_get_stream_page_title', 9, 2);
		add_filter('runcrew_filter_get_stream_page_link',	'runcrew_services_get_stream_page_link', 9, 2);
		add_filter('runcrew_filter_get_stream_page_id',	'runcrew_services_get_stream_page_id', 9, 2);
		add_filter('runcrew_filter_query_add_filters',		'runcrew_services_query_add_filters', 9, 2);
		add_filter('runcrew_filter_detect_inheritance_key','runcrew_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (runcrew_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'runcrew_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'runcrew_post_fill_options_column', 9, 2);
		}

		// Register shortcodes [trx_services] and [trx_services_item]
		add_action('runcrew_action_shortcodes_list',		'runcrew_services_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_services_reg_shortcodes_vc');
		
		// Add supported data types
		runcrew_theme_support_pt('services');
		runcrew_theme_support_tx('services_group');
	}
}

if ( !function_exists( 'runcrew_services_settings_theme_setup2' ) ) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_services_settings_theme_setup2', 3 );
	function runcrew_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		runcrew_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-service',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'custom'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'runcrew_is_services_page' ) ) {
	function runcrew_is_services_page() {
		$is = in_array(runcrew_storage_get('page_template'), array('blog-services', 'single-service'));
		if (!$is) {
			if (!runcrew_storage_empty('pre_query'))
				$is = runcrew_storage_call_obj_method('pre_query', 'get', 'post_type')=='services' 
						|| runcrew_storage_call_obj_method('pre_query', 'is_tax', 'services_group') 
						|| (runcrew_storage_call_obj_method('pre_query', 'is_page') 
								&& ($id=runcrew_get_template_page_id('blog-services')) > 0 
								&& $id==runcrew_storage_get_obj_property('pre_query', 'queried_object_id', 0) 
							);
			else
				$is = get_query_var('post_type')=='services' 
						|| is_tax('services_group') 
						|| (is_page() && ($id=runcrew_get_template_page_id('blog-services')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'runcrew_services_detect_inheritance_key' ) ) {
	//add_filter('runcrew_filter_detect_inheritance_key',	'runcrew_services_detect_inheritance_key', 9, 1);
	function runcrew_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return runcrew_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'runcrew_services_get_blog_type' ) ) {
	//add_filter('runcrew_filter_get_blog_type',	'runcrew_services_get_blog_type', 9, 2);
	function runcrew_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'runcrew_services_get_blog_title' ) ) {
	//add_filter('runcrew_filter_get_blog_title',	'runcrew_services_get_blog_title', 9, 2);
	function runcrew_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( runcrew_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = runcrew_get_post_title();
			} else {
				$title = esc_html__('All services', 'runcrew');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'runcrew_services_get_stream_page_title' ) ) {
	//add_filter('runcrew_filter_get_stream_page_title',	'runcrew_services_get_stream_page_title', 9, 2);
	function runcrew_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (runcrew_strpos($page, 'services')!==false) {
			if (($page_id = runcrew_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = runcrew_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'runcrew');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'runcrew_services_get_stream_page_id' ) ) {
	//add_filter('runcrew_filter_get_stream_page_id',	'runcrew_services_get_stream_page_id', 9, 2);
	function runcrew_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (runcrew_strpos($page, 'services')!==false) $id = runcrew_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'runcrew_services_get_stream_page_link' ) ) {
	//add_filter('runcrew_filter_get_stream_page_link',	'runcrew_services_get_stream_page_link', 9, 2);
	function runcrew_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (runcrew_strpos($page, 'services')!==false) {
			$id = runcrew_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'runcrew_services_get_current_taxonomy' ) ) {
	//add_filter('runcrew_filter_get_current_taxonomy',	'runcrew_services_get_current_taxonomy', 9, 2);
	function runcrew_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( runcrew_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'runcrew_services_is_taxonomy' ) ) {
	//add_filter('runcrew_filter_is_taxonomy',	'runcrew_services_is_taxonomy', 9, 2);
	function runcrew_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'runcrew_services_query_add_filters' ) ) {
	//add_filter('runcrew_filter_query_add_filters',	'runcrew_services_query_add_filters', 9, 2);
	function runcrew_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

/*
[trx_services id="unique_id" columns="4" count="4" style="services-1|services-2|..." title="Block title" subtitle="xxx" description="xxxxxx"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
[/trx_services]
*/
if ( !function_exists( 'runcrew_sc_services' ) ) {
	function runcrew_sc_services($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => esc_html__('Learn more', 'runcrew'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'runcrew'),
			"link" => '',
			"scheme" => '',
			"image" => '',
			"image_align" => '',
            "popup" => "no",
            "link_popup" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (runcrew_param_is_off($slider) && $columns > 1 && $style == 'services-5' && !empty($image)) $columns = 2;
		if (!empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
		}

		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && runcrew_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = runcrew_get_css_dimensions_from_values($width);
		$hs = runcrew_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$columns = max(1, min(12, (int) $columns));
		$count = max(1, (int) $count);
		if (runcrew_param_is_off($custom) && $count < $columns) $columns = $count;

		if (runcrew_param_is_on($slider)) runcrew_enqueue_slider('swiper');

		runcrew_storage_set('sc_services_data', array(
			'id' => $id,
            'style' => $style,
            'type' => $type,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs,
            'readmore' => $readmore
            )
        );

        if (runcrew_param_is_on($popup)) runcrew_enqueue_popup('magnific');

        $output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
						. ' class="sc_services_wrap'
						. ($scheme && !runcrew_param_is_off($scheme) && !runcrew_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(runcrew_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(runcrew_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title">' . trim(runcrew_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(runcrew_strmacros($description)) . '</div>' : '')
					. (runcrew_param_is_on($slider) 
						? ('<div class="sc_slider_swiper swiper-slider-container'
										. ' ' . esc_attr(runcrew_get_slider_controls_classes($controls))
										. (runcrew_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && runcrew_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && runcrew_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
									. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
									. ' data-slides-min-width="250"'
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 
							? ($style == 'services-5' && !empty($image) 
								? '<div class="sc_service_container sc_align_'.esc_attr($image_align).'">'
									. '<div class="sc_services_image">'
									. ((runcrew_param_is_on($popup) && !empty($link_popup))
                                        ? '<a href="'.esc_url($link_popup).'" class="sc_popup_link"><img src="'.esc_url($image).'" alt=""></a>'
                                        : '<img src="'.esc_url($image).'" alt="">')
									. '</div>'
								: '')
								. '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		$content = do_shortcode($content);
	
		if (runcrew_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = runcrew_query_add_sort_order($args, $orderby, $order);
			$args = runcrew_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
			
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => runcrew_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= runcrew_show_post_layout($args);
			}
			wp_reset_postdata();
		}
	
		if (runcrew_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
			if ($style == 'services-5' && !empty($image))
				$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.runcrew_do_shortcode('[trx_button link="'.esc_url($link).'" size="large"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';
	
		// Add template specific scripts and styles
		do_action('runcrew_action_blog_scripts', $style);
	
		return apply_filters('runcrew_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	runcrew_require_shortcode('trx_services', 'runcrew_sc_services');
}


if ( !function_exists( 'runcrew_sc_services_item' ) ) {
	function runcrew_sc_services_item($atts, $content=null) {
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		runcrew_storage_inc_array('sc_services_data', 'counter');

		$id = $id ? $id : (runcrew_storage_get_array('sc_services_data', 'id') ? runcrew_storage_get_array('sc_services_data', 'id') . '_' . runcrew_storage_get_array('sc_services_data', 'counter') : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? runcrew_storage_get_array('sc_services_data', 'readmore') : $readmore;

		$type = runcrew_storage_get_array('sc_services_data', 'type');
		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = runcrew_get_thumb_sizes(array('layout' => runcrew_storage_get_array('sc_services_data', 'style')));
			$image = runcrew_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => runcrew_storage_get_array('sc_services_data', 'style'),
			'number' => runcrew_storage_get_array('sc_services_data', 'counter'),
			'columns_count' => runcrew_storage_get_array('sc_services_data', 'columns'),
			'slider' => runcrew_storage_get_array('sc_services_data', 'slider'),
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => runcrew_storage_get_array('sc_services_data', 'css_wh')
		);
		$output = runcrew_show_post_layout($args, $post_data);
		return apply_filters('runcrew_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	runcrew_require_shortcode('trx_services_item', 'runcrew_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('runcrew_services_reg_shortcodes')) {
	//add_filter('runcrew_action_shortcodes_list',	'runcrew_services_reg_shortcodes');
	function runcrew_services_reg_shortcodes() {
		if (runcrew_storage_isset('shortcodes')) {

			$services_groups = runcrew_get_list_terms(false, 'services_group');
			$services_styles = runcrew_get_list_templates('services');
			$controls 		 = runcrew_get_list_slider_controls();

			runcrew_sc_map_after('trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", 'runcrew'),
					"desc" => wp_kses_data( __("Insert services list in your page (post)", 'runcrew') ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'runcrew'),
							"desc" => wp_kses_data( __("Title for the block", 'runcrew') ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'runcrew'),
							"desc" => wp_kses_data( __("Subtitle for the block", 'runcrew') ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'runcrew'),
							"desc" => wp_kses_data( __("Short description for the block", 'runcrew') ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Services style", 'runcrew'),
							"desc" => wp_kses_data( __("Select style to display services list", 'runcrew') ),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"image" => array(
								"title" => esc_html__("Item's image", 'runcrew'),
								"desc" => wp_kses_data( __("Item's image", 'runcrew') ),
								"dependency" => array(
									'style' => 'services-5'
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
						),
						"image_align" => array(
							"title" => esc_html__("Image alignment", 'runcrew'),
							"desc" => wp_kses_data( __("Alignment of the image", 'runcrew') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => runcrew_get_sc_param('align')
						),
                        "popup" => array(
                            "title" => esc_html__("Open link in popup", 'runcrew'),
                            "desc" => wp_kses_data( __("Open link target in popup window", 'runcrew') ),
                            "dependency" => array(
                                'style' => array('services-5')
                            ),
                            "value" => "no",
                            "type" => "switch",
                            "options" => runcrew_get_sc_param('yes_no')
                        ),
                        "link_popup" => array(
                            "title" => esc_html__("Link URL for Popup", 'runcrew'),
                            "desc" => wp_kses_data( __("URL for link on image click", 'runcrew') ),
                            "divider" => true,
                            "dependency" => array(
                                'style' => array('services-5')
                            ),
                            "value" => "",
                            "type" => "text"
                        ),
                        "type" => array(
							"title" => esc_html__("Icon's type", 'runcrew'),
							"desc" => wp_kses_data( __("Select type of icons: font icon or image", 'runcrew') ),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'runcrew'),
								'images' => esc_html__('Images', 'runcrew')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'runcrew'),
							"desc" => wp_kses_data( __("How many columns use to show services list", 'runcrew') ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'runcrew'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
							"value" => "",
							"type" => "checklist",
							"options" => runcrew_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'runcrew'),
							"desc" => wp_kses_data( __("Use slider to show services", 'runcrew') ),
							"value" => "no",
							"type" => "switch",
							"options" => runcrew_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'runcrew'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'runcrew') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'runcrew'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'runcrew') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'runcrew'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'runcrew') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'runcrew'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'runcrew') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => runcrew_get_sc_param('yes_no')
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'runcrew'),
							"desc" => wp_kses_data( __("Alignment of the services block", 'runcrew') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => runcrew_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'runcrew'),
							"desc" => wp_kses_data( __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", 'runcrew') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => runcrew_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'runcrew'),
							"desc" => wp_kses_data( __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", 'runcrew') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => runcrew_array_merge(array(0 => esc_html__('- Select category -', 'runcrew')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'runcrew'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'runcrew') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'runcrew'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'runcrew') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'runcrew'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'runcrew') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => runcrew_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'runcrew'),
							"desc" => wp_kses_data( __("Select desired posts order", 'runcrew') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => runcrew_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'runcrew'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'runcrew') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"readmore" => array(
							"title" => esc_html__("Read more", 'runcrew'),
							"desc" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'runcrew') ),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'runcrew'),
							"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'runcrew') ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'runcrew'),
							"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'runcrew') ),
							"value" => "",
							"type" => "text"
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
					),
					"children" => array(
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", 'runcrew'),
						"desc" => wp_kses_data( __("Service item", 'runcrew') ),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", 'runcrew'),
								"desc" => wp_kses_data( __("Item's title", 'runcrew') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon",  'runcrew'),
								"desc" => wp_kses_data( __('Select icon for the item from Fontello icons set',  'runcrew') ),
								"value" => "",
								"type" => "icons",
								"options" => runcrew_get_sc_param('icons')
							),
							"image" => array(
								"title" => esc_html__("Item's image", 'runcrew'),
								"desc" => wp_kses_data( __("Item's image (if icon not selected)", 'runcrew') ),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", 'runcrew'),
								"desc" => wp_kses_data( __("Link on service's item page", 'runcrew') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", 'runcrew'),
								"desc" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'runcrew') ),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'runcrew'),
								"desc" => wp_kses_data( __("Item's short description", 'runcrew') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => runcrew_get_sc_param('id'),
							"class" => runcrew_get_sc_param('class'),
							"animation" => runcrew_get_sc_param('animation'),
							"css" => runcrew_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('runcrew_services_reg_shortcodes_vc')) {
	//add_filter('runcrew_action_shortcodes_list_vc',	'runcrew_services_reg_shortcodes_vc');
	function runcrew_services_reg_shortcodes_vc() {

		$services_groups = runcrew_get_list_terms(false, 'services_group');
		$services_styles = runcrew_get_list_templates('services');
		$controls		 = runcrew_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => esc_html__("Services", 'runcrew'),
				"description" => wp_kses_data( __("Insert services list", 'runcrew') ),
				"category" => esc_html__('Content', 'runcrew'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Services style", 'runcrew'),
						"description" => wp_kses_data( __("Select style to display services list", 'runcrew') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", 'runcrew'),
						"description" => wp_kses_data( __("Select type of icons: font icon or image", 'runcrew') ),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Icons', 'runcrew') => 'icons',
							esc_html__('Images', 'runcrew') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'runcrew'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
						"class" => "",
						"value" => array_flip(runcrew_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'runcrew'),
						"description" => wp_kses_data( __("Item's image", 'runcrew') ),
						'dependency' => array(
							'element' => 'style',
							'value' => 'services-5'
						),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_align",
						"heading" => esc_html__("Image alignment", 'runcrew'),
						"description" => wp_kses_data( __("Alignment of the image", 'runcrew') ),
						"class" => "",
						"value" => array_flip(runcrew_get_sc_param('align')),
						"type" => "dropdown"
					),
                    array(
                        "param_name" => "popup",
                        "heading" => esc_html__("Open link in popup", 'runcrew'),
                        "description" => wp_kses_data( __("Open link target in popup window", 'runcrew') ),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('services-5')
                        ),
                        "class" => "",
                        "group" => esc_html__('Popup', 'runcrew'),
                        "value" => array(esc_html__('Open in popup', 'runcrew') => 'yes'),
                        "type" => "checkbox"
                    ),
                    array(
                        "param_name" => "link_popup",
                        "heading" => esc_html__("Link URL for Popup", 'runcrew'),
                        "description" => wp_kses_data( __("URL for the link on image click", 'runcrew') ),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('services-5')
                        ),
                        "class" => "",
                        "group" => esc_html__('Popup', 'runcrew'),
                        "value" => "",
                        "type" => "textfield"
                    ),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'runcrew'),
						"description" => wp_kses_data( __("Use slider to show services", 'runcrew') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'runcrew'),
						"class" => "",
						"std" => "no",
						"value" => array_flip(runcrew_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'runcrew'),
						"description" => wp_kses_data( __("Slider controls style and position", 'runcrew') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'runcrew'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'runcrew'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'runcrew') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'runcrew'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'runcrew'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'runcrew') ),
						"group" => esc_html__('Slider', 'runcrew'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'runcrew'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'runcrew') ),
						"group" => esc_html__('Slider', 'runcrew'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'runcrew'),
						"description" => wp_kses_data( __("Alignment of the services block", 'runcrew') ),
						"class" => "",
						"value" => array_flip(runcrew_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'runcrew'),
						"description" => wp_kses_data( __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", 'runcrew') ),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'runcrew'),
						"description" => wp_kses_data( __("Title for the block", 'runcrew') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'runcrew'),
						"description" => wp_kses_data( __("Subtitle for the block", 'runcrew') ),
						"group" => esc_html__('Captions', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'runcrew'),
						"description" => wp_kses_data( __("Description for the block", 'runcrew') ),
						"group" => esc_html__('Captions', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'runcrew'),
						"description" => wp_kses_data( __("Select category to show services. If empty - select services from any category (group) or from IDs list", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(runcrew_array_merge(array(0 => esc_html__('- Select category -', 'runcrew')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'runcrew'),
						"description" => wp_kses_data( __("How many columns use to show services list", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'runcrew'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'runcrew') ),
						"admin_label" => true,
						"group" => esc_html__('Query', 'runcrew'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'runcrew'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'runcrew'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "date",
						"class" => "",
						"value" => array_flip(runcrew_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'runcrew'),
						"description" => wp_kses_data( __("Select desired posts order", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "desc",
						"class" => "",
						"value" => array_flip(runcrew_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Service's IDs list", 'runcrew'),
						"description" => wp_kses_data( __("Comma separated list of service's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'runcrew'),
						"description" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'runcrew') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'runcrew'),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'runcrew') ),
						"group" => esc_html__('Captions', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'runcrew'),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'runcrew') ),
						"group" => esc_html__('Captions', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					runcrew_vc_width(),
					runcrew_vc_height(),
					runcrew_get_vc_param('margin_top'),
					runcrew_get_vc_param('margin_bottom'),
					runcrew_get_vc_param('margin_left'),
					runcrew_get_vc_param('margin_right'),
					runcrew_get_vc_param('id'),
					runcrew_get_vc_param('class'),
					runcrew_get_vc_param('animation'),
					runcrew_get_vc_param('css')
				),
				'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'runcrew' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'runcrew' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'runcrew' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'runcrew' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_services_item",
				"name" => esc_html__("Services item", 'runcrew'),
				"description" => wp_kses_data( __("Custom services item - all data pull out from shortcode parameters", 'runcrew') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'runcrew'),
						"description" => wp_kses_data( __("Item's title", 'runcrew') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'runcrew'),
						"description" => wp_kses_data( __("Select icon for the item from Fontello icons set", 'runcrew') ),
						"admin_label" => true,
						"class" => "",
						"value" => runcrew_get_sc_param('icons'),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'runcrew'),
						"description" => wp_kses_data( __("Item's image (if icon is empty)", 'runcrew') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'runcrew'),
						"description" => wp_kses_data( __("Link on item's page", 'runcrew') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'runcrew'),
						"description" => wp_kses_data( __("Caption for the Read more link (if empty - link not showed)", 'runcrew') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					runcrew_get_vc_param('id'),
					runcrew_get_vc_param('class'),
					runcrew_get_vc_param('animation'),
					runcrew_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Services extends RUNCREW_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends RUNCREW_VC_ShortCodeCollection {}

	}
}
?>