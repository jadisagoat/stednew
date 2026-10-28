<?php
// 1. privilege block (content_type 35 = contact us, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("35");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title, image "show image" popup)
$grid = array(
'module' => 'contact_us', 'key' => 'id_contact_us', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'image'),
'types' => array('image' => 'image'),
'files' => 'uploads/contact_us/', 'thumb' => '',
'widths' => array('image' => 140),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('contact us', 'new'),
'bulk' => $delete_priv, 'reorder' => 0, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
