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
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>edit-profile">Home</a></li>
							<!-- <li class="breadcrumb-item"><a href="teachers-list.html">All Teachers</a></li> -->
							<li class="breadcrumb-item active" aria-current="page"><?php echo $user['name']; ?></li>
						</ol>
					</nav>
				</div>

			</div> <!-- End row -->
		</div> <!-- End container -->
	</div> <!-- END BREADCRUMB -->

	<section id="team-3" class="pt-0 pb-100 team-section division">
		<div class="container">
			<div class="row mt-0 mt-md-4">
				<div class="col-lg-3 col-md-4 col-12 tutor-dashboard-left-menu">
					<!-- Side
		============================================= -->
					<?php $data['login'] = $login;
					$data['login']['page'] = 'editprofile';
					$this->load->view('include/side_profile', $data);
					?>
					<!-- Side  CONTENT
		============================================= -->
				</div>

				<div class="col-lg-9 col-md-8 col-12">
					<div class="tutor-dashboard-content">
						<div class="tutor-dashboard-content-inner">

							<div class="tutor-dashboard-inline-links">

								<div class="tutor-dashboard-content-inner card">

									<div class="card-header">
										<h3 class="mb-0">Profile Details</h3>
									</div>

									<div class="card-images card-body">
										<form id="imageform" class="form-image" method="post" enctype="multipart/form-data" action='<?php echo base_url(); ?>users/upload'>
											<!-- Custom-styled file input button -->
											<label for="photoimg" class="custom-file-upload">
												Upload your image
											</label>
											<input type="file" name="photoimg" id="photoimg" style="display: none;" />
											<input type="hidden" id="id_profile_image" name="id_profile_image" value="<?php echo $id_profile; ?>" readonly>
										</form>
										<div id='preview'>
											<?php
											$target_file = base_url() . "images/nopic.jpg";
											if ($user['image'])
												$target_file = base_url() . "uploads/user/" . $user['image'];
											echo '<img src="' . htmlspecialchars($target_file) . '" alt="StedLb" class="preview">';
											?>
										</div>
									</div>




									<div class="card-body">
										<h4 class="mb-3 pb-2 " style="border-bottom:1px solid #eae7f5">Personal Details</h4>
										<form method="post" id="savedata" enctype="multipart/form-data">
											<div class="row">

												<!-- <div class="mb-3 col-12 col-12 col-md-6">
					<label class="form-label">Select image to upload:</label>
					 
					<input class="form-control" type="file" id="uploadData" name="uploadData"  onChange="fileSelect();" >
				 
				</div>
				<div class="mb-3 col-12 col-12 col-md-6">
				<label class="form-label"> &nbsp</label>
					<input   class="form-control" type="text" id="image" name="image" value="<?php echo $user['image']; ?>" placeholder="image"
					 onchange="submitFormData('image');" >
					 
					 <span id="msg_image" class="msg error valid">update success</span>
				</div> -->

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
												if ($id_user && $permisssion_admin_user['0']['role'] == "Admin") { ?>

													<div class="mb-3 col-12">
														<label for="active" class="form-label">Active</label>
														<select class="form-control" id="active" name="active" onchange="submitFormData('active');">
															<option value="0" selected="selected">Select active</option>
															<option value="0" <?php if ($user['active'] == 0) {
																					echo ' selected';
																				} ?>>InActive</option>
															<option value="1" <?php if ($user['active'] == 1) {
																					echo ' selected';
																				} ?>>Active</option>

														</select>
														<span id="msg_countries" class="msg error valid">update success</span>

													</div>
												<?php } ?>
												<div class="mb-3 col-12 col-md-6">
													<label class="form-label">Name</label>
													<input class="form-control" type="text" id="name" name="name" value="<?php echo $user['name']; ?>" placeholder="Name" onchange="submitFormData('name');">
													<span id="msg_name" class="msg error valid">update success</span>
												</div>

												<div class="mb-3 col-12 col-md-6">
													<label class="form-label">Phone</label>
													<input class="form-control" type="tel" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" name="phone" value="<?php echo $user['phone']; ?>" placeholder="Phone" onchange="submitFormData('phone');">
													<span id="msg_phone" class="msg error valid">update success</span>


												</div>

												<div class="mb-3 col-12 col-md-6">
													<label class="form-label">User Name</label>
													<input class="form-control" type="text" disabled="disabled" value="<?php echo $user['username']; ?>">
												</div>

												<div class="mb-3 col-12 col-md-6">
													<label class="form-label">Email</label>
													<input class="form-control" type="text" disabled="disabled" value="<?php echo $user['email']; ?>">
													<span id="msg_email" class="msg error valid">update success</span>

												</div>

												<div class="mb-3 col-12">
													<label class="form-label">profession</label>
													<input class="form-control" type="text" name="occupation" value="<?php echo $user['occupation']; ?>" placeholder="Ex:Mechanical Engineer" onchange="submitFormData('occupation');">
													<span id="msg_occupation" class="msg error valid">update success</span>

												</div>


												<div class="mb-3 col-12">
													<label for="countries" class="form-label">Country</label>
													<select class="form-control" id="id_countries" name="id_countries" onchange="submitFormData('id_countries');">
														<option value="0" selected="selected">Select Country</option>
														<?php foreach ($countries as $row) { ?>
															<option value="<?php echo $row['id_countries']; ?>" <?php if ($user['id_countries'] == $row['id_countries']) {
																													print ' selected';
																												} ?>><?php echo $row['title']; ?></option>
														<?php } ?>
													</select>
													<span id="msg_countries" class="msg error valid">update success</span>

												</div>

												<div class="mb-3 col-12 col-md-6">
													<label class="form-label">Age</label>
													<input class="form-control" type="text" name="age" value="<?php echo $user['age']; ?>" placeholder="" onchange="submitFormData('age');">
													<span id="msg_age" class="msg error valid">update success</span>

												</div>

												<div class="mb-3 col-12 col-md-6">
													<label class="form-label">Gender</label>

													<select class="form-control" name="id_gender" id="id_gender" onchange="submitFormData('id_gender');">
														<option value="1" <?php if ($user['id_gender'] == 1) { ?> selected <?php } ?>>Male</option>
														<option value="2" <?php if ($user['id_gender'] == 2) { ?> selected <?php } ?>>Female</option>
													</select>

													<!-- <input class="form-control" type="text" name="id_gender"
					   value="<?php echo $user['id_gender']; ?>" placeholder=""  
					    onchange="submitFormData('id_gender');" > -->
													<span id="msg_id_gender" class="msg error valid">update success</span>

												</div>

												<div class="mb-3 col-12	">
													<label class="form-label">Years Of Experience</label>
													<input class="form-control" type="text" name="years_of_exp" value="<?php echo $user['years_of_exp']; ?>" placeholder="" onchange="submitFormData('years_of_exp');">
													<span id="msg_years_of_exp" class="msg error valid">update success</span>

												</div>

												<div class="mb-3 col-12">
													<label class="form-label">Bio</label>
													<textarea class="form-control" name="bio" onchange="submitFormData('bio');"><?php echo $user['bio']; ?></textarea>
													<!-- <span id="msg_bio_name" class="msg error valid">update success</span> -->

												</div>
											</div>


											<h4 class="mb-3 pb-2 mt-5" style="border-bottom:1px solid #eae7f5">Social Profiles</h4>

											<div class="row">
												<div class="mb-3 col-12">
													<label class="form-label" for="_tutor_profile_website">Website</label>
													<input class="form-control" type="text" id="website" name="website" value="<?php echo $user['website']; ?>" onchange="submitFormData('website');">
													<span id="msg_website" class="msg error valid">update success</span>

												</div>

												<div class="mb-3 col-12">
													<label class="form-label" for="_tutor_profile_facebook">Facebook</label>
													<input class="form-control" type="text" id="facebook" name="facebook" placeholder="https://facebook.com/username" value="<?php echo $user['facebook']; ?>" onchange="submitFormData('facebook');">
													<span id="msg_facebook" class="msg error valid">update success</span>

												</div>

												<div class="mb-3 col-12">
													<label class="form-label" for="twitter">Twitter</label>
													<input class="form-control" type="text" id="twitter" name="twitter" placeholder="https://twitter.com/username" value="<?php echo $user['twitter']; ?>" onchange="submitFormData('twitter');">
													<span id="msg_twitter" class="msg error valid">update success</span>

												</div>

												<div class="mb-3 col-12">
													<label class="form-label" for="_tutor_profile_linkedin">Linkedin</label>
													<input class="form-control" type="text" id="linkedin" name="linkedin" placeholder="https://linkedin.com/username" value="<?php echo $user['linkedin']; ?>" onchange="submitFormData('linkedin');">
													<span id="msg_linkedin" class="msg error valid">update success</span>

												</div>
											</div>

											<!-- <div class="tutor-form-group tutor-profile-form-btn-wrap">
				<button type="submit" class="btn btn-primary tutor-button tutor-profile-settings-save">Update Profile</button>
			</div> -->

											<input type="hidden" id="id_profile" name="id_profile" value="<?php echo $id_profile; ?>" readonly>
										</form>


										<div class="mb-3 col-12 col-md-6" style="display:none">
											<label class="form-label">Signature:</label>

											<div class="card-images-signature card-body">
												<form id="imageform_signature" class="form-image" method="post" enctype="multipart/form-data" action='<?php echo base_url(); ?>users/upload_signature'>
													<!-- Custom-styled file input button -->
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

						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

	<hr>

	<script>
		function submitFormData(msg) {
			$.ajax({
					type: 'POST',
					url: "<?php echo base_url(); ?>home/updatedata",
					data: $('#savedata').serialize(), // this doesn't work
					dataType: "json",
				})
				.done(function(response) {
					if (response) {
						$('#msg_' + msg).val('Data Updated');
						$('#msg_' + msg).removeClass('error');
						$('#msg_' + msg).addClass('valid msg_show');

						var $element = $('#msg_' + msg);
						// Remove a class from the element after one second
						setTimeout(function() {
							$element.removeClass('msg_show');
						}, 3000); // 1000 milliseconds = 1 second

					} else { // Successful post, but not response came back !?

						var $element = $('#msg_' + msg);
						// Remove a class from the element after one second
						setTimeout(function() {
							$element.removeClass('msg_show');
						}, 3000); // 1000 milliseconds = 1 second

						$('#msg_' + msg).val('Error');
						$('#msg_' + msg).removeClass('valid');
						$('#msg_' + msg).addClass('error msg_show');

						console.error("Post sent, but no response came back!?");
					}
				}).fail(function(response) {
					console.error("Unknown Error!"); // not sure yet how I'd get here... ?
				});
		}
	</script>
	<script type="text/javascript">
		// var textbox = document.getElementById('image');
		// var fileselect = document.getElementById('uploadData');

		// function fileSelect()
		// {
		//     var x =  document.getElementById('uploadData').value;
		//     var fileName = x.match(/[^\/\\]+$/);
		//     document.getElementById('image').value = fileName;

		// 	var formData = new FormData($('#savedata')[0]);
		// 	$.ajax({
		//             url: '<?php echo base_url(); ?>users/upload', // URL to your server-side script
		//             type: 'POST',
		//             data: formData,
		//             processData: false,
		//             contentType: false,
		//             beforeSend: function() {
		//                 $('#uploadStatus').html('Uploading...');
		//             },
		//             success: function(response){
		//                 $('#uploadStatus').html(response);
		//             },
		//             error: function(xhr, status, error){
		//                 $('#uploadStatus').html('Error: ' + error);
		//             }
		//         });


		// 	submitFormData('image');
		// }
	</script>

	<script>
		// var jq331 = $.noConflict(true);
	</script>




	<style>
		.loader {
			width: 100px;
			height: 100px;
		}

		#preview_signature {
			width: 300px;
			height: 60px;
			text-align: center;
			z-index: 0;
			position: absolute;
		}

		#preview_signature img {
			width: 300px;
			height: 60px;
		}

		.preview-signature {
			width: 300px;
			height: 60px;
			object-fit: cover;
		}

		.card-images-signature {
			width: 300px;
			height: 125px;
			position: relative;
		}

		.form-image-signature {
			position: absolute;
			width: 300px;
			width: 100%;
			z-index: 1;
		}

		.custom-file-upload-signature {
			cursor: pointer;
			display: inline-block;
			padding: 8px 20px;
			background-color: #00ADB1;
			color: white;
			border: none;
			border-radius: 4px;
			text-align: center;
			text-decoration: none;
			font-size: 16px;
			margin-top: 70px;
			width: 100%;
			cursor: pointer;
		}

		.custom-file-upload-signature:hover {
			background-color: #F16624;
		}


		#preview {
			width: 100%;
			height: 100%;
			text-align: center;
			z-index: 0;
			position: absolute;
		}

		#preview .preview {
			border-radius: 50%;
			width: 100px;
			height: 100px;
			object-fit: cover;
		}


		/* Style for the custom file input button */
		.card-images {
			width: 200px;
			height: 200px;
			position: relative;
		}

		.form-image {
			position: absolute;
			height: 133px;
			width: 100%;
			z-index: 1;
		}

		.custom-file-upload {
			cursor: pointer;
			display: inline-block;
			padding: 8px 20px;
			background-color: #00ADB1;
			color: white;
			border: none;
			border-radius: 4px;
			text-align: center;
			text-decoration: none;
			font-size: 16px;
			margin-top: 105px;
			width: 100%;
			cursor: pointer;
		}

		.custom-file-upload:hover {
			background-color: #F16624;
		}
	</style>