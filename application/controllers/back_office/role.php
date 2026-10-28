<?
class Role extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->table = "role";
	}


	public function index($order = "")
	{
		if ($order == "")
			$order = "sort_order";
		$data["title"] = "List role";
		$data["content"] = "back_office/role/list";
		$data["info"] = $this->fct->getAll($this->table, $order);
		$this->load->view("back_office/template", $data);
	}

	public function add()
	{
		$data["title"] = "Add role";
		$data["content"] = "back_office/role/add";
		$this->load->view("back_office/template", $data);
	}

	public function edit($id)
	{
		$data["title"] = "Edit role";
		$data["content"] = "back_office/role/add";
		$cond = array("id_role" => $id);
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
		$this->db->where("id_role", $id);
		$this->db->update($this->table, $_data);
		$this->session->set_userdata("success_message", "Information was deleted successfully");
		redirect(base_url() . "back_office/role");
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
						$this->db->where("id_role", $cehcklist[$i]);
						$this->db->update($this->table, $_data);
					}
				}
			}
			$this->session->set_userdata("success_message", "Informations were deleted successfully");
		}
		redirect(base_url() . "back_office/role");
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
			$this->db->where("id_role", $sort[$i]);
			$this->db->update($this->table, $_data);
		}
	}

	public function submit()
	{
		$data["title"] = "Add / Edit role";
		$this->form_validation->set_rules("title", "title", "trim|required");
		$this->form_validation->set_rules("active", "active", "trim");
		if ($this->form_validation->run() == FALSE) {
			if ($this->input->post("id") != "") {
				$cond = array("id_role" => $this->input->post("id"));
				$data["id"] = $this->input->post("id");
				$data["info"] = $this->fct->getonerecord("role", $cond);
			}
			$data["content"] = "back_office/role/add";
			$this->load->view("back_office/template", $data);
		} else {
			$_data["title"] = $this->input->post("title");
			$_data["active"] = $this->input->post("active");

			if ($this->input->post("id") != "") {
				$_data["updated_date"] = date("Y-m-d h:i:s");
				$this->db->where("id_role", $this->input->post("id"));
				$this->db->update($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was updated successfully");
			} else {
				$_data["created_date"] = date("Y-m-d h:i:s");
				$this->db->insert($this->table, $_data);
				$this->session->set_userdata("success_message", "Information was inserted successfully");
			}
			redirect(base_url() . "back_office/role");
		}
	}

	public function delete_image($field, $image, $id)
	{
		if (file_exists("./uploads/role/" . $image)) {
			unlink("./uploads/role/" . $image);
		}
		$q = " SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'role')
AND name = '" . $field . "'";
		$query = $this->db->query($q);
		$res = $query->row_array();
		if ($res["thumb"] == 1) {
			$sumb_val1 = explode(",", $res["thumb_val"]);
			foreach ($sumb_val1 as $key => $value) {
				if (file_exists("./uploads/role/" . $value . "/" . $image)) {
					unlink("./uploads/role/" . $value . "/" . $image);
				}
			}
		}
		$_data[$field] = "";
		$this->db->where("id_role", $id);
		$this->db->update("role", $_data);
		redirect(base_url() . "back_office/role");
	}

	public function priviledge($id)
	{
		$data["title"] = "Set Role's Privileges";
		$data["content"] = "back_office/role/priviledge";
		$data["id"] = $id;
		$data["attr"] = $this->fct->getAll('content_type', 'sort_order');
		$this->load->view('back_office/template', $data);
	}

	public function submit_privi()
	{
		$id = $this->input->post('id');
		$cehcklist = $this->input->post('cehcklist');
		// echo "<pre>";
		// print_r($_REQUEST);
		// exit();
		// $qry = "DELETE FROM priviledge WHERE `id_role` =" . $id;
		// $query = $this->db->query($qry);

		$content_type = $this->fct->getAll('content_type', 'sort_order');

		// if (count($cehcklist) > 0) {
		//for ($j = 0; $j < count($cehcklist); $j++) {

		foreach ($content_type as $val) {
			//if ($cehcklist[$j] != '') {
			if ($val["position"] != 0) {
				$j = $val['id_content'];
				$section = $this->input->post('section' . $j) ?? 0;
				$read = $this->input->post('read' . $j) ?? 0;
				$write = $this->input->post('write' . $j) ?? 0;
				$delete = $this->input->post('delete' . $j) ?? 0;
				//}
				// echo "<br>";
				// echo $j . "====" . $section;
				// echo "<br>";
				$_data = array(
					'read_p' => $read,
					'write_p' => $write,
					'delete_p' => $delete
				);
				$this->db->where("`id_role` = '" . (int) $id . "' AND `id_content` = '" . (int) $j . "'"); // Security: integers only
				$this->db->update('priviledge', $_data);
				// $this->db->insert('priviledge', $_data);


				// echo "<br>";
				// echo  $this->db->last_query();
				// echo "<br>";
				// exit();
			}  //position
		}
		// }
		// exit();

		$this->session->set_userdata('success_message', 'Informations were updated successfully');
		//}
		// exit();
		redirect(base_url() . 'back_office/role/priviledge/' . $id);
	}
}
