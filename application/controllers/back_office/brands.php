<?
class Brands extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="brands";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List brands";
$data["content"]="back_office/brands/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add brands";
$data["content"]="back_office/brands/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit brands";
$data["content"]="back_office/brands/add";
$cond=array("id_brands"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_brands",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/brands");
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
$this->db->where("id_brands",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/brands");	
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
$this->db->where("id_brands",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit brands";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("image", "image", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_brands"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("brands",$cond); }
$data["content"]="back_office/brands/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_brands"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("brands","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/brands/'.$old_image)){
unlink("./uploads/brands/".$old_image);
 } }
$image1= $this->fct->uploadImage("image","brands");
$_data["image"]=$image1;	
}

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_brands",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/brands");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/brands/".$image)){
unlink("./uploads/brands/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'brands')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/brands/".$value."/".$image)){
unlink("./uploads/brands/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_brands",$id);
$this->db->update("brands",$_data);
redirect(base_url()."back_office/brands");	
}

}