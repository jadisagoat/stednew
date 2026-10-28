<?php
$controllers = '<?
class ' . ucfirst($table_name) . ' extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="' . $table_name . '";
}

public function all_data($order = "")
{
	if ($order == "") {
		$order = "sort_order";
	}
	$listofuser = $this->fct->getAll($this->table, $order);
	$arrays = [];

	foreach ($listofuser as $row) { ';

$controllers .= '
$row["PIDid_' . $table_name . '"] =
	\'<input type="checkbox" name="cehcklist[]" value="\'. $row["id_' . $table_name . '"].\'" />\';';

foreach ($attrrr as $v) {


	if ($v["type"] == 7) {


		$cond001 = array("id_content" => $v["foreign_key"]);
		$content001 = $this->fct->getonecell("content_type", "name", $cond001);
		if (str_replace(" ", "_", $content001) == $table_name)
			$frn1 = "parent";
		else
			$frn1 = str_replace(" ", "_", $content001);
		$controllers .= '  if($row["id_' . $frn1 . '"] != 0){
		                     $cond=array("id_' . str_replace(" ", "_", $content001) . '" => $row["id_' . $frn1 . '"]);
						   $row["' . str_replace(" ", "_", $v["name"]) . '"] =   $this->fct->getonecell("' . str_replace(" ", "_", $content001) . '","title",$cond);
						    } 
						   else {
		  $row["' . str_replace(" ", "_", $v["name"]) . '"] = \'<small class="blue">--</small>\';
	  } ';

		//$controllers .= '$row["' . str_replace(" ", "_", $v["name"]) . '"] = "sssss"; ';
	} elseif ($v["type"] == 6) {
		$controllers .= ' if($row["' . str_replace(" ", "_", $v["name"]) . '"] == 1)
		$row["' . str_replace(" ", "_", $v["name"]) . '"] = "<span class=\' label label-success\' >Active</span>";
		else
		$row["' . str_replace(" ", "_", $v["name"]) . '"] = "<span class=\' label label-important \' >InActive</span>";  
		';
	} elseif ($v["type"] == 5) {
		$controllers .= '
		if (!empty($row["' . str_replace(" ", "_", $v["name"]) . '"])) {
		  $row["' . str_replace(" ", "_", $v["name"]) . '"] = \'<a href="\' . base_url() . \'uploads/' . $table_name . '/\' .$row["' . str_replace(" ", "_", $v["name"]) . '"]. \'" class="blue" title="\' .$row["title"]. \'" >
		  show file</a>\';
	  } else {
		  $row["' . str_replace(" ", "_", $v["name"]) . '"] = \'<small class="blue">No Image Available</small>\';
	  } ';
	} elseif ($v["type"] == 4) {
		$controllers .= '
		  if (!empty($row["' . str_replace(" ", "_", $v["name"]) . '"])) {
			$row["' . str_replace(" ", "_", $v["name"]) . '"] = \'<a onclick="popup(\\\'\' . base_url() . \'uploads/\' . $this->table . \'/\' . $row["' . str_replace(" ", "_", $v["name"]) . '"] . \'\\\')" class="blue gallery" title="\' . $row["title"] . \'">show image</a>\';
		} else {
			$row["' . str_replace(" ", "_", $v["name"]) . '"] = \'<small class="blue">No Image Available</small>\';
		} ';
	} elseif ($v["type"] == 1) {
		$controllers .= ' $row["' . str_replace(" ", "_", $v["name"]) . '"] =  $this->fct->date_out_formate($row["' . str_replace(" ", "_", $v["name"]) . '"]) ;';
	}
}

$controllers .= '
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
$data["title"]="List ' . $content_type . '";
$data["content"]="back_office/' . $table_name . '/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add ' . $content_type . '";
$data["content"]="back_office/' . $table_name . '/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit ' . $content_type . '";
$data["content"]="back_office/' . $table_name . '/add";
$cond=array("id_' . $table_name . '"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_' . $table_name . '",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
 
  redirect(base_url()."back_office/' . $table_name . '");
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
$this->db->where("id_' . $table_name . '",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/' . $table_name . '");	
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
$this->db->where("id_' . $table_name . '",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit ' . $content_type . '";
';
foreach ($attrrr as $val) {
	$controllers .= '$this->form_validation->set_rules("' . str_replace(" ", "_", $val["name"]) . '", "' . $val["name"] . '", "' . $val["validation"] . '");
';
}
$controllers .= 'if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_' . $table_name . '"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("' . $table_name . '",$cond); }
$data["content"]="back_office/' . $table_name . '/add";
$this->load->view("back_office/template",$data);
}
else
{
';
foreach ($attrrr as $val) {
	// Date
	if ($val["type"] == 1) {
		$controllers .= '
$_data["' . str_replace(" ", "_", $val["name"]) . '"]=$this->fct->date_in_formate($this->input->post("' . str_replace(" ", "_", $val["name"]) . '"));
';
	}
	// foreign_key
	elseif ($val["type"] == 7) {
		$cond = array("id_content" => $val["foreign_key"]);
		$content001 = $this->fct->getonecell("content_type", "name", $cond);
		if (str_replace(" ", "_", $content001) == $table_name)
			$frn = "parent";
		else
			$frn = str_replace(" ", "_", $content001);
		$controllers .= '$_data["id_' . $frn . '"]=$this->input->post("' . str_replace(" ", "_", $val["name"]) . '"); 
';
	}
	// Image	
	elseif ($val["type"] == 4) {
		$controllers .= 'if(!empty($_FILES["' . str_replace(" ", "_", $val["name"]) . '"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_' . $table_name . '"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("' . $table_name . '","' . str_replace(" ", "_", $val["name"]) . '",$cond_image);
if(!empty($old_image) && file_exists(\'./uploads/' . $table_name . '/\'.$old_image)){
unlink("./uploads/' . $table_name . '/".$old_image);
';
		if ($val["thumb"] == 1) {
			$controllers .= '$sumb_val1=explode(",","' . $val["thumb_val"] . '");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/' . $table_name . '/".$value."/".$old_image)){
unlink("./uploads/' . $table_name . '/".$value."/".$old_image);	 }							
} 
';
		}
		$controllers .= ' } }
$image1= $this->fct->uploadImage("' . str_replace(" ", "_", $val["name"]) . '","' . $table_name . '");
';
		if ($val["thumb"] == 1) {
			if ($val["resize_status"] == 1) {
				$controllers .= '$this->fct->createthumb1($image1,"' . $table_name . '","' . $val["thumb_val"] . '");';
			} else {
				$controllers .= '$this->fct->createthumb($image1,"' . $table_name . '","' . $val["thumb_val"] . '");';
			}
		}
		$controllers .= '$_data["' . str_replace(" ", "_", $val["name"]) . '"]=$image1;	
}
';
	}
	// File 
	elseif ($val["type"] == 5) {
		$controllers .= '
if(!empty($_FILES["' . str_replace(" ", "_", $val["name"]) . '"]["name"])) {
if($this->input->post("id")!=""){
$cond_file=array("id_' . $table_name . '"=>$this->input->post("id"));
$old_file=$this->fct->getonecell("' . $table_name . '","' . str_replace(" ", "_", $val["name"]) . '",$cond_file);
if(!empty($old_file))
unlink("./uploads/' . $table_name . '/".$old_file);	
}
$file1= $this->fct->uploadImage("' . str_replace(" ", "_", $val["name"]) . '","' . $table_name . '");
$_data["' . str_replace(" ", "_", $val["name"]) . '"]=$file1;	
}
';
	}
	// others
	else {
		$controllers .= '$_data["' . str_replace(" ", "_", $val["name"]) . '"]=$this->input->post("' . str_replace(" ", "_", $val["name"]) . '");
';
	}
	// end foreach 
}
$controllers .= '
	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_' . $table_name . '",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/' . $table_name . '");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/' . $table_name . '/".$image)){
unlink("./uploads/' . $table_name . '/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = \'' . str_replace("_", " ", $table_name) . '\')
AND name = \'".$field."\'";
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/' . $table_name . '/".$value."/".$image)){
unlink("./uploads/' . $table_name . '/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_' . $table_name . '",$id);
$this->db->update("' . $table_name . '",$_data);
redirect(base_url()."back_office/' . $table_name . '");	
}

}';
