<?
class Manage_db extends CI_Controller{
public function __construct()
{
parent::__construct();
// Your own constructor code
//$this->headers='back_office/includes/header';
//$this->footer='back_office/includes/footer';

}


public function index(){
$data["title"]="Manage DataBases Section";
$query=$this->db->get('manage_db');
$data["db"]=$query->result_array();
$data["content"]="back_office/db_list";
$this->load->view('back_office/template',$data);
}


public function create(){
$data["title"]="Manage DataBases Section";
$data["content"]="back_office/db_create";
$this->load->view('back_office/template',$data);	
}
	
public function submit(){
$this->form_validation->set_rules('db_name', 'DataBase Name', 'trim|required|min_length[5]|max_length[12]|xss_clean');	
if ($this->form_validation->run() == FALSE){
$data["title"]="Manage DataBases Section";
$data["content"]="back_office/db_create";
$this->load->view('back_office/template',$data);
}else{
echo $db_name= $this->input->post('db_name');
echo $db_selected = mysql_select_db($db_name);
if($db_selected==1){
$this->session->set_userdata('error_message','This Database already exist .');
$data["title"]="Manage DataBases Section";
$data["content"]="back_office/db_create";
$this->load->view('back_office/template',$data);
}
else{
$this->db->query('CREATE DATABASE IF NOT EXISTS '.$db_name);
$data_=array('db_name' => $db_name );
$this->db->insert('manage_db',$data_);
redirect(base_url().'back_office/manage_db');
	 }
}
}

// Delete Database 
public function delete($db_name){
	$this->db->where('db_name',$db_name );
	$this->db->delete('manage_db'); 
	$this->db->query('DROP DATABASE IF EXISTS '.$db_name);
	redirect(base_url().'back_office/manage_db');
}
// Delete All
public function delete_all(){
$cehcklist= $this->input->post('cehcklist');
$check_option= $this->input->post('check_option');

if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
	if($cehcklist[$i] != ''){
	$this->db->where('db_name',$cehcklist[$i]);
	$this->db->delete('manage_db'); 
	$this->db->query('DROP DATABASE IF EXISTS '.$cehcklist[$i]);
	}
} } }
	redirect(base_url().'back_office/manage_db');	
}

}