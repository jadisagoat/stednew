<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function index()
    {
        ////////// Start Header  
        $cond = array('admin_id' => 2);
        $data['generalinfo'] = $this->fct->getonerow("admin", $cond);
        $data['menuactive'] = "home";
        $data['category'] = $this->fct->getAll("category", "sort_order");
        $cond = array('top_header' => '1');
        $data['header_news'] = $this->fct->getAll_limit('news', "sort_order", "desc", "5", $cond);
        $cond = array('top_course' => '1');
        $data['header_course'] = $this->fct->getAll_limit('course', "sort_order", "desc", "5", $cond);
        $home_page = $this->fct->getAll('home_page', "sort_order");
        $data['home_page'] = [];
        foreach ($home_page as $row) {
            $data['home_page'][$row['id_home_page']] = $row;
        }
        $data['link_footer'] = $this->fct->getAll('link_footer', "sort_order");
        // Initialize an empty associative array
        $category_subcategory = array();
        $result = $this->fct->getAll("category", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_subcategory[$idCategory][] = $row;
        }
        $data['category_subcategory'] = $category_subcategory;

        // Added for 3rd level menu
        $data['group_category'] = $this->fct->getAll("group_category", "sort_order");
        $group_category_category = array();
        foreach ($data['category'] as $row) {
            $idSuperCategory = (int)$row['id_group_category'];
            $group_category_category[$idSuperCategory][] = $row;
        }
        $data['group_category_category'] = $group_category_category;
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;
        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header






        $data["content"] = "dashbaord";
        $this->load->view('template', $data);
    }
}
