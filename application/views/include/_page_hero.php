<?php
/* ==========================================================================
   MODERN INNER-PAGE HERO (shared by inner pages)
   Variables (all optional except $ph_title):
     $ph_title    string   page title (plain text)
     $ph_eyebrow  string   small label above the title
     $ph_text     string   intro text (HTML is stripped)
     $ph_crumbs   array    [ ['label' => 'Home', 'url' => '...'], ['label' => 'Current'] ]
     $ph_stats    array    [ ['icon' => 'fas fa-book', 'value' => '12', 'label' => 'Courses'] ]
     $ph_image    string   image URL shown on the right
     $ph_badge    array    ['icon' => '...', 'value' => '...', 'label' => '...'] floating card on the image
   ========================================================================== */
if (!function_exists('mx_clean')) {
	function mx_clean($s, $limit = 0)
	{
		$s = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string)$s), ENT_QUOTES, 'UTF-8')));
		if ($limit && mb_strlen($s) > $limit) {
			$s = rtrim(mb_substr($s, 0, $limit));
			$s = preg_replace('/\s+\S*$/u', '', $s) . '…';
		}
		return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
	}
}
$ph_eyebrow = isset($ph_eyebrow) ? $ph_eyebrow : '';
$ph_text    = isset($ph_text) ? $ph_text : '';
$ph_crumbs  = isset($ph_crumbs) ? $ph_crumbs : array();
$ph_stats   = isset($ph_stats) ? $ph_stats : array();
$ph_image   = isset($ph_image) ? $ph_image : '';
$ph_badge   = isset($ph_badge) ? $ph_badge : array();
?>
<section class="mx-phero<?php echo $ph_image ? ' has-visual' : ''; ?>">
	<span class="mx-phero-blob mx-phero-blob-1" aria-hidden="true"></span>
	<span class="mx-phero-blob mx-phero-blob-2" aria-hidden="true"></span>
	<span class="mx-phero-grid" aria-hidden="true"></span>

	<div class="container">
		<div class="row align-items-center">
			<div class="<?php echo $ph_image ? 'col-lg-7' : 'col-lg-9'; ?>">

				<?php if ($ph_crumbs) { ?>
					<nav class="mx-crumbs" aria-label="breadcrumb" data-reveal>
						<?php foreach ($ph_crumbs as $i => $c) {
							if ($i) echo '<i class="fas fa-chevron-right" aria-hidden="true"></i>';
							if (!empty($c['url'])) { ?>
								<a href="<?php echo $c['url']; ?>"><?php if ($i == 0) { ?><i class="fas fa-house" aria-hidden="true"></i><?php } ?><?php echo mx_clean($c['label']); ?></a>
							<?php } else { ?>
								<span aria-current="page"><?php echo mx_clean($c['label']); ?></span>
						<?php }
						} ?>
					</nav>
				<?php } ?>

				<?php if ($ph_eyebrow) { ?>
					<span class="mx-eyebrow mx-eyebrow-light" data-reveal style="--d:80ms"><span class="mx-pulse"></span><?php echo mx_clean($ph_eyebrow); ?></span>
				<?php } ?>

				<h1 class="mx-phero-title" data-reveal style="--d:140ms"><?php echo mx_clean($ph_title); ?></h1>

				<?php if (mx_clean($ph_text) !== '') { ?>
					<p class="mx-phero-text" data-reveal style="--d:220ms"><?php echo mx_clean($ph_text, 260); ?></p>
				<?php } ?>

				<?php if ($ph_stats) { ?>
					<div class="mx-phero-stats" data-reveal style="--d:300ms">
						<?php foreach ($ph_stats as $s) { ?>
							<div class="mx-phero-stat">
								<i class="<?php echo $s['icon']; ?>" aria-hidden="true"></i>
								<span><b><?php echo $s['value']; ?></b><small><?php echo $s['label']; ?></small></span>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>

			<?php if ($ph_image) { ?>
				<div class="col-lg-5 d-none d-lg-block">
					<div class="mx-phero-visual" data-reveal style="--d:200ms">
						<span class="mx-phero-ring" aria-hidden="true"></span>
						<div class="mx-phero-img"><img src="<?php echo $ph_image; ?>" alt="<?php echo mx_clean($ph_title); ?>"></div>
						<?php if ($ph_badge) { ?>
							<div class="mx-phero-badge">
								<i class="<?php echo $ph_badge['icon']; ?>" aria-hidden="true"></i>
								<span><b><?php echo $ph_badge['value']; ?></b><small><?php echo $ph_badge['label']; ?></small></span>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>

	<svg class="mx-phero-wave" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
		<path d="M0,48 C240,88 480,8 720,32 C960,56 1200,84 1440,40 L1440,80 L0,80 Z"></path>
	</svg>
</section>
