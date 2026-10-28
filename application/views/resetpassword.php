<?php
/* ==========================================================================
   RESET PASSWORD (modern design - backup: resetpassword____28_10_2026.php)
   Reached from the e-mail link resetpassword/<token>; the user is kept in the
   'resetpassword' session. Posts { resetpassword } to home/updatepassword (returns '1').
   Styles: css/pages/account.css
   ========================================================================== */
$resetpassword = $this->session->userdata('resetpassword');
$info["idUser"] = $resetpassword['idUser'];
$info["fullname"] = $resetpassword['fullname'];
$info["email"] = $resetpassword['email'];
$mx_first = trim((string)$info["fullname"]) !== '' ? preg_split('/\s+/u', trim($info["fullname"]))[0] : '';

$this->load->view('include/_page_hero', array(
	'ph_title'   => 'Set a new password',
	'ph_eyebrow' => 'Account security',
	'ph_text'    => 'Choose a new password for your STED account. Once it is saved you are signed in and taken back to the website.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'Reset password'),
	),
	'ph_stats'   => array(
		array('icon' => 'fas fa-lock', 'value' => 'Secure', 'label' => 'Private reset link'),
		array('icon' => 'fas fa-bolt', 'value' => '1 minute', 'label' => 'To get back in'),
	),
));
?>

<div class="mx-inner mx-acc mx-acc-reset">
	<section class="mx-acc-main">
		<div class="container">
			<div class="row justify-content-center">

				<div class="col-lg-7">
					<div class="mx-form-card mx-acc-reset-card" data-reveal>
						<span class="mx-eyebrow"><span class="mx-pulse"></span>Reset password</span>
						<h2>Hi <?php echo htmlspecialchars($mx_first !== '' ? $mx_first : $info["fullname"], ENT_QUOTES, 'UTF-8'); ?>,</h2>
						<p class="mx-form-lead">Please enter your new password<?php if ($info["email"]) { ?> for <b><?php echo htmlspecialchars($info["email"], ENT_QUOTES, 'UTF-8'); ?></b><?php } ?>.</p>

						<form class="mx-form" id="mx-reset-form" novalidate onsubmit="if (!$('#updatepassword').prop('disabled')) updatepassword(); return false;">
							<input type="text" name="username" value="<?php echo htmlspecialchars($info["email"], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="username" hidden aria-hidden="true" tabindex="-1">

							<div class="mx-field mx-acc-pw">
								<label for="resetpassword">New password <span id="resetpassword-error" class="text-error"></span></label>
								<div class="mx-input password-container">
									<i class="fas fa-lock"></i>
									<input type="password" class="input-xxlarge" id="resetpassword" autocomplete="new-password" placeholder="Your new password" />
									<button type="button" class="toggle-password mx-acc-eye" onclick="togglePasswordVisibility('resetpassword')" aria-label="Show or hide the password"><i class="far fa-eye"></i></button>
								</div>
								<div class="mx-acc-strength" data-level="0" aria-hidden="true">
									<span></span><span></span><span></span><span></span>
									<small>Use 8+ characters with letters, numbers and symbols</small>
								</div>
							</div>

							<div class="mx-field mx-acc-pw">
								<label for="confirmresetpassword">Confirm password</label>
								<div class="mx-input password-container">
									<i class="fas fa-shield-halved"></i>
									<input type="password" class="input-xxlarge" id="confirmresetpassword" value="" autocomplete="new-password" placeholder="Type it again" />
									<button type="button" class="toggle-password mx-acc-eye" onclick="togglePasswordVisibility('confirmresetpassword')" aria-label="Show or hide the password"><i class="far fa-eye"></i></button>
								</div>
								<span id="confirmresetpassword-error" class="text-error mx-acc-err"></span>
							</div>

							<div class="mx-form-actions">
								<button type="button" id="updatepassword" onClick="updatepassword()" class="mx-btn mx-btn-primary mx-send" disabled>
									<span class="mx-send-txt">Reset password</span>
									<i class="fas fa-key"></i>
								</button>
								<span class="mx-form-note"><i class="fas fa-lock"></i> Your password is never shared.</span>
							</div>
							<div class="allinfocontact mx-form-status" id="allinfocontact" role="status" aria-live="polite"></div>
						</form>
					</div>
				</div>

				<div class="col-lg-5">
					<aside class="mx-info-card" data-reveal style="--d:120ms">
						<span class="mx-info-dots" aria-hidden="true"></span>
						<h3>A strong password</h3>
						<ul class="mx-info-list">
							<li><i class="fas fa-ruler-horizontal"></i><span><b>8 characters or more</b><small>Longer is always stronger</small></span></li>
							<li><i class="fas fa-shuffle"></i><span><b>Mix it up</b><small>Letters, numbers and a symbol</small></span></li>
							<li><i class="fas fa-user-secret"></i><span><b>Keep it unique</b><small>Do not reuse another site's password</small></span></li>
						</ul>
						<div class="mx-info-desc">
							<p>Didn't ask for a new password? You can ignore the e-mail - your current password keeps working. Need help? <a class="mx-acc-link-light" href="<?php echo base_url(); ?>contactus">Contact us</a>.</p>
						</div>
					</aside>
				</div>

			</div>
		</div>
	</section>
</div>

<script>
	function togglePasswordVisibility(fieldId) {
		var passwordField = document.getElementById(fieldId);
		if (passwordField.type === 'password') {
			passwordField.type = 'text';
		} else {
			passwordField.type = 'password';
		}
		$(passwordField).siblings('.mx-acc-eye').find('i').toggleClass('fa-eye', passwordField.type === 'password').toggleClass('fa-eye-slash', passwordField.type !== 'password');
	}

	// field state helpers (red / green outline through classes, so the modern input style is kept)
	function mxPwState(id, state) {
		$('#' + id).closest('.mx-field').toggleClass('has-error', state === 'err').toggleClass('is-ok', state === 'ok');
	}

	function mxPwStrength() {
		var v = $('#resetpassword').val() || '', s = 0;
		if (v.length >= 6) s++;
		if (v.length >= 10) s++;
		if (/[A-Z]/.test(v) && /[a-z]/.test(v)) s++;
		if (/[0-9]/.test(v) && /[^A-Za-z0-9]/.test(v)) s++;
		if (v.length && !s) s = 1;
		var txt = ['Use 8+ characters with letters, numbers and symbols', 'Weak password', 'Fair password', 'Good password', 'Strong password'];
		$('.mx-acc-strength').attr('data-level', v.length ? s : 0).find('small').text(txt[v.length ? s : 0]);
	}

	// Function to validate confirm password field
	function validateConfirmPassword1() {
		var password = $('#resetpassword').val();
		if (password == '') {
			$("#updatepassword").prop("disabled", true);
			mxPwState('resetpassword', 'err');
			$('#resetpassword-error').text('Passwords Required!');
			return false;
		}
		mxPwState('resetpassword', '');
		$('#resetpassword-error').text('');
	}

	function validateConfirmPassword() {
		var password = $('#resetpassword').val();
		var confirmPassword = $('#confirmresetpassword').val();
		$("#updatepassword").prop("disabled", true);

		if (password == '') {
			mxPwState('resetpassword', 'err');
			$('#resetpassword-error').text('*');
			return false;
		} else
		if (confirmPassword !== password) {
			mxPwState('confirmresetpassword', confirmPassword === '' ? '' : 'err');
			$('#confirmresetpassword-error').text(confirmPassword === '' ? '' : 'Passwords do not match.');
			return false;
		} else {
			$("#updatepassword").prop("disabled", false);
			mxPwState('resetpassword', 'ok');
			$('#resetpassword-error').text('');
			mxPwState('confirmresetpassword', 'ok');
			$('#confirmresetpassword-error').text('');
			return true;
		}
	}

	$(document).ready(function() {
		$('#confirmresetpassword').on('change', function() {
			validateConfirmPassword();
		});
		$('#resetpassword').on('change', function() {
			validateConfirmPassword1();
		});
		$('#resetpassword').on('input', function() {
			mxPwStrength();
			if ($('#confirmresetpassword').val() !== '') validateConfirmPassword();
		});
	});

	function updatepassword() {
		var $btn = $("#updatepassword");
		var $status = $('.allinfocontact');

		var password = $('#resetpassword').val();
		var confirmPassword = $('#confirmresetpassword').val();
		var boolean = 0;

		// Clear previous errors
		$("#resetpassword-error").text('');
		$("#confirmresetpassword-error").text('');
		mxPwState('resetpassword', '');
		$status.removeClass('is-ok is-err').html('');

		// Validate password
		if (password == '') {
			mxPwState('resetpassword', 'err');
			$("#resetpassword-error").text('Password is required.');
			boolean = 1;
		} else
		if (confirmPassword == '') {
			mxPwState('confirmresetpassword', 'err');
			$("#confirmresetpassword-error").text('Please confirm your password.');
			boolean = 1;
		} else if (password !== confirmPassword) {
			mxPwState('confirmresetpassword', 'err');
			$("#confirmresetpassword-error").text('Passwords do not match.');
			boolean = 1;
		}

		if (boolean == 0) {
			$btn.prop("disabled", true).addClass('is-loading').find('.mx-send-txt').text('Saving...');
			$.ajax({
				type: 'POST',
				url: "<?php echo base_url() ?>home/updatepassword",
				data: {
					'resetpassword': password,
				},
				success: function(response) {
					if (response === '1') {
						$status.addClass('is-ok').html('<i class="fas fa-circle-check"></i> Password updated successfully.');
						$btn.removeClass('is-loading').find('.mx-send-txt').text('Password saved');

						setTimeout(function() {
							<?php $linkpage = base_url(); ?>
							window.location.href = '<?php echo $linkpage; ?>';
						}, 1000)

					} else {
						$status.addClass('is-err').html('<i class="fas fa-circle-exclamation"></i> Password update failed.');
						$btn.prop("disabled", false).removeClass('is-loading').find('.mx-send-txt').text('Reset password');
					}
				},
				error: function(xhr, status, error) {
					console.error("AJAX Error: ", status, error);
					$status.addClass('is-err').html('<i class="fas fa-circle-exclamation"></i> An error occurred.');
					$btn.prop("disabled", false).removeClass('is-loading').find('.mx-send-txt').text('Reset password');
				}
			});
		} else {
			$status.addClass('is-err').html('<i class="fas fa-circle-exclamation"></i> Password update failed.');
			return "error";
		}
	}

	document.getElementById('confirmresetpassword').addEventListener('input', validateConfirmPassword);
</script>
