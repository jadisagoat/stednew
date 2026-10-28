<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getPost')) {

    function getPost($key) {
        $CI = & get_instance();
        return $CI->input->post($key);
    }

}

if (!function_exists('in_array_object')) {

    function in_array_object($array, $object, $key = "id") {
        foreach ($array as $o) {
            if ($o->$key == $object->$key)
                return true;
        }

        return false;
    }

}