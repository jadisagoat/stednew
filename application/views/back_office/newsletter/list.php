<?php
// 1. privilege block: the legacy list (list_legacy.php) had no privilege check, every admin could manage it
$read_priv = 1; $write_priv = 1; $delete_priv = 1;
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: email, date; bulk "Send News Letter" opens newsletter/send with the selected rows)
$grid = array(
'module' => 'newsletter', 'key' => 'id_newsletter', 'rows' => $rows, 'title' => $title,
'order' => ($bo_order ? $bo_order : 'sort_order'),
'columns' => array('email', 'created_date'),
'labels' => array('created_date' => bo_label('date')),
'types' => array('created_date' => 'datetime'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('newsletter', 'new'),
'bulk' => 1, 'bulk_options' => array('send_news' => bo_t('grid_send_newsletter'), 'delete_all' => bo_t('grid_delete_selected')),
'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
