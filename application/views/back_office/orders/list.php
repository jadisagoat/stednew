<?php
// 1. privilege block (content_type 27 = orders, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("27");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// legacy sort link: index/date
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: user, date); the user is shown by name (user.title, used by the legacy list, is mostly empty)
$grid = array(
'module' => 'orders', 'key' => 'id_orders', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('id_user', 'date'),
'types' => array('date' => 'date'),
'lookups' => array('id_user' => array('user', 'name', 'id_user')), 'lookups_empty' => bo_t('grid_not_available'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('orders', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
