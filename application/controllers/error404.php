<?
class Error404 extends CI_Controller{
	
public function index(){
set_status_header(404); // real 404 status so search engines drop missing pages
$this->load->view('404');	
}
}