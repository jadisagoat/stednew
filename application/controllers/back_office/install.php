<?
class Install extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="content_type_attr";
$this->load->helper('file');
}

public function settings($id_content){
$data["title"]="Set Validation Rules";
// get  conetent type attributes .
$data["id_content"]=$id_content;
$cond["id_content"]=$id_content;
$data["info"]=$this->fct->getAll_cond($this->table,'sort_order',$cond);

// get the content type .
$data["table"]= $this->fct->getonecell('content_type','name',$cond);

// creat folder with content type name inside view .
if(!is_dir('./application/views/back_office/'.str_replace(' ','_',$data["table"])))
mkdir('./application/views/back_office/'.str_replace(' ','_',$data["table"]));
// create folcer inside uploads for images  and thumbnails for images .
if(!is_dir('./uploads/'.str_replace(' ','_',$data["table"])))
mkdir('./uploads/'.str_replace(' ','_',$data["table"]));

foreach($data["info"] as $val){
if($val["type"] ==  4){
if($val["thumb"] ==1){
$sumb_val =array();	
$sumb_val=explode(",", $val["thumb_val"]);	
foreach($sumb_val as $key => $value){
if(!is_dir('./uploads/'.str_replace(' ','_',$data["table"])."/".$value))
mkdir('./uploads/'.str_replace(' ','_',$data["table"])."/".$value);	
} } } }


$data["content"]="back_office/controlers/install";
$this->load->view('back_office/template',$data);
}


public function next($id_content){
$data["title"]="Install Content Type";
// get  conetent type attributes
$data["id_content"]=$id_content;
$cond["id_content"]=$id_content;
$info=$this->fct->getAll_cond($this->table,'sort_order',$cond);
$i=0;
foreach($info as $val){
$i++;
$validation='trim';
if($this->input->post('required_'.$i)==true)
$validation.='|required';
if($this->input->post('min_length_'.$i)==true){
if($this->input->post('min_num_'.$i)=='') $min_num_0=5; else $min_num_0= $this->input->post('min_num_'.$i);
$validation.='|min_length['.$min_num_0.']';
}
if($this->input->post('max_length_'.$i)==true){
if($this->input->post('max_num_'.$i)=='') $max_num_0=5; else $max_num_0= $this->input->post('max_num_'.$i);	
$validation.='|max_length['.$max_num_0.']';
}
if($this->input->post('valid_email_'.$i)==true)
$validation.='|valid_email';
//echo $validation;
// update validation 
$_data=array('validation' => $validation);
$this->db->where('id_attr',$val["id_attr"]);
$this->db->update('content_type_attr',$_data);
}


// get the content type 
$data["table"]= $this->fct->getonecell('content_type','name',$cond);
$data["content"]="back_office/controlers/validate";
$this->load->view('back_office/template',$data);	
}


function finish(){
$_data['position'] = $this->input->post('position');
$id_content = $this->input->post('id_content');
$this->db->where('id_content',$id_content);
$this->db->update('content_type',$_data);
$this->session->set_userdata('success_message','conetnt was installed successfully');
$cond=array('id_content' => $id_content);
$content_type=$this->fct->getonecell('content_type','name',$cond);
$table_name=str_replace(" ","_",$content_type);



// create controller 
$cond=array('id_content'=>$id_content);
$attrrr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);
include('./application/views/back_office/controlers/create_controller.php');
$this->fct->write_file('./application/controllers/back_office/'.$table_name.'.php',$controllers);

//create views
$cond=array('id_content'=>$id_content);
$attr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);

$id_content_old=$id_content;

include('./application/views/back_office/controlers/create_view_add.php');
$this->fct->write_file('./application/views/back_office/'.$table_name.'/add.php',$add_view);
$id_content=$id_content_old;
$cond=array('display'=>1,'id_content'=>$id_content);
$attr=$this->fct->getAll_cond("content_type_attr","sort_order",$cond);

// ayman echo $id_content;
// echo $this->db->last_query();
// echo "<pre>";
// print_r($attr);
// exit();


include('./application/views/back_office/controlers/create_view_list.php');
$this->fct->write_file('./application/views/back_office/'.$table_name.'/list.php',$list_view);

redirect(base_url().'back_office/control');	
}


} 
?>
