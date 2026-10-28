<?
class Group_category extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="group_category";
}

public function all_data($order = "")
{
	if ($order == "") {
		$order = "sort_order";
	}
	$order = preg_replace('/[^A-Za-z0-9_ ]/', '', $order); // Security: ORDER BY comes from the URL
	// Optimized query to fetch counts in one go
	$query_str = "SELECT group_category.*, 
		(SELECT COUNT(*) FROM category WHERE id_group_category = group_category.id_group_category AND deleted = 0) as category_count,
		(SELECT COUNT(*) FROM course JOIN category ON course.id_category = category.id_category WHERE category.id_group_category = group_category.id_group_category AND course.deleted = 0 AND category.deleted = 0) as course_count,
		(SELECT COUNT(*) FROM content JOIN course ON content.id_course = course.id_course JOIN category ON course.id_category = category.id_category WHERE category.id_group_category = group_category.id_group_category AND content.deleted = 0 AND course.deleted = 0 AND category.deleted = 0) as content_count
		FROM group_category
		WHERE deleted = 0
		ORDER BY $order";
		
	$listofuser = $this->db->query($query_str)->result_array();
	$arrays = [];

	foreach ($listofuser as $row) { 
		$id_gc = $row["id_group_category"];
		
		// Fetch categories for this group
		$categories = $this->db->get_where('category', array('id_group_category' => $id_gc, 'deleted' => 0))->result_array();
		$category_count = count($categories);
		
		$desc_html = "<ul>";
		foreach ($categories as $cat) {
			$desc_html .= "<li><strong>" . $cat['title'] . "</strong>";
			
			// Fetch courses for this category
			$courses = $this->db->get_where('course', array('id_category' => $cat['id_category'], 'deleted' => 0))->result_array();
			
			if (!empty($courses)) {
				$desc_html .= "<ul>";
				foreach ($courses as $crs) {
					// Count content for this course
					$cnt_count = $this->db->where(array('id_course' => $crs['id_course'], 'deleted' => 0))->count_all_results('content');
					$desc_html .= "<li>" . $crs['title'] . " ($cnt_count)</li>";
				}
				$desc_html .= "</ul>";
			}
			$desc_html .= "</li>";
		}
		$desc_html .= "</ul>";
		
		$row["description"] = $desc_html;
		$row["category_count"] = $category_count;
		
$row["PIDid_group_category"] =
	'<input type="checkbox" name="cehcklist[]" value="'. $row["id_group_category"].'" />'; if($row["active"] == 1)
		$row["active"] = "<span class=' label label-success' >Active</span>";
		else
		$row["active"] = "<span class=' label label-important ' >InActive</span>";  
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
$data["title"]="List group category";
$data["content"]="back_office/group_category/list";
$data["info"]=$this->fct->getAll($this->table,$order);
// grid (list.php): number of categories of each group + the "category > course (nb of contents)" tree shown as the
// count tooltip, as the legacy all_data() computed it (3 queries instead of one per category / course)
$categories = $this->db->query("SELECT id_category, id_group_category, title FROM category WHERE deleted = 0 ORDER BY sort_order, id_category")->result_array();
$courses = $this->db->query("SELECT id_course, id_category, title FROM course WHERE deleted = 0 ORDER BY sort_order, id_course")->result_array();
$counts = array();
foreach ($this->db->query("SELECT id_course, COUNT(*) AS n FROM content WHERE deleted = 0 GROUP BY id_course")->result_array() as $c) $counts[$c["id_course"]] = (int) $c["n"];
$by_cat = array();
foreach ($courses as $crs) $by_cat[$crs["id_category"]][] = "    - " . $crs["title"] . " (" . (isset($counts[$crs["id_course"]]) ? $counts[$crs["id_course"]] : 0) . ")";
$by_group = array();
foreach ($categories as $cat) {
	$lines = array($cat["title"]);
	if (isset($by_cat[$cat["id_category"]])) $lines = array_merge($lines, $by_cat[$cat["id_category"]]);
	$by_group[$cat["id_group_category"]][] = implode("\n", $lines);
}
foreach ($data["info"] as $i => $row) {
	$id_gc = $row["id_group_category"];
	$data["info"][$i]["category_count"] = isset($by_group[$id_gc]) ? count($by_group[$id_gc]) : 0;
	$data["info"][$i]["category_tree"] = isset($by_group[$id_gc]) ? implode("\n", $by_group[$id_gc]) : "";
}
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add group category";
$data["content"]="back_office/group_category/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit group category";
$data["content"]="back_office/group_category/add";
$cond=array("id_group_category"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_group_category",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
 
  redirect(base_url()."back_office/group_category");
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
$this->db->where("id_group_category",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/group_category");	
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
$this->db->where("id_group_category",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit group category";
$this->form_validation->set_rules("title", "title", "trim");
$this->form_validation->set_rules("active", "active", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_group_category"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("group_category",$cond); }
$data["content"]="back_office/group_category/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["active"]=$this->input->post("active");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_group_category",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/group_category");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/group_category/".$image)){
unlink("./uploads/group_category/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'group category')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/group_category/".$value."/".$image)){
unlink("./uploads/group_category/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_group_category",$id);
$this->db->update("group_category",$_data);
redirect(base_url()."back_office/group_category");	
}

}