<?php
$cond = array('admin_id' => 2);
$title_site = $this->fct->getonerow('admin', $cond);
$seg = $this->uri->segment(2);
$bo_level = (int) $this->session->userdata('level');
$bo_user_name = (string) $this->session->userdata('user_name');
$bo_initial = function_exists('mb_substr') ? mb_substr($bo_user_name, 0, 1, 'UTF-8') : substr($bo_user_name, 0, 1);
$bo_other = bo_lang() === 'ar' ? 'en' : 'ar';
// Users + roles: same rule as the old STED navbar (USERS admin rule for admins, always for level 1)
$cond = array('rules' => 'USERS');
$user_rule = $this->fct->getonecell('admin_rules', 'active', $cond);
$bo_show_users = (($user_rule == 1 && $bo_level != 0) || $bo_level == 1);
?>
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
<div class="container-fluid">
<?php // sidebar toggle on EVERY screen size (was d-xl-none: desktop had no way to collapse the menu).
// Sneat's main1.js binds the click on .layout-menu-toggle: small screens open/close the off-canvas menu,
// desktop collapses it to icons and remembers the state in localStorage (config.enableMenuLocalStorage). ?>
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-2">
<button type="button" class="nav-item nav-link px-0 bo-menu-toggle" id="bo-menu-toggle" aria-controls="layout-menu" aria-expanded="true"
 title="<?= bo_t('nav_menu_collapse') ?>" aria-label="<?= bo_t('nav_menu_collapse') ?>"
 data-label-collapse="<?= bo_t('nav_menu_collapse') ?>" data-label-expand="<?= bo_t('nav_menu_expand') ?>"><i class="bx bx-menu bx-sm" aria-hidden="true"></i></button>
</div>
<script>
// keep the button's name/state in sync with the menu (for screen readers and the tooltip)
document.addEventListener('DOMContentLoaded', function () {
  var b = document.getElementById('bo-menu-toggle'); if (!b || !window.Helpers) return;
  function sync() {
    var collapsed = window.Helpers.isCollapsed();
    var label = b.getAttribute(collapsed ? 'data-label-expand' : 'data-label-collapse');
    b.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    b.setAttribute('aria-label', label); b.setAttribute('title', label);
    b.classList.toggle('is-collapsed', collapsed);
  }
  sync(); window.addEventListener('layouttoggle', sync); window.addEventListener('resize', sync);
});
</script>
<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
<?php if ($seg != "home") { ?>
<a href="javascript:history.go(-1)" class="btn btn-sm btn-outline-secondary bo-back me-2" title="<?= bo_t('nav_back') ?>"><i class="bx bx-arrow-back"></i><span class="d-none d-md-inline ms-1"><?= bo_t('nav_back') ?></span></a>
<?php } ?>
<a href="<?= base_url() ?>back_office/home/dashboard" class="bo-brand-text fw-bold d-none d-sm-inline d-xl-none"><?= htmlspecialchars((string) $title_site["website_title"], ENT_QUOTES, 'UTF-8') ?></a>
<ul class="navbar-nav flex-row align-items-center ms-auto">
<li class="nav-item me-2 me-xl-1">
<a class="nav-link bo-lang-toggle" href="<?= base_url() ?>back_office/home/lang/<?= $bo_other ?>" title="<?= bo_t('nav_lang_name') ?>"><i class="bx bx-globe me-1"></i><?= bo_t('nav_lang_switch') ?></a>
</li>
<li class="nav-item me-2 me-xl-1">
<a class="nav-link" href="<?= base_url() ?>" target="_blank" title="<?= bo_t('nav_website') ?>" aria-label="<?= bo_t('nav_website') ?>"><i class="bx bx-link-external"></i></a>
</li>
<li class="nav-item navbar-dropdown dropdown-user dropdown">
<a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
<div class="avatar avatar-online bo-avatar"><?= htmlspecialchars(strtoupper($bo_initial), ENT_QUOTES, 'UTF-8') ?></div>
</a>
<ul class="dropdown-menu dropdown-menu-end">
<li>
<a class="dropdown-item" href="javascript:void(0);">
<div class="d-flex">
<div class="flex-shrink-0 me-3"><div class="avatar avatar-online bo-avatar"><?= htmlspecialchars(strtoupper($bo_initial), ENT_QUOTES, 'UTF-8') ?></div></div>
<div class="flex-grow-1">
<span class="fw-medium d-block"><?= bo_t('nav_hello') ?> <?= htmlspecialchars($bo_user_name, ENT_QUOTES, 'UTF-8') ?></span>
<small class="text-muted"><b><?= bo_t('nav_last_login') ?>:</b> <bdi dir="ltr"><?= htmlspecialchars(bo_login_date($this->session->userdata('login_date')), ENT_QUOTES, 'UTF-8') ?></bdi></small>
</div>
</div>
</a>
</li>
<li><div class="dropdown-divider"></div></li>
<li><a class="dropdown-item" href="<?= base_url() ?>back_office/home/dashboard"><i class="bx bx-home-circle me-2"></i><span class="align-middle"><?= bo_t('nav_dashboard') ?></span></a></li>
<li><a class="dropdown-item" href="<?= base_url() ?>back_office/settings"><i class="bx bx-cog me-2"></i><span class="align-middle"><?= bo_t('nav_settings') ?></span></a></li>
<?php if ($bo_show_users) { ?>
<li><a class="dropdown-item" href="<?= base_url() ?>back_office/user"><i class="bx bx-user me-2"></i><span class="align-middle"><?= bo_module_label('user', 'list') ?></span></a></li>
<li><a class="dropdown-item" href="<?= base_url() ?>back_office/role"><i class="bx bx-shield-quarter me-2"></i><span class="align-middle"><?= bo_module_label('role') ?></span></a></li>
<?php } ?>
<?php if ($bo_level == 1) { ?>
<li><a class="dropdown-item" href="<?= base_url() ?>back_office/control"><i class="bx bx-wrench me-2"></i><span class="align-middle"><?= bo_t('nav_content_types') ?></span></a></li>
<?php } ?>
<li><div class="dropdown-divider"></div></li>
<li><a class="dropdown-item" href="<?= base_url() ?>back_office/home/logout"><i class="bx bx-power-off me-2"></i><span class="align-middle"><?= bo_t('nav_logout') ?></span></a></li>
</ul>
</li>
</ul>
</div>
</div>
</nav>
