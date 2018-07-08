<?php
/**
 * RunCrew Framework: theme variables storage
 *
 * @package	runcrew
 * @since	runcrew 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('runcrew_storage_get')) {
	function runcrew_storage_get($var_name, $default='') {
		global $RUNCREW_STORAGE;
		return isset($RUNCREW_STORAGE[$var_name]) ? $RUNCREW_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('runcrew_storage_set')) {
	function runcrew_storage_set($var_name, $value) {
		global $RUNCREW_STORAGE;
		$RUNCREW_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('runcrew_storage_empty')) {
	function runcrew_storage_empty($var_name, $key='', $key2='') {
		global $RUNCREW_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($RUNCREW_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($RUNCREW_STORAGE[$var_name][$key]);
		else
			return empty($RUNCREW_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('runcrew_storage_isset')) {
	function runcrew_storage_isset($var_name, $key='', $key2='') {
		global $RUNCREW_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($RUNCREW_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($RUNCREW_STORAGE[$var_name][$key]);
		else
			return isset($RUNCREW_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('runcrew_storage_inc')) {
	function runcrew_storage_inc($var_name, $value=1) {
		global $RUNCREW_STORAGE;
		if (empty($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = 0;
		$RUNCREW_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('runcrew_storage_concat')) {
	function runcrew_storage_concat($var_name, $value) {
		global $RUNCREW_STORAGE;
		if (empty($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = '';
		$RUNCREW_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('runcrew_storage_get_array')) {
	function runcrew_storage_get_array($var_name, $key, $key2='', $default='') {
		global $RUNCREW_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($RUNCREW_STORAGE[$var_name][$key]) ? $RUNCREW_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($RUNCREW_STORAGE[$var_name][$key][$key2]) ? $RUNCREW_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('runcrew_storage_set_array')) {
	function runcrew_storage_set_array($var_name, $key, $value) {
		global $RUNCREW_STORAGE;
		if (!isset($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = array();
		if ($key==='')
			$RUNCREW_STORAGE[$var_name][] = $value;
		else
			$RUNCREW_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('runcrew_storage_set_array2')) {
	function runcrew_storage_set_array2($var_name, $key, $key2, $value) {
		global $RUNCREW_STORAGE;
		if (!isset($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = array();
		if (!isset($RUNCREW_STORAGE[$var_name][$key])) $RUNCREW_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$RUNCREW_STORAGE[$var_name][$key][] = $value;
		else
			$RUNCREW_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('runcrew_storage_set_array_after')) {
	function runcrew_storage_set_array_after($var_name, $after, $key, $value='') {
		global $RUNCREW_STORAGE;
		if (!isset($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = array();
		if (is_array($key))
			runcrew_array_insert_after($RUNCREW_STORAGE[$var_name], $after, $key);
		else
			runcrew_array_insert_after($RUNCREW_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('runcrew_storage_set_array_before')) {
	function runcrew_storage_set_array_before($var_name, $before, $key, $value='') {
		global $RUNCREW_STORAGE;
		if (!isset($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = array();
		if (is_array($key))
			runcrew_array_insert_before($RUNCREW_STORAGE[$var_name], $before, $key);
		else
			runcrew_array_insert_before($RUNCREW_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('runcrew_storage_push_array')) {
	function runcrew_storage_push_array($var_name, $key, $value) {
		global $RUNCREW_STORAGE;
		if (!isset($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($RUNCREW_STORAGE[$var_name], $value);
		else {
			if (!isset($RUNCREW_STORAGE[$var_name][$key])) $RUNCREW_STORAGE[$var_name][$key] = array();
			array_push($RUNCREW_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('runcrew_storage_pop_array')) {
	function runcrew_storage_pop_array($var_name, $key='', $defa='') {
		global $RUNCREW_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($RUNCREW_STORAGE[$var_name]) && is_array($RUNCREW_STORAGE[$var_name]) && count($RUNCREW_STORAGE[$var_name]) > 0) 
				$rez = array_pop($RUNCREW_STORAGE[$var_name]);
		} else {
			if (isset($RUNCREW_STORAGE[$var_name][$key]) && is_array($RUNCREW_STORAGE[$var_name][$key]) && count($RUNCREW_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($RUNCREW_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('runcrew_storage_inc_array')) {
	function runcrew_storage_inc_array($var_name, $key, $value=1) {
		global $RUNCREW_STORAGE;
		if (!isset($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = array();
		if (empty($RUNCREW_STORAGE[$var_name][$key])) $RUNCREW_STORAGE[$var_name][$key] = 0;
		$RUNCREW_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('runcrew_storage_concat_array')) {
	function runcrew_storage_concat_array($var_name, $key, $value) {
		global $RUNCREW_STORAGE;
		if (!isset($RUNCREW_STORAGE[$var_name])) $RUNCREW_STORAGE[$var_name] = array();
		if (empty($RUNCREW_STORAGE[$var_name][$key])) $RUNCREW_STORAGE[$var_name][$key] = '';
		$RUNCREW_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('runcrew_storage_call_obj_method')) {
	function runcrew_storage_call_obj_method($var_name, $method, $param=null) {
		global $RUNCREW_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($RUNCREW_STORAGE[$var_name]) ? $RUNCREW_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($RUNCREW_STORAGE[$var_name]) ? $RUNCREW_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('runcrew_storage_get_obj_property')) {
	function runcrew_storage_get_obj_property($var_name, $prop, $default='') {
		global $RUNCREW_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($RUNCREW_STORAGE[$var_name]->$prop) ? $RUNCREW_STORAGE[$var_name]->$prop : $default;
	}
}
?>