<?php
// 1. privilege block (content_type 17 = faqs, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("17");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title, icon (show image + delete image), small description; rows were drag-sortable)
$grid = array(
'module' => 'faqs', 'key' => 'id_faqs', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'icon', 'small_description'),
'types' => array('icon' => 'image', 'small_description' => 'html'),
'files' => 'uploads/faqs/', 'thumb' => '55x55',
'widths' => array('icon' => 90),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('site_delete_image'), 'url' => 'faqs/delete_image/icon/{icon}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'icon'),
) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('faqs', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
