<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/


//$route['(:any)'] = "pages/$1";


$route['default_controller'] = "home";
$route['cms'] = "back_office/home";
$route['back_office/home/dashboard'] = "back_office/home/dashboard";
$route['back_office/manage_project'] = "back_office/manage_project";
$route['404_override'] = 'error404';

$route['category/(:any)'] = "home/category/$1";
$route['coursedetails/(:any)'] = "home/coursedetails/$1";
$route['aboutus'] = "home/aboutus";
$route['faqs'] = "home/faqs";
$route['contactus'] = "home/contactus";

$route['getLogin'] = "home/getLogin";
$route['updateLogin'] = "home/updateLogin";
$route['resetpassword/(:any)'] = "home/resetpassword/$1";
$route['updatepassword'] = "home/updatepassword";
$route['activateyourpassword/(:any)'] = "home/activateyourpassword/$1";


$route['logout'] = "home/logout";
$route['Registration'] = "home/Registration";

$route['edit-profile'] = "home/editprofile";
$route['edit-profile/(:any)'] = "home/editprofile/$1";
$route['enrolled-courses'] = "home/enrolled_courses";
$route['wishlist'] = "home/wishlistdata";
$route['news/(:any)'] = "home/news/$1";
$route['categorydetails/(:any)'] = "home/categorydetails/$1";
$route['chatbot'] = "home/chatbot";
$route['video/(:any)'] = "home/videodata/$1";


$route['listuserscontent'] = "course/listuserscontent";
$route['listuserscontent/(:any)'] = "course/listuserscontent/$1";
$route['userscontent'] = "course/userscontent";

$route['examcourse'] = "course/examcourse";
$route['examcourse/(:any)'] = "course/examcourse/$1";
$route['listexam'] = "course/listexam";

$route['editexam'] = "course/editexam";
$route['editexam/(:any)'] = "course/editexam/$1";

$route['addexam'] = "course/addexam";


$route['newsletter'] = "home/newsletter";

$route['listapidata'] = "course/listapidata";
$route['getapidata'] = "course/getapidata";

$route['deleteexam/(:any)'] = "course/deleteexam/$1";
$route['teacherprofile/(:any)'] = "home/teacherprofile/$1";

$route['listusers'] = "users/listusers";
$route['employees'] = "users/listemployeenotregistered";


$route['mycourses'] = "home/my_courses";

$route['courseexamhistory'] = "course/courseexamhistory";
$route['courseexamhistory/(:any)'] = "course/courseexamhistory/$1";
$route['listexamhistory'] = "course/listexamhistory";


$route['feedbackhistory'] = "course/feedbackhistory";
$route['feedbackhistory/(:any)'] = "course/feedbackhistory/$1";
$route['listfeebacks'] = "course/listfeebacks";

$route['listcourses'] = "course/listcourses";

$route['totalexamshistory'] = "course/totalexamshistory";

$route['totallistexamshistory'] = "course/totallistexamshistory";


// $route['datalistexam/(:any)'] = "course/listexam/$1";

//$route['_404'] = '';
/* End of file routes.php */
/* Location: ./application/config/routes.php */
