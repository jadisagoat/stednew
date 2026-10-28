<?php
/* ==========================================================================
   EDIT PROFILE (modern design - backup: editprofile____28_10_2026.php)
   $user = user row, $countries = countries list, $id_profile = edited user (admin)
   Every field saves on change (submitFormData -> home/updatedata, serialised #savedata).
   Photo upload: #photoimg / #imageform / #preview are wired in template.php (users/upload).
   Styles: css/pages/account.css
   ========================================================================== */
$mx_e = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };

$mx_photo = base_url() . "images/nopic.jpg";
if ($user['image'])
	$mx_photo = base_url() . "uploads/user/" . $user['image'];

$mx_country = '';
foreach ($countries as $row) {
	if ($user['id_countries'] == $row['id_countries']) { $mx_country = $row['title']; break; }
}

// how complete is the profile (visual hint only)
$mx_checks = array($user['name'], $user['phone'], $user['occupation'], $user['id_countries'], $user['age'], $user['years_of_exp'], $user['bio'], $user['image'], $user['website'] . $user['facebook'] . $user['twitter'] . $user['linkedin']);
$mx_filled = 0;
foreach ($mx_checks as $c) if (trim((string)$c) !== '' && (string)$c !== '0') $mx_filled++;
$mx_complete = (int)round($mx_filled / count($mx_checks) * 100);

$mx_editing_other = ($id_profile && isset($login['idUser']) && $id_profile != $login['idUser']);

$this->load->view('include/_page_hero', array(
	'ph_title'   => $mx_editing_other ? 'Edit profile' : 'My profile',
	'ph_eyebrow' => 'My account',
	'ph_text'    => $mx_editing_other ? 'You are editing the profile of ' . $user['name'] . '. Every change is saved automatically.' : 'Keep your details up to date - every change is saved automatically as soon as you leave a field.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'My account', 'url' => base_url() . 'edit-profile'),
		array('label' => $user['name'] ? $user['name'] : 'Profile'),
	),
	'ph_stats'   => array(
		array('icon' => 'fas fa-user-check', 'value' => $mx_complete . '%', 'label' => 'Profile complete'),
		array('icon' => 'fas fa-bolt', 'value' => 'Auto-save', 'label' => 'No save button needed'),
	),
));
?>

<div class="mx-inner mx-acc">
	<section class="mx-acc-main">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-xl-3 tutor-dashboard-left-menu">
					<?php $data['login'] = $login;
					$data['login']['page'] = 'editprofile';
					$this->load->view('include/side_profile', $data);
					?>
				</div>

				<div class="col-lg-8 col-xl-9">

					<!-- PROFILE CARD + PHOTO -->
					<div class="mx-acc-panel mx-acc-me" data-reveal style="--d:100ms">
						<div class="mx-acc-photo card-images">
							<div id="preview">
								<?php echo '<img src="' . htmlspecialchars($mx_photo) . '" alt="StedLb" class="preview">'; ?>
							</div>
							<form id="imageform" class="form-image" method="post" enctype="multipart/form-data" action='<?php echo base_url(); ?>users/upload'>
								<label for="photoimg" class="custom-file-upload mx-acc-photo-btn" title="Upload your image">
									<i class="fas fa-camera" aria-hidden="true"></i><span class="sr-only">Upload your image</span>
								</label>
								<input type="file" name="photoimg" id="photoimg" accept="image/*" style="display: none;" />
								<input type="hidden" id="id_profile_image" name="id_profile_image" value="<?php echo $id_profile; ?>" readonly>
							</form>
						</div>

						<div class="mx-acc-me-txt">
							<span class="mx-eyebrow"><span class="mx-pulse"></span>Profile details</span>
							<h2><?php echo $mx_e($user['name']); ?></h2>
							<p><?php echo trim((string)$user['occupation']) !== '' ? $mx_e($user['occupation']) : 'Add your profession below so lecturers know your background.'; ?></p>
							<div class="mx-acc-facts">
								<?php if ($user['email']) { ?><span><i class="far fa-envelope"></i><?php echo $mx_e($user['email']); ?></span><?php } ?>
								<?php if ($mx_country) { ?><span><i class="fas fa-location-dot"></i><?php echo $mx_e($mx_country); ?></span><?php } ?>
								<?php if ((int)$user['years_of_exp'] > 0) { ?><span><i class="fas fa-briefcase"></i><?php echo (int)$user['years_of_exp']; ?> yrs experience</span><?php } ?>
							</div>
							<label for="photoimg" class="mx-acc-upload-link"><i class="fas fa-cloud-arrow-up"></i> Upload a new photo <small>JPG or PNG</small></label>
						</div>

						<div class="mx-acc-meter" style="--p:<?php echo $mx_complete; ?>">
							<b><?php echo $mx_complete; ?><small>%</small></b>
							<span>Profile<br>complete</span>
						</div>
					</div>

					<form method="post" id="savedata" class="mx-form mx-acc-form" enctype="multipart/form-data" onsubmit="return false;">

						<!-- PERSONAL DETAILS -->
						<div class="mx-acc-panel" data-reveal>
							<div class="mx-acc-head">
								<span class="mx-acc-head-ico"><i class="far fa-id-card"></i></span>
								<div class="mx-acc-head-txt">
									<h2>Personal details</h2>
									<p>Saved automatically when you change a field</p>
								</div>
								<span class="mx-acc-autosave"><i class="fas fa-cloud"></i> Auto-save on</span>
							</div>

							<div class="mx-form-grid">
								<?php
								$userId = $this->session->userdata('login');
								$id_user = $userId['idUser'];
								$qrys = "SELECT
											role.title as role,
											user.id_user,
											user.name,
											user.email,
											user.username,
											user.phone,
											id_countries
											FROM
											user
											INNER JOIN
											role ON role.id_role = user.id_role
											WHERE
											user.deleted = 0 and user.id_role='1' and user.id_user= $id_user
											GROUP BY
											user.id_user
											ORDER BY
											user.id_role";
								$permisssion_admin_user = $this->fct->search($qrys);
								if ($id_user && isset($permisssion_admin_user['0']['role']) && $permisssion_admin_user['0']['role'] == "Admin") { ?>
									<div class="mx-field mx-field-full mx-acc-admin">
										<label for="active">Account status <em>Admin only</em></label>
										<div class="mx-input mx-acc-select">
											<i class="fas fa-toggle-on"></i>
											<select id="active" name="active" onchange="submitFormData('active');">
												<option value="0" selected="selected">Select active</option>
												<option value="0" <?php if ($user['active'] == 0) {
																		echo ' selected';
																	} ?>>InActive</option>
												<option value="1" <?php if ($user['active'] == 1) {
																		echo ' selected';
																	} ?>>Active</option>
											</select>
										</div>
										<span id="msg_countries" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
									</div>
								<?php } ?>

								<div class="mx-field">
									<label for="name">Full name</label>
									<div class="mx-input">
										<i class="far fa-user"></i>
										<input type="text" id="name" name="name" value="<?php echo $mx_e($user['name']); ?>" placeholder="Name" onchange="submitFormData('name');">
									</div>
									<span id="msg_name" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field">
									<label for="mx-acc-phone">Phone</label>
									<div class="mx-input">
										<i class="fas fa-phone"></i>
										<input type="tel" id="mx-acc-phone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" name="phone" value="<?php echo $mx_e($user['phone']); ?>" placeholder="Phone" onchange="submitFormData('phone');">
									</div>
									<span id="msg_phone" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field">
									<label for="mx-acc-username">User name <em><i class="fas fa-lock"></i> locked</em></label>
									<div class="mx-input mx-acc-locked">
										<i class="fas fa-at"></i>
										<input type="text" id="mx-acc-username" disabled="disabled" value="<?php echo $mx_e($user['username']); ?>">
									</div>
								</div>

								<div class="mx-field">
									<label for="mx-acc-email">Email <em><i class="fas fa-lock"></i> locked</em></label>
									<div class="mx-input mx-acc-locked">
										<i class="far fa-envelope"></i>
										<input type="text" id="mx-acc-email" disabled="disabled" value="<?php echo $mx_e($user['email']); ?>">
									</div>
									<span id="msg_email" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field mx-field-full">
									<label for="mx-acc-occupation">Profession</label>
									<div class="mx-input">
										<i class="fas fa-briefcase"></i>
										<input type="text" id="mx-acc-occupation" name="occupation" value="<?php echo $mx_e($user['occupation']); ?>" placeholder="Ex:Mechanical Engineer" onchange="submitFormData('occupation');">
									</div>
									<span id="msg_occupation" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field mx-field-full">
									<label for="id_countries">Country</label>
									<div class="mx-input mx-acc-select">
										<i class="fas fa-earth-americas"></i>
										<select id="id_countries" name="id_countries" onchange="submitFormData('id_countries');">
											<option value="0" selected="selected">Select Country</option>
											<?php foreach ($countries as $row) { ?>
												<option value="<?php echo $row['id_countries']; ?>" <?php if ($user['id_countries'] == $row['id_countries']) {
																										print ' selected';
																									} ?>><?php echo $row['title']; ?></option>
											<?php } ?>
										</select>
									</div>
									<span id="msg_countries" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field">
									<label for="mx-acc-age">Age</label>
									<div class="mx-input">
										<i class="fas fa-cake-candles"></i>
										<input type="text" id="mx-acc-age" name="age" value="<?php echo $mx_e($user['age']); ?>" placeholder="" onchange="submitFormData('age');">
									</div>
									<span id="msg_age" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field">
									<label for="id_gender">Gender</label>
									<div class="mx-input mx-acc-select">
										<i class="fas fa-venus-mars"></i>
										<select name="id_gender" id="id_gender" onchange="submitFormData('id_gender');">
											<option value="1" <?php if ($user['id_gender'] == 1) { ?> selected <?php } ?>>Male</option>
											<option value="2" <?php if ($user['id_gender'] == 2) { ?> selected <?php } ?>>Female</option>
										</select>
									</div>
									<span id="msg_id_gender" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field mx-field-full">
									<label for="mx-acc-exp">Years of experience</label>
									<div class="mx-input">
										<i class="fas fa-chart-simple"></i>
										<input type="text" id="mx-acc-exp" name="years_of_exp" value="<?php echo $mx_e($user['years_of_exp']); ?>" placeholder="" onchange="submitFormData('years_of_exp');">
									</div>
									<span id="msg_years_of_exp" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field mx-field-full">
									<label for="mx-acc-bio">Bio</label>
									<div class="mx-input mx-input-area">
										<i class="far fa-pen-to-square"></i>
										<textarea id="mx-acc-bio" name="bio" placeholder="A few words about you, your role and what you want to learn" onchange="submitFormData('bio');"><?php echo $mx_e($user['bio']); ?></textarea>
									</div>
									<span class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>
							</div>
						</div>

						<!-- SOCIAL PROFILES -->
						<div class="mx-acc-panel" data-reveal>
							<div class="mx-acc-head">
								<span class="mx-acc-head-ico is-warm"><i class="fas fa-share-nodes"></i></span>
								<div class="mx-acc-head-txt">
									<h2>Social profiles</h2>
									<p>Optional - help others find you online</p>
								</div>
							</div>

							<div class="mx-form-grid">
								<div class="mx-field">
									<label for="website">Website</label>
									<div class="mx-input">
										<i class="fas fa-globe"></i>
										<input type="text" id="website" name="website" placeholder="https://your-site.com" value="<?php echo $mx_e($user['website']); ?>" onchange="submitFormData('website');">
									</div>
									<span id="msg_website" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field">
									<label for="linkedin">LinkedIn</label>
									<div class="mx-input mx-acc-brand is-in">
										<i class="fab fa-linkedin-in"></i>
										<input type="text" id="linkedin" name="linkedin" placeholder="https://linkedin.com/username" value="<?php echo $mx_e($user['linkedin']); ?>" onchange="submitFormData('linkedin');">
									</div>
									<span id="msg_linkedin" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field">
									<label for="facebook">Facebook</label>
									<div class="mx-input mx-acc-brand is-fb">
										<i class="fab fa-facebook-f"></i>
										<input type="text" id="facebook" name="facebook" placeholder="https://facebook.com/username" value="<?php echo $mx_e($user['facebook']); ?>" onchange="submitFormData('facebook');">
									</div>
									<span id="msg_facebook" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>

								<div class="mx-field">
									<label for="twitter">Twitter / X</label>
									<div class="mx-input mx-acc-brand is-x">
										<i class="fab fa-x-twitter"></i>
										<input type="text" id="twitter" name="twitter" placeholder="https://twitter.com/username" value="<?php echo $mx_e($user['twitter']); ?>" onchange="submitFormData('twitter');">
									</div>
									<span id="msg_twitter" class="msg error valid mx-acc-msg"><span class="mx-acc-ok"><i class="fas fa-check"></i> Saved</span><span class="mx-acc-ko"><i class="fas fa-xmark"></i> Not saved</span></span>
								</div>
							</div>

							<p class="mx-form-note mx-acc-note"><i class="fas fa-shield-halved"></i> Your user name and email are managed by STED and cannot be changed here.</p>
						</div>

						<input type="hidden" id="id_profile" name="id_profile" value="<?php echo $id_profile; ?>" readonly>
					</form>

					<!-- Signature upload (hidden, kept for users/upload_signature - wired in template.php) -->
					<div class="mb-3 col-12 col-md-6" style="display:none">
						<label class="form-label">Signature:</label>
						<div class="card-images-signature card-body">
							<form id="imageform_signature" class="form-image" method="post" enctype="multipart/form-data" action='<?php echo base_url(); ?>users/upload_signature'>
								<label for="photoimg_signature" class="custom-file-upload-signature">
									Upload your Signature
								</label>
								<input type="file" name="photoimg_signature" id="photoimg_signature" style="display: none;" />
								<input type="hidden" id="id_profile_image" name="id_profile_image" value="<?php echo $id_profile; ?>" readonly>
							</form>
							<div id='preview_signature'>
								<?php
								$target_file = base_url() . "uploads/watrmark.jpeg";
								if ($user['signature'])
									$target_file = base_url() . "uploads/user/" . $user['signature'];
								echo '<img  src="' . htmlspecialchars($target_file) . '" alt="StedLb" class="preview-signature">';
								?>
							</div>
						</div>
					</div>
					<div class="error" id="msg"></div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	// Saves the whole #savedata form (same endpoint and data as before) and shows
	// a small "Saved" / "Not saved" badge under the field that changed.
	function submitFormData(msg) {
		var $field = $('#savedata [name="' + msg + '"]').closest('.mx-field');
		var $el = $field.find('.mx-acc-msg');
		if (!$el.length) $el = $('#msg_' + msg);
		$field.addClass('is-saving');

		function show(ok) {
			$el.removeClass(ok ? 'error' : 'valid').addClass((ok ? 'valid' : 'error') + ' msg_show');
			clearTimeout($el.data('mxTimer'));
			$el.data('mxTimer', setTimeout(function() {
				$el.removeClass('msg_show');
			}, 3000));
		}

		$.ajax({
				type: 'POST',
				url: "<?php echo base_url(); ?>home/updatedata",
				data: $('#savedata').serialize(),
				dataType: "json",
			})
			.done(function(response) {
				if (response) {
					show(true);
				} else { // Successful post, but not response came back !?
					show(false);
					console.error("Post sent, but no response came back!?");
				}
			}).fail(function(response) {
				show(false);
				console.error("Unknown Error!");
			}).always(function() {
				$field.removeClass('is-saving');
			});
	}
</script>
