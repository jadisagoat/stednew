<?php
// 1. privilege block (content_type 5 = category, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("5");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. legacy "Count Courses" cell: the count, hovering shows the courses (nb of contents) of the category
//    (course_count / course_tree computed by category::index())
$h = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
foreach ($rows as $i => $r) {
    $tree = isset($r["course_tree"]) ? (string) $r["course_tree"] : '';
    $rows[$i]["course_count_html"] = '<span class="bo-count"' . ($tree !== '' ? ' title="' . str_replace("\n", '&#10;', $h($tree)) . '" style="cursor:help;border-bottom:1px dotted"' : '') . '>'
        . (int) (isset($r["course_count"]) ? $r["course_count"] : 0) . '</span>';
}
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title, count courses, group category, active, is in footer, section)
$grid = array(
'module' => 'category', 'key' => 'id_category', 'rows' => $rows, 'title' => $title,
'order' => ($bo_order ? $bo_order : 'sort_order'),
'columns' => array('title', 'course_count_html', 'id_group_category', 'active', 'is_in_footer', 'id_home_sections'),
'labels' => array('course_count_html' => bo_label('courses_nbr')),
'types' => array('course_count_html' => 'raw', 'active' => 'status', 'is_in_footer' => 'yesno'),
'lookups' => array('id_group_category' => array('group_category', 'title', 'id_group_category'),
                   'id_home_sections' => array('home_sections', 'title', 'id_home_sections')),
'lookups_empty' => '--',
'widths' => array('course_count_html' => 110),
'align' => array('course_count_html' => 'center'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('category', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
