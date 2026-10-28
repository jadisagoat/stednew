
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
			  //$data['login']['page']='enrolled_courses';
			  $data['login']['page']='enrolled_courses';
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
		<h3 class="mb-0">Enrolled Courses</h3>
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
<?php 
foreach($enrolledcourses as $row){    ?> 
    <div class="col-lg-6">
            <div class="cbox-1">
                <a class="img-course" href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>">
                <?php   if(is_file("uploads/course/".$row['image'])) { ?> 
                    <!-- Image -->
                    <img class="img-fluid" src="<?php echo base_url(); ?>uploads/course/<?php echo $row['image']; ?>" alt="course-preview" />
                   <?php }?>
				</a>
				<a  href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>">

                    <!-- Text -->
                    <div class="cbox-4-txt">
	
<div class="gridcenter">
	<div  class="mybox radial rate-point plans calibri bold font16">
		<div class="theme-5">
			<?php 
			$avgcourse1=0;
			if(isset( $avgcourse[$row['id_course']]) && $row['id_course']){
				$avgcourse1=$avgcourse[$row['id_course']];
			 }
				 ?>
			<svg id="animated" class='' data-toggle="class" viewbox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
			<circle class='no-fill' cx="50" cy="50" r="45"/>
			<item data-func="prodata" data-run="auto" data-self="4000"
				data-mark="100" data-start="0" data-end="<?php echo $avgcourse1;?>" 
				data-time="5000" data-unit='%' data-angle="60"
				data-bkpoints="0 20 50 70 100"
				data-bkclass ="low normal medium high complete"
				data-bkcolors="rgba(102,45,45) rgba(102,45,45,0.5) rgba(102,45,45,0.5) rgb(207,204,4) rgba(102,45,45,0.5)">
			</item>
			<path   class="no-fill bar9 cap-round" data-func="progressbar" stroke-linecap="round" stroke-dasharray="0,251.36264038085938" stroke-width="1" d="M50 10 a 30 30 0 0 1 0 80 a 30 30 0 0 1 0 -80">
			</path>
			<foreignObject data-func="protext" x="20" y="20" height="60%" width="60%" text-anchor="middle" dy="7" class="text-center"></foreignObject>
			</svg>
		</div>
	</div>
</div>






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
h5.h5-xs {
    font-size: 1rem !important;
}
.card-body a.img-course {
	  display: block;
       width: 100%;
       background: #eee;
	   text-align: center;
	}

 .card-body  .img-fluid{
	 width: auto !important;
	 height: 170px;
	}
	.cbox-1 .img-fluid {
		height: 170px;
		width: 100%;
	}
</style>
 

<!-- <link href="<?php echo base_url();?>lib/radial-bar-rate-point/css/jquerysctipttop.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="<?php echo base_url();?>lib/radial-bar-rate-point/css/design.css">
    <link rel="stylesheet" href="<?php echo base_url();?>lib/radial-bar-rate-point/css/colors.css">
    <link rel="stylesheet" href="<?php echo base_url();?>lib/radial-bar-rate-point/css/radial-themes.css">
 
    <style>
        .rate-point {
            width: max(20px, 3.5vw);
            margin: .5em 0;
            color: white;
        }
        
        /* .samples {
            grid-template-columns: repeat(3, 1fr);
        }
        
        @media screen and (max-width:1000px) {
            .samples {
                grid-template-columns: repeat(2, 1fr);
            }
        } */

		.radial svg #xhtml .xid {
    position: relative;
    top: -4px;
    line-height: 2;
	font-size: 24px;
}

		.gridcenter{ 
			place-items: center;
			position: absolute;
			right: 27px;
			bottom: 48px;
		}
		.radial .theme-5 svg #xhtml,
		.radial .theme-6 svg #xhtml{
			color:white
		}

 @media screen and (max-width:1000px) {
		.rate-point {
			width: max(46px, 3.5vw);
			margin: 0;
			color: white;
		}
		.gridcenter {
			place-items: center;
			position: absolute;
			right: 27px;
			/* top: 0; */
			bottom: 94px;
		}
} 

    </style>

<script src="<?php echo base_url();?>lib/radial-bar-rate-point/js/ratePoint.js"></script>
<script>
	autoRatePoint(); //initialize ratepointer
</script>