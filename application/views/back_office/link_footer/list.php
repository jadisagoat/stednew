<?php
// 1. privilege block (content_type 32 = link footer, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("32");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title, link => clickable external URL)
$grid = array(
'module' => 'link_footer', 'key' => 'id_link_footer', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'link'),
'types' => array('link' => 'link'),
'links' => array('link' => array('url' => '{value}', 'target' => '_blank')),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('link footer', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
