			<!-- FOOTER-2
			============================================= -->
			<footer id="footer-2" class="footer division">
				<div class="container">
					<?php
					if (isset($home_page['14']) && $home_page['14'])
						$footerdescr = $home_page['14'];
					else $footerdescr = '';
					?>
					<!-- FOOTER CONTENT -->
					<div class="row">


						<!-- FOOTER INFO -->
						<div class="col-md-5 col-lg-5 col-xl-4">
							<div class="footer-info mb-40">

								<!-- Footer Logo -->
								<!-- For Retina Ready displays take a image with double the amount of pixels that your image will be displayed (e.g 344 x 80 pixels) -->
								<?php if (is_file("uploads/home_page/" . $footerdescr['image'])) ?>
								<a href="<?php echo $footerdescr['link']; ?>">
									<img src="<?php echo base_url(); ?>uploads/home_page/<?php echo $footerdescr['image']; ?>" width="172" alt="footer-logo">
								</a>
								<!-- Text -->
								<p><?php echo $footerdescr['description']; ?>
								</p>

							</div>
						</div>


						<!-- FOOTER PRODUCTS LINKS -->
						<div class="col-md-3 col-lg-3 col-xl-2">
							<div class="footer-links mb-40">

								<!-- Title -->
								<h5 class="h5-md">Quick Links</h5>

								<!-- Footer Links -->
								<ul class="foo-links clearfix">
									<?php

									foreach ($link_footer as $row) { ?>
										<li><a target="_blank" href="<?php echo $row['link']; ?>"><?php echo $row['title']; ?></a></li>
									<?php } ?>
									<!-- <li><a href="about.html">About STED</a></li>
									<li><a href="categories-list.html">Courses Catalog</a></li>
									<li><a href="reviews.html">Our Testimonials</a></li>
									<li><a href="pricing.html">Premium Learning</a></li>
									<li><a href="blog-listing.html">From the Blog</a></li>								 -->
								</ul>

							</div>
						</div>


						<!-- FOOTER COMPANY LINKS -->
						<div class="col-md-4 col-lg-4 col-xl-3">
							<div class="footer-links mb-40">

								<!-- Title -->
								<h5 class="h5-md">Popular Categories</h5>

								<!-- Footer Links -->
								<ul class="clearfix">
									<?php foreach ($category as $row) {
										if ($row['is_in_footer'] == '1') { ?>
											<li>
												<a href="<?php echo base_url() ?>category/<?php echo $row['id_category']; ?>">
													<?php echo $row['title']; ?>
												</a>
											</li>
									<?php }
									} ?>
									<!-- <li><a href="#">Business English</a></li>
									<li><a href="#">Software Development</a></li>
									<li><a href="#">Finance & Accounting</a></li>
									<li><a href="#">Health and Fitness</a></li>
									<li><a href="#">Office Productivity</a></li>				 -->
								</ul>

							</div>
						</div>


						<!-- FOOTER NEWSLETTER FORM -->
						<div class="col-md-7 col-xl-3">
							<div class="footer-form mb-20">

								<!-- Title -->
								<h5 class="h5-md">Stay in Touch</h5>

								<!-- Newsletter Form Input -->
								<form class="newsletter-form">

									<div class="input-group">
										<input type="hidden" id="url-email" value="<?php echo base_url(); ?>">
										<input type="email" autocomplete="off" class="form-control" placeholder="Your Email Address" required id="s-email">
										<span class="input-group-btn">
											<button type="submit" class="btn btn-rose black-hover">Subscribe</button>
										</span>
									</div>

									<!-- Newsletter Form Notification -->
									<label for="s-email" class="form-notification"></label>

								</form>

							</div>
						</div> <!-- END FOOTER NEWSLETTER FORM -->


					</div> <!-- END FOOTER CONTENT -->


					<!-- BOTTOM FOOTER -->
					<div class="bottom-footer">
						<div class="row">


							<!-- FOOTER COPYRIGHT -->
							<div class="col-lg-8">
								<ul class="bottom-footer-list">
									<li>
										<p>&copy; <span>Copyright</span> STED <?php echo date('Y'); ?></p>
									</li>
									<li>
										<p><a href="tel:+966112543739">+966112543739</a></p>
									</li>
									<li>
										<p class="last-li"><a href="mailto:info@stedtraining.com">info@stedtraining.com</a></p>
									</li>
								</ul>
							</div>


							<!-- FOOTER SOCIALS LINKS -->
							<div class="col-lg-4 text-right">
								<ul class="foo-socials text-center clearfix">

                                 <?php if($generalinfo['twitter']){?>
									<li><a target="_blank" href="<?php echo $generalinfo['twitter'];?>" class="ico-twitter"><i class="fab fa-twitter"></i></a></li>
									 <?php } ?>
									  <?php if(!empty($generalinfo['google'])){?>
									<li><a target="_blank" href="<?php echo $generalinfo['google'];?>" class="ico-google-plus"><i class="fab fa-google-plus-g"></i></a></li>
									<?php } ?>
									<?php if($generalinfo['linkedin']){?>
									<li><a target="_blank" href="<?php echo $generalinfo['linkedin'];?>" class="ico-linkedin"><i class="fab fa-linkedin-in"></i></a></li>
									<?php } ?>
									<?php if($generalinfo['youtube']){?>
									<li><a target="_blank" href="<?php echo $generalinfo['youtube'];?>" class="ico-youtube"><i class="fab fa-youtube"></i></a></li>
									<?php } ?>
									<?php if($generalinfo['facebook']){?>
									<li><a target="_blank" href="<?php echo $generalinfo['facebook'];?>" class="ico-facebook"><i class="fab fa-facebook-f"></i></a></li>
									 <?php } ?>
									<!--
									<li><a href="#" class="ico-behance"><i class="fab fa-behance"></i></a></li>	
									<li><a href="#" class="ico-dribbble"><i class="fab fa-dribbble"></i></a></li>									
									<li><a href="#" class="ico-instagram"><i class="fab fa-instagram"></i></a></li>	
									<li><a href="#" class="ico-linkedin"><i class="fab fa-linkedin-in"></i></a></li>
									<li><a href="#" class="ico-pinterest"><i class="fab fa-pinterest-p"></i></a></li>								
									<li><a href="#" class="ico-youtube"><i class="fab fa-youtube"></i></a></li>										
									<li><a href="#" class="ico-vk"><i class="fab fa-vk"></i></a></li>
									<li><a href="#" class="ico-yelp"><i class="fab fa-yelp"></i></a></li>
									<li><a href="#" class="ico-yahoo"><i class="fab fa-yahoo"></i></a></li>
								    -->

								</ul>
							</div>


						</div>
					</div> <!-- END BOTTOM FOOTER -->


				</div> <!-- End container -->
			</footer> <!-- END FOOTER-2 -->



			<!-- The Modal -->
			<div class="modal" id="myModal">
				<div class="modal-dialog">

					<div class="modal-content" id="header-login">

						<link rel="stylesheet" href="<?php echo base_url(); ?>css/login.css" />
						<div class="login-wrap">
							<div class="login-html">
								<button type="button" class="close" data-dismiss="modal">&times;</button>


								<input id="tab-1" onclick="clicktab('1');" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Sign In</label>
								<input id="tab-2" onclick="clicktab('2');" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Sign Up</label>
								<input id="tab-3" onclick="clicktab('3');" type="radio" name="tab" class="forgot">
								<label for="tab-3" class="tab" style="display:none">Forgot Password</label>

								<div class="login-form">
									<div class="sign-in-htm" style="min-height:4000px;">
										<div class="group">
											<label for="user" class="label">Username</label>
											<input name="username" id="username" type="text" class="input" autocomplete="off">
										</div>
										<div class="group">
											<label for="pass" class="label">Password</label>
											<input name="password" id="password" class="input" data-type="password" autocomplete="off">
											<!-- type="password"  -->
										</div>

										<div class="group">

											<input type="submit" class="button" value="Sign In" onclick="submitform()">
										</div>

										<div class="hr"></div>
										<div class="foot-lnk">
											<label for="tab-3">Forgot Password?</label>
										</div>

									</div>
									<div class="sign-up-htm" style="min-height:4000px;">
										<div id="submitrgistration"></div>
										<div class="group">
											<label for="name" class="label">Full Name</label>
											<input name="name" id="name" type="text" class="input">
										</div>
										<div class="group">
											<label for="email" class="label">Email Address</label>
											<input name="email" id="email" type="text" class="input" data-type="email">
										</div>
										<div class="group">
											<label for="Phone" class="label">Phone</label>
											<input name="phone" id="phone" type="text" class="input" data-type="Phone">
										</div>

										<div class="group">
											<label for="username" class="label">Username</label>
											<input name="username" id="username_r" type="text" class="input" data-type="username" autocomplete="off">
										</div>
										<div class="group">
											<label for="password" class="label">Password</label>
											<input name="password" id="password_r" type="text" class="input" data-type="password" autocomplete="off">
										</div>

										<div class="group">
											<input type="submit" class="button" value="Sign Up" onclick="submitrgistration()">
										</div>
										<div class="hr"></div>
										<div class="foot-lnk">
											<label for="tab-1">Already Member?</a>
										</div>
									</div>


									<div class="forgot-htm">
										<div class="group">
											<label for="user" class="label">Email Address</label>
											<input name="username_forgot" id="username_forgot" type="text" class="input">
										</div>
										<div class="group">
											<div class="loader_button">
												<input type="submit" class="button desgin-button" value="Reset Password" onclick="submitForgot()">
												<div class="loader_loader"></div>
											</div>

										</div>
										<div class="hr"></div>
										<div class="foot-lnk">
											<label for="tab-1">Already Member?</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<style>
						.desgin-button {
							background: #00ADB1;
							border-radius: 5px;
							color: #fff;
							font-size: 1.1em;
							border: none;
							margin-left: 0;
							width: 100%;
							min-width: 100px;
							outline: none;
							font-weight: 600;
							padding: 0.6em 1em;
							transition: 0.5s all;
							-webkit-transition: 0.5s all;
						}

						.loader_button {
							position: relative;
							width: 100%;
						}

						.loader_loader {
							display: none;
							border: 16px solid #f3f3f3;
							border-radius: 50%;
							border-top: 16px solid #ff4500;
							width: 20px;
							height: 20px;
							-webkit-animation: spin 2s linear infinite;
							animation: spin 2s linear infinite;
							position: absolute;
							top: calc(50% - 20px);
							left: calc(50% - 20px);
							transform: translate(-50%, -50%);
						}

						@-webkit-keyframes spin {
							0% {
								-webkit-transform: rotate(0deg);
							}

							100% {
								-webkit-transform: rotate(360deg);
							}
						}

						@keyframes spin {
							0% {
								transform: rotate(0deg);
							}

							100% {
								transform: rotate(360deg);
							}
						}
					</style>
					<style>
						#header-signup {
							display: none
						}

						.modal-header {
							background: #00ADB1;

							.modal-title,
							button {
								color: white;
								opacity: 1
							}
						}

						.modal-backdrop.show {
							opacity: 0 !important;
							z-index: 0 !important;
						}
					</style>
					<script>
						function isEmail(email) {
							// const regx = /^[^\s@]+@stedltd\.com$/;

							const regx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

							return regx.test(email);
						}

						function clicktab(tab = '2') {
							if (tab === '3') {
								$('.login-wrap').css('min-height', '370px');
							} else
							if (tab === '2') {
								$('.login-wrap').css('min-height', '750px');
							} else {
								// Add different styles for other conditions if needed
								$('.login-wrap').css('min-height', '465px');
							}
						}

						/*Custom fucntion to validate data and submit data thorugh ajax*/
						function submitform() {
							/*falg to check the data, if there is an error, flag will turn to 1*/
							var flag = 0;
							/*Checking the value of inputs*/
							var username = $('#username').val();
							var password = $('#password').val();
							/*Validating the values of inputs that it is neither null nor undefined.*/
							if (username == '' || username == undefined) {
								$('#username').css('border', '1px solid red');
								flag = 1;
							}
							if (password == '' || password == undefined) {
								$('#password').css('border', '1px solid red');
								flag = 1;
							}
							/*if there is no error, go to flag==0 condition*/
							if (flag == 0) {
								/*Ajax call*/
								$.ajax({
									url: "<?php echo base_url() ?>getLogin",
									method: 'POST',
									data: "UserName=" + username + "&Password=" + password,
									success: function(result) {
										/*result is the response from LoginController.php file under getLogin.php function*/
										if (result == 1) {
											/*if response result is 1, it means it is successful.*/
											$('#username').css('border', '1px solid green');
											$('#password').css('border', '1px solid green');
											setTimeout(function() {
												/*Redirect to login page after 1 sec*/
												<?php
												if ($this->session->userdata('redirect_url')) {
													$linkpage = $this->session->userdata('redirect_url')['redirect_url'];
												} else $linkpage = base_url();
												?>
												window.location.href = <?php echo json_encode($linkpage, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES); ?>; // Security: safe JS string
											}, 1000)
										} else if (result == 2) {
											/*if response result is 2, it means, username is invalid.*/
											$('#username').css('border', '1px solid red');
											alert('Invalid Username');
										} else if (result == 3) {
											/*if response result is 3, it means, password is invalid.*/
											$('#password').css('border', '1px solid red');
											alert('Invalid Password');
										} else if (result == 7) {
											/*if response result is 3, it means, password is invalid.*/
											$('#password').css('border', '1px solid red');
											alert('Acount not activate');
										} else if (result == 4 || result == 5) {
											/*if response result is 4 or 5, it means, username & password are invalid.*/
											$('#username').css('border', '1px solid red');
											$('#password').css('border', '1px solid red');
											alert('Invalid Username & Passowrd');
										} else {
											alert('Something went wrong');
										}
									}
								});
							} else {
								alert('Something went wrong');
							}
						}
						///////////////////  Forgot password
						function submitForgot() {
							$(".loader_loader").show(); // Show the loader
							$(".desgin-button").prop("disabled", true); // Optionally disable the button



							var flag = 0;
							var username = $('#username_forgot').val();

							if (username == '' || username == undefined) {
								$('#username_forgot').css('border', '1px solid red');
								$(".loader_loader").hide(); // Show the loader
								$(".desgin-button").prop("disabled", false); // Optionally disable the button
								flag = 1;
							}

							// Validate the email
							const invalidEmail = !isEmail(username);

							if (invalidEmail) {
								$(".loader_loader").hide(); // Show the loader
								$(".desgin-button").prop("disabled", false); // Optionally disable the button
								$('#username_forgot').css('border', '1px solid red');
								flag = 1;
								alert('Please check your email, it is invalid: ----@stedltd.com');
								return '';
							}

							if (flag == 0) {
								$.ajax({
									url: "<?php echo base_url() ?>updateLogin",
									method: 'POST',
									data: {
										UserName: username
									},
									success: function(result) {
										/* result is the response from the server */
										if (result == 1) {
											/* If response result is 1, it means it is successful */
											$('#username_forgot').css('border', '1px solid green');
											alert('Please check your email for the reset link.');
											// Uncomment the following block if you want to redirect after success
											// setTimeout(function() {
											// 	window.location.href = '<?php echo base_url(); ?>';
											// }, 1000);

											$(".close").trigger("click");
											$("#tab-1").trigger("click");

										} else if (result == 2) {
											/* If response result is 2, it means the username is invalid */
											$('#username_forgot').css('border', '1px solid red');
											alert('Invalid Username');

										} else if (result == 3) {
											/* If response result is 3, it means the username does not exist in the database */
											$('#username_forgot').css('border', '1px solid red');
											alert('Username does not exist in our database');

										} else {
											/* Handle any other unexpected responses */
											alert('Something went wrong');

										}


										$(".loader_loader").hide(); // Show the loader
										$(".desgin-button").prop("disabled", false); // Optionally disable the button
									},
									error: function() {
										/* Handle errors in the AJAX request */
										alert('Error connecting to server');
									}
								});
							} else {
								alert('Please fill in the required field');
							}
						}

						///////////////////////////////
						function submitrgistration() {
							/*falg to check the data, if there is an error, flag will turn to 1*/
							var flag = 0;
							/*Checking the value of inputs*/
							var name = $('#name').val();
							var phone = $('#phone').val();
							var email = $('#email').val();
							var username_r = $('#username_r').val();
							var password_r = $('#password_r').val();
							$('#submitrgistration').html('');
							$('#submitrgistration').css('display', 'none');
							$('.hr').css('margin', '30px 0 30px 0');
							$('.login-form').css('margin-top', '30px');
							const invalidEmail = !isEmail(email);

							$('#name').css('border', '0');
							$('#phone').css('border', '0');
							$('#email').css('border', '0');
							$('#username_r').css('border', '0');
							$('#phone').css('border', '0');
							$('#password_r').css('border', '0');


							/*Validating the values of inputs that it is neither null nor undefined.*/





							if (name == '' || name == undefined) {
								$('#name').css('border', '1px solid red');
								$('.hr').css('margin', '0px 0 20px 0');
								$('#submitrgistration').html('Full name is empty');
								$('#submitrgistration').css('display', 'block');
								$('.login-form').css('margin-top', '0');

								flag = 1;
							} else
							if (email == '' || email == undefined || invalidEmail) {
								$('#email').css('border', '1px solid red');
								if (email == '' || email == undefined)
									$('#submitrgistration').html('Email is empty');
								else
								if (invalidEmail) {
									$('#submitrgistration').html('Only STED employees are authorized to carry out the registration process.');
								}
								$('#submitrgistration').css('display', 'block');
								$('.login-form').css('margin-top', '0');

								$('.hr').css('margin', '0px 0 20px 0');
								flag = 1;
							} else
							if (phone == '' || phone == undefined) {
								$('#phone').css('border', '1px solid red');
								$('#submitrgistration').html('Phone is empty');
								$('#submitrgistration').css('display', 'block');
								$('.login-form').css('margin-top', '0');

								$('.hr').css('margin', '0px 0 20px 0');
								flag = 1;
							} else
							if (username_r == '' || username_r == undefined) {
								$('#username_r').css('border', '1px solid red');
								$('#submitrgistration').html('Username is empty');
								$('#submitrgistration').css('display', 'block');
								$('.login-form').css('margin-top', '0');

								$('.hr').css('margin', '0px 0 20px 0');
								flag = 1;
							} else
							if (password_r == '' || password_r == undefined) {
								$('#password_r').css('border', '1px solid red');
								$('#submitrgistration').html('Password is empty');
								$('#submitrgistration').css('display', 'block');
								$('.login-form').css('margin-top', '0');

								$('.hr').css('margin', '0px 0 20px 0');
								flag = 1;
							}

							/*if there is no error, go to flag==0 condition*/
							if (flag == 0) {
								/*Ajax call*/
								$.ajax({
									url: "<?php echo base_url() ?>Registration",
									method: 'POST',
									data: "email=" + email + "&phone=" + phone + "&name=" + name + "&password_r=" + password_r + "&username_r=" + username_r,
									success: function(result) {
										/*result is the response from LoginController.php file under getLogin.php function*/
										if (result == 100) {
											$('#email').css('border', '1px solid red');
											$('.hr').css('margin', '0px 0 20px 0');
											$('#submitrgistration').html('Invalid phone');
											$('#submitrgistration').css('display', 'block');
											$('.login-form').css('margin-top', '0');
											$('#submitrgistration').html('Only STED employees are authorized to carry out the registration process.');
										} else
										if (result == 1) {
											/*if response result is 1, it means it is successful.*/
											$('#email').css('border', '1px solid green');
											$('#password_r').css('border', '1px solid green');
											alert('Please active your account, already send email to verification your!');
											setTimeout(function() {
												/*Redirect to login page after 1 sec*/
												window.location.href = '<?php echo base_url() ?>home';
											}, 1000)
										} else if (result == 2) {
											/*if response result is 2, it means, username is invalid.*/
											$('#email').css('border', '1px solid red');
											$('.hr').css('margin', '0px 0 20px 0');
											$('#submitrgistration').html('Invalid email');
											$('#submitrgistration').css('display', 'block');
											$('.login-form').css('margin-top', '0');


											// alert('Invalid email');
										} else if (result == 3) {
											/*if response result is 3, it means, password is invalid.*/
											$('#phone').css('border', '1px solid red');
											$('.hr').css('margin', '0px 0 20px 0');
											$('#submitrgistration').html('Invalid phone');
											$('#submitrgistration').css('display', 'block');
											$('.login-form').css('margin-top', '0');

											//alert('Invalid phone');
										} else if (result == 6) {
											/*if response result is 3, it means, password is invalid.*/
											$('#name').css('border', '1px solid red');
											$('.hr').css('margin', '0px 0 20px 0');
											$('#submitrgistration').html('Invalid name');
											$('#submitrgistration').css('display', 'block');
											$('.login-form').css('margin-top', '0');


											//alert('Invalid name');
										} else if (result == 4 || result == 5) {
											/*if response result is 4 or 5, it means, username & password are invalid.*/
											$('#username_r').css('border', '1px solid red');
											$('#password_r').css('border', '1px solid red');

											$('.hr').css('margin', '0px 0 20px 0');
											$('#submitrgistration').html('Invalid Username & Passowrd');
											$('#submitrgistration').css('display', 'block');
											$('.login-form').css('margin-top', '0');

											//alert('Invalid Username & Passowrd');
										} else if (result == 7) {
											/*if response result is 2, it means, username is invalid.*/
											$('#email').css('border', '1px solid red');

											$('.hr').css('margin', '0px 0 20px 0');
											$('#submitrgistration').html('Email not active');
											$('#submitrgistration').css('display', 'block');
											$('.login-form').css('margin-top', '0');

											//alert('Email Already Exist');
										} else {

											$('.hr').css('margin', '0px 0 20px 0');
											$('#submitrgistration').html('Something went wrong');
											$('#submitrgistration').css('display', 'block');
											$('.login-form').css('margin-top', '0');

											// alert('Something went wrong');
										}
									}
								});
							} else {
								// alert('Something went wrong');
							}
						}
					</script>

					<style>
						#submitrgistration {
							display: none;
							background: red;
							color: white;
							font-weight: bold;
							padding: 5px;
							border-radius: 4px;
							width: 50%;
							margin: 10px auto;
							margin10px: auto;
							font-size: 12px;
							margin-top: 10px;
							text-align: center;
							/* text-transform: capitalize; */
							text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
						}
					</style>