<?php
/* ==========================================================================
   CATEGORY DETAILS (modern design - backup: category-details____28_10_2026.php)
   $course          = active courses of the category (+ avg_course_rating)
   $subcategoryinfo = category title (cattitle / subcattitle)
   The favourite bookmark keeps its classes/data attributes (handled in template.php).
   ========================================================================== */
$mx_cat_title = 'Courses';
foreach ($subcategoryinfo as $row) {
	if (trim((string)$row['cattitle']) !== '') $mx_cat_title = $row['cattitle'];
}
$mx_count = count($course);
$mx_rated = 0;
$mx_rating_sum = 0;
foreach ($course as $row) {
	if ($row['avg_course_rating'] !== null && $row['avg_course_rating'] !== '') {
		$mx_rated++;
		$mx_rating_sum += (float)$row['avg_course_rating'];
	}
}
$mx_avg = $mx_rated ? round($mx_rating_sum / $mx_rated, 1) : 0;
$mx_logged = isset($login['idUser']) && !empty($login['idUser']);

$mx_crumbs = array(array('label' => 'Home', 'url' => base_url()), array('label' => 'Categories'));
foreach ($subcategoryinfo as $row) {
	$mx_crumbs[] = array('label' => $row['cattitle']);
	if ($row['subcattitle'] !== $row['cattitle']) $mx_crumbs[] = array('label' => $row['subcattitle']);
}
if (!$subcategoryinfo) $mx_crumbs[] = array('label' => $mx_cat_title);

$mx_stats = array(array('icon' => 'fas fa-book-open', 'value' => $mx_count, 'label' => $mx_count == 1 ? 'Course' : 'Courses'));
if ($mx_avg > 0) $mx_stats[] = array('icon' => 'fas fa-star', 'value' => number_format($mx_avg, 1), 'label' => 'Average rating');
$mx_stats[] = array('icon' => 'fas fa-certificate', 'value' => 'Certified', 'label' => 'After passing the exam');

$this->load->view('include/_page_hero', array(
	'ph_title'   => $mx_cat_title,
	'ph_eyebrow' => 'Course Category',
	'ph_text'    => 'Practical, industry-focused training from STED. Learn online at your own pace and earn your certificate.',
	'ph_crumbs'  => $mx_crumbs,
	'ph_stats'   => $mx_stats,
	'ph_image'   => $course ? course_image_url($course[0]['image'], 700) : '',
	'ph_badge'   => array('icon' => 'fas fa-laptop', 'value' => '100% Online', 'label' => 'Learn anywhere, anytime'),
));
?>

<div class="mx-inner mx-catd">
	<section class="mx-cat-section">
		<div class="container">

			<div class="mx-cat-toolbar" data-reveal>
				<div class="mx-cat-count">
					<h2><?php echo $mx_count; ?> <?php echo $mx_count == 1 ? 'course' : 'courses'; ?> <span>available</span></h2>
					<p>in <?php echo mx_clean($mx_cat_title); ?><?php if ($mx_logged) { ?> &middot; tap <i class="fas fa-bookmark"></i> to save a course to your favorites<?php } ?></p>
				</div>
			</div>

			<?php if ($course) { ?>
				<div class="row mx-ccards">
					<?php foreach ($course as $i => $row) {
						$url = base_url() . 'coursedetails/' . $row['id_course'];
						$hours = isset($row['hours']) ? (float)$row['hours'] : 0;
						$average = $row['avg_course_rating'];
						$fav = isset($favorit[$row['id_course']]) ? $favorit[$row['id_course']] : 0; ?>
						<div class="col-md-6 col-lg-4 d-flex">
							<article class="mx-ccard" data-reveal style="--d:<?php echo min($i % 3, 2) * 110; ?>ms">
								<?php if ($mx_logged) { ?>
									<span class="clikfavorite button_favorite mx-catd-fav favorite_<?php echo $row['id_course']; ?>" data-active="<?php echo $fav; ?>" data-id-course="<?php echo $row['id_course']; ?>" role="button" tabindex="0" aria-label="Save to favorites">
										<i title="favorite" class="fa fa-bookmark <?php if ($fav) { ?> fa-yellow <?php } ?>"></i>
									</span>
								<?php } ?>
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
										<div class="form-group rating-class mx-catd-stars" title="<?php echo $average ? number_format((float)$average, 1) . ' / 5' : 'Not rated yet'; ?>">
											<?php
											for ($s = 1; $s <= 5; $s++) {
												$ratingClass = "star-grey";
												if ($s <= $average) {
													$ratingClass = "star-highlight";
												}
												echo '<i class="fa fa-star fa-lg rateButton ' . $ratingClass . '" aria-hidden="true"></i>';
											}
											?>
											<small><?php echo $average ? number_format((float)$average, 1) : 'New'; ?></small>
										</div>
										<span class="mx-ccard-go">View <i class="fas fa-arrow-right"></i></span>
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
					<p>There are no courses in this category yet. Contact us about this topic and we will let you know when training is available.</p>
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
	// keyboard access for the favorite bookmark (the click itself is handled in template.php)
	(function() {
		document.querySelectorAll('.mx-catd-fav').forEach(function(el) {
			el.addEventListener('keydown', function(e) {
				if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); el.click(); }
			});
		});
	})();
</script>
