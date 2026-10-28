<?php
// 1. privilege block (content_type 22 = priviledge role permissions, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("22");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// legacy sort links: index/read_p | write_p | delete_p
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: role, permission, read / write / delete flags (legacy Active / InActive badges, shown as Yes / No))
$grid = array(
'module' => 'priviledge_role_permissions', 'key' => 'id_priviledge_role_permissions', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('id_role', 'id_permissions', 'read_p', 'write_p', 'delete_p'),
'types' => array('read_p' => 'yesno', 'write_p' => 'yesno', 'delete_p' => 'yesno'),
'lookups' => array('id_role' => array('role', 'title', 'id_role'), 'id_permissions' => array('permissions', 'title', 'id_permissions')),
'lookups_empty' => bo_t('grid_not_available'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('priviledge role permissions', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
