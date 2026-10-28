<?php
// 1. privilege block (content_type 42 = user_play_video; the legacy view had an empty $id_content, which denied every
//    level-0 user: 42 is the id the sidebar uses for this module)
list($read_priv, $write_priv, $delete_priv) = bo_priv("42");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
$bo_order = $this->uri->segment(4);
// 2. grid config (legacy columns: user, video; + the date of the view)
// The table is small (the front office never writes to it), so the rows stay inline.
$grid = array(
'module' => 'user_play_video', 'key' => 'id_user_play_video', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('id_user', 'id_video', 'created_date'),
'labels' => array('id_user' => bo_label('user'), 'id_video' => bo_label('video'), 'created_date' => bo_label('date')),
'types' => array('created_date' => 'datetime'),
'lookups' => array('id_user' => array('user', 'name', 'id_user'), 'id_video' => array('video', 'title', 'id_video')),
'lookups_empty' => bo_t('grid_not_available'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('user play video', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
