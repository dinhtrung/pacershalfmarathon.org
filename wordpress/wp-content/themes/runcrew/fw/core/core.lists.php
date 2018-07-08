<?php
/**
 * RunCrew Framework: return lists
 *
 * @package runcrew
 * @since runcrew 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'runcrew_get_list_styles' ) ) {
	function runcrew_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'runcrew'), $i);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'runcrew_get_list_margins' ) ) {
	function runcrew_get_list_margins($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'runcrew'),
				'tiny'		=> esc_html__('Tiny',		'runcrew'),
				'small'		=> esc_html__('Small',		'runcrew'),
				'medium'	=> esc_html__('Medium',		'runcrew'),
				'large'		=> esc_html__('Large',		'runcrew'),
				'huge'		=> esc_html__('Huge',		'runcrew'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'runcrew'),
				'small-'	=> esc_html__('Small (negative)',	'runcrew'),
				'medium-'	=> esc_html__('Medium (negative)',	'runcrew'),
				'large-'	=> esc_html__('Large (negative)',	'runcrew'),
				'huge-'		=> esc_html__('Huge (negative)',	'runcrew')
				);
			$list = apply_filters('runcrew_filter_list_margins', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'runcrew_get_list_animations' ) ) {
	function runcrew_get_list_animations($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'runcrew'),
				'bounced'		=> esc_html__('Bounced',		'runcrew'),
				'flash'			=> esc_html__('Flash',		'runcrew'),
				'flip'			=> esc_html__('Flip',		'runcrew'),
				'pulse'			=> esc_html__('Pulse',		'runcrew'),
				'rubberBand'	=> esc_html__('Rubber Band',	'runcrew'),
				'shake'			=> esc_html__('Shake',		'runcrew'),
				'swing'			=> esc_html__('Swing',		'runcrew'),
				'tada'			=> esc_html__('Tada',		'runcrew'),
				'wobble'		=> esc_html__('Wobble',		'runcrew')
				);
			$list = apply_filters('runcrew_filter_list_animations', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'runcrew_get_list_line_styles' ) ) {
	function runcrew_get_list_line_styles($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'runcrew'),
				'dashed'=> esc_html__('Dashed', 'runcrew'),
				'dotted'=> esc_html__('Dotted', 'runcrew'),
				'double'=> esc_html__('Double', 'runcrew'),
				'image'	=> esc_html__('Image', 'runcrew')
				);
			$list = apply_filters('runcrew_filter_list_line_styles', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'runcrew_get_list_animations_in' ) ) {
	function runcrew_get_list_animations_in($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'runcrew'),
				'bounceIn'			=> esc_html__('Bounce In',			'runcrew'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'runcrew'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'runcrew'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'runcrew'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'runcrew'),
				'fadeIn'			=> esc_html__('Fade In',			'runcrew'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'runcrew'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'runcrew'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'runcrew'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'runcrew'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'runcrew'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'runcrew'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'runcrew'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'runcrew'),
				'flipInX'			=> esc_html__('Flip In X',			'runcrew'),
				'flipInY'			=> esc_html__('Flip In Y',			'runcrew'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'runcrew'),
				'rotateIn'			=> esc_html__('Rotate In',			'runcrew'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','runcrew'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'runcrew'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'runcrew'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','runcrew'),
				'rollIn'			=> esc_html__('Roll In',			'runcrew'),
				'slideInUp'			=> esc_html__('Slide In Up',		'runcrew'),
				'slideInDown'		=> esc_html__('Slide In Down',		'runcrew'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'runcrew'),
				'slideInRight'		=> esc_html__('Slide In Right',		'runcrew'),
				'zoomIn'			=> esc_html__('Zoom In',			'runcrew'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'runcrew'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'runcrew'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'runcrew'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'runcrew')
				);
			$list = apply_filters('runcrew_filter_list_animations_in', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'runcrew_get_list_animations_out' ) ) {
	function runcrew_get_list_animations_out($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'runcrew'),
				'bounceOut'			=> esc_html__('Bounce Out',			'runcrew'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'runcrew'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'runcrew'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'runcrew'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'runcrew'),
				'fadeOut'			=> esc_html__('Fade Out',			'runcrew'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'runcrew'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'runcrew'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'runcrew'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'runcrew'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'runcrew'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'runcrew'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'runcrew'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'runcrew'),
				'flipOutX'			=> esc_html__('Flip Out X',			'runcrew'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'runcrew'),
				'hinge'				=> esc_html__('Hinge Out',			'runcrew'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'runcrew'),
				'rotateOut'			=> esc_html__('Rotate Out',			'runcrew'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'runcrew'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'runcrew'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'runcrew'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'runcrew'),
				'rollOut'			=> esc_html__('Roll Out',		'runcrew'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'runcrew'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'runcrew'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'runcrew'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'runcrew'),
				'zoomOut'			=> esc_html__('Zoom Out',			'runcrew'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'runcrew'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'runcrew'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'runcrew'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'runcrew')
				);
			$list = apply_filters('runcrew_filter_list_animations_out', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('runcrew_get_animation_classes')) {
	function runcrew_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return runcrew_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!runcrew_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'runcrew_get_list_categories' ) ) {
	function runcrew_get_list_categories($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'runcrew_get_list_terms' ) ) {
	function runcrew_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = runcrew_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = runcrew_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'runcrew_get_list_posts_types' ) ) {
	function runcrew_get_list_posts_types($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_posts_types'))=='') {
			// This way to return all registered post types
			$types = get_post_types();
            
			if (in_array('post', $types)) isset($list['post']) ? $list['post'] = esc_html__('Post', 'runcrew') :'' ;
			if (is_array($types) && count($types) > 0) {
				foreach ($types as $t) {
					if ($t == 'post') continue;
					$list[$t] = runcrew_strtoproper($t);
				}
			}
            
			// Return only theme inheritance supported post types
			$list = apply_filters('runcrew_filter_list_post_types', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'runcrew_get_list_posts' ) ) {
	function runcrew_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = runcrew_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'runcrew');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set($hash, $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'runcrew_get_list_pages' ) ) {
	function runcrew_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return runcrew_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'runcrew_get_list_users' ) ) {
	function runcrew_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = runcrew_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'runcrew');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_users', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'runcrew_get_list_sliders' ) ) {
	function runcrew_get_list_sliders($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'runcrew')
			);
			$list = apply_filters('runcrew_filter_list_sliders', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'runcrew_get_list_slider_controls' ) ) {
	function runcrew_get_list_slider_controls($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'runcrew'),
				'side'		=> esc_html__('Side', 'runcrew'),
				'bottom'	=> esc_html__('Bottom', 'runcrew'),
				'pagination'=> esc_html__('Pagination', 'runcrew')
				);
			$list = apply_filters('runcrew_filter_list_slider_controls', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'runcrew_get_slider_controls_classes' ) ) {
	function runcrew_get_slider_controls_classes($controls) {
		if (runcrew_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'runcrew_get_list_popup_engines' ) ) {
	function runcrew_get_list_popup_engines($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'runcrew'),
				"magnific"	=> esc_html__("Magnific popup", 'runcrew')
				);
			$list = apply_filters('runcrew_filter_list_popup_engines', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'runcrew_get_list_menus' ) ) {
	function runcrew_get_list_menus($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'runcrew');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'runcrew_get_list_sidebars' ) ) {
	function runcrew_get_list_sidebars($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_sidebars'))=='') {
			if (($list = runcrew_storage_get('registered_sidebars'))=='') $list = array();
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'runcrew_get_list_sidebars_positions' ) ) {
	function runcrew_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'runcrew'),
				'left'  => esc_html__('Left',  'runcrew'),
				'right' => esc_html__('Right', 'runcrew')
				);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'runcrew_get_sidebar_class' ) ) {
	function runcrew_get_sidebar_class() {
		$sb_main = runcrew_get_custom_option('show_sidebar_main');
		$sb_outer = runcrew_get_custom_option('show_sidebar_outer');
		return (runcrew_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (runcrew_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_body_styles' ) ) {
	function runcrew_get_list_body_styles($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'runcrew'),
				'wide'	=> esc_html__('Wide',		'runcrew')
				);
			if (runcrew_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'runcrew');
				$list['fullscreen']	= esc_html__('Fullscreen',	'runcrew');
			}
			$list = apply_filters('runcrew_filter_list_body_styles', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'runcrew_get_list_skins' ) ) {
	function runcrew_get_list_skins($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_skins'))=='') {
			$list = runcrew_get_list_folders("skins");
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_skins', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'runcrew_get_list_themes' ) ) {
	function runcrew_get_list_themes($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_themes'))=='') {
			$list = runcrew_get_list_files("css/themes");
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'runcrew_get_list_templates' ) ) {
	function runcrew_get_list_templates($mode='') {
		if (($list = runcrew_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = runcrew_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: runcrew_strtoproper($v['layout'])
										);
				}
			}
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_templates_blog' ) ) {
	function runcrew_get_list_templates_blog($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_templates_blog'))=='') {
			$list = runcrew_get_list_templates('blog');
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_templates_blogger' ) ) {
	function runcrew_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_templates_blogger'))=='') {
			$list = runcrew_array_merge(runcrew_get_list_templates('blogger'), runcrew_get_list_templates('blog'));
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_templates_single' ) ) {
	function runcrew_get_list_templates_single($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_templates_single'))=='') {
			$list = runcrew_get_list_templates('single');
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_templates_header' ) ) {
	function runcrew_get_list_templates_header($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_templates_header'))=='') {
			$list = runcrew_get_list_templates('header');
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_templates_forms' ) ) {
	function runcrew_get_list_templates_forms($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_templates_forms'))=='') {
			$list = runcrew_get_list_templates('forms');
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_article_styles' ) ) {
	function runcrew_get_list_article_styles($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'runcrew'),
				"stretch" => esc_html__('Stretch', 'runcrew')
				);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'runcrew_get_list_post_formats_filters' ) ) {
	function runcrew_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'runcrew'),
				"thumbs"  => esc_html__('With thumbs', 'runcrew'),
				"reviews" => esc_html__('With reviews', 'runcrew'),
				"video"   => esc_html__('With videos', 'runcrew'),
				"audio"   => esc_html__('With audios', 'runcrew'),
				"gallery" => esc_html__('With galleries', 'runcrew')
				);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'runcrew_get_list_portfolio_filters' ) ) {
	function runcrew_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'runcrew'),
				"tags"		=> esc_html__('Tags', 'runcrew'),
				"categories"=> esc_html__('Categories', 'runcrew')
				);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_hovers' ) ) {
	function runcrew_get_list_hovers($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'runcrew');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'runcrew');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'runcrew');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'runcrew');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'runcrew');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'runcrew');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'runcrew');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'runcrew');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'runcrew');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'runcrew');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'runcrew');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'runcrew');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'runcrew');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'runcrew');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'runcrew');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'runcrew');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'runcrew');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'runcrew');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'runcrew');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'runcrew');
			$list['square effect1']  = esc_html__('Square Effect 1',  'runcrew');
			$list['square effect2']  = esc_html__('Square Effect 2',  'runcrew');
			$list['square effect3']  = esc_html__('Square Effect 3',  'runcrew');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'runcrew');
			$list['square effect5']  = esc_html__('Square Effect 5',  'runcrew');
			$list['square effect6']  = esc_html__('Square Effect 6',  'runcrew');
			$list['square effect7']  = esc_html__('Square Effect 7',  'runcrew');
			$list['square effect8']  = esc_html__('Square Effect 8',  'runcrew');
			$list['square effect9']  = esc_html__('Square Effect 9',  'runcrew');
			$list['square effect10'] = esc_html__('Square Effect 10',  'runcrew');
			$list['square effect11'] = esc_html__('Square Effect 11',  'runcrew');
			$list['square effect12'] = esc_html__('Square Effect 12',  'runcrew');
			$list['square effect13'] = esc_html__('Square Effect 13',  'runcrew');
			$list['square effect14'] = esc_html__('Square Effect 14',  'runcrew');
			$list['square effect15'] = esc_html__('Square Effect 15',  'runcrew');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'runcrew');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'runcrew');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'runcrew');
			$list['square effect_more']  = esc_html__('Square Effect More',  'runcrew');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'runcrew');
			$list = apply_filters('runcrew_filter_portfolio_hovers', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'runcrew_get_list_blog_counters' ) ) {
	function runcrew_get_list_blog_counters($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'runcrew'),
				'likes'		=> esc_html__('Likes', 'runcrew'),
				'rating'	=> esc_html__('Rating', 'runcrew'),
				'comments'	=> esc_html__('Comments', 'runcrew')
				);
			$list = apply_filters('runcrew_filter_list_blog_counters', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'runcrew_get_list_alter_sizes' ) ) {
	function runcrew_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'runcrew'),
					'1_2' => esc_html__('1x2', 'runcrew'),
					'2_1' => esc_html__('2x1', 'runcrew'),
					'2_2' => esc_html__('2x2', 'runcrew'),
					'1_3' => esc_html__('1x3', 'runcrew'),
					'2_3' => esc_html__('2x3', 'runcrew'),
					'3_1' => esc_html__('3x1', 'runcrew'),
					'3_2' => esc_html__('3x2', 'runcrew'),
					'3_3' => esc_html__('3x3', 'runcrew')
					);
			$list = apply_filters('runcrew_filter_portfolio_alter_sizes', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'runcrew_get_list_hovers_directions' ) ) {
	function runcrew_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'runcrew'),
				'right_to_left' => esc_html__('Right to Left',  'runcrew'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'runcrew'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'runcrew'),
				'scale_up'      => esc_html__('Scale Up',  'runcrew'),
				'scale_down'    => esc_html__('Scale Down',  'runcrew'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'runcrew'),
				'from_left_and_right' => esc_html__('From Left and Right',  'runcrew'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'runcrew')
			);
			$list = apply_filters('runcrew_filter_portfolio_hovers_directions', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'runcrew_get_list_label_positions' ) ) {
	function runcrew_get_list_label_positions($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'runcrew'),
				'bottom'	=> esc_html__('Bottom',		'runcrew'),
				'left'		=> esc_html__('Left',		'runcrew'),
				'over'		=> esc_html__('Over',		'runcrew')
			);
			$list = apply_filters('runcrew_filter_label_positions', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'runcrew_get_list_bg_image_positions' ) ) {
	function runcrew_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'runcrew'),
				'center top'   => esc_html__("Center Top", 'runcrew'),
				'right top'    => esc_html__("Right Top", 'runcrew'),
				'left center'  => esc_html__("Left Center", 'runcrew'),
				'center center'=> esc_html__("Center Center", 'runcrew'),
				'right center' => esc_html__("Right Center", 'runcrew'),
				'left bottom'  => esc_html__("Left Bottom", 'runcrew'),
				'center bottom'=> esc_html__("Center Bottom", 'runcrew'),
				'right bottom' => esc_html__("Right Bottom", 'runcrew')
			);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'runcrew_get_list_bg_image_repeats' ) ) {
	function runcrew_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'runcrew'),
				'repeat-x'	=> esc_html__('Repeat X', 'runcrew'),
				'repeat-y'	=> esc_html__('Repeat Y', 'runcrew'),
				'no-repeat'	=> esc_html__('No Repeat', 'runcrew')
			);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'runcrew_get_list_bg_image_attachments' ) ) {
	function runcrew_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'runcrew'),
				'fixed'		=> esc_html__('Fixed', 'runcrew'),
				'local'		=> esc_html__('Local', 'runcrew')
			);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'runcrew_get_list_bg_tints' ) ) {
	function runcrew_get_list_bg_tints($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'runcrew'),
				'light'	=> esc_html__('Light', 'runcrew'),
				'dark'	=> esc_html__('Dark', 'runcrew')
			);
			$list = apply_filters('runcrew_filter_bg_tints', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'runcrew_get_list_field_types' ) ) {
	function runcrew_get_list_field_types($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'runcrew'),
				'textarea' => esc_html__('Text Area','runcrew'),
				'password' => esc_html__('Password',  'runcrew'),
				'radio'    => esc_html__('Radio',  'runcrew'),
				'checkbox' => esc_html__('Checkbox',  'runcrew'),
				'select'   => esc_html__('Select',  'runcrew'),
				'date'     => esc_html__('Date','runcrew'),
				'time'     => esc_html__('Time','runcrew'),
				'button'   => esc_html__('Button','runcrew')
			);
			$list = apply_filters('runcrew_filter_field_types', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'runcrew_get_list_googlemap_styles' ) ) {
	function runcrew_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'runcrew')
			);
			$list = apply_filters('runcrew_filter_googlemap_styles', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'runcrew_get_list_icons' ) ) {
	function runcrew_get_list_icons($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_icons'))=='') {
			$list = runcrew_parse_icons_classes(runcrew_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'runcrew_get_list_socials' ) ) {
	function runcrew_get_list_socials($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_socials'))=='') {
			$list = runcrew_get_list_files("images/socials", "png");
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'runcrew_get_list_flags' ) ) {
	function runcrew_get_list_flags($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_flags'))=='') {
			$list = runcrew_get_list_files("images/flags", "png");
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'runcrew_get_list_yesno' ) ) {
	function runcrew_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'runcrew'),
			'no'  => esc_html__("No", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'runcrew_get_list_onoff' ) ) {
	function runcrew_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'runcrew'),
			"off" => esc_html__("Off", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'runcrew_get_list_showhide' ) ) {
	function runcrew_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'runcrew'),
			"hide" => esc_html__("Hide", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'runcrew_get_list_orderings' ) ) {
	function runcrew_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'runcrew'),
			"desc" => esc_html__("Descending", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'runcrew_get_list_directions' ) ) {
	function runcrew_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'runcrew'),
			"vertical" => esc_html__("Vertical", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'runcrew_get_list_shapes' ) ) {
	function runcrew_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'runcrew'),
			"square" => esc_html__("Square", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'runcrew_get_list_sizes' ) ) {
	function runcrew_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'runcrew'),
			"small"  => esc_html__("Small", 'runcrew'),
			"medium" => esc_html__("Medium", 'runcrew'),
			"large"  => esc_html__("Large", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'runcrew_get_list_controls' ) ) {
	function runcrew_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'runcrew'),
			"side" => esc_html__("Side", 'runcrew'),
			"bottom" => esc_html__("Bottom", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'runcrew_get_list_floats' ) ) {
	function runcrew_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'runcrew'),
			"left" => esc_html__("Float Left", 'runcrew'),
			"right" => esc_html__("Float Right", 'runcrew')
		);
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'runcrew_get_list_alignments' ) ) {
	function runcrew_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'runcrew'),
			"left" => esc_html__("Left", 'runcrew'),
			"center" => esc_html__("Center", 'runcrew'),
			"right" => esc_html__("Right", 'runcrew')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'runcrew');
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'runcrew_get_list_hpos' ) ) {
	function runcrew_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'runcrew');
		if ($center) $list['center'] = esc_html__("Center", 'runcrew');
		$list['right'] = esc_html__("Right", 'runcrew');
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'runcrew_get_list_vpos' ) ) {
	function runcrew_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'runcrew');
		if ($center) $list['center'] = esc_html__("Center", 'runcrew');
		$list['bottom'] = esc_html__("Bottom", 'runcrew');
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'runcrew_get_list_sortings' ) ) {
	function runcrew_get_list_sortings($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'runcrew'),
				"title" => esc_html__("Alphabetically", 'runcrew'),
				"views" => esc_html__("Popular (views count)", 'runcrew'),
				"comments" => esc_html__("Most commented (comments count)", 'runcrew'),
				"author_rating" => esc_html__("Author rating", 'runcrew'),
				"users_rating" => esc_html__("Visitors (users) rating", 'runcrew'),
				"random" => esc_html__("Random", 'runcrew')
			);
			$list = apply_filters('runcrew_filter_list_sortings', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'runcrew_get_list_columns' ) ) {
	function runcrew_get_list_columns($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'runcrew'),
				"1_1" => esc_html__("100%", 'runcrew'),
				"1_2" => esc_html__("1/2", 'runcrew'),
				"1_3" => esc_html__("1/3", 'runcrew'),
				"2_3" => esc_html__("2/3", 'runcrew'),
				"1_4" => esc_html__("1/4", 'runcrew'),
				"3_4" => esc_html__("3/4", 'runcrew'),
				"1_5" => esc_html__("1/5", 'runcrew'),
				"2_5" => esc_html__("2/5", 'runcrew'),
				"3_5" => esc_html__("3/5", 'runcrew'),
				"4_5" => esc_html__("4/5", 'runcrew'),
				"1_6" => esc_html__("1/6", 'runcrew'),
				"5_6" => esc_html__("5/6", 'runcrew'),
				"1_7" => esc_html__("1/7", 'runcrew'),
				"2_7" => esc_html__("2/7", 'runcrew'),
				"3_7" => esc_html__("3/7", 'runcrew'),
				"4_7" => esc_html__("4/7", 'runcrew'),
				"5_7" => esc_html__("5/7", 'runcrew'),
				"6_7" => esc_html__("6/7", 'runcrew'),
				"1_8" => esc_html__("1/8", 'runcrew'),
				"3_8" => esc_html__("3/8", 'runcrew'),
				"5_8" => esc_html__("5/8", 'runcrew'),
				"7_8" => esc_html__("7/8", 'runcrew'),
				"1_9" => esc_html__("1/9", 'runcrew'),
				"2_9" => esc_html__("2/9", 'runcrew'),
				"4_9" => esc_html__("4/9", 'runcrew'),
				"5_9" => esc_html__("5/9", 'runcrew'),
				"7_9" => esc_html__("7/9", 'runcrew'),
				"8_9" => esc_html__("8/9", 'runcrew'),
				"1_10"=> esc_html__("1/10", 'runcrew'),
				"3_10"=> esc_html__("3/10", 'runcrew'),
				"7_10"=> esc_html__("7/10", 'runcrew'),
				"9_10"=> esc_html__("9/10", 'runcrew'),
				"1_11"=> esc_html__("1/11", 'runcrew'),
				"2_11"=> esc_html__("2/11", 'runcrew'),
				"3_11"=> esc_html__("3/11", 'runcrew'),
				"4_11"=> esc_html__("4/11", 'runcrew'),
				"5_11"=> esc_html__("5/11", 'runcrew'),
				"6_11"=> esc_html__("6/11", 'runcrew'),
				"7_11"=> esc_html__("7/11", 'runcrew'),
				"8_11"=> esc_html__("8/11", 'runcrew'),
				"9_11"=> esc_html__("9/11", 'runcrew'),
				"10_11"=> esc_html__("10/11", 'runcrew'),
				"1_12"=> esc_html__("1/12", 'runcrew'),
				"5_12"=> esc_html__("5/12", 'runcrew'),
				"7_12"=> esc_html__("7/12", 'runcrew'),
				"10_12"=> esc_html__("10/12", 'runcrew'),
				"11_12"=> esc_html__("11/12", 'runcrew')
			);
			$list = apply_filters('runcrew_filter_list_columns', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'runcrew_get_list_dedicated_locations' ) ) {
	function runcrew_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'runcrew'),
				"center"  => esc_html__('Above the text of the post', 'runcrew'),
				"left"    => esc_html__('To the left the text of the post', 'runcrew'),
				"right"   => esc_html__('To the right the text of the post', 'runcrew'),
				"alter"   => esc_html__('Alternates for each post', 'runcrew')
			);
			$list = apply_filters('runcrew_filter_list_dedicated_locations', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'runcrew_get_post_format_name' ) ) {
	function runcrew_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'runcrew') : esc_html__('galleries', 'runcrew');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'runcrew') : esc_html__('videos', 'runcrew');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'runcrew') : esc_html__('audios', 'runcrew');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'runcrew') : esc_html__('images', 'runcrew');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'runcrew') : esc_html__('quotes', 'runcrew');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'runcrew') : esc_html__('links', 'runcrew');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'runcrew') : esc_html__('statuses', 'runcrew');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'runcrew') : esc_html__('asides', 'runcrew');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'runcrew') : esc_html__('chats', 'runcrew');
		else						$name = $single ? esc_html__('standard', 'runcrew') : esc_html__('standards', 'runcrew');
		return apply_filters('runcrew_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'runcrew_get_post_format_icon' ) ) {
	function runcrew_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('runcrew_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'runcrew_get_list_fonts_styles' ) ) {
	function runcrew_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','runcrew'),
				'u' => esc_html__('U', 'runcrew')
			);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'runcrew_get_list_fonts' ) ) {
	function runcrew_get_list_fonts($prepend_inherit=false) {
		if (($list = runcrew_storage_get('list_fonts'))=='') {
			$list = array();
			$list = runcrew_array_merge($list, runcrew_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>runcrew_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = runcrew_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('runcrew_filter_list_fonts', $list);
			if (runcrew_get_theme_setting('use_list_cache')) runcrew_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? runcrew_array_merge(array('inherit' => esc_html__("Inherit", 'runcrew')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'runcrew_get_list_font_faces' ) ) {
	function runcrew_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = runcrew_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? runcrew_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? runcrew_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'runcrew').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>
