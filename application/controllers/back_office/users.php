<?
class Users extends CI_Controller
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
		$data["title"] = "List Users";
		$data["content"] = "back_office/users";
		$data["info"] = $this->fct->getAll('user', 'sort_order');
		$this->load->view('back_office/template', $data);
	}

	public function add()
	{
		$data["title"] = "Add New User";
		$data["content"] = "back_office/add_users";
		$this->load->view('back_office/template', $data);
	}
	public function edit($id)
	{
		$data["title"] = "Edit User";
		$data["content"] = "back_office/add_users";
		$cond = array('id_user' => $id);
		$data["id"] = $id;
		$data["info"] = $this->fct->getonerecord('user', $cond);
		$this->load->view('back_office/template', $data);
	}

	public function priviledge($id)
	{
		$data["title"] = "Set User's Privileges";
		$data["content"] = "back_office/priviledge";
		$data["id"] = $id;
		$data["attr"] = $this->fct->getAll('content_type', 'sort_order');
		$this->load->view('back_office/template', $data);
	}

	public function submit()
	{
		$this->form_validation->set_rules('name', 'Full Name', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('username', 'User Name', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('address', 'address', 'trim');
		$this->form_validation->set_rules('phone', 'phone', 'trim');
		$this->form_validation->set_rules("image", "image", "trim");

		if ($this->form_validation->run() == FALSE) {
			$data["title"] = "Add / Edit New User";
			$data["content"] = "back_office/add_users";
			if ($this->input->post('id') != '') {
				$cond = array('id_user' => $this->input->post('id'));
				$data["id"] = $this->input->post('id');
				$data["info"] = $this->fct->getonerecord('user', $cond);
			}
			$this->load->view('back_office/template', $data);
		} else {

			$cond = array('username' => $this->input->post('username'), 'password' => $this->input->post('password'));
			$check_user = $this->fct->getonerow('user', $cond);
			if (count($check_user) > 0 && $this->input->post('id') == '') {
				$data["title"] = "Add / Edit New User";
				$data["content"] = "back_office/add_users";
				if ($this->input->post('id') != '') {
					$cond = array('id_user' => $this->input->post('id'));
					$data["id"] = $this->input->post('id');
					$data["info"] = $this->fct->getonerecord('user', $cond);
				}
				$this->session->set_userdata('error_message', 'This username and password are already exist .');
				$this->load->view('back_office/template', $data);
			} else {

				$_data = array(
					'name' => $this->input->post('name'),
					'username' => $this->input->post('username'),
					'password' => $this->input->post('password'),
					'email' => $this->input->post('email'),
					'phone' => $this->input->post('phone'),
					'address' => $this->input->post('address')
				);


				$image1 = '';

				if (!empty($_FILES["image"]["name"])) {
					if ($this->input->post("id") != "") {
						$cond_image = array("id_user" => $this->input->post("id"));
						$old_image = $this->fct->getonecell("user", "image", $cond_image);
						if (!empty($old_image) && file_exists('./uploads/user/' . $old_image)) {
							unlink("./uploads/user/" . $old_image);
							$sumb_val1 = explode(",", "45x45");
							foreach ($sumb_val1 as $key => $value) {
								if (file_exists("./uploads/user/" . $value . "/" . $old_image)) {
									unlink("./uploads/user/" . $value . "/" . $old_image);
								}
							}
						}
					}
					$image1 = $this->fct->uploadImage("image", "user");
					$this->fct->createthumb1($image1, "user", "45x45");
					$_data["image"] = $image1;
				}


				if ($this->input->post('id') != '') {
					$_data["updated_date"] = date("Y-m-d h:i:s");
					$this->db->where('id_user ', $this->input->post('id'));
					$this->db->update('user', $_data);
					$this->session->set_userdata('success_message', 'Information was updated successfully');
				} else {
					$_data["created_date"] = date("Y-m-d h:i:s");
					$this->db->insert('user', $_data);

					$this->session->set_userdata('success_message', 'Information was inserted successfully');
				}
				redirect(base_url() . 'back_office/users');
			}
		}
	}

	public function delete($id)
	{

		$_data = array(
			'deleted' => 1,
			'deleted_date' => date("Y-m-d h:i:s")
		);
		$this->db->where('id_user', $id);
		$this->db->update('user', $_data);
		$this->session->set_userdata('success_message', 'Information was deleted successfully');
		redirect(base_url() . 'back_office/users');
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
						$this->db->where('id_user', $cehcklist[$i]);
						$this->db->update('user', $_data);
					}
				}
			}
			$this->session->set_userdata('success_message', 'Informations were deleted successfully');
		}
		redirect(base_url() . 'back_office/users');
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
			$this->db->where('id_user', $sort[$i]);
			$this->db->update('user', $_data);
		}
	}


	public function submit_privi()
	{
		$id = $this->input->post('id');
		$cehcklist = $this->input->post('cehcklist');
		$content_type = $this->fct->getAll_1('content_type', 'sort_order');
		if (count($cehcklist) > 0) {
			for ($j = 0; $j < 11; $j++) {
				//if($cehcklist[$i] != ''){

				//$j= $cehcklist[$i];
				$section = $this->input->post('section' . $j);
				$read = $this->input->post('read' . $j);
				$write = $this->input->post('write' . $j);
				$delete = $this->input->post('delete' . $j);

				$_data = array(
					'read_p' => $read,
					'write_p' => $write,
					'delete_p' => $delete
				);
				$this->db->where("`id_user` = '" . (int) $id . "' AND `id_content` = '" . (int) $section . "'"); // Security: integers only
				$this->db->update('priviledge', $_data);
				//}
			}
			$this->session->set_userdata('success_message', 'Informations were updated successfully');
		}
		redirect(base_url() . 'back_office/users/priviledge/' . $id);
	}
}
