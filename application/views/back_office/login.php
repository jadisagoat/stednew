<?php
$cond = array('admin_id' => 2);
$this->db->select('image,website_title');
$title_site = $this->fct->getonerow('admin', $cond);
$bo_base = base_url();
$bo_other = bo_lang() === 'ar' ? 'en' : 'ar';
?>
<!DOCTYPE html>
<html lang="<?= bo_lang() ?>" dir="<?= bo_rtl() ? 'rtl' : 'ltr' ?>" class="light-style" data-theme="theme-bordered" data-assets-path="<?= $bo_base ?>sted_theme/assets/" data-template="vertical-menu-template-free">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= htmlspecialchars((string) $title_site["website_title"], ENT_QUOTES, 'UTF-8') ?> - <?= bo_t('login_title') ?></title>
<link rel="icon" type="image/x-icon" href="<?= $bo_base ?>sted_theme/assets/img/favicon/favicon.ico" />
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/fonts/boxicons.css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/css/rtl/theme-bordered.css" class="template-customizer-theme-css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/css/pages/page-auth.css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/css/demo.css" />
<link rel="stylesheet" href="<?= $bo_base ?>css/admin_legacy.css" />
</head>
<body>
<div class="container-xxl">
<div class="authentication-wrapper authentication-basic container-p-y">
<div class="authentication-inner">
<div class="card">
<div class="card-body">
<div class="app-brand justify-content-center mb-4">
<a href="<?= $bo_base ?>back_office/home" class="app-brand-link gap-2">
<span class="app-brand-logo"><img src="<?= $bo_base ?>uploads/website/45x45/<?= $title_site["image"] ?>" width="45" height="45" alt="" /></span>
<span class="app-brand-text demo text-body fw-bold bo-brand"><?= htmlspecialchars((string) $title_site["website_title"], ENT_QUOTES, 'UTF-8') ?></span>
</a>
</div>
<h4 class="mb-1"><?= bo_t('login_welcome') ?></h4>
<p class="mb-4"><?= bo_t('login_intro') ?></p>
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<?php if ($this->session->userdata('login_error')) {
    echo '<div class="alert alert-danger">' . htmlspecialchars(bo_t('login_error'), ENT_QUOTES, 'UTF-8') . '</div>';
} ?>
<form action="<?= $bo_base ?>back_office/home/login_validate" method="post" name="login-form" class="mb-3">
<div class="mb-3">
<label for="bo-username" class="form-label"><?= bo_t('login_username') ?></label>
<input type="text" class="form-control" id="bo-username" name="username" value="<?= set_value('username'); ?>" placeholder="<?= bo_t('login_username') ?>" autofocus />
</div>
<div class="mb-3 form-password-toggle">
<label class="form-label" for="bo-password"><?= bo_t('login_password') ?></label>
<div class="input-group input-group-merge">
<input type="password" id="bo-password" class="form-control" name="pass" value="<?= set_value('pass'); ?>" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
</div>
</div>

<div class="mb-3">
<button type="submit" class="btn btn-primary d-grid w-100"><?= bo_t('login_submit') ?></button>
</div>
</form>
<p class="text-center mb-0">
<a href="<?= $bo_base ?>back_office/home/lang/<?= $bo_other ?>"><i class="bx bx-globe"></i> <?= bo_t('nav_lang_switch') ?></a>
</p>
</div>
</div>
</div>
</div>
</div>
<script src="<?= $bo_base ?>lib/devexpress/jquery-3.4.1.min.js"></script>
<script src="<?= $bo_base ?>sted_theme/assets/vendor/js/helpers.js"></script>
<script src="<?= $bo_base ?>sted_theme/assets/vendor/js/bootstrap.js"></script>
<script>if (window.Helpers && Helpers.initPasswordToggle) Helpers.initPasswordToggle();</script>
</body>
</html>
<?php $this->session->unset_userdata('login_error'); ?>
