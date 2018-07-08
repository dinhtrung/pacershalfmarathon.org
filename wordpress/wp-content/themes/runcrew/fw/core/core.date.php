<?php
/**
 * RunCrew Framework: date and time manipulations
 *
 * @package	runcrew
 * @since	runcrew 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Convert date from MySQL format (YYYY-mm-dd) to Date (dd.mm.YYYY)
if (!function_exists('runcrew_sql_to_date')) {
	function runcrew_sql_to_date($str) {
		return (trim($str)=='' || trim($str)=='0000-00-00' ? '' : trim(runcrew_substr($str,8,2).'.'.runcrew_substr($str,5,2).'.'.runcrew_substr($str,0,4).' '.runcrew_substr($str,11)));
	}
}

// Convert date from Date format (dd.mm.YYYY) to MySQL format (YYYY-mm-dd)
if (!function_exists('runcrew_date_to_sql')) {
	function runcrew_date_to_sql($str) {
		if (trim($str)=='') return '';
		$str = strtr(trim($str),'/\-,','....');
		if (trim($str)=='00.00.0000' || trim($str)=='00.00.00') return '';
		$pos = runcrew_strpos($str,'.');
		$d=trim(runcrew_substr($str,0,$pos));
		$str=runcrew_substr($str,$pos+1);
		$pos = runcrew_strpos($str,'.');
		$m=trim(runcrew_substr($str,0,$pos));
		$y=trim(runcrew_substr($str,$pos+1));
		$y=($y<50?$y+2000:($y<1900?$y+1900:$y));
		return ''.($y).'-'.(runcrew_strlen($m)<2?'0':'').($m).'-'.(runcrew_strlen($d)<2?'0':'').($d);
	}
}

// Return difference or date
if (!function_exists('runcrew_get_date_or_difference')) {
	function runcrew_get_date_or_difference($dt1, $dt2=null, $max_days=-1) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($max_days < 0) $max_days = runcrew_get_theme_option('show_date_after', 30);
		if ($dt2 == null) $dt2 = date('Y-m-d H:i:s');
		$dt2n = strtotime($dt2)+$gmt_offset*3600;
		$dt1n = strtotime($dt1);
		if (is_numeric($dt1n) && is_numeric($dt2n)) {
			$diff = $dt2n - $dt1n;
			$days = floor($diff / (24*3600));
			if (abs($days) < $max_days)
				return sprintf($days >= 0 ? esc_html__('%s ago', 'runcrew') : esc_html__('in %s', 'runcrew'), runcrew_get_date_difference($days >= 0 ? $dt1 : $dt2, $days >= 0 ? $dt2 : $dt1));
			else
				return runcrew_get_date_translations(date(get_option('date_format'), $dt1n));
		} else
			return runcrew_get_date_translations($dt1);
	}
}

// Difference between two dates
if (!function_exists('runcrew_get_date_difference')) {
	function runcrew_get_date_difference($dt1, $dt2=null, $short=1, $sec = false) {
		static $gmt_offset = 999;
		if ($gmt_offset==999) $gmt_offset = (int) get_option('gmt_offset');
		if ($dt2 == null) $dt2n = time()+$gmt_offset*3600;
		else $dt2n = strtotime($dt2)+$gmt_offset*3600;
		$dt1n = strtotime($dt1);
		if (is_numeric($dt1n) && is_numeric($dt2n)) {
			$diff = $dt2n - $dt1n;
			$days = floor($diff / (24*3600));
			$months = floor($days / 30);
			$diff -= $days * 24 * 3600;
			$hours = floor($diff / 3600);
			$diff -= $hours * 3600;
			$min = floor($diff / 60);
			$diff -= $min * 60;
			$rez = '';
			if ($months > 0 && $short == 2)
				$rez .= ($rez!='' ? ' ' : '') . sprintf($months > 1 ? esc_html__('%s months', 'runcrew') : esc_html__('%s month', 'runcrew'), $months);
			if ($days > 0 && ($short < 2 || $rez==''))
				$rez .= ($rez!='' ? ' ' : '') . sprintf($days > 1 ? esc_html__('%s days', 'runcrew') : esc_html__('%s day', 'runcrew'), $days);
			if ((!$short || $rez=='') && $hours > 0)
				$rez .= ($rez!='' ? ' ' : '') . sprintf($hours > 1 ? esc_html__('%s hours', 'runcrew') : esc_html__('%s hour', 'runcrew'), $hours);
			if ((!$short || $rez=='') && $min > 0)
				$rez .= ($rez!='' ? ' ' : '') . sprintf($min > 1 ? esc_html__('%s minutes', 'runcrew') : esc_html__('%s minute', 'runcrew'), $min);
			if ($sec || $rez=='')
				$rez .=  $rez!='' || $sec ? (' ' . sprintf($diff > 1 ? esc_html__('%s seconds', 'runcrew') : esc_html__('%s second', 'runcrew'), $diff)) : esc_html__('less then minute', 'runcrew');
			return $rez;
		} else
			return $dt1;
	}
}

// Prepare month names in date for translation
if (!function_exists('runcrew_get_date_translations')) {
	function runcrew_get_date_translations($dt) {
		return str_replace(
			array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
				  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
			array(
				esc_html__('January', 'runcrew'),
				esc_html__('February', 'runcrew'),
				esc_html__('March', 'runcrew'),
				esc_html__('April', 'runcrew'),
				esc_html__('May', 'runcrew'),
				esc_html__('June', 'runcrew'),
				esc_html__('July', 'runcrew'),
				esc_html__('August', 'runcrew'),
				esc_html__('September', 'runcrew'),
				esc_html__('October', 'runcrew'),
				esc_html__('November', 'runcrew'),
				esc_html__('December', 'runcrew'),
				esc_html__('Jan', 'runcrew'),
				esc_html__('Feb', 'runcrew'),
				esc_html__('Mar', 'runcrew'),
				esc_html__('Apr', 'runcrew'),
				esc_html__('May', 'runcrew'),
				esc_html__('Jun', 'runcrew'),
				esc_html__('Jul', 'runcrew'),
				esc_html__('Aug', 'runcrew'),
				esc_html__('Sep', 'runcrew'),
				esc_html__('Oct', 'runcrew'),
				esc_html__('Nov', 'runcrew'),
				esc_html__('Dec', 'runcrew'),
			),
			$dt);
	}
}
?>