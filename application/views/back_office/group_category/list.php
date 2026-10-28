<?php
// 1. privilege block (content_type 52 = group category, same id as the legacy view list_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("52");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. legacy "Count Categories" cell: the count, hovering shows the categories > courses (nb of contents) tree
//    (category_count / category_tree computed by group_category::index())
$h = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
foreach ($rows as $i => $r) {
    $tree = isset($r["category_tree"]) ? (string) $r["category_tree"] : '';
    $rows[$i]["category_count_html"] = '<span class="bo-count"' . ($tree !== '' ? ' title="' . str_replace("\n", '&#10;', $h($tree)) . '" style="cursor:help;border-bottom:1px dotted"' : '') . '>'
        . (int) (isset($r["category_count"]) ? $r["category_count"] : 0) . '</span>';
}
$bo_order = $this->uri->segment(4);
// 3. grid config
$grid = array(
'module' => 'group_category', 'key' => 'id_group_category', 'rows' => $rows, 'title' => $title,
'order' => ($bo_order ? $bo_order : 'sort_order'),
'columns' => array('title', 'category_count_html', 'active'),
'labels' => array('category_count_html' => bo_module_label('category')),
'types' => array('category_count_html' => 'raw', 'active' => 'status'),
'widths' => array('category_count_html' => 120),
'align' => array('category_count_html' => 'center'),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('group category', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
