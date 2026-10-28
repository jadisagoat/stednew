<div class="team-3-photo text-center">
	<div class="ud-heading-xl_side">
		<span>
			<?php
			if ($login['fullname']) {

				$myString = explode(" ", $login['fullname']);
				$first = $last = 0;
				if (isset($myString['0']) && $myString['0'])
					$first = substr($myString['0'], 0, 1);

				if (isset($myString['1']) && $myString['1'])
					$last = substr($myString['1'], 0, 1);
				echo $first . "" . $last;
			}
			?>
		</span>
	</div>
</div>

<!-- Social Icons -->
<div class="tm-3-social clearfix">
	<ul class="text-center clearfix">
		<li><a href="#" class="ico-facebook ico-valign "><i class="fab 
			fa-facebook-f"></i></a></li>
		<li><a href="#" class="ico-twitter ico-valign"><i class="fab fa-twitter"></i></a></li>
		<li><a href="#" class="ico-google-plus ico-valign"><i class="fab fa-google-plus-g"></i></a></li>
		<li><a href="#" class="ico-linkedin ico-valign"><i class="fab fa-linkedin-in"></i></a></li>
	</ul>
</div>

<nav class="navbar navbar-expand-md navbar-light shadow-sm mb-4 mb-lg-0">
	<a href="#" class="navbar-brand d-xl-none d-lg-none d-md-none text-inherit fw-bold">Menu</a>

	<button class="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light collapsed" type="button" data-toggle="collapse" data-target="#sidenav" aria-controls="sidenav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="fa-solid fa-bars fa-fw"></span>
	</button>
	<div class="collapse navbar-collapse" id="sidenav">
		<div class="navbar-nav flex-column">
			<ul class="tutor-dashboard-permalinks list-unstyled ms-n2 mb-0">





				<!-- <li class="tutor-dashboard-menu-index nav-item">
					<a href="<?php echo base_url(); ?>edit-profile" class="nav-link">
						<i class="fa-solid fa-house-user fa-fw"></i>
						Dashboard
					</a>
				</li> -->

				<li class="tutor-dashboard-menu-settings nav-item <?php if (isset($login['page']) && $login['page'] == 'editprofile') {
																		echo 'active';
																	} ?>">
					<a href="<?php echo base_url(); ?>edit-profile" class="nav-link">
						<i class="fa-regular fa-user fa-fw"></i>
						My Profile
					</a>
				</li>

				<li class="tutor-dashboard-menu-enrolled-courses nav-item <?php if (isset($login['page']) && $login['page'] == 'enrolled-courses') {
																				echo 'active';
																			} ?>">
					<a href="<?php echo base_url(); ?>enrolled-courses" class="nav-link">
						<i class="fa-sharp fa-solid fa-book fa-fw"></i>
						Enrolled Courses
					</a>
				</li>

				<?php if ($login['id_init_role'] == 2) { // lecturer  
				?>
					<li class="tutor-dashboard-menu-enrolled-courses nav-item <?php if (isset($login['page']) && $login['page'] == 'mycourses') {
																					echo 'active';
																				} ?>">
						<a href="<?php echo base_url(); ?>mycourses" class="nav-link">
							<i class="fa-sharp fa-solid fa-book fa-fw"></i>
							My Courses
						</a>
					</li>
				<?php } ?>


				<?php
				if ($login['id_init_role'] == 1) { // admin
				?>
					<li class="tutor-dashboard-menu-enrolled-courses nav-item 
						<?php if (isset($login['page']) && $login['page'] == 'listusers') {
							echo 'active';
						} ?>">
						<a href="<?php echo base_url(); ?>listusers" class="nav-link">
							<i class="fa-sharp fa-solid fa-book fa-fw"></i>
							List Users
						</a>
					</li>

					<li class="tutor-dashboard-menu-enrolled-courses nav-item 
						<?php if (isset($login['page']) && $login['page'] == 'listuserscontent') {
							echo 'active';
						} ?>">
						<a href="<?php echo base_url(); ?>listuserscontent" class="nav-link">
							<i class="fa-sharp fa-solid fa-book fa-fw"></i>
							List users content
						</a>
					</li>

					<li class="tutor-dashboard-menu-enrolled-courses nav-item 
						<?php if (isset($login['page']) && $login['page'] == 'employees') {
							echo 'active';
						} ?>">
						<a href="<?php echo base_url(); ?>employees" class="nav-link">
							<i class="fa-sharp fa-solid fa-book fa-fw"></i>
							List employees
						</a>
					</li>

				<?php } ?>


				<li class="tutor-dashboard-menu-enrolled-courses nav-item 
						<?php if (isset($login['page']) && $login['page'] == 'examcourse') {
							echo 'active';
						} ?>">
					<a href="<?php echo base_url(); ?>examcourse" class="nav-link">
						<i class="fa-sharp fa-solid fa-book fa-fw"></i>
						List Exam
					</a>
				</li>

				<?php if ($login['id_init_role'] == 3) { // Student  
				?>
					<li class="tutor-dashboard-menu-enrolled-courses nav-item 
						<?php if (isset($login['page']) && $login['page'] == 'courseexamhistory') {
							echo 'active';
						} ?>">
						<a href="<?php echo base_url(); ?>courseexamhistory" class="nav-link">
							<i class="fa-sharp fa-solid fa-book fa-fw"></i>
							Exam History
						</a>
					</li>
				<?php } ?>
				<!-- 
				<li class="tutor-dashboard-menu-enrolled-courses nav-item 
						<?php if (isset($login['page']) && $login['page'] == 'listapidata') {
							echo 'active';
						} ?>">
					<a href="<?php echo base_url(); ?>listapidata" class="nav-link">
						<i class="fa-sharp fa-solid fa-book fa-fw"></i>
						AI GPT (Questions)
					</a>
				</li> -->


				<li class="tutor-dashboard-menu-wishlist nav-item <?php if (isset($login['page']) && $login['page'] == 'wishlist') {
																		echo 'active';
																	} ?>">
					<a href="<?php echo base_url(); ?>wishlist" class="nav-link">
						<i class="fa-regular fa-heart fa-fw"></i>
						wishlist
					</a>
				</li>

				<?php if ($login['id_init_role'] < 3) { // Admin || Lecturer  
				?>
					<li class="tutor-dashboard-menu-enrolled-courses nav-item <?php if (isset($login['page']) && $login['page'] == 'feedbackhistory') {
																					echo 'active';
																				} ?>">
						<a href="<?php echo base_url(); ?>feedbackhistory" class="nav-link">
							<i class="fa-sharp fa-solid fa-book fa-fw"></i>
							Feedbacks
						</a>
					</li>
				<?php } ?>

				<?php if ($login['id_init_role'] == 1) { // Admin  
				?>
					<li class="tutor-dashboard-menu-enrolled-courses nav-item
						 <?php if (isset($login['page']) && $login['page'] == 'listcourses') {
								echo 'active';
							} ?>">
						<a href="<?php echo base_url(); ?>listcourses" class="nav-link">
							<i class="fa-sharp fa-solid fa-book fa-fw"></i>
							Courses
						</a>
					</li>
				<?php } ?>


				<?php if ($login['id_init_role'] == 1) { // Admin  
				?>
					<li class="tutor-dashboard-menu-enrolled-courses nav-item 
						<?php if (isset($login['page']) && $login['page'] == 'totalexamshistory') {
							echo 'active';
						} ?>">
						<a href="<?php echo base_url(); ?>totalexamshistory" class="nav-link">
							<i class="fa-sharp fa-solid fa-book fa-fw"></i>
							Exam History
						</a>
					</li>
				<?php } ?>

				<li class="tutor-dashboard-menu-logout nav-item">
					<a href="<?php echo base_url(); ?>logout" class="nav-link" data-no-instant=""><i class="fa-solid fa-right-from-bracket fa-fw"></i>Logout</a>
				</li>
			</ul>
		</div>
	</div>
</nav>