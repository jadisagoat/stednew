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
								    	<li class="breadcrumb-item"><a href="demo-1.html">Home</a></li>
								    	<li class="breadcrumb-item"><a href="teachers-list.html">All Teachers</a></li>
								    	<li class="breadcrumb-item active" aria-current="page"><?php echo $lecturerinfo['name']; ?></li>
								  	</ol>
								</nav>
							</div> 

						</div>	 <!-- End row -->
					</div>	<!-- End container -->		
				</div>	<!-- END BREADCRUMB -->	




				<!-- TEAM-3
				============================================= -->
				<section id="team-3" class="pt-100 team-section division">
					<div class="container">
						<div class="row">	


							<!-- TEACHER PHOTO -->
							<div class="col-md-4">
								<div class="team-3-photo text-center">

									<!-- Teacher Photo -->
                                    
									<div class="t-3-photo mb-25">
                                    <?php   if(is_file("uploads/user/".$lecturerinfo['image'])) { ?> 
										<img class="img-fluid" src="<?php echo base_url(); ?>uploads/user/<?php echo $lecturerinfo['image']; ?>" alt="team-member-foto">
                                        <?php }?>
									</div>

									<!-- Social Icons -->
									<div class="tm-3-social clearfix">
										<ul class="text-center clearfix">																			
											<li><a href="#" class="ico-facebook"><i class="fab fa-facebook-f"></i></a></li>
											<li><a href="#" class="ico-twitter"><i class="fab fa-twitter"></i></a></li>
											<li><a href="#" class="ico-google-plus"><i class="fab fa-google-plus-g"></i></a></li>	
											<li><a href="#" class="ico-linkedin"><i class="fab fa-linkedin-in"></i></a></li>
										</ul>
									</div>

									<!-- Links -->
									<div class="t-3-links">
										<a href="https://stedltd.com/" class="btn btn-md btn-tra-grey rose-hover">Website</a>
										<a href="mailto:<?php echo $lecturerinfo['email']; ?>" class="btn btn-md btn-tra-grey rose-hover"><?php echo $lecturerinfo['email']; ?></a>
									</div>

								</div>
							</div>	<!-- END TEACHER PHOTO -->


							<!-- TEACHER DATA -->
							<div class="col-md-8">
								<div class="team-3-txt pr-45 pb-45">

									<!-- Name -->
									<h3 class="h3-xs"><?php echo $lecturerinfo['name']; ?></h3>
									<span><?php echo $lecturerinfo['title']; ?></span>

									<!-- Data -->
									<p class="teacher-data">14,622 Total Students &bull; 9 Courses &bull; 4.63 Rating (738 Reviews)</p>

									<!-- Small Title -->
									<h5 class="h5-xl mt-40">About Me</h5>
                                            <p><?php echo $lecturerinfo['aboutme']; ?></p>
									 

								</div>
							</div>	<!-- END TEACHER DATA -->


						</div>     <!-- End row -->
					</div>     <!-- End container -->
				</section>	<!-- END TEAM-3 -->




				<!-- COURSES-3
				============================================= -->
				<?php if(count($courses)){ ?>
				<section id="courses-3" class="pt-80 pb-60 courses-section division">
					<div class="container">


						<!-- SECTION TITLE -->	
						<div class="row">	
							<div class="col-md-12">
								<div class="section-title mb-40">
									<h5 class="h5-xl">My Courses (<?php echo count($courses); ?>)</h5>	
								</div>	
							</div>
						</div>


						<!-- COURSES HOLDER -->
						<div class="row courses-grid">

                        <?php foreach($courses as $row) {?>
							<!-- COURSE #1 -->
							<div id="c3-1" class="col-md-6 col-lg-4 col-xl-3">
								<div class="cbox-1">
                                <a href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>">
										
										<!-- Image -->
                                        <?php   if(is_file("uploads/course/".$row['image'])) { ?> 
										<img class="img-fluid" src="<?php echo base_url(); ?>uploads/course/<?php echo $row['image']; ?>" alt="course-preview" />
                                        <?php }?>
										<!-- Text -->
										<div class="cbox-1-txt">

											<!-- Course Tags -->
											<p class="course-tags">
												<span>Languages</span>
												<span>English</span>
											</p>	

											<!-- Course Title -->
											<h5 class="h5-xs"><?php echo $row['title']; ?></h5>

											<!-- Course Rating -->
											<!-- <div class="course-rating clearfix">
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star-half-alt"></i>
												<span>4.5 (26 Ratings)</span>
											</div> -->

											<!-- Course Price -->
											<!-- <span class="course-price"><span class="old-price">$149.99</span> $138.99</span> -->

										</div>

									</a>
								</div>
							</div>	<!-- END COURSE #1 -->	

                            <?php } ?>


							 							


						</div>	  <!-- END COURSES HOLDER -->
					</div>     <!-- End container -->
				</section>	<!-- END COURSES-3 -->
				 <?php } ?>

<style>
.team-3-txt {
	text-align: justify;
}
.team-3-txt h3 {
  margin-top:0;
}
</style>

<!-- <h2>Video Conference</h2>
    <div id="meet"></div>

    <script src="https://meet.jit.si/external_api.js"></script>
    <script>
        const domain = 'meet.jit.si';
        const options = {
            roomName: 'YourUniqueRoomName123',
            width: '100%',
            height: 700,
            parentNode: document.querySelector('#meet'),
            userInfo: {
                displayName: 'John Doe'
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    </script> -->