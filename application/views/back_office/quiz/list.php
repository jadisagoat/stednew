<?php
// 1. privilege block (content_type 43 = quiz, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("43");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// numeric key / sort_order (the default order is sort_order: as strings "10" would come before "2")
foreach ($rows as $i => $r) {
    $rows[$i]['id_quiz'] = (int) $r['id_quiz'];
    $rows[$i]['sort_order'] = (int) $r['sort_order'];
}
$bo_order = $this->uri->segment(4);   // legacy sort link: quiz/index/<field>
// 2. grid config (legacy column: title)
$grid = array(
'module' => 'quiz', 'key' => 'id_quiz', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('quiz', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
