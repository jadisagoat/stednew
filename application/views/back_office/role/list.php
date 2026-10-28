<?php
// 1. privilege block (content_type 2 = role, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("2");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// legacy sort links: index/title | index/active
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title, active badge; per-row "Priviledge" link = role/priviledge/<id>)
$grid = array(
'module' => 'role', 'key' => 'id_role', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'active'),
'types' => array('active' => 'status'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('grid_privileges', 'Privileges'), 'url' => 'role/priviledge/{id}', 'icon' => 'bx-lock-open'),
) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('role', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
