<?php
// 1. privilege block (content_type 47 = employee, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("47");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 2. grid config (legacy columns: title, email, job position)
$grid = array(
'module' => 'employee', 'key' => 'id_employee', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'email', 'job_position'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('employee', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
