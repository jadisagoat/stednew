<?php
/* ==========================================================================
   404 PAGE (modern design - backup: 404____28_10_2026.php)
   Loaded on its own by controllers/error404.php (no template), so it links
   its own stylesheets: bootstrap + icons + font + modern.css + pages/content.css
   ========================================================================== */
if (function_exists('set_status_header') && !headers_sent()) set_status_header(404);

$mx_logo = '';
$mx_site = 'STED Training';
$CI =& get_instance();
if (isset($CI->fct)) {
	$mx_info = $CI->fct->getonerow('admin', array('admin_id' => 2));
	if (!empty($mx_info['image']) && is_file('uploads/website/' . $mx_info['image'])) $mx_logo = base_url() . 'uploads/website/' . $mx_info['image'];
	if (!empty($mx_info['website_title'])) $mx_site = $mx_info['website_title'];
}
$mx_site = htmlspecialchars(strip_tags($mx_site), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex">
	<title>Page not found | <?php echo $mx_site; ?></title>
	<link rel="icon" href="<?php echo base_url(); ?>favicon.ico?v=2" type="image/x-icon">
	<link href="<?php echo base_url(); ?>theme_academy/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>theme_academy/fontawesome-free-6.5.1-web/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="<?php echo base_url(); ?>theme_academy/css/modern.css?v=10" rel="stylesheet">
	<link href="<?php echo base_url(); ?>theme_academy/css/pages/content.css?v=1" rel="stylesheet">
</head>

<body class="mx-ui mx-404-page">
	<main class="mx-404">
		<span class="mx-phero-blob mx-phero-blob-1" aria-hidden="true"></span>
		<span class="mx-phero-blob mx-phero-blob-2" aria-hidden="true"></span>
		<span class="mx-phero-grid" aria-hidden="true"></span>

		<div class="container">
			<header class="mx-404-top">
				<a class="mx-404-logo" href="<?php echo base_url(); ?>" aria-label="<?php echo $mx_site; ?> - home">
					<?php if ($mx_logo) { ?>
						<img src="<?php echo $mx_logo; ?>" alt="<?php echo $mx_site; ?>">
					<?php } else { ?>
						<b>STED</b>
					<?php } ?>
				</a>
				<a class="mx-btn mx-btn-glass" href="<?php echo base_url(); ?>contactus">Need help? <i class="fas fa-arrow-right"></i></a>
			</header>
		</div>

		<div class="mx-404-main">
			<div class="container text-center">
				<div class="mx-404-num" aria-hidden="true">
					<span>4</span>
					<span class="mx-404-zero"><i class="fas fa-helmet-safety"></i></span>
					<span>4</span>
				</div>
				<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Error 404</span>
				<h1>This page is off the blueprint</h1>
				<p class="mx-404-text">The page you are looking for doesn't exist, was moved, or the link is incomplete. Let's get you back on track.</p>
				<div class="mx-404-actions">
					<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>">Back to home <i class="fas fa-arrow-right"></i></a>
					<a class="mx-btn mx-btn-glass mx-404-back" href="<?php echo base_url(); ?>">Previous page <i class="fas fa-rotate-left"></i></a>
				</div>
				<ul class="mx-404-links">
					<li><a href="<?php echo base_url(); ?>aboutus"><i class="fas fa-building"></i> About STED</a></li>
					<li><a href="<?php echo base_url(); ?>faqs"><i class="far fa-circle-question"></i> FAQs</a></li>
					<li><a href="<?php echo base_url(); ?>contactus"><i class="far fa-envelope"></i> Contact us</a></li>
				</ul>
			</div>
		</div>

		<footer class="mx-404-foot">&copy; <?php echo date('Y'); ?> <?php echo $mx_site; ?></footer>
	</main>

	<script>
		// "Previous page" goes back when the visitor came from this site, otherwise it stays a link home
		(function() {
			var back = document.querySelector('.mx-404-back');
			if (back && document.referrer && document.referrer.indexOf(location.host) > -1 && history.length > 1) {
				back.addEventListener('click', function(e) { e.preventDefault(); history.back(); });
			} else if (back) {
				back.style.display = 'none';
			}
		})();
	</script>
</body>

</html>
