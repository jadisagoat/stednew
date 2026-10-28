<?
class Modules extends CI_Controller{
public function __construct()
{
parent::__construct();

}


public function index(){
$data["title"]="List Modules";
$data["content"]="back_office/modules";
$data["info"]=$this->fct->getAll('module','sort_order');
$this->load->view('back_office/template',$data);
}

public function activate($id){
$cond=array('id_module'=>$id);
$module=$this->fct->getonerow('module',$cond);
if($module["activate"]== 1){
$status="<span class='label label-important'>disable</span>";
$_data["activate"] = 0;
} else {
$status="<span class='label label-success'>enable</span>";
$_data["activate"] = 1;
}
$this->db->where('id_module',$id);
$this->db->update('module',$_data);
echo $status;
}


public function sorted(){
$sort=array();
foreach($this->input->get('table-1') as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array('sort_order'=>$i);
$this->db->where('id_module',$sort[$i]);
$this->db->update('module',$_data);	
}
}


}