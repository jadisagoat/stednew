<header id="header" class="header white-menu navbar-dark">
	<div class="header-wrapper">


		<!-- MOBILE HEADER -->
		<div class="wsmobileheader clearfix">
			<a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
			<span class="smllogo smllogo-black mx-logo-fx" style="--mx-logo:url('<?php echo base_url(); ?>uploads/website/<?= $generalinfo["image"]; ?>')"><img src="<?php echo  base_url(); ?>uploads/website/<?= $generalinfo["image"]; ?>" width="110" alt="mobile-logo" /></span>
			<span class="smllogo smllogo-white mx-logo-fx" style="--mx-logo:url('<?php echo base_url(); ?>uploads/website/<?= $generalinfo["image"]; ?>')"><img src="<?php echo  base_url(); ?>uploads/website/<?= $generalinfo["image"]; ?>" width="110" alt="mobile-logo" /></span>
		</div>


		<!-- NAVIGATION MENU -->
		<div class="wsmainfull menu clearfix">
			<div class="wsmainwp clearfix">


				<!-- LOGO IMAGE -->
				<!-- For Retina Ready displays take a image with double the amount of pixels that your image will be displayed (e.g 344 x 80 pixels) -->
				<div class="desktoplogo"><a href="<?php echo base_url() ?>home" class="logo-black mx-logo-fx" style="--mx-logo:url('<?php echo base_url(); ?>uploads/website/<?= $generalinfo["image"]; ?>')"><img src="<?php echo  base_url(); ?>uploads/website/<?= $generalinfo["image"]; ?>" width="140" alt="header-logo"></a></div>
				<div class="desktoplogo"><a href="<?php echo base_url() ?>home" class="logo-white mx-logo-fx" style="--mx-logo:url('<?php echo base_url(); ?>uploads/website/<?= $generalinfo["image"]; ?>')"><img src="<?php echo  base_url(); ?>uploads/website/<?= $generalinfo["image"]; ?>" width="140" alt="header-logo"></a></div>


				<!-- MAIN MENU -->
				<nav class="wsmenu clearfix">
					<ul class="wsmenu-list">


						<!-- SIMPLE NAVIGATION LINK -->
						<li class="nl-simple" aria-haspopup="true"><a href="<?php echo base_url() ?>aboutus">About</a></li>

						<!-- DROPDOWN MENU - 3 LEVEL: Group Category > Category > Course -->
						<?php
						// Prepare menu data once (view only - no queries)
						$mx_groups = array();
						if (!empty($group_category)) {
							foreach ($group_category as $row_sc) {
								$gid  = $row_sc['id_group_category'];
								$cats = (isset($group_category_category[$gid]) && $group_category_category[$gid]) ? $group_category_category[$gid] : array();
								$total = 0;
								foreach ($cats as $c) {
									if (isset($category_course[$c['id_category']]) && $category_course[$c['id_category']]) $total += count($category_course[$c['id_category']]);
								}
								$mx_groups[] = array('row' => $row_sc, 'cats' => $cats, 'total' => $total);
							}
						}
						if (!function_exists('mx_menu_icon')) {
							function mx_menu_icon($title)
							{
								$map = array(
									'fire' => 'fa-fire-extinguisher', 'hse' => 'fa-helmet-safety', 'safety' => 'fa-helmet-safety', 'quality' => 'fa-clipboard-check',
									'hvac' => 'fa-fan', 'fire' => 'fa-fire-extinguisher', 'plumb' => 'fa-faucet-drip',
									'electric' => 'fa-bolt', 'elv' => 'fa-network-wired', 'ict' => 'fa-network-wired',
									'building' => 'fa-building', 'envelope' => 'fa-building', 'professional' => 'fa-user-tie',
									'skill' => 'fa-user-tie', 'testing' => 'fa-gauge-high', 'commission' => 'fa-gauge-high',
									'energy' => 'fa-leaf', 'sustain' => 'fa-leaf', 'automation' => 'fa-microchip', 'smart' => 'fa-microchip',
									'security' => 'fa-shield-halved', 'audio' => 'fa-volume-high', 'mechanical' => 'fa-gears',
									'water' => 'fa-droplet', 'manage' => 'fa-diagram-project', 'environment' => 'fa-leaf',
								);
								$t = strtolower($title);
								foreach ($map as $k => $icon) {
									if (strpos($t, $k) !== false) return $icon;
								}
								return 'fa-layer-group';
							}
						}
						?>
						<li class="mx-cat" aria-haspopup="true">
							<span class="wsmenu-click"><i class="wsmenu-arrow"></i></span>
							<a href="#">Category <span class="wsarrow"></span></a>

							<!-- Mobile: nested list (opened by the menu toggles) -->
							<ul class="sub-menu">
								<?php foreach ($mx_groups as $g) {
									$row_sc = $g['row'];
									if ($g['cats']) { ?>
										<li aria-haspopup="true">
											<span class="wsmenu-click03"><i class="wsmenu-arrow"></i></span>
											<a href="#"><?php echo $row_sc['title']; ?> <i class="fa fa-angle-right"></i></a>
											<ul class="sub-menu">
												<?php foreach ($g['cats'] as $row) {
													if (isset($category_course[$row['id_category']]) && $category_course[$row['id_category']]) { ?>
														<li aria-haspopup="true">
															<span class="wsmenu-click03"><i class="wsmenu-arrow"></i></span>
															<a href="#"><?php echo $row['title']; ?> <i class="fa fa-angle-right"></i></a>
															<ul class="sub-menu">
																<?php foreach ($category_course[$row['id_category']] as $row_sub) { ?>
																	<li><a href="<?php echo base_url() ?>coursedetails/<?php echo $row_sub['id_course']; ?>"><?php echo $row_sub['title']; ?></a></li>
																<?php } ?>
															</ul>
														</li>
													<?php } else { ?>
														<li aria-haspopup="true"><a href="<?php echo base_url() ?>category/<?php echo $row['id_category']; ?>"><?php echo $row['title']; ?></a></li>
												<?php }
												} ?>
											</ul>
										</li>
									<?php } else { ?>
										<li aria-haspopup="true"><a href="#"><?php echo $row_sc['title']; ?></a></li>
								<?php }
								} ?>
							</ul>

							<!-- Desktop: mega panel (Fields > Categories > Courses) -->
							<div class="mx-catmenu" role="region" aria-label="Browse courses by category">
								<div class="mx-cm-top">
									<div class="mx-cm-heading">
										<span class="mx-cm-eyebrow">Explore</span>
										<h4>Browse our training catalogue</h4>
									</div>
									<label class="mx-cm-search">
										<i class="fa-solid fa-magnifying-glass"></i>
										<input type="search" placeholder="Search courses..." aria-label="Search courses" autocomplete="off">
										<kbd>Esc</kbd>
									</label>
								</div>

								<div class="mx-cm-body">
									<!-- Column 1: fields -->
									<div class="mx-cm-col mx-cm-groups">
										<div class="mx-cm-label">Fields</div>
										<ul class="mx-cm-list">
											<?php foreach ($mx_groups as $g) {
												if (!$g['total']) continue;
												$gid = $g['row']['id_group_category']; ?>
												<li>
													<button type="button" class="mx-cm-item" data-g="<?php echo $gid; ?>">
														<span class="mx-cm-ico"><i class="fa-solid <?php echo mx_menu_icon($g['row']['title']); ?>"></i></span>
														<span class="mx-cm-text"><?php echo $g['row']['title']; ?><small><?php echo $g['total']; ?> course<?php echo $g['total'] == 1 ? '' : 's'; ?></small></span>
														<i class="fa-solid fa-chevron-right mx-cm-chev"></i>
													</button>
												</li>
											<?php } ?>
										</ul>
									</div>

									<!-- Column 2: categories of the active field -->
									<div class="mx-cm-col mx-cm-cats">
										<?php foreach ($mx_groups as $g) {
											if (!$g['total']) continue; ?>
											<div class="mx-cm-pane" data-g="<?php echo $g['row']['id_group_category']; ?>">
												<div class="mx-cm-label"><?php echo $g['row']['title']; ?></div>
												<ul class="mx-cm-list">
													<?php foreach ($g['cats'] as $row) {
														$n = (isset($category_course[$row['id_category']]) && $category_course[$row['id_category']]) ? count($category_course[$row['id_category']]) : 0; ?>
														<li>
															<a class="mx-cm-item<?php echo $n ? '' : ' is-empty'; ?>" href="<?php echo base_url() ?>category/<?php echo $row['id_category']; ?>" <?php echo $n ? 'data-c="' . $row['id_category'] . '"' : ''; ?>>
																<span class="mx-cm-text"><?php echo $row['title']; ?><small><?php echo $n ? $n . ' course' . ($n == 1 ? '' : 's') : 'Coming soon'; ?></small></span>
																<?php if ($n) { ?><i class="fa-solid fa-chevron-right mx-cm-chev"></i><?php } ?>
															</a>
														</li>
													<?php } ?>
												</ul>
											</div>
										<?php } ?>
									</div>

									<!-- Column 3: courses of the active category -->
									<div class="mx-cm-col mx-cm-courses">
										<?php foreach ($mx_groups as $g) {
											foreach ($g['cats'] as $row) {
												if (empty($category_course[$row['id_category']])) continue; ?>
												<div class="mx-cm-pane" data-c="<?php echo $row['id_category']; ?>">
													<div class="mx-cm-label"><?php echo $row['title']; ?></div>
													<ul class="mx-cm-list">
														<?php foreach ($category_course[$row['id_category']] as $row_sub) { ?>
															<li>
																<a class="mx-cm-course" href="<?php echo base_url() ?>coursedetails/<?php echo $row_sub['id_course']; ?>" data-cat="<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>">
																	<span class="mx-cm-cico"><i class="fa-solid fa-play"></i></span>
																	<span class="mx-cm-ctitle"><?php echo $row_sub['title']; ?></span>
																	<i class="fa-solid fa-arrow-right mx-cm-go"></i>
																</a>
															</li>
														<?php } ?>
													</ul>
													<a class="mx-cm-more" href="<?php echo base_url() ?>category/<?php echo $row['id_category']; ?>">View category <i class="fa-solid fa-arrow-right"></i></a>
												</div>
										<?php }
										} ?>
									</div>

									<!-- Search results -->
									<div class="mx-cm-results">
										<div class="mx-cm-label">Results</div>
										<ul class="mx-cm-list mx-cm-grid"></ul>
										<p class="mx-cm-noresult"><i class="fa-regular fa-face-meh"></i>No course matches your search.</p>
									</div>
								</div>
							</div>
						</li> <!-- END DROPDOWN MENU -->


						<!-- MEGAMENU -->
						<li aria-haspopup="true"><a href="#">News <span class="wsarrow"></span></a>
							<div class="wsmegamenu mx-news-mega clearfix">
								<div class="container">
									<div class="row">


										<!-- MEGAMENU QUICK LINKS -->
										<div class="col-md-12 col-lg-3">

											<!-- Title -->
											<h3 class="title">Top 5 Courses:</h3>

											<ul class="link-list clearfix">
												<?php foreach ($header_course as $row) { ?>
													<li><a href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>"><?php echo $row['title']; ?></a></li>
												<?php } ?>
											</ul>

										</div> <!-- END MEGAMENU QUICK LINKS -->


										<!-- MEGAMENU FEATURED NEWS -->
										<div class="col-md-12 col-lg-5">

											<!-- Title -->
											<h3 class="title">Featured News:</h3>

											<!-- Image -->
											<div class="fluid-width-video-wrapper mb-15">
												<a class="news-img-link" href="<?php echo base_url(); ?>news/<?php echo $header_news[0]['id_news']; ?>">
													<span class="mx-news-badge"><i class="fa-solid fa-bolt"></i> Featured</span>
													<span class="mx-news-cta">Read article <i class="fa-solid fa-arrow-right"></i></span>
													<img loading="lazy" src="<?php echo base_url() ?>uploads/news/<?php echo is_file("uploads/news/420x250/" . $header_news[0]['image']) ? "420x250/" : ""; ?><?php echo $header_news[0]['image'] ?>" alt="<?php echo $header_news[0]['title']; ?>" />
												</a>
											</div>

											<!-- Text -->
											<h5 class="h5-md">
												<a href="<?php echo base_url(); ?>news/<?php echo $header_news[0]['id_news']; ?>">
													<?php echo $header_news[0]['title']; ?>
												</a>
											</h5>
											<p class="wsmwnutxt">
												<?php echo $header_news[0]['small_description']; ?>
											</p>

										</div> <!-- END MEGAMENU FEATURED NEWS -->


										<!-- MEGAMENU LATEST NEWS -->
										<div class="col-md-12 col-lg-4">

											<!-- Title -->
											<h3 class="title">Latest News:</h3>

											<!-- Latest News -->
											<ul class="latest-news">
												<?php $i = 0;
												foreach ($header_news as $row) {
													if ($i != 0) { ?>
														<!-- Post #1 -->
														<li class="clearfix d-flex align-items-center">

															<!-- Image -->
															<a class="news-img-link" href="<?php echo base_url(); ?>news/<?php echo $row['id_news']; ?>">
																<img class="img-fluid" loading="lazy" src="<?php echo base_url() ?>uploads/news/<?php echo is_file("uploads/news/85x85/" . $row['image']) ? "85x85/" : ""; ?><?php echo $row['image'] ?>" alt="<?php echo $row['title']; ?>" />
															</a>

															<!-- Text -->
															<div class="post-summary">
																<a href="<?php echo base_url(); ?>news/<?php echo $row['id_news']; ?>"><?php echo $row['title']; ?></a>
																<p><a class="news-date-link" href="<?php echo base_url(); ?>news/<?php echo $row['id_news']; ?>"><i class="fa-regular fa-clock"></i> <?php echo date("d-m-Y", strtotime($row['created_date'])); ?> | <?php echo date("g:i", strtotime($row['created_date'])); ?></a></p>
															</div>

														</li>
												<?php	}
													$i++;
												} ?>
											</ul>
										</div> <!-- END MEGAMENU LATEST NEWS -->


									</div> <!-- End row -->
								</div> <!-- End container -->
							</div> <!-- End wsmegamenu -->
						</li> <!-- END MEGAMENU -->


						<!-- SIMPLE NAVIGATION LINK -->
						<li class="nl-simple" aria-haspopup="true"><a href="<?php echo base_url(); ?>faqs">FAQs</a></li>

						<!-- SIMPLE NAVIGATION LINK -->
						<li class="nl-simple" aria-haspopup="true"><a href="<?php echo base_url(); ?>contactus">Contact</a></li>

						<!-- USER MENU: avatar > profile card + links (styles: modern.css "HEADER - USER DROPDOWN") -->
						<?php
						$hd_logged = (isset($login['idUser']) && $login['idUser']);
						if ($hd_logged) {
							$hd_fullname = isset($login['fullname']) ? trim((string) $login['fullname']) : '';
							$hd_mail = isset($login['email']) ? trim((string) $login['email']) : '';
							// initials: first letter of the first two words of the name
							$hd_initials = '';
							if ($hd_fullname !== '') {
								foreach (array_slice(preg_split('/\s+/u', $hd_fullname), 0, 2) as $hd_w) {
									if ($hd_w !== '') $hd_initials .= mb_strtoupper(mb_substr($hd_w, 0, 1, 'UTF-8'), 'UTF-8');
								}
							}
							if ($hd_initials === '') $hd_initials = '?';
							$hd_avatar = (!empty($login['image']) && is_file('uploads/user/' . $login['image'])) ? base_url() . 'uploads/user/' . rawurlencode($login['image']) : '';
							$hd_role = isset($login['id_init_role']) ? (int) $login['id_init_role'] : 0;
							$hd_role_label = array(1 => 'Administrator', 2 => 'Lecturer', 3 => 'Learner');
							$hd_role_icon = array(1 => 'fa-solid fa-shield-halved', 2 => 'fa-solid fa-chalkboard-user', 3 => 'fa-solid fa-graduation-cap');
							$hd_name = htmlspecialchars($hd_fullname, ENT_QUOTES, 'UTF-8');
							$hd_email = htmlspecialchars($hd_mail, ENT_QUOTES, 'UTF-8');
							$hd_ini = htmlspecialchars($hd_initials, ENT_QUOTES, 'UTF-8');
						}
						?>
						<li class="nl-simple<?php echo $hd_logged ? ' mx-user' : ''; ?>" aria-haspopup="true">
							<?php if ($hd_logged) { ?>
								<div class="login_header_login lang-select mx-user-btn" role="button" tabindex="0" aria-label="Account menu">
									<?php if ($hd_avatar) { ?><img src="<?php echo $hd_avatar; ?>" alt=""><?php } else { echo $hd_ini; } ?>
									<i class="mx-user-dot" aria-hidden="true"></i>
								</div>
								<ul class="sub-menu last-sub-menu mx-user-menu">
									<li class="mx-user-card">
										<span class="mx-user-glow" aria-hidden="true"></span>
										<span class="mx-user-dots" aria-hidden="true"></span>
										<div class="mx-user-avatar">
											<?php if ($hd_avatar) { ?><img src="<?php echo $hd_avatar; ?>" alt=""><?php } else { ?><span><?php echo $hd_ini; ?></span><?php } ?>
										</div>
										<div class="mx-user-who">
											<b title="<?php echo $hd_name; ?>"><?php echo $hd_name; ?></b>
											<?php if ($hd_email !== '') { ?><small title="<?php echo $hd_email; ?>"><?php echo $hd_email; ?></small><?php } ?>
											<?php if (isset($hd_role_label[$hd_role])) { ?>
												<span class="mx-user-role"><i class="<?php echo $hd_role_icon[$hd_role]; ?>" aria-hidden="true"></i><?php echo $hd_role_label[$hd_role]; ?></span>
											<?php } ?>
										</div>
									</li>
									<li><a href="<?php echo base_url(); ?>edit-profile" class="mx-user-link"><i class="fa-regular fa-user fa-fw" aria-hidden="true"></i><span>Edit Profile</span><i class="fa-solid fa-chevron-right mx-user-arrow" aria-hidden="true"></i></a></li>
									<li class="mx-user-foot"><a href="<?php echo base_url(); ?>logout" class="mx-user-link mx-user-logout"><i class="fa-solid fa-right-from-bracket fa-fw" aria-hidden="true"></i><span>Sign out</span><i class="fa-solid fa-chevron-right mx-user-arrow" aria-hidden="true"></i></a></li>
								</ul>
							<?php } else { ?>
								<button type="button" class="login_header" data-toggle="modal" data-target="#myModal">
									Login
								</button>
							<?php } ?>
						</li>
					</ul>
				</nav> <!-- END MAIN MENU -->

			</div>
		</div> <!-- END NAVIGATION MENU -->
	</div> <!-- End header-wrapper -->
</header> <!-- END HEADER -->

 
<style>
	@media (max-width: 991px) {

		/* Hide all submenus by default on mobile */
		.wsmenu .sub-menu {
			display: none;
		}

		/* When a list item receives .open, reveal its immediate submenu */
		.wsmenu li.open>.sub-menu {
			display: block !important;
		}

		/* allow nested submenus to be indented (optional styling) */
		.wsmenu .sub-menu .sub-menu {
			padding-left: 12px;
		}
	}
</style>