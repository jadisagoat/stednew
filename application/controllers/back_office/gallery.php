<?
class Gallery extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="gallery";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List gallery";
$data["content"]="back_office/gallery/list";
// $data["info"]=$this->fct->getAll($this->table,$order);
// $this->load->view("back_office/template",$data);
// }

$cond=array("id_course" => $this->session->userdata("id_course"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data);
}



public function lists($id_course="",$order = ""){
$this->load->library('session');

if($id_course){
$id_course = array('id_course'  => $id_course );
$this->session->set_userdata($id_course);
}

if($order == "")
$order ="sort_order";
$data["title"]="List gallery";
$data["content"]="back_office/gallery/list";
$cond=array("id_course" => $this->session->userdata("id_course"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data); 
}


public function add(){
$data["title"]="Add gallery";
$data["content"]="back_office/gallery/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit gallery";
$data["content"]="back_office/gallery/add";
$cond=array("id_gallery"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_gallery",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/gallery");
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
$this->db->where("id_gallery",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/gallery");	
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
$this->db->where("id_gallery",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit gallery";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("image", "image", "trim");
$this->form_validation->set_rules("course", "course", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_gallery"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("gallery",$cond); }
$data["content"]="back_office/gallery/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_gallery"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("gallery","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/gallery/'.$old_image)){
unlink("./uploads/gallery/".$old_image);
$sumb_val1=explode(",","600x480");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/gallery/".$value."/".$old_image)){
unlink("./uploads/gallery/".$value."/".$old_image);	 }							
} 
 } }
$image1= $this->fct->uploadImage("image","gallery");
$this->fct->createthumb1($image1,"gallery","600x480");$_data["image"]=$image1;	
}
$_data["id_course"]=$this->input->post("course"); 

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_gallery",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/gallery");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/gallery/".$image)){
unlink("./uploads/gallery/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'gallery')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/gallery/".$value."/".$image)){
unlink("./uploads/gallery/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_gallery",$id);
$this->db->update("gallery",$_data);
redirect(base_url()."back_office/gallery");	
}

}