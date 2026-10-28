<?
class User extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "user";
	}

	public function get_rating()
	{
		$selected_month = (int) $_REQUEST['month']; // Security: used in SQL
		$this->load->database();
		$query = "
		SELECT 
			 course.title AS country,
            AVG(user_course_rating.rating) AS area
        FROM 
            course
       INNER JOIN 
            category ON course.id_category = category.id_category
        LEFT JOIN 
        user_course_rating ON course.id_course = user_course_rating.id_course
        WHERE 
            course.deleted = 0
            AND category.deleted = 0
            AND course.top_course = 1
        GROUP BY 
            course.id_course;

		";

		$result = $this->db->query($query)->result_array();
		echo json_encode(['data' => $result]);
	}



	public function get_contents_read_data()
	{
		$selected_month = (int) $_REQUEST['month']; // Security: used in SQL
		$this->load->database();
		$query = "
		SELECT 
    cc.id_course,
    cc.title AS course_title,
    COUNT(DISTINCT uc.id_user) AS total_contents_read,
    GROUP_CONCAT(DISTINCT u.username ORDER BY u.username ASC SEPARATOR ', ') AS users_list,
    COUNT(DISTINCT ehuser.id_user) AS total_exam,
    GROUP_CONCAT(DISTINCT CONCAT(ehuser.username, ' (', gg.title, ')') ORDER BY ehuser.username ASC SEPARATOR ', ') AS exam_users_list
FROM 
    course cc
LEFT JOIN 
    user_content uc ON uc.id_course = cc.id_course
LEFT JOIN 
    user u ON uc.id_user = u.id_user
LEFT JOIN 
    exam_history eh ON cc.id_course = eh.id_course
LEFT JOIN 
    grade gg ON gg.id_grade = eh.id_grade
LEFT JOIN 
    user ehuser ON ehuser.id_user = eh.id_user
WHERE 
    MONTH(uc.created_date) = 08 -- Filter by the selected month (you may need to adjust this for dynamic filtering)
GROUP BY 
    cc.id_course
ORDER BY 
    cc.id_course DESC;


		";

		$result = $this->db->query($query)->result_array();
		echo json_encode(['data' => $result]);
	}


	public function get_population_data($order = "")
	{

		$selected_month = (int) $_REQUEST['month']; // Security: used in SQL
		$query = " SELECT  
    DATE(created_date) AS created_day, 
    COUNT(*) AS total_exams
FROM 
    `user`
WHERE 
    MONTH(created_date) = $selected_month 
GROUP BY 
    created_day
ORDER BY 
    created_day ASC ";

		$usress = $this->fct->search($query);

		$arrays = [];
		foreach ($usress as $row) {
			$arrays[] = $row;
		}

		// Limiting the number of records to only a page
		$response["data"] = $arrays;
		$totalRecords_num = count($arrays);

		if ($totalRecords_num > 0) {
			// Data exists, proceed normally
		} else {
			$response["data"] = null;
		}

		// Set appropriate headers
		header("Content-Type: application/json");
		echo json_encode($response);
	}



	public function get_enrollment_user_vy_day($order = "")
	{

		$selected_month = (int) $_REQUEST['month']; // Security: used in SQL
		$query = "
	SELECT 
	DATE(uc.created_date ) AS enrollment_date, 
    COUNT(*) AS total_enrollments
FROM 
    user_course uc
JOIN 
    user u ON uc.id_user = u.id_user

	WHERE 
    MONTH(uc.created_date) = $selected_month 

GROUP BY 
    enrollment_date
ORDER BY 
    enrollment_date ASC";

		$usress = $this->fct->search($query);

		$arrays = [];
		foreach ($usress as $row) {
			$row['total_enrollments'] = (int)$row['total_enrollments'];
			$arrays[] = $row;
		}

		// Limiting the number of records to only a page
		$response["data"] = $arrays;
		$totalRecords_num = count($arrays);

		if ($totalRecords_num > 0) {
			// Data exists, proceed normally
		} else {
			$response["data"] = null;
		}

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
			unset($row["password"], $row["hashsendcode"]); // Security: never send passwords / reset codes to the browser
			$row["PIDid_user"] =
				'<input type="checkbox" name="cehcklist[]" value="' . $row["id_user"] . '" />';
			if (!empty($row["signature"])) {
				$row["signature"] = '<a onclick="popup(\'' . base_url() . 'uploads/' . $this->table . '/' . $row["signature"] . '\')" class="blue gallery" title="' . $row["title"] . '">show image</a>';
			} else {
				$row["signature"] = '<small class="blue">No Image Available</small>';
			}
			if ($row["id_role"] != 0) {
				$cond = array("id_role" => $row["id_role"]);
				$row["global_role"] =   $this->fct->getonecell_cached("role", "title", $cond);
			} else {
				$row["global_role"] = '<small class="blue">--</small>';
			}
			if (!empty($row["image"])) {
				$row["image"] = '<a onclick="popup(\'' . base_url() . 'uploads/' . $this->table . '/' . $row["image"] . '\')" class="blue gallery" title="' . $row["title"] . '">show image</a>';
			} else {
				$row["image"] = '<small class="blue">No Image Available</small>';
			}
			if ($row["active"] == 1)
				$row["active"] = "<span class=' label label-success' >Active</span>";
			else
				$row["active"] = "<span class=' label label-important ' >InActive</span>";
			if ($row["id_countries"] != 0) {
				$cond = array("id_countries" => $row["id_countries"]);
				$row["id_countries"] =   $this->fct->getonecell_cached("countries", "title", $cond);
			} else {
				$row["id_countries"] = '<small class="blue">--</small>';
			}
			if ($row["id_gender"] != 0) {
				$cond = array("id_gender" => $row["id_gender"]);
				$row["gender"] =   $this->fct->getonecell_cached("gender", "title", $cond);
			} else {
				$row["gender"] = '<small class="blue">--</small>';
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
		$data["title"] = "List user";
		$data["content"] = "back_office/user/list";
		$data["info"] = $this->fct->getAll($this->table, $order);
		$this->load->view("back_office/template", $data);
	}

	public function add()
	{
		$data["title"] = "Add user";
		$data["content"] = "back_office/user/add";
		$this->load->view("back_office/template", $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit user";
		$data["content"] = "back_office/user/add";
		$cond = array("id_user" => $id);
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
		$this->db->where("id_user", $id);
		$this->db->update($this->table, $_data);
		$this->session->set_userdata("success_message", "Information was deleted successfully");

		redirect(base_url() . "back_office/user");
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
						$this->db->where("id_user", $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata("success_message", "Informations were deleted successfully");
		}
		redirect(base_url() . "back_office/user");
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
			$this->db->where("id_user", $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}

	public function submit()
	{
		$data["title"] = "Add / Edit user";
		$this->form_validation->set_rules("name", "name", "trim|required");
		$this->form_validation->set_rules("username", "username", "trim");
		$this->form_validation->set_rules("password", "password", "trim");
		$this->form_validation->set_rules("hashsendcode", "hashsendcode", "trim");
		$this->form_validation->set_rules("signature", "signature", "trim");
		$this->form_validation->set_rules("global_role", "global role", "trim");
		$this->form_validation->set_rules("image", "image", "trim");
		$this->form_validation->set_rules("years_of_exp", "years of exp", "trim");
		$this->form_validation->set_rules("phone", "phone", "trim");
		$this->form_validation->set_rules("age", "age", "trim");
		$this->form_validation->set_rules("active", "active", "trim");
		$this->form_validation->set_rules("id_countries", "id_countries", "trim");
		$this->form_validation->set_rules("email", "email", "trim");
		$this->form_validation->set_rules("nationality", "nationality", "trim");
		$this->form_validation->set_rules("resident", "resident", "trim");
		$this->form_validation->set_rules("profession", "profession", "trim");
		$this->form_validation->set_rules("facebook", "facebook", "trim");
		$this->form_validation->set_rules("twitter", "twitter", "trim");
		$this->form_validation->set_rules("occupation", "occupation", "trim");
		$this->form_validation->set_rules("gender", "gender", "trim");
		$this->form_validation->set_rules("bio", "bio", "trim");
		$this->form_validation->set_rules("linkedin", "linkedin", "trim");
		$this->form_validation->set_rules("website", "website", "trim");
		$this->form_validation->set_rules("title", "title", "trim");
		$this->form_validation->set_rules("aboutme", "aboutme", "trim");
		$this->form_validation->set_rules("address", "address", "trim");
		if ($this->form_validation->run() == FALSE) {
			if ($this->input->post("id") != "") {
				$cond = array("id_user" => $this->input->post("id"));
				$data["id"] = $this->input->post("id");
				$data["info"] = $this->fct->getonerecord("user", $cond);
			}
			$data["content"] = "back_office/user/add";
			$this->load->view("back_office/template", $data);
		} else {
			$_data["name"] = $this->input->post("name");
			$_data["username"] = $this->input->post("username");
			$_data["password"] = $this->input->post("password");
			$_data["hashsendcode"] = $this->input->post("hashsendcode");
			if (!empty($_FILES["signature"]["name"])) {
				if ($this->input->post("id") != "") {
					$cond_image = array("id_user" => $this->input->post("id"));
					$old_image = $this->fct->getonecell("user", "signature", $cond_image);
					if (!empty($old_image) && file_exists('./uploads/user/' . $old_image)) {
						unlink("./uploads/user/" . $old_image);
						$sumb_val1 = explode(",", "330x60");
						foreach ($sumb_val1 as $key => $value) {
							if (file_exists("./uploads/user/" . $value . "/" . $old_image)) {
								unlink("./uploads/user/" . $value . "/" . $old_image);
							}
						}
					}
				}
				$image1 = $this->fct->uploadImage("signature", "user");
				$this->fct->createthumb1($image1, "user", "330x60");
				$_data["signature"] = $image1;
			}
			$_data["id_role"] = $this->input->post("global_role");
			if (!empty($_FILES["image"]["name"])) {
				if ($this->input->post("id") != "") {
					$cond_image = array("id_user" => $this->input->post("id"));
					$old_image = $this->fct->getonecell("user", "image", $cond_image);
					if (!empty($old_image) && file_exists('./uploads/user/' . $old_image)) {
						unlink("./uploads/user/" . $old_image);
						$sumb_val1 = explode(",", "300x350");
						foreach ($sumb_val1 as $key => $value) {
							if (file_exists("./uploads/user/" . $value . "/" . $old_image)) {
								unlink("./uploads/user/" . $value . "/" . $old_image);
							}
						}
					}
				}
				$image1 = $this->fct->uploadImage("image", "user");
				$this->fct->createthumb1($image1, "user", "300x350");
				$_data["image"] = $image1;
			}
			$_data["years_of_exp"] = $this->input->post("years_of_exp");
			$_data["phone"] = $this->input->post("phone");
			$_data["age"] = $this->input->post("age");
			$_data["active"] = $this->input->post("active");
			$_data["id_countries"] = $this->input->post("id_countries");
			$_data["email"] = $this->input->post("email");
			$_data["nationality"] = $this->input->post("nationality");
			$_data["resident"] = $this->input->post("resident");
			$_data["profession"] = $this->input->post("profession");
			$_data["facebook"] = $this->input->post("facebook");
			$_data["twitter"] = $this->input->post("twitter");
			$_data["occupation"] = $this->input->post("occupation");
			$_data["id_gender"] = $this->input->post("gender");
			$_data["bio"] = $this->input->post("bio");
			$_data["linkedin"] = $this->input->post("linkedin");
			$_data["website"] = $this->input->post("website");
			$_data["title"] = $this->input->post("title");
			$_data["aboutme"] = $this->input->post("aboutme");
			$_data["address"] = $this->input->post("address");

			if ($this->input->post("id") != "") {
				$_data["updated_date"] = date("Y-m-d h:i:s");
				$this->db->where("id_user", $this->input->post("id"));
				$this->db->update($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was updated successfully");
			} else {
				$_data["created_date"] = date("Y-m-d h:i:s");
				$this->db->insert($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was inserted successfully");
			}
			redirect(base_url() . "back_office/user");
		}
	}

	public function delete_image($field, $image, $id)
	{
		if (file_exists("./uploads/user/" . $image)) {
			unlink("./uploads/user/" . $image);
		}
		$q = " SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'user')
AND name = '" . $field . "'";
		$query = $this->db->query($q);
		$res = $query->row_array();
		if ($res["thumb"] == 1) {
			$sumb_val1 = explode(",", $res["thumb_val"]);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists("./uploads/user/" . $value . "/" . $image)) {
					unlink("./uploads/user/" . $value . "/" . $image);
				}
			}
		}
		$_data[$field] = "";
		$this->db->where("id_user", $id);
		$this->db->update("user", $_data);
		redirect(base_url() . "back_office/user");
	}
}
