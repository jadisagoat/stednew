<?
class Category extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="category";
}

public function all_data($order = "")
{
	if ($order == "") {
		$order = "sort_order";
	}
	$listofuser = $this->fct->getAll($this->table, $order);
	$arrays = [];

	foreach ($listofuser as $row) { 
		$id_cat = $row["id_category"];
		
		// Fetch courses for this category
		$courses = $this->db->get_where('course', array('id_category' => $id_cat, 'deleted' => 0))->result_array();
		$course_count = count($courses);
		
		$desc_html = "<ul>";
		if (!empty($courses)) {
			foreach ($courses as $crs) {
				// Count content for this course
				$cnt_count = $this->db->where(array('id_course' => $crs['id_course'], 'deleted' => 0))->count_all_results('content');
				$desc_html .= "<li>" . $crs['title'] . " ($cnt_count)</li>";
			}
		} else {
			$desc_html .= "<li>No courses</li>";
		}
		$desc_html .= "</ul>";
		
		$row["description"] = $desc_html;
		$row["course_count"] = $course_count;

$row["PIDid_category"] =
	'<input type="checkbox" name="cehcklist[]" value="'. $row["id_category"].'" />';  if($row["id_group_category"] != 0){
		                     $cond=array("id_group_category" => $row["id_group_category"]);
						   $row["group_category"] =   $this->fct->getonecell_cached("group_category","title",$cond);
						    } 
						   else {
		  $row["group_category"] = '<small class="blue">--</small>';
	  }  if($row["active"] == 1)
		$row["active"] = "<span class=' label label-success' >Active</span>";
		else
		$row["active"] = "<span class=' label label-important ' >InActive</span>";  
		 if($row["is_in_footer"] == 1)
		$row["is_in_footer"] = "<span class=' label label-success' >Active</span>";
		else
		$row["is_in_footer"] = "<span class=' label label-important ' >InActive</span>";  
		
		  if (!empty($row["image"])) {
			$row["image"] = '<a onclick="popup(\'' . base_url() . 'uploads/' . $this->table . '/' . $row["image"] . '\')" class="blue gallery" title="' . $row["title"] . '">show image</a>';
		} else {
			$row["image"] = '<small class="blue">No Image Available</small>';
		}   if($row["id_home_sections"] != 0){
		                     $cond=array("id_home_sections" => $row["id_home_sections"]);
						   $row["section"] =   $this->fct->getonecell_cached("home_sections","title",$cond);
						    } 
						   else {
		  $row["section"] = '<small class="blue">--</small>';
	  } 
	  $row["sort_order"] = (int)$row["sort_order"];
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
$order = preg_replace('/[^A-Za-z0-9_ ]/', '', $order); // Security: ORDER BY comes from the URL
$data["title"]="List category";
$data["content"]="back_office/category/list";
$data["info"]=$this->fct->getAll($this->table,$order);
// grid (list.php): number of courses of each category + the "course (nb of contents)" list shown as the count
// tooltip, as the legacy all_data() computed it (2 queries instead of one per category / course)
$counts = array();
foreach ($this->db->query("SELECT id_course, COUNT(*) AS n FROM content WHERE deleted = 0 GROUP BY id_course")->result_array() as $c) $counts[$c["id_course"]] = (int) $c["n"];
$by_cat = array();
foreach ($this->db->query("SELECT id_course, id_category, title FROM course WHERE deleted = 0 ORDER BY sort_order, id_course")->result_array() as $crs) {
	$by_cat[$crs["id_category"]][] = $crs["title"] . " (" . (isset($counts[$crs["id_course"]]) ? $counts[$crs["id_course"]] : 0) . ")";
}
foreach ($data["info"] as $i => $row) {
	$id_cat = $row["id_category"];
	$data["info"][$i]["course_count"] = isset($by_cat[$id_cat]) ? count($by_cat[$id_cat]) : 0;
	$data["info"][$i]["course_tree"] = isset($by_cat[$id_cat]) ? implode("\n", $by_cat[$id_cat]) : "";
}
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add category";
$data["content"]="back_office/category/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit category";
$data["content"]="back_office/category/add";
$cond=array("id_category"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_category",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
 
  redirect(base_url()."back_office/category");
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
$this->db->where("id_category",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/category");	
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
$this->db->where("id_category",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit category";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("group_category", "group category", "trim");
$this->form_validation->set_rules("active", "active", "trim");
$this->form_validation->set_rules("is_in_footer", "is in footer", "trim");
$this->form_validation->set_rules("image", "image", "trim");
$this->form_validation->set_rules("description", "description", "trim");
$this->form_validation->set_rules("section", "section", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_category"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("category",$cond); }
$data["content"]="back_office/category/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_group_category"]=$this->input->post("group_category"); 
$_data["active"]=$this->input->post("active");
$_data["is_in_footer"]=$this->input->post("is_in_footer");
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_category"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("category","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/category/'.$old_image)){
unlink("./uploads/category/".$old_image);
 } }
$image1= $this->fct->uploadImage("image","category");
$_data["image"]=$image1;	
}
$_data["description"]=$this->input->post("description");
$_data["id_home_sections"]=$this->input->post("section"); 

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_category",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/category");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/category/".$image)){
unlink("./uploads/category/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'category')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/category/".$value."/".$image)){
unlink("./uploads/category/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_category",$id);
$this->db->update("category",$_data);
redirect(base_url()."back_office/category");	
}

}