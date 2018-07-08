<?php
// Get template args
extract(runcrew_template_get_args('counters'));

$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';

//if (is_array($post_options['counters'])) $post_options['counters'] = join(',', $post_options['counters']);

// Views
if ($show_all_counters || runcrew_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php echo trim($counters_tag); ?> class="post_counters_item post_counters_views icon-eye" title="<?php echo esc_attr( sprintf(__('Views - %s', 'runcrew'), $post_data['post_views']) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_views']); ?></span><?php if (runcrew_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Views', 'runcrew'); ?></<?php echo trim($counters_tag); ?>>
	<?php
}

// Comments
if ($show_all_counters || runcrew_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment_icon" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'runcrew'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_comments']); ?></span><?php if (runcrew_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Comments', 'runcrew'); ?></a>
	<?php 
}
 
// Rating
$rating = $post_data['post_reviews_'.(runcrew_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || runcrew_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php echo trim($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'runcrew'), $rating) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo trim($rating); ?></span></<?php echo trim($counters_tag); ?>>
	<?php
}

// Likes
if ($show_all_counters || runcrew_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	runcrew_enqueue_messages();
	$likes = isset($_COOKIE['runcrew_likes']) ? $_COOKIE['runcrew_likes'] : '';
	$allow = runcrew_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-like_icon <?php echo !empty($allow) ? 'enabled' : 'disabled'; ?>" title="<?php echo !empty($allow) ? esc_attr__('Like', 'runcrew') : esc_attr__('Dislike', 'runcrew'); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'runcrew'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'runcrew'); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_likes']); ?></span><?php if (runcrew_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Likes', 'runcrew'); ?></a>
	<?php
}

// Edit page link
if (runcrew_strpos($post_options['counters'], 'edit')!==false) {
	edit_post_link( esc_html__( 'Edit', 'runcrew' ), '<span class="post_edit edit-link">', '</span>' );
}

// Markup for search engines
if (is_single() && runcrew_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(runcrew_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(runcrew_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>