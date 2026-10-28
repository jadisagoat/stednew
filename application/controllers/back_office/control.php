<?
class Control extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "content_type";
	}


	public function index()
	{
		$data["title"] = "List Content Type";
		$data["content"] = "back_office/controlers/list";

		if ($order == "")
			$order = "id_menu, sort_order";
		$cond = array('name != ' => 'menu');
		$data["info"] = $this->fct->getAll_cond($this->table, $order, $cond);
		// $this->fct->getAll($this->table, 'sort_order');
		$this->load->view('back_office/template', $data);
	}

	public function add()
	{
		$data["title"] = "Add Content Type";
		$data["content"] = "back_office/controlers/add";
		$this->load->view('back_office/template', $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit Content Type";
		$cond = array('id_content' => $id);
		$data["id"] = $id;
		$data["info"] = $this->fct->getonerecord($this->table, $cond);
		$data["content"] = "back_office/controlers/add";
		$this->load->view('back_office/template', $data);
	}

	public function delete($id)
	{
		//$_data=array('deleted'=>1,'deleted_date'=>date("Y-m-d h:i:s"));
		$cond = array('id_content' => $id);
		$content_type = $this->fct->getonecell('content_type', 'name', $cond);
		$with_gallery = $this->fct->getonecell('content_type', 'gallery', $cond);
		$target = str_replace(" ", "_", $content_type);
		$this->db->where('id_content', $id);
		$this->db->delete($this->table);
		$this->db->where('id_content', $id);
		$this->db->delete('content_type_attr');
		$this->db->query('DROP TABLE `' . str_replace(" ", "_", $content_type) . '`');
		if ($with_gallery == 1) {
			$this->db->query('DROP TABLE `' . str_replace(" ", "_", $content_type) . '_gallery`');
		}
		unlink('./application/controllers/back_office/' . $target . '.php');
		$this->fct->deleteDirectory('./application/views/back_office/' . $target);
		$this->fct->deleteDirectory('./uploads/' . $target);
		$this->session->set_userdata('success_message', 'Information was deleted successfully');
		redirect(base_url() . 'back_office/control');
	}

	public function delete_all()
	{
		$cehcklist = $this->input->post('cehcklist');
		$check_option = $this->input->post('check_option');
		if ($check_option == "delete_all") {
			if (count($cehcklist) > 0) {
				for ($i = 0; $i < count($cehcklist); $i++) {
					if ($cehcklist[$i] != '') {
						$_data = array(
							'deleted' => 1,
							'deleted_date' => date("Y-m-d h:i:s")
						);
						$this->db->where('id_content', $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata('success_message', 'Informations were deleted successfully');
		}
		redirect(base_url() . 'back_office/control');
	}

	public function sorted()
	{
		$sort = array();
		foreach ($this->input->get('table-1') as $key => $val) {
			if (!empty($val))
				$sort[] = $val;
		}
		$i = 0;
		for ($i = 0; $i < count($sort); $i++) {
			$_data = array('sort_order' => $i);
			$this->db->where('id_content', $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}


	public function submit()
	{
		$this->form_validation->set_rules('name', 'Content Type Name', 'trim|required');
		$this->form_validation->set_rules('url_name', 'url_name', 'trim');
		$this->form_validation->set_rules('icon', 'Icon', 'trim');
		$this->form_validation->set_rules('gallery', 'Icon', 'trim');



		if ($this->form_validation->run() == FALSE) {
			$data["title"] = "Add Content Type";
			$data["content"] = "back_office/controlers/add";
			$this->load->view('back_office/template', $data);
		} else {
			$check_name = strtolower($this->input->post('name'));
			$condd = array('name' => strtolower($this->input->post('name')), 'name' => strtolower(str_replace("_", " ", $this->input->post('name'))));
			$content_type = $this->fct->getonerow('content_type', $condd);
			if ((count($content_type) != 0 && $this->input->post('id') == "") || $check_name == "control" || $check_name == "events" || $check_name == "home" || $check_name == "install" || $check_name == "messages" || $check_name == "modules" || $check_name == "newsletter" || $check_name == "pages" || $check_name == "recycle" || $check_name == "settings" || $check_name == "todo" || $check_name == "users" || $check_name == "controlers" || $check_name == "includes" || $check_name == "content type" || $check_name == "content_type" || $check_name == "pdf" || $check_name == "website" || $check_name == "user" || $check_name == "priviledge" || $check_name == "admin" || $check_name == "contactform") {
				$data["title"] = "Add Content Type";
				$this->session->set_userdata('error_message', 'This Content Type Already Exist .');
				$data["content"] = "back_office/controlers/add";
				$this->load->view('back_office/template', $data);
			} else {
				// upload image 

				$_data = array(
					'name' => strtolower($this->input->post('name')),
					'url_name' => str_replace(" ", "_", $this->input->post('url_name')),
					'gallery' => $this->input->post('gallery'),
					'thumb_val_gal' => $this->input->post('thumb_val_gal'),
					'resize_status' => $this->input->post('resize_status'),
					'id_menu' => $this->input->post('id_menu'),
					'used_name' => str_replace(" ", "_", strtolower($this->input->post('name')))
				);



				// if (!empty($_FILES["icon"]["name"])) {
				// 	if ($this->input->post("id") != "") {
				// 		$cond_image = array("id_content" => $this->input->post("id"));
				// 		$old_image = $this->fct->getonecell("content_type", "icon", $cond_image);
				// 		if (!empty($old_image)) {
				// 			unlink("./uploads/content_type/" . $old_image);
				// 			unlink("./uploads/content_type/32x32/" . $old_image);
				// 		}
				// 	}
				// 	$image1 = $this->fct->uploadImage("icon", "content_type");
				// 	$this->fct->createthumb($image1, "content_type", "32x32");
				// 	$_data["icon"] = $image1;
				// }

				$_data["icon"] = $this->input->post('icon');



				if ($this->input->post('id') != '') {
					$_data["updated_date"] = date("Y-m-d h:i:s");
					$this->db->where('id_content', $this->input->post('id'));
					$this->db->update($this->table, $_data);
					if ($this->input->post('gallery') == 1) {
						$table_name = strtolower(str_replace(" ", "_", $this->input->post('name')));
						$q2 = "CREATE TABLE `" . $table_name . "_gallery` (
`id_gallery` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`deleted` INT( 1 ) NOT NULL ,
`created_date` DATETIME NOT NULL ,
`updated_date` DATETIME NOT NULL ,
`deleted_date` DATETIME NOT NULL ,
`sort_order` INT( 1 ) NOT NULL ,
`status` INT( 1 ) NOT NULL ,
`title` VARCHAR( 250 ) NOT NULL ,
`image` VARCHAR( 150 ) NOT NULL ,
`id_" . $table_name . "` INT( 11 ) NOT NULL,
PRIMARY KEY ( `id_gallery` ),
INDEX ( `id_" . $table_name . "` )
) ENGINE = InnoDB 
";
						$result = $this->db->query($q2);

						mkdir('./uploads/' . $table_name . '/');
						mkdir('./uploads/' . $table_name . '/gallery/');
						mkdir('./uploads/' . $table_name . '/gallery/120x120/');
						$sumb_val1 = explode(",", $this->input->post('thumb_val_gal'));
						foreach ($sumb_val1 as $key => $value) {
							mkdir("./uploads/" . $table_name . "/gallery/" . $value . "/");
						}
					}
					$this->session->set_userdata('success_message', 'Information was updated successfully');
				} else {
					$_data["created_date"] = date("Y-m-d h:i:s");
					$this->db->insert($this->table, $_data);
					$table_name = strtolower(str_replace(" ", "_", $this->input->post('name')));
					$q = "CREATE TABLE `" . $table_name . "` (
`id_" . $table_name . "` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`deleted` INT( 1 ) NOT NULL DEFAULT '0' ,
`created_date` DATETIME NULL ,
`updated_date` DATETIME NULL ,
`deleted_date` DATETIME NULL ,
`sort_order` INT( 1 ) NOT NULL DEFAULT '0' ,
`status` INT( 1 ) NOT NULL DEFAULT '0' ,
PRIMARY KEY ( `id_" . $table_name . "` )
) ENGINE = InnoDB 
";
					$this->db->query($q);
					if ($this->input->post('gallery') == 1) {
						$q2 = "CREATE TABLE `" . $table_name . "_gallery` (
`id_gallery` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`deleted` INT( 1 ) NOT NULL DEFAULT '0' ,
`created_date` DATETIME NULL ,
`updated_date` DATETIME NULL ,
`deleted_date` DATETIME NULL ,
`sort_order` INT( 1 ) NOT NULL DEFAULT '0' ,
`status` INT( 1 ) NOT NULL DEFAULT '0',
`title` VARCHAR( 250 ) NULL ,
`image` VARCHAR( 150 ) NULL ,
`id_" . $table_name . "` INT( 11 ) NOT NULL,
PRIMARY KEY ( `id_gallery` ),
INDEX ( `id_" . $table_name . "` )
) ENGINE = InnoDB 
";
						$this->db->query($q2);
						mkdir('./uploads/' . $table_name . '/');
						mkdir('./uploads/' . $table_name . '/gallery/');
						mkdir('./uploads/' . $table_name . '/gallery/120x120/');
						$sumb_val1 = explode(",", $this->input->post('thumb_val_gal'));
						foreach ($sumb_val1 as $key => $value) {
							mkdir("./uploads/" . $table_name . "/gallery/" . $value . "/");
						}
					}
					$this->session->set_userdata('success_message', 'Information was inserted successfully');
				}
				// echo $this->db->last_query();
				// exit();



				redirect(base_url() . 'back_office/control');
			}
		}
	}

	//manage content Type 
	public function manage($id)
	{
		$data["title"] = "Manage Content Type";
		$data["title1"] = "<a href='" . base_url() . "back_office/control' class='blue'>Manage Content Type</a>";
		$data["content"] = "back_office/controlers/manage";
		$cond = array('id_content' => $id);
		$data["con_type"] = $this->fct->getonerecord($this->table, $cond);
		$data["info"] = $this->fct->getAll_cond('content_type_attr', 'sort_order', $cond);
		$this->load->view('back_office/template', $data);
	}

	//add_attr
	public function add_attr($id)
	{
		$data["length_attr"] = 0;
		$data["thumb_attr"] = 0;
		$data["foreign_attr"] = 0;
		$data["title"] = "Add Attribute for content type";
		$data["title1"] = "<a href='" . base_url() . "back_office/control/manage/" . $id . "' class='blue'>Add Attribute for content type</a>";
		$data["content"] = "back_office/controlers/add_attr";
		$cond = array('id_content' => $id);
		$data["con_type"] = $this->fct->getonerecord($this->table, $cond);
		$data["attr_type"] = $this->fct->getAll_001('attr_type', 'name');
		$data["info"] = $this->fct->getAll('content_type_attr', 'sort_order');
		$this->load->view('back_office/template', $data);
	}

	// submit Attr
	function submit_attr()
	{
		$data["length_attr"] = 0;
		$data["thumb_attr"] = 0;
		$data["foreign_attr"] = 0;
		$id = $this->input->post('id_content');
		$this->form_validation->set_rules('name', 'Attr Name', 'trim|required');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		if ($this->input->post('type') == 2 || $this->input->post('type') == 8 || $this->input->post('type') == 9) {
			$data["length_attr"] = 1;
			$this->form_validation->set_rules('length', 'length', 'trim|required|min_length[1]|max_length[5]');
		}
		$this->form_validation->set_rules('thumbs', 'thumbs', 'trim');
		if ($this->input->post('thumbs') == true) {
			$data["thumb_attr"] = 1;
			$this->form_validation->set_rules('thumb_val', 'thumb_val', 'trim|required');
		}
		if ($this->input->post('type') == 7) {
			$data["foreign_attr"] = 1;
			$this->form_validation->set_rules('foreign_key', 'Foreign Key', 'trim|required');
		}
		$this->form_validation->set_rules('display', 'display', 'trim');

		if ($this->form_validation->run() == FALSE) {
			$data["title"] = "Add Attribute for content type";
			$data["title1"] = "<a href='" . base_url() . "back_office/control/manage/" . $id . "' class='blue'>Add Attribute for content type</a>";
			$data["content"] = "back_office/controlers/add_attr";
			$cond = array('id_content' => $id);
			$data["con_type"] = $this->fct->getonerecord($this->table, $cond);
			$data["attr_type"] = $this->fct->getAll_001('attr_type', 'name');
			$data["info"] = $this->fct->getAll('content_type_attr', 'sort_order');
			$this->load->view('back_office/template', $data);
		} else {
			$query = $this->db->query("SELECT name FROM `content_type_attr` WHERE id_content = " . (int) $id . " AND name = '" . $this->db->escape_str(strtolower($this->input->post('name'))) . "'"); // Security: escaped
			$res = $query->result_array();
			if (count($res) > 0) {
				$data["title"] = "Add Attribute for content type";
				$data["title1"] = "<a href='" . base_url() . "back_office/control/manage/" . $id . "' class='blue'>Add Attribute for content type</a>";
				$data["content"] = "back_office/controlers/add_attr";
				$cond = array('id_content' => $id);
				$data["con_type"] = $this->fct->getonerecord($this->table, $cond);
				$data["attr_type"] = $this->fct->getAll_001('attr_type', 'name');
				$data["info"] = $this->fct->getAll('content_type_attr', 'sort_order');
				$this->session->set_userdata('error_message', 'This attribute already exist .');
				$this->load->view('back_office/template', $data);
			} else {
				$_data = array(
					'name' => strtolower($this->input->post('name')),
					'type' => $this->input->post('type'),
					'display' => $this->input->post('display'),
					'id_content' => $id
				);
				if ($this->input->post('type') == 2 || $this->input->post('type') == 8 || $this->input->post('type') == 9) {
					$_data["length"] = $this->input->post('length');
				}
				if ($this->input->post('type') == 4) {
					$_data["thumb"] = $this->input->post('thumbs');
					$_data["thumb_val"] = $this->input->post('thumb_val');
					$_data["resize_status"] = $this->input->post('resize_status');
				}
				if ($this->input->post('type') == 7) {
					$_data["foreign_key"] = $this->input->post('foreign_key');
				}
				if ($this->input->post('id') != '') {
					$this->db->where('id_attr', $this->input->post('id'));
					$this->db->update('content_type_attr', $_data);
					$this->session->set_userdata('success_message', 'Information was updated successfully');
				} else {
					$this->db->insert('content_type_attr', $_data);
					$this->session->set_userdata('success_message', 'Information was inserted successfully');
				}
				redirect(base_url() . 'back_office/control/add_attr/' . $id);
			}
		}
	}

	public function edit_attr($id_cont, $id)
	{
		$data["length_attr"] = 0;
		$data["thumb_attr"] = 0;
		$data["foreign_attr"] = 0;
		$data["title"] = "Edit Attribute for content type";
		$data["title1"] = "<a href='" . base_url() . "back_office/control/manage/" . $id_cont . "' class='blue'>Edit Attribute for content type</a>";
		$data["content"] = "back_office/controlers/add_attr";
		$cond = array('id_content' => $id_cont);
		$data["id"] = $id;
		$data["con_type"] = $this->fct->getonerecord($this->table, $cond);
		$data["attr_type"] = $this->fct->getAll_001('attr_type', 'name');
		$cond1 = array('id_attr' => $id);
		$data["info"] = $this->fct->getonerecord('content_type_attr', $cond1);
		$check = $data["info"];
		if ($check["type"] == 2)
			$data["length_attr"] = 1;
		if ($check["type"] == 4)
			$data["thumb_attr"] = 1;
		if ($check["type"] == 7)
			$data["foreign_attr"] = 1;
		$this->load->view('back_office/template', $data);
	}

	public function delete_attr($id)
	{
		$cond = array('id_attr' => $id);
		$id1 = $this->fct->getonecell('content_type_attr', 'id_content', $cond);
		$field = $this->fct->getonecell('content_type_attr', 'name', $cond);
		$cond1 = array('id_content' => $id1);
		echo $table = $this->fct->getonecell('content_type', 'name', $cond1);
		echo $q = " ALTER TABLE `" . str_replace(" ", "_", $table) . "` DROP `" . $field . "` ";

		$result = $this->db->query($q);

		$thumb_val = $this->fct->getonecell('content_type_attr', 'thumb_val', $cond);
		if ($thumb_val != "") {
			$sumb_val1 = explode(",", $thumb_val);
			foreach ($sumb_val1 as $key => $value) {
				$this->fct->deleteDirectory("./uploads/" . str_replace(" ", "_", $table) . "/" . $value);
			}
		}
		$this->db->where('id_attr', $id);
		$this->db->delete('content_type_attr');
		$this->session->set_userdata('success_message', 'Information was deleted successfully');

		redirect(base_url() . 'back_office/control/manage/' . $id1);
	}

	public function delete_all_attr()
	{
		$cehcklist = $this->input->post('cehcklist');
		$check_option = $this->input->post('check_option');
		if ($check_option == "delete_all") {
			if (count($cehcklist) > 0) {
				for ($i = 0; $i < count($cehcklist); $i++) {
					if ($cehcklist[$i] != '') {
						// get id_content for one of them that it will be the same for all .
						if ($i == 0) {
							$cond = array('id_attr' => $cehcklist[$i]);
							$id1 = $this->fct->getonecell('content_type_attr', 'id_content', $cond);
						}
						$this->db->where('id_attr', $cehcklist[$i]);
						$this->db->delete('content_type_attr');
					}
				}
			}
			$this->session->set_userdata('success_message', 'Informations were deleted successfully');
		}
		redirect(base_url() . 'back_office/control/manage/' . $id1);
	}

	public function sorted_attr()
	{
		$sort = array();
		foreach ($this->input->get('table-1') as $key => $val) {
			if (!empty($val))
				$sort[] = $val;
		}
		$i = 0;
		for ($i = 0; $i < count($sort); $i++) {
			$_data = array('sort_order' => $i);
			$this->db->where('id_attr', $sort[$i]);
			$this->db->update('content_type_attr', $_data);
		}
	}



	public function delete_image($field, $image, $id)
	{
		unlink("./uploads/content_type/" . $image);
		unlink("./uploads/content_type/32x32/" . $image);
		$_data[$field] = "";
		$this->db->where("id_content", $id);
		$this->db->update("content_type", $_data);
		redirect(base_url() . "back_office/control");
	}



	public function admin_rules()
	{
		$data["title"] = "Manage Admin Rules";
		$data["content"] = "back_office/controlers/rules";
		$data["info"] = $this->fct->getAll_1('admin_rules', 'rules');
		$this->load->view('back_office/template', $data);
	}

	public function update_admin_rules()
	{

		$cehcklist = $this->input->post('cehcklist');
		$checklist = array();
		if (count($cehcklist) != 0) {
			$rule = array();
			for ($i = 0; $i < count($cehcklist); $i++) {
				if ($cehcklist[$i] != '') {
					$rule[] = $this->input->post('rule' . $cehcklist[$i]);
				}
			}
			// update admin rules table 
			$info = $this->fct->getAll_1('admin_rules', 'rules');
			foreach ($info as $val) {
				if (in_array($val["rules"], $rule)) {
					$active = 1;
				} else {
					$active = 0;
				}
				$_data = array('active' => $active);
				$this->db->where('rules', $val["rules"]);
				$this->db->update('admin_rules', $_data);
			}
			$this->session->set_userdata('success_message', 'Informations were updated successfully');
		}
		redirect(base_url() . "back_office/control/admin_rules");
	}


	//manage gallery 
	public function manage_gallery($content_type, $id)
	{
		$data["title"] = "Manage Gallery";
		$data["title1"] = "<a href='" . base_url() . "back_office/" . $content_type . "' class='blue'>Manage " . str_replace("_", " ", $content_type) . "</a>&nbsp;&raquo;Manage Poto Gallery";
		$data["content_type"] = $content_type;
		$data["id_gallery"] = $id;
		$table = $content_type . "_gallery";
		$q = "SELECT * FROM `" . $table . "` WHERE id_" . $content_type . "='" . (int) $id . "' ORDER BY sort_order"; // Security: integer id
		$query = $this->db->query($q);
		$data["info"] = $query->result_array();
		//print_r($data["info"]);
		//$data["info"]=$this->fct->getAll_cond('content_type_attr','sort_order',$cond);
		$data["content"] = "back_office/manage_gallery/list";
		$this->load->view('back_office/template', $data);
	}

	// add photo gallery 
	public function add_photos($content_type, $id)
	{
		$data["title"] = "Add Photo Gallery";
		$data["title1"] = "<a href='" . base_url() . "back_office/control/manage_gallery/" . $content_type . "/" . $id . "' class='blue' >
Add Photo Gallery For " . $content_type . "</a>";
		$data["content_type"] = $content_type;
		$data["id_gallery"] = $id;
		$data["content"] = "back_office/manage_gallery/add";
		$this->load->view('back_office/template', $data);
	}


	public function edit_photo($content_type = "", $id = "", $id_gallery = "")
	{
		$data["title"] = "Edit Photo";
		$data["title1"] = "<a href='" . base_url() . "back_office/control/manage_gallery/" . $content_type . "/" . $id . "' class='blue' >Edit Photo For " . $content_type . " Gallery</a>";
		$data["content_type"] = $content_type;
		$data["id_gallery"] = $id;
		$data["id"] = $id_gallery;
		$cond = array('id_gallery' => $id_gallery);
		$data["info"] = $this->fct->getonerecord($content_type . '_gallery', $cond);
		$data["content"] = "back_office/manage_gallery/edit";
		$this->load->view('back_office/template', $data);
	}

	public function edit_photo_submit()
	{
		$content_type = $this->input->post('content_type');
		$id = $this->input->post('id');
		$id_gallery = $this->input->post('id_gallery');
		$cond = array("id_gallery" => $id);
		$image = $this->fct->getonecell($content_type . "_gallery", "image", $cond);
		if (!empty($_FILES["image"]["name"])) {
			if (file_exists('./uploads/' . $content_type . '/gallery/' . $image)) {
				unlink('./uploads/' . $content_type . '/gallery/' . $image);
			}
			if (file_exists('./uploads/' . $content_type . '/gallery/120x120/' . $image)) {
				unlink('./uploads/' . $content_type . '/gallery/120x120/' . $image);
			}
			// unlink thumbnails also
			$cond2 = array('used_name' => $content_type);
			$thumb_val_gal = $this->fct->getonecell("content_type", "thumb_val_gal", $cond2);
			$resize_status = $this->fct->getonecell('content_type', 'resize_status', $cond2);
			$sumb_val1 = explode(",", $thumb_val_gal);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists('./uploads/' . $content_type . '/gallery/' . $value . '/' . $image)) {
					unlink("./uploads/" . $content_type . "/gallery/" . $value . "/" . $image);
				}
			}
			// end unlink  thumbnails 
			$image1 = $this->fct->uploadImage("image", $content_type . "/gallery");
			$this->fct->createthumb($image1, $content_type . "/gallery", "120x120");
			if ($resize_status == 1) {
				$this->fct->createthumb1($image1, $content_type . "/gallery", $thumb_val_gal);
			} else {
				$this->fct->createthumb($image1, $content_type . "/gallery", $thumb_val_gal);
			}
			$_data = array(
				'title' => $this->input->post("title"),
				'image' => $image1
			);
		} else {
			$_data = array('title' => $this->input->post("title"));
		}
		$this->db->where('id_gallery', $id);
		$this->db->update($content_type . '_gallery', $_data);
		$this->session->set_userdata('success_message', 'Photos were updated successfully');
		redirect(base_url() . 'back_office/control/manage_gallery/' . $content_type . '/' . $id_gallery);
	}
	// submit photos 
	public function submit_photos()
	{
		$content_type = $this->input->post('content_type');
		$cond = array('used_name' => $content_type);
		$thumb_val_gal = $this->fct->getonecell('content_type', 'thumb_val_gal', $cond);
		$resize_status = $this->fct->getonecell('content_type', 'resize_status', $cond);
		$id_gallery = $this->input->post('id_gallery');
		for ($i = 0; $i < 5; $i++) {
			$image = "image" . $i;
			$title = "title" . $i;
			if (!empty($_FILES[$image]["name"])) {
				$image1 = $this->fct->uploadImage($image, $content_type . "/gallery");
				$this->fct->createthumb($image1, $content_type . "/gallery", "120x120");
				if ($resize_status == 1) {
					$this->fct->createthumb1($image1, $content_type . "/gallery", $thumb_val_gal);
				} else {
					$this->fct->createthumb($image1, $content_type . "/gallery", $thumb_val_gal);
				}
				$_data = array(
					'title' => $this->input->post($title),
					'image' => $image1,
					'id_' . $content_type => $id_gallery
				);
				$this->db->insert($content_type . '_gallery', $_data);
			}
		}

		$this->session->set_userdata('success_message', 'Photos were uploaded successfully');
		redirect(base_url() . 'back_office/control/manage_gallery/' . $content_type . '/' . $id_gallery);
	}

	public function delete_photo($content_type, $id_gallery)
	{
		$cond1 = array('id_gallery' => $id_gallery);
		$id = $this->fct->getonecell($content_type . "_gallery", "id_" . $content_type, $cond1);
		$image = $this->fct->getonecell($content_type . "_gallery", "image", $cond1);
		$cond2 = array('used_name' => $content_type);
		$thumb_val_gal = $this->fct->getonecell("content_type", "thumb_val_gal", $cond2);
		unlink('./uploads/' . $content_type . '/gallery/' . $image);
		unlink('./uploads/' . $content_type . '/gallery/120x120/' . $image);
		$sumb_val1 = explode(",", $thumb_val_gal);
		foreach ($sumb_val1 as $key => $value) {
			unlink("./uploads/" . $content_type . "/gallery/" . $value . "/" . $image);
		}
		$this->db->where('id_gallery', $id_gallery);
		$this->db->delete($content_type . "_gallery");
		$this->session->set_userdata('success_message', 'Photo was deleted successfully');
		redirect(base_url() . 'back_office/control/manage_gallery/' . $content_type . '/' . $id);
	}


	public function delete_all_photos()
	{
		$cehcklist = $this->input->post('cehcklist');
		$check_option = $this->input->post('check_option');
		$content_type = $this->input->post('content_type');
		$id = $this->input->post('id_gallery');
		if ($check_option == "delete_all") {
			if (count($cehcklist) > 0) {
				for ($i = 0; $i < count($cehcklist); $i++) {
					if ($cehcklist[$i] != '') {
						// deleted action 
						$cond1 = array('id_gallery' => $cehcklist[$i]);
						$image = $this->fct->getonecell($content_type . "_gallery", "image", $cond1);
						unlink('./uploads/' . $content_type . '/gallery/' . $image);
						unlink('./uploads/' . $content_type . '/gallery/120x120/' . $image);
						// delter thumbnails 
						$cond2 = array('used_name' => $content_type);
						$thumb_val_gal = $this->fct->getonecell("content_type", "thumb_val_gal", $cond2);
						$sumb_val1 = explode(",", $thumb_val_gal);
						foreach ($sumb_val1 as $key => $value) {
							unlink("./uploads/" . $content_type . "/gallery/" . $value . "/" . $image);
						}
						// end delete thumbnails 

						$this->db->where('id_gallery', $cehcklist[$i]);
						$this->db->delete($content_type . "_gallery");
					}
				}
			}
			$this->session->set_userdata('success_message', 'Photos were deleted successfully');
		}
		redirect(base_url() . 'back_office/control/manage_gallery/' . $content_type . '/' . $id);
	}


	public function sorteddd()
	{
		$content_type = $this->session->userdata('content_type_gallery');
		$sort = array();
		foreach ($this->input->get('table-1') as $key => $val) {
			if (!empty($val))
				$sort[] = $val;
		}
		$i = 0;
		for ($i = 0; $i < count($sort); $i++) {
			$_data = array('sort_order' => $i);
			$this->db->where('id_gallery', $sort[$i]);
			$this->db->update($content_type . '_gallery', $_data);
		}
	}

	public function delete_image_gallery($field, $image, $id_gallery, $id, $content_type)
	{
		if (file_exists('./uploads/' . $content_type . '/gallery/' . $image)) {
			unlink("./uploads/" . $content_type . "/gallery/" . $image);
		}
		if (file_exists('./uploads/' . $content_type . '/gallery/120x120/' . $image)) {
			unlink("./uploads/" . $content_type . "/gallery/120x120/" . $image);
		}

		$cond2 = array('used_name' => $content_type);
		$thumb_val_gal = $this->fct->getonecell("content_type", "thumb_val_gal", $cond2);
		$sumb_val1 = explode(",", $thumb_val_gal);
		foreach ($sumb_val1 as $key => $value) {
			if (file_exists('./uploads/' . $content_type . '/gallery/' . $value . '/' . $image)) {
				unlink("./uploads/" . $content_type . "/gallery/" . $value . "/" . $image);
			}
		}
		$_data[$field] = "";
		$this->db->where("id_gallery", $id);
		$this->db->update($content_type . "_gallery", $_data);
		redirect(base_url() . "back_office/control/edit_photo/" . $content_type . "/" . $id_gallery . "/" . $id);
	}
}
