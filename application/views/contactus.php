<?php
/* ==========================================================================
   CONTACT PAGE (modern design - backup: contactus____28_10_2026.php)
   $info = row from contact_us (title, description, image, map)
   ========================================================================== */
$mx_phone    = '+966112543739';
$mx_whatsapp = '+966509296490';
$mx_email    = 'info@stedtraining.com';
$mx_map      = (!empty($info['map']) && $info['map'] !== '0') ? $info['map'] : '';
$mx_map_link = '';
if ($mx_map && preg_match('/[?&]q=([^&]+)/', $mx_map, $m)) {
	$mx_map_link = 'https://www.google.com/maps/search/?api=1&query=' . $m[1];
}
$mx_img = (!empty($info['image']) && is_file("uploads/contact_us/" . $info['image'])) ? base_url() . 'uploads/contact_us/' . $info['image'] : '';

$this->load->view('include/_page_hero', array(
	'ph_title'   => "Let's talk about your training",
	'ph_eyebrow' => 'Contact us',
	'ph_text'    => 'Questions about a course, help with registration or feedback - call us, message us on WhatsApp or send the form below.',
	'ph_crumbs'  => array(
		array('label' => 'Home', 'url' => base_url()),
		array('label' => 'Contact us'),
	),
	'ph_image'   => $mx_img,
	'ph_badge'   => array('icon' => 'fas fa-headset', 'value' => 'We are here to help', 'label' => 'Courses · Registration · Feedback'),
	'ph_stats'   => array(),
));
?>

<div class="mx-inner mx-contact">

	<!-- QUICK CONTACT CARDS -->
	<section class="mx-qc-wrap">
		<div class="container">
			<div class="row mx-qc-row">
				<div class="col-sm-6 col-lg-3 d-flex">
					<a class="mx-qc" href="tel:<?php echo $mx_phone; ?>" data-reveal style="--d:0ms">
						<span class="mx-qc-ico"><i class="fas fa-phone"></i></span>
						<small>Call us</small>
						<b dir="ltr"><?php echo $mx_phone; ?></b>
						<span class="mx-qc-go">Call now <i class="fas fa-arrow-right"></i></span>
					</a>
				</div>
				<div class="col-sm-6 col-lg-3 d-flex">
					<a class="mx-qc mx-qc-wa" href="https://wa.me/<?php echo ltrim($mx_whatsapp, '+'); ?>" target="_blank" rel="noopener" data-reveal style="--d:110ms">
						<span class="mx-qc-ico"><i class="fab fa-whatsapp"></i></span>
						<small>WhatsApp</small>
						<b dir="ltr"><?php echo $mx_whatsapp; ?></b>
						<span class="mx-qc-go">Start a chat <i class="fas fa-arrow-right"></i></span>
					</a>
				</div>
				<div class="col-sm-6 col-lg-3 d-flex">
					<a class="mx-qc mx-qc-mail" href="mailto:<?php echo $mx_email; ?>" data-reveal style="--d:220ms">
						<span class="mx-qc-ico"><i class="fas fa-envelope"></i></span>
						<small>Email</small>
						<b><?php echo $mx_email; ?></b>
						<span class="mx-qc-go">Send an email <i class="fas fa-arrow-right"></i></span>
					</a>
				</div>
				<div class="col-sm-6 col-lg-3 d-flex">
					<a class="mx-qc mx-qc-loc" href="<?php echo $mx_map_link ? $mx_map_link : '#mx-map'; ?>" <?php echo $mx_map_link ? 'target="_blank" rel="noopener"' : ''; ?> data-reveal style="--d:330ms">
						<span class="mx-qc-ico"><i class="fas fa-location-dot"></i></span>
						<small>Visit us</small>
						<b>Riyadh, Saudi Arabia</b>
						<span class="mx-qc-go">Get directions <i class="fas fa-arrow-right"></i></span>
					</a>
				</div>
			</div>
		</div>
	</section>

	<!-- FORM + INFO -->
	<section class="mx-contact-main">
		<div class="container">
			<div class="row">

				<!-- Form -->
				<div class="col-lg-7">
					<div class="mx-form-card" data-reveal>
						<span class="mx-eyebrow"><span class="mx-pulse"></span>Send a message</span>
						<h2>We're here for you</h2>
						<p class="mx-form-lead">Fill in the form and our team will get back to you by email.</p>

						<form id="mx-contact-form" class="mx-form" novalidate onsubmit="insert(); return false;">
							<div class="mx-form-grid">
								<div class="mx-field">
									<label for="namename">Your name</label>
									<div class="mx-input">
										<i class="far fa-user"></i>
										<input type="text" id="namename" name="name" autocomplete="name" placeholder="e.g. Ahmad Ali" value="<?= set_value("name", ""); ?>">
									</div>
									<span class="mx-field-err">Please enter your name</span>
								</div>
								<div class="mx-field">
									<label for="emailemail">Your email</label>
									<div class="mx-input">
										<i class="far fa-envelope"></i>
										<input type="email" id="emailemail" name="email" autocomplete="email" placeholder="name@company.com" value="<?= set_value("email", ""); ?>">
									</div>
									<span class="mx-field-err">Please enter a valid email</span>
								</div>
								<div class="mx-field mx-field-full">
									<label for="subject">Subject</label>
									<div class="mx-input">
										<i class="far fa-comment-dots"></i>
										<input type="text" id="subject" name="subject" placeholder="How can we help?" value="<?= set_value("subject", ""); ?>">
									</div>
									<span class="mx-field-err">Please add a subject</span>
								</div>
								<div class="mx-field mx-field-full">
									<label for="comments">Your message</label>
									<div class="mx-input mx-input-area">
										<i class="far fa-pen-to-square"></i>
										<textarea id="comments" name="comments" rows="6" placeholder="Tell us a little about what you need..."><?= set_value("comments", ""); ?></textarea>
									</div>
									<span class="mx-field-err">Please write your message</span>
								</div>
							</div>

							<div class="mx-form-actions">
								<button type="submit" class="mx-btn mx-btn-primary mx-send">
									<span class="mx-send-txt">Send message</span>
									<i class="fas fa-paper-plane"></i>
								</button>
								<span class="mx-form-note"><i class="fas fa-lock"></i> Your details are only used to reply to you.</span>
							</div>
							<div class="allinfocontact mx-form-status" id="allinfocontact" role="status" aria-live="polite"></div>
						</form>
					</div>
				</div>

				<!-- Info panel -->
				<div class="col-lg-5">
					<aside class="mx-info-card" data-reveal style="--d:120ms">
						<span class="mx-info-dots" aria-hidden="true"></span>
						<h3>How we can help</h3>
						<ul class="mx-info-list">
							<li><i class="fas fa-graduation-cap"></i><span><b>Course questions</b><small>Content, levels and which course fits you</small></span></li>
							<li><i class="fas fa-id-card"></i><span><b>Registration help</b><small>Accounts, enrolment and access</small></span></li>
							<li><i class="fas fa-star"></i><span><b>Feedback</b><small>Tell us how we can improve</small></span></li>
						</ul>
						<?php if (!empty($info['description'])) { ?>
							<div class="mx-info-desc"><?php echo $info['description']; ?></div>
						<?php } ?>
						<div class="mx-info-social">
							<span>Follow STED</span>
							<?php if (!empty($generalinfo['linkedin']) && $generalinfo['linkedin'] !== '0') { ?>
								<a href="<?php echo $generalinfo['linkedin']; ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
							<?php } ?>
							<?php if (!empty($generalinfo['facebook']) && $generalinfo['facebook'] !== '0') { ?>
								<a href="<?php echo $generalinfo['facebook']; ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
							<?php } ?>
							<a href="https://wa.me/<?php echo ltrim($mx_whatsapp, '+'); ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
						</div>
					</aside>
				</div>

			</div>
		</div>
	</section>

	<?php if ($mx_map) { ?>
		<!-- MAP -->
		<section class="mx-map-wrap" id="mx-map">
			<div class="container">
				<div class="mx-map" data-reveal>
					<iframe src="<?php echo htmlspecialchars($mx_map, ENT_QUOTES, 'UTF-8'); ?>" title="STED location map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
					<div class="mx-map-card">
						<span class="mx-qc-ico"><i class="fas fa-location-dot"></i></span>
						<span><b>STED Training Center</b><small>Riyadh, Saudi Arabia</small></span>
						<?php if ($mx_map_link) { ?><a href="<?php echo $mx_map_link; ?>" target="_blank" rel="noopener" aria-label="Open in Google Maps"><i class="fas fa-arrow-up-right-from-square"></i></a><?php } ?>
					</div>
				</div>
			</div>
		</section>
	<?php } ?>
</div>

<script>
	(function($) {
		var emailRe = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,})+$/;
		var fields = {
			namename: function(v) { return v !== ''; },
			emailemail: function(v) { return emailRe.test(v); },
			subject: function(v) { return v !== ''; },
			comments: function(v) { return v !== ''; }
		};

		function check(id) {
			var $el = $('#' + id), ok = fields[id]($.trim($el.val()));
			$el.toggleClass('error', !ok).closest('.mx-field').toggleClass('has-error', !ok);
			return ok;
		}

		$(function() {
			// re-check a field once the visitor edits it after a failed submit
			$.each(fields, function(id) {
				$('#' + id).on('input change', function() {
					if ($(this).hasClass('error')) check(id);
				});
			});
		});

		window.insert = function() {
			var ok = true;
			$.each(fields, function(id) { if (!check(id)) ok = false; });
			var $status = $('.allinfocontact');
			if (!ok) {
				$('#mx-contact-form .has-error input, #mx-contact-form .has-error textarea').first().focus();
				return;
			}

			var $btn = $('#mx-contact-form .mx-send').prop('disabled', true).addClass('is-loading');
			$btn.find('.mx-send-txt').text('Sending...');
			$status.removeClass('is-ok is-err').html('');

			$.ajax({
				type: 'POST',
				url: "<?php echo base_url() ?>home/contactinfo",
				data: {
					name: $.trim($('#namename').val()),
					subject: $.trim($('#subject').val()),
					email: $.trim($('#emailemail').val()),
					comments: $.trim($('#comments').val())
				}
			}).done(function(response) {
				var text = $('<div>').html(response || '').text().trim();
				if (/thank/i.test(text)) {
					$status.addClass('is-ok').html('<i class="fas fa-circle-check"></i> Thank you! Your message was sent - we will get back to you soon.');
					$('#namename, #subject, #emailemail, #comments').val('');
				} else {
					$status.addClass('is-err').html('<i class="fas fa-circle-exclamation"></i> ' + (text && text !== 'Error' ? $('<div>').text(text).html() : 'Please check the form and try again.'));
				}
			}).fail(function() {
				$status.addClass('is-err').html('<i class="fas fa-circle-exclamation"></i> An error occurred while sending. Please try again or contact us by phone.');
			}).always(function() {
				$btn.prop('disabled', false).removeClass('is-loading').find('.mx-send-txt').text('Send message');
			});
		};
	})(jQuery);
</script>
