<?
class Contactform extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="contactform";
}

public function all_data($order = "")
{
	if ($order == "") {
		$order = "sort_order";
	}
	$listofuser = $this->fct->getAll($this->table, $order);
	$arrays = [];

	foreach ($listofuser as $row) { 
$row["PIDid_contactform"] =
	'<input type="checkbox" name="cehcklist[]" value="'. $row["id_contactform"].'" />';
 $arrays[] = $row;
	}
	$response["data"] = $arrays;
	////-------------------------------------limiting nb of records to only a page-------------------
	$totalRecords_num = count($listofuser);
	//-----------------------------------------------------------------------------------------------------
	if ($totalRecords_num > 0) {
	} else {
		$response["data"] = null;
	}
	// // Set appropriate headers
	header("Content-Type: application/json");
	echo json_encode($response);
}

public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List contactform";
$data["content"]="back_office/contactform/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add contactform";
$data["content"]="back_office/contactform/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit contactform";
$data["content"]="back_office/contactform/add";
$cond=array("id_contactform"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_contactform",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
 
  redirect(base_url()."back_office/contactform");
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
redirect(base_url()."back_office/contactform");	
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
$data["title"]="Add / Edit contactform";
$this->form_validation->set_rules("name", "name", "trim");
$this->form_validation->set_rules("subject", "subject", "trim");
$this->form_validation->set_rules("email", "email", "trim");
$this->form_validation->set_rules("comments", "comments", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_contactform"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("contactform",$cond); }
$data["content"]="back_office/contactform/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["name"]=$this->input->post("name");
$_data["subject"]=$this->input->post("subject");
$_data["email"]=$this->input->post("email");
$_data["comments"]=$this->input->post("comments");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_contactform",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/contactform");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/contactform/".$image)){
unlink("./uploads/contactform/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'contactform')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/contactform/".$value."/".$image)){
unlink("./uploads/contactform/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_contactform",$id);
$this->db->update("contactform",$_data);
redirect(base_url()."back_office/contactform");	
}

}