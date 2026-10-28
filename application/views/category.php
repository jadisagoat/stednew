<?php
/* ==========================================================================
   CATEGORY PAGE (modern design - backup: category____28_10_2026.php)
   ========================================================================== */
$mx_cat_title = isset($categoryinfo['title']) ? $categoryinfo['title'] : 'Courses';
$mx_count = 0;
foreach ($countcousre as $row) $mx_count = (int)$row['coursenbr'];
$mx_hours = 0;
foreach ($course as $row) $mx_hours += (float)$row['hours'];

$mx_cat_img = '';
if (!empty($categoryinfo['image']) && is_file("uploads/category/" . $categoryinfo['image'])) {
	$mx_cat_img = optimized_image_url('category', $categoryinfo['image'], 700);
} elseif ($course) {
	$mx_cat_img = course_image_url($course[0]['image'], 700);
}

$mx_stats = array(
	array('icon' => 'fas fa-book-open', 'value' => $mx_count, 'label' => $mx_count == 1 ? 'Course' : 'Courses'),
);
if ($mx_hours > 0) $mx_stats[] = array('icon' => 'far fa-clock', 'value' => round($mx_hours), 'label' => 'Learning hours');
$mx_stats[] = array('icon' => 'fas fa-certificate', 'value' => 'Certified', 'label' => 'After passing the exam');

$this->load->view('include/_page_hero', array(
	'ph_title'   => $mx_cat_title,
	'ph_eyebrow' => 'Course Category',
	'ph_text'    => 'Practical, industry-focused training from STED. Learn online at your own pace and earn your certificate.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'Categories'),
		array('label' => $mx_cat_title),
	),
	'ph_stats'   => $mx_stats,
	'ph_image'   => $mx_cat_img,
	'ph_badge'   => array('icon' => 'fas fa-laptop', 'value' => '100% Online', 'label' => 'Learn anywhere, anytime'),
));
?>

<div class="mx-inner">
	<section class="mx-cat-section">
		<div class="container">

			<!-- Toolbar: result count + other categories -->
			<div class="mx-cat-toolbar" data-reveal>
				<div class="mx-cat-count">
					<h2><?php echo $mx_count; ?> <?php echo $mx_count == 1 ? 'course' : 'courses'; ?> <span>available</span></h2>
					<p>in <?php echo mx_clean($mx_cat_title); ?></p>
				</div>
				<?php if (!empty($category) && count($category) > 1) { ?>
					<div class="mx-chips-wrap">
					<span class="mx-chips-label"><i class="fas fa-layer-group"></i> Browse categories</span>
					<div class="mx-chips" aria-label="Other categories">
						<?php foreach ($category as $c) {
							$is_on = ((int)$c['id_category'] === (int)$categoryinfo['id_category']); ?>
							<a class="mx-chip<?php echo $is_on ? ' is-on' : ''; ?>" href="<?php echo base_url(); ?>category/<?php echo $c['id_category']; ?>"<?php echo $is_on ? ' aria-current="page"' : ''; ?>><?php echo mx_clean($c['title'], 40); ?></a>
						<?php } ?>
					</div>
					</div>
				<?php } ?>
			</div>

			<?php if ($course) { ?>
				<div class="row mx-ccards">
					<?php foreach ($course as $i => $row) {
						$url = base_url() . 'coursedetails/' . $row['id_course'];
						$hours = (float)$row['hours']; ?>
						<div class="col-md-6 col-lg-4 d-flex">
							<article class="mx-ccard" data-reveal style="--d:<?php echo min($i % 3, 2) * 110; ?>ms">
								<a class="mx-ccard-media" href="<?php echo $url; ?>" tabindex="-1" aria-hidden="true">
									<img src="<?php echo course_image_url($row['image']); ?>" alt="" loading="lazy">
									<span class="mx-ccard-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
								</a>
								<div class="mx-ccard-body">
									<div class="mx-ccard-meta">
										<span><i class="fas fa-laptop"></i> Online</span>
										<?php if ($hours > 0) { ?><span><i class="far fa-clock"></i> <?php echo max(1, round($hours)); ?> hrs</span><?php } ?>
										<span><i class="fas fa-certificate"></i> Certificate</span>
									</div>
									<h3 class="mx-ccard-title"><a href="<?php echo $url; ?>"><?php echo mx_clean($row['title']); ?></a></h3>
									<p class="mx-ccard-text"><?php echo mx_clean($row['summary'], 170); ?></p>
									<div class="mx-ccard-foot">
										<span class="mx-ccard-cat"><?php echo mx_clean($mx_cat_title, 34); ?></span>
										<span class="mx-ccard-go">View course <i class="fas fa-arrow-right"></i></span>
									</div>
								</div>
							</article>
						</div>
					<?php } ?>
				</div>
			<?php } else { ?>
				<div class="mx-empty" data-reveal>
					<span class="mx-empty-ico"><i class="fas fa-book-open"></i></span>
					<h3>New courses are coming soon</h3>
					<p>There are no courses in this category yet. Browse the other categories above or contact us about this topic.</p>
					<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Contact us <i class="fas fa-arrow-right"></i></a>
				</div>
			<?php } ?>

			<!-- CTA -->
			<div class="mx-cta" data-reveal>
				<span class="mx-cta-dots" aria-hidden="true"></span>
				<div class="mx-cta-txt">
					<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Training for teams</span>
					<h3>Need this training for your whole team?</h3>
					<p>Tell us what your engineers need and we will help you choose the right courses.</p>
				</div>
				<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Talk to us <i class="fas fa-arrow-right"></i></a>
			</div>

		</div>
	</section>
</div>

<script>
	// keep the current category chip in view inside the scrollable row
	(function() {
		var on = document.querySelector('.mx-chips .mx-chip.is-on'), row = on && on.parentNode;
		if (row && row.scrollWidth > row.clientWidth) row.scrollLeft = on.offsetLeft - row.offsetLeft - 24;
	})();
</script>
