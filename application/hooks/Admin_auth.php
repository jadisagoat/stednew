<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin_auth hook (post_controller_constructor)
 *
 * Security: the back office used to check the login only inside
 * views/back_office/template.php, so any action that saved / deleted
 * data before loading the template ran without a session.
 * This hook applies the same check ("user_id" in session) to every
 * back_office controller before the method runs, and mirrors the
 * existing menu permissions:
 *  - level >= 1 (login from the "admin" table): full access
 *  - level 0 + role Admin (id_role 1, login from the "user" table): access,
 *    except the developer / database tools
 *  - level 0 + other roles (lecturers, students - they have no menu
 *    permissions): dashboard and their own profile only
 */
class Admin_auth
{
	// back_office/home methods that must stay public (login screen)
	var $public_methods = array('index', 'login_validate', 'logout', 'lang');

	// admin-only tools that live outside the back_office folder
	var $admin_controllers = array('formextreme', 'downloader');

	// developer test methods (phpinfo, admin password dump, file writes) - disabled for everyone
	var $disabled_methods = array('content/test', 'settings/test', 'settings/index11');

	// code generator / database tools - admin table logins (level >= 1) only
	var $level_admin_controllers = array('control', 'install', 'manage_db');
	var $level_admin_methods = array('settings/submit');

	// what a level-0 non-admin user (lecturer / student) may open
	var $basic_user_methods = array('home/dashboard', 'home/logout', 'settings/index', 'settings/submit_user');

	function check()
	{
		$CI = &get_instance();

		$directory = strtolower(trim($CI->router->fetch_directory(), '/'));
		$class = strtolower($CI->router->fetch_class());
		$method = strtolower($CI->router->fetch_method());
		$action = $class . '/' . $method;

		$is_back_office = ($directory === 'back_office');
		$is_admin_tool = ($directory === '' && in_array($class, $this->admin_controllers));

		if (!$is_back_office && !$is_admin_tool)
			return;

		if ($is_back_office && in_array($action, $this->disabled_methods))
			show_404();

		if ($is_back_office && $class === 'home' && in_array($method, $this->public_methods))
			return;

		if (!$CI->session->userdata('user_id')) {
			redirect(base_url() . 'back_office/home');
			exit();
		}

		if ((int) $CI->session->userdata('level') >= 1)
			return;

		// level 0 = login from the "user" table
		$denied = $is_admin_tool
			|| in_array($class, $this->level_admin_controllers)
			|| in_array($action, $this->level_admin_methods)
			|| ($CI->session->userdata('id_role') != 1 && !in_array($action, $this->basic_user_methods));

		if ($denied) {
			redirect(base_url() . 'back_office/home/dashboard');
			exit();
		}
	}
}
