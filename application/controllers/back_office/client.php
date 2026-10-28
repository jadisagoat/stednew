<?
class Client extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="client";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List client";
$data["content"]="back_office/client/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add client";
$data["content"]="back_office/client/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit client";
$data["content"]="back_office/client/add";
$cond=array("id_client"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_client",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/client");
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
$this->db->where("id_client",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/client");	
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
$this->db->where("id_client",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit client";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("email", "email", "trim");
$this->form_validation->set_rules("profession", "profession", "trim");
$this->form_validation->set_rules("nationality", "nationality", "trim");
$this->form_validation->set_rules("resident", "resident", "trim");
$this->form_validation->set_rules("phone", "phone", "trim");
$this->form_validation->set_rules("gender", "gender", "trim");
$this->form_validation->set_rules("company", "company", "trim");
$this->form_validation->set_rules("password", "password", "trim");
$this->form_validation->set_rules("role", "role", "trim");
$this->form_validation->set_rules("image", "image", "trim");
$this->form_validation->set_rules("user", "user", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_client"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("client",$cond); }
$data["content"]="back_office/client/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["email"]=$this->input->post("email");
$_data["profession"]=$this->input->post("profession");
$_data["id_countries"]=$this->input->post("nationality"); 
$_data["resident"]=$this->input->post("resident");
$_data["phone"]=$this->input->post("phone");
$_data["id_gender"]=$this->input->post("gender"); 
$_data["company"]=$this->input->post("company");
$_data["password"]=$this->input->post("password");
$_data["id_role"]=$this->input->post("role"); 
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_client"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("client","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/client/'.$old_image)){
unlink("./uploads/client/".$old_image);
$sumb_val1=explode(",","45x45");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/client/".$value."/".$old_image)){
unlink("./uploads/client/".$value."/".$old_image);	 }							
} 
 } }
$image1= $this->fct->uploadImage("image","client");
$this->fct->createthumb1($image1,"client","45x45");$_data["image"]=$image1;	
}
$_data["id_user"]=$this->input->post("user"); 

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_client",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/client");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/client/".$image)){
unlink("./uploads/client/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'client')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/client/".$value."/".$image)){
unlink("./uploads/client/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_client",$id);
$this->db->update("client",$_data);
redirect(base_url()."back_office/client");	
}

}