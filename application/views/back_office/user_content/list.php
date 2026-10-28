<?php
// 1. privilege block (content_type 45 = user content, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("45");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. rows = the progress report of user_content::_progress_rows() (one row per enrolment, key row_key):
//    read-only like the legacy list (no add / edit / delete / bulk: the rows are not user_content records)
$rows = (isset($info) && is_array($info)) ? $info : array();
foreach ($rows as $i => $r) {
    // the rating feedback is saved from a rich-text editor: shown (and searched / exported) as plain text
    $rows[$i]["feedback"] = trim((string) preg_replace('/[\s\x{00A0}]+/u', ' ', html_entity_decode(strip_tags((string) $r["feedback"]), ENT_QUOTES, 'UTF-8')));
}
// 3. grid config (legacy columns: course id, course, user, e-mail, feedback, rating, avg course rating, completion %)
$grid = array(
'module' => 'user_content', 'key' => 'row_key', 'rows' => $rows, 'title' => $title,
'order' => 'row_key',
'columns' => array('id_course', 'coursetitle', 'name', 'email', 'feedback', 'rating', 'avg_course_rating', 'completion_percentage'),
// f_feedback and f_rating share the Arabic caption التقييم: the feedback column uses f_comment (التعليق) in Arabic
'labels' => array('id_course' => '#', 'coursetitle' => bo_label('course'), 'name' => bo_label('user'),
                  'feedback' => bo_rtl() ? bo_label('comment') : bo_label('feedback'),
                  // f_avg_course_rating / f_completion_percentage: English fallbacks until the dictionary has them
                  'avg_course_rating' => bo_t('f_avg_course_rating', 'Avg. Course Rating'),
                  'completion_percentage' => bo_t('f_completion_percentage', 'Completion %')),
'types' => array('id_course' => 'number', 'rating' => 'number', 'avg_course_rating' => 'number', 'completion_percentage' => 'number'),
'align' => array('id_course' => 'center', 'rating' => 'center', 'avg_course_rating' => 'center', 'completion_percentage' => 'center'),
'widths' => array('id_course' => 60, 'avg_course_rating' => 130, 'completion_percentage' => 130),
// narrow screens: keep course, user and completion; hide the id, feedback and averages first
'hiding' => array('coursetitle' => 200, 'name' => 190, 'completion_percentage' => 180, 'email' => 170, 'rating' => 160,
                  'avg_course_rating' => 150, 'feedback' => 140, 'id_course' => 130),
'actions' => array('edit' => 0, 'delete' => 0),
'add' => 0, 'bulk' => 0, 'reorder' => 0, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
