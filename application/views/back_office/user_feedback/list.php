<?php
// 1. privilege block (content_type 51 = user feedback, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("51");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// the feedback text may contain HTML: shown (and searched / exported) as plain text, in full
foreach ($rows as $i => $r) {
    $rows[$i]["description"] = trim((string) preg_replace('/[\s\x{00A0}]+/u', ' ', html_entity_decode(strip_tags((string) $r["description"]), ENT_QUOTES, 'UTF-8')));
}
$bo_order = $this->uri->segment(4);
// 2. grid config (legacy column: user; the feedback text (description) is shown too)
$grid = array(
'module' => 'user_feedback', 'key' => 'id_user_feedback', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('id_user', 'description'),
'labels' => array('id_user' => bo_label('user')),
'lookups' => array('id_user' => array('user', 'name', 'id_user')),
'lookups_empty' => bo_t('grid_not_available'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('user feedback', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
