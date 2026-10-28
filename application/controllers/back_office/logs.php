<?
class Logs extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="logs";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List logs";
$data["content"]="back_office/logs/list";
// rows are loaded page by page by the grid (ajax mode, see grid_data()): the log table can grow without limit
$data["info"]=array();
$this->load->view("back_office/template",$data);
}

// server side of the logs grid (views/back_office/logs/list.php, includes/dxgrid.php ajax mode)
public function grid_data(){
bo_grid_output(bo_grid_data($this->table, array('id_content' => 13,
	'columns' => array('title', 'id_client', 'ip_address', 'created_date'),
	'search' => array('title', 'id_client_text', 'ip_address'),
	'lookups' => array('id_client' => array('client', 'title', 'id_client')),
	'order' => 'id_logs desc')));
}

public function add(){
$data["title"]="Add logs";
$data["content"]="back_office/logs/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit logs";
$data["content"]="back_office/logs/add";
$cond=array("id_logs"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_logs",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/logs");
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
$this->db->where("id_logs",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/logs");	
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
$this->db->where("id_logs",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit logs";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("client", "client", "trim");
$this->form_validation->set_rules("ip_address", "ip address", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_logs"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("logs",$cond); }
$data["content"]="back_office/logs/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_client"]=$this->input->post("client"); 
$_data["ip_address"]=$this->input->post("ip_address");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_logs",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/logs");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/logs/".$image)){
unlink("./uploads/logs/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'logs')
AND name = '".$field."'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/logs/".$value."/".$image)){
unlink("./uploads/logs/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_logs",$id);
$this->db->update("logs",$_data);
redirect(base_url()."back_office/logs");	
}

}