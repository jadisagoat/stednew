<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class users extends CI_Controller
{
    // Security: these lists contain every user's personal data - website admins only (id_init_role 1)
    private function _is_admin()
    {
        $login = $this->session->userdata('login');
        return !empty($login['idUser']) && $login['id_init_role'] == 1;
    }

    public function all_user()
    {
        if (!$this->_is_admin()) {
            header('Content-Type: application/json');
            echo json_encode(array('data' => null));
            exit();
        }
        $user = "SELECT 
                    user.id_user,
                    CONCAT('" . base_url() . "uploads/user/', user.image) AS image,
                    CONCAT('" . base_url() . "uploads/user/', user.signature) AS signature,
                    user.name,
                    user.email,
                    user.username,
                    user.phone,
                    role.title,
                CASE 
                WHEN user.active = 1 THEN 'Active'
                WHEN user.active = 0 THEN 'Inactive'
                END AS status
                    FROM 
                        user
                    LEFT JOIN 
                        role ON user.id_role = role.id_role
                    WHERE 
                        user.deleted = 0
                    GROUP BY 
                        user.id_user
                    ORDER BY 
                        user.id_role";
        $listofuser = $this->fct->search($user);
        $response['data'] = $listofuser;
        ////-------------------------------------limiting nb of records to only a page-------------------
        $totalRecords_num = count($listofuser);
        //-----------------------------------------------------------------------------------------------------
        if ($totalRecords_num > 0) {
        } else {
            $response['data'] = null;
        }
        // // Set appropriate headers
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function allemployeenotregistered()
    {
        if (!$this->_is_admin()) {
            header('Content-Type: application/json');
            echo json_encode(array('data' => null));
            exit();
        }
        $user = "
            SELECT 
      e.id_employee,
                     CONCAT('" . base_url() . "uploads/user/', u.image) AS image,
                    e.title,
                    e.email,
                    u.username,
                    u.phone,
                    e.job_position,
                   
    CASE 
        WHEN u.email IS NOT NULL THEN 'Yes'
        ELSE 'No'
    END AS InUserTable
FROM 
    employee e 
LEFT JOIN 
    user u 
ON 
  u.deleted=0 and e.email COLLATE utf8_unicode_ci = u.email COLLATE utf8_unicode_ci 
    where e.deleted=0    ";

        $listofuser = $this->fct->search($user);
        $response['data'] = $listofuser;
        ////-------------------------------------limiting nb of records to only a page-------------------
        $totalRecords_num = count($listofuser);
        //-----------------------------------------------------------------------------------------------------
        if ($totalRecords_num > 0) {
        } else {
            $response['data'] = null;
        }
        // // Set appropriate headers
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function course()
    {
        if (!$this->_is_admin()) {
            header('Content-Type: application/json');
            echo json_encode(array('data' => null));
            exit();
        }
        $course = "SELECT 
            role.title as role,
            course.id_course as ID,
            user.id_user ,
            CONCAT('" . base_url() . "uploads/course/', course.image) AS image,
            course.title,
            CASE 
                WHEN user_course.id_user IS NULL THEN 'Not Exist' 
                ELSE 'Exist' 
            END AS user_course_status
        FROM 
            user 
        LEFT JOIN 
            user_course ON user_course.id_user = user.id_user AND user_course.deleted = 0
        INNER JOIN
            role ON role.id_role = user_course.id_role
        LEFT JOIN 
            course ON user_course.id_course = course.id_course AND course.deleted = 0
        WHERE
            user_course.deleted=0 and
            role.deleted=0 and
            course.deleted=0 and
            user.deleted = 0
        ORDER BY 
            user.id_role";

        $listofuser = $this->fct->search($course);
        $response['data'] = $listofuser;
        ////-------------------------------------limiting nb of records to only a page-------------------
        $totalRecords_num = count($listofuser);
        //-----------------------------------------------------------------------------------------------------
        if ($totalRecords_num > 0) {
        } else {
            $response['data'] = null;
        }
        // // Set appropriate headers
        header('Content-Type: application/json');
        echo json_encode($response);
    }



    public function listusers($id_course = '')
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

        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        if ($userId['id_init_role'] != 1) {
            echo "<script>alert('You need to permission Admin!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution   
        }

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $data['page'] = '';
        $data["content"] = "userlist";
        $this->load->view('template', $data);
    }

    public function listemployeenotregistered($id_course = '')
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

        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        if ($userId['id_init_role'] != 1) {
            echo "<script>alert('You need to permission Admin!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution   
        }

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $data['page'] = '';
        $data["content"] = "listemployeenotregistered";
        $this->load->view('template', $data);
    }


    public function listofuser()
    {
        if (!$this->_is_admin()) {
            header('Content-Type: application/json');
            echo json_encode(array('data' => null));
            exit();
        }
        // List of Users
        $qrys = "SELECT 
                    role.title as role,
                    user.id_user,
                    CONCAT('" . base_url() . "uploads/user/', user.image) AS image,
                    course.title,
                    user.name,
                    user.email,
                    user.username,
                    user.phone,
                    user.id_role as id_init_role,
                    -- count(course.id_course) as countcourse,
                    CASE 
                        WHEN user_course.id_user IS NULL THEN 'Not Exist' 
                        ELSE 'Exist' 
                    END AS user_course_status
                FROM 
                    user 
                LEFT JOIN 
                    user_course ON user_course.id_user = user.id_user AND user_course.deleted = 0
                INNER JOIN
                    role ON role.id_role = user.id_role
                LEFT JOIN 
                    course ON user_course.id_course = course.id_course AND course.deleted = 0
                WHERE
                    user_course.deleted=0 and
                    role.deleted=0 and
                    course.deleted=0 and
                    user.deleted = 0
                --     GROUP BY 
                --     user.id_user
                ORDER BY 
                    user.id_role";
        $listofuser = $this->fct->search($qrys);
        // End List of Users
        $response['data'] = $listofuser;
        ////-------------------------------------limiting nb of records to only a page-------------------
        $totalRecords_num = count($response['data']);
        //-----------------------------------------------------------------------------------------------------
        if ($totalRecords_num > 0) {
            // while ($articles = mysqli_fetch_array($result)) {
            //     $response['data'][] = $articles;
            // }
        } else {
            $response['data'] = null;
        }

        // // Set appropriate headers
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function deleteuser($id)
    {
        if (!$this->session->userdata('login')) {
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit;
        }
        // Security: only an admin can delete users
        if (!$this->_is_admin()) {
            echo '0';
            exit;
        }
        $id = (int) $id;

        $_data = array(
            "deleted" => 1,
            "deleted_date" => date("Y-m-d h:i:s")
        );
        $this->db->where("id_user", $id);
        $this->db->update('user', $_data);
        echo '1';
        // $this->session->set_userdata("success_message","Information was deleted successfully");
        // redirect(base_url()."exam");
    }



    // public function userAddEdit($id_course='')
    // {
    //     ////////// Start Header  
    //     $cond=array('admin_id'=>2);
    //     $data['generalinfo'] = $this->fct->getonerow("admin",$cond);
    //    //////////////////////////

    //     if(!$this->session->userdata('login')) {
    //             echo "<script>alert('You need to log in!');</script>";
    //             echo '<script>window.location.href="' . base_url() . '";</script>';
    //             exit; // Make sure to exit to prevent further execution
    //     }

    //     $userId = $this->session->userdata('login');
    //     $id_user=$userId['idUser'];
    //     $data['login']= $userId;

    //     if($userId['id_init_role']!=1){
    //         echo "<script>alert('You need to permission Admin!');</script>";
    //         echo '<script>window.location.href="' . base_url() . '";</script>';
    //         exit; // Make sure to exit to prevent further execution   
    //      }

    //     $linkpage['redirect_url']= $_SERVER['REQUEST_URI'];
    //     $this->session->set_userdata('redirect_url', $linkpage);
    //     //////////  End Header

    //     $this->load->view('userAddEdit',$data);
    // }

    function upload()
    {

        $userId = $this->session->userdata('login');
        $id_user = (int) $userId['idUser'];
        // Security: login required (no anonymous uploads)
        if (!$id_user)
            exit();
        $id_profile = 0;
        if (isset($_REQUEST['id_profile_image']) && $_REQUEST['id_profile_image'])
            $id_profile = (int) $_REQUEST['id_profile_image'];

        // permission admin
        $qrys = "SELECT 
            role.title as role,
            user.id_user,
            user.name,
            user.email,
            user.username,
            user.phone
        FROM 
            user 
        INNER JOIN
            role ON role.id_role = user.id_role
        WHERE
            user.deleted = 0 and user.id_role='1' and user.id_user= $id_user
        GROUP BY 
            user.id_user
        ORDER BY 
            user.id_role";
        $permisssion_admin_user = $this->fct->search($qrys);

        if ($id_user && $permisssion_admin_user['0']['role'] == "Admin")
            if ($id_profile)
                $id_user =  $id_profile;
        /////////////////////////////

        $image1 = $this->fct->uploadImage("photoimg", "user");
        $this->fct->createthumb1($image1, "user", "300x350");
        $_data["image"] = $image1;
        $this->db->where("id_user", $id_user);
        $this->db->update("user", $_data);

        echo '<img src="' . base_url() . 'uploads/user/' . $image1 . '" alt="StedLb" class="preview">';
    }

    function upload_signature()
    {

        $userId = $this->session->userdata('login');
        $id_user = (int) $userId['idUser'];
        // Security: login required (no anonymous uploads)
        if (!$id_user)
            exit();
        $id_profile = 0;
        if (isset($_REQUEST['id_profile_image']) && $_REQUEST['id_profile_image'])
            $id_profile = (int) $_REQUEST['id_profile_image'];

        // permission admin
        $qrys = "SELECT 
            role.title as role,
            user.id_user,
            user.name,
            user.email,
            user.username,
            user.phone,
            CONCAT('" . base_url() . "uploads/user/', user.signature) AS signature

        FROM 
            user 
        INNER JOIN
            role ON role.id_role = user.id_role
        WHERE
            user.deleted = 0 and user.id_role='1' and user.id_user= $id_user
        GROUP BY 
            user.id_user
        ORDER BY 
            user.id_role";
        $permisssion_admin_user = $this->fct->search($qrys);

        if ($id_user && $permisssion_admin_user['0']['role'] == "Admin")
            if ($id_profile)
                $id_user =  $id_profile;
        /////////////////////////////

        $image1 = $this->fct->uploadImage("photoimg_signature", "user");
        $this->fct->createthumb1($image1, "user", "360x60");
        $_data["signature"] = $image1;
        $this->db->where("id_user", $id_user);
        $this->db->update("user", $_data);

        echo '<img src="' . base_url() . 'uploads/user/' . $image1 . '" alt="StedLb" class="preview">';
    }
}
