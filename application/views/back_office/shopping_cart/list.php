<?php
// 1. privilege block (content_type 9 = shopping cart, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("9");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// legacy sort links: index/title | index/purshased
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title, client, course, purchased flag)
$grid = array(
'module' => 'shopping_cart', 'key' => 'id_shopping_cart', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'id_client', 'id_course', 'purshased'),
'types' => array('purshased' => 'yesno'),
'lookups' => array('id_client' => array('client', 'title', 'id_client'), 'id_course' => array('course', 'title', 'id_course')),
'lookups_empty' => bo_t('grid_not_available'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('shopping cart', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
