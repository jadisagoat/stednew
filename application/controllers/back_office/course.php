<?
class Course extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "course";
	}





	public function getalldata()
	{

		$query = 'SELECT course.*,course.title as courseName,category.title as category_title
 
FROM course 
LEFT JOIN category ON course.id_category = category.id_category
WHERE  course.deleted = 0';

		$result = $this->fct->search($query);

		$courses = [];

		foreach ($result as $row) {
			$row["PIDid_course"] =
				'<input type="checkbox" name="cehcklist[]" value="' . $row["id_course"] . '" />';
			if ($row["active"] == 1)
				$row["active"] = "<span class=' label label-success' >Active</span>";
			else
				$row["active"] = "<span class=' label label-important ' >InActive</span>";
			// Handle the image display
			if (!empty($row["image"])) {
				$row["image"] = '<a onclick="popup(\'' . base_url() . 'uploads/' . $this->table . '/' . $row["image"] . '\')" class="blue gallery" title="' . $row["title"] . '">show image</a>';
			} else {
				$row["image"] = '<small class="blue">No Image Available</small>';
			}

			// Handle top_course status
			if ($row["top_course"] == 1) {
				$row["top_course"] = "<span class='label label-success'>Active</span>";
			} else {
				$row["top_course"] = "<span class='label label-important'>InActive</span>";
			}

			// Handle category
			if ($row["id_category"] != 0) {
				$row["category"] = !empty($row["category_title"]) ? $row["category_title"] : '--';
			} else {
				$row["category"] = '<small class="blue">--</small>';
			}

			// Add group_category - get from category table
			if ($row["id_category"] != 0) {
				$cond = array("id_category" => $row["id_category"]);
				$group_cat_id = $this->fct->getonecell("category", "id_group_category", $cond);
				if ($group_cat_id) {
					$cond_gc = array("id_group_category" => $group_cat_id);
					$row["group_category"] = $this->fct->getonecell("group_category", "title", $cond_gc);
				} else {
					$row["group_category"] = 'Uncategorized';
				}
			} else {
				$row["group_category"] = 'Uncategorized';
			}

			// Handle schedule
			$row["schedule"] = $this->fct->date_out_formate($row["schedule"]);

			// If the course is not already in the $courses array, add it
			if (!isset($courses[$row['id_course']])) {
				// Fetch contents for this course
				$contents = $this->db->get_where('content', array('id_course' => $row['id_course'], 'deleted' => 0))->result_array();
				$cnt_count = count($contents);
				$desc_html = "<strong>Units ($cnt_count):</strong><ul>";
				foreach ($contents as $cnt) {
					$desc_html .= "<li>" . $cnt['title'] . "</li>";
				}
				$desc_html .= "</ul>";

				$courses[$row['id_course']] = [
					'id_course' => $row['id_course'],
					'description' => $desc_html,
					'content_count' => $cnt_count,
					'PIDid_course' => $row['PIDid_course'],
					'courseName' => $row['courseName'],
					'category' => $row['category'],
					'group_category' => $row['group_category'],
					'sort_order' => $row['sort_order'],
					'schedule' => $row['schedule'],
					'image' => $row['image'],
					'userName' => $row['userName'],
					'role' => $row['roleName'],
					'employees' => [],
					'hours' => $row['hours'],
					'active' => $row["active"]
				];
			}

			// Add employees to the course
			$courses[$row['id_course']]['employees'][] = [
				'userName' => $row['userName'],
				'role' => $row['roleName']
			];
		}

		$data = array_values($courses);

		// Limiting number of records to only a page
		$totalRecords_num = count($data);

		if ($totalRecords_num > 0) {
			$response["data"] = $data;
		} else {
			$response["data"] = [];
		}

		$response["totalCount"] = $totalRecords_num;

		// Set appropriate headers
		header("Content-Type: application/json");
		echo json_encode($response);
	}






	public function all_data($order = "")
	{
		if ($order == "") {
			$order = "sort_order";
		}
		$listofuser = $this->fct->getAll($this->table, $order);
		$arrays = [];

		foreach ($listofuser as $row) {
			$row["PIDid_course"] =
				'<input type="checkbox" name="cehcklist[]" value="' . $row["id_course"] . '" />';
			if (!empty($row["image"])) {
				$row["image"] = '<a onclick="popup(\'' . base_url() . 'uploads/' . $this->table . '/' . $row["image"] . '\')" class="blue gallery" title="' . $row["title"] . '">show image</a>';
			} else {
				$row["image"] = '<small class="blue">No Image Available</small>';
			}
			if ($row["active"] == 1)
				$row["active"] = "<span class=' label label-success' >Active</span>";
			else
				$row["active"] = "<span class=' label label-important ' >InActive</span>";
			if ($row["top_course"] == 1)
				$row["top_course"] = "<span class=' label label-success' >Active</span>";
			else
				$row["top_course"] = "<span class=' label label-important ' >InActive</span>";
			if ($row["id_category"] != 0) {
				$cond = array("id_category" => $row["id_category"]);
				$row["category"] =   $this->fct->getonecell("category", "title", $cond);
			} else {
				$row["category"] = '<small class="blue">--</small>';
			}
			$row["schedule"] =  $this->fct->date_out_formate($row["schedule"]);
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
		$order = preg_replace('/[^A-Za-z0-9_ ]/', '', $order); // Security: ORDER BY comes from the URL
		$data["title"] = "List course";
		$data["content"] = "back_office/course/listnew";
		$data["info"] = $this->fct->getAll($this->table, $order);
		// grid (listnew.php), as the legacy getalldata() computed it: group category of the course's category,
		// number of units (contents) + their titles for the count tooltip, number of participants (3 queries in all)
		$groups = array();
		foreach ($this->db->query("SELECT c.id_category, g.title FROM category c JOIN group_category g ON g.id_group_category = c.id_group_category")->result_array() as $g) {
			$groups[$g["id_category"]] = $g["title"];
		}
		$units = array();
		foreach ($this->db->query("SELECT id_course, title FROM content WHERE deleted = 0 ORDER BY sort_order, id_content")->result_array() as $u) {
			$units[$u["id_course"]][] = $u["title"];
		}
		$enrolled = array();
		foreach ($this->db->query("SELECT id_course, COUNT(*) AS n FROM user_course WHERE deleted = 0 GROUP BY id_course")->result_array() as $e) {
			$enrolled[$e["id_course"]] = (int) $e["n"];
		}
		foreach ($data["info"] as $i => $row) {
			$id = $row["id_course"];
			$data["info"][$i]["user_count"] = isset($enrolled[$id]) ? $enrolled[$id] : 0;
			$data["info"][$i]["group_category"] = isset($groups[$row["id_category"]]) ? $groups[$row["id_category"]] : "";
			$data["info"][$i]["content_count"] = isset($units[$id]) ? count($units[$id]) : 0;
			$data["info"][$i]["content_tree"] = isset($units[$id]) ? implode("\n", $units[$id]) : "";
		}
		$this->load->view("back_office/template", $data);
	}

	public function add()
	{
		$data["title"] = "Add course";
		$data["content"] = "back_office/course/add";
		$this->load->view("back_office/template", $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit course";
		$data["content"] = "back_office/course/add";
		$cond = array("id_course" => $id);
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
		$this->db->where("id_course", $id);
		$this->db->update($this->table, $_data);
		$this->session->set_userdata("success_message", "Information was deleted successfully");

		redirect(base_url() . "back_office/course");
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
						$this->db->where("id_course", $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata("success_message", "Informations were deleted successfully");
		}
		redirect(base_url() . "back_office/course");
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
			$this->db->where("id_course", $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}

	public function submit()
	{
		$data["title"] = "Add / Edit course";
		$this->form_validation->set_rules("title", "title", "trim|required");
		$this->form_validation->set_rules("image", "image", "trim");
		$this->form_validation->set_rules("top_course", "top course", "trim");
		$this->form_validation->set_rules("category", "category", "trim");
		$this->form_validation->set_rules("summary", "summary", "trim");
		$this->form_validation->set_rules("description", "description", "trim");
		$this->form_validation->set_rules("requirements", "requirements", "trim");
		$this->form_validation->set_rules("schedule", "schedule", "trim");
		$this->form_validation->set_rules("hours", "hours", "trim");
		$this->form_validation->set_rules("price", "price", "trim");
		$this->form_validation->set_rules("old_price", "old price", "trim");
		$this->form_validation->set_rules("course_brief", "course brief", "trim");
		if ($this->form_validation->run() == FALSE) {
			if ($this->input->post("id") != "") {
				$cond = array("id_course" => $this->input->post("id"));
				$data["id"] = $this->input->post("id");
				$data["info"] = $this->fct->getonerecord("course", $cond);
			}
			$data["content"] = "back_office/course/add";
			$this->load->view("back_office/template", $data);
		} else {
			$_data["title"] = $this->input->post("title");
			$_data["active"] = $this->input->post("active");
			if (!empty($_FILES["image"]["name"])) {
				if ($this->input->post("id") != "") {
					$cond_image = array("id_course" => $this->input->post("id"));
					$old_image = $this->fct->getonecell("course", "image", $cond_image);
					if (!empty($old_image) && file_exists('./uploads/course/' . $old_image)) {
						unlink("./uploads/course/" . $old_image);
					}
				}
				$image1 = $this->fct->uploadImage("image", "course");
				$_data["image"] = $image1;
			}
			$_data["top_course"] = $this->input->post("top_course");
			$_data["id_category"] = $this->input->post("category");
			$_data["summary"] = $this->input->post("summary");
			$_data["description"] = $this->input->post("description");
			$_data["requirements"] = $this->input->post("requirements");

			$_data["schedule"] = $this->fct->date_in_formate($this->input->post("schedule"));
			//$_data["hours"] = $this->input->post("hours");
			$id_course = $this->input->post("id");

			$this->db->select_sum('hours');
			$this->db->from('content');
			$this->db->where('id_course', $id_course);
			$this->db->where('deleted', 0);
			$query = $this->db->get();
			$result = $query->row();

			$_data["hours"] = $result->hours ?? 0; // Default to 0 if null

			$_data["price"] = $this->input->post("price");
			$_data["old_price"] = $this->input->post("old_price");
			$_data["course_brief"] = $this->input->post("course_brief");

			if ($this->input->post("id") != "") {
				$_data["updated_date"] = date("Y-m-d h:i:s");
				$this->db->where("id_course", $this->input->post("id"));
				$this->db->update($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was updated successfully");
			} else {
				$_data["created_date"] = date("Y-m-d h:i:s");
				$this->db->insert($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was inserted successfully");
			}
			redirect(base_url() . "back_office/course");
		}
	}

	public function delete_image($field, $image, $id)
	{
		if (file_exists("./uploads/course/" . $image)) {
			unlink("./uploads/course/" . $image);
		}
		$q = " SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'course')
AND name = '" . $field . "'";
		$query = $this->db->query($q);
		$res = $query->row_array();
		if ($res["thumb"] == 1) {
			$sumb_val1 = explode(",", $res["thumb_val"]);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists("./uploads/course/" . $value . "/" . $image)) {
					unlink("./uploads/course/" . $value . "/" . $image);
				}
			}
		}
		$_data[$field] = "";
		$this->db->where("id_course", $id);
		$this->db->update("course", $_data);
		redirect(base_url() . "back_office/course");
	}
}
