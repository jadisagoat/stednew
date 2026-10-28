<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
	<?php $this->load->view('include/_head'); ?>
	<link href="<?php echo base_url() ?>flipbook/css/style.css" rel="stylesheet" />
	<link href="<?php echo base_url() ?>lib/addeditcourse/addeditcourse.css" rel="stylesheet" />


	<script type="text/javascript" src="<?php echo base_url(); ?>ajax_image_upload/scripts/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>ajax_image_upload/scripts/jquery.form.js"></script>
	<script type="text/javascript">
		jQuery.noConflict();
		jQuery(document).ready(function($) {
			$('#photoimg').live('change', function() {
				$("#preview").html('');
				$("#preview").html('<img class="loader" src="<?php echo base_url(); ?>images/loader.gif" alt="Uploading...."  />');
				$("#imageform").ajaxForm({
					target: '#preview'
				}).submit();

			});

			$('#photoimg_signature').live('change', function() {
				$("#preview_signature").html('');
				$("#preview_signature").html('<img class="loader" src="<?php echo base_url(); ?>images/loader.gif" alt="Uploading...."  />');
				$("#imageform_signature").ajaxForm({
					target: '#preview_signature'
				}).submit();

			});

			////////////////////////////////////////////////////////////////
			$('#delete_user_exam').live('click', function() {
				// Data you want to send via POST
				var id_exam = $("#id_exam").val();
				var id_course = $("#id_course").val();

				var data = {
					id_exam: id_exam,
					id_course: id_course,
					// Add more key-value pairs as needed
				};

				// Create a form
				var form = $('<form>', {
					action: '<?php echo base_url(); ?>home/delete_user_exam', // Replace with your target URL
					method: 'get'
				});

				// Append the data as hidden form fields
				$.each(data, function(key, value) {
					$('<input>', {
						type: 'hidden',
						name: key,
						value: value
					}).appendTo(form);
				});

				// Append the form to the body and submit it
				form.appendTo('body').submit();
			});
		});
	</script>

	<script src="<?php echo base_url() ?>theme_academy/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/modernizr.custom.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/jquery.easing.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/jquery.appear.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/menu.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/materialize.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/jquery.scrollto.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/jquery.countdown.min.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/imagesloaded.pkgd.min.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/isotope.pkgd.min.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/jquery.flexslider.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/owl.carousel.min.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/jquery.magnific-popup.min.js"></script>
	<!-- <script src="<?php echo base_url() ?>theme_academy/js/register-form.js"></script> -->
	<!-- <script src="<?php echo base_url() ?>theme_academy/js/comment-form.js"></script> -->
	<script src="<?php echo base_url() ?>theme_academy/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url() ?>theme_academy/js/jquery.ajaxchimp.min.js"></script>
	<!-- Custom Script -->
	<script src="<?php echo base_url() ?>theme_academy/js/custom.js?v=2"></script>

	<?php
	// Performance: DevExpress grid + Excel/PDF export (~7.7 MB) only on the pages that use it
	$dx_pages = array('courselist', 'examhistorylist', 'examlist', 'feedbackhistorylist', 'formextreme', 'listemployeenotregistered', 'listuserscontent', 'totalexamshistorylist', 'userlist');
	if (isset($content) && in_array($content, $dx_pages)) { ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>lib/devexpress/dx.light.css" />
	<script src="<?php echo base_url(); ?>lib/devexpress/dx.all.js"></script>
	<script src="<?php echo base_url(); ?>lib/devexpress/exceljs.min.js"></script>
	<script src="<?php echo base_url(); ?>lib/devexpress/FileSaver.min.js"></script>


	<script src="<?php echo base_url(); ?>lib/devexpress/jspdf.umd.js"></script>
	<script src="<?php echo base_url(); ?>lib/devexpress/jspdf.plugin.autotable.js"></script>
	<?php } ?>


	<script src="<?php echo base_url(); ?>js/timer/sweetalert.js"></script>
	<script src="<?php echo base_url(); ?>js/timer/myjs.js"></script>


	<script>
		// 		function init() {
		//   cd();
		// }
		// window.onload = init;



		$(document).ready(function() {

			$('.ClickExamRateFeedback').on('click', function() {
				var baseURL = window.location.origin + window.location.pathname;
				var targetURL = baseURL + '#Rate_this_course';
				window.location.href = targetURL;
				$('.closevideo').trigger('click');
				alert('Before taking the exam, we kindly ask you to provide your feedback on the course along with your rating.Thank you for your input, and we wish you the best of luck!');
			});
			$('.ClickExamRateFeedback1').on('click', function() {
				var baseURL = window.location.origin + window.location.pathname;
				var targetURL = baseURL + '#Rate_this_course1';
				window.location.href = targetURL;
				$('.closevideo').trigger('click');
				alert('Before taking the exam, we kindly ask you to provide your feedback on the course along with your rating.Thank you for your input, and we wish you the best of luck!');
			});
			$('.ClickExamRateFeedback2').on('click', function() {
				var baseURL = window.location.origin + window.location.pathname;
				var targetURL = baseURL + '#Rate_this_course2';
				window.location.href = targetURL;
				$('.closevideo').trigger('click');
				alert('Before taking the exam, we kindly ask you to provide your feedback on the course along with your rating.Thank you for your input, and we wish you the best of luck!');
			});

			$('.ClickExam').on('click', function() {
				// alert("Testing ClickExam")
				cd();
			});

			// Trigger click event on elements with the class ".clikfavorite"
			$('.clikfavorite').click(function() {
				// Get the dynamic class name
				var dynamicClass = $(this).data('id-course');
				var active = $(this).data('active');

				if (active == 1) active = 0;
				else active = 1;

				$.ajax({
					type: 'POST',
					url: base_url + "home/favorite",
					data: {
						"idcourse": dynamicClass,
						"active": active
					}, // this doesn't work

				}).done(function(response) {
					if (response) {
						$('.clikfavorite').data('active', active);

						if (active == 1) {
							$('.favorite_' + dynamicClass + ' .fa-bookmark').addClass('fa-yellow');
						} else {
							$('.favorite_' + dynamicClass + ' .fa-bookmark').removeClass('fa-yellow');
						}

						// $('#msg').val('Data Updated');
					} else { // Successful post, but not response came back !?
						// $('#msg').val('Error');
						// console.error("Post sent, but no response came back!?");
					}
				}).fail(function(response) {
					console.error("Unknown Error!"); // not sure yet how I'd get here... ?
				});

				// Do something with the ID, for example, alert it
			});
		});
	</script>

</head>

<body class="mx-ui<?php echo (isset($content) && $content) ? " page-" . preg_replace("/[^a-z0-9_-]/i", "", $content) : ""; ?>">
	<!-- PRELOADER SPINNER
        ============================================= -->
	<div id="loader-wrapper">
		<div id="loading">
			<div id="loading-center">
				<div class="cssload-loading"><i></i><i></i><i></i><i></i></div>
				<div class="mx-loader" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
			</div>
		</div>
	</div>
	<!-- PAGE CONTENT
        ============================================= id="page" class="page"-->
	<div>
		<!-- HEADER
            ============================================= -->
		<? $data['menuacitve'] = $menuactive;
		$this->load->view('include/_header', $data);
		?>
		<!-- BODY  CONTENT
            ============================================= -->
		<?php $this->load->view($content); ?>
		<!-- FOOTER
            ============================================= -->
		<? $this->load->view('include/_footer');  ?>
	</div> <!-- END PAGE CONTENT -->
	<!-- EXTERNAL SCRIPTS
        ============================================= -->



	<style>
		.dx-treeview-select-all-item {
			height: 45px !important
		}

		.dx-toolbar-after,
		.dx-toolbar-before {
			display: inline-block !important;
			position: relative !important;
		}

		.dx-datagrid-search-panel {
			margin: 0 !important;
		}
	</style>

	<!-- Custom geeks -->
	<script>
		<?php if (isset($curentpage) && ($curentpage == "course-details")) { ?>
			// Disable right-click context menu
			document.oncontextmenu = function(e) {
				e.preventDefault(); // Prevent the context menu from appearing
				//alert('Right click is disabled!!!'); // Optional: Provide feedback to the user
				return false;
			};

			// Disable copy
			document.oncopy = function(e) {
				e.preventDefault(); // Prevent the copy action
				//alert('Copying is disabled!!!'); // Optional: Provide feedback to the user
				return false;
			};

			// Disable paste
			document.onpaste = function(e) {
				e.preventDefault(); // Prevent the paste action
				//alert('Pasting is disabled!!!'); // Optional: Provide feedback to the user
				return false;
			};
		<?php } ?>
		/////////////////////////////
		var base_url = '<?php echo base_url(); ?>';

		function insertcourse() {
			$('.dataresponse').html("");
			var title = $('#title').val();
			var start_date = $('#start_date').val();
			var close_date = $('#close_date').val();

			var time = $('#time').val();
			var description = $('#description').val();
			var id_course = $('#id_course').val();
			var id_exam = $('#id_exam').val();

			var number_of_questions = $('#number_of_questions').val();
			var number_of_attempts = $('#number_of_attempts').val();

			var emailfff = document.getElementById('emailemail');
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;


			$("#title").css({
				'border': '1px solid #bebdbd',
				'color': '#132c57'
			});
			$("#start_date").css({
				'border': '1px solid #bebdbd',
				'color': '#132c57'
			});
			$("#close_date").css({
				'border': '1px solid #bebdbd',
				'color': '#132c57'
			});
			$(".datetimepicker").css({
				'border': '1px solid #bebdbd',
				'color': '#132c57'
			});
			$("#time").css({
				'border': '1px solid #bebdbd',
				'color': '#132c57'
			});
			$("#id_course").css({
				'border': '1px solid #bebdbd',
				'color': '#132c57'
			});
			$("#number_of_questions").css({
				'border': '1px solid #bebdbd',
				'color': '#132c57'
			});
			$("#number_of_attempts").css({
				'border': '1px solid #bebdbd',
				'color': '#132c57'
			});


			var boolean = 0;
			if (title == '') {
				$("#title").css({
					'border': '1px solid #ff0000',
					'color': '#ff0000'
				});
				boolean = 1;
			} else
			if (start_date == '') {
				$("#start_date").css({
					'border': '1px solid #ff0000',
					'color': '#ff0000'
				});
				boolean = 1;
			} else
			if (close_date == '') {
				$("#close_date").css({
					'border': '1px solid #ff0000',
					'color': '#ff0000'
				});
				boolean = 1;
			} else
			if (start_date != '' && close_date != '') {
				// Create Date objects
				var date1 = new Date(start_date);
				var date2 = new Date(close_date);
				// Compare dates
				if (date1 > date2) {
					$(".datetimepicker").css({
						'border': '1px solid #ff0000',
						'color': '#ff0000'
					});
					$('.dataresponse').html("Start Date is after Close Date");
					boolean = 1;
				} else
				if (date1 < date2) {
					console.log("Start Date is before Close Date");
				} else {
					$(".datetimepicker").css({
						'border': '1px solid #ff0000',
						'color': '#ff0000'
					});
					$('.dataresponse').html("Start Date is equal to Close Date");
					boolean = 1;
				}

			} else
			if (time == '') {
				$("#time").css({
					'border': '1px solid #ff0000',
					'color': '#ff0000'
				});
				boolean = 1;
			} else
			if (number_of_questions == '') {
				$("#number_of_questions").css({
					'border': '1px solid #ff0000',
					'color': '#ff0000'
				});
				boolean = 1;
			} else
			if (number_of_attempts == '') {
				$("#number_of_attempts").css({
					'border': '1px solid #ff0000',
					'color': '#ff0000'
				});
				boolean = 1;
			} else
			if (id_course == '') {
				$("#id_course").css({
					'border': '1px solid #ff0000',
					'color': '#ff0000'
				});
				boolean = 1;
			}


			if (boolean == 0) { // alert(boolean)
				$.ajax({
						type: 'POST',
						url: base_url + "addexam",
						data: {
							'number_of_attempts': number_of_attempts,
							'number_of_attempts': number_of_attempts,
							'number_of_questions': number_of_questions,
							'time': time,
							'title': title,
							'start_date': start_date,
							'close_date': close_date,
							'id_course': id_course,
							'id_exam': id_exam,
							'description': description
						},
						dataType: "json",
					})
					.done(function(response) {
						if (response) {
							// success
							var id_exam = '';
							// var parts = response.split('_insert_');
							// console.log(parts[1]);
							if (response > 1) {
								id_exam = response;
								$("#id_exam").val(id_exam);
							}
							if (response == 1)
								$('.dataresponse').html("Information was updated successfully");
							else if (response > 1) {
								$('.dataresponse').html("Information was inserted successfully");
							}
						} else { // error
							$('.dataresponse').html("Error in data was insert!");
						}


					}).fail(function(response) {
						console.error("Unknown Error!"); // not sure yet how I'd get here... ?
					});
			}
		}
	</script>
	<!-- Modern UI layer (animations, hero slider, reveal on scroll) -->
	<script src="<?php echo base_url() ?>theme_academy/js/modern.js?v=9"></script>
</body>

</html>