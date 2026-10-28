<?php
// 1. privilege block (content_type 14 = exam history, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("14");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. number_attempts is a varchar: cast it (and score) so the grid sorts/filters them as numbers;
//    the key too (the default order is the key, desc: as strings "99" would come before "431")
foreach ($rows as $i => $r) {
    $rows[$i]['id_exam_history'] = (int) $r['id_exam_history'];
    foreach (array('score', 'number_attempts') as $f) {
        $v = isset($r[$f]) ? trim((string) $r[$f]) : '';
        $rows[$i][$f] = is_numeric($v) ? $v + 0 : ($v === '' ? null : $v);
    }
}
// legacy sort link exam_history/index/<field>; default: latest results first (sort_order is always 0 here)
$bo_order = $this->uri->segment(4);
if ($bo_order === null || $bo_order === '' || $bo_order === false) $bo_order = 'id_exam_history desc';
// 3. grid config (legacy columns; the certificate is the generated PNG in uploads/exam_history/)
$grid = array(
'module' => 'exam_history', 'key' => 'id_exam_history', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('id_course', 'id_exam', 'id_user', 'score', 'id_grade', 'exam_date', 'number_attempts', 'certificate'),
'types' => array('score' => 'number', 'exam_date' => 'date', 'number_attempts' => 'number', 'certificate' => 'file'),
'files' => 'uploads/exam_history/',
'lookups' => array(
    'id_course' => array('course', 'title', 'id_course'),
    'id_exam'   => array('exam', 'title', 'id_exam'),
    'id_user'   => array('user', 'name', 'id_user'),
    'id_grade'  => array('grade', 'title', 'id_grade'),
), 'lookups_empty' => '--',
'widths' => array('score' => 90, 'id_grade' => 90, 'number_attempts' => 100),
'hiding' => array('id_user' => 60, 'id_exam' => 59, 'score' => 58, 'id_grade' => 57, 'exam_date' => 56,
                  'certificate' => 55, 'id_course' => 54, 'number_attempts' => 53),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('exam history', 'new'),
// a results log: no manual drag & drop order
'bulk' => $delete_priv, 'reorder' => 0, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
