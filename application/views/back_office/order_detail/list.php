<?php
// 1. privilege block (content_type 28 = order detail, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("28");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// legacy sort links: index/quantity | index/price
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: order, course, quantity, price)
// the orders table has no title column (the legacy getonecell("orders", "title") failed): the order is shown by its
// number, linked to its edit page
$grid = array(
'module' => 'order_detail', 'key' => 'id_order_detail', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('id_orders', 'id_course', 'quantity', 'price'),
'types' => array('id_orders' => 'link'),
'links' => array('id_orders' => 'orders/edit/{id_orders}'),
'lookups' => array('id_course' => array('course', 'title', 'id_course')), 'lookups_empty' => bo_t('grid_not_available'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('order detail', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
