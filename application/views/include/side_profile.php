<?php
/* ==========================================================================
   ACCOUNT SIDEBAR (modern design - backup: side_profile____28_10_2026.php)
   $login = session login (idUser, fullname, email, image, id_init_role, page)
   Roles: 1 = admin, 2 = lecturer, 3 = learner. Styles: css/pages/account.css
   ========================================================================== */
$mx_role = isset($login['id_init_role']) ? (int)$login['id_init_role'] : 0;
$mx_page = isset($login['page']) ? str_replace('_', '-', $login['page']) : '';

// initials (first letter of the first two words of the name)
$mx_initials = '';
if (!empty($login['fullname'])) {
	$mx_words = preg_split('/\s+/u', trim($login['fullname']));
	foreach (array_slice($mx_words, 0, 2) as $w) {
		if ($w !== '') $mx_initials .= mb_strtoupper(mb_substr($w, 0, 1, 'UTF-8'), 'UTF-8');
	}
}
$mx_avatar = (!empty($login['image']) && is_file('uploads/user/' . $login['image'])) ? base_url() . 'uploads/user/' . rawurlencode($login['image']) : '';
$mx_role_label = array(1 => 'Administrator', 2 => 'Lecturer', 3 => 'Learner');
$mx_role_icon  = array(1 => 'fas fa-shield-halved', 2 => 'fas fa-chalkboard-user', 3 => 'fas fa-graduation-cap');

// menu groups - same links and role conditions as before
$mx_groups = array();
$mx_groups['My learning'] = array(
	array('page' => 'editprofile',      'url' => 'edit-profile',     'icon' => 'fa-regular fa-user',         'label' => 'My Profile',       'cls' => 'tutor-dashboard-menu-settings'),
	array('page' => 'enrolled-courses', 'url' => 'enrolled-courses', 'icon' => 'fa-solid fa-book-open',      'label' => 'Enrolled Courses', 'cls' => 'tutor-dashboard-menu-enrolled-courses'),
);
if ($mx_role == 2) { // lecturer
	$mx_groups['My learning'][] = array('page' => 'mycourses', 'url' => 'mycourses', 'icon' => 'fa-solid fa-chalkboard-user', 'label' => 'My Courses', 'cls' => 'tutor-dashboard-menu-enrolled-courses');
}
$mx_groups['My learning'][] = array('page' => 'examcourse', 'url' => 'examcourse', 'icon' => 'fa-solid fa-file-pen', 'label' => 'List Exam', 'cls' => 'tutor-dashboard-menu-enrolled-courses');
if ($mx_role == 3) { // learner
	$mx_groups['My learning'][] = array('page' => 'courseexamhistory', 'url' => 'courseexamhistory', 'icon' => 'fa-solid fa-clock-rotate-left', 'label' => 'Exam History', 'cls' => 'tutor-dashboard-menu-enrolled-courses');
}
$mx_groups['My learning'][] = array('page' => 'wishlist', 'url' => 'wishlist', 'icon' => 'fa-regular fa-heart', 'label' => 'Wishlist', 'cls' => 'tutor-dashboard-menu-wishlist');

if ($mx_role < 3) { // admin || lecturer
	$mx_groups['Teaching'] = array(
		array('page' => 'feedbackhistory', 'url' => 'feedbackhistory', 'icon' => 'fa-regular fa-comments', 'label' => 'Feedbacks', 'cls' => 'tutor-dashboard-menu-enrolled-courses'),
	);
}
if ($mx_role == 1) { // admin
	$mx_groups['Administration'] = array(
		array('page' => 'listusers',         'url' => 'listusers',         'icon' => 'fa-solid fa-users',          'label' => 'List Users',         'cls' => 'tutor-dashboard-menu-enrolled-courses'),
		array('page' => 'listuserscontent',  'url' => 'listuserscontent',  'icon' => 'fa-solid fa-list-check',     'label' => 'List users content', 'cls' => 'tutor-dashboard-menu-enrolled-courses'),
		array('page' => 'employees',         'url' => 'employees',         'icon' => 'fa-solid fa-id-card',        'label' => 'List employees',     'cls' => 'tutor-dashboard-menu-enrolled-courses'),
		array('page' => 'listcourses',       'url' => 'listcourses',       'icon' => 'fa-solid fa-layer-group',    'label' => 'Courses',            'cls' => 'tutor-dashboard-menu-enrolled-courses'),
		array('page' => 'totalexamshistory', 'url' => 'totalexamshistory', 'icon' => 'fa-solid fa-chart-column',   'label' => 'Exam History',       'cls' => 'tutor-dashboard-menu-enrolled-courses'),
	);
}
?>
<aside class="mx-side" data-reveal>
	<div class="mx-side-head">
		<span class="mx-side-glow" aria-hidden="true"></span>
		<span class="mx-side-dots" aria-hidden="true"></span>

		<div class="mx-side-avatar">
			<?php if ($mx_avatar) { ?>
				<img src="<?php echo $mx_avatar; ?>" alt="">
			<?php } else { ?>
				<span><?php echo htmlspecialchars($mx_initials !== '' ? $mx_initials : '?', ENT_QUOTES, 'UTF-8'); ?></span>
			<?php } ?>
			<i class="mx-side-online" aria-hidden="true"></i>
		</div>

		<div class="mx-side-who">
			<b><?php echo htmlspecialchars(isset($login['fullname']) ? $login['fullname'] : '', ENT_QUOTES, 'UTF-8'); ?></b>
			<?php if (!empty($login['email'])) { ?><small><?php echo htmlspecialchars($login['email'], ENT_QUOTES, 'UTF-8'); ?></small><?php } ?>
			<?php if (isset($mx_role_label[$mx_role])) { ?>
				<span class="mx-side-role"><i class="<?php echo $mx_role_icon[$mx_role]; ?>" aria-hidden="true"></i><?php echo $mx_role_label[$mx_role]; ?></span>
			<?php } ?>
		</div>

		<button class="mx-side-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidenav" aria-controls="sidenav" aria-expanded="false" aria-label="Toggle account menu">
			<span>Menu</span><i class="fa-solid fa-bars" aria-hidden="true"></i>
		</button>
	</div>

	<nav class="mx-side-nav collapse" id="sidenav" aria-label="Account menu">
		<?php foreach ($mx_groups as $mx_group => $mx_items) { ?>
			<span class="mx-side-label"><?php echo $mx_group; ?></span>
			<ul class="tutor-dashboard-permalinks mx-side-list">
				<?php foreach ($mx_items as $it) {
					$on = ($mx_page === $it['page']); ?>
					<li class="<?php echo $it['cls']; ?> nav-item<?php echo $on ? ' active' : ''; ?>">
						<a href="<?php echo base_url() . $it['url']; ?>" class="mx-side-link<?php echo $on ? ' is-on' : ''; ?>" <?php echo $on ? 'aria-current="page"' : ''; ?>>
							<i class="<?php echo $it['icon']; ?> fa-fw" aria-hidden="true"></i>
							<span><?php echo $it['label']; ?></span>
							<i class="fa-solid fa-chevron-right mx-side-arrow" aria-hidden="true"></i>
						</a>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>

		<ul class="tutor-dashboard-permalinks mx-side-list mx-side-foot">
			<li class="tutor-dashboard-menu-logout nav-item">
				<a href="<?php echo base_url(); ?>logout" class="mx-side-link mx-side-logout" data-no-instant=""><i class="fa-solid fa-right-from-bracket fa-fw" aria-hidden="true"></i><span>Logout</span></a>
			</li>
		</ul>
	</nav>
</aside>
