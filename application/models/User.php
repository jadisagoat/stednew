<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model
{
	function __construct(){
	    parent:: __construct();
	}

	function get($id) {
		$get = $this->db->query("select id, token, name, email, countryCode, phone, country, image, categoryID, notificationStatus, notificationInterest from users where id = '$id'")->row();


		$get->categoryName = $this->db->get_where('categories', ["id"=>$get->categoryID])->row()->name;

		$get->image = base_url('images/'). $get->image;

		$get->notificationInterest = json_decode($get->notificationInterest);

		$get->notificationsCount = $this->db->get_where('notifications', ["userID"=>$id, "read"=>0])->num_rows();
		
		return $get;
	}
	
}

?>