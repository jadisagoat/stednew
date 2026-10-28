<?
class Exam_history extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "exam_history";
	}

	public function all_data($order = "")
	{
		if ($order == "") {
			$order = "sort_order";
		}
		$listofuser = $this->fct->getAll($this->table, $order);
		$arrays = [];

		foreach ($listofuser as $row) {
			$row["PIDid_exam_history"] =
				'<input type="checkbox" name="cehcklist[]" value="' . $row["id_exam_history"] . '" />';
			if ($row["id_course"] != 0) {
				$cond = array("id_course" => $row["id_course"]);
				$row["course"] =   $this->fct->getonecell_cached("course", "title", $cond);
			} else {
				$row["course"] = '<small class="blue">--</small>';
			}
			if ($row["id_exam"] != 0) {
				$cond = array("id_exam" => $row["id_exam"]);
				$row["exam"] =   $this->fct->getonecell_cached("exam", "title", $cond);
			} else {
				$row["exam"] = '<small class="blue">--</small>';
			}
			if ($row["id_user"] != 0) {
				$cond = array("id_user" => $row["id_user"]);
				$row["user"] =   $this->fct->getonecell_cached("user", "name", $cond);
			} else {
				$row["user"] = '<small class="blue">--</small>';
			}
			if ($row["id_grade"] != 0) {
				$cond = array("id_grade" => $row["id_grade"]);
				$row["grade"] =   $this->fct->getonecell_cached("grade", "title", $cond);
			} else {
				$row["grade"] = '<small class="blue">--</small>';
			}
			$row["exam_date"] =  $this->fct->date_out_formate($row["exam_date"]);
			if (!empty($row["certificate"])) {
				$row["certificate"] = '<a href="' . base_url() . 'uploads/exam_history/' . $row["certificate"] . '" class="blue" title="' . $row["title"] . '" >
		  show file</a>';
			} else {
				$row["certificate"] = '<small class="blue">No Image Available</small>';
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
		$data["title"] = "List exam history";
		$data["content"] = "back_office/exam_history/list";
		$data["info"] = $this->fct->getAll($this->table, $order);
		$this->load->view("back_office/template", $data);
	}

	public function add()
	{
		$data["title"] = "Add exam history";
		$data["content"] = "back_office/exam_history/add";
		$this->load->view("back_office/template", $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit exam history";
		$data["content"] = "back_office/exam_history/add";
		$cond = array("id_exam_history" => $id);
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
		$this->db->where("id_exam_history", $id);
		$this->db->update($this->table, $_data);
		$this->session->set_userdata("success_message", "Information was deleted successfully");

		redirect(base_url() . "back_office/exam_history");
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
						$this->db->where("id_exam_history", $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata("success_message", "Informations were deleted successfully");
		}
		redirect(base_url() . "back_office/exam_history");
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
			$this->db->where("id_exam_history", $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}

	public function submit()
	{
		$data["title"] = "Add / Edit exam history";
		$this->form_validation->set_rules("course", "course", "trim");
		$this->form_validation->set_rules("exam", "exam", "trim");
		$this->form_validation->set_rules("user", "user", "trim");
		$this->form_validation->set_rules("score", "score", "trim");
		$this->form_validation->set_rules("grade", "grade", "trim");
		$this->form_validation->set_rules("exam_date", "exam date", "trim");
		$this->form_validation->set_rules("number_attempts", "number attempts", "trim");
		$this->form_validation->set_rules("certificate", "certificate", "trim");
		if ($this->form_validation->run() == FALSE) {
			if ($this->input->post("id") != "") {
				$cond = array("id_exam_history" => $this->input->post("id"));
				$data["id"] = $this->input->post("id");
				$data["info"] = $this->fct->getonerecord("exam_history", $cond);
			}
			$data["content"] = "back_office/exam_history/add";
			$this->load->view("back_office/template", $data);
		} else {
			$_data["id_course"] = $this->input->post("course");
			$_data["id_exam"] = $this->input->post("exam");
			$_data["id_user"] = $this->input->post("user");
			$_data["score"] = $this->input->post("score");
			$_data["id_grade"] = $this->input->post("grade");

			$_data["exam_date"] = $this->fct->date_in_formate($this->input->post("exam_date"));
			$_data["number_attempts"] = $this->input->post("number_attempts");

			if (!empty($_FILES["certificate"]["name"])) {
				if ($this->input->post("id") != "") {
					$cond_file = array("id_exam_history" => $this->input->post("id"));
					$old_file = $this->fct->getonecell("exam_history", "certificate", $cond_file);
					if (!empty($old_file))
						unlink("./uploads/exam_history/" . $old_file);
				}
				$file1 = $this->fct->uploadImage("certificate", "exam_history");
				$_data["certificate"] = $file1;
			}

			if ($this->input->post("id") != "") {
				$_data["updated_date"] = date("Y-m-d h:i:s");
				$this->db->where("id_exam_history", $this->input->post("id"));
				$this->db->update($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was updated successfully");
			} else {
				$_data["created_date"] = date("Y-m-d h:i:s");
				$this->db->insert($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was inserted successfully");
			}
			redirect(base_url() . "back_office/exam_history");
		}
	}

	public function delete_image($field, $image, $id)
	{
		if (file_exists("./uploads/exam_history/" . $image)) {
			unlink("./uploads/exam_history/" . $image);
		}
		$q = " SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'exam history')
AND name = '" . $field . "'";
		$query = $this->db->query($q);
		$res = $query->row_array();
		if ($res["thumb"] == 1) {
			$sumb_val1 = explode(",", $res["thumb_val"]);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists("./uploads/exam_history/" . $value . "/" . $image)) {
					unlink("./uploads/exam_history/" . $value . "/" . $image);
				}
			}
		}
		$_data[$field] = "";
		$this->db->where("id_exam_history", $id);
		$this->db->update("exam_history", $_data);
		redirect(base_url() . "back_office/exam_history");
	}
}
