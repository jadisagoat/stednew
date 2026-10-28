<?
class Priviledge_role_permissions extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="priviledge_role_permissions";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List priviledge role permissions";
$data["content"]="back_office/priviledge_role_permissions/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add priviledge role permissions";
$data["content"]="back_office/priviledge_role_permissions/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit priviledge role permissions";
$data["content"]="back_office/priviledge_role_permissions/add";
$cond=array("id_priviledge_role_permissions"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_priviledge_role_permissions",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/priviledge_role_permissions");
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
$this->db->where("id_priviledge_role_permissions",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/priviledge_role_permissions");	
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
$this->db->where("id_priviledge_role_permissions",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit priviledge role permissions";
$this->form_validation->set_rules("role", "role", "trim|required");
$this->form_validation->set_rules("permissions", "permissions", "trim|required");
$this->form_validation->set_rules("read_p", "read_p", "trim");
$this->form_validation->set_rules("write_p", "write_p", "trim");
$this->form_validation->set_rules("delete_p", "delete_p", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_priviledge_role_permissions"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("priviledge_role_permissions",$cond); }
$data["content"]="back_office/priviledge_role_permissions/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["id_role"]=$this->input->post("role"); 
$_data["id_permissions"]=$this->input->post("permissions"); 
$_data["read_p"]=$this->input->post("read_p");
$_data["write_p"]=$this->input->post("write_p");
$_data["delete_p"]=$this->input->post("delete_p");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_priviledge_role_permissions",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/priviledge_role_permissions");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/priviledge_role_permissions/".$image)){
unlink("./uploads/priviledge_role_permissions/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'priviledge role permissions')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/priviledge_role_permissions/".$value."/".$image)){
unlink("./uploads/priviledge_role_permissions/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_priviledge_role_permissions",$id);
$this->db->update("priviledge_role_permissions",$_data);
redirect(base_url()."back_office/priviledge_role_permissions");	
}

}