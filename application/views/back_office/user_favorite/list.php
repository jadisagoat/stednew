<?php
// 1. privilege block (content_type 34 = user favorite, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("34");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// legacy sort link: index/active (default: sort_order, as the controller orders)
$bo_order = $this->uri->segment(4);
// 2. grid config (legacy columns: user, course, active; drag & drop reorder)
$grid = array(
'module' => 'user_favorite', 'key' => 'id_user_favorite', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('id_user', 'id_course', 'active'),
'labels' => array('id_user' => bo_label('user'), 'id_course' => bo_label('course')),
'types' => array('active' => 'status'),
'lookups' => array('id_user' => array('user', 'name', 'id_user'), 'id_course' => array('course', 'title', 'id_course')),
'lookups_empty' => bo_t('grid_not_available'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('user favorite', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
