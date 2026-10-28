<?php
// 1. privilege block (content_type 48 = user exam, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("48");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: ~3500 answers given by students -> server mode, rows from user_exam/grid_data
//    (same columns / lookups as the controller). Legacy columns user / question / answer + the exam.
$grid = array(
'module' => 'user_exam', 'key' => 'id_user_exam', 'ajax' => true, 'rows' => array(), 'title' => $title,
'order' => 'id_user_exam desc',
'columns' => array('id_user', 'id_exam', 'id_question', 'id_answer'),
'lookups' => array(
    'id_user'     => array('user', 'name', 'id_user'),
    'id_exam'     => array('exam', 'title', 'id_exam'),
    'id_question' => array('question', 'title', 'id_question'),
    'id_answer'   => array('answer', 'title', 'id_answer'),
),
'search' => array('id_user', 'id_exam', 'id_question', 'id_answer'),
'hiding' => array('id_user' => 60, 'id_question' => 59, 'id_answer' => 58, 'id_exam' => 57),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('user exam', 'new'),
'bulk' => $delete_priv, 'reorder' => 0, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
