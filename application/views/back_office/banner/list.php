<?php
// 1. privilege block (content_type 15 = banner, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("15");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 3. grid config
$grid = array(
'module' => 'banner', 'key' => 'id_banner', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'image', 'url'),
'types' => array('image' => 'image', 'url' => 'link'),
'links' => array('url' => array('url' => '{value}', 'target' => '_blank')),
'files' => 'uploads/banner/', 'thumb' => '',
'widths' => array('image' => 140),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('site_delete_image'), 'url' => 'banner/delete_image/image/{image}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'image'),
) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('banner', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
