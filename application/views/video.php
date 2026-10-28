<?php
/* ==========================================================================
   COURSE VIDEOS (modern design - backup: video____28_10_2026.php)
   Route video/<id> -> home::videodata. Styles: css/pages/course.css (.mx-vid-*)
   $coursedetail (rows), $video_course, $coursesummary, $coursecontent, $login
   ========================================================================== */
$vd = isset($coursedetail[0]) ? $coursedetail[0] : array('coursetitle' => 'Course videos', 'categtitle' => '', 'summary' => '', 'hours' => 0);
$vd_id = (int) $this->uri->segment(2);
$vd_lectures = 0;
$vd_hours = 0;
foreach ($coursesummary as $row) {
	$vd_lectures = (int) $row['contentnbr'];
	$vd_hours = (float) $row['sumhrs'];
}
$vd_logged = !empty($login['idUser']);

// one playable source per video row
$vd_items = array();
foreach ($video_course as $row) {
	if ($row['youtube']) {
		$yt = trim($row['youtube']); // usually the bare video id; full YouTube links are accepted too
		if (preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{6,})~', $yt, $ym)) $yt = $ym[1];
		$vd_items[] = array('type' => 'youtube', 'src' => 'https://www.youtube.com/embed/' . rawurlencode($yt), 'row' => $row);
	} elseif ($row['video_file']) {
		$vd_items[] = array('type' => 'mp4', 'src' => base_url() . 'uploads/video/' . $row['video_file'], 'row' => $row);
	}
}

$vd_stats = array();
if (count($video_course)) $vd_stats[] = array('icon' => 'fas fa-play', 'value' => count($video_course), 'label' => count($video_course) == 1 ? 'Video' : 'Videos');
if ($vd_lectures) $vd_stats[] = array('icon' => 'fas fa-layer-group', 'value' => $vd_lectures, 'label' => $vd_lectures == 1 ? 'Lecture' : 'Lectures');
if ($vd_hours > 0) $vd_stats[] = array('icon' => 'far fa-clock', 'value' => max(1, round($vd_hours)), 'label' => 'Total hours');

$this->load->view('include/_page_hero', array(
	'ph_title'   => $vd['coursetitle'],
	'ph_eyebrow' => 'Course videos',
	'ph_text'    => $vd['summary'],
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => $vd['categtitle'] ? $vd['categtitle'] : 'Courses'),
		array('label' => $vd['coursetitle']),
	),
	'ph_stats'   => $vd_stats,
));
?>

<div class="mx-inner mx-vid">
	<section class="mx-vid-main">
		<div class="container">

			<?php if ($vd_logged) { ?>

				<?php if ($vd_items) {
					$first = $vd_items[0]; ?>
					<div class="mx-vid-stage" data-reveal>
						<div class="mx-vid-player">
							<div class="mx-vid-screen" id="mx-vid-screen">
								<?php if ($first['type'] == 'youtube') { ?>
									<iframe src="<?php echo $first['src']; ?>" title="<?php echo mx_clean($first['row']['title']); ?>" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
								<?php } else { ?>
									<video controls controlsList="nodownload" preload="metadata" src="<?php echo $first['src']; ?>"></video>
								<?php } ?>
							</div>
							<div class="mx-vid-now">
								<span class="mx-eyebrow"><span class="mx-pulse"></span>Now playing</span>
								<h2 id="mx-vid-title"><?php echo mx_clean($first['row']['title']); ?></h2>
								<p id="mx-vid-text"><?php echo mx_clean($first['row']['summary'], 320); ?></p>
							</div>
						</div>

						<aside class="mx-vid-list" aria-label="Videos">
							<span class="mx-vid-list-label"><i class="fas fa-list"></i> <?php echo count($vd_items); ?> videos</span>
							<?php foreach ($vd_items as $i => $it) {
								$thumb = !empty($it['row']['image']) ? base_url() . 'uploads/video/' . $it['row']['image'] : ''; ?>
								<button type="button" class="mx-vid-item<?php echo $i == 0 ? ' is-on' : ''; ?>"
									data-type="<?php echo $it['type']; ?>" data-src="<?php echo htmlspecialchars($it['src'], ENT_QUOTES, 'UTF-8'); ?>"
									data-title="<?php echo mx_clean($it['row']['title']); ?>" data-text="<?php echo mx_clean($it['row']['summary'], 320); ?>">
									<span class="mx-vid-thumb"<?php echo $thumb ? ' style="background-image:url(\'' . htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8') . '\')"' : ''; ?>><i class="fas fa-play"></i></span>
									<span class="mx-vid-item-txt">
										<b><?php echo mx_clean($it['row']['title'], 70); ?></b>
										<small><?php echo $it['type'] == 'youtube' ? 'YouTube' : 'Video'; ?> · <?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></small>
									</span>
								</button>
							<?php } ?>
						</aside>
					</div>
				<?php } else { ?>
					<div class="mx-empty" data-reveal>
						<span class="mx-empty-ico"><i class="fas fa-film"></i></span>
						<h3>No videos yet</h3>
						<p>The videos of this course are not available yet. You can follow the lessons from the course page.</p>
						<?php if ($vd_id) { ?><a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>coursedetails/<?php echo $vd_id; ?>">Open the course <i class="fas fa-arrow-right"></i></a><?php } ?>
					</div>
				<?php } ?>

				<?php if ($coursecontent) { ?>
					<!-- COURSE CONTENT -->
					<div class="mx-cd-card mx-vid-content" data-reveal>
						<div class="mx-cd-card-head">
							<span class="mx-cd-ico is-warm"><i class="fas fa-list-check"></i></span>
							<div>
								<h2 class="mx-cd-h2">Course content</h2>
								<p>Level Beginner &bull; <?php echo $vd_lectures; ?> Lectures &bull; <?php echo $vd_hours > 0 ? max(1, round($vd_hours)) : 0; ?> Total Hours</p>
							</div>
						</div>
						<div id="accordion" class="mx-vid-acc" role="tablist">
							<?php foreach ($coursecontent as $i => $row) {
								$hid = 'mx-vid-h' . $i;
								$cid = 'mx-vid-c' . $i; ?>
								<div class="mx-vid-acc-item">
									<div class="mx-vid-acc-head" role="tab" id="<?php echo $hid; ?>">
										<a class="collapsed" data-toggle="collapse" href="#<?php echo $cid; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $cid; ?>">
											<span class="mx-cd-lnum"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
											<span class="mx-vid-acc-title"><?php echo mx_clean($row['title']); ?></span>
											<?php if ((float) $row['hours'] > 0) { ?><span class="mx-vid-acc-hrs"><i class="far fa-clock"></i> <?php echo $row['hours']; ?> hrs</span><?php } ?>
											<i class="fas fa-chevron-down mx-vid-acc-arrow" aria-hidden="true"></i>
										</a>
									</div>
									<div id="<?php echo $cid; ?>" class="collapse" role="tabpanel" aria-labelledby="<?php echo $hid; ?>" data-parent="#accordion">
										<div class="mx-cd-body mx-vid-acc-body">
											<?php echo mx_clean($row['description']) !== '' ? $row['description'] : '<p>No description for this lecture.</p>'; ?>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

			<?php } else { ?>
				<div class="mx-empty" data-reveal>
					<span class="mx-empty-ico"><i class="fas fa-lock"></i></span>
					<h3>Log in to watch the videos</h3>
					<p>The videos of this course are available to registered learners. Log in with your account to start watching.</p>
					<button type="button" class="mx-btn mx-btn-primary mx-vid-login" data-toggle="modal" data-target="#myModal">Log in <i class="fas fa-arrow-right"></i></button>
				</div>
			<?php } ?>

		</div>
	</section>
</div>

<script>
	// playlist: switch the main player to the chosen video
	(function () {
		var screen = document.getElementById('mx-vid-screen');
		if (!screen) return;
		var title = document.getElementById('mx-vid-title'), text = document.getElementById('mx-vid-text');
		[].forEach.call(document.querySelectorAll('.mx-vid-item'), function (btn) {
			btn.addEventListener('click', function () {
				var type = btn.getAttribute('data-type'), src = btn.getAttribute('data-src');
				var el = document.createElement(type === 'youtube' ? 'iframe' : 'video');
				if (type === 'youtube') {
					el.src = src + '?autoplay=1';
					el.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
					el.setAttribute('allowfullscreen', '');
				} else {
					el.src = src; el.controls = true; el.autoplay = true;
					el.setAttribute('controlsList', 'nodownload');
				}
				screen.innerHTML = '';
				screen.appendChild(el);
				title.textContent = btn.getAttribute('data-title');
				text.textContent = btn.getAttribute('data-text');
				[].forEach.call(document.querySelectorAll('.mx-vid-item'), function (b) { b.classList.toggle('is-on', b === btn); });
				if (window.innerWidth < 992) screen.scrollIntoView({ behavior: 'smooth', block: 'center' });
			});
		});
	})();
</script>
