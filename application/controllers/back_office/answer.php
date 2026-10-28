<?
class Answer extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="answer";
}

public function all_data($order = "")
{
	if ($order == "") {
		$order = "sort_order";
	}
	$listofuser = $this->fct->getAll($this->table, $order);
	$arrays = [];

	foreach ($listofuser as $row) { 
$row["PIDid_answer"] =
	'<input type="checkbox" name="cehcklist[]" value="'. $row["id_answer"].'" />';  if($row["id_question"] != 0){
		                     $cond=array("id_question" => $row["id_question"]);
						   $row["question"] =   $this->fct->getonecell_cached("question","title",$cond);
						    } 
						   else {
		  $row["question"] = '<small class="blue">--</small>';
	  }  if($row["true"] == 1)
		$row["true"] = "<span class=' label label-success' >Active</span>";
		else
		$row["true"] = "<span class=' label label-important ' >InActive</span>";  
		
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
// full list: forget the question of a previous answer/lists/<id_question> drill-down
$this->session->unset_userdata("bo_answer_question");
$data["title"]="List answer";
$data["content"]="back_office/answer/list";
// ~4000 rows: the grid loads them page by page from grid_data() (server mode)
$data["info"]=array();
$this->load->view("back_office/template",$data);
}

/** Server-mode rows of the full answer list (views/back_office/answer/list.php, 'ajax' => true). */
public function grid_data(){
bo_grid_output(bo_grid_data($this->table, array(
	'id_content' => 12,
	'columns' => array('title', 'id_question', 'true'),
	'search' => array('title', 'id_question_text'),
	'lookups' => array('id_question' => array('question', 'title', 'id_question')),
	'order' => 'id_answer desc',
)));
}

/** Answers of one question (drill-down from the question list: back_office/answer/lists/<id_question>). */
public function lists($id_question = 0){
$id_question = (int) $id_question;
if($id_question <= 0) { redirect(base_url()."back_office/answer"); }
// remembered so delete / bulk delete / save return to this question's answers
$this->session->set_userdata("bo_answer_question", $id_question);
$data["title"]="List answer";
$data["content"]="back_office/answer/list";
$data["info"]=$this->fct->getAll_cond($this->table,"sort_order",array("id_question"=>$id_question));
$this->load->view("back_office/template",$data);
}

/** List to return to after an action: the question drill-down when one is open, else the full list. */
private function _list_url(){
$id_question = (int) $this->session->userdata("bo_answer_question");
return base_url()."back_office/answer".($id_question > 0 ? "/lists/".$id_question : "");
}

public function add(){
$data["title"]="Add answer";
$data["content"]="back_office/answer/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit answer";
$data["content"]="back_office/answer/add";
$cond=array("id_answer"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_answer",$id);
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
$this->db->where("id_answer",$cehcklist[$i]);
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
$this->db->where("id_answer",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit answer";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("question", "question", "trim");
$this->form_validation->set_rules("true", "true", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_answer"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("answer",$cond); }
$data["content"]="back_office/answer/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_question"]=$this->input->post("question"); 
$_data["true"]=$this->input->post("true");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_answer",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	// drill-down open: go back to the answers of the question the answer was saved in
	if((int) $this->session->userdata("bo_answer_question") > 0 && (int) $_data["id_question"] > 0)
	$this->session->set_userdata("bo_answer_question", (int) $_data["id_question"]);
	redirect($this->_list_url());
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/answer/".$image)){
unlink("./uploads/answer/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'answer')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/answer/".$value."/".$image)){
unlink("./uploads/answer/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_answer",$id);
$this->db->update("answer",$_data);
redirect($this->_list_url());	
}

}