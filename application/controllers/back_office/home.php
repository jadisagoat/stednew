<?
class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->headers='back_office/includes/header';
		//$this->footer='back_office/includes/footer';

	}



	public function index()
	{
		$this->load->view('back_office/login');
		/*	$data=array(
	'pg_title'=>'Home Page',
	'title'=>'DashBoard',
	'header' => $this->headers,
	'content' =>'back_office/dashboard',
	'footer'=>$this->footer );
	$this->load->view('back_office/template',$data);*/
	}

	public function login_validate()
	{
		// field names and messages in the admin language (bo_lang cookie; the login page has no session yet)
		$this->form_validation->set_message('required', bo_t('login_err_required'));
		$this->form_validation->set_message('min_length', bo_t('login_err_min'));
		$this->form_validation->set_message('max_length', bo_t('login_err_max'));
		$this->form_validation->set_rules('username', bo_t('login_username'), 'trim|required|min_length[5]|max_length[12]|xss_clean');
		$this->form_validation->set_rules('pass', bo_t('login_password'), 'trim|required|min_length[5]|max_length[12]|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('back_office/login');
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('pass');
			$this->db->where('user_name', $username);
			$this->db->where('password', $password);
			$query = $this->db->get('admin');
			$admin = $query->row_array();

			if ($query->num_rows() > 0) {
				$this->session->set_userdata('id_role', $admin["id_role"]);
				$this->session->set_userdata('user_id', $admin["admin_id"]);
				$this->session->set_userdata('user_name', $admin["admin_name"]);
				$this->session->set_userdata('level', $admin["level"]);
				$this->session->set_userdata('login_date', date("d M Y - h:i:s A"));
				redirect(base_url() . 'back_office/home/dashboard');
			} else {
				// check in user table 
				$this->db->where('username', $username);
				$this->db->where('password', $password);
				$this->db->where('deleted', 0);
				$this->db->where('active >', 0); // Security: same rule as the website login (inactive accounts cannot sign in)
				$query = $this->db->get('user');
				$user = $query->row_array();
				if ($query->num_rows() > 0) {
					$this->session->set_userdata('id_role', $user["id_role"]);
					$this->session->set_userdata('user_id', $user["id_user"]);
					$this->session->set_userdata('user_name', $user["username"]);
					$this->session->set_userdata('level', 0);
					$this->session->set_userdata('login_date', date("d M Y - h:i:s A"));
					redirect(base_url() . 'back_office/home/dashboard');
				} else {
					$this->session->set_userdata('login_error', 'check your username or password .');
					$this->load->view('back_office/login');
				}
			}
		}
	}
	public function dashboard()
	{
		$data["title"] = "STED Admin Section";
		$data["content"] = "back_office/dashboard";
		$data["date_calendar"] = $this->fct->module(1);
		$data["vis_links"] = $this->fct->module(2);

		$cond = array('admin_id' => 2);
		$this->db->select('image,website_title');
		$data["title_site"] = $this->fct->getonerow('admin', $cond);

		//$data["system_message"]=$this->fct->module(3);
		$data["todo"] = $this->fct->module(4);
		$data["todo_list"] = $this->fct->getto_do_list();
		$data["todo_list_comp"] = $this->fct->getto_do_list_completed();
		//$data["messages"]=$this->fct->getAll_limit('contactform','created_date','DESC',2);
		$data["content_type"] = $this->fct->getAll('content_type', 'sort_order');

		$data["messages"] = $this->fct->getAll_orderdate('contactform');
		$data["new_message"] = $this->fct->get_unreaded_emails();

		$data["users"] = $this->fct->getAll_orderdate('user');
		$data["employee"] = $this->fct->getAll_orderdate('employee');

		$data["courses"] = $this->fct->getAll_orderdate('course');
		$data["content_1"] = $this->fct->getAll_orderdate('content');
		//////////////////////////////
		$qrys = "SELECT 
		-- course.id_course,
		course.title AS coursetitle,
		-- user.id_user,
		user.name,
		-- user.email,
		-- user.username,
		-- user_course_rating.feedback,
		-- user_course_rating.rating,
		-- IFNULL(ROUND(avg_course_rating.avg_rating, 2), 0) AS avg_course_rating,
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
	 
		ORDER BY `user_course_rating`.`id_user_course_rating` DESC  limit 9 ;
	
	";
		$usercourse = $this->fct->search($qrys);
		$data['totalCount'] = count($usercourse);
		$data['data_content'] = $usercourse;

		///////////////////////// history exam		
		// exam_history.id_user ,
		// exam.id_exam,
		//     exam.title,
		//   exam_history.score,
		//  exam_history.number_attempts,

		$query = "select  
		user.name ,
		  
		  course.title course,

		grade.title grade,
	
		    DATE_FORMAT(exam_history.created_date, '%Y/%d/%m %H:%i') AS created_date

        FROM exam_history,course,
		grade,
		exam,
		user

        where exam_history.id_course = course.id_course
        and exam_history.id_exam = exam.id_exam
        and exam_history.id_grade=grade.id_grade
        and exam_history.id_user = user.id_user
        and exam_history.deleted =0
        and exam.deleted = 0
        and grade.deleted = 0
        and course.deleted = 0
        and user.deleted = 0   
		ORDER BY `exam_history`.`id_exam_history` DESC limit 5";
		$examhist = $this->fct->search($query);
		$data['data_exam'] = $examhist;
		/////////////////////////
		$query = "SELECT
    AVG(CASE 
            WHEN score >= 70 AND number_attempts = 1 THEN score 
            ELSE NULL 
        END) AS average_grade_pass_first_attempt,

    COUNT(CASE 
            WHEN score < 70 THEN 1 
            ELSE NULL 
        END) AS total_failures,

    COUNT(CASE 
            WHEN score >= 70 AND number_attempts = 1 THEN 1 
            ELSE NULL 
        END) AS total_pass_first_attempt,

    COUNT(CASE 
            WHEN score >= 70 AND number_attempts > 1 THEN 1 
            ELSE NULL 
        END) AS total_pass_subsequent_attempts,

    COUNT(*) AS total_exams,

    (COUNT(CASE WHEN score < 70 THEN 1 ELSE NULL END) * 100.0 / COUNT(*)) AS percentage_failures,
    (COUNT(CASE WHEN score >= 70 AND number_attempts = 1 THEN 1 ELSE NULL END) * 100.0 / COUNT(*)) AS percentage_pass_first_attempt,
    (COUNT(CASE WHEN score >= 70 AND number_attempts > 1 THEN 1 ELSE NULL END) * 100.0 / COUNT(*)) AS percentage_pass_subsequent_attempts

FROM
    `exam_history`
WHERE
    deleted = 0;
";
		$examhist = $this->fct->search($query);
		$data['exam_history'] = $examhist;
		/////////////////////////

		$query = "SELECT
    (SELECT COUNT(*) FROM employee WHERE deleted = 0) AS total_employees,
    COUNT(DISTINCT user.id_user) AS registered_users,
    ROUND((COUNT(DISTINCT user.email) * 100.0 / (SELECT COUNT(*) FROM employee WHERE deleted = 0)), 2) AS percentage_registered
FROM
    user
JOIN 
    employee ON user.email = employee.email
WHERE 
    user.deleted = 0 and employee.deleted = 0 ";
		$user_employee = $this->fct->search($query);
		$data['user_employee'] = $user_employee;
		////////////////////

		// $querys = "select * from user ";
		// $result = $this->fct->search_count_cond($querys);

		// echo "<pre>";
		// print_r(count($data["users"]));
		// exit();

		// echo "<pre>";
		// print_r(count($data["users"]));
		// exit();

		$this->load->view('back_office/template', $data);
	}

	public function complete_todo($id)
	{
		$cond = array('id_do' => $id);
		$do_it = $this->fct->getonerow('do_it', $cond);
		echo '<a class="cur" onclick="completed(' . $id . ');">';
		if ($do_it["completed"] == 1) {
			$action = 0;
			echo "<span class='label label-important'>Pending</span>";
		} else {
			echo "<span class='label label-success'>Completed</span>";
			$action = 1;
		}
		echo '</a>';
		$_data["completed"] = $action;
		$this->db->where('id_do', $id);
		$this->db->update('do_it', $_data);
	}

	public function do_it_pop_up($id)
	{
		$cond = array('id_do' => $id);
		$do_it = $this->fct->getonerow('do_it', $cond);
?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel"><?= $do_it["title"]; ?>&nbsp;&nbsp;<small><?= $do_it["Deadline"]; ?></small></h3>
		</div>
		<div class="modal-body" id="modal-body">
			<p><?= $do_it["description"]; ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
<?
		//echo '<div style="height:auto;"><h2 style="font-size:160%; font-weight:bold; margin:10px 0;">'.$do_it["title"].'&nbsp;&nbsp;<span style="color:#AE9A62">Deadline:'.$do_it["Deadline"].'</span> </h2>
		//<p>'.$do_it["description"].'</p></div>';	
	}

	public function message_popup($id)
	{
		$cond = array('id' => $id);
		$do_it = $this->fct->getonerow('contactform', $cond);
		echo '<div style="height:auto;"><h2 style="font-size:160%; font-weight:bold; margin:10px 0;">' . $do_it["subject"] . '&nbsp;&nbsp;<span style="color:#AE9A62">Date:' . $do_it["created_date"] . '</span> </h2>
<p>' . $do_it["message"] . '</p></div>';
	}

	public function logout()
	{
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_name');
		redirect(base_url() . 'back_office/home');
	}

	/** Switch the admin language (en|ar): cookie + session, then back to the referer. */
	public function lang($code = "")
	{
		$code = ($code === "ar") ? "ar" : "en";
		setcookie("bo_lang", $code, time() + 31536000, "/");
		$this->session->set_userdata("bo_lang", $code);
		$ref = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";
		if ($ref !== "" && strpos($ref, base_url()) === 0 && strpos($ref, "/home/lang/") === false) {
			redirect($ref);
		}
		redirect(base_url() . "back_office/home/dashboard");
	}
}
