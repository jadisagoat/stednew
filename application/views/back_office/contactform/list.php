<?php
// 1. privilege block (content_type 36 = contactform, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("36");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. e-mail cell = mailto: link (external '{mailto}' template, see dxgrid 'links')
foreach ($rows as $i => $r) $rows[$i]["mailto"] = trim((string) $r["email"]) !== '' ? 'mailto:' . trim($r["email"]) : '';
// legacy sort link: index/<field>; default = newest message first
$bo_order = $this->uri->segment(4);
if (!$bo_order || $bo_order === 'sort_order') $bo_order = 'created_date desc';
// 3. grid config (legacy columns: name, subject; + e-mail, message, read flag and date of the contact form)
$grid = array(
'module' => 'contactform', 'key' => 'id_contactform', 'rows' => $rows, 'title' => $title,
'order' => $bo_order,
'columns' => array('name', 'email', 'subject', 'comments', 'readed', 'created_date'),
'labels' => array('comments' => bo_label('message'), 'created_date' => bo_label('date')),
'types' => array('email' => 'link', 'comments' => 'html', 'readed' => 'badge', 'created_date' => 'datetime'),
'links' => array('email' => '{mailto}'),
'badges' => array('readed' => array(0 => array(bo_t('grid_unread'), 'label-warning'), 1 => array(bo_label('readed'), 'label-success'))),
'hiding' => array('name' => 200, 'subject' => 190, 'readed' => 180, 'created_date' => 170, 'email' => 160, 'comments' => 150),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => array(
    // read + reply page (messages::read also marks the message as read)
    array('label' => bo_t('grid_view'), 'url' => 'messages/read/{id}', 'icon' => 'bx-envelope-open'),
)),
'add' => $write_priv, 'add_label' => bo_module_label('contactform', 'new'),
'bulk' => $delete_priv, 'reorder' => 0, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
