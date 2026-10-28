<?php
if(!$this->session->userdata('user_id'))
redirect(base_url().'back_office/home');
$bo_page_title = isset($title) ? bo_page_title($title) : '';
?>
<!DOCTYPE html>
<html lang="<?= bo_lang() ?>" dir="<?= bo_rtl() ? 'rtl' : 'ltr' ?>" class="light-style layout-navbar-fixed layout-menu-fixed" data-theme="theme-bordered" data-assets-path="<?= base_url() ?>sted_theme/assets/" data-template="vertical-menu-template-free">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= htmlspecialchars($bo_page_title, ENT_QUOTES, 'UTF-8') ?></title>
<?php $this->load->view('back_office/includes/inc_header'); ?>
</head>
<body class="dx-viewport">
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
<div class="layout-container">
<?php $this->load->view('back_office/includes/left_box'); ?>
<div class="layout-page">
<?php $this->load->view('back_office/includes/header'); ?>
<div class="content-wrapper">
<div class="container-fluid flex-grow-1 container-p-y bo-content">
<?php $this->load->view($content); ?>
</div>
<?php $this->load->view('back_office/includes/footer'); ?>
<div class="content-backdrop fade"></div>
</div>
</div>
</div>
<div class="layout-overlay layout-menu-toggle"></div>
<div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->
<div id="popup-image" class="popup-image"><span class="close-button">&times;</span><img src="" alt=""></div>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog"><div class="modal-content"></div></div>
</div>
<div id="sample-modal" class="d-none"></div>
</body>
</html>
