<?
class News extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="news";
}

public function all_data($order = "")
{
	if ($order == "") {
		$order = "sort_order";
	}
	$listofuser = $this->fct->getAll($this->table, $order);
	$arrays = [];

	foreach ($listofuser as $row) { 
$row["PIDid_news"] =
	'<input type="checkbox" name="cehcklist[]" value="'. $row["id_news"].'" />';
		  if (!empty($row["image"])) {
			$row["image"] = '<a onclick="popup(\'' . base_url() . 'uploads/' . $this->table . '/' . $row["image"] . '\')" class="blue gallery" title="' . $row["title"] . '">show image</a>';
		} else {
			$row["image"] = '<small class="blue">No Image Available</small>';
		}  if($row["top_header"] == 1)
		$row["top_header"] = "<span class=' label label-success' >Active</span>";
		else
		$row["top_header"] = "<span class=' label label-important ' >InActive</span>";  
		
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
$data["title"]="List news";
$data["content"]="back_office/news/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add news";
$data["content"]="back_office/news/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit news";
$data["content"]="back_office/news/add";
$cond=array("id_news"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_news",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
 
  redirect(base_url()."back_office/news");
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
$this->db->where("id_news",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/news");	
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
$this->db->where("id_news",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit news";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("image", "image", "trim");
$this->form_validation->set_rules("top_header", "top header", "trim");
$this->form_validation->set_rules("small_description", "small description", "trim");
$this->form_validation->set_rules("description", "description", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_news"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("news",$cond); }
$data["content"]="back_office/news/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_news"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("news","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/news/'.$old_image)){
unlink("./uploads/news/".$old_image);
$sumb_val1=explode(",","85x85,420x250");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/news/".$value."/".$old_image)){
unlink("./uploads/news/".$value."/".$old_image);	 }							
} 
 } }
$image1= $this->fct->uploadImage("image","news");
$this->fct->createthumb1($image1,"news","85x85,420x250");$_data["image"]=$image1;	
}
$_data["top_header"]=$this->input->post("top_header");
$_data["small_description"]=$this->input->post("small_description");
$_data["description"]=$this->input->post("description");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_news",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/news");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/news/".$image)){
unlink("./uploads/news/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'news')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/news/".$value."/".$image)){
unlink("./uploads/news/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_news",$id);
$this->db->update("news",$_data);
redirect(base_url()."back_office/news");	
}

}