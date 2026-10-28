<?
class User_exam extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "user_exam";
	}

	public function all_data($order = "")
	{
		if ($order == "") {
			$order = "sort_order";
		}
		$listofuser = $this->fct->getAll($this->table, $order);
		$arrays = [];

		foreach ($listofuser as $row) {
			$row["PIDid_user_exam"] =
				'<input type="checkbox" name="cehcklist[]" value="' . $row["id_user_exam"] . '" />';
			if ($row["id_user"] != 0) {
				$cond = array("id_user" => $row["id_user"]);
				$row["user"] =   $this->fct->getonecell_cached("user", "name", $cond);
			} else {
				$row["user"] = '<small class="blue">--</small>';
			}
			if ($row["id_exam"] != 0) {
				$cond = array("id_exam" => $row["id_exam"]);
				$row["exam"] =   $this->fct->getonecell_cached("exam", "title", $cond);
			} else {
				$row["exam"] = '<small class="blue">--</small>';
			}
			if ($row["id_question"] != 0) {
				$cond = array("id_question" => $row["id_question"]);
				$row["question"] =   $this->fct->getonecell_cached("question", "title", $cond);
			} else {
				$row["question"] = '<small class="blue">--</small>';
			}
			if ($row["id_answer"] != 0) {
				$cond = array("id_answer" => $row["id_answer"]);
				$row["answer"] =   $this->fct->getonecell_cached("answer", "title", $cond);
			} else {
				$row["answer"] = '<small class="blue">--</small>';
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
		$data["title"] = "List user exam";
		$data["content"] = "back_office/user_exam/list";
		// ~3500 rows with 4 lookups: the grid loads them page by page from grid_data() (server mode)
		$data["info"] = array();
		$this->load->view("back_office/template", $data);
	}

	/** Server-mode rows of the user exam list (views/back_office/user_exam/list.php, 'ajax' => true). */
	public function grid_data()
	{
		bo_grid_output(bo_grid_data($this->table, array(
			'id_content' => 48,
			'columns' => array('id_user', 'id_exam', 'id_question', 'id_answer'),
			'search' => array('id_user_text', 'id_exam_text', 'id_question_text', 'id_answer_text'),
			'lookups' => array(
				'id_user' => array('user', 'name', 'id_user'),
				'id_exam' => array('exam', 'title', 'id_exam'),
				'id_question' => array('question', 'title', 'id_question'),
				'id_answer' => array('answer', 'title', 'id_answer'),
			),
			'order' => 'id_user_exam desc',
		)));
	}

	public function add()
	{
		$data["title"] = "Add user exam";
		$data["content"] = "back_office/user_exam/add";
		$this->load->view("back_office/template", $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit user exam";
		$data["content"] = "back_office/user_exam/add";
		$cond = array("id_user_exam" => $id);
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
		$this->db->where("id_user_exam", $id);
		$this->db->update($this->table, $_data);
		$this->session->set_userdata("success_message", "Information was deleted successfully");

		redirect(base_url() . "back_office/user_exam");
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
						$this->db->where("id_user_exam", $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata("success_message", "Informations were deleted successfully");
		}
		redirect(base_url() . "back_office/user_exam");
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
			$this->db->where("id_user_exam", $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}

	public function submit()
	{
		$data["title"] = "Add / Edit user exam";
		$this->form_validation->set_rules("user", "user", "trim|required");
		$this->form_validation->set_rules("exam", "exam", "trim");
		$this->form_validation->set_rules("question", "question", "trim");
		$this->form_validation->set_rules("answer", "answer", "trim");
		if ($this->form_validation->run() == FALSE) {
			if ($this->input->post("id") != "") {
				$cond = array("id_user_exam" => $this->input->post("id"));
				$data["id"] = $this->input->post("id");
				$data["info"] = $this->fct->getonerecord("user_exam", $cond);
			}
			$data["content"] = "back_office/user_exam/add";
			$this->load->view("back_office/template", $data);
		} else {
			$_data["id_user"] = $this->input->post("user");
			$_data["id_exam"] = $this->input->post("exam");
			$_data["id_question"] = $this->input->post("question");
			$_data["id_answer"] = $this->input->post("answer");

			if ($this->input->post("id") != "") {
				$_data["updated_date"] = date("Y-m-d h:i:s");
				$this->db->where("id_user_exam", $this->input->post("id"));
				$this->db->update($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was updated successfully");
			} else {
				$_data["created_date"] = date("Y-m-d h:i:s");
				$this->db->insert($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was inserted successfully");
			}
			redirect(base_url() . "back_office/user_exam");
		}
	}

	public function delete_image($field, $image, $id)
	{
		if (file_exists("./uploads/user_exam/" . $image)) {
			unlink("./uploads/user_exam/" . $image);
		}
		$q = " SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'user exam')
AND name = '" . $field . "'";
		$query = $this->db->query($q);
		$res = $query->row_array();
		if ($res["thumb"] == 1) {
			$sumb_val1 = explode(",", $res["thumb_val"]);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists("./uploads/user_exam/" . $value . "/" . $image)) {
					unlink("./uploads/user_exam/" . $value . "/" . $image);
				}
			}
		}
		$_data[$field] = "";
		$this->db->where("id_user_exam", $id);
		$this->db->update("user_exam", $_data);
		redirect(base_url() . "back_office/user_exam");
	}
}
