<?php
/**
 * RunCrew Framework: messages subsystem
 *
 * @package	runcrew
 * @since	runcrew 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('runcrew_messages_theme_setup')) {
	add_action( 'runcrew_action_before_init_theme', 'runcrew_messages_theme_setup' );
	function runcrew_messages_theme_setup() {
		// Core messages strings
		add_action('runcrew_action_add_scripts_inline', 'runcrew_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('runcrew_get_error_msg')) {
	function runcrew_get_error_msg() {
		return runcrew_storage_get('error_msg');
	}
}

if (!function_exists('runcrew_set_error_msg')) {
	function runcrew_set_error_msg($msg) {
		$msg2 = runcrew_get_error_msg();
		runcrew_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('runcrew_get_success_msg')) {
	function runcrew_get_success_msg() {
		return runcrew_storage_get('success_msg');
	}
}

if (!function_exists('runcrew_set_success_msg')) {
	function runcrew_set_success_msg($msg) {
		$msg2 = runcrew_get_success_msg();
		runcrew_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('runcrew_get_notice_msg')) {
	function runcrew_get_notice_msg() {
		return runcrew_storage_get('notice_msg');
	}
}

if (!function_exists('runcrew_set_notice_msg')) {
	function runcrew_set_notice_msg($msg) {
		$msg2 = runcrew_get_notice_msg();
		runcrew_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('runcrew_set_system_message')) {
	function runcrew_set_system_message($msg, $status='info', $hdr='') {
		update_option('runcrew_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('runcrew_get_system_message')) {
	function runcrew_get_system_message($del=false) {
		$msg = get_option('runcrew_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			runcrew_del_system_message();
		return $msg;
	}
}

if (!function_exists('runcrew_del_system_message')) {
	function runcrew_del_system_message() {
		delete_option('runcrew_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('runcrew_messages_add_scripts_inline')) {
	function runcrew_messages_add_scripts_inline() {
		echo '<script type="text/javascript">'
			
			. "if (typeof RUNCREW_STORAGE == 'undefined') var RUNCREW_STORAGE = {};"
			
			// Strings for translation
			. 'RUNCREW_STORAGE["strings"] = {'
				. 'ajax_error: 			"' . addslashes(esc_html__('Invalid server answer', 'runcrew')) . '",'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'runcrew')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'runcrew')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'runcrew')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'runcrew')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'runcrew')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'runcrew')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'runcrew')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'runcrew')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'runcrew')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'runcrew')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'runcrew')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'runcrew')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'runcrew')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'runcrew')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'runcrew')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'runcrew')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'runcrew')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'runcrew')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'runcrew')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'runcrew')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'runcrew')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'runcrew')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'runcrew')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'runcrew')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'runcrew')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'runcrew')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'runcrew')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'runcrew')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'runcrew')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'runcrew')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'runcrew')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'runcrew')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'runcrew')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'runcrew')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'runcrew')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'runcrew')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'runcrew')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'runcrew')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'runcrew')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'runcrew')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'runcrew')) . '"'
				. '};'
			
			. '</script>';
	}
}
?>