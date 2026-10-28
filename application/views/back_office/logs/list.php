<?php
// 1. privilege block (content_type 13 = logs, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("13");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 3. grid config: AJAX mode (logs::grid_data(), same columns / lookups / order), the log table can be large
//    legacy columns: title, client, ip address (+ date of the log entry)
$grid = array(
'module' => 'logs', 'key' => 'id_logs', 'ajax' => true, 'title' => $title,
'columns' => array('title', 'id_client', 'ip_address', 'created_date'),
'labels' => array('created_date' => bo_label('date')),
'types' => array('created_date' => 'datetime'),
'lookups' => array('id_client' => array('client', 'title', 'id_client')),
'search' => array('title', 'id_client', 'ip_address'),
'order' => 'id_logs desc',
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('logs', 'new'),
'bulk' => $delete_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
