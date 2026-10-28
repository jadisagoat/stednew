<?
class Exam extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="exam";
}

public function all_data($order = "")
{
	if ($order == "") {
		$order = "sort_order";
	}
	$listofuser = $this->fct->getAll($this->table, $order);
	$arrays = [];

	foreach ($listofuser as $row) { 
$row["PIDid_exam"] =
	'<input type="checkbox" name="cehcklist[]" value="'. $row["id_exam"].'" />';  if($row["id_course"] != 0){
		                     $cond=array("id_course" => $row["id_course"]);
						   $row["course"] =   $this->fct->getonecell("course","title",$cond);
						    } 
						   else {
		  $row["course"] = '<small class="blue">--</small>';
	  }  $row["start_date"] =  $this->fct->date_out_formate($row["start_date"]) ; $row["close_date"] =  $this->fct->date_out_formate($row["close_date"]) ; if($row["hide_students"] == 1)
		$row["hide_students"] = "<span class=' label label-success' >Active</span>";
		else
		$row["hide_students"] = "<span class=' label label-important ' >InActive</span>";  
		
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
if($order == "")
$order ="sort_order";
$data["title"]="List exam";
$data["content"]="back_office/exam/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add exam";
$data["content"]="back_office/exam/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit exam";
$data["content"]="back_office/exam/add";
$cond=array("id_exam"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_exam",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
 
  redirect(base_url()."back_office/exam");
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
$this->db->where("id_exam",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/exam");	
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
$this->db->where("id_exam",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit exam";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("course", "course", "trim");
$this->form_validation->set_rules("start_date", "start date", "trim");
$this->form_validation->set_rules("start_date_time", "start date time", "trim");
$this->form_validation->set_rules("close_date", "close date", "trim");
$this->form_validation->set_rules("close_date_time", "close date time", "trim");
$this->form_validation->set_rules("time", "time", "trim");
$this->form_validation->set_rules("number_of_questions", "number of questions", "trim");
$this->form_validation->set_rules("number_of_attempts", "number of attempts", "trim");
$this->form_validation->set_rules("hide_students", "hide students", "trim");
$this->form_validation->set_rules("description", "description", "trim");
$this->form_validation->set_rules("image", "image", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_exam"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("exam",$cond); }
$data["content"]="back_office/exam/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_course"]=$this->input->post("course"); 

$_data["start_date"]=$this->fct->date_in_formate($this->input->post("start_date"));
$_data["start_date_time"]=$this->input->post("start_date_time");

$_data["close_date"]=$this->fct->date_in_formate($this->input->post("close_date"));
$_data["close_date_time"]=$this->input->post("close_date_time");
$_data["time"]=$this->input->post("time");
$_data["number_of_questions"]=$this->input->post("number_of_questions");
$_data["number_of_attempts"]=$this->input->post("number_of_attempts");
$_data["hide_students"]=$this->input->post("hide_students");
$_data["description"]=$this->input->post("description");
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_exam"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("exam","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/exam/'.$old_image)){
unlink("./uploads/exam/".$old_image);
 } }
$image1= $this->fct->uploadImage("image","exam");
$_data["image"]=$image1;	
}

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_exam",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/exam");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/exam/".$image)){
unlink("./uploads/exam/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'exam')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/exam/".$value."/".$image)){
unlink("./uploads/exam/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_exam",$id);
$this->db->update("exam",$_data);
redirect(base_url()."back_office/exam");	
}

}