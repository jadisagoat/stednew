<?php
// 1. privilege block (content_type 12 = answer, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("12");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. context: answer/lists/<id_question> = answers of one question (drill-down from the question list, rows inline);
//    answer = all answers (~4000 rows: server mode, rows from answer/grid_data, same columns/lookups as the controller)
$bo_id_question = ($this->uri->segment(3) === 'lists') ? (int) $this->uri->segment(4) : 0;
$bo_drill = ($bo_id_question > 0);
$rows = ($bo_drill && isset($info) && is_array($info)) ? $info : array();
// numeric key / sort_order (the drill-down order is sort_order: as strings "10" would come before "2")
foreach ($rows as $i => $r) {
    $rows[$i]['id_answer'] = (int) $r['id_answer'];
    $rows[$i]['sort_order'] = (int) $r['sort_order'];
}
$bo_chain = '';
$bo_toolbar = array();
if ($bo_drill) {
    // Exams > <exam> (link: questions of that exam) > <question>
    $bo_q = $this->fct->getonerow("question", array("id_question" => $bo_id_question));
    $bo_chain = array(array('label' => bo_module_label('exam'), 'url' => 'exam'));
    if (!empty($bo_q["id_exam"])) {
        $bo_exam = trim((string) $this->fct->getonecell("exam", "title", array("id_exam" => $bo_q["id_exam"])));
        $bo_chain[] = array('label' => ($bo_exam !== '' ? $bo_exam : '#' . (int) $bo_q["id_exam"]), 'url' => 'question/lists/' . (int) $bo_q["id_exam"]);
    }
    $bo_qt = isset($bo_q["title"]) ? trim(strip_tags((string) $bo_q["title"])) : '';
    if (function_exists('mb_strimwidth')) $bo_qt = mb_strimwidth($bo_qt, 0, 90, '...', 'UTF-8');
    $bo_chain[] = array('label' => ($bo_qt !== '' ? $bo_qt : '#' . $bo_id_question));
    $bo_toolbar[] = array('label' => bo_module_label('answer', 'list'), 'url' => 'answer', 'icon' => 'bx-list-ul');
}
// 3. grid config
$grid = array(
'module' => 'answer', 'key' => 'id_answer', 'title' => $title,
'subtitle' => $bo_chain, 'toolbar' => $bo_toolbar,
// the question column is redundant inside one question's drill-down
'columns' => $bo_drill ? array('title', 'true') : array('title', 'id_question', 'true'),
'types' => array('true' => 'yesno'),
'lookups' => array('id_question' => array('question', 'title', 'id_question')), 'lookups_empty' => '--',
'search' => array('title', 'id_question'),
'hiding' => array('title' => 60, 'id_question' => 59, 'true' => 58),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('answer', 'new'),
'bulk' => $delete_priv, 'export' => true,
);
if ($bo_drill) {
    // a few answers: inline rows, drag & drop order of the question's answers
    $grid += array('ajax' => false, 'rows' => $rows, 'order' => 'sort_order', 'reorder' => $write_priv);
} else {
    $grid += array('ajax' => true, 'rows' => array(), 'order' => 'id_answer desc', 'reorder' => 0);
}
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
