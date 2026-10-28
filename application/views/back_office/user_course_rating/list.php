<?php
// 1. privilege block (content_type 39 = "Student feedback" = user_course_rating, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("39");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// feedback is saved from a rich-text editor on the site: shown (and searched / exported) as plain text, in full
foreach ($rows as $i => $r) {
    $rows[$i]["feedback"] = trim((string) preg_replace('/[\s\x{00A0}]+/u', ' ', html_entity_decode(strip_tags((string) $r["feedback"]), ENT_QUOTES, 'UTF-8')));
}
$bo_order = $this->uri->segment(4);
// 2. grid config (legacy columns: user, course, rating, feedback, active)
$grid = array(
'module' => 'user_course_rating', 'key' => 'id_user_course_rating', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('id_user', 'id_course', 'rating', 'feedback', 'active'),
// f_feedback and f_rating share the Arabic caption التقييم: the feedback column uses f_comment (التعليق) in Arabic
'labels' => array('id_user' => bo_label('user'), 'id_course' => bo_label('course'),
                  'feedback' => bo_rtl() ? bo_label('comment') : bo_label('feedback')),
'types' => array('rating' => 'number', 'active' => 'status'),
'lookups' => array('id_user' => array('user', 'name', 'id_user'), 'id_course' => array('course', 'title', 'id_course')),
'lookups_empty' => bo_t('grid_not_available'),
'align' => array('rating' => 'center'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('user course rating', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
