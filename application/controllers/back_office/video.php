<?
class Video extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="video";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List video";
$data["content"]="back_office/video/list";
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
$data["title"]="List video";
$data["content"]="back_office/video/list";
$cond=array("id_course" => $this->session->userdata("id_course"));
$data["info"]=$this->fct->getAll_cond($this->table,$order,$cond);
$this->load->view("back_office/template",$data); 
}


public function add(){
$data["title"]="Add video";
$data["content"]="back_office/video/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit video";
$data["content"]="back_office/video/add";
$cond=array("id_video"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_video",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/video");
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
$this->db->where("id_video",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/video");	
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
$this->db->where("id_video",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit video";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("video_file", "video file", "trim");
$this->form_validation->set_rules("summary", "summary", "trim");
$this->form_validation->set_rules("image", "image", "trim");
$this->form_validation->set_rules("course", "course", "trim");
$this->form_validation->set_rules("youtube", "youtube", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_video"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("video",$cond); }
$data["content"]="back_office/video/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");

if(!empty($_FILES["video_file"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_video"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("video","video_file",$cond_file);
if(!empty($old_file))
unlink("./uploads/video/".$old_file);	
}
$file1= $this->fct->uploadImage("video_file","video");
$_data["video_file"]=$file1;	
}
$_data["summary"]=$this->input->post("summary");
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_video"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("video","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/video/'.$old_image)){
unlink("./uploads/video/".$old_image);
$sumb_val1=explode(",","120x90");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/video/".$value."/".$old_image)){
unlink("./uploads/video/".$value."/".$old_image);	 }							
} 
 } }
$image1= $this->fct->uploadImage("image","video");
$this->fct->createthumb1($image1,"video","120x90");$_data["image"]=$image1;	
}
$_data["id_course"]=$this->input->post("course"); 
$_data["youtube"]=$this->input->post("youtube");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_video",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/video");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/video/".$image)){
unlink("./uploads/video/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'video')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/video/".$value."/".$image)){
unlink("./uploads/video/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_video",$id);
$this->db->update("video",$_data);
redirect(base_url()."back_office/video");	
}

}