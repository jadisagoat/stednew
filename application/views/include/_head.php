<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!-- SEO: title, description, canonical, robots, hreflang, social cards, JSON-LD (application/helpers/seo_helper.php) -->
<?php get_instance()->load->helper('seo'); seo_head(get_defined_vars()); ?>

<!-- FAVICON AND TOUCH ICONS  -->
<?php /* Favicon: theme_academy/images/favicon.ico is the theme's yellow placeholder - use the STED favicon.ico from the site root */ ?>
<link rel="shortcut icon" href="<?php echo base_url() ?>favicon.ico?v=2" type="image/x-icon">
<link rel="icon" href="<?php echo base_url() ?>favicon.ico?v=2" type="image/x-icon">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url() ?>theme_academy/images/Sted-ltd-Favicon.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url() ?>theme_academy/images/Sted-ltd-Favicon.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url() ?>theme_academy/images/Sted-ltd-Favicon.png">
<link rel="apple-touch-icon" href="<?php echo base_url() ?>theme_academy/images/Sted-ltd-Favicon.png">

<!-- BOOTSTRAP CSS -->
<link href="<?php echo base_url() ?>theme_academy/css/bootstrap.min.css" rel="stylesheet">

<!-- FONT ICONS -->


<link href="<?php echo base_url() ?>theme_academy/fontawesome-free-6.5.1-web/css/all.min.css" rel="stylesheet" crossorigin="anonymous">

<?php /* Performance: theme_academy/css/all.css does not exist - each page load triggered an extra PHP 404 request */ ?>
<link href="<?php echo base_url() ?>theme_academy/css/flaticon.css" rel="stylesheet">

<!-- PLUGINS STYLESHEET -->
<link href="<?php echo base_url() ?>theme_academy/css/menu.css" rel="stylesheet">
<link id="effect" href="<?php echo base_url() ?>theme_academy/css/dropdown-effects/fade-down.css" media="all" rel="stylesheet">
<link href="<?php echo base_url() ?>theme_academy/css/magnific-popup.css" rel="stylesheet">
<link href="<?php echo base_url() ?>theme_academy/css/flexslider.css" rel="stylesheet">
<link href="<?php echo base_url() ?>theme_academy/css/owl.carousel.min.css" rel="stylesheet">
<link href="<?php echo base_url() ?>theme_academy/css/owl.theme.default.min.css" rel="stylesheet">

<!-- ON SCROLL ANIMATION -->
<link href="<?php echo base_url() ?>theme_academy/css/animate.css" rel="stylesheet">

<!-- TEMPLATE CSS -->
<link href="<?php echo base_url() ?>theme_academy/css/style.css" rel="stylesheet">

<!-- RESPONSIVE CSS -->
<link href="<?php echo base_url() ?>theme_academy/css/responsive.css" rel="stylesheet">

<!-- MODERN UI LAYER (fonts + design overrides) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<link href="<?php echo base_url() ?>theme_academy/css/modern.css?v=27" rel="stylesheet">
<!-- Modern page groups (one file per group of pages) -->
<link href="<?php echo base_url() ?>theme_academy/css/pages/course.css?v=4" rel="stylesheet">
<link href="<?php echo base_url() ?>theme_academy/css/pages/content.css?v=3" rel="stylesheet">
<link href="<?php echo base_url() ?>theme_academy/css/pages/account.css?v=4" rel="stylesheet">
<link href="<?php echo base_url() ?>theme_academy/css/pages/exams.css?v=2" rel="stylesheet">
<link href="<?php echo base_url() ?>theme_academy/css/pages/manage.css?v=2" rel="stylesheet">
