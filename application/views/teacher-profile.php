<?php
/* ==========================================================================
   TEACHER PROFILE (modern design - backup: teacher-profile____28_10_2026.php)
   $lecturerinfo = the user row (name, title, image, email, aboutme, website, socials...)
   $courses      = active courses linked to the lecturer
   ========================================================================== */

// Clean the CKEditor HTML (same rules as the news article): drop inline styles/spans,
// <div> lines -> paragraphs, no empty lines, bold-only lines -> sub-headings, <br> lines -> list.
if (!function_exists('mx_rich_html')) {
	function mx_rich_html($html)
	{
		$html = preg_replace('/\s(style|class|align|face|color|size)\s*=\s*"[^"]*"/i', '', (string)$html);
		$html = preg_replace('#</?(span|font)\b[^>]*>#i', '', $html);
		$html = preg_replace('#<(/?)div\b[^>]*>#i', '<$1p>', $html);
		$html = preg_replace('#<br\s*/?>#i', '<br>', $html);
		$html = preg_replace('#<p>(\s|&nbsp;|\xC2\xA0|<br>)*</p>#iu', '', $html);
		$plain = function ($s) { return trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($s), ENT_QUOTES, 'UTF-8'))); };
		$html = preg_replace_callback('#<p>(.*?)</p>#is', function ($m) use ($plain) {
			$inner = trim($m[1]);
			$lines = array();
			foreach (preg_split('#<br>#i', $inner) as $l) if ($plain($l) !== '') $lines[] = trim($l);
			if (!$lines) return '';
			if (count($lines) < 3) {
				if (count($lines) == 1 && preg_match('#^<(strong|b)>(.*?)</\1>\s*:?$#is', $lines[0], $s) && mb_strlen($plain($s[2])) <= 80) {
					return '<h3 class="mx-rich-sub">' . trim($s[2]) . '</h3>';
				}
				return '<p>' . implode('<br>', $lines) . '</p>';
			}
			$out = '';
			if (preg_match('/:\s*$/', $plain($lines[0])) && mb_strlen($plain($lines[0])) <= 90) {
				$out .= '<p class="mx-rich-label">' . preg_replace('/:\s*$/', '', array_shift($lines)) . '</p>';
			}
			foreach ($lines as $l) if (mb_strlen($plain($l)) > 160) return $out . '<p>' . implode('</p><p>', $lines) . '</p>';
			$numbered = true;
			foreach ($lines as $l) if (!preg_match('/^\d+[.)]\s+/', $plain($l))) $numbered = false;
			$items = '';
			foreach ($lines as $l) {
				if ($numbered) {
					$items .= '<li>' . preg_replace('/^\s*\d+[.)]\s+/', '', $l) . '</li>';
				} elseif (preg_match('/^([a-z])[.)]\s+/i', $plain($l), $mk)) {
					$items .= '<li class="is-head" data-mark="' . strtolower($mk[1]) . '">' . preg_replace('/^\s*[a-z][.)]\s+/i', '', $l) . '</li>';
				} else {
					$items .= '<li>' . $l . '</li>';
				}
			}
			$tag = $numbered ? 'ol' : 'ul';
			return $out . '<' . $tag . '>' . $items . '</' . $tag . '>';
		}, $html);
		return trim($html);
	}
}

// admin-entered link (may be "www.site.com") -> safe absolute URL, or '' when it is not a link
if (!function_exists('mx_teacher_link')) {
	function mx_teacher_link($u)
	{
		$u = trim((string)$u);
		if ($u === '' || preg_match('/\s/', $u)) return '';
		if (!preg_match('#^https?://#i', $u)) $u = 'https://' . ltrim($u, '/');
		$host = parse_url($u, PHP_URL_HOST);
		if (!filter_var($u, FILTER_VALIDATE_URL) || !$host || strpos($host, '.') === false) return '';
		return htmlspecialchars($u, ENT_QUOTES, 'UTF-8');
	}
}

$t = (!empty($lecturerinfo) && is_array($lecturerinfo)) ? $lecturerinfo : array();
$mx_found = !empty($t) && trim((string)$t['name']) !== '';
$mx_name = $mx_found ? $t['name'] : 'Lecturer not found';
$mx_role = ($mx_found && trim((string)$t['title']) !== '' && !is_numeric(trim($t['title']))) ? $t['title'] : '';
$mx_occupation = '';
foreach (array('occupation', 'profession') as $k) {
	if ($mx_occupation === '' && !empty($t[$k]) && !is_numeric(trim($t[$k]))) $mx_occupation = $t[$k];
}
$mx_years = ($mx_found && isset($t['years_of_exp']) && (int)$t['years_of_exp'] > 0) ? (int)$t['years_of_exp'] : 0;
$mx_ncourses = count($courses);

$mx_photo = '';
if ($mx_found && !empty($t['image']) && is_file("uploads/user/" . $t['image'])) {
	$mx_photo = optimized_image_url('user', $t['image'], 400);
}
$mx_initials = '';
foreach (preg_split('/\s+/', trim(html_entity_decode(strip_tags((string)$mx_name), ENT_QUOTES, "UTF-8"))) as $w) if ($w !== '' && mb_strlen($mx_initials) < 2) $mx_initials .= htmlspecialchars(mb_strtoupper(mb_substr($w, 0, 1)), ENT_QUOTES, "UTF-8");

$mx_about = $mx_found ? mx_rich_html($t['aboutme']) : '';
$mx_email = ($mx_found && filter_var(trim((string)$t['email']), FILTER_VALIDATE_EMAIL)) ? trim($t['email']) : '';
$mx_website = $mx_found ? mx_teacher_link($t['website']) : '';
if ($mx_website === '') $mx_website = 'https://stedltd.com/';
$mx_social = array();
if ($mx_found) {
	foreach (array('linkedin' => 'fab fa-linkedin-in', 'twitter' => 'fab fa-x-twitter', 'facebook' => 'fab fa-facebook-f') as $k => $ico) {
		$link = isset($t[$k]) ? mx_teacher_link($t[$k]) : '';
		if ($link !== '') $mx_social[] = array('url' => $link, 'icon' => $ico, 'label' => ucfirst($k));
	}
}

// category names for the course cards (active categories are loaded for the header)
$mx_cats = array();
if (!empty($category)) foreach ($category as $c) $mx_cats[$c['id_category']] = $c['title'];

$mx_stats = array();
if ($mx_found) {
	$mx_stats[] = array('icon' => 'fas fa-book-open', 'value' => $mx_ncourses, 'label' => $mx_ncourses == 1 ? 'Course' : 'Courses');
	if ($mx_years) $mx_stats[] = array('icon' => 'fas fa-briefcase', 'value' => $mx_years . '+ yrs', 'label' => 'Experience');
	$mx_stats[] = array('icon' => 'fas fa-certificate', 'value' => 'Certified', 'label' => 'STED training');
}

$this->load->view('include/_page_hero', array(
	'ph_title'   => $mx_name,
	'ph_eyebrow' => $mx_role !== '' ? $mx_role : 'Lecturer profile',
	'ph_text'    => $mx_found ? ($mx_occupation !== '' ? $mx_occupation . ' - sharing hands-on engineering experience through STED\'s online courses.' : 'One of the engineers and trainers behind STED\'s practical, industry-focused online courses.') : 'This profile may have been moved or removed.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'Lecturers'),
		array('label' => $mx_name),
	),
	'ph_stats'   => $mx_stats,
));
?>

<div class="mx-inner mx-teacher">

	<?php if ($mx_found) { ?>
		<div class="mx-teacher-wrap">
			<div class="container">
				<div class="row">

					<!-- PROFILE CARD -->
					<div class="col-lg-4">
						<aside class="mx-teacher-card" data-reveal>
							<div class="mx-teacher-photo">
								<?php if ($mx_photo) { ?>
									<img src="<?php echo $mx_photo; ?>" alt="<?php echo mx_clean($mx_name); ?>">
								<?php } else { ?>
									<span class="mx-teacher-initials" aria-hidden="true"><?php echo $mx_initials; ?></span>
								<?php } ?>
							</div>
							<h2><?php echo mx_clean($mx_name); ?></h2>
							<span class="mx-teacher-role"><?php echo $mx_role !== '' ? mx_clean($mx_role, 60) : 'STED Lecturer'; ?></span>

							<ul class="mx-teacher-facts">
								<li><i class="fas fa-book-open"></i><b><?php echo $mx_ncourses; ?></b><small><?php echo $mx_ncourses == 1 ? 'Course' : 'Courses'; ?></small></li>
								<li><i class="fas fa-<?php echo $mx_years ? 'briefcase' : 'laptop'; ?>"></i><b><?php echo $mx_years ? $mx_years . '+' : 'Online'; ?></b><small><?php echo $mx_years ? 'Years of experience' : 'Self-paced learning'; ?></small></li>
								<?php if ($mx_occupation !== '') { ?>
									<li class="is-wide"><i class="fas fa-helmet-safety"></i><b><?php echo mx_clean($mx_occupation, 80); ?></b><small>Occupation</small></li>
								<?php } ?>
							</ul>

							<div class="mx-teacher-actions">
								<?php if ($mx_email) { ?>
									<a class="mx-btn mx-btn-primary" href="mailto:<?php echo htmlspecialchars($mx_email, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($mx_email, ENT_QUOTES, 'UTF-8'); ?>"><i class="far fa-envelope"></i><span><?php echo htmlspecialchars($mx_email, ENT_QUOTES, 'UTF-8'); ?></span></a>
								<?php } ?>
								<a class="mx-btn mx-teacher-ghost" href="<?php echo $mx_website; ?>" target="_blank" rel="noopener"><i class="fas fa-globe"></i><span>Website</span></a>
							</div>

							<?php if ($mx_social) { ?>
								<div class="mx-teacher-social">
									<?php foreach ($mx_social as $s) { ?>
										<a href="<?php echo $s['url']; ?>" target="_blank" rel="noopener" aria-label="<?php echo $s['label']; ?>"><i class="<?php echo $s['icon']; ?>"></i></a>
									<?php } ?>
								</div>
							<?php } ?>
						</aside>
					</div>

					<!-- ABOUT -->
					<div class="col-lg-8">
						<section class="mx-teacher-about" data-reveal style="--d:120ms">
							<div class="mx-teacher-head">
								<i class="fas fa-user-tie" aria-hidden="true"></i>
								<div>
									<h2>About me</h2>
									<p><?php echo $mx_role !== '' ? mx_clean($mx_role, 80) : 'Lecturer at STED'; ?></p>
								</div>
							</div>
							<?php if (trim(strip_tags($mx_about)) !== '') { ?>
								<div class="mx-rich"><?php echo $mx_about; ?></div>
							<?php } else { ?>
								<div class="mx-empty">
									<span class="mx-empty-ico"><i class="fas fa-feather-pointed"></i></span>
									<h3>Biography coming soon</h3>
									<p><?php echo mx_clean($mx_name); ?> hasn't added a biography yet. Browse the courses below or contact us to learn more.</p>
								</div>
							<?php } ?>
						</section>
					</div>

				</div>
			</div>
		</div>

		<?php if (count($courses)) { ?>
			<!-- COURSES -->
			<section class="mx-teacher-courses">
				<div class="container">
					<div class="mx-teacher-courses-head" data-reveal>
						<div>
							<span class="mx-eyebrow"><span class="mx-pulse"></span>Courses</span>
							<h2>My courses <span>(<?php echo count($courses); ?>)</span></h2>
							<p>Online courses taught by <?php echo mx_clean($mx_name); ?></p>
						</div>
					</div>
					<div class="row mx-ccards">
						<?php foreach ($courses as $i => $row) {
							$url = base_url() . 'coursedetails/' . $row['id_course'];
							$cat = isset($mx_cats[$row['id_category']]) ? $mx_cats[$row['id_category']] : 'STED course'; ?>
							<div class="col-md-6 col-lg-4 d-flex">
								<article class="mx-ccard" data-reveal style="--d:<?php echo min($i % 3, 2) * 110; ?>ms">
									<a class="mx-ccard-media" href="<?php echo $url; ?>" tabindex="-1" aria-hidden="true">
										<img src="<?php echo course_image_url($row['image']); ?>" alt="" loading="lazy">
										<span class="mx-ccard-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
									</a>
									<div class="mx-ccard-body">
										<div class="mx-ccard-meta">
											<span><i class="fas fa-laptop"></i> Online</span>
											<span><i class="fas fa-certificate"></i> Certificate</span>
										</div>
										<h3 class="mx-ccard-title"><a href="<?php echo $url; ?>"><?php echo mx_clean($row['title']); ?></a></h3>
										<p class="mx-ccard-text"><?php echo mx_clean($row['summary'], 170); ?></p>
										<div class="mx-ccard-foot">
											<span class="mx-ccard-cat"><?php echo mx_clean($cat, 34); ?></span>
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

	<?php } else { ?>
		<section class="mx-cat-section">
			<div class="container">
				<div class="mx-empty" data-reveal>
					<span class="mx-empty-ico"><i class="fas fa-user-slash"></i></span>
					<h3>We couldn't find this lecturer</h3>
					<p>The profile may have been moved or removed. Explore our courses from the home page instead.</p>
					<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>">Back to home <i class="fas fa-arrow-right"></i></a>
				</div>
			</div>
		</section>
	<?php } ?>

	<!-- CTA -->
	<section class="mx-teacher-end<?php echo ($mx_found && !count($courses)) ? ' pt-4' : ''; ?>">
		<div class="container">
			<div class="mx-cta" data-reveal>
				<span class="mx-cta-dots" aria-hidden="true"></span>
				<div class="mx-cta-txt">
					<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Training for teams</span>
					<h3>Want this expertise for your team?</h3>
					<p>Talk to us about tailored training led by STED's engineers.</p>
				</div>
				<a class="mx-btn mx-btn-primary" href="<?php echo base_url(); ?>contactus">Talk to us <i class="fas fa-arrow-right"></i></a>
			</div>
		</div>
	</section>
</div>
