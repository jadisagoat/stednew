<?php
// 1. privilege block (content_type 25 = home page, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("25");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy grid showed the title; its all_data also prepared the image => shown as a thumbnail)
$grid = array(
'module' => 'home_page', 'key' => 'id_home_page', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'image'),
'types' => array('image' => 'image'),
'files' => 'uploads/home_page/', 'thumb' => '',
'widths' => array('image' => 140),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('site_delete_image'), 'url' => 'home_page/delete_image/image/{image}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'image'),
) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('home page', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
