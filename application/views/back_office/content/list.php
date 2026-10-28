<?php
// 1. privilege block (content_type 8 = content, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("8");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. drill-down context: contents of ONE course (id_course kept in the session by content::lists($id_course),
//    reached from the course list "Content" link); parent chain = Courses > <course title>
$bo_id_course = (int) $this->session->userdata("id_course");
$bo_chain = array(array('label' => bo_module_label('course'), 'url' => 'course'));
$bo_toolbar = array();
if ($bo_id_course > 0) {
    $bo_course = trim((string) $this->fct->getonecell("course", "title", array("id_course" => $bo_id_course)));
    if ($bo_course !== '') $bo_chain[] = array('label' => $bo_course);
    // sibling drill-down of the same course (legacy course list "User" link)
    $bo_toolbar[] = array('label' => bo_module_label('user course'), 'url' => 'user_course/lists/' . $bo_id_course, 'icon' => 'bx-group');
}
// 3. grid config (legacy columns: title, title page, course, minutes, type, image, file, link)
$grid = array(
'module' => 'content', 'key' => 'id_content', 'rows' => $rows, 'title' => $title,
'subtitle' => $bo_chain, 'toolbar' => $bo_toolbar,
'order' => 'sort_order',
'columns' => array('title', 'title_page', 'id_course', 'hours', 'id_type', 'image', 'file', 'link'),
'labels' => array('hours' => bo_t('grid_minutes', 'Minutes')),
'types' => array('hours' => 'number', 'image' => 'image', 'file' => 'file', 'link' => 'link'),
'links' => array('link' => array('url' => '{value}', 'target' => '_blank')),
'files' => 'uploads/content/', 'thumb' => '',
'lookups' => array('id_course' => array('course', 'title', 'id_course'), 'id_type' => array('type', 'title', 'id_type')),
'lookups_empty' => '--',
'widths' => array('hours' => 100, 'id_type' => 100, 'image' => 100, 'file' => 120),
'hiding' => array('title' => 200, 'hours' => 190, 'id_type_text' => 185, 'file' => 180, 'link' => 170, 'image' => 160, 'title_page' => 150, 'id_course_text' => 140),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('content', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
