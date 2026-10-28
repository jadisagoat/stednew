<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class course extends CI_Controller
{
    // Security: staff = admins, lecturers and users who teach a course (exam management, reports with users' data)
    private function _is_staff()
    {
        $login = $this->session->userdata('login');
        if (empty($login['idUser']))
            return false;
        if ($login['id_init_role'] == 1 || $login['id_init_role'] == 2)
            return true;
        $this->db->where('id_user', (int) $login['idUser']);
        $this->db->where_in('id_role', array(1, 2));
        $this->db->where('deleted', 0);
        return $this->db->count_all_results('user_course') > 0;
    }

    public function saveQuestions()
    {
        if (!$this->_is_staff()) {
            echo 0;
            exit();
        }
        $this->load->database(); // Make sure to load the database library if not autoloaded  
        // Assuming id_exam is passed as part of the request
        $id_exam = $this->input->post('id_exam');

        if ($id_exam) {
            // Soft delete old questions related to the id_exam
            $_data = array("deleted" => 1, "deleted_date" => date("Y-m-d h:i:s"));
            $this->db->where("id_exam", $id_exam);
            $this->db->update('question', $_data); // Assuming your table is named 'question'

            // Since you're soft deleting questions, related answers will also need to be soft deleted.
            // This assumes each question's 'id' is properly referenced as 'id_question' in 'answer' table
            $questions = $this->db->select('id_question')->from('question')->where('id_exam', $id_exam)->get()->result_array();
            foreach ($questions as $question) {
                $_data = array("deleted" => 1, "deleted_date" => date("Y-m-d h:i:s"));
                $this->db->where("id_question", $question['id_question']);
                $this->db->update('answer', $_data); // Assuming your table is named 'answer'
            }
        }

        // Process new questions and answers
        $questions = $this->input->post('question');
        $answers = $this->input->post('answer'); // Assuming this is structured appropriately
        $istrue = $this->input->post('istrue'); // Adjust based on your actual form structure

        if (!empty($questions)) {
            foreach ($questions as $index => $question) {
                $questionData = [
                    'created_date' => date("Y-m-d h:i:s"),
                    'title' => $question,
                    'id_exam' => $id_exam,
                ];
                $this->db->insert('question', $questionData);
                $questionId = $this->db->insert_id();

                if (isset($answers[$index])) {
                    foreach ($answers[$index] as $key => $answer) {
                        $answerData = [
                            'created_date' => date("Y-m-d h:i:s"),
                            'id_question' => $questionId,
                            'title' => $answer,
                            'true' => isset($istrue[$index][$key]) ? 1 : 0, // Handling 'true' flag
                        ];
                        $this->db->insert('answer', $answerData);

                        // echo $this->db->last_query();
                        // echo "<pre>";
                        // print_r( $istrue);

                    }
                }
            }
        }
        echo 1;
        // Optionally, return success or handle errors
    }

    public function deleteexam($id)
    {
        if (!$this->session->userdata('login') || !$this->_is_staff()) {
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit;
        }
        $id = (int) $id;

        $_data = array(
            "deleted" => 1,
            "deleted_date" => date("Y-m-d h:i:s")
        );
        $this->db->where("id_exam", $id);
        $this->db->update('exam', $_data);
        echo '1';
        // $this->session->set_userdata("success_message","Information was deleted successfully");
        // redirect(base_url()."exam");
    }

    public function editexam($id_exam = '', $addincourse = '')
    {
        // Security: the edit form shows the questions with their correct answers
        if (!$this->_is_staff()) {
            redirect(base_url());
            exit();
        }
        $id_exam = (int) $id_exam;
        //////////////////// Start Header  
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
        $category_course = array();
        $result = $this->fct->getAll("course", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

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
            exit;
        }
        $userId = $this->session->userdata('login');

        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        ////////////////////  End Header 
        $data['info']["start_date"] = '';
        $data['info']["title"] = '';
        $data['info']["close_date"] = '';
        $data['info']["time"] = '';
        $data['info']["description"] = '';
        $data['info']["number_of_questions"] = '';

        if ($id_exam && $addincourse == '') {
            $cond = array('id_exam' => $id_exam);
            $data['info'] = $this->fct->getonerow("exam", $cond);
            $id_course = $data['info']['id_course'];
            $cond = array('id_course' => $id_course);
            $data['infocourse'] = $this->fct->getonerow("course", $cond);

            $conda = array('id_course' => $id_course, 'id_user' => $id_user);
        } else { // new exam 
            $conda = array('id_user' => $id_user);

            if ($id_exam && $addincourse == "addincourse") {
                $id_course = $id_exam;
                $cond = array('id_course' => $id_course);
                $data['infocourse'] = $this->fct->getonerow("course", $cond);

                $cond = array('id_course' => $id_course);
                $one_exam_only = $this->fct->getonerow("exam", $cond);

                if (isset($one_exam_only['id_exam']) && $one_exam_only['id_exam'])
                    redirect(base_url() . "editexam/" . $one_exam_only['id_exam']);

                /// $addincourse


            }
        }
        // $usercourse = $this->fct->getAll_cond('user_course',"sort_order",$conda);
        // $data['userenrollement'] =0;
        // if(count($usercourse)){
        //     foreach($usercourse as $row){
        //         if($row['id_role']){
        //             $data['userenrollement'] = $row['id_role']; 
        //         }else{
        //             // $data['userenrollement']= '0';
        //             // echo "<script>alert('You need to log in!');</script>";
        //             // echo '<script>window.location.href="' . base_url() . '";</script>';
        //             // exit; 
        //         }
        //     }
        // }else {
        //     // echo "<script>alert('You need to log in!');</script>";
        //     // echo '<script>window.location.href="' . base_url() . '";</script>';
        //     // exit; 
        // }
        ///////////////////
        if ($id_course)
            $conda = " AND course.id_course = " . $id_course . " AND user.id_user = " . $id_user;
        else
            $conda = " AND user.id_user = " . $id_user;

        if ($id_exam) {
            $qrys = "SELECT 
 course.id_course,
 course.title
FROM 
course 
INNER JOIN 
user_course ON user_course.id_course = course.id_course AND user_course.deleted = 0 AND user_course.id_role = 2 
INNER JOIN 
user ON user_course.id_user = user.id_user AND user.deleted = 0

LEFT JOIN
exam ON course.id_course = exam.id_course
  WHERE
( exam.id_course IS NULL OR exam.id_exam = $id_exam)-- Filter out courses that have corresponding entries in the  
and

 course.deleted = 0
" . $conda . " 
GROUP BY 
course.id_course";
        } else {
            $qrys = "SELECT 
 course.id_course,
 course.title
FROM 
course 
INNER JOIN 
user_course ON user_course.id_course = course.id_course AND user_course.deleted = 0 AND user_course.id_role = 2 
INNER JOIN 
user ON user_course.id_user = user.id_user AND user.deleted = 0

LEFT JOIN
exam ON course.id_course = exam.id_course
  WHERE
exam.id_course IS NULL -- Filter out courses that have corresponding entries in the  
and

 course.deleted = 0
" . $conda . " 
GROUP BY 
course.id_course";
        }

        $data["usercourse"] = $this->fct->search($qrys);
        ////////////////////////////////////////////////////////////////////


        if ($id_exam && $addincourse == '') {
            // Retrieve questions
            $data["question"] = $this->db->select('*')
                ->from('question')
                ->where('deleted', 0)
                ->where('id_exam', $id_exam)
                ->get()
                ->result_array();
            // echo "<pre>";
            // echo $this->db->last_query();
            // // print_r($data["question"]);
            // exit();

            // Extract question IDs from the $data["questions"] array
            $question_ids = array_column($data["question"], 'id_question');

            // Initialize the $data["answers"] array
            $data["answers"] = array();
            // Retrieve answers for each question ID
            foreach ($question_ids as $question_id) {
                $answers = $this->db->select('*')
                    ->from('answer')
                    ->where('deleted', 0)
                    ->where('id_question', $question_id)
                    ->get()
                    ->result_array();
                // Group answers by question ID
                $data["answer"][$question_id] = $answers;
            }
        }
        ////////////////////////////////////////////////////////////////////////  
        $data["content"] = "examadd";
        $this->load->view('template', $data);
    }


    public function listuserscontent()
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
        $category_course = array();
        $result = $this->fct->getAll("course", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

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

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        ///////////////////////////////////////////////////////////////////////////
        $data['page'] = '';
        $data["content"] = "listuserscontent";
        $this->load->view('template', $data);
    }

    public function userscontent($id_course = '')
    {
        // if (!$this->session->userdata('login')) {
        //     echo "<script>alert('You need to log in!');</script>";
        //     echo '<script>window.location.href="' . base_url() . '";</script>';
        //     exit; // Make sure to exit to prevent further execution
        // }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;
        /////////////////////////////   
        // Security: users' names / emails / progress are for staff only
        if (!$this->_is_staff()) {
            echo json_encode(array('data' => null));
            exit();
        }

        $qrys = "SELECT 
    course.id_course,
    course.title AS coursetitle,
    user.id_user,
    user.name,
    user.email,
    user.username,
    IFNULL(completion_data.completion_percentage, 0) AS completion_percentage,
    IFNULL(ROUND(avg_course_rating.avg_rating, 2), 0) AS avg_course_rating,
    user_course_rating.rating,
    user_course_rating.feedback
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
    user.id_user, course.id_course;

";
        /////////////////////////
        $usercourse = $this->fct->search($qrys);
        $response['totalCount'] = count($usercourse);
        /////////////////////////
        $response['data'] = $usercourse;
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



    public function examcourse($id_course = '')
    {
        $id_course = ($id_course === '') ? '' : (int) $id_course; // Security: numeric id from the URL
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
        $category_course = array();
        $result = $this->fct->getAll("course", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

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

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $cond = array('id_course' => $id_course);
        $data['oncourse'] = $this->fct->getonerow("course", $cond);

        $data['id_course'] = $id_course;
        ///////////////////////////////////////////////////
        if ($id_course)
            $conda = " AND course.id_course = " . $id_course . " AND user.id_user = " . $id_user;
        else
            $conda = " AND user.id_user = " . $id_user;

        $qrys = "SELECT 
        exam.time, 
        exam.hide_students, 
        exam.id_exam,
        course.id_course,
        course.title,
        exam.title AS exam,
        exam.number_of_questions,
        exam.start_date,
        exam.close_date,
        user_course.id_role
    FROM 
        course 
    INNER JOIN 
        exam ON exam.id_course = course.id_course AND exam.deleted = 0
    INNER JOIN 
        user_course ON user_course.id_course = course.id_course AND user_course.deleted = 0
    INNER JOIN 
        user ON user_course.id_user = user.id_user AND user.deleted = 0
    WHERE
        (user_course.id_role != 3 OR exam.hide_students != 1)
        AND course.deleted = 0
        " . $conda . " 
    GROUP BY 
    exam.id_exam ";
        $usercourse = $this->fct->search($qrys);
        ////////////////////////////////// userenrollement
        $data['userenrollement'] = 0;
        if (count($usercourse)) {
            foreach ($usercourse as $row) {
                if ($row['id_role']) {
                    $data['userenrollement'] = $row['id_role'];
                    if ($row['id_role'] == 2) break;
                } else {
                    $data['userenrollement'] = '0';
                }
            }
        }


        //////////////////////////////
        $qryssss = "SELECT 
course.id_course,
ROUND(COUNT(DISTINCT user_content.id_content) / COUNT(DISTINCT content.id_content) * 100, 2) AS completion_percentage
FROM 
    course 
LEFT JOIN 
    content ON content.id_course = course.id_course and content.deleted=0 
LEFT JOIN  
    user_content ON content.id_content = user_content.id_content AND user_content.id_user =" . $id_user . " 
    and user_content.deleted=0
    where course.deleted=0
GROUP BY 
    course.id_course";
        $avgPercentageResults = $this->fct->search($qryssss);
        $avgCoursePercentage = [];
        foreach ($avgPercentageResults as $row) {
            $avgCoursePercentage[$row['id_course']] = $row['completion_percentage'];
        }
        $data['avgcourse'] = $avgCoursePercentage;
        ///////////////////////////////////////////////////////////////////////////
        $data['page'] = '';
        $data["content"] = "examlist";
        $this->load->view('template', $data);
    }


    public function listexam($id_course = '')
    {
        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;
        /////////////////////////////   
        $data = array();
        $i = 1;
        $records_html = "";
        $response = [];
        $response['groupCount'] = '';
        $response['summary'] = '';
        // $response['totalCount'] = $totalRecords_num;
        //$response['data'] = [];

        //////////////////////////
        if ($id_course)
            $conda = " AND course.id_course = " . $id_course . " AND user.id_user = " . $id_user;
        else
            $conda = " AND user.id_user = " . $id_user;

        //         $qrys = "SELECT 
        //     exam.time, 
        //     exam.hide_students, 
        //     exam.id_exam,
        //     course.id_course,
        //     course.title,
        //     exam.title AS exam,
        //     exam.number_of_questions,
        //     exam.start_date,
        //     exam.close_date, 
        //     user_course.id_role,
        //     CASE
        //         WHEN CURDATE() BETWEEN exam.start_date AND exam.close_date THEN 'Yes'
        //         ELSE 'No'
        //     END AS is_exam_active
        //     FROM 
        //         course 
        //     INNER JOIN 
        //         exam ON exam.id_course = course.id_course AND exam.deleted = 0
        //     INNER JOIN 
        //         user_course ON user_course.id_course = course.id_course AND user_course.deleted = 0
        //     INNER JOIN 
        //         user ON user_course.id_user = user.id_user AND user.deleted = 0
        //     WHERE
        //         (user_course.id_role != 3 OR exam.hide_students != 1)
        //         AND course.deleted = 0
        //         " . $conda . " 
        //     GROUP BY 
        //     exam.id_exam
        // ";



        $qrys = "
        -- First query: Includes LEFT JOIN with the latest exam_history
SELECT 
    exam.time, 
    exam.hide_students, 
    exam.id_exam,
    course.id_course,
    course.title,
    exam.title AS exam,
    exam.number_of_questions,
    exam.start_date,
    exam.close_date, 
    user_course.id_role,
    CASE
        WHEN CURDATE() BETWEEN exam.start_date AND exam.close_date THEN 'Yes'
        ELSE 'No'
    END AS is_exam_active,
    user_exam.id_user_exam,
    latest_exam_history.id_exam_history, -- Add other fields from latest_exam_history as needed
    latest_exam_history.score
FROM 
    course 
INNER JOIN 
    exam ON exam.id_course = course.id_course AND exam.deleted = 0
INNER JOIN 
    user_course ON user_course.id_course = course.id_course AND user_course.deleted = 0
INNER JOIN 
    user ON user_course.id_user = user.id_user AND user.deleted = 0
LEFT JOIN 
    user_exam ON exam.id_exam = user_exam.id_exam 
       AND user_course.id_user = user_exam.id_user -- Assuming this is the join condition
LEFT JOIN
    (
        SELECT eh.*
        FROM exam_history eh
        INNER JOIN (
            SELECT id_exam, id_user, MAX(id_exam_history) AS max_id_exam_history
            FROM exam_history
            GROUP BY id_exam, id_user
        ) grouped_history
        ON eh.id_exam = grouped_history.id_exam 
        AND eh.id_user = grouped_history.id_user 
        AND eh.id_exam_history = grouped_history.max_id_exam_history
    ) AS latest_exam_history ON exam.id_exam = latest_exam_history.id_exam 
       AND user_course.id_user = latest_exam_history.id_user -- Assuming this is the join condition
WHERE
    (user_course.id_role != 3 OR exam.hide_students != 1)
    AND course.deleted = 0
    $conda
GROUP BY 
    exam.id_exam

UNION

-- Second query: Excludes user_exam and sets additional fields to NULL
SELECT 
    exam.time, 
    exam.hide_students, 
    exam.id_exam,
    course.id_course,
    course.title,
    exam.title AS exam,
    exam.number_of_questions,
    exam.start_date,
    exam.close_date, 
    user_course.id_role,
    CASE
        WHEN CURDATE() BETWEEN exam.start_date AND exam.close_date THEN 'Yes'
        ELSE 'No'
    END AS is_exam_active,
    NULL AS id_user_exam, -- Consistent field names and types with the first query
    NULL AS id_exam_history, -- Additional fields set to NULL
    NULL AS score -- Additional fields set to NULL

FROM 
    course 
INNER JOIN 
    exam ON exam.id_course = course.id_course AND exam.deleted = 0
INNER JOIN 
    user_course ON user_course.id_course = course.id_course AND user_course.deleted = 0
INNER JOIN 
    user ON user_course.id_user = user.id_user AND user.deleted = 0
WHERE
    (user_course.id_role != 3 OR exam.hide_students != 1)
    AND course.deleted = 0
    $conda
    AND exam.id_exam NOT IN (
        SELECT id_exam FROM user_exam WHERE 1 $conda
    )
GROUP BY 
    exam.id_exam
ORDER BY 
    id_user_exam DESC";


        // echo  $qrys;
        // exit();
        //////////////////////////////
        $qryssss = "SELECT 
                        course.id_course,
                        ROUND(COUNT(DISTINCT user_content.id_content) / COUNT(DISTINCT content.id_content) * 100, 2) AS completion_percentage
                        FROM 
                            course 
                        LEFT JOIN 
                            content ON content.id_course = course.id_course and content.deleted=0 
                        LEFT JOIN  
                            user_content ON content.id_content = user_content.id_content AND user_content.id_user =" . $id_user . " 
                            and user_content.deleted=0
                            where course.deleted=0
                        GROUP BY 
                            course.id_course";
        // $avgPercentageResults = $this->fct->search($qryssss);
        // $avgCoursePercentage = [];
        // foreach ($avgPercentageResults as $row) {
        //     $avgCoursePercentage[$row['id_course']] = $row['completion_percentage'];
        // }
        // $data['avgcourse'] = $avgCoursePercentage;
        // ///////////////////////////////////////////////////////////////////////////
        // $usercourse = $this->fct->search($qrys);


        $qryCombined = "
            SELECT 
                q1.score,
                q1.id_user_exam,
                q1.time, 
                q1.hide_students, 
                q1.id_exam,
                q1.id_course,
                q1.title,
                q1.exam,
                q1.number_of_questions,
                q1.start_date,
                q1.close_date, 
                q1.id_role,
                q1.is_exam_active,
                IFNULL(q2.completion_percentage, 0) AS completion_percentage
            FROM 
                ($qrys) AS q1
            LEFT JOIN
                ($qryssss) AS q2
            ON
                q1.id_course = q2.id_course
            ";

        $usercourse = $this->fct->search($qryCombined);
        $response['totalCount'] = count($usercourse);

        //////////////////////////////////
        // $data['userenrollement'] = 0;
        // if (count($usercourse)) {
        //     foreach ($usercourse as $row) {
        //         if ($row['id_role']) {
        //             $data['userenrollement'] = $row['id_role'];
        //         } else {
        //             $data['userenrollement'] = '0';
        //         }
        //     }
        // }


        /////////////////////////
        $response['data'] = $usercourse;
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


    public function addexam()
    {
        if (!$this->_is_staff()) {
            redirect(base_url());
            exit();
        }
        $this->form_validation->set_rules("title", "title", "trim|required");
        $this->form_validation->set_rules("start_date", "start date", "trim|required");
        $this->form_validation->set_rules("close_date", "close date", "trim|required");
        $this->form_validation->set_rules("time", "time", "trim|required");
        $this->form_validation->set_rules("description", "description", "trim");
        $this->form_validation->set_rules("number_of_questions", "number of questions", "trim");
        $this->form_validation->set_rules("number_of_attempts", "number of attempts", "trim");


        if ($this->form_validation->run() == FALSE) {
            echo "Error ";
        } else {
            $_data["title"] = $this->input->post("title");
            $_data["id_course"] = $this->input->post("id_course");
            $_data["start_date"] = $this->input->post("start_date");
            $_data["close_date"] = $this->input->post("close_date");
            $_data["time"] = $this->input->post("time");
            $_data["description"] = $this->input->post("description");
            $_data["number_of_questions"] = $this->input->post("number_of_questions");
            $_data["number_of_attempts"] = $this->input->post("number_of_attempts");
            $_data["id_exam"] = $this->input->post("id_exam");

            $datinsertupdate = 0;
            $datinsert = 0;
            if ($_data["id_course"]) {
                if ($this->input->post("id_exam") != "") {
                    $_data["updated_date"] = date("Y-m-d h:i:s");
                    $this->db->where("id_exam", $this->input->post("id_exam"));
                    $datinsert = $this->db->update("exam", $_data);
                    $datinsertupdate = '1';
                    //$this->session->set_userdata("success_message","Information was updated successfully");
                } else {
                    $_data["created_date"] = date("Y-m-d h:i:s");
                    $datinsert = $this->db->insert('exam', $_data);
                    $id_exam = $this->db->insert_id();
                    $datinsertupdate = '2';
                }
            }

            if ($datinsert) {
                // If insertion is successful, return success message
                if ($datinsertupdate == 1)
                    echo "1";
                if ($datinsertupdate == 0)
                    echo "0";
                elseif ($datinsertupdate == 2)
                    echo $id_exam;
            } else {
                // If insertion fails, return error message
                echo "0";
            }

            // if(!$this->session->userdata('login')) {
            //     echo "<script>alert('Information was inserted successfully');</script>";
            //     echo '<script>window.location.href="' . base_url() . 'editexam";</script>';
            //     exit; // Make sure to exit to prevent further execution
            // }

            // $this->session->set_userdata("success_message","Information was inserted successfully");
            //echo "<div class='titledata' >Thanks for your contact</div>";
            //redirect(base_url()."course/");
        }
    }





    public function listapidata()
    {
        //////////////////// Start Header  
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
        $category_course = array();
        $result = $this->fct->getAll("course", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;
        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit;
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        ////////////////////  End Header 
        $data['info']["description"] = '';

        ///////////////////

        //////////////////  
        $data["content"] = "listapidata";
        $this->load->view('template', $data);
    }




    public function getapidata()
    {
        // Security: logged-in users only (the generated SQL is executed on the database)
        $login = $this->session->userdata('login');
        if (empty($login['idUser'])) {
            echo "<div class='apidata'>Please login first!</div>";
            return;
        }
        // Set the OpenAI API endpoint
        $endpoint = 'https://api.openai.com/v1/completions';

        // Set the question
        // $question = ' get me first name   from about ';
        $question = $_REQUEST['question'];
        // Fetch all table names
        $tables_query = $this->db->query("SHOW TABLES");
        $tables_result = $tables_query->result_array();
        $tables = array_column($tables_result, 'Tables_in_' . $this->db->database);

        // Fetch columns for each table
        $schema = [];
        foreach ($tables as $table) {
            $columns_query = $this->db->query("DESCRIBE $table");
            $columns_result = $columns_query->result_array();
            $columns = array_column($columns_result, 'Field');
            $columns = array_diff($columns, array('password', 'hashsendcode')); // Security: never expose secrets
            if ($table != "admin" && $table != "client" && $table != "admin_rules" && $table != "attr_type" && $table != "banner" && $table != "captcha") {
                $schema[$table] = $columns;
            }
        }
        // echo "<pre>";
        // print_r($schema);
        // exit();




        // Ensure schema is stored with GPT model
        // Text representation of the database schema

        // Append the question to the prompt
        $prompt = "please generate a SQL query to answer this question: '{$question}'. The query should be specific and directly answer the question.";


        // Construct the prompt with the database
        $prompt .= " Given the following database schema: ";
        foreach ($schema as $table => $columns) {

            $prompt .= "Table '{$table}' has columns '" . implode("', '", $columns) . "'.\n";
        }



        // Set the API key
        // Key lives in application/config/secrets.php (not committed)
        $secrets = is_file(APPPATH . 'config/secrets.php') ? include APPPATH . 'config/secrets.php' : array();
        $apiKey = isset($secrets['openai_api_key']) ? $secrets['openai_api_key'] : '';

        // Set the request data
        $data = [
            'model' => 'gpt-3.5-turbo-instruct-0914',
            'prompt' => $prompt,
            'temperature' => 0.5,
            'max_tokens' => 200,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
        ];

        // Initialize cURL session
        $ch = curl_init($endpoint);

        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
        ]);

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $fullname_user = $userId['fullname'];

        // Execute the cURL request
        $response = curl_exec($ch);
        // Check for errors
        if ($response === false) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            // Decode the JSON response
            $responseData = json_decode($response, true);

            // Extract the generated query from the response
            $generatedQuery = $responseData['choices'][0]['text'] ?? '';

            // Clean up the generated query
            $cleanQuery = preg_replace('/\s+/', ' ', trim($generatedQuery));

            // Output or use the clean query
            // Security: only plain SELECTs, never on passwords / reset codes / admin tables / server files
            // (strpos instead of str_starts_with so it also works on PHP 7.4)
            $forbidden = '/password|hashsendcode|information_schema|performance_schema|mysql\s*\.|(from|join)\s+`?(admin|admin_rules|client|captcha)`?|into\s+(out|dump)file|load_file|sleep\s*\(|benchmark\s*\(/i';
            if (!empty($cleanQuery) && strpos(strtolower($cleanQuery), 'select') === 0 && !preg_match($forbidden, $cleanQuery)) {
                // $cleanQuery = $this->fct->search($cleanQuery);

                try {
                    $query = $this->db->query($cleanQuery);
                    if ($query) {
                        $cleanQuery = $query->result_array(); // Performance: reuse the result instead of running the query twice
                        $ii = 1;
                        if ($cleanQuery) {
                            foreach ($cleanQuery as  $row) { //if(str_contains($row , $fullname_user))
                                if ($row) {
                                    echo  "<div class='apidata'>" . $ii . " - "; // Access data by column name
                                    $ii++;

                                    foreach ($row as  $value) {
                                        if ($value) {
                                            echo  "<span class='apidata'>" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "</span> "; // Access data by column name (escaped)
                                        }
                                        // echo "\n";
                                    }
                                    echo  "</div><div class='apidataline'></div>";
                                }
                                //  echo "Generated SQL query: " . $cleanQuery;
                            }
                        } else {
                            echo "<div class='apidata'>No data in your search!</div>";
                        }
                    } else {
                        echo "<div class='apidata'>Incorrect question format or other error occurred!</div>";
                    }
                } catch (Exception $e) {
                    // Handle the exception
                    echo "<div class='apidata'>Incorrect question format or other error occurred!</div>"; // Security: don't leak DB errors
                }
            } else {
                echo "<div class='apidata'>No valid SQL query generated.</div>";
            }
        }
        // Close the cURL session
        curl_close($ch);
    } ///



    public function courseexamhistory($id_course = '')
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
        $category_course = array();
        $result = $this->fct->getAll("course", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $cond = array('id_course' => $id_course);
        $data['oncourse'] = $this->fct->getonerow("course", $cond);

        $data['id_course'] = $id_course;
        ///////////////////////////////////////////////////
        // if($id_course)
        // $conda=" AND course.id_course = ".$id_course." AND user.id_user = ".$id_user;
        // else 
        // $conda= " AND user.id_user = ".$id_user;
        $query = "select   exam.id_exam,course.title course,exam.title exam,grade.title grade,exam_history.score,exam_history.number_attempts
FROM exam_history,course,grade,exam
where exam_history.id_course = course.id_course
and exam_history.id_exam = exam.id_exam
and exam_history.id_grade=grade.id_grade
and exam_history.deleted =0
and exam.deleted = 0
and grade.deleted = 0
and course.deleted = 0
 and exam_history.id_user = " . $id_user;
        $examhsit = $this->fct->search($query);

        $qrys = "SELECT 
 exam.time, 
 exam.hide_students, 
 exam.id_exam,
 course.id_course,
 course.title,
 exam.title AS exam,
 exam.number_of_questions,
 exam.start_date,
 exam.close_date,
 user_course.id_role
FROM 
 course 
INNER JOIN 
 exam ON exam.id_course = course.id_course 
 AND exam.deleted = 0
INNER JOIN 
 user_course ON user_course.id_course = course.id_course AND user_course.deleted = 0
INNER JOIN 
 user ON user_course.id_user = user.id_user AND user.deleted = 0
WHERE
 (user_course.id_role != 3 OR exam.hide_students != 1)
 AND course.deleted = 0
 " . $conda . " 
GROUP BY 
exam.id_exam ";
        $usercourse = $this->fct->search($qrys);
        ////////////////////////////////// userenrollement
        $data['userenrollement'] = 0;
        if (count($usercourse)) {
            foreach ($usercourse as $row) {
                if ($row['id_role']) {
                    $data['userenrollement'] = $row['id_role'];
                    if ($row['id_role'] == 2) break;
                } else {
                    $data['userenrollement'] = '0';
                }
            }
        }


        //////////////////////////////
        $qryssss = "SELECT 
course.id_course,
ROUND(COUNT(DISTINCT user_content.id_content) / COUNT(DISTINCT content.id_content) * 100, 2) AS completion_percentage
FROM 
course 
LEFT JOIN 
content ON content.id_course = course.id_course and content.deleted=0 
LEFT JOIN  
user_content ON content.id_content = user_content.id_content AND user_content.id_user =" . $id_user . " 
and user_content.deleted=0
where course.deleted=0
GROUP BY 
course.id_course";
        $avgPercentageResults = $this->fct->search($qryssss);
        $avgCoursePercentage = [];
        foreach ($avgPercentageResults as $row) {
            $avgCoursePercentage[$row['id_course']] = $row['completion_percentage'];
        }
        $data['avgcourse'] = $avgCoursePercentage;
        ///////////////////////////////////////////////////////////////////////////
        $data['page'] = '';
        $data["content"] = "examhistorylist";
        $this->load->view('template', $data);
    }


    public function listexamhistory($id_course = '')
    {
        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;
        /////////////////////////////   
        $data = array();
        $i = 1;
        $records_html = "";
        $response = [];
        $response['groupCount'] = '';
        $response['summary'] = '';
        $response['totalCount'] = $totalRecords_num;
        //$response['data'] = [];

        //////////////////////////

        $query = "select   exam.id_exam,course.title course,exam.title exam,grade.title grade,exam_history.score,exam_history.number_attempts,exam_history.created_date
FROM exam_history,course,grade,exam
where exam_history.id_course = course.id_course
and exam_history.id_exam = exam.id_exam
and exam_history.id_grade=grade.id_grade
and exam_history.deleted =0
and exam.deleted = 0
and grade.deleted = 0
and course.deleted = 0
 and exam_history.id_user = " . $id_user;

        $examhist = $this->fct->search($query);

        /////////////////////////
        $response['data'] = $examhist;
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



    public function feedbackhistory($id_course = '')
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
        $category_course = array();
        $result = $this->fct->getAll("course", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }


        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $cond = array('id_course' => $id_course);
        $data['oncourse'] = $this->fct->getonerow("course", $cond);

        $data['id_course'] = $id_course;
        ///////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////
        $data['page'] = '';
        $data["content"] = "feedbackhistorylist";
        $this->load->view('template', $data);
    }

    public function listfeebacks($id_course = '')
    {
        if (!$this->session->userdata('login') || !$this->_is_staff()) { // Security: all users' data - staff only
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;
        /////////////////////////////   
        $data = array();
        $i = 1;
        $records_html = "";
        $response = [];
        $response['groupCount'] = '';
        $response['summary'] = '';
        $response['totalCount'] = $totalRecords_num;
        //$response['data'] = [];

        //////////////////////////

        $query = "SELECT 
   course.title AS title,
    user.id_user,
    user.name,
    user.email,
    CONCAT('" . base_url() . "uploads/user/', user.image) AS image,
    user.occupation,
    user_course_rating.feedback,
    lecture_name.lecturername ,
    user_course_rating.rating,
    avg_rating.avg_rating AS average_rating
FROM user_course_rating
inner join course
on user_course_rating.id_course = course.id_course
inner join user 
on user_course_rating.id_user = user.id_user
  left join (SELECT user.name lecturername,id_course
        FROM user, user_course 
        where user.deleted = 0 
        and user_course.deleted = 0
        and user.id_user = user_course.id_user
        and user_course.id_role = 2) as lecture_name on lecture_name.id_course = course.id_course
        left JOIN
    (SELECT
        id_course,
        AVG(rating) AS avg_rating
    FROM
        user_course_rating
    WHERE
        deleted = 0
    GROUP BY
        id_course) AS avg_rating ON course.id_course = avg_rating.id_course
            where user_course_rating.deleted = 0
        and course.deleted = 0 
        and user.deleted = 0; ";





        // and exam_history.id_user = ".$id_user;
        $feedbacks = $this->fct->search($query);

        // echo "<pre>";
        // print_r($feedbacks);
        // exit();


        /////////////////////////
        $response['data'] = $feedbacks;
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

    public function raitingCourse()
    {
        $qry = "SELECT 
                    course.title AS arg,
                 AVG(user_course_rating.rating) AS val
               --  user_course_rating.id_user,
               --  user.name,user.email
                FROM 
                    course
                LEFT JOIN 
                    user_course_rating ON course.id_course = user_course_rating.id_course
                    left join user on user_course_rating.id_user = user.id_user
                WHERE 
                    course.deleted = 0 and
                    user_course_rating.deleted = 0
                    and user.deleted = 0
                     GROUP BY course.id_course
            ";
        $avg_course_rating = $this->fct->search($qry);

        $response = $avg_course_rating;
        // Set appropriate headers
        header('Content-Type: application/json');
        echo json_encode($response);
        // echo "<pre>";
        // print_r($data);
        // exit();
    }


    public function listcourses()
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
        $category_course = array();
        $result = $this->fct->getAll("course", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

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
        $data["content"] = "courselist";
        $this->load->view('template', $data);
    }

    public function all_courses()
    {
        $course = "SELECT   course.id_course,
        course.title coursetitle,category.title cattitle,
        CONCAT('" . base_url() . "uploads/course/', course.image) AS image,
        lecture_name.lecturername ,
        avg_rating.avg_rating AS average_rating
        FROM
        course    
        left join category on course.id_category = category.id_category
        LEFT JOIN
        (SELECT
            id_course,
            AVG(rating) AS avg_rating
        FROM
            user_course_rating
        WHERE
            deleted = 0
        GROUP BY
            id_course) AS avg_rating ON course.id_course = avg_rating.id_course
            left join (SELECT user.name lecturername,id_course
            FROM user, user_course 
            where user.deleted = 0 
            and user_course.deleted = 0
            and user.id_user = user_course.id_user
            and user_course.id_role = 2) as lecture_name on lecture_name.id_course = course.id_course
        WHERE
        course.deleted = 0
              and category.deleted = 0 
                       ";
        $listofcourses = $this->fct->search($course);
        $response['data'] = $listofcourses;
        ////-------------------------------------limiting nb of records to only a page-------------------
        $totalRecords_num = count($listofcourses);
        //-----------------------------------------------------------------------------------------------------
        if ($totalRecords_num > 0) {
        } else {
            $response['data'] = null;
        }
        // // Set appropriate headers
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function content()
    {
        $course = "SELECT course.id_course, content.id_content, content.title contenttitle
        FROM course left join content
        on course.id_course = content.id_course 
            where course.deleted = 0
            and content.deleted = 0
        ORDER BY 
        content.id_content";

        $listofcourses = $this->fct->search($course);
        $response['data'] = $listofcourses;
        ////-------------------------------------limiting nb of records to only a page-------------------
        $totalRecords_num = count($listofcourses);
        //-----------------------------------------------------------------------------------------------------
        if ($totalRecords_num > 0) {
        } else {
            $response['data'] = null;
        }
        // // Set appropriate headers
        header('Content-Type: application/json');
        echo json_encode($response);
    }



    public function totalexamshistory($id_course = '')
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
        $category_course = array();
        $result = $this->fct->getAll("course", "sort_order");
        // Loop through the query result array
        foreach ($result as $row) {
            // Extract the id_category value from the current row
            $idCategory = $row['id_category'];
            // Append the current row to the array corresponding to its id_category
            $category_course[$idCategory][] = $row;
        }
        $data['category_course'] = $category_course;

        if (!$this->session->userdata('login')) {
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }

        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;

        $linkpage['redirect_url'] = $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $linkpage);
        //////////  End Header

        $cond = array('id_course' => $id_course);
        $data['oncourse'] = $this->fct->getonerow("course", $cond);

        $data['id_course'] = $id_course;



        ///////////////////////////////////////////////////////////////////////////
        $data['page'] = '';
        $data["content"] = "totalexamshistorylist";
        $this->load->view('template', $data);
    }


    public function totallistexamshistory($id_course = '')
    {
        if (!$this->session->userdata('login') || !$this->_is_staff()) { // Security: all users' data - staff only
            echo "<script>alert('You need to log in!');</script>";
            echo '<script>window.location.href="' . base_url() . '";</script>';
            exit; // Make sure to exit to prevent further execution
        }
        $userId = $this->session->userdata('login');
        $id_user = $userId['idUser'];
        $data['login'] = $userId;
        /////////////////////////////   
        $data = array();
        $i = 1;
        $records_html = "";
        $response = [];
        $response['groupCount'] = '';
        $response['summary'] = '';
        $response['totalCount'] = '10';
        //$response['data'] = [];

        //////////////////////////

        $query = "select  user.name as username,exam_history.id_user , exam.id_exam,course.title course,
        exam.title exam,grade.title grade,exam_history.score,exam_history.number_attempts,exam_history.created_date
        FROM exam_history,course,grade,exam,user
        where exam_history.id_course = course.id_course
        and exam_history.id_exam = exam.id_exam
        and exam_history.id_grade=grade.id_grade
        and exam_history.id_user = user.id_user
        and exam_history.deleted =0
        and exam.deleted = 0
        and grade.deleted = 0
        and course.deleted = 0
        and user.deleted = 0";
        //   echo "<pre>";
        //             echo $query ;
        //             // print_r($data["question"]);
        //             exit();
        $examhist = $this->fct->search($query);

        /////////////////////////
        $response['data'] = $examhist;
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
}
