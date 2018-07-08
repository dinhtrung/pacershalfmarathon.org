<?php
/**
 * RunCrew Framework: strings manipulations
 *
 * @package	runcrew
 * @since	runcrew 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'RUNCREW_MULTIBYTE' ) ) define( 'RUNCREW_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('runcrew_strlen')) {
	function runcrew_strlen($text) {
		return RUNCREW_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('runcrew_strpos')) {
	function runcrew_strpos($text, $char, $from=0) {
		return RUNCREW_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('runcrew_strrpos')) {
	function runcrew_strrpos($text, $char, $from=0) {
		return RUNCREW_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('runcrew_substr')) {
	function runcrew_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = runcrew_strlen($text)-$from;
		}
		return RUNCREW_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('runcrew_strtolower')) {
	function runcrew_strtolower($text) {
		return RUNCREW_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('runcrew_strtoupper')) {
	function runcrew_strtoupper($text) {
		return RUNCREW_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('runcrew_strtoproper')) {
	function runcrew_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<runcrew_strlen($text); $i++) {
			$ch = runcrew_substr($text, $i, 1);
			$rez .= runcrew_strpos(' .,:;?!()[]{}+=', $last)!==false ? runcrew_strtoupper($ch) : runcrew_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('runcrew_strrepeat')) {
	function runcrew_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('runcrew_strshort')) {
	function runcrew_strshort($str, $maxlength, $add='...') {
	//	if ($add && runcrew_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= runcrew_strlen($str)) 
			return strip_tags($str);
		$str = runcrew_substr(strip_tags($str), 0, $maxlength - runcrew_strlen($add));
		$ch = runcrew_substr($str, $maxlength - runcrew_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = runcrew_strlen($str) - 1; $i > 0; $i--)
				if (runcrew_substr($str, $i, 1) == ' ') break;
			$str = trim(runcrew_substr($str, 0, $i));
		}
		if (!empty($str) && runcrew_strpos(',.:;-', runcrew_substr($str, -1))!==false) $str = runcrew_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('runcrew_strclear')) {
	function runcrew_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (runcrew_substr($text, 0, runcrew_strlen($open))==$open) {
					$pos = runcrew_strpos($text, '>');
					if ($pos!==false) $text = runcrew_substr($text, $pos+1);
				}
				if (runcrew_substr($text, -runcrew_strlen($close))==$close) $text = runcrew_substr($text, 0, runcrew_strlen($text) - runcrew_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('runcrew_get_slug')) {
	function runcrew_get_slug($title) {
		return runcrew_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('runcrew_strmacros')) {
	function runcrew_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('runcrew_unserialize')) {
	function runcrew_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			//if ($data===false) $data = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $str));
			return $data;
		} else
			return $str;
	}
}
?>