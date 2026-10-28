<?php
/* ==========================================================================
   COURSE DETAILS (modern design - backup: course-details____28_10_2026.php)
   Controller: home::coursedetails  ·  styles: theme_academy/css/pages/course.css
   Popups (player + exam): course-details-popup.php / course-details-popup-exam.php
   NOTE: ids / classes used by the scripts below, by the popups and by template.php
   (#id_course, .startcourse, #examClick, .ClickExam*, .tak-exam, .add_list_reviews,
   #list_rating, .rateClick, #list_reviews, .postReviews, #listComments ...) are kept.
   ========================================================================== */
$cd_id       = $coursedetail['id_course'];
$cd_title    = $coursedetail['coursetitle'];
$cd_cat      = $coursedetail['categtitle'];
$cd_rating   = (float) $coursedetail['avg_course_rating'];
$cd_hours    = (float) $coursedetail['hours'];
$cd_enrolled = (isset($login) && $login && $userenrollement != 0);
$cd_progress = isset($avgcourse[$cd_id]) ? max(0, min(100, (int) round($avgcourse[$cd_id]))) : 0;
$cd_done_ids = isset($id_content_complete_by_course) ? $id_content_complete_by_course : array();
$cd_lecturers = (isset($alllecture) && is_array($alllecture)) ? array_filter($alllecture) : array();

// lesson types (1 = video, 2 = reading, 3 = PDF)
$cd_types = array('1' => 0, '2' => 0, '3' => 0);
foreach ($contents as $row) {
	if (isset($cd_types[$row['id_type']])) $cd_types[$row['id_type']]++;
}
$cd_type_meta = array(
	'1' => array('icon' => 'fas fa-play', 'label' => 'Video'),
	'2' => array('icon' => 'far fa-file-lines', 'label' => 'Reading'),
	'3' => array('icon' => 'far fa-file-pdf', 'label' => 'PDF'),
);

// description: drop a leading "Description" heading (the card has its own title)
$cd_desc = (string) $coursedetail['description'];
if (preg_match('~^\s*<(p|h[1-6])\b[^>]*>.*?</\1>~is', $cd_desc, $m) && strcasecmp(trim(html_entity_decode(strip_tags($m[0]), ENT_QUOTES, 'UTF-8')), 'Description') === 0) {
	$cd_desc = substr($cd_desc, strlen($m[0]));
}
$cd_requirements = (string) $coursedetail['requirements'];

$cd_updated = '';
if (!empty($coursedetail['updated_date']) && strtotime($coursedetail['updated_date']) > 0) {
	$cd_updated = date('d/m/Y', strtotime($coursedetail['updated_date']));
}

// stars helper output (read-only)
$cd_stars = '';
for ($i = 1; $i <= 5; $i++) {
	$cd_stars .= '<i class="fa fa-star ' . ($i <= $cd_rating ? 'star-highlight' : ($i - 0.5 <= $cd_rating ? 'star-half' : 'star-grey')) . '" aria-hidden="true"></i>';
}
?>
<input type="hidden" name='id_course' id='id_course' value="<?php echo $cd_id; ?>">
<?php
/* ---------- HERO ---------- */
$cd_stats = array();
if ($cd_rating > 0) $cd_stats[] = array('icon' => 'fas fa-star', 'value' => number_format($cd_rating, 1) . ' / 5', 'label' => 'Course rating');
if (count($contents)) $cd_stats[] = array('icon' => 'fas fa-layer-group', 'value' => count($contents), 'label' => count($contents) == 1 ? 'Lesson' : 'Lessons');
if ($cd_hours > 0) $cd_stats[] = array('icon' => 'far fa-clock', 'value' => max(1, round($cd_hours)), 'label' => 'Hours of learning');

$this->load->view('include/_page_hero', array(
	'ph_title'   => $cd_title,
	'ph_eyebrow' => $cd_cat ? $cd_cat : 'Online course',
	'ph_text'    => trim(strip_tags((string) $coursedetail['summary'])) !== '' ? $coursedetail['summary'] : $cd_desc,
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => $cd_cat),
		array('label' => $cd_title),
	),
	'ph_stats'   => $cd_stats,
	'ph_image'   => course_image_url($coursedetail['image'], 700),
	'ph_badge'   => array('icon' => 'fas fa-award', 'value' => 'Certificate', 'label' => 'After passing the final exam'),
));
?>

<div class="mx-inner mx-cd">
	<section class="mx-cd-main">
		<div class="container">
			<div class="row">

				<!-- ============ SIDEBAR (first on phones) ============ -->
				<aside class="col-lg-4 order-lg-2 mx-cd-aside">
					<div class="mx-cd-side">
						<div class="mx-cd-card mx-cd-buy" data-reveal>
							<div class="mx-cd-buy-media d-lg-none">
								<img src="<?php echo course_image_url($coursedetail['image'], 700); ?>" alt="<?php echo mx_clean($cd_title); ?>">
							</div>

							<div class="mx-cd-buy-head">
								<?php if ($cd_enrolled) { ?>
									<span class="mx-cd-status is-on"><i class="fas fa-circle-check"></i> You are enrolled</span>
								<?php } else { ?>
									<span class="mx-cd-status"><i class="fas fa-laptop"></i> 100% online course</span>
								<?php } ?>
								<div class="mx-cd-rate">
									<span class="mx-cd-stars"><?php echo $cd_stars; ?></span>
									<b><?php echo $cd_rating > 0 ? number_format($cd_rating, 1) : 'New'; ?></b>
								</div>
							</div>

							<?php if ($cd_enrolled && $userenrollement == '3') { ?>
								<div class="mx-cd-progress" aria-label="Course progress">
									<div class="mx-cd-progress-top"><span>Your progress</span><b><?php echo $cd_progress; ?>%</b></div>
									<div class="mx-cd-bar"><span style="width:<?php echo $cd_progress; ?>%"></span></div>
								</div>
							<?php } ?>

							<!-- actions -->
							<div class="course-data-links mx-cd-actions">
								<?php
								if ($userenrollement) { ?>
									<span class="mx-cd-enrolled-pill"><i class="fas fa-check"></i> Enrolled</span>
									<?php
									if ($userenrollement == '2') {  //teacher
										if ($id_exam) { ?>
											<a class="mx-btn mx-cd-btn-dark" href="<?php echo base_url(); ?>editexam/<?php echo $id_exam; ?>">
												<i class="fas fa-pen-to-square"></i> Edit Exam
											</a>
										<?php } else { ?>
											<a class="mx-btn mx-cd-btn-dark" href="<?php echo base_url(); ?>editexam/<?php echo $coursedetail['id_course']; ?>/addincourse">
												<i class="fas fa-plus"></i> Add Exam
											</a>
										<?php }
									} else
										if ($userenrollement == '3') {  //student

											if (count($questions) && $avgcourse[$coursedetail['id_course']] == "100.00") { ///10000
												$ClickExam = 'ClickExamRateFeedback1';
												$attributes = '';
												if ($my_user_course_rating['0']['rating'] && $my_user_course_rating['0']['feedback']) {
													$ClickExam = 'ClickExam';
													$attributes = ' data-toggle="modal" data-target="#examModal" ';
												}
												?>
												<a class="<?php echo $ClickExam; ?> mx-btn mx-btn-primary" <?php echo $attributes; ?> role="button" tabindex="0" style="cursor:pointer">
													<i class="fas fa-graduation-cap"></i> Take Exam
												</a>
											<?php }
										}
									if ($cd_enrolled) { ?>
										<a class="mx-cd-link" href="#mx-cd-learn"><i class="fas fa-arrow-down"></i> Go to your lessons</a>
									<?php }
								} else { ?>
									<a href="#" class="mx-btn mx-btn-primary mx-cd-enroll"
										onclick="submitEnrollCourse('<?php echo $coursedetail['id_course']; ?>'); return false;">Enroll Now <i class="fas fa-arrow-right"></i></a>
									<p class="mx-cd-note"><i class="fas fa-lock-open"></i> Free enrollment · learn at your own pace</p>
								<?php } ?>
							</div>

							<!-- this course includes -->
							<ul class="mx-cd-facts">
								<?php if (count($contents)) { ?>
									<li><i class="fas fa-layer-group"></i><span><b><?php echo count($contents); ?> <?php echo count($contents) == 1 ? 'lesson' : 'lessons'; ?></b>
										<small><?php
										$cd_parts = array();
										$cd_words = array('1' => array('video', 'videos'), '2' => array('reading', 'readings'), '3' => array('PDF', 'PDFs'));
										foreach ($cd_types as $t => $n) if ($n) $cd_parts[] = $n . ' ' . $cd_words[$t][$n > 1 ? 1 : 0];
										echo implode(' · ', $cd_parts);
										?></small></span></li>
								<?php } ?>
								<?php if ($cd_hours > 0) { ?>
									<li><i class="far fa-clock"></i><span><b><?php echo max(1, round($cd_hours)); ?> hours</b><small>Self-paced, on any device</small></span></li>
								<?php } ?>
								<li><i class="fas fa-award"></i><span><b>Certificate</b><small>After passing the final exam</small></span></li>
								<?php if ($cd_lecturers) { ?>
									<li><i class="fas fa-chalkboard-user"></i><span><b><?php echo mx_clean(implode(', ', $cd_lecturers)); ?></b><small><?php echo count($cd_lecturers) > 1 ? 'Lecturers' : 'Lecturer'; ?></small></span></li>
								<?php } ?>
								<?php if ($cd_updated) { ?>
									<li><i class="far fa-calendar-check"></i><span><b><?php echo $cd_updated; ?></b><small>Last updated</small></span></li>
								<?php } ?>
							</ul>
						</div>
					</div>

					<!-- follows the reader down the long main column (desktop only) -->
					<div class="mx-cd-stick d-none d-lg-block">
						<div class="mx-cd-card mx-cd-path" data-reveal>
							<span class="mx-eyebrow"><span class="mx-pulse"></span>How it works</span>
							<h3 class="mx-cd-path-title">Your path to the certificate</h3>
							<ol class="mx-cd-steps">
								<li class="<?php echo $userenrollement ? 'is-done' : 'is-now'; ?>">
									<span class="mx-cd-step-ico"><i class="fas <?php echo $userenrollement ? 'fa-check' : 'fa-user-plus'; ?>"></i></span>
									<span><b>Enroll for free</b><small><?php echo $userenrollement ? 'Done - you are in' : 'One click, no payment'; ?></small></span>
								</li>
								<li class="<?php echo !$cd_enrolled ? '' : ($cd_progress >= 100 ? 'is-done' : 'is-now'); ?>">
									<span class="mx-cd-step-ico"><i class="fas <?php echo ($cd_enrolled && $cd_progress >= 100) ? 'fa-check' : 'fa-play'; ?>"></i></span>
									<span><b><?php echo count($contents) ? count($contents) . ' ' . (count($contents) == 1 ? 'lesson' : 'lessons') : 'Lessons'; ?></b>
										<small><?php echo ($cd_enrolled && $userenrollement == '3') ? $cd_progress . '% completed' : ($cd_hours > 0 ? max(1, round($cd_hours)) . ' hours, at your own pace' : 'At your own pace'); ?></small></span>
								</li>
								<li class="<?php echo ($cd_enrolled && $cd_progress >= 100) ? 'is-now' : ''; ?>">
									<span class="mx-cd-step-ico"><i class="fas fa-graduation-cap"></i></span>
									<span><b>Final exam</b><small>Opens when all lessons are done</small></span>
								</li>
								<li>
									<span class="mx-cd-step-ico"><i class="fas fa-award"></i></span>
									<span><b>Certificate</b><small>Yours after passing the exam</small></span>
								</li>
							</ol>

							<?php if ($userenrollement) { ?>
								<a class="mx-btn mx-btn-primary mx-cd-path-btn" href="#mx-cd-learn"><i class="fas fa-arrow-down"></i> Go to your lessons</a>
							<?php } else { ?>
								<a href="#" class="mx-btn mx-btn-primary mx-cd-path-btn mx-cd-enroll"
									onclick="submitEnrollCourse('<?php echo $coursedetail['id_course']; ?>'); return false;">Enroll Now <i class="fas fa-arrow-right"></i></a>
							<?php } ?>

							<div class="mx-cd-help">
								<span>Questions about this course?</span>
								<a href="https://wa.me/966509296490" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp</a>
								<a href="<?php echo base_url(); ?>contactus"><i class="far fa-envelope"></i> Contact</a>
							</div>
						</div>
					</div>
				</aside>

				<!-- ============ MAIN COLUMN ============ -->
				<div class="col-lg-8 order-lg-1">

					<?php if (isset($login) && $login && $userenrollement != 0) { ?>
						<!-- LEARNING PATH (enrolled users) -->
						<div class="mx-cd-card mx-cd-learn" id="mx-cd-learn" data-reveal>
							<div class="mx-cd-learn-head">
								<div>
									<span class="mx-eyebrow"><span class="mx-pulse"></span>Your learning path</span>
									<h2 class="mx-cd-h2"><?php echo mx_clean($cd_title); ?></h2>
								</div>
								<div class="mx-cd-ring" style="--p:<?php echo $cd_progress; ?>">
									<b><?php echo $cd_progress; ?>%</b><small>done</small>
								</div>
							</div>

							<ol class="mx-cd-lessons">
								<?php $cd_next = false;
								foreach ($contents as $i => $row) {
									$is_done = in_array($row['id_content'], $cd_done_ids);
									$is_next = (!$is_done && !$cd_next);
									if ($is_next) $cd_next = true;
									$tm = isset($cd_type_meta[$row['id_type']]) ? $cd_type_meta[$row['id_type']] : $cd_type_meta['2']; ?>
									<li class="<?php echo $is_done ? 'is-done' : ($is_next ? 'is-next' : 'is-locked'); ?>">
										<span class="mx-cd-lnum"><?php if ($is_done) { ?><i class="fas fa-check"></i><?php } else { echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); } ?></span>
										<span class="mx-cd-ltxt">
											<b><?php echo mx_clean($row['title']); ?></b>
											<small><i class="<?php echo $tm['icon']; ?>"></i> <?php echo $tm['label']; ?><?php if ($is_next) { ?> · <em>Up next</em><?php } ?></small>
										</span>
										<i class="mx-cd-lstate fas <?php echo $is_done ? 'fa-circle-check' : ($is_next ? 'fa-circle-play' : 'fa-lock'); ?>" aria-hidden="true"></i>
									</li>
								<?php } ?>
							</ol>

							<?php
							if (count($questions) && $avgcourse[$coursedetail['id_course']] == "100") {  //10000
								$style = " style=' display:block !important'";
							} else { // condition to start exam not achieved
								$style = " style=' display:none !important'";
							} ?>
							<div class="mx-cd-learn-actions">
								<button type="button" id="startcourse" class="startcourse mx-btn mx-btn-primary" data-toggle="modal" data-target="#courseModal">
									<i class="fas fa-play"></i> <?php echo $cd_progress >= 100 ? 'Review lessons' : ($cd_progress > 0 ? 'Continue course' : 'Start course'); ?>
								</button>

								<?php
								$boolean_finish_read_course = 0;
								if (count($questions)) {
									if ($avgcourse[$coursedetail['id_course']] == "100") {
										$boolean_finish_read_course = 1;
										$style = " style=' display:block !important'";
									} else {
										$style = " style=' display:none !important'";
									}
									$ClickExam = 'ClickExamRateFeedback2';
									$attributes = '';
									if ($my_user_course_rating['0']['rating'] && $my_user_course_rating['0']['feedback']) {
										$ClickExam = 'ClickExam';
										$attributes = ' data-toggle="modal" data-target="#examModal" ';
									} ?>
									<div id="examClick" <?php echo $attributes; ?> <?php echo $style; ?>
										class="<?php echo $ClickExam; ?> tak-exam mx-cd-exam-btn" role="button" tabindex="0">
										<i class="fas fa-graduation-cap"></i> Take Exam
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>

					<!-- ABOUT THIS COURSE -->
					<div class="mx-cd-card" data-reveal>
						<div class="mx-cd-card-head">
							<span class="mx-cd-ico"><i class="fas fa-book-open"></i></span>
							<div>
								<h2 class="mx-cd-h2">About this course</h2>
								<p><?php echo mx_clean($cd_cat); ?><?php echo $cd_lecturers ? ' · by ' . mx_clean(implode(', ', $cd_lecturers)) : ''; ?></p>
							</div>
						</div>
						<?php if (mx_clean($cd_desc) !== '') { ?>
							<div class="mx-cd-body mx-cd-clamp" id="mx-cd-desc">
								<?php echo $cd_desc; ?>
							</div>
							<button type="button" class="mx-cd-more" hidden><span>Read more</span> <i class="fas fa-chevron-down"></i></button>
						<?php } else { ?>
							<p class="mx-cd-muted">The full description of this course will be available soon.</p>
						<?php } ?>
					</div>

					<?php if (!(isset($login) && $login && $userenrollement != 0) && count($contents)) { ?>
						<!-- COURSE CONTENT (preview for visitors) -->
						<div class="mx-cd-card" data-reveal>
							<div class="mx-cd-card-head">
								<span class="mx-cd-ico is-warm"><i class="fas fa-list-check"></i></span>
								<div>
									<h2 class="mx-cd-h2">Course content</h2>
									<p><?php echo count($contents); ?> <?php echo count($contents) == 1 ? 'lesson' : 'lessons'; ?><?php echo $cd_hours > 0 ? ' · ' . max(1, round($cd_hours)) . ' hours' : ''; ?> · final exam</p>
								</div>
							</div>
							<ol class="mx-cd-lessons is-preview">
								<?php foreach ($contents as $i => $row) {
									$tm = isset($cd_type_meta[$row['id_type']]) ? $cd_type_meta[$row['id_type']] : $cd_type_meta['2']; ?>
									<li>
										<span class="mx-cd-lnum"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
										<span class="mx-cd-ltxt">
											<b><?php echo mx_clean($row['title']); ?></b>
											<small><i class="<?php echo $tm['icon']; ?>"></i> <?php echo $tm['label']; ?></small>
										</span>
										<i class="mx-cd-lstate fas fa-lock" aria-hidden="true"></i>
									</li>
								<?php } ?>
								<li class="is-exam">
									<span class="mx-cd-lnum"><i class="fas fa-graduation-cap"></i></span>
									<span class="mx-cd-ltxt"><b>Final exam &amp; certificate</b><small><i class="fas fa-award"></i> Pass the exam to get your certificate</small></span>
									<i class="mx-cd-lstate fas fa-lock" aria-hidden="true"></i>
								</li>
							</ol>
							<div class="mx-cd-unlock">
								<span><i class="fas fa-lock-open"></i> Enroll to unlock every lesson</span>
								<a href="#" class="mx-btn mx-btn-primary" onclick="submitEnrollCourse('<?php echo $coursedetail['id_course']; ?>'); return false;">Enroll Now <i class="fas fa-arrow-right"></i></a>
							</div>
						</div>
					<?php } ?>

					<!-- REQUIREMENTS (ids kept: hash targets used by template.php) -->
					<div id="Rate_this_course1" class="mx-cd-anchor"></div>
					<div id="Rate_this_course" class="mx-cd-card cs-requirements" data-reveal>
						<div class="mx-cd-card-head">
							<span class="mx-cd-ico is-navy"><i class="fas fa-clipboard-check"></i></span>
							<div>
								<h2 class="mx-cd-h2" id="Rate_this_course2">Requirements</h2>
								<p>What you need before you start</p>
							</div>
						</div>
						<?php if (mx_clean($cd_requirements) !== '') { ?>
							<div class="mx-cd-body"><?php echo $cd_requirements; ?></div>
						<?php } else { ?>
							<div class="mx-cd-body"><p>No special requirements - just a computer or phone and the motivation to learn.</p></div>
						<?php } ?>
					</div>

					<?php
					if (isset($login) && $login && $userenrollement != 0) {
						//$contents
						$displaynone = ' ';

						if ($avgcourse[$coursedetail['id_course']] == "100") {
							$comment_list_style = " display:block !important";
							$displaynone = ' ;display:block !important  ';
						} else {
							$comment_list_style = " display:none !important ";
							$displaynone = ' ;display:none !important  ';
						}

						if ($my_user_course_rating['0']['rating'] && $my_user_course_rating['0']['feedback']) {
							$displaynone = ' ;display:none !important  ';
						}

						?>
						<div class="mx-cd-alert" style="<?php echo $displaynone; ?>">
							<i class="fas fa-circle-info"></i>
							<div>
								<b>One last step before the exam</b>
								Before taking the exam, we kindly ask you to provide your feedback on the course along with your rating.
								Thank you for your input, and we wish you the best of luck!
							</div>
						</div>
						<div class="add_list_reviews mx-cd-card mx-cd-review" style="<?php echo $comment_list_style; ?>">
							<div class="mx-cd-card-head">
								<span class="mx-cd-ico is-warm"><i class="fas fa-star"></i></span>
								<div>
									<h2 class="mx-cd-h2">Rate this course</h2>
									<p>Tap a star - your rating is saved right away</p>
								</div>
							</div>
							<form id="list_rating" action="post">
								<div id="ratingSection" class="mx-cd-rater">
									<?php
									$my_rating = $my_user_course_rating['0']['rating'];
									//$averageRating = round($average, 0);
									for ($i = 1; $i <= 5; $i++) {
										$ratingClass = "star-grey";
										if ($i <= $my_rating) {
											$ratingClass = "star-highlight star-selected";
										}
										echo '<i class="fa fa-star rateButton rateClick ' . $ratingClass . '" title="' . $i . ' / 5" aria-hidden="true"></i>';
									}
									// echo "<span class='counter'>".$count . ' Reviews </span>';
									?>

									<input type="hidden" class="form-control" id="ratingClick"
										name="ratingClick" value="<?php echo $my_rating; ?>">
									<input type="hidden" class="form-control" id="id_course"
										name="id_course" value="<?php echo $coursedetail['id_course']; ?>">
								</div>
							</form>
							<?php
							$has_feedback = ($count > 0 && isset($my_user_course_rating['0']) && $my_user_course_rating['0']['feedback'] != '');
							$form_style = $has_feedback ? "display:none !important;" : "display:block !important;";
							$comment_style = $has_feedback ? "display:block !important;" : "display:none !important;";
							?>
							<form method="post" name="list_reviews" id="list_reviews" class="mx-form"
								style="<?php echo $form_style; ?>">
								<div class="block-feedback mx-cd-fb">
									<label for="reviews" class="mx-cd-fb-label">
										Your feedback
										<i class="fa fa-pen" aria-hidden="true" id="submitButton" title="Add Feedback"></i>
									</label>
									<div class="mx-input mx-input-area">
										<i class="far fa-comment-dots"></i>
										<textarea id="reviews" name="reviews"
											placeholder="What did you like? What could be better?"><?php echo $has_feedback ? htmlspecialchars($my_user_course_rating['0']['feedback']) : ''; ?></textarea>
									</div>
								</div>

								<div class="mx-cd-fb-actions">

									<?php
									$id_rating_val = '';
									if (isset($my_user_course_rating['0']['id_user_course_rating'])) {
										$id_rating_val = $my_user_course_rating['0']['id_user_course_rating'];
									} else if (isset($coursedetail['id_user_course_rating'])) {
										$id_rating_val = $coursedetail['id_user_course_rating'];
									}
									?>
									<input type="hidden" class="form-control" id="id_user_course_rating"
										name="id_user_course_rating" value="<?php echo $id_rating_val; ?>">
									<input type="hidden" class="form-control" id="id_course"
										name="id_course" value="<?php echo $coursedetail['id_course']; ?>">
									<div class="loader_button">
										<div class="postReviews mx-btn mx-btn-primary" role="button" tabindex="0">
											Post feedback <i class="fas fa-paper-plane"></i>
										</div>
										<div class="loader_loader"></div>
									</div>

								</div>
							</form>

							<div class="listComments" style="<?php echo $comment_style; ?>"
								id="listComments">
								<?php foreach ($my_user_course_rating as $row) { ?>
									<div class="comment-row mx-cd-comment" id="<?php echo $row['id_user_course_rating']; ?>">
										<?php
										$target_file = base_url() . "images/nopic.jpg";
										if ($row['image'])
											$target_file = base_url() . "uploads/user/" . $row['image'];
										echo '<img src="' . htmlspecialchars($target_file) . '" alt="" class="preview mx-cd-avatar">';
										?>
										<div class="mx-cd-comment-body">
											<div class="mx-cd-comment-top">
												<span class="posted-by"><?php echo mx_clean($row['name']); ?></span>
												<span class="posted-at postedat"><i class="far fa-calendar"></i> <?php
												$timestamp = strtotime($row['created_date']);
												$formattedDate = date("j/n/Y", $timestamp);
												echo $formattedDate; // Output: 26/4/2024
												?></span>
											</div>
											<div class="comment-text"><?php echo nl2br(htmlspecialchars($row['feedback'], ENT_QUOTES, 'UTF-8')); ?></div>
											<span class="commentedit" onclick="editFeedback()" role="button" tabindex="0" title="Edit your feedback">
												<i class="fa fa-pen"></i> Edit
											</span>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>

				</div>
			</div>
		</div>
	</section>

	<?php if (!empty($similarcourses)) { ?>
		<!-- SIMILAR COURSES -->
		<section class="mx-cd-similar">
			<div class="container">
				<div class="mx-cd-sec-head" data-reveal>
					<span class="mx-eyebrow"><span class="mx-pulse"></span>Keep learning</span>
					<h2>Browse similar courses</h2>
					<p>More courses from <?php echo mx_clean($cd_cat); ?> you may like.</p>
				</div>
				<div class="row mx-ccards">
					<?php foreach ($similarcourses as $i => $row) {
						$url = base_url() . 'coursedetails/' . $row['id_course'];
						$hours = (float) $row['hours'];
						$avg = (float) $row['avg_course_rating']; ?>
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
										<?php if ($avg > 0) { ?><span class="mx-cd-meta-rate"><i class="fas fa-star"></i> <?php echo number_format($avg, 1); ?></span><?php } ?>
									</div>
									<h3 class="mx-ccard-title"><a href="<?php echo $url; ?>"><?php echo mx_clean($row['title']); ?></a></h3>
									<p class="mx-ccard-text"><?php echo mx_clean($row['summary'], 170); ?></p>
									<div class="mx-ccard-foot">
										<span class="mx-ccard-cat"><?php echo mx_clean($cd_cat, 34); ?></span>
										<span class="mx-ccard-go">View course <i class="fas fa-arrow-right"></i></span>
									</div>
								</div>
							</article>
						</div>
					<?php } ?>
				</div>
			</div>
		</section>
	<?php } ?>

	<!-- CTA -->
	<section class="mx-cd-end">
		<div class="container">
			<div class="mx-cta" data-reveal>
				<span class="mx-cta-dots" aria-hidden="true"></span>
				<div class="mx-cta-txt">
					<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Questions?</span>
					<h3>Not sure this course is right for you?</h3>
					<p>Talk to our team - we will help you pick the right training for you or your engineers.</p>
				</div>
				<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Contact us <i class="fas fa-arrow-right"></i></a>
			</div>
		</div>
	</section>
</div>

<?php
/* popups live outside the animated content (a transformed parent would break position:fixed) */
if (isset($login) && $login && $userenrollement != 0) {
	include('course-details-popup.php');
}
if (count($questions))
	include('course-details-popup-exam.php');
?>
<div id="loginModal" style="display: none;"></div>

<?php if (empty($login['idUser'])) { ?>
<!-- "sign in to enroll" popup (replaces the browser alert) -->
<div class="mx-lp" id="mxLoginPrompt" role="dialog" aria-modal="true" aria-labelledby="mxLpTitle" aria-hidden="true">
	<div class="mx-lp-card">
		<button type="button" class="mx-lp-close" aria-label="Close"><i class="fas fa-xmark"></i></button>
		<div class="mx-lp-icon"><i class="fas fa-lock"></i></div>
		<h3 id="mxLpTitle">Sign in to enroll</h3>
		<p>In order to enroll in this course, logging in is a required first step. It only takes a moment.</p>
		<div class="mx-lp-actions">
			<button type="button" class="mx-btn mx-btn-primary mx-lp-go" data-tab="1">Sign In <i class="fas fa-arrow-right"></i></button>
			<button type="button" class="mx-lp-alt mx-lp-go" data-tab="2">New here? <b>Create an account</b></button>
		</div>
	</div>
</div>
<style>
	.mx-lp { position: fixed; inset: 0; z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 16px;
		background: rgba(8, 29, 48, .55); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
		opacity: 0; visibility: hidden; transition: opacity .3s var(--mx-ease), visibility .3s; }
	.mx-lp.is-open { opacity: 1; visibility: visible; }
	.mx-lp-card { position: relative; width: 100%; max-width: 420px; padding: 40px 32px 30px; text-align: center;
		background: #fff; border-radius: var(--mx-radius); box-shadow: var(--mx-shadow-lg); font-family: var(--mx-font);
		transform: translateY(24px) scale(.96); transition: transform .4s var(--mx-ease); }
	.mx-lp-card::before { content: ""; position: absolute; inset: 0 0 auto; height: 6px; border-radius: var(--mx-radius) var(--mx-radius) 0 0; background: var(--mx-grad); }
	.mx-lp.is-open .mx-lp-card { transform: none; }
	.mx-lp-close { position: absolute; top: 14px; right: 14px; width: 36px; height: 36px; border: 0; border-radius: 50%;
		background: var(--mx-bg); color: var(--mx-muted); font-size: 16px; cursor: pointer; transition: background .2s, color .2s; }
	.mx-lp-close:hover { background: var(--mx-line); color: var(--mx-ink); }
	.mx-lp-icon { width: 76px; height: 76px; margin: 0 auto 18px; display: flex; align-items: center; justify-content: center;
		border-radius: 50%; background: var(--mx-grad); color: #fff; font-size: 28px;
		box-shadow: 0 0 0 8px rgba(0, 173, 177, .12), 0 14px 30px -10px rgba(0, 173, 177, .7); }
	.mx-lp.is-open .mx-lp-icon { animation: mx-lp-pop .6s var(--mx-ease) .1s both; }
	@keyframes mx-lp-pop { from { transform: scale(.4) rotate(-15deg); opacity: 0; } to { transform: none; opacity: 1; } }
	.mx-lp-card h3 { margin: 0 0 10px; font-size: 22px; font-weight: 800; color: var(--mx-ink); }
	.mx-lp-card p { margin: 0 0 26px; font-size: 15px; line-height: 1.6; color: var(--mx-text); }
	.mx-lp-actions { display: flex; flex-direction: column; align-items: center; gap: 14px; }
	.mx-lp-actions .mx-btn { width: 100%; justify-content: center; border: 0; cursor: pointer; }
	.mx-lp-alt { border: 0; background: none; padding: 4px; font-size: 14px; color: var(--mx-muted); cursor: pointer; }
	.mx-lp-alt b { color: var(--mx-teal); }
	.mx-lp-alt:hover b { color: var(--mx-teal-dark); text-decoration: underline; }
	body.mx-lp-lock { overflow: hidden; }
</style>
<script>
	(function () {
		var $lp = $('#mxLoginPrompt'), lastFocus = null;
		function close() {
			$lp.removeClass('is-open').attr('aria-hidden', 'true');
			$('body').removeClass('mx-lp-lock');
			if (lastFocus) lastFocus.focus();
		}
		window.mxLoginPrompt = function () {
			lastFocus = document.activeElement;
			$lp.addClass('is-open').attr('aria-hidden', 'false');
			$('body').addClass('mx-lp-lock');
			setTimeout(function () { $lp.find('.mx-btn').trigger('focus'); }, 50);
		};
		$lp.on('click', function (e) { if (e.target === this) close(); });
		$lp.find('.mx-lp-close').on('click', close);
		$(document).on('keydown', function (e) { if (e.key === 'Escape' && $lp.hasClass('is-open')) close(); });
		// open the site's existing sign in / sign up window on the right tab
		$lp.find('.mx-lp-go').on('click', function () {
			var tab = String($(this).data('tab'));
			lastFocus = null;
			close();
			if (typeof mxOpenLogin === 'function') mxOpenLogin(tab);
		});
	})();
</script>
<?php } ?>

<script>
	$(document).on("keydown", function (event) {
		if (event.key === "F5" || (event.ctrlKey && event.key === "r")) {
			event.preventDefault();
			console.log("Page refresh prevented.");
		}
	});

	$(".postReviews").click(function () {

		$(".loader_loader").show();
		$(this).prop("disabled", true);
		var reviews = $('#reviews').val();
		if (reviews) {
			$.ajax({
				type: 'POST',
				url: "<?php echo base_url() ?>home/save_review",
				data: $('#list_reviews').serialize(), // this doesn't work
				// dataType : "json",
			})
				.done(function (response) {
					// Handle success response
					$('#listComments').html(response); // assuming response is HTML content
					$("#list_reviews").css("display", "none");
					$("#listComments").css("display", "block");

					setTimeout(function () {
						$(".msg-feedback").css("display", "none");

						// $(".loader_loader").hide();
						var baseURL = window.location.origin + window.location.pathname;
						var targetURL = baseURL; //+ '#Rate_this_course';
						window.location.href = targetURL;
					}, 1000);




				}).fail(function (response) {
					$(".loader_loader").hide();
					$(this).prop("disabled", false);
					console.error("Unknown Error!"); // not sure yet how I'd get here... ?
				});
		} else {
			// nothing typed yet: hide the spinner and point at the field
			$(".loader_loader").hide();
			$('#reviews').focus().closest('.mx-input').addClass('mx-cd-shake');
			setTimeout(function () { $('.mx-cd-shake').removeClass('mx-cd-shake'); }, 500);
		}
	});



	function submitEnrollCourse(idcourse = 0) {
		<?php
		if (!empty($login['idUser']) && $userenrollement == 0) { ?>

			$.ajax({
				type: 'POST',
				url: "<?php echo base_url(); ?>home/enrollcoursedata",
				data: {
					"idcourse": idcourse
				}, // this doesn't work
			}).done(function (response) {
				if (response) {

					$('#msg').val('Data Updated');
					// location.reload();
					mxCdEnrolled();
				} else { // Successful post, but not response came back !?
					$('#msg').val('Error');
					console.error("Post sent, but no response came back!?");
				}
			}).fail(function (response) {
				console.error("Unknown Error!"); // not sure yet how I'd get here... ?
			});
		<?php } else { ?>
			if (typeof mxLoginPrompt === 'function') mxLoginPrompt();
			else mxAlert('In order to enroll in the course, logging in is a required first step.', 'info', 'Sign in to enroll');
		<?php } ?>

	}

	// visual feedback after a successful enrollment: the buttons turn into "open my course" (reloads this page)
	function mxCdEnrolled() {
		$('.mx-cd-enroll, .mx-cd-unlock .mx-btn').each(function () {
			$(this).attr('href', window.location.origin + window.location.pathname)
				.removeAttr('onclick').addClass('is-done')
				.html('<i class="fas fa-check"></i> Enrolled - open my course');
		});
		$('.mx-cd-unlock > span').html('<i class="fas fa-circle-check"></i> You are enrolled!');
		$('.mx-cd-note').html('<i class="fas fa-circle-check"></i> Welcome aboard - your lessons are ready.');
	}
</script>
<script>
	// Get the current URL
	var url = window.location.href;
	// Split the URL by the hash symbol (#)
	var parts = url.split('#');
	// Get the part after the hash symbol
	var hashPart = parts[1]; // parts[0] will contain the part before the hash
	// Output the hash part
	if (hashPart == "examModal") document.getElementById("examClick").click();
	// startcourse
	setTimeout(function () {
		if (hashPart == "courseModal") $(".startcourse").click();
	}, 500);

	// implement start rating select/deselect
	$(".rateClick").click(function () {

		if ($(this).hasClass('star-grey')) {
			$(this).removeClass('star-grey').addClass('star-highlight star-selected');
			$(this).prevAll('.rateClick').removeClass('star-grey').addClass('star-highlight star-selected');
			$(this).nextAll('.rateClick').removeClass('star-highlight star-selected').addClass('star-grey');
		} else {
			//alert($('.star-selected').length)
			$(this).nextAll('.rateClick').removeClass('star-highlight star-selected').addClass('star-grey');
		}
		$("#ratingClick").val($('.star-selected').length); //count the num of star selected

		$.ajax({
			type: 'POST',
			url: "<?php echo base_url() ?>home/save_rating",
			data: $('#list_rating').serialize(), // this doesn't work
			dataType: "json",
		})
			.done(function (response) {
				// this doesn't work
				// $('#list_reviews').html('<div class="msg_reviews">'+response.data+'</div>');

				var baseURL = window.location.origin + window.location.pathname;
				var targetURL = baseURL; // + '#Rate_this_course';
				window.location.href = targetURL;

			}).fail(function (response) {
				console.error("Unknown Error!"); // not sure yet how I'd get here... ?
			});
	});

	// hover preview on the rating stars
	$(".rateClick").on('mouseenter', function () {
		$(this).addClass('is-hover').prevAll('.rateClick').addClass('is-hover');
	}).on('mouseleave', function () {
		$(".rateClick").removeClass('is-hover');
	});

	$("#id_course").change(function () { });

	$(document).ready(function () {
		// Attach change event listener to all radio buttons within the content div
		$('.content input[type="radio"]').change(function () {
			// Uncheck all other radio buttons within the same question group
			$(this).closest('.content').find('input[type="radio"]').not(this).prop('checked', false);
		});
	});

	function editFeedback() {
		$("#listComments").attr("style", "display: none !important");
		$("#list_reviews").attr("style", "display: block !important");
	}

	// keyboard access for the div/span buttons
	$(document).on('keydown', '.mx-cd [role="button"]', function (e) {
		if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); $(this).trigger('click'); }
	});

	// long descriptions: show the first part, "Read more" opens the rest
	(function () {
		var box = document.getElementById('mx-cd-desc');
		if (!box) return;
		var btn = box.nextElementSibling;
		if (box.scrollHeight > box.clientHeight + 40) {
			btn.hidden = false;
			btn.addEventListener('click', function () {
				var open = box.classList.toggle('is-open');
				btn.querySelector('span').textContent = open ? 'Show less' : 'Read more';
				btn.classList.toggle('is-open', open);
				if (!open) box.scrollIntoView({ behavior: 'smooth', block: 'start' });
			});
		} else {
			box.classList.remove('mx-cd-clamp');
		}
	})();
</script>
