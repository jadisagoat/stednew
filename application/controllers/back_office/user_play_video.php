<?
class User_play_video extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "user_play_video";
	}

	public function all_data($order = "")
	{
		if ($order == "") {
			$order = "sort_order";
		}
		$listofuser = $this->fct->getAll($this->table, $order);
		$arrays = [];

		foreach ($listofuser as $row) {
			$row["PIDid_user_play_video"] =
				'<input type="checkbox" name="cehcklist[]" value="' . $row["id_user_play_video"] . '" />';
			if ($row["id_user"] != 0) {
				$cond = array("id_user" => $row["id_user"]);
				$row["user"] =   $this->fct->getonecell("user", "name", $cond);
			} else {
				$row["user"] = '<small class="blue">--</small>';
			}
			if ($row["id_video"] != 0) {
				$cond = array("id_video" => $row["id_video"]);
				$row["video"] =   $this->fct->getonecell("video", "title", $cond);
			} else {
				$row["video"] = '<small class="blue">--</small>';
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
		$data["title"] = "List user_play_video";
		$data["content"] = "back_office/user_play_video/list";
		$data["info"] = $this->fct->getAll($this->table, $order);
		$this->load->view("back_office/template", $data);
	}

	public function add()
	{
		$data["title"] = "Add user_play_video";
		$data["content"] = "back_office/user_play_video/add";
		$this->load->view("back_office/template", $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit user_play_video";
		$data["content"] = "back_office/user_play_video/add";
		$cond = array("id_user_play_video" => $id);
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
		$this->db->where("id_user_play_video", $id);
		$this->db->update($this->table, $_data);
		$this->session->set_userdata("success_message", "Information was deleted successfully");

		redirect(base_url() . "back_office/user_play_video");
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
						$this->db->where("id_user_play_video", $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata("success_message", "Informations were deleted successfully");
		}
		redirect(base_url() . "back_office/user_play_video");
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
			$this->db->where("id_user_play_video", $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}

	public function submit()
	{
		$data["title"] = "Add / Edit user_play_video";
		$this->form_validation->set_rules("user", "user", "trim|required");
		$this->form_validation->set_rules("video", "video", "trim|required");
		if ($this->form_validation->run() == FALSE) {
			if ($this->input->post("id") != "") {
				$cond = array("id_user_play_video" => $this->input->post("id"));
				$data["id"] = $this->input->post("id");
				$data["info"] = $this->fct->getonerecord("user_play_video", $cond);
			}
			$data["content"] = "back_office/user_play_video/add";
			$this->load->view("back_office/template", $data);
		} else {
			$_data["id_user"] = $this->input->post("user");
			$_data["id_video"] = $this->input->post("video");

			if ($this->input->post("id") != "") {
				$_data["updated_date"] = date("Y-m-d h:i:s");
				$this->db->where("id_user_play_video", $this->input->post("id"));
				$this->db->update($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was updated successfully");
			} else {
				$_data["created_date"] = date("Y-m-d h:i:s");
				$this->db->insert($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was inserted successfully");
			}
			redirect(base_url() . "back_office/user_play_video");
		}
	}

	public function delete_image($field, $image, $id)
	{
		if (file_exists("./uploads/user_play_video/" . $image)) {
			unlink("./uploads/user_play_video/" . $image);
		}
		$q = " SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'user play video')
AND name = '" . $field . "'";
		$query = $this->db->query($q);
		$res = $query->row_array();
		if ($res["thumb"] == 1) {
			$sumb_val1 = explode(",", $res["thumb_val"]);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists("./uploads/user_play_video/" . $value . "/" . $image)) {
					unlink("./uploads/user_play_video/" . $value . "/" . $image);
				}
			}
		}
		$_data[$field] = "";
		$this->db->where("id_user_play_video", $id);
		$this->db->update("user_play_video", $_data);
		redirect(base_url() . "back_office/user_play_video");
	}
}
