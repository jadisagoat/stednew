<?php
// 1. privilege block (content_type 4 = client, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("4");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// legacy sort links: index/title | email | profession | resident | phone
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title, email, profession, nationality, resident, phone, gender, role, image (+ delete image))
// the password column is never shown nor sent (dxgrid strips sensitive fields)
$grid = array(
'module' => 'client', 'key' => 'id_client', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('title', 'email', 'profession', 'id_countries', 'resident', 'phone', 'id_gender', 'id_role', 'image'),
'labels' => array('id_countries' => bo_label('nationality')),
'types' => array('image' => 'image'),
'files' => 'uploads/client/', 'thumb' => '45x45',
'lookups' => array('id_countries' => array('countries', 'title', 'id_countries'), 'id_gender' => array('gender', 'title', 'id_gender'),
                   'id_role' => array('role', 'title', 'id_role')),
'lookups_empty' => bo_t('grid_not_available'),
'widths' => array('image' => 90),
'hiding' => array('title' => 200, 'email' => 190, 'phone' => 180, 'id_role' => 170, 'image' => 160, 'profession' => 150,
                  'id_countries' => 140, 'resident' => 130, 'id_gender' => 120),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => ($write_priv == 1) ? array(
    array('label' => bo_t('site_delete_image'), 'url' => 'client/delete_image/image/{image}/{id}', 'icon' => 'bx-image-alt',
          'confirm' => bo_t('grid_confirm_delete_image'), 'if' => 'image'),
) : array()),
'add' => $write_priv, 'add_label' => bo_module_label('client', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
