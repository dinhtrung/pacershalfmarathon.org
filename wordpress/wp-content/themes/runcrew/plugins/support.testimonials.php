<?php
/**
 * RunCrew Framework: Testimonial support
 *
 * @package	runcrew
 * @since	runcrew 1.0
 */

// Theme init
if (!function_exists('runcrew_testimonial_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_testimonial_theme_setup', 1 );
	function runcrew_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_action('add_meta_boxes',		'runcrew_testimonial_add_meta_box');

		// Save data from meta box
		add_action('save_post',				'runcrew_testimonial_save_data');

		// Register shortcodes [trx_testimonials] and [trx_testimonials_item]
		add_action('runcrew_action_shortcodes_list',		'runcrew_testimonials_reg_shortcodes');
		if (function_exists('runcrew_exists_visual_composer') && runcrew_exists_visual_composer())
			add_action('runcrew_action_shortcodes_list_vc','runcrew_testimonials_reg_shortcodes_vc');

		// Meta box fields
		runcrew_storage_set('testimonial_meta_box', array(
			'id' => 'testimonial-meta-box',
			'title' => esc_html__('Testimonial Details', 'runcrew'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'runcrew'),
					"desc" => wp_kses_data( __("Name of the testimonial's author", 'runcrew') ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'runcrew'),
					"desc" => wp_kses_data( __("Position of the testimonial's author", 'runcrew') ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'runcrew'),
					"desc" => wp_kses_data( __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'runcrew') ),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'runcrew'),
					"desc" => wp_kses_data( __("URL of the testimonial source or author profile page", 'runcrew') ),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
				)
			)
		);
		
		// Add supported data types
		runcrew_theme_support_pt('testimonial');
		runcrew_theme_support_tx('testimonial_group');
		
	}
}


// Add meta box
if (!function_exists('runcrew_testimonial_add_meta_box')) {
	//add_action('add_meta_boxes', 'runcrew_testimonial_add_meta_box');
	function runcrew_testimonial_add_meta_box() {
		$mb = runcrew_storage_get('testimonial_meta_box');
		add_meta_box($mb['id'], $mb['title'], 'runcrew_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('runcrew_testimonial_show_meta_box')) {
	function runcrew_testimonial_show_meta_box() {
		global $post;

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_testimonial_nonce" value="'.esc_attr(wp_create_nonce(admin_url())).'" />';
		
		$data = get_post_meta($post->ID, 'runcrew_testimonial_data', true);
	
		$fields = runcrew_storage_get_array('testimonial_meta_box', 'fields');
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('runcrew_testimonial_save_data')) {
	//add_action('save_post', 'runcrew_testimonial_save_data');
	function runcrew_testimonial_save_data($post_id) {
		// verify nonce
		if ( !wp_verify_nonce( runcrew_get_value_gp('meta_box_testimonial_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = runcrew_storage_get_array('testimonial_meta_box', 'fields');

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, 'runcrew_testimonial_data', $data);
	}
}






// ---------------------------------- [trx_testimonials] ---------------------------------------

/*
[trx_testimonials id="unique_id" style="1|2|3"]
	[trx_testimonials_item user="user_login"]Testimonials text[/trx_testimonials_item]
	[trx_testimonials_item email="" name="" position="" photo="photo_url"]Testimonials text[/trx_testimonials]
[/trx_testimonials]
*/

if (!function_exists('runcrew_sc_testimonials')) {
	function runcrew_sc_testimonials($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "testimonials-1",
			"columns" => 1,
			"slider" => "yes",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
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
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && runcrew_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = runcrew_get_scheme_color('bg');
			$rgb = runcrew_hex2rgb($bg_color);
		}
		
		$class .= ($class ? ' ' : '') . runcrew_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = runcrew_get_css_dimensions_from_values($width);
		$hs = runcrew_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (runcrew_param_is_off($custom) && $count < $columns) $columns = $count;
		
		runcrew_storage_set('sc_testimonials_data', array(
			'id' => $id,
            'style' => $style,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs
            )
        );

		if (runcrew_param_is_on($slider)) runcrew_enqueue_slider('swiper');
	
		$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || runcrew_strlen($bg_texture)>2 || ($scheme && !runcrew_param_is_off($scheme) && !runcrew_param_is_inherit($scheme))
					? '<div class="sc_testimonials_wrap sc_section'
							. ($scheme && !runcrew_param_is_off($scheme) && !runcrew_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
							. '"'
						.' style="'
							. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
							. '"'
						. (!runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
						. '>'
						. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
								. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
									. (runcrew_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
									. '"'
									. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
									. '>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
 					. ' ' . esc_attr(runcrew_get_template_property($style, 'container_classes'))
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. '"'
				. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !runcrew_param_is_off($animation) ? ' data-animation="'.esc_attr(runcrew_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(runcrew_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title">' . trim(runcrew_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(runcrew_strmacros($description)) . '</div>' : '')
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
					? '<div class="sc_columns columns_wrap">' 
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
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = runcrew_query_add_sort_order($args, $orderby, $order);
			$args = runcrew_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');
	
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
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = runcrew_get_post_data($args);
				$post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
				$post_meta = get_post_meta($post_data['post_id'], 'runcrew_testimonial_data', true);
				$thumb_sizes = runcrew_get_thumb_sizes(array('layout' => $style));
				$args['author'] = $post_meta['testimonial_author'];
				$args['position'] = $post_meta['testimonial_position'];
				$args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';	//$post_data['post_link'];
				$args['email'] = $post_meta['testimonial_email'];
				$args['photo'] = $post_data['post_thumb'];
				$mult = runcrew_get_retina_multiplier();
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*$mult);
				$output .= runcrew_show_post_layout($args, $post_data);
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
		}

		$output .= '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || runcrew_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
	
		// Add template specific scripts and styles
		do_action('runcrew_action_blog_scripts', $style);

		return apply_filters('runcrew_shortcode_output', $output, 'trx_testimonials', $atts, $content);
	}
	runcrew_require_shortcode('trx_testimonials', 'runcrew_sc_testimonials');
}
	
	
if (!function_exists('runcrew_sc_testimonials_item')) {
	function runcrew_sc_testimonials_item($atts, $content=null){	
		if (runcrew_in_shortcode_blogger()) return '';
		extract(runcrew_html_decode(shortcode_atts(array(
			// Individual params
			"author" => "",
			"position" => "",
			"link" => "",
			"photo" => "",
			"email" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
		), $atts)));

		runcrew_storage_inc_array('sc_testimonials_data', 'counter');
	
		$id = $id ? $id : (runcrew_storage_get_array('sc_testimonials_data', 'id') ? runcrew_storage_get_array('sc_testimonials_data', 'id') . '_' . runcrew_storage_get_array('sc_testimonials_data', 'counter') : '');
	
		$thumb_sizes = runcrew_get_thumb_sizes(array('layout' => runcrew_storage_get_array('sc_testimonials_data', 'style')));

		if (empty($photo)) {
			if (!empty($email))
				$mult = '';
				$mult = runcrew_get_retina_multiplier();
				$photo = get_avatar($email, $thumb_sizes['w']*$mult);
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = runcrew_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_content' => do_shortcode($content)
		);
		$args = array(
			'layout' => runcrew_storage_get_array('sc_testimonials_data', 'style'),
			'number' => runcrew_storage_get_array('sc_testimonials_data', 'counter'),
			'columns_count' => runcrew_storage_get_array('sc_testimonials_data', 'columns'),
			'slider' => runcrew_storage_get_array('sc_testimonials_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => '',
			'tag_css' => $css,
			'tag_css_wh' => runcrew_storage_get_array('sc_testimonials_data', 'css_wh'),
			'author' => $author,
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo
		);
		$output = runcrew_show_post_layout($args, $post_data);

		return apply_filters('runcrew_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
	}
	runcrew_require_shortcode('trx_testimonials_item', 'runcrew_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------



// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('runcrew_testimonials_reg_shortcodes')) {
	//add_filter('runcrew_action_shortcodes_list',	'runcrew_testimonials_reg_shortcodes');
	function runcrew_testimonials_reg_shortcodes() {
		if (runcrew_storage_isset('shortcodes')) {

			$testimonials_groups = runcrew_get_list_terms(false, 'testimonial_group');
			$testimonials_styles = runcrew_get_list_templates('testimonials');
			$controls = runcrew_get_list_slider_controls();

			runcrew_sc_map_before('trx_title', array(
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", 'runcrew'),
					"desc" => wp_kses_data( __("Insert testimonials into post (page)", 'runcrew') ),
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
							"title" => esc_html__("Testimonials style", 'runcrew'),
							"desc" => wp_kses_data( __("Select style to display testimonials", 'runcrew') ),
							"value" => "testimonials-1",
							"type" => "select",
							"options" => $testimonials_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'runcrew'),
							"desc" => wp_kses_data( __("How many columns use to show testimonials", 'runcrew') ),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'runcrew'),
							"desc" => wp_kses_data( __("Use slider to show testimonials", 'runcrew') ),
							"value" => "yes",
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
							"desc" => wp_kses_data( __("Alignment of the testimonials block", 'runcrew') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => runcrew_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'runcrew'),
							"desc" => wp_kses_data( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'runcrew') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => runcrew_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'runcrew'),
							"desc" => wp_kses_data( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'runcrew') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => runcrew_array_merge(array(0 => esc_html__('- Select category -', 'runcrew')), $testimonials_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'runcrew'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'runcrew') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
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
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'runcrew'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
							"value" => "",
							"type" => "checklist",
							"options" => runcrew_get_sc_param('schemes')
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'runcrew'),
							"desc" => wp_kses_data( __("Any background color for this section", 'runcrew') ),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", 'runcrew'),
							"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'runcrew') ),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'runcrew'),
							"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'runcrew') ),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'runcrew'),
							"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'runcrew') ),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
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
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", 'runcrew'),
						"desc" => wp_kses_data( __("Testimonials item (custom parameters)", 'runcrew') ),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", 'runcrew'),
								"desc" => wp_kses_data( __("Name of the testimonmials author", 'runcrew') ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'runcrew'),
								"desc" => wp_kses_data( __("Link URL to the testimonmials author page", 'runcrew') ),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'runcrew'),
								"desc" => wp_kses_data( __("E-mail of the testimonmials author (to get gravatar)", 'runcrew') ),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'runcrew'),
								"desc" => wp_kses_data( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'runcrew') ),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", 'runcrew'),
								"desc" => wp_kses_data( __("Current testimonials text", 'runcrew') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => runcrew_get_sc_param('id'),
							"class" => runcrew_get_sc_param('class'),
							"css" => runcrew_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('runcrew_testimonials_reg_shortcodes_vc')) {
	//add_filter('runcrew_action_shortcodes_list_vc',	'runcrew_testimonials_reg_shortcodes_vc');
	function runcrew_testimonials_reg_shortcodes_vc() {

		$testimonials_groups = runcrew_get_list_terms(false, 'testimonial_group');
		$testimonials_styles = runcrew_get_list_templates('testimonials');
		$controls			 = runcrew_get_list_slider_controls();
			
		// Testimonials			
		vc_map( array(
				"base" => "trx_testimonials",
				"name" => esc_html__("Testimonials", 'runcrew'),
				"description" => wp_kses_data( __("Insert testimonials slider", 'runcrew') ),
				"category" => esc_html__('Content', 'runcrew'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_columns trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Testimonials style", 'runcrew'),
						"description" => wp_kses_data( __("Select style to display testimonials", 'runcrew') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($testimonials_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'runcrew'),
						"description" => wp_kses_data( __("Use slider to show testimonials", 'runcrew') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'runcrew'),
						"class" => "",
						"std" => "yes",
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
						"description" => wp_kses_data( __("Alignment of the testimonials block", 'runcrew') ),
						"class" => "",
						"value" => array_flip(runcrew_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'runcrew'),
						"description" => wp_kses_data( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'runcrew') ),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
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
						"description" => wp_kses_data( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(runcrew_array_merge(array(0 => esc_html__('- Select category -', 'runcrew')), $testimonials_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'runcrew'),
						"description" => wp_kses_data( __("How many columns use to show testimonials", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'runcrew'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'runcrew') ),
						"group" => esc_html__('Query', 'runcrew'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
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
						"heading" => esc_html__("Post IDs list", 'runcrew'),
						"description" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'runcrew') ),
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
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'runcrew'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'runcrew') ),
						"group" => esc_html__('Colors and Images', 'runcrew'),
						"class" => "",
						"value" => array_flip(runcrew_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'runcrew'),
						"description" => wp_kses_data( __("Any background color for this section", 'runcrew') ),
						"group" => esc_html__('Colors and Images', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", 'runcrew'),
						"description" => wp_kses_data( __("Select background image from library for this section", 'runcrew') ),
						"group" => esc_html__('Colors and Images', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'runcrew'),
						"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'runcrew') ),
						"group" => esc_html__('Colors and Images', 'runcrew'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", 'runcrew'),
						"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'runcrew') ),
						"group" => esc_html__('Colors and Images', 'runcrew'),
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
				'js_view' => 'VcTrxColumnsView'
		) );
			
			
		vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => esc_html__("Testimonial", 'runcrew'),
				"description" => wp_kses_data( __("Single testimonials item", 'runcrew') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", 'runcrew'),
						"description" => wp_kses_data( __("Name of the testimonmials author", 'runcrew') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'runcrew'),
						"description" => wp_kses_data( __("Link URL to the testimonmials author page", 'runcrew') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", 'runcrew'),
						"description" => wp_kses_data( __("E-mail of the testimonmials author", 'runcrew') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Photo", 'runcrew'),
						"description" => wp_kses_data( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'runcrew') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Testimonials text", 'runcrew'),
						"description" => wp_kses_data( __("Current testimonials text", 'runcrew') ),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					runcrew_get_vc_param('id'),
					runcrew_get_vc_param('class'),
					runcrew_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
		) );
			
		class WPBakeryShortCode_Trx_Testimonials extends RUNCREW_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Testimonials_Item extends RUNCREW_VC_ShortCodeCollection {}
		
	}
}
?>