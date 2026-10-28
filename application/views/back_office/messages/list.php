<?php
// 1. privilege block: the legacy list (list_legacy.php) had no privilege check, every admin could manage it
$read_priv = 1; $write_priv = 1; $delete_priv = 1;
// rows = contactform messages (index: all, unread(): readed = 0, opened(): readed = 1), newest first
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. e-mail cell = mailto: link (legacy), via the external '{mailto}' link template
foreach ($rows as $i => $r) $rows[$i]["mailto"] = trim((string) $r["email"]) !== '' ? 'mailto:' . trim($r["email"]) : '';
// legacy breadcrumb buttons: All / Unread / Archived messages (current one in bold)
$bo_view = $this->uri->segment(3);
// 3. grid config
$grid = array(
'module' => 'messages', 'key' => 'id_contactform', 'rows' => $rows, 'title' => $title,
'order' => 'created_date desc',
'columns' => array('subject', 'name', 'email', 'readed', 'created_date'),
'labels' => array('created_date' => bo_label('date')),
'types' => array('email' => 'link', 'readed' => 'badge', 'created_date' => 'datetime'),
'links' => array('email' => '{mailto}'),
// legacy envelope icon (closed = unread, opened = read)
'badges' => array('readed' => array(0 => array(bo_t('grid_unread'), 'label-warning'), 1 => array(bo_label('readed'), 'label-success'))),
'hiding' => array('subject' => 200, 'readed' => 190, 'created_date' => 180, 'email' => 170, 'name' => 160),
'actions' => array('edit' => 0, 'delete' => $delete_priv, 'extra' => array(
    // legacy "Read" link: messages::read shows the message + reply form and marks it as read
    array('label' => bo_t('grid_view'), 'url' => 'messages/read/{id}', 'icon' => 'bx-envelope-open'),
)),
'toolbar' => array(
    array('label' => bo_t('grid_all'), 'url' => 'messages', 'icon' => 'bx-envelope', 'active' => ($bo_view == '' || $bo_view == 'index')),
    array('label' => bo_t('grid_unread'), 'url' => 'messages/unread', 'icon' => 'bx-envelope', 'active' => ($bo_view == 'unread')),
    array('label' => bo_t('grid_archived'), 'url' => 'messages/opened', 'icon' => 'bx-envelope-open', 'active' => ($bo_view == 'opened')),
),
'add' => 0,
'bulk' => $delete_priv, 'reorder' => 0, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
