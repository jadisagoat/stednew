<?php
/* ==========================================================================
   ENROLLED COURSES (modern design - backup: enrolled_courses____28_10_2026.php)
   $enrolledcourses = id_course, image, coursetitle, summary, subtitle (category)
   $avgcourse[id_course] = completion percentage. Styles: css/pages/account.css
   ========================================================================== */
$mx_courses = isset($enrolledcourses) && is_array($enrolledcourses) ? $enrolledcourses : array();
$mx_total = count($mx_courses);
$mx_sum = 0;
$mx_done = 0;
foreach ($mx_courses as $k => $row) {
	$p = (isset($avgcourse[$row['id_course']]) && $row['id_course']) ? (float)$avgcourse[$row['id_course']] : 0;
	$p = max(0, min(100, (int)round($p)));
	$mx_courses[$k]['mx_progress'] = $p;
	$mx_sum += $p;
	if ($p >= 100) $mx_done++;
}
$mx_avg = $mx_total ? (int)round($mx_sum / $mx_total) : 0;

$this->load->view('include/_page_hero', array(
	'ph_title'   => 'Enrolled courses',
	'ph_eyebrow' => 'My account',
	'ph_text'    => 'Pick up where you left off - follow your progress on every course you are enrolled in.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'My account', 'url' => base_url() . 'edit-profile'),
		array('label' => 'Enrolled courses'),
	),
	'ph_stats'   => array(
		array('icon' => 'fas fa-book-open', 'value' => $mx_total, 'label' => $mx_total == 1 ? 'Course enrolled' : 'Courses enrolled'),
		array('icon' => 'fas fa-chart-line', 'value' => $mx_avg . '%', 'label' => 'Average progress'),
		array('icon' => 'fas fa-award', 'value' => $mx_done, 'label' => 'Completed'),
	),
));
?>

<div class="mx-inner mx-acc">
	<section class="mx-acc-main">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-xl-3 tutor-dashboard-left-menu">
					<?php $data['login'] = $login;
					$data['login']['page'] = 'enrolled_courses';
					$this->load->view('include/side_profile', $data);
					?>
				</div>

				<div class="col-lg-8 col-xl-9">
					<div class="mx-acc-panel" data-reveal style="--d:100ms">
						<div class="mx-acc-head">
							<span class="mx-acc-head-ico"><i class="fas fa-book-open"></i></span>
							<div class="mx-acc-head-txt">
								<h2>My learning</h2>
								<p><?php echo $mx_total ? $mx_total . ($mx_total == 1 ? ' course' : ' courses') . ' · ' . $mx_avg . '% average progress' : 'Courses you enrol in will appear here'; ?></p>
							</div>
							<a class="mx-acc-head-btn" href="<?php echo base_url(); ?>#courses-1">Explore courses <i class="fas fa-arrow-right"></i></a>
						</div>

						<?php if ($mx_total) { ?>
							<div class="row mx-ccards mx-acc-cards">
								<?php foreach ($mx_courses as $i => $row) {
									$url = base_url() . 'coursedetails/' . $row['id_course'];
									$p = $row['mx_progress'];
									if ($p >= 100) {
										$st = array('is-done', 'fas fa-circle-check', 'Completed');
										$go = 'Review course';
									} elseif ($p > 0) {
										$st = array('is-progress', 'fas fa-spinner', 'In progress');
										$go = 'Continue';
									} else {
										$st = array('is-new', 'fas fa-play', 'Not started');
										$go = 'Start course';
									} ?>
									<div class="col-sm-6 d-flex">
										<article class="mx-ccard mx-acc-ccard" data-reveal style="--d:<?php echo ($i % 2) * 110; ?>ms">
											<a class="mx-ccard-media" href="<?php echo $url; ?>" tabindex="-1" aria-hidden="true">
												<?php if (!empty($row['image']) && is_file("uploads/course/" . $row['image'])) { ?>
													<img src="<?php echo course_image_url($row['image']); ?>" alt="" loading="lazy">
												<?php } else { ?>
													<span class="mx-acc-noimg"><i class="fas fa-graduation-cap"></i></span>
												<?php } ?>
												<span class="mx-ccard-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
												<span class="mx-acc-ring" style="--p:<?php echo $p; ?>"><b><?php echo $p; ?><small>%</small></b></span>
											</a>
											<div class="mx-ccard-body">
												<div class="mx-ccard-meta">
													<?php if (!empty($row['subtitle'])) { ?><span><i class="fas fa-layer-group"></i> <?php echo mx_clean($row['subtitle'], 30); ?></span><?php } ?>
													<span class="mx-acc-status <?php echo $st[0]; ?>"><i class="<?php echo $st[1]; ?>"></i> <?php echo $st[2]; ?></span>
												</div>
												<h3 class="mx-ccard-title"><a href="<?php echo $url; ?>"><?php echo mx_clean($row['coursetitle']); ?></a></h3>
												<?php if (mx_clean($row['summary']) !== '') { ?>
													<p class="mx-ccard-text"><?php echo mx_clean($row['summary'], 150); ?></p>
												<?php } ?>
												<div class="mx-acc-progress" role="progressbar" aria-valuenow="<?php echo $p; ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress">
													<div class="mx-acc-bar"><span style="width:<?php echo $p; ?>%"></span></div>
													<small><b><?php echo $p; ?>%</b> complete</small>
												</div>
												<div class="mx-ccard-foot">
													<span class="mx-ccard-cat"><i class="far fa-bookmark"></i> Enrolled</span>
													<span class="mx-ccard-go"><?php echo $go; ?> <i class="fas fa-arrow-right"></i></span>
												</div>
											</div>
										</article>
									</div>
								<?php } ?>
							</div>
						<?php } else { ?>
							<div class="mx-empty mx-acc-empty">
								<span class="mx-empty-ico"><i class="fas fa-book-open"></i></span>
								<h3>No courses yet</h3>
								<p>You are not enrolled in a course yet. Explore the STED catalogue and start learning today.</p>
								<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>#courses-1">Explore courses <i class="fas fa-arrow-right"></i></a>
							</div>
						<?php } ?>

						<form method="post" id="savedata" enctype="multipart/form-data"></form>
						<div class="error" id="msg"></div>
					</div>

					<?php if ($mx_total) { ?>
						<div class="mx-cta mx-acc-cta" data-reveal>
							<span class="mx-cta-dots" aria-hidden="true"></span>
							<div class="mx-cta-txt">
								<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Exams</span>
								<h3>Ready to test your knowledge?</h3>
								<p>Finish a course, then take its exam to earn your certificate.</p>
							</div>
							<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>examcourse">Go to my exams <i class="fas fa-arrow-right"></i></a>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>
</div>
