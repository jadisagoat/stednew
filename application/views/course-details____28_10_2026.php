<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/vplayer.css"/> -->

<!-- PAGE CONTENT
		============================================= -->
<div>
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
								<?php //foreach($coursedetail as $row){ 
								?>

								<!-- <li class="breadcrumb-item"><a href="courses-list.html"><?php echo $coursedetail['categtitle']; ?></a></li> -->
								<li class="breadcrumb-item"><?php echo $coursedetail['categtitle']; ?>
									<!-- <li class="breadcrumb-item active" aria-current="page"><?php //echo $coursedetail['subcategtitle']; 
									?></li> -->
								<li class="breadcrumb-item active" aria-current="page">
									<?php echo $coursedetail['coursetitle']; ?>
								</li>
								<input type="hidden" name='id_course' id='id_course'
									value="<?php echo $coursedetail['id_course']; ?>">
								<?php // } 
								?>
							</ol>
						</nav>
					</div>

				</div> <!-- End row -->
			</div> <!-- End container -->
		</div> <!-- END BREADCRUMB -->
		<!-- COURSE DETAILS
				============================================= -->
		<section id="course-details" class="wide-40 course-section division pt-20">
			<div class="container">
				<div class="row">

					<?php // foreach($coursedetail as $row){ 
					?>
					<!-- COURSE DESCRIPTION -->
					<div class="col-lg-8">
						<div class="course-txt pr-30">
							<!-- Course Title -->
							<h3 class="h3-sm"><?php echo $coursedetail['coursetitle']; ?></h3>
								<!-- <button type="button" id="rateProduct" class="btn btn-md btn-rose  <?php //if(empty($_SESSION['userid'])){echo $settingClass;}  
								?>" >Rate This Product</button> -->
								<div class="form-group rating-class DOMContentLoaded course-detail"
									style="margin: 12px 0 20px 0; padding: 0 !important; position: static; height: auto;">
									<?php
									$average = $coursedetail['avg_course_rating'];
									//$averageRating = round($average, 0);
									for ($i = 1; $i <= 5; $i++) {
										$ratingClass = "star-grey";
										if ($i <= $average) {
											$ratingClass = "star-highlight";
										}

										echo '<i class="fa fa-star fa-lg rateButton  ' . $ratingClass . '"  aria-hidden="true"></i>';
									}
									?>
								</div>
							<!-- Course Shot Description -->
							<div class="p-md">
								<?php echo $coursedetail['description']; ?>
							</div>
							<!-- Course Short Data -->
							<div class="course-short-data">
								<!-- Created by: <?php echo $coursedetail['clienttitle']; ?> &bull;  -->
								<!-- Email:  <?php echo $coursedetail['clientemail']; ?> &bull;  -->

								<b><i><?php echo $coursedetail['categtitle']; ?> <?php //echo $coursedetail['subcategtitle']; 
									?> </i></b>
								<br>
								<?php
								if (isset($alllecture) && count($alllecture)) { ?>
									Lecture: <?php $kk = 0;
									foreach ($alllecture as $row) {
										if ($kk != 0)
											echo ', ';
										echo $row;
									} ?>
								<?php } ?><br>
								<?php if ($coursedetail['updated_date']) { ?>
									Last updated: <?php
									$date = date('d/m/Y', strtotime($coursedetail['updated_date']));
									echo $date;
								} ?> <br>
								<?php // echo $coursedetail['coursetitle']; 
								?>
								<!-- + 7 more -->
							</div>
							<!-- Course Rating -->
							<!-- <div class="course-rating clearfix">
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star"></i>
											<i class="fas fa-star-half-alt"></i>
											<span>4.84 (468 Ratings) &bull; 14,425 Students Enrolled</span>
										</div> -->
						</div>
					</div>
					<!-- COURSE DATA -->
					<div class="col-lg-4">
						<div class="course-data">
							<img class="img-fluid"
								src="<?php echo course_image_url($coursedetail['image']); ?>"
								alt="course-preview" />
						</div>

						<div class="course-data-links mt-10">
							<?php
							if ($userenrollement) { ?>
								<span class="btn btn-md btn-turquoise tra-g re y-hover">Enrolled</span>
								<?php
								if ($userenrollement == '2') {  //teacher			
									if ($id_exam) { ?>
										<a href="<?php echo base_url(); ?>editexam/<?php echo $id_exam; ?>">
											<span class="btn btn-md btn-turquoise tra-g re y-hover">Edit Exam</span>
										</a>
									<?php } else { ?>
										<a
											href="<?php echo base_url(); ?>editexam/<?php echo $coursedetail['id_course']; ?>/addincourse">
											<span class="btn btn-md btn-turquoise tra-g re y-hover">Add Exam</span>
										</a>
									<?php }
								} else
									if ($userenrollement == '3') {  //student	
							
										if (count($questions) && $avgcourse[$coursedetail['id_course']] == "100.00") { ///10000 
											$ClickExam = 'ClickExamRateFeedback1';
											$attributes = '';
											if ($my_user_course_rating['0']['rating'] && $my_user_course_rating['0']['feedback']) {
												$ClickExam = 'ClickExam';
												$attributes = ' data-toggle="modal" data-target="#examModal" ';
											}
											?>
											<!-- aaaaaaaaaaaaaaaaaaaaac -->

											<a class="<?php echo $ClickExam; ?>" <?php echo $attributes; ?> style="cursor:pointer">
												<span class="  aaa btn btn-md btn-turquoise tra-g re y-hover">Take Exam</span>
											</a>
										<?php }
									}
							} else { ?>
								<a href="#" class="btn btn-md btn-rose tra-grey-hover"
									onclick="submitEnrollCourse('<?php echo $coursedetail['id_course']; ?>');" ;>Enroll
									Now</a>
							<?php } ?>
						</div>
					</div>
				</div>


				<!-- fahrasss -->
				<?php if (isset($login) && $login && $userenrollement != 0) { ?>
					<section id="about-2" class="about-section division">
						<div class="pt-60 pb-20">
							<div class="panell panell-primary">
								<div class="panell-heading">
									<?php echo $coursedetail['coursetitle']; ?>
								</div>

								<div class="panell-body"><!-- Button to trigger modal -->
									<?php foreach ($contents as $row) { ?>
										<div class="row d-flex pb-3">
											<div class="col-md-12 link ">
												<!-- cursor-pointer data-toggle="modal" data-target="#courseModal"> -->
												<label class="boolean-label boolean-true"><?php echo $row['title']; ?></label>
											</div>
										</div>
									<?php }
									if (count($questions) && $avgcourse[$coursedetail['id_course']] == "100") {  //10000 
										$style = " style=' display:block !important'";
									} else { // condition to start exam not achieved
										$style = " style=' display:none !important'";
										?>
										<!-- <div class="row d-flex pb-3">
	<div class="col-md-12 link cursor-pointer">
		Exam
	</div>
</div> -->
									<?php } ?>
									<div class="row d-flex pb-3">
										<!-- #endregion -->
										<div id="startcourse" class="col-md-2 link cursor-pointer startcourse"
											data-toggle="modal" data-target="#courseModal">
											<span class="btn btn-turquoise tra-g re y-hover">Start Course</span>
										</div>

										<?php

										// echo "<pre>";
										// print_r($questions);
										// exit();
										$boolean_finish_read_course = 0;
										if (count($questions)) {
											if ($avgcourse[$coursedetail['id_course']] == "100") {
												$boolean_finish_read_course = 1;
												$style = " style=' display:block !important'";
											} else {
												$style = " style=' display:none !important'";
											}
											$ClickExam = 'ClickExamRateFeedback2';
											$attributes = '';
											if ($my_user_course_rating['0']['rating'] && $my_user_course_rating['0']['feedback']) {
												$ClickExam = 'ClickExam';
												$attributes = ' data-toggle="modal" data-target="#examModal" ';
											} ?>

											<!-- aaaaaaaaaaaaaaaaaaaaab -->
											<!-- <div id="examClick" <?php echo $style; ?> class="<?php echo $ClickExam; ?> tak-exam col-md-2 link cursor-pointer  btn btn-turquoise tra-g re y-hover" data-toggle="modal" data-target="#examModal"> -->
											<div id="examClick" <?php echo $attributes; ?> 		<?php echo $style; ?>
												class="<?php echo $ClickExam; ?> tak-exam col-md-2 link cursor-pointer  btn btn-turquoise tra-g re y-hover">

												Take Exam
											</div>
											<?php if ($boolean_finish_read_course) { ?>


											<?php }
										} ?>
									</div>
								</div>
							</div>
						</div>
					</section>
					<!-- fahrass -->
					<?php include('course-details-popup.php');
				} ?>

				<?php // } 
				?>
				<?php
				if (count($questions))
					include('course-details-popup-exam.php');
				?>
				<!-- /.container -->

				<section id="course-details" class="wide-0 course-section division">
					<div class="">
						<div class="row">
							<div class="col-lg-12">
								<div class="course-txt pr-30">

									<!-- 
									<div class="what-you-learn">
										<h5 class="h5-xl">What you'll learn</h5>
										<div class="row">		 
											<div class="col-lg-12">
												 
													   <ul><?php echo $coursedetail['cou rse_brief']; ?></ul>
											</div>
										</div>
									</div>	 -->
									<!-- COURSE REQUIREMENTS -->
									<div id="Rate_this_course1">&nbsp;</div>
									<div id="Rate_this_course" class="cs-requirements cd-block">
										<!-- Small Title -->
										<h5 class="h5-xl" id="Rate_this_course2">Requirements</h5>
										<?php //foreach($coursedetail as $row) {
										?>
										<?php echo $coursedetail['requirements'] ?><?php //} 
										   ?>
									</div> <!-- END COURSE REQUIREMENTS -->
									<?php

									if (isset($login) && $login && $userenrollement != 0) {
										//$contents 
										$displaynone = ' ';

										if ($avgcourse[$coursedetail['id_course']] == "100") {
											$comment_list_style = " display:block !important";
											$displaynone = ' ;display:block !important  ';
										} else {
											$comment_list_style = " display:none !important ";
											$displaynone = ' ;display:none !important  ';
										}

										if ($my_user_course_rating['0']['rating'] && $my_user_course_rating['0']['feedback']) {
											$displaynone = ' ;display:none !important  ';
										}

										?>
										<div
											style=" padding-top:30px;color:red;font-weight: bold; <?php echo $displaynone; ?>">
											Before taking the exam, we kindly ask you to provide your feedback on the course
											along with your rating.<br>Thank you for your input, and we wish you the best of
											luck!
											<br><br>
										</div>
										<div class="add_list_reviews" style="<?php echo $comment_list_style; ?>">
											<h5 class="h4-xl">
												Rate this course
											</h5>
											<form id="list_rating" action="post">
												<div id="ratingSection"
													class="form-group rating-class DOMContentLoaded course-detail"
													style="margin: 12px 0 20px 0; padding: 0 !important; position: static; height: auto;">
													<?php
													$my_rating = $my_user_course_rating['0']['rating'];
													//$averageRating = round($average, 0);
													for ($i = 1; $i <= 5; $i++) {
														$ratingClass = "star-grey";
														if ($i <= $my_rating) {
															$ratingClass = "star-highlight star-selected";
														}
														echo '<i class="fa fa-star fa-lg rateButton rateClick ' . $ratingClass . '"  aria-hidden="true"></i>';
													}
													// echo "<span class='counter'>".$count . ' Reviews </span>';
													?>

													<input type="hidden" class="form-control" id="ratingClick"
														name="ratingClick" value="<?php echo $my_rating; ?>">
													<input type="hidden" class="form-control" id="id_course"
														name="id_course" value="<?php echo $coursedetail['id_course']; ?>">
												</div>
											</form>
											<br>
											<?php
											$has_feedback = ($count > 0 && isset($my_user_course_rating['0']) && $my_user_course_rating['0']['feedback'] != '');
											$form_style = $has_feedback ? "display:none !important;" : "display:block !important;";
											$comment_style = $has_feedback ? "display:block !important;" : "display:none !important;";
											?>
											<form method="post" name="list_reviews" id="list_reviews"
												style="<?php echo $form_style; ?>">
												<div class="block-feedback">
													<h5 class="h4-xl">
														Feedback
														<i class="fa fa-plus-square" style="float:right" aria-hidden="true"
															id="submitButton" title="Add Feedback"></i>
													</h5>
													<textarea id="reviews" name="reviews"
														placeholder="Please Insert Review"><?php echo $has_feedback ? htmlspecialchars($my_user_course_rating['0']['feedback']) : ''; ?></textarea>
												</div>

												<div class="d-flex pb-3 tak-left">

													<?php
													$id_rating_val = '';
													if (isset($my_user_course_rating['0']['id_user_course_rating'])) {
														$id_rating_val = $my_user_course_rating['0']['id_user_course_rating'];
													} else if (isset($coursedetail['id_user_course_rating'])) {
														$id_rating_val = $coursedetail['id_user_course_rating'];
													}
													?>
													<input type="hidden" class="form-control" id="id_user_course_rating"
														name="id_user_course_rating" value="<?php echo $id_rating_val; ?>">
													<input type="hidden" class="form-control" id="id_course"
														name="id_course" value="<?php echo $coursedetail['id_course']; ?>">
													<div class="loader_button">
														<div
															class="link cursor-pointer btn btn-md btn-turquoise tra-g re y-hover postReviews desgin-button">
															Post
														</div>
														<div class="loader_loader"></div>
													</div>

												</div>
												<br clear="All">
											</form>

											<div class="listComments" style="<?php echo $comment_style; ?>"
												id="listComments">
												<?php foreach ($my_user_course_rating as $row) { ?>
													<div class="comment-row col-md-12"
														id="<?php echo $row['id_user_course_rating']; ?>">
														<div class="comment-info d-flex">
															<div id='preview' class="col-md-2">
																<?php
																$target_file = base_url() . "images/nopic.jpg";
																if ($row['image'])
																	$target_file = base_url() . "uploads/user/" . $row['image'];
																echo '<img src="' . htmlspecialchars($target_file) . '" alt="StedLb" class="preview">';
																?>
															</div>
															<div class="col-md-10 preview-right">
																<span class="posted-by"><?php echo $row['name']; ?></span>
																<span class="posted-at postedat"><?php
																$timestamp = strtotime($row['created_date']);
																$formattedDate = date("j/n/Y", $timestamp);
																echo $formattedDate; // Output: 26/4/2024
																?></span>
																<div class="comment-text"><?php echo nl2br(htmlspecialchars($row['feedback'], ENT_QUOTES, 'UTF-8')); ?></div>
																<!-- <span class="commentdelete" onclick="commentdelete(<?php echo $row['id_user_course_rating']; ?>)">
																	<i class="fa fa-trash"></i>
																</span> -->
																<span class="commentedit" onclick="editFeedback()">
																	<i class="fa fa-edit"></i>
																</span>
															</div>

														</div>
													</div>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div> <!-- END COURSE DESCRIPTION -->
					</div> <!-- End row -->
			</div> <!-- End container -->
		</section> <!-- END COURSE DETAILS -->

		<section id="courses-5" class="bg-whitesmoke courses-section division">
			<div class="container">

				<!-- SECTION TITLE -->
				<div class="row">
					<div class="col-md-12">
						<div class="section-title mb-60">
							<!-- Title 	-->
							<h4 class="h4-xl">Browse Similar Courses</h4>
							<!-- Text -->
							<p class="p-md">Below, you will find courses similar to the one you have selected.
							</p>
						</div>
					</div>
				</div>

				<!-- COURSES HOLDER -->
				<div class="row courses-grid">
					<?php foreach ($similarcourses as $row) { ?>
						<!-- COURSE #2 -->
						<div class="col-md-6 col-lg-4 col-xl-3">
							<div class="cbox-1 position-relative">
								<a href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>">
									<!-- Image -->
									<img class="img-fluid"
										src="<?php echo course_image_url($row['image']); ?>"
										alt="co   w" />
									<!-- Text -->
									<div class="cbox-1-txt">
										<!-- Course Tags -->
										<p class="course-tags">
											<span><?php echo $row['title']; ?></span>
										</p>
										<!-- Title -->
										<h5 class="h5-xs"><?php echo $row['summary']; ?></h5>
										<!-- Course Rating -->
									</div>
								</a>


								<div class="form-group rating-class DOMContentLoaded similarcourses">
									<?php
									$average = $row['avg_course_rating'];
									//$averageRating = round($average, 0);
									for ($i = 1; $i <= 5; $i++) {
										$ratingClass = "star-grey";
										if ($i <= $average) {
											$ratingClass = "star-highlight ";
										}

										echo '<i class="fa fa-star fa-lg rateButton ' . $ratingClass . '"  aria-hidden="true"></i>';
									}
									// echo "<span class='counter'>".$count . ' Reviews </span>';
									?>
								</div>
							</div>
						</div> <!-- END COURSE #2 -->
					<?php } ?>
				</div> <!-- END COURSES HOLDER -->
			</div> <!-- End container -->
		</section> <!-- END COURSES-3 -->
	</div> <!-- END INNER PAGE WRAPPER -->
</div> <!-- END PAGE CONTENT -->
<div id="loginModal" style="display: none;"></div>

<script>
	$(document).on("keydown", function (event) {
		if (event.key === "F5" || (event.ctrlKey && event.key === "r")) {
			event.preventDefault();
			console.log("Page refresh prevented.");
		}
	});

	$(".postReviews").click(function () {

		$(".loader_loader").show();
		$(this).prop("disabled", true);
		var reviews = $('#reviews').val();
		if (reviews) {
			$.ajax({
				type: 'POST',
				url: "<?php echo base_url() ?>home/save_review",
				data: $('#list_reviews').serialize(), // this doesn't work
				// dataType : "json",
			})
				.done(function (response) {
					// Handle success response
					$('#listComments').html(response); // assuming response is HTML content
					$("#list_reviews").css("display", "none");
					$("#listComments").css("display", "block");

					setTimeout(function () {
						$(".msg-feedback").css("display", "none");

						// $(".loader_loader").hide();
						var baseURL = window.location.origin + window.location.pathname;
						var targetURL = baseURL; //+ '#Rate_this_course';
						window.location.href = targetURL;
					}, 1000);




				}).fail(function (response) {
					$(".loader_loader").hide();
					$(this).prop("disabled", false);
					console.error("Unknown Error!"); // not sure yet how I'd get here... ?
				});
		}
	});



	function submitEnrollCourse(idcourse = 0) {
		<?php
		if (!empty($login['idUser']) && $userenrollement == 0) { ?>

			$.ajax({
				type: 'POST',
				url: "<?php echo base_url(); ?>home/enrollcoursedata",
				data: {
					"idcourse": idcourse
				}, // this doesn't work
			}).done(function (response) {
				if (response) {

					$('#msg').val('Data Updated');
					// location.reload();
				} else { // Successful post, but not response came back !?
					$('#msg').val('Error');
					console.error("Post sent, but no response came back!?");
				}
			}).fail(function (response) {
				console.error("Unknown Error!"); // not sure yet how I'd get here... ?
			});
		<?php } else { ?> alert('In order to enroll in the course, logging in is a required first step');
		<?php } ?>

	}
</script>
<script>
	// Get the current URL
	var url = window.location.href;
	// Split the URL by the hash symbol (#)
	var parts = url.split('#');
	// Get the part after the hash symbol
	var hashPart = parts[1]; // parts[0] will contain the part before the hash
	// Output the hash part
	if (hashPart == "examModal") document.getElementById("examClick").click();
	// startcourse
	setTimeout(function () {
		if (hashPart == "courseModal") $(".startcourse").click();
	}, 500);
	// console.log(hashPart); // Output: examModal
	// alert("test");



	// rating form hide/show
	// $( "#rateProduct" ).click(function() {
	// 		$("#ratingSection").show();
	// 		alert('ssss');
	// });

	// implement start rating select/deselect
	$(".rateClick").click(function () {

		if ($(this).hasClass('star-grey')) {
			$(this).removeClass('star-grey').addClass('star-highlight star-selected');
			$(this).prevAll('.rateClick').removeClass('star-grey').addClass('star-highlight star-selected');
			$(this).nextAll('.rateClick').removeClass('star-highlight star-selected').addClass('star-grey');
		} else {
			//alert($('.star-selected').length)
			$(this).nextAll('.rateClick').removeClass('star-highlight star-selected').addClass('star-grey');
		}
		$("#ratingClick").val($('.star-selected').length); //count the num of star selected

		$.ajax({
			type: 'POST',
			url: "<?php echo base_url() ?>home/save_rating",
			data: $('#list_rating').serialize(), // this doesn't work
			dataType: "json",
		})
			.done(function (response) {
				// this doesn't work
				// $('#list_reviews').html('<div class="msg_reviews">'+response.data+'</div>');	 

				var baseURL = window.location.origin + window.location.pathname;
				var targetURL = baseURL; // + '#Rate_this_course';
				window.location.href = targetURL;

			}).fail(function (response) {
				console.error("Unknown Error!"); // not sure yet how I'd get here... ?
			});
	});



	$("#id_course").change(function () { });

	$(document).ready(function () {
		// Attach change event listener to all radio buttons within the content div
		$('.content input[type="radio"]').change(function () {
			// Uncheck all other radio buttons within the same question group
			$(this).closest('.content').find('input[type="radio"]').not(this).prop('checked', false);
		});

		// Add click event listener to the Save button
		// $('.save-exam').click(function() {
		// 	// Perform your save operation here
		// 	// For example:
		// 	var selectedAnswers = $('.content input[type="radio"]:checked').map(function() {
		// 		return $(this).val();
		// 	}).get();
		// 	console.log(selectedAnswers); // Output selected answers
		// });
	});

	function editFeedback() {
		$("#listComments").attr("style", "display: none !important");
		$("#list_reviews").attr("style", "display: block !important");
	}
</script>
<style>
	.msg_reviews {
		color: red;
		text-align: center;
		padding: 10px;
		margin: 0 auto;
	}

	.boolean-label {
		display: inline-block;
		padding: 5px 10px 5px 17px;
		border-radius: 5px;
		font-weight: bold;
		text-transform: uppercase;
		position: relative;
	}

	/* Circle style */
	.boolean-label::before {
		content: '';
		display: inline-block;
		width: 10px;
		height: 10px;
		border-radius: 50%;
		background-color: #00ADB1 !important;
		position: absolute;
		top: 49%;
		left: 0;
		transform: translateY(-50%);
	}

	.msg-feedback {
		text-align: center;
		color: red;
		font-weight: bold;
	}

	@media screen and (min-width: 769px) {
		.preview-right {
			padding: 0;

		}
	}

	#preview {
		width: 100px;
		height: 100px;
		text-align: center;
	}

	.preview {
		border-radius: 50%;
		width: 100px;
		height: 100px;
		object-fit: cover;
	}

	.comment-row {
		width: 100%;
		border-bottom: #e0dfdf 1px solid;
		margin-bottom: 5px;
		padding: 15px;
		/* margin-right: 15px; */
		background: #E5E5EA;
		border-radius: 2px 2px 2px 2px;
		position: relative;
		border-radius: 5px;
	}

	span.posted-by {
		color: #000;
		font-weight: bold;
		font-size: 16px !important;
		text-transform: uppercase;
	}

	.comment-text {
		font-size: 14px;
		margin: 0px;
	}

	.posted-at {
		float: right;
		text-align: right;
		direction: ltr;
	}

	.wpb_wrapper span {
		font-size: 11px !important;
	}

	.commentedit {
		position: absolute;
		bottom: 10px;
		font-size: 14px;
		color: red;
		right: 15px;
		cursor: pointer;
	}

	.commentdelete {
		position: absolute;
		bottom: 10px;
		font-size: 14px;
		color: red;
		right: 40px;
		cursor: pointer;
	}

	textarea {
		width: 100%;
		min-height: 157px;
		border: 0;
	}

	textarea:hover,
	textarea:active {
		outline: 0;
	}

	.postReviews {
		float: right;
		text-decoration: none;
	}

	.block-feedback {
		min-height: 200px;
		border: 1px solid #00ADB1;
		border-radius: 10px 10px 0 0;
		color: #fff;
		margin-top: 10px;
		justify-content: space-between;
	}

	.block-feedback h5 {
		border-radius: 10px 10px 0 0;
		background-color: #00ADB1;
		color: #fff;
		padding: 10px 20px
	}
</style>
<style>
	.desgin-button {
		background: #00ADB1;
		border-radius: 5px;
		color: #fff;
		font-size: 1.1em;
		border: none;
		margin-left: 20px;
		width: 200px;
		min-width: 100px;
		outline: none;
		font-weight: 600;
		padding: 0.6em 1em;
		transition: 0.5s all;
		-webkit-transition: 0.5s all;
	}


	.loader_button {
		position: relative !important;
		width: 200px !important;
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
		top: 13%;
		left: 50%;
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