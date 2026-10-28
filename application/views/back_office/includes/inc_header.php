<?php $bo_base = base_url(); ?>
<link rel="icon" type="image/x-icon" href="<?= $bo_base ?>sted_theme/assets/img/favicon/favicon.ico" />
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/fonts/boxicons.css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/css/rtl/theme-bordered.css" class="template-customizer-theme-css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="<?= $bo_base ?>lib/devexpress/dx.light.css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/css/style.css" />
<link rel="stylesheet" href="<?= $bo_base ?>sted_theme/assets/css/demo.css" />
<link rel="stylesheet" href="<?= $bo_base ?>css/datepicker.css" />
<link rel="stylesheet" href="<?= $bo_base ?>css/admin_legacy.css" />
<script src="<?= $bo_base ?>lib/devexpress/jquery-3.4.1.min.js"></script>
<script src="<?= $bo_base ?>lib/devexpress/exceljs.min.js"></script>
<script src="<?= $bo_base ?>lib/devexpress/FileSaver.min.js"></script>
<script src="<?= $bo_base ?>lib/devexpress/jspdf.umd.js"></script>
<script src="<?= $bo_base ?>lib/devexpress/jspdf.plugin.autotable.js"></script>
<script src="<?= $bo_base ?>lib/devexpress/dx.all.js"></script>
<script src="<?= $bo_base ?>lib/devexpress/ar.js"></script>
<script>
var BO_LANG = '<?= bo_lang() ?>', BO_RTL = <?= bo_rtl() ? 'true' : 'false' ?>, BO_BASE = '<?= $bo_base ?>',
    BO_I18N = <?= json_encode(bo_js_dict(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
if (window.DevExpress) { DevExpress.localization.locale(BO_LANG); DevExpress.config({ rtlEnabled: BO_RTL }); }
</script>
