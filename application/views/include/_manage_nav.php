<?php
/* ==========================================================================
   MANAGEMENT PAGES - quick navigation between the logged-in tools
   (same role rules as include/side_profile.php)
   Variables:
     $mg_active  string  key of the current page (listusers, employees, listuserscontent, listcourses, feedbackhistory, listapidata)
   Uses $login (set by the controller) for the role.
   ========================================================================== */
$mg_active = isset($mg_active) ? $mg_active : '';
$mg_role   = (isset($login['id_init_role'])) ? (int)$login['id_init_role'] : 0;

$mg_links = array(
	array('key' => 'editprofile', 'url' => 'edit-profile', 'icon' => 'fa-regular fa-user', 'label' => 'My profile'),
);
if ($mg_role == 1) { // admin
	$mg_links[] = array('key' => 'listusers', 'url' => 'listusers', 'icon' => 'fas fa-users', 'label' => 'Users');
	$mg_links[] = array('key' => 'listuserscontent', 'url' => 'listuserscontent', 'icon' => 'fas fa-user-graduate', 'label' => 'Enrollments');
	$mg_links[] = array('key' => 'employees', 'url' => 'employees', 'icon' => 'fas fa-id-badge', 'label' => 'Employees');
	$mg_links[] = array('key' => 'listcourses', 'url' => 'listcourses', 'icon' => 'fas fa-book-open', 'label' => 'Courses');
}
if ($mg_role > 0 && $mg_role < 3) { // admin + lecturer
	$mg_links[] = array('key' => 'feedbackhistory', 'url' => 'feedbackhistory', 'icon' => 'far fa-comment-dots', 'label' => 'Feedbacks');
}
if ($mg_role == 1) {
	$mg_links[] = array('key' => 'totalexamshistory', 'url' => 'totalexamshistory', 'icon' => 'fas fa-clipboard-check', 'label' => 'Exam history');
}
if ($mg_active == 'listapidata') {
	$mg_links[] = array('key' => 'listapidata', 'url' => 'listapidata', 'icon' => 'fas fa-wand-magic-sparkles', 'label' => 'STED AI');
}
?>
<nav class="mx-mg-nav" aria-label="Management pages" data-reveal>
	<span class="mx-mg-nav-label"><i class="fas fa-sliders" aria-hidden="true"></i> Tools</span>
	<div class="mx-mg-nav-links">
		<?php foreach ($mg_links as $l) {
			$on = ($l['key'] === $mg_active); ?>
			<a class="mx-mg-nav-link<?php echo $on ? ' is-on' : ''; ?>" href="<?php echo base_url() . $l['url']; ?>"<?php echo $on ? ' aria-current="page"' : ''; ?>>
				<i class="<?php echo $l['icon']; ?>" aria-hidden="true"></i><?php echo $l['label']; ?>
			</a>
		<?php } ?>
	</div>
</nav>
