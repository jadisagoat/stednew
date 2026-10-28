<?
class Newsletter extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="newsletter";
}


public function index(){
$data["title"]="List Emails";
$data["content"]="back_office/newsletter/list";
$data["info"]=$this->fct->getAll($this->table,'sort_order');
$this->load->view('back_office/template',$data);
}

public function add(){
$data["title"]="Add Email";
$data["content"]="back_office/newsletter/add";
$this->load->view('back_office/template',$data);
} 

public function edit($id){
$data["title"]="Edit Email";
$data["content"]="back_office/newsletter/add";
$cond=array('id_newsletter'=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view('back_office/template',$data);
}

public function delete($id){
$_data=array('deleted'=>1,
'deleted_date'=>date("Y-m-d h:i:s"));
$this->db->where('id_newsletter',$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata('success_message','Information was deleted successfully');
redirect(base_url().'back_office/newsletter');
}

public function delete_all(){
$cehcklist= $this->input->post('cehcklist');
$check_option= $this->input->post('check_option');
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ''){
$_data=array('deleted'=>1,
'deleted_date'=>date("Y-m-d h:i:s"));
$this->db->where('id_newsletter',$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata('success_message','Informations were deleted successfully');
redirect(base_url().'back_office/newsletter');	
}
elseif($check_option == "send_news"){
$data["title"]="Send News Letter";

if(count($cehcklist) > 0){
$emails_id=array();
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ''){
$emails_id[]=$cehcklist[$i];
}
} } 
$data["ids"]=$emails_id;
$data["content"]="back_office/newsletter/send";
$this->load->view('back_office/template',$data);	
}
else{
redirect(base_url().'back_office/newsletter');	
}
}

public function sorted(){
$sort=array();
foreach($this->input->get('table-1') as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array('sort_order'=>$i);
$this->db->where('id_newsletter',$sort[$i]);
$this->db->update($this->table,$_data);	
}
}


public function submit(){
$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

if ($this->form_validation->run() == FALSE){
$data["title"]="Add Email";
if($this->input->post('id')!=''){
$cond=array('id_newsletter'=>$this->input->post('id'));
$data["id"]=$this->input->post('id');
$data["info"]=$this->fct->getonerecord($this->table,$cond);	
}
$data["content"]="back_office/newsletter/add";
$this->load->view('back_office/template',$data);
}
else
{
$this->db->where('email',$this->input->post('email'));
$query=$this->db->get('newsletter');
$res=$query->row_array();
if(count($res) > 0 && $this->input->post('id') == ''){
$data["title"]="Add Email";
$this->session->set_userdata('error_message','This email already exist .');
$data["content"]="back_office/newsletter/add";
$this->load->view('back_office/template',$data);	
}
else{
$_data=array(
'email'=>$this->input->post('email'));
if($this->input->post('id')!=''){	
$_data["updated_date"]=date("Y-m-d h:i:s");
$this->db->where('id_newsletter',$this->input->post('id'));
$this->db->update($this->table,$_data);
$this->session->set_userdata('success_message','Information was updated successfully');
} else {
$_data["created_date"]=date("Y-m-d h:i:s");
$this->db->insert($this->table,$_data); 	
$this->session->set_userdata('success_message','Information was inserted successfully');
}
redirect(base_url().'back_office/newsletter');
}
}
}


public function send_newsletter(){
$message=$this->input->post('message');
$ids=$this->input->post('ids');
if(!empty($_FILES['image']['name'])){
$image1= $this->fct->uploadImage('image','pdf');
}
// send emails 
$cond=array('admin_id' => 2);
$email_from = $this->fct->getonecell('admin','email',$cond);
$website_title = $this->fct->getonecell('admin','website_title',$cond);
$emails = explode(',',$ids);
$this->load->library('email');
$config= Array(
    'mailtype'  => 'html'
);
$this->email->initialize($config);
$this->email->set_newline("\r\n");
for($i=0;$i<count($emails);$i++){
$this->email->from($email_from);
$this->email->to($emails[$i]);
$this->email->subject($website_title." News Letter");
$this->email->message($message);
$this->email->attach('./uploads/pdf/'.$image1);
$this->email->send();
}
$this->session->set_userdata('success_message','News Letter was sent successfully');	
redirect(base_url().'back_office/newsletter');

}

}