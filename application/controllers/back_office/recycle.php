<?
class Recycle extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="who_we_was";
}


public function index(){
$data["title"]="Recycle Bin";
$data["info"]= $this->fct->getAll('content_type','sort_order');
$data["content"]="back_office/recycle/list";
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add who we was";
$cond1=array('name'=>"who_we_was");
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$cond=array('id_content'=>22);
$data["attr"]=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
$data["content"]="back_office/who_we_was/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit who we was";
$cond1=array('name'=>"who_we_was");
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$cond=array('id_content'=>22);
$data["attr"]=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
$data["content"]="back_office/who_we_was/add";
$cond=array("id_who_we_was"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_who_we_was",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/who_we_was");
}

public function delete_all(){
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){

$count= $this->input->post('count_content');
for($i = 1 ; $i <= $count; $i++){
$table = str_replace(" ","_",$this->input->post('check_parent_'.$i));
$cehcklist= $this->input->post("cehcklist_".$i);
if(count($cehcklist) > 0){
for($j = 0; $j < count($cehcklist); $j++){
if($cehcklist[$j] != ""){
//$_data=array("deleted"=>0,"deleted_date"=>date("Y-m-d h:i:s"));
// Delte Related Images 

$this->fct->deleter_related_images($this->input->post('check_parent_'.$i),$cehcklist[$j]);

// Delte Items Image if gallery exist 
 $content_type = str_replace(' ','_',$this->input->post('check_parent_'.$i));
$cond12=array('used_name'=>$content_type);
$gallery=$this->fct->getonecell("content_type","gallery",$cond12);
$thumb_val_gal=$this->fct->getonecell("content_type","thumb_val_gal",$cond12); 
if($gallery == 1){

// get all image gallery related to this item ... 
$cond3=array('id_'.$content_type => $cehcklist[$j]);
$row2= $this->fct->getAll_cond($content_type.'_gallery','sort_order',$cond3);
// get the thumbnails value from the content type  ...

$sumb_val1=explode(",",$thumb_val_gal);
foreach($row2 as $vivi){
if($vivi["image"] != ""){
if(file_exists('./uploads/'.$content_type.'/gallery/'.$vivi["image"])){
unlink("./uploads/".$content_type."/gallery/".$vivi["image"]); }
if(file_exists('./uploads/'.$content_type.'/gallery/120x120/'.$vivi["image"])){
unlink("./uploads/".$content_type."/gallery/120x120/".$vivi["image"]); }
foreach($sumb_val1 as $key => $value){
if(file_exists('./uploads/'.$content_type.'/gallery/'.$value.'/'.$vivi["image"])){
unlink("./uploads/".$content_type."/gallery/".$value."/".$vivi["image"]); }							
}
}
$this->db->where('id_gallery' , $vivi["id_gallery"]);
$this->db->delete($content_type.'_gallery');
}


}

$this->db->where("id_".$table,$cehcklist[$j]);
$this->db->delete($table);	

}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
}
elseif($check_option == "restore_all"){
$count= $this->input->post('count_content');
for($i = 1 ; $i <= $count; $i++){
	$table = str_replace(" ","_",$this->input->post('check_parent_'.$i));
	$cehcklist= $this->input->post("cehcklist_".$i);
if(count($cehcklist) > 0){
for($j = 0; $j < count($cehcklist); $j++){
if($cehcklist[$j] != ""){
$_data=array("deleted"=>0,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_".$table,$cehcklist[$j]);
$this->db->update($table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were restored successfully");
}
}
redirect(base_url()."back_office/recycle");	
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
$this->db->where("id_who_we_was",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit who we was";
$cond1=array('name'=>"who_we_was");
$id_content=$this->fct->getonecell("content_type","id_content",$cond1);
$cond=array('id_content'=>22);
$attrrr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
$data["attr"]= $attrrr;

foreach($attrrr as $val){
$this->form_validation->set_rules(str_replace(" ","_",$val["name"]), $val["name"], $val["validation"]);
}
if ($this->form_validation->run() == FALSE){
$data["content"]="back_office/who_we_was/add";
$this->load->view("back_office/template",$data);
}
else
{
foreach($attrrr as $val){
$_data[str_replace(" ","_",$val["name"])]=$this->input->post(str_replace(" ","_",$val["name"]));
}
	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_who_we_was",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/who_we_was");
}
	
}

}