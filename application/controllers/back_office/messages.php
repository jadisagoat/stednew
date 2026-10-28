<?
class Messages extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="contactform";
}


public function index(){
$data["title"]="List All Messages";
$data["content"]="back_office/messages/list";
$q="SELECT * FROM `".$this->table."` WHERE deleted = 0 ORDER BY created_date DESC";
$query=$this->db->query($q);
$data["info"]=$query->result_array();
$this->session->set_userdata('message_link','');
$this->load->view("back_office/template",$data);
}

public function opened(){
$data["title"]="List Archived Messages";
$data["content"]="back_office/messages/list";
$q="SELECT * FROM `".$this->table."` WHERE deleted = 0 AND readed = 1 ORDER BY created_date DESC";
$query=$this->db->query($q);
$data["info"]=$query->result_array();
$this->session->set_userdata('message_link','opened');
$this->load->view("back_office/template",$data);
}

public function unread(){
$data["title"]="List Unread Messages";
$data["content"]="back_office/messages/list";
$q="SELECT * FROM `".$this->table."` WHERE deleted = 0 AND readed = 0 ORDER BY created_date DESC";
$query=$this->db->query($q);
$data["info"]=$query->result_array();
$this->session->set_userdata('message_link','unread');
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add Messages";
$cond1=array('name'=>"news");
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$cond=array('id_content'=>16);
$data["attr"]=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
$data["content"]="back_office/news/add";
$this->load->view("back_office/template",$data);
} 

public function read($id){
$data["title"]="Read Message";
$data["content"]="back_office/messages/add";
$cond=array("id_contactform"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$_data["readed"]=1;
$this->db->where("id_contactform",$id);
$this->db->update($this->table,$_data);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_contactform",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/messages/".$this->session->userdata('message_link'));
}

public function delete_all(){
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ""){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_contactform",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/messages/".$this->session->userdata('message_link'));	
}

public function sorted(){
$sort=array();
foreach($this->input->get("table-1") as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array("sort_order"=>$i);
$this->db->where("id_contactform",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
 $to=$this->input->post('to');
 $subject=$this->input->post('subject');
 $message=$this->input->post('message');
$cond=array('admin_id' => 2);
 $email = $this->fct->getonecell('admin','email',$cond);
$id=$this->input->post('id');
// send email 
$this->load->library('email');
$config= Array(
    'mailtype'  => 'html'
);
$this->email->initialize($config);
$this->email->set_newline("\r\n");
$this->email->from($email);
$this->email->to($to);
$this->email->subject($subject);
$this->email->message($message);
//$this->email->attach('./uploads/apply_career/'.$career_file_new);
$this->email->send();
$this->session->set_userdata("success_message","Email was sent successfully");
redirect(base_url()."back_office/messages/read/".$id);
}

}