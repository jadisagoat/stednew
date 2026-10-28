<?
class Settings extends CI_Controller{
public function __construct()
{
parent::__construct();
// Your own constructor code
$this->headers='back_office/includes/header';
$this->footer='back_office/includes/footer';
$this->load->helper('file');
$this->load->helper('date');
$this->load->helper('directory');

}

/*function _remap($method)
{
if(method_exists($this, $method))
{
$arrUrl = $this->uri->segments;
if(count($arrUrl) > 0)
{
foreach($arrUrl as $key => $url)
{
if(in_array($method, $arrUrl))
{
unset($arrUrl[$key]);
}
}
}
}
else
{
$this->load->helper('url');
$error404 = site_url($this->router->routes['404_override']);
redirect($error404);
}
}
*/
public function index(){
$data["title"]="Settings";
if($this->session->userdata('level') == 0){
$cond=array('id_user'=>$this->session->userdata('user_id'));
$data["id"]=$this->session->userdata('user_id');
$data["info"]=$this->fct->getonerecord('user',$cond);
} else {
$cond=array('admin_id'=>$this->session->userdata('user_id'));
$data["admin"]= $this->fct->getonerow('admin',$cond);
}
$data["content"]="back_office/setting";
$this->load->view('back_office/template',$data);		
}

public function submit_user(){
$this->form_validation->set_rules('name', 'Full Name', 'trim|required|min_length[5]');
$this->form_validation->set_rules('username', 'User Name', 'trim|required|min_length[5]|max_length[12]|xss_clean');
$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[12]|xss_clean');
$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
$this->form_validation->set_rules('address', 'address', 'trim');
$this->form_validation->set_rules('phone', 'phone', 'trim');
if ($this->form_validation->run() == FALSE){
$data["title"]="Settings";
$cond=array('id_user'=>$this->session->userdata('user_id'));
$data["id"]=$this->session->userdata('user_id');
$data["info"]=$this->fct->getonerecord('user',$cond);
$data["content"]="back_office/setting";
$this->load->view('back_office/template',$data);		
}
else
{
	$_data=array(
	'name'=>$this->input->post('name'),
	'username'=>$this->input->post('username'),
	'password'=>$this->input->post('password'),
	'email'=>$this->input->post('email'),
	'phone'=>$this->input->post('phone'),
	'address'=>$this->input->post('address'));
	if($this->input->post('id')!=''){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where('id_user ',$this->session->userdata('user_id')); // Security: only your own account (was the posted id)
	$this->db->update('user',$_data);
	$this->session->set_userdata('success_message','Information was updated successfully');
	}
	redirect(base_url().'back_office/settings');	
}
}
public function submit(){
$this->form_validation->set_rules('a_name', 'Admin Name', 'trim|required|min_length[5]');
$this->form_validation->set_rules('user_name', 'User Name', 'trim|required|min_length[5]|max_length[12]|xss_clean');
$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[12]|xss_clean');
$this->form_validation->set_rules('email', 'Email Address', 'trim|required');
if ($this->form_validation->run() == FALSE){
$data["title"]="Settings";
$cond=array('admin_id'=>$this->session->userdata('user_id'));
$data["admin"]= $this->fct->getonerow('admin',$cond);
$data["content"]="back_office/setting";
$this->load->view('back_office/template',$data);		
}
else
{
	$_data=array(
	'admin_name'=>$this->input->post('a_name'),
	'user_name'=>$this->input->post('user_name'),
	'password'=>$this->input->post('password'),
	'facebook'=>$this->input->post('facebook'),
	'twitter'=>$this->input->post('twitter'),
	'skype'=>$this->input->post('skype'),
	'youtube'=>$this->input->post('youtube'),
	'phone'=>$this->input->post('phone'),
	'mobile'=>$this->input->post('mobile'),
	'website'=>$this->input->post('website'),
	'address'=>$this->input->post('address'),
	'linkedin'=>$this->input->post('linkedin'),
	'google_map'=>$this->input->post('google_map'),
	'website_title'=>$this->input->post('website_title'),
	'email'=>$this->input->post('email'));
if(!empty($_FILES['image']['name'])) {
if($this->input->post("id")!=""){
$cond_image=array("admin_id"=>2);
$old_image=$this->fct->getonecell("admin",'image',$cond_image);
if(!empty($old_image))
unlink("./uploads/website/".$old_image);	
unlink("./uploads/website/45x45/".$old_image);									
}
$image1= $this->fct->uploadImage('image','website');
$this->fct->createthumb($image1,"website","45x45");
$_data["image"]=$image1;	
}
	
$this->db->where('admin_id',$this->session->userdata('user_id'));
$this->db->update('admin',$_data);
$this->session->set_userdata('success_message','Information was updated successfully');
redirect(base_url().'back_office/settings');
}

}
public function index11(){
$data=array(
'pg_title'=>'Setting\'s Page',
'title'=>'Settings',
'header' => $this->headers,
'content' =>'back_office/settings',
'footer'=>$this->footer );
$query=$this->db->get('admin');
$data["admin"]=$query->row_array();

// File
$path=base_url().'file/rami.txt';
echo $path."<br />";
$string = read_file('./file/rami.txt');
echo $string."<br />";
$data = 'Some file data';

if ( !write_file('./file/rami.txt', $data))
{
echo 'Unable to write the file';
}
else
{
echo 'File written!'."<br />";
}
$s= get_filenames('./file/');
print_r($s);
delete_files('./file/nock/');
$ss= get_dir_file_info('./file/',false);
foreach($ss as $val){
echo $val['name']."<br >";
}
//Date
// unix to humen
echo unix_to_human(time());
echo "<br />";
// standard date
$format = 'DATE_ATOM';
$time = time();
echo standard_date($format,$time);
echo "<br />";
// mdate
$datestring = "Year: %Y Month: %m Day: %d - %h:%i %a";
$time = time();
echo mdate($datestring,$time);
// Directory  
$map = directory_map('./file/',1);
$map=directory_map('./file/', false, TRUE);
print_r($map);
//$this->load->view('back_office/template1',$data);
}


function test(){
$this->load->model('fct');
$data="Go to the hell bitch";
$this->fct->write_file('./file/soso.txt',$data);	
$string = read_file('./file/soso.txt');
echo $string;
echo "<hr />";
$this->db->where('admin_id',1);
$query= $this->db->get('admin');
$admin = $query->row_array();
print_r($admin);
echo "<hr />";
/*$this->DB1 = $this->load->database('site_test',TRUE); 
$this->DB1->where('blog_user_id',10);
$query1= $this->DB1->get('blog_users');
$admin1 = $query1->row_array();
print_r($admin1);*/
$this->load->library('zip');
$name = 'mydata1.txt';
$data = 'A Data String!';
$this->zip->add_data($name, $data);
$name = 'personal/my_bio.txt';
$data = 'I was born in an elevator...';
$this->zip->add_data($name, $data); 
$this->zip->clear_data(); 
$this->zip->add_dir('myfolder');
$array=array('soso/ra.txt'=>'what the fuck','soso/post.txt'=>'ya wad ya 5amid');
$this->zip->add_data($array);
 
$path='file:///C:/wamp/www/db/images/404.png';
echo $path;
$this->zip->read_file($path);
//$this->zip->archive('./file/nock/my_backup2.zip'); 
//echo $zip_file = $this->zip->get_zip(); 

$this->zip->download('my_backup.zip'); 
}


//delete logo 
public function delete_image($field,$image,$id){
unlink("./uploads/website/".$image);
unlink("./uploads/website/45x45/".$image);									
$_data[$field]="";
$this->db->where("admin_id",$id);
$this->db->update("admin",$_data);
redirect(base_url()."back_office/settings");	
}	
	
	
}