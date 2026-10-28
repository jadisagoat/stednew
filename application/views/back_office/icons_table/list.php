<?php
// 1. privilege block (content_type 26 = icons table, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("26");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title + home page title of id_home_page, "--" when 0; all_data also prepared the image)
$grid = array(
'module' => 'icons_table', 'key' => 'id_icons_table', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'image', 'id_home_page'),
'types' => array('image' => 'image'),
'files' => 'uploads/icons_table/', 'thumb' => '',
'lookups' => array('id_home_page' => array('home_page', 'title', 'id_home_page')),
'lookups_empty' => bo_t('grid_not_available'),
'widths' => array('image' => 100),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('site_delete_image'), 'url' => 'icons_table/delete_image/image/{image}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'image'),
) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('icons table', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
