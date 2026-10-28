<?php
// 1. privilege block (content_type 11 = question, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("11");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// numeric key / sort_order (the default order is sort_order: as strings "10" would come before "2")
foreach ($rows as $i => $r) {
    $rows[$i]['id_question'] = (int) $r['id_question'];
    $rows[$i]['sort_order'] = (int) $r['sort_order'];
}
// 2. context: question/lists/<id_exam> = questions of one exam (drill-down from the exam list), question = all questions
$bo_id_exam = ($this->uri->segment(3) === 'lists') ? (int) $this->uri->segment(4) : 0;
$bo_chain = '';
$bo_toolbar = array();
if ($bo_id_exam > 0) {
    $bo_exam = trim((string) $this->fct->getonecell("exam", "title", array("id_exam" => $bo_id_exam)));
    $bo_chain = array(array('label' => bo_module_label('exam'), 'url' => 'exam'),
                      array('label' => ($bo_exam !== '' ? $bo_exam : '#' . $bo_id_exam)));
    $bo_toolbar[] = array('label' => bo_module_label('question', 'list'), 'url' => 'question', 'icon' => 'bx-list-ul');
}
// legacy sort link question/index/<field> (full list only)
$bo_order = ($bo_id_exam > 0) ? 'sort_order' : $this->uri->segment(4);
// 3. grid config
$grid = array(
'module' => 'question', 'key' => 'id_question', 'rows' => $rows, 'title' => $title,
'subtitle' => $bo_chain, 'toolbar' => $bo_toolbar,
'order' => $bo_order,
// the exam column is redundant inside one exam's drill-down
'columns' => ($bo_id_exam > 0) ? array('title', 'rate') : array('title', 'id_exam', 'rate'),
'types' => array('rate' => 'number'),
'lookups' => array('id_exam' => array('exam', 'title', 'id_exam')), 'lookups_empty' => '--',
'widths' => array('rate' => 100),
'hiding' => array('title' => 60, 'id_exam' => 59, 'rate' => 58),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => array(
    array('label' => bo_module_label('answer'), 'url' => 'answer/lists/{id}', 'icon' => 'bx-check-square'),
)),
'add' => $write_priv, 'add_label' => bo_module_label('question', 'new'),
'bulk' => $delete_priv,
// drag & drop sends the whole visible order in one GET: only inside one exam (the full list has ~1000 rows)
'reorder' => ($bo_id_exam > 0) ? $write_priv : 0,
'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
