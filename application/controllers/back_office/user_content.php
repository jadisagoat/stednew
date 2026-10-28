<?
class User_content extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "user_content";
	}

	/**
	 * Progress report shown by the list (one row per enrolment: user x course, with the user's rating / feedback,
	 * the course average rating and the % of the course contents the user opened).
	 * row_key = position in the report: (user, course) pairs are not unique when a user is enrolled twice.
	 */
	private function _progress_rows()
	{
		$qrys = "SELECT 
course.id_course,
course.title AS coursetitle,
user.id_user,
user.name,
user.email,
user.username,
user_course_rating.feedback,
user_course_rating.rating,
IFNULL(ROUND(avg_course_rating.avg_rating, 2), 0) AS avg_course_rating,
IFNULL(completion_data.completion_percentage, 0) AS completion_percentage 
 
FROM 
course
INNER JOIN 
user_course ON user_course.id_course = course.id_course AND user_course.deleted = 0
INNER JOIN 
user ON user.id_user = user_course.id_user AND user.deleted = 0
LEFT JOIN (
SELECT 
	user_content.id_user,
	content.id_course,
	ROUND((COUNT(DISTINCT user_content.id_content) / content_counts.total_contents) * 100, 2) AS completion_percentage
FROM 
	user_content
INNER JOIN 
	content ON content.id_content = user_content.id_content AND content.deleted = 0
INNER JOIN (
	SELECT 
		id_course,
		COUNT(id_content) AS total_contents
	FROM 
		content
	WHERE 
		content.deleted = 0
	GROUP BY 
		id_course
) AS content_counts ON content.id_course = content_counts.id_course
WHERE 
	user_content.deleted = 0
GROUP BY 
	user_content.id_user, content.id_course
) AS completion_data ON course.id_course = completion_data.id_course AND user.id_user = completion_data.id_user
LEFT JOIN (
SELECT
	id_course,
	AVG(rating) AS avg_rating
FROM
	user_course_rating
WHERE
	deleted = 0 
GROUP BY
	id_course
) AS avg_course_rating ON course.id_course = avg_course_rating.id_course
LEFT JOIN 
user_course_rating ON user.id_user = user_course_rating.id_user AND course.id_course = user_course_rating.id_course AND user_course_rating.deleted = 0
WHERE 
course.deleted = 0
ORDER BY 
user.id_user, course.id_course  ;

";
		$rows = $this->fct->search($qrys);
		foreach ($rows as $i => $row) {
			$rows[$i]["row_key"] = $i + 1;
		}
		return $rows;
	}

	public function all_data($order = "")
	{
		// 	if ($order == "") {
		// 		$order = "sort_order";
		// 	}
		// 	$listofuser = $this->fct->getAll($this->table, $order);
		// 	$arrays = [];

		// 	foreach ($listofuser as $row) { 
		// $row["PIDid_user_content"] =
		// 	'<input type="checkbox" name="cehcklist[]" value="'. $row["id_user_content"].'" />';  if($row["id_user"] != 0){
		// 		                     $cond=array("id_user" => $row["id_user"]);
		// 						   $row["user"] =   $this->fct->getonecell("user","title",$cond);
		// 						    } 
		// 						   else {
		// 		  $row["user"] = '<small class="blue">--</small>';
		// 	  }   if($row["id_content"] != 0){
		// 		                     $cond=array("id_content" => $row["id_content"]);
		// 						   $row["content"] =   $this->fct->getonecell("content","title",$cond);
		// 						    } 
		// 						   else {
		// 		  $row["content"] = '<small class="blue">--</small>';
		// 	  }   if($row["id_course"] != 0){
		// 		                     $cond=array("id_course" => $row["id_course"]);
		// 						   $row["course"] =   $this->fct->getonecell("course","title",$cond);
		// 						    } 
		// 						   else {
		// 		  $row["course"] = '<small class="blue">--</small>';
		// 	  } 
		//  $arrays[] = $row;
		// 	}

		$usercourse = $this->_progress_rows();
		$data['totalCount'] = count($usercourse);
		$data['data_content'] = $usercourse;


		$response["data"] = $usercourse;
		////-------------------------------------limiting nb of records to only a page-------------------
		$totalRecords_num = $data['totalCount'];
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
		$data["title"] = "List user content";
		$data["content"] = "back_office/user_content/list";
		// the list is the progress report (same rows as all_data), not the raw user_content table
		$data["info"] = $this->_progress_rows();

		$this->load->view("back_office/template", $data);
	}

	public function add()
	{
		$data["title"] = "Add user content";
		$data["content"] = "back_office/user_content/add";
		$this->load->view("back_office/template", $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit user content";
		$data["content"] = "back_office/user_content/add";
		$cond = array("id_user_content" => $id);
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
		$this->db->where("id_user_content", $id);
		$this->db->update($this->table, $_data);
		$this->session->set_userdata("success_message", "Information was deleted successfully");

		redirect(base_url() . "back_office/user_content");
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
						$this->db->where("id_user_content", $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata("success_message", "Informations were deleted successfully");
		}
		redirect(base_url() . "back_office/user_content");
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
			$this->db->where("id_user_content", $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}

	public function submit()
	{
		$data["title"] = "Add / Edit user content";
		$this->form_validation->set_rules("user", "user", "trim");
		$this->form_validation->set_rules("content", "content", "trim|required");
		$this->form_validation->set_rules("course", "course", "trim");
		if ($this->form_validation->run() == FALSE) {
			if ($this->input->post("id") != "") {
				$cond = array("id_user_content" => $this->input->post("id"));
				$data["id"] = $this->input->post("id");
				$data["info"] = $this->fct->getonerecord("user_content", $cond);
			}
			$data["content"] = "back_office/user_content/add";
			$this->load->view("back_office/template", $data);
		} else {
			$_data["id_user"] = $this->input->post("user");
			$_data["id_content"] = $this->input->post("content");
			$_data["id_course"] = $this->input->post("course");

			if ($this->input->post("id") != "") {
				$_data["updated_date"] = date("Y-m-d h:i:s");
				$this->db->where("id_user_content", $this->input->post("id"));
				$this->db->update($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was updated successfully");
			} else {
				$_data["created_date"] = date("Y-m-d h:i:s");
				$this->db->insert($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was inserted successfully");
			}
			redirect(base_url() . "back_office/user_content");
		}
	}

	public function delete_image($field, $image, $id)
	{
		if (file_exists("./uploads/user_content/" . $image)) {
			unlink("./uploads/user_content/" . $image);
		}
		$q = " SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'user content')
AND name = '" . $field . "'";
		$query = $this->db->query($q);
		$res = $query->row_array();
		if ($res["thumb"] == 1) {
			$sumb_val1 = explode(",", $res["thumb_val"]);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists("./uploads/user_content/" . $value . "/" . $image)) {
					unlink("./uploads/user_content/" . $value . "/" . $image);
				}
			}
		}
		$_data[$field] = "";
		$this->db->where("id_user_content", $id);
		$this->db->update("user_content", $_data);
		redirect(base_url() . "back_office/user_content");
	}
}
