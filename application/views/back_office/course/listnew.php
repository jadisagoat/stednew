<?php
// 1. privilege block (content_type 7 = course, same id as the legacy view listnew_legacy.php)
list($read_priv, $write_priv, $delete_priv) = bo_priv("7");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$rows = (isset($info) && is_array($info)) ? $info : array();
// 2. the legacy row links "Content" and "User" (content/lists/<id>, user_course/lists/<id>) as two compact count buttons:
//    nb of units (hover = unit titles, the legacy "Count Units" tooltip) and nb of participants
//    (group_category / content_count / content_tree / user_count computed by course::index())
$h = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$bo_btn = function ($url, $icon, $label, $n, $tip) use ($h) {
    return '<a class="btn btn-sm btn-outline-primary text-nowrap bo-rel" href="' . base_url() . 'back_office/' . $url . '"'
        . ' title="' . str_replace("\n", '&#10;', $h($label . ($tip !== '' ? ":\n" . $tip : ''))) . '" aria-label="' . $h($label) . '">'
        . '<i class="bx ' . $icon . '"></i> ' . (int) $n . '</a>';
};
foreach ($rows as $i => $r) {
    $id = (int) $r["id_course"];
    $rows[$i]["content_count_html"] = $bo_btn('content/lists/' . $id, 'bx-video', bo_module_label('content'),
        isset($r["content_count"]) ? $r["content_count"] : 0, isset($r["content_tree"]) ? (string) $r["content_tree"] : '');
    $rows[$i]["user_count_html"] = $bo_btn('user_course/lists/' . $id, 'bx-group', bo_module_label('user course'),
        isset($r["user_count"]) ? $r["user_count"] : 0, '');
    if (!isset($r["group_category"]) || trim((string) $r["group_category"]) === '') $rows[$i]["group_category"] = '--';
}
$bo_order = $this->uri->segment(4);
// 3. grid config (legacy columns: title, count units, active, minutes, group category, category;
//    legacy row links: Content | User | Edit | Delete)
$grid = array(
'module' => 'course', 'key' => 'id_course', 'rows' => $rows, 'title' => $title,
'order' => ($bo_order ? $bo_order : 'sort_order'),
'columns' => array('title', 'content_count_html', 'user_count_html', 'active', 'hours', 'group_category', 'id_category'),
'labels' => array('content_count_html' => bo_t('st_contents'), 'user_count_html' => bo_module_label('user course'), 'hours' => bo_t('grid_minutes', 'Minutes'),
                  'group_category' => bo_label('group_category')),
'types' => array('content_count_html' => 'raw', 'user_count_html' => 'raw', 'active' => 'status', 'hours' => 'number'),
'lookups' => array('id_category' => array('category', 'title', 'id_category')),
'lookups_empty' => '--',
'widths' => array('content_count_html' => 120, 'user_count_html' => 135, 'hours' => 100),
'align' => array('content_count_html' => 'center', 'user_count_html' => 'center'),
'hiding' => array('title' => 200, 'content_count_html' => 190, 'user_count_html' => 185, 'id_category_text' => 180, 'group_category' => 170, 'active' => 160, 'hours' => 150),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => bo_module_label('course', 'new'),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4b. same tree as the old list: Group Category > Category > courses, expanded, with "(Course: n)" per group.
//     Runs after dxgrid's own $(function) (registered first), so boGrid.grid exists.
?>
<script>
$(function () {
  var g = boGrid && boGrid.grid;
  if (!g) return;
  g.beginUpdate();
  g.columnOption('group_category', 'groupIndex', 0);
  g.columnOption('id_category_text', 'groupIndex', 1);
  g.option('grouping.autoExpandAll', true);
  g.option('summary', { groupItems: [{ column: 'title', summaryType: 'count', displayFormat: <?= bo_js(bo_module_label('course') . ': {0}') ?> }] });
  g.endUpdate();
});
</script>
<?php
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
