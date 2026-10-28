<?php
// 1. privilege block (content_type 10 = exam, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("10");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. numeric columns are stored as varchar/decimal: cast them so the grid sorts/filters them as numbers
//    (key + sort_order too: the default order is sort_order, as strings "10" would come before "2")
foreach ($rows as $i => $r) {
    $rows[$i]['id_exam'] = (int) $r['id_exam'];
    $rows[$i]['sort_order'] = (int) $r['sort_order'];
    foreach (array('time', 'number_of_questions', 'number_of_attempts') as $f) {
        $v = isset($r[$f]) ? trim((string) $r[$f]) : '';
        $rows[$i][$f] = is_numeric($v) ? $v + 0 : ($v === '' ? null : $v);
    }
}
$bo_order = $this->uri->segment(4);   // legacy sort link: exam/index/<field>
// 3. grid config (legacy columns + the exam image; drill-down: questions of the exam)
$grid = array(
'module' => 'exam', 'key' => 'id_exam', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
// start_date / close_date are timestamps (shown with their time); the legacy free-text start_date_time / close_date_time
// columns are not shown: they only hold NULL / "" / "0" (the time is part of start_date / close_date)
'columns' => array('title', 'image', 'id_course', 'start_date', 'close_date',
                   'time', 'number_of_questions', 'number_of_attempts', 'hide_students'),
'types' => array('image' => 'image', 'start_date' => 'datetime', 'close_date' => 'datetime',
                 'time' => 'number', 'number_of_questions' => 'number', 'number_of_attempts' => 'number', 'hide_students' => 'yesno'),
'files' => 'uploads/exam/', 'thumb' => '',
'lookups' => array('id_course' => array('course', 'title', 'id_course')), 'lookups_empty' => '--',
'widths' => array('image' => 100, 'time' => 90),
// narrow screens: image and flags fold first, title / course / dates stay
'hiding' => array('title' => 60, 'id_course' => 59, 'start_date' => 58, 'close_date' => 57, 'number_of_questions' => 56,
                  'number_of_attempts' => 55, 'time' => 54, 'hide_students' => 53, 'image' => 52),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => array_merge(
    array(array('label' => bo_module_label('question'), 'url' => 'question/lists/{id}', 'icon' => 'bx-help-circle')),
    ($write_priv == 1) ? array(array('label' => bo_t('site_delete_image'), 'url' => 'exam/delete_image/image/{image}/{id}',
        'icon' => 'bx-image-alt', 'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'image')) : array()
)),
'add' => $write_priv, 'add_label' => bo_module_label('exam', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
