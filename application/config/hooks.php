<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/



/* End of file hooks.php */
/* Location: ./application/config/hooks.php */

// Security: require the admin session for every back_office action (see hooks/Admin_auth.php)
$hook['post_controller_constructor'] = array(
	'class'    => 'Admin_auth',
	'function' => 'check',
	'filename' => 'Admin_auth.php',
	'filepath' => 'hooks'
);
