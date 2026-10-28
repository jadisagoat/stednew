<?php
// 1. privilege block: content_type 33 = user_course (the legacy view list_legacy.php had an empty $id_content,
//    which denied every level-0 user and showed Edit / Delete to everyone else)
list($read_priv, $write_priv, $delete_priv) = bo_priv("33");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. drill-down context: participants of ONE course (id_course kept in the session by user_course::lists($id_course),
//    reached from the course list "User" link); parent chain = Courses > <course title>
$bo_id_course = (int) $this->session->userdata("id_course");
$bo_chain = array(array('label' => bo_module_label('course'), 'url' => 'course'));
$bo_toolbar = array();
if ($bo_id_course > 0) {
    $bo_course = trim((string) $this->fct->getonecell("course", "title", array("id_course" => $bo_id_course)));
    if ($bo_course !== '') $bo_chain[] = array('label' => $bo_course);
    // sibling drill-down of the same course (legacy course list "Content" link)
    $bo_toolbar[] = array('label' => bo_module_label('content'), 'url' => 'content/lists/' . $bo_id_course, 'icon' => 'bx-video');
}
// 3. grid config (legacy columns: user, course, role - the legacy grid grouped by course then role;
//    the group panel is still available to group by any column)
$grid = array(
'module' => 'user_course', 'key' => 'id_user_course', 'rows' => $rows, 'title' => 'List user course',
'subtitle' => $bo_chain, 'toolbar' => $bo_toolbar,
'order' => 'id_user',
'columns' => array('id_user', 'id_course', 'id_role'),
'lookups' => array('id_user' => array('user', 'name', 'id_user'), 'id_course' => array('course', 'title', 'id_course'),
                   'id_role' => array('role', 'title', 'id_role')),
'lookups_empty' => '--',
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('user course', 'new'),
'bulk' => $delete_priv, 'reorder' => 0, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
