<?php
/* ==========================================================================
   MY COURSES - lecturer (modern design - backup: mycourses____28_10_2026.php)
   $mycourses = id_course, image, coursetitle, summary. Styles: css/pages/account.css
   ========================================================================== */
$mx_courses = isset($mycourses) && is_array($mycourses) ? $mycourses : array();
$mx_total = count($mx_courses);

$this->load->view('include/_page_hero', array(
	'ph_title'   => 'My courses',
	'ph_eyebrow' => 'Lecturer area',
	'ph_text'    => 'The courses you teach at STED - open one to review its content, lessons and learners.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'My account', 'url' => base_url() . 'edit-profile'),
		array('label' => 'My courses'),
	),
	'ph_stats'   => array(
		array('icon' => 'fas fa-chalkboard-user', 'value' => $mx_total, 'label' => $mx_total == 1 ? 'Course you teach' : 'Courses you teach'),
		array('icon' => 'far fa-comments', 'value' => 'Feedback', 'label' => 'See what learners say'),
	),
));
?>

<div class="mx-inner mx-acc">
	<section class="mx-acc-main">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-xl-3 tutor-dashboard-left-menu">
					<?php $data['login'] = $login;
					$data['login']['page'] = 'mycourses';
					$this->load->view('include/side_profile', $data);
					?>
				</div>

				<div class="col-lg-8 col-xl-9">
					<div class="mx-acc-panel" data-reveal style="--d:100ms">
						<div class="mx-acc-head">
							<span class="mx-acc-head-ico"><i class="fas fa-chalkboard-user"></i></span>
							<div class="mx-acc-head-txt">
								<h2>Courses you teach</h2>
								<p><?php echo $mx_total ? $mx_total . ($mx_total == 1 ? ' course' : ' courses') . ' assigned to you' : 'No course assigned yet'; ?></p>
							</div>
							<a class="mx-acc-head-btn" href="<?php echo base_url(); ?>feedbackhistory">Learner feedback <i class="fas fa-arrow-right"></i></a>
						</div>

						<?php if ($mx_total) { ?>
							<div class="row mx-ccards mx-acc-cards">
								<?php foreach ($mx_courses as $i => $row) {
									$url = base_url() . 'coursedetails/' . $row['id_course']; ?>
									<div class="col-sm-6 d-flex">
										<article class="mx-ccard mx-acc-ccard" data-reveal style="--d:<?php echo ($i % 2) * 110; ?>ms">
											<a class="mx-ccard-media" href="<?php echo $url; ?>" tabindex="-1" aria-hidden="true">
												<?php if (!empty($row['image']) && is_file("uploads/course/" . $row['image'])) { ?>
													<img src="<?php echo course_image_url($row['image']); ?>" alt="" loading="lazy">
												<?php } else { ?>
													<span class="mx-acc-noimg"><i class="fas fa-graduation-cap"></i></span>
												<?php } ?>
												<span class="mx-ccard-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
											</a>
											<div class="mx-ccard-body">
												<div class="mx-ccard-meta">
													<?php if (!empty($row['subtitle'])) { ?><span><i class="fas fa-layer-group"></i> <?php echo mx_clean($row['subtitle'], 30); ?></span><?php } ?>
													<span><i class="fas fa-chalkboard-user"></i> Lecturer</span>
												</div>
												<h3 class="mx-ccard-title"><a href="<?php echo $url; ?>"><?php echo mx_clean($row['coursetitle']); ?></a></h3>
												<?php if (mx_clean($row['summary']) !== '') { ?>
													<p class="mx-ccard-text"><?php echo mx_clean($row['summary'], 150); ?></p>
												<?php } ?>
												<div class="mx-ccard-foot">
													<span class="mx-ccard-cat"><i class="fas fa-laptop"></i> Online course</span>
													<span class="mx-ccard-go">Open course <i class="fas fa-arrow-right"></i></span>
												</div>
											</div>
										</article>
									</div>
								<?php } ?>
							</div>
						<?php } else { ?>
							<div class="mx-empty mx-acc-empty">
								<span class="mx-empty-ico"><i class="fas fa-chalkboard-user"></i></span>
								<h3>No courses assigned yet</h3>
								<p>When the STED team assigns a course to you as lecturer, it will appear here.</p>
								<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Contact the team <i class="fas fa-arrow-right"></i></a>
							</div>
						<?php } ?>

						<form method="post" id="savedata" enctype="multipart/form-data"></form>
						<div class="error" id="msg"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
