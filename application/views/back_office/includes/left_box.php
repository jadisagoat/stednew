<?php if (defined('BO_MENU_RENDERED')) return; define('BO_MENU_RENDERED', 1);
/* Vertical menu (Sneat, AmCham design). Included once by template.php; the per-view includes inside the legacy views
   return immediately. STED: the groups come from the "menu" table (title + icon), exactly like the old menu:
   content_type position 2 with an id_menu, ordered by menu.sort_order then content_type.sort_order, filtered by
   the read privilege of the user's role. */
$query = 'select content_type.*, menu.icon as menuicon, menu.title as menutitle, menu.id_menu as menuid from content_type
 left join menu on menu.id_menu = content_type.id_menu
 where content_type.position = 2 and content_type.deleted = 0 and menu.deleted = 0
 order by menu.sort_order, content_type.sort_order';
$menu_left = $this->fct->search($query);
$menu_left_seg1 = $this->uri->segment(2);
$menu_left_seg2 = $this->uri->segment(3);
$id_role = $this->session->userdata('id_role');
$bo_level = (int) $this->session->userdata('level');
$cond = array('admin_id' => 2);
$title_site = $this->fct->getonerow('admin', $cond);

// drill-down modules highlight their parent entry
$bo_alias = array(
'sub_category' => 'category', 'user_course' => 'course', 'user_content' => 'content', 'video' => 'content',
'quiz' => 'exam', 'user_favorite' => 'course', 'user_feedback' => 'user_course_rating', 'usercourserating' => 'user_course_rating',
);
$bo_cur = isset($bo_alias[$menu_left_seg1]) ? $bo_alias[$menu_left_seg1] : $menu_left_seg1;

$bo_groups = array();       // id_menu => array('label', 'icon', 'items')
foreach ($menu_left as $val) {
    if ($bo_level == 0) {
        $cond = array('id_role' => $id_role, 'id_content' => $val["id_content"]);
        $read_priv = $this->fct->getonecell('priviledge', 'read_p', $cond);
    } else { $read_priv = 1; }
    if ($read_priv != 1) continue;
    $gid = (int) $val["menuid"];
    if (!isset($bo_groups[$gid])) {
        $gt = trim((string) $val["menutitle"]);
        $lg = bo_legacy($gt);
        $bo_groups[$gid] = array('label' => $lg !== null ? $lg : $gt, 'icon' => $val["menuicon"] ? $val["menuicon"] : 'bx-grid', 'items' => array());
    }
    $used = $val["used_name"] ? $val["used_name"] : str_replace(" ", "_", $val["name"]);
    $e = bo_menu_entry($val["name"]);
    if (!$e) $e = bo_menu_entry($used);
    $bo_groups[$gid]['items'][] = array(
        'used' => $used, 'url' => ($e && isset($e['url'])) ? $e['url'] : $used,
        'url_add' => ($e && isset($e['url_add'])) ? $e['url_add'] : $used . '/add',
        'icon' => $e ? $e['icon'] : 'bx-grid', 'kind' => $e ? $e['kind'] : 'submenu',
        'label' => bo_module_label($e ? $val["name"] : $used), 'list' => bo_module_label($e ? $val["name"] : $used, 'list'),
        'new' => bo_module_label($e ? $val["name"] : $used, 'new'),
        'active' => ($bo_cur == $used),
    );
}
// System group (hard-coded pages, same rules as the navbar)
$cond = array('rules' => 'USERS');
$user_rule = $this->fct->getonecell('admin_rules', 'active', $cond);
$bo_sys = array();
$bo_sys[] = array('used' => 'settings', 'url' => 'settings', 'icon' => 'bx-cog', 'kind' => 'direct', 'label' => bo_t('nav_settings'), 'active' => ($bo_cur == 'settings'));
if (($user_rule == 1 && $bo_level != 0) || $bo_level == 1) {
    $bo_sys[] = array('used' => 'user', 'url' => 'user', 'url_add' => 'user/add', 'icon' => 'bx-user', 'kind' => 'submenu', 'label' => bo_module_label('user'), 'list' => bo_module_label('user', 'list'), 'new' => bo_module_label('user', 'new'), 'active' => ($bo_cur == 'user'));
    $bo_sys[] = array('used' => 'role', 'url' => 'role', 'url_add' => 'role/add', 'icon' => 'bx-shield-quarter', 'kind' => 'submenu', 'label' => bo_module_label('role'), 'list' => bo_module_label('role', 'list'), 'new' => bo_module_label('role', 'new'), 'active' => ($bo_cur == 'role'));
}
if ($bo_level == 1) {
    $bo_sys[] = array('used' => 'control', 'url' => 'control', 'icon' => 'bx-wrench', 'kind' => 'direct', 'label' => bo_t('nav_content_types'), 'active' => ($bo_cur == 'control'));
}
$bo_groups['system'] = array('label' => bo_t('group_system'), 'icon' => 'bx-cog', 'items' => $bo_sys);
// pages reached from the dashboard that have no menu entry of their own highlight "Dashboard"
$bo_any_active = false;
foreach ($bo_groups as $g) { foreach ($g['items'] as $it) { if (!empty($it['active'])) $bo_any_active = true; } }
$bo_dash_active = ($menu_left_seg1 == 'home') || !$bo_any_active;
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
<div class="app-brand demo">
<a href="<?= base_url() ?>back_office/home/dashboard" class="app-brand-link">
<span class="app-brand-logo demo"><img src="<?= base_url() ?>uploads/website/45x45/<?= $title_site["image"] ?>" width="40" height="40" alt="" onerror="this.onerror=null;this.src='<?= base_url() ?>uploads/website/<?= $title_site["image"] ?>';" /></span>
<span class="app-brand-text demo menu-text fw-bold ms-2 bo-brand"><?= htmlspecialchars((string) $title_site["website_title"], ENT_QUOTES, 'UTF-8') ?></span>
</a>
<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none"><i class="bx bx-chevron-left bx-sm align-middle"></i></a>
</div>
<div class="menu-inner-shadow"></div>
<ul class="menu-inner py-1">
<li class="menu-item <?= $bo_dash_active ? 'active' : '' ?>">
<a href="<?= base_url() ?>back_office/home/dashboard" class="menu-link"><i class="menu-icon tf-icons bx bx-home-circle"></i><div><?= bo_t('nav_dashboard') ?></div></a>
</li>
<?php foreach ($bo_groups as $g) { if (!count($g['items'])) continue; ?>
<li class="menu-header small text-uppercase"><span class="menu-header-text"><?= htmlspecialchars($g['label'], ENT_QUOTES, 'UTF-8') ?></span></li>
<?php /* one direct link per module to its list page (the list has the Add button); stays active on add/edit */
foreach ($g['items'] as $it) { ?>
<li class="menu-item <?= $it['active'] ? 'active' : '' ?>">
<a href="<?= base_url() ?>back_office/<?= $it['url'] ?>" class="menu-link"><i class="menu-icon tf-icons bx <?= $it['icon'] ?>"></i><div><?= $it['label'] ?></div></a>
</li>
<?php } } ?>
</ul>
</aside>
