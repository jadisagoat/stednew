
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
								    	<li class="breadcrumb-item"><a href="<?php echo base_url();?>edit-profile">Home</a></li>
								    	<!-- <li class="breadcrumb-item"><a href="teachers-list.html">All Teachers</a></li> -->
								    	<li class="breadcrumb-item active" aria-current="page"><?php echo $user['name'];?></li>
								  	</ol>
								</nav>
							</div> 

						</div>	 <!-- End row -->
					</div>	<!-- End container -->		
				</div>	<!-- END BREADCRUMB -->	

				<section id="team-3" class="pt-0 pb-100 team-section division">
					<div class="container">
    <div class="row mt-0 mt-md-4">
	<div class="col-lg-3 col-md-4 col-12 tutor-dashboard-left-menu">
 		<!-- Side
		============================================= -->
		<?php $data['login']=$login;
		      $data['login']['page']='wishlist';
			  $this->load->view('include/side_profile',$data);
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
		<h3 class="mb-0">Wishlist Courses</h3>
	</div>

	<div class="card-body">

		
		<!-- <div id="tutor_profile_cover_photo_editor" class="pop-up-opened5 d-lg-flex align-items-center justify-content-between">
			<div class="d-md-flex align-items-center mb-4 mb-lg-0">
				<div id="tutor_profile_area" class="avatar-xl rounded-circle bg-cover" 
				  data-fallback="https://augmintech.com/wp-content/plugins/tutor/assets/images/profile-photo.png" 
				  style="background-image:url(https://augmintech.com/wp-content/plugins/tutor/assets/images/profile-photo.png)"
				>
				</div>

				<div class="mt-3 mt-md-0 ms-md-3">
					<h4 class="mb-0">Your avatar</h4>
					<p class="mb-0">
						PNG or JPG no bigger than 800px wide and tall.					</p>	
				</div>
			</div>

			<div id="tutor_pp_option">
				
				<span class="tutor_pp_uploader btn btn-outline-white btn-sm">
					Update
				</span>

				<span class="tutor_pp_deleter btn btn-outline-danger btn-sm">
					Delete
				</span>

				<div></div>
			</div>
		</div> -->

<!-- COURSES HOLDER -->
<div class="row courses-grid">

<!-- COURSE #1 -->
<?php foreach($wishcourses as $row){    ?> 
    <div class="col-lg-6">
            <div class="cbox-1">
                <a href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>">
                <?php   if(is_file("uploads/course/".$row['image'])) { ?> 
                    <!-- Image -->
                    <img class="img-fluid" src="<?php echo base_url(); ?>uploads/course/<?php echo $row['image']; ?>" alt="course-preview" />
                   <?php }?>
                    <!-- Text -->
                    <div class="cbox-4-txt">	

                        <!-- Course Tags -->
                        <p class="course-tags">
                            <span><?php echo $row['subtitle']; ?></span>
                            <!-- <span>English</span> -->
                        </p>

                        <!-- Course Title -->
                         
                        <!-- //  $out = strlen($in) > 50 ? substr($in,0,50)."..." : $in;  -->
                        <h5 class="h5-xs"><?php echo $row['coursetitle']; ?></h5>

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
                        <!-- <span class="course-price"><span class="old-price">$<?php echo $row['price']; ?></span> $<?php echo $row['price']; ?></span> -->
                </a>
                    </div>

        
            </div>
        </div>	<!-- END COURSE #1 -->	

        <?php }?>

    </div>	  <!-- END COURSES HOLDER -->
		 
 

		<form  method="post" id="savedata" enctype="multipart/form-data">
			 
		</form>
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
<style>
	.cbox-1 .img-fluid {
		height: 170px;
		width: 100%;
	}
</style>