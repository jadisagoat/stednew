<?
class Content extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "content";
	}

	public function test($order = "")
	{
		echo "<pre>";
		print_r(phpinfo());
	}

	public function all_data($order = "")
	{
		if ($order == "") {
			$order = "sort_order";
		}


		$cond = array("id_course" => $this->session->userdata("id_course"));
		$listofuser = $this->fct->getAll_cond($this->table, $order, $cond);

		//$listofuser = $this->fct->getAll($this->table, $order);
		$arrays = [];

		foreach ($listofuser as $row) {
			$row["PIDid_content"] =
				'<input type="checkbox" name="cehcklist[]" value="' . $row["id_content"] . '" />';
			if ($row["id_course"] != 0) {
				$cond = array("id_course" => $row["id_course"]);
				$row["course"] =   $this->fct->getonecell("course", "title", $cond);
			} else {
				$row["course"] = '<small class="blue">--</small>';
			}
			if ($row["id_type"] != 0) {
				$cond = array("id_type" => $row["id_type"]);
				$row["type"] =   $this->fct->getonecell("type", "title", $cond);
			} else {
				$row["type"] = '<small class="blue">--</small>';
			}
			if (!empty($row["image"])) {
				$row["image"] = '<a onclick="popup(\'' . base_url() . 'uploads/' . $this->table . '/' . $row["image"] . '\')" class="blue gallery" title="' . $row["title"] . '">show image</a>';
			} else {
				$row["image"] = '<small class="blue">No Image Available</small>';
			}
			if (!empty($row["file"])) {
				$row["file"] = '<a href="' . base_url() . 'uploads/content/' . $row["file"] . '" class="blue" title="' . $row["title"] . '" >
		  show file</a>';
			} else {
				$row["file"] = '<small class="blue">No Image Available</small>';
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

	public function index($order = "")
	{
		if ($order == "")
			$order = "sort_order";
		$data["title"] = "List content";
		$data["content"] = "back_office/content/list";
		// $data["info"]=$this->fct->getAll($this->table,$order);
		// $this->load->view("back_office/template",$data);

		$cond = array("id_course" => $this->session->userdata("id_course"));
		$data["info"] = $this->fct->getAll_cond($this->table, $order, $cond);
		$this->load->view("back_office/template", $data);
	}


	public function lists($id_course = "", $order = "")
	{
		$this->load->library('session');

		if ($id_course) {
			$id_course = array('id_course'  => $id_course);
			$this->session->set_userdata($id_course);
		}

		if ($order == "")
			$order = "sort_order";
		$data["title"] = "List content";
		$data["content"] = "back_office/content/list";
		$cond = array("id_course" => $this->session->userdata("id_course"));
		$data["info"] = $this->fct->getAll_cond($this->table, $order, $cond);
		$this->load->view("back_office/template", $data);
	}



	public function add()
	{
		$data["title"] = "Add content";
		$data["content"] = "back_office/content/add";
		$this->load->view("back_office/template", $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit content";
		$data["content"] = "back_office/content/add";
		$cond = array("id_content" => $id);
		$data["id"] = $id;
		$data["info"] = $this->fct->getonerecord($this->table, $cond);
		$this->load->view("back_office/template", $data);
	}

	public function delete($id)
	{
		$_data = array(
			"deleted" => 1,
			"deleted_date" => date("Y-m-d h:i:s")
		);
		$this->db->where("id_content", $id);
		$this->db->update($this->table, $_data);
		$this->session->set_userdata("success_message", "Information was deleted successfully");

		redirect(base_url() . "back_office/content");
	}

	public function delete_all()
	{
		$cehcklist = $this->input->post("cehcklist");
		$check_option = $this->input->post("check_option");
		if ($check_option == "delete_all") {
			if (count($cehcklist) > 0) {
				for ($i = 0; $i < count($cehcklist); $i++) {
					if ($cehcklist[$i] != "") {
						$_data = array(
							"deleted" => 1,
							"deleted_date" => date("Y-m-d h:i:s")
						);
						$this->db->where("id_content", $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata("success_message", "Informations were deleted successfully");
		}
		redirect(base_url() . "back_office/content");
	}

	public function sorted()
	{
		$sort = array();
		foreach ($this->input->get("table-1") as $key => $val) {
			if (!empty($val))
				$sort[] = $val;
		}
		$i = 0;
		for ($i = 0; $i < count($sort); $i++) {
			$_data = array("sort_order" => $i);
			$this->db->where("id_content", $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}

	public function submit()
	{
		$data["title"] = "Add / Edit content";
		$this->form_validation->set_rules("title", "title", "trim|required");
		$this->form_validation->set_rules("title_page", "title page", "trim");
		$this->form_validation->set_rules("course", "course", "trim");
		$this->form_validation->set_rules("hours", "hours", "trim|required");
		$this->form_validation->set_rules("type", "type", "trim");
		$this->form_validation->set_rules("image", "image", "trim");
		$this->form_validation->set_rules("file", "file", "trim");
		$this->form_validation->set_rules("link", "link", "trim");
		$this->form_validation->set_rules("description", "description", "trim");
		if ($this->form_validation->run() == FALSE) {
			if ($this->input->post("id") != "") {
				$cond = array("id_content" => $this->input->post("id"));
				$data["id"] = $this->input->post("id");
				$data["info"] = $this->fct->getonerecord("content", $cond);
			}
			$data["content"] = "back_office/content/add";
			$this->load->view("back_office/template", $data);
		} else {
			$_data["title"] = $this->input->post("title");
			$_data["title_page"] = $this->input->post("title_page");
			$_data["id_course"] = $this->input->post("course");
			$_data["hours"] = $this->input->post("hours");
			$_data["id_type"] = $this->input->post("type");
			if (!empty($_FILES["image"]["name"])) {
				if ($this->input->post("id") != "") {
					$cond_image = array("id_content" => $this->input->post("id"));
					$old_image = $this->fct->getonecell("content", "image", $cond_image);
					if (!empty($old_image) && file_exists('./uploads/content/' . $old_image)) {
						unlink("./uploads/content/" . $old_image);
					}
				}
				$image1 = $this->fct->uploadImage("image", "content");
				$_data["image"] = $image1;
			}

			if (!empty($_FILES["file"]["name"])) {
				if ($this->input->post("id") != "") {
					$cond_file = array("id_content" => $this->input->post("id"));
					$old_file = $this->fct->getonecell("content", "file", $cond_file);
					if (!empty($old_file))
						unlink("./uploads/content/" . $old_file);
				}
				$file1 = $this->fct->uploadImage("file", "content");
				$_data["file"] = $file1;
			}
			$_data["link"] = $this->input->post("link");
			$_data["description"] = $this->input->post("description");

			if ($this->input->post("id") != "") {
				$_data["updated_date"] = date("Y-m-d h:i:s");
				$this->db->where("id_content", $this->input->post("id"));
				$this->db->update($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was updated successfully");
			} else {
				$_data["created_date"] = date("Y-m-d h:i:s");
				$this->db->insert($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was inserted successfully");
			}
			redirect(base_url() . "back_office/content");
		}
	}

	public function delete_image($field, $image, $id)
	{
		if (file_exists("./uploads/content/" . $image)) {
			unlink("./uploads/content/" . $image);
		}
		$q = " SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'content')
AND name = '" . $field . "'";
		$query = $this->db->query($q);
		$res = $query->row_array();
		if ($res["thumb"] == 1) {
			$sumb_val1 = explode(",", $res["thumb_val"]);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists("./uploads/content/" . $value . "/" . $image)) {
					unlink("./uploads/content/" . $value . "/" . $image);
				}
			}
		}
		$_data[$field] = "";
		$this->db->where("id_content", $id);
		$this->db->update("content", $_data);
		redirect(base_url() . "back_office/content");
	}
}
