<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function date_to_string($date) {
	$now = date("Y-m-d H:i:s");

	$diff = strtotime($now) - strtotime($date);

	if ($diff < 60) {
		return "Just Now";
	}

	$mins = floor($diff/60);

	if ($mins >= 1 && $mins <= 59) {
		if ($mins == 1) return $mins . " minute ago";
		return $mins . " minutes ago";
	}


	$hours = floor($mins / 60);

	if ($hours >= 1 && $hours <= 23) {
		if ($hours == 1) return $hours . " hour ago";
		return $hours . " hours ago";
	}

	$days = floor ($hours / 24);

	if ($days == 1) return $days . " day ago";
 	
	return $days . " days ago";

}

if (!function_exists('__plural_en')) {

    function __plural_en($num) {
        if ($num != 1)
            return 's';
        else
            return '';
    }

}

if (!function_exists('__plural')) {

    function __plural($num, $lng = 'en') {
        $func = "__plural_$lng";

        return call_user_func($func, $num);
    }

}

if (!function_exists('relative_time')) {

    function relative_time($ts) {
        $diff = time() - strtotime($ts);

        if ($diff < 0)
            $prefix = 'in ';
        else
            $suffix = ' ago';

        $diff = abs($diff);

        if ($diff < 60)
            return __(((isset($prefix)) ? $prefix : '') . '%d second%s' . ((isset($suffix)) ? $suffix : ''), array($diff, __plural($diff)));

        $diff = round($diff / 60);
        if ($diff < 60)
            return __(((isset($prefix)) ? $prefix : '') . '%d minute%s' . ((isset($suffix)) ? $suffix : ''), array($diff, __plural($diff)));

        $diff = round($diff / 60);
        if ($diff < 24)
            return __(((isset($prefix)) ? $prefix : '') . '%d hour%s' . ((isset($suffix)) ? $suffix : ''), array($diff, __plural($diff)));

        $diff = round($diff / 24);
        if ($diff < 7)
            return __(((isset($prefix)) ? $prefix : '') . '%d day%s' . ((isset($suffix)) ? $suffix : ''), array($diff, __plural($diff)));

        $diff = round($diff / 7);
        if ($diff < 4)
            return __(((isset($prefix)) ? $prefix : '') . '%d week%s' . ((isset($suffix)) ? $suffix : ''), array($diff, __plural($diff)));

        $diff = round($diff / 4);
        if ($diff < 12)
            return __(((isset($prefix)) ? $prefix : '') . '%d month%s' . ((isset($suffix)) ? $suffix : ''), array($diff, __plural($diff)));

        $diff = round($diff / 12);
        return __(((isset($prefix)) ? $prefix : '') . '%d year%s' . ((isset($suffix)) ? $suffix : ''), array($diff, __plural($diff)));
    }

}