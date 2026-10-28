<?
class Unit extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="unit";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List unit";
$data["content"]="back_office/unit/list";
$cond=array("id_material" => $this->session->userdata("id_material"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data);
}



public function lists($id_material="",$order = ""){
$this->load->library('session');

if($id_material){
$id_material = array('id_material'  => $id_material );
$this->session->set_userdata($id_material);
}

if($order == "")
$order ="sort_order";
$data["title"]="List unit";
$data["content"]="back_office/unit/list";
$cond=array("id_material" => $this->session->userdata("id_material"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data); 
}

public function add(){
$data["title"]="Add unit";
$data["content"]="back_office/unit/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit unit";
$data["content"]="back_office/unit/add";
$cond=array("id_unit"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_unit",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/unit");
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
$this->db->where("id_unit",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/unit");	
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
$this->db->where("id_unit",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit unit";
$this->form_validation->set_rules("title", "title", "trim|required");
//$this->form_validation->set_rules("class", "class", "trim|required");
$this->form_validation->set_rules("material", "material", "trim|required");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_unit"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("unit",$cond); }
$data["content"]="back_office/unit/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_classes"]=$this->input->post("class"); 
$_data["id_material"]=$this->input->post("material"); 

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_unit",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/unit");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/unit/".$image)){
unlink("./uploads/unit/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'unit')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/unit/".$value."/".$image)){
unlink("./uploads/unit/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_unit",$id);
$this->db->update("unit",$_data);
redirect(base_url()."back_office/unit");	
}

}
