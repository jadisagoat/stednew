<!-- INNER PAGE WRAPPER
============================================= -->
<div class="inner-page-wrapper">
	<!-- BREADCRUMB
    ============================================= -->
	<div id="breadcrumb" class="division">
		<div class="container">
			<div class="row">

				<!-- BREADCRUMB NAV -->
				<div class="col-md-12">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Contact us</li>
						</ol>
					</nav>
				</div>

			</div> <!-- End row -->
		</div> <!-- End container -->
	</div> <!-- END BREADCRUMB -->

	<!-- CATEGORIES-2
    ============================================= -->
	<section id="about-2" class="about-section division">
		<div class="container pt-60 pb-60">
			<?
			$info["name"] = "";
			$info["subject"] = "";
			$info["email"] = "";
			$info["comments"] = "";
			?>
			<div class="row d-flex">
				<div class="col-md-5 col-lg-6 contact-top">
					<h3>We're here for you</h3>
					<div>
						<span>Your Name </span>
						<input type="text" class="input-xxlarge" id="namename" value="<?= set_value("name", $info["name"]); ?>" />
						<?= form_error("name", "<span class='text-error'>", "</span>"); ?>
					</div>
					<div>
						<span>Your Email </span>
						<input type="text" class="input-xxlarge" id="emailemail" value="<?= set_value("email", $info["email"]); ?>" />
						<?= form_error("email", "<span class='text-error'>", "</span>"); ?>
					</div>
					<div>
						<span>Subject</span>
						<input type="text" class="input-xxlarge" id="subject" value="<?= set_value("subject", $info["subject"]); ?>" />
						<?= form_error("subject", "<span class='text-error'>", "</span>"); ?>
					</div>
					<div>
						<span>Your Message</span>
						<textarea class="ckeditor" id="comments" rows="7" cols="100" name="comments"><?= set_value("comments", $info["comments"]); ?></textarea>
						<?= form_error("comments", "<span class='text-error'>", "</span>"); ?>
					</div>

					<input type="button" value="SEND" onClick="insert()" class="contactbutt" />

					<br>
					<div class="allinfocontact" id="allinfocontact"></div>
					<script>
						$(document).ready(function() {
							$("#namename, #subject, #emailemail, #comments").on('input change', function() {
								var value = $(this).val();
								if (value === '') {
									$(this).addClass('error');
								} else {
									$(this).removeClass('error');
								}
							});
							$("#emailemail").on('input change', function() {
								var emailfff = document.getElementById('emailemail');
								var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
								if (!filter.test(emailfff.value)) {
									$("#emailemail").addClass('error');
								}
							});
						});

						function insert() {
							var name = $('#namename').val();
							var subject = $('#subject').val();
							var email = $('#emailemail').val();
							var comments = $('#comments').val();

							var emailfff = document.getElementById('emailemail');
							var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

							var boolean = 0;

							if (name == '') {
								$("#namename").addClass('error');
								boolean = 1;
							} else {
								$("#namename").removeClass('error');
							}

							if (email == '') {
								$("#emailemail").addClass('error');
								boolean = 1;
							} else if (!filter.test(emailfff.value)) {
								$("#emailemail").addClass('error');
								boolean = 1;
							} else {
								$("#emailemail").removeClass('error');
							}

							if (subject == '') {
								$("#subject").addClass('error');
								boolean = 1;
							} else {
								$("#subject").removeClass('error');
							}

							if (comments == '') {
								$("#comments").addClass('error');
								boolean = 1;
							} else {
								$("#comments").removeClass('error');
							}

							if (boolean == 0) {

								$.ajax({
									type: 'POST',
									url: "<?php echo base_url() ?>home/contactinfo",
									data: {
										'name': name,
										'subject': subject,
										'email': email,
										'comments': comments
									},
									// dataType: "json"
								}).done(function(response) {
									$('.allinfocontact').html("aaaaaaaaaa");
									if (response) {
										$('.allinfocontact').html(response);
										$('#namename').val('');
										$('#subject').val('');
										$('#emailemail').val('');
										$('#comments').val('');
									} else {
										$('.allinfocontact').html('An error occurred while submitting the form.');
									}
								}).fail(function() {
									$('.allinfocontact').html('An error occurred while submitting the form.');
								});
							}
						}
					</script>

				</div>
				<div class="col-md-5 col-lg-6 contact-top">
					<h3><?php if (isset($info['title'])) echo $info['title']; ?></h3>
					<p><?php if (isset($info['description'])) echo $info['description']; ?></p>
					<!-- Image -->
					<?php if (is_file("uploads/contact_us/" . $info['image'])) { ?>
						<img class="img-fluid" src="<?php echo base_url(); ?>uploads/contact_us/<?php echo $info['image']; ?>" alt="<?php echo $info['title']; ?>" />
					<?php } ?>
				</div>
			</div>


			<!-- <div class="row d-flex">
                <div class="col-md-5 col-lg-6 contact-top">
			       <div class="map">
					   <div class="google-map"><?php echo $info['map']; ?>
						<?php
						if ($info['map']) { ?>
						  <iframe src="<?php echo $info['map']; ?>"></iframe>
						<?php }  ?>
						</div> 
					</div> 
				</div> 
			</div> -->
		</div>
	</section> <!-- End ABOUT-2 -->
</div> <!-- END INNER PAGE WRAPPER -->
<style>
	.contact-top div input[type="text"].error,
	.contact-top div textarea.error {
		border: 1px solid #ff0000 !important;
		color: #ff0000 !important;
	}
</style>