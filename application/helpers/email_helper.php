<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists("send_email")) {

    function send_email($template, $subject, $recipients) {
        $CI = & get_instance();
        $email = $CI->email;
        $admin = $CI->admin->getByID(1);

        if (!$admin)
            return false;

        $email->from($admin->senderEmail, $admin->senderName);
        $email->to($recipients);

        $email->subject($subject);
        $email->message($template);


        if (!$email->send()) {
            show_error($email->print_debugger());
            return false;
        }

        return true;
    }

}
