<?
class Question extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="question";
}

public function all_data($order = "")
{
	if ($order == "") {
		$order = "sort_order";
	}
	$listofuser = $this->fct->getAll($this->table, $order);
	$arrays = [];

	foreach ($listofuser as $row) { 
$row["PIDid_question"] =
	'<input type="checkbox" name="cehcklist[]" value="'. $row["id_question"].'" />';  if($row["id_exam"] != 0){
		                     $cond=array("id_exam" => $row["id_exam"]);
						   $row["exam"] =   $this->fct->getonecell_cached("exam","title",$cond);
						    } 
						   else {
		  $row["exam"] = '<small class="blue">--</small>';
	  } 
		  if (!empty($row["image"])) {
			$row["image"] = '<a onclick="popup(\'' . base_url() . 'uploads/' . $this->table . '/' . $row["image"] . '\')" class="blue gallery" title="' . $row["title"] . '">show image</a>';
		} else {
			$row["image"] = '<small class="blue">No Image Available</small>';
		} 
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
// full list: forget the exam of a previous question/lists/<id_exam> drill-down
$this->session->unset_userdata("bo_question_exam");
if($order == "")
$order ="sort_order";
$data["title"]="List question";
$data["content"]="back_office/question/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

/** Questions of one exam (drill-down from the exam list: back_office/question/lists/<id_exam>). */
public function lists($id_exam = 0){
$id_exam = (int) $id_exam;
if($id_exam <= 0) { redirect(base_url()."back_office/question"); }
// remembered so delete / bulk delete / save / delete image return to this exam's questions
$this->session->set_userdata("bo_question_exam", $id_exam);
$data["title"]="List question";
$data["content"]="back_office/question/list";
$data["info"]=$this->fct->getAll_cond($this->table,"sort_order",array("id_exam"=>$id_exam));
$this->load->view("back_office/template",$data);
}

/** List to return to after an action: the exam drill-down when one is open, else the full list. */
private function _list_url(){
$id_exam = (int) $this->session->userdata("bo_question_exam");
return base_url()."back_office/question".($id_exam > 0 ? "/lists/".$id_exam : "");
}

public function add(){
$data["title"]="Add question";
$data["content"]="back_office/question/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit question";
$data["content"]="back_office/question/add";
$cond=array("id_question"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_question",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
 
  redirect($this->_list_url());
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
$this->db->where("id_question",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect($this->_list_url());	
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
$this->db->where("id_question",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit question";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("exam", "exam", "trim");
$this->form_validation->set_rules("rate", "rate", "trim");
$this->form_validation->set_rules("image", "image", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_question"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("question",$cond); }
$data["content"]="back_office/question/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_exam"]=$this->input->post("exam"); 
$_data["rate"]=$this->input->post("rate");
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_question"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("question","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/question/'.$old_image)){
unlink("./uploads/question/".$old_image);
 } }
$image1= $this->fct->uploadImage("image","question");
$_data["image"]=$image1;	
}

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_question",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	// drill-down open: go back to the questions of the exam the question was saved in
	if((int) $this->session->userdata("bo_question_exam") > 0 && (int) $_data["id_exam"] > 0)
	$this->session->set_userdata("bo_question_exam", (int) $_data["id_exam"]);
	redirect($this->_list_url());
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/question/".$image)){
unlink("./uploads/question/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'question')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/question/".$value."/".$image)){
unlink("./uploads/question/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_question",$id);
$this->db->update("question",$_data);
redirect($this->_list_url());	
}

}