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
                            <?php foreach($subcategoryinfo as $row) {?>
							<li class="breadcrumb-item active" aria-current="page"><?php echo $row['cattitle']; ?><?php // Categorytitle ?> </li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $row['subcattitle']; ?><?php // SubCategorytitle ?></li>
                            <?php } ?>
                        </ol>
                    </nav>
                </div> 

            </div>	 <!-- End row -->
        </div>	<!-- End container -->		
    </div>	<!-- END BREADCRUMB -->	

	<!-- COURSES-3
				============================================= -->
				<section id="courses-3" class="pt-60 pb-25 courses-section division">
					<div class="container">

						<!-- SECTION TITLE -->	
						<div class="row">	
							<div class="col-md-12">
								<div class="section-title mb-40">

									<!-- Title 	-->	
									<h4 class="h4-xl">All Courses</h4>	

									<!-- Text -->	
									<!-- <p class="p-md">Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero 
									   tempus, blandit posuere and ligula varius magna a porta
									</p> -->

								</div>	
							</div>
						</div>


						<!-- COURSES HOLDER -->
						<div class="row courses-grid">

							<?php foreach($course as $row) { ?>
							<!-- COURSE #2 -->
							<div class="col-md-6 col-lg-4 col-xl-3">
								<div class="cbox-1">
<?php if( isset($login['idUser']) && !empty($login['idUser'])) { ?>
<span class="clikfavorite button_favorite favorite_<?php echo $row['id_course'];?>" data-active="<?php echo $favorit[$row['id_course']]; ?>" data-id-course="<?php echo $row['id_course']; ?>">
    <i title="favorite" class="fa fa-bookmark <?php if($favorit[$row['id_course']]){ ?> fa-yellow <?php } ?>" class="btn-text"></i>
</span>
<?php } ?>
								<a href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>">
										<!-- Image -->
									<img class="img-fluid" src="<?php echo course_image_url($row['image']); ?>" alt="co   w" />
										<!-- Text -->
										<div class="cbox-1-txt">

											<!-- Course Tags -->
											<p class="course-tags">
												<span><?php echo $row['title']; ?></span>
											</p>	

											<!-- Title -->
											<h5 class="h5-xs"><?php echo $row['summary']; ?></h5>

											<!-- Course Rating -->
											<!-- <div class="course-rating clearfix">
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<span>5 (118 Ratings)</span>
											</div> -->

											<!-- Course Price -->
											<!-- <span class="course-price"><span class="old-price">$<?php echo $row['old_price']; ?></span> $<?php echo $row['price']; ?></span> -->

										</div>

									</a>

<div class="form-group rating-class DOMContentLoaded" >
<?php
$average = $row['avg_course_rating'];
//$averageRating = round($average, 0);
for ($i = 1; $i <= 5; $i++) {
$ratingClass = "star-grey";
if($i <= $average) {
$ratingClass = "star-highlight";
}

echo '<i class="fa fa-star fa-lg rateButton '.$ratingClass. '"  aria-hidden="true"></i>';
}
// echo "<span class='counter'>".$count . ' Reviews </span>';
?>

<!-- <input type="hidden" class="form-control"   value="4">
<input type="hidden" class="form-control"   value="2">
<input type="hidden" name="action" value="saveRating"> -->
</div>
								</div>
							</div>	<!-- END COURSE #2 -->							
						   <?php } ?>

						</div>	  <!-- END COURSES HOLDER -->


					</div>     <!-- End container -->
				</section>	<!-- END COURSES-3 -->
</div>	<!-- END INNER PAGE WRAPPER -->


<style>
/* for star css*/
.rating-class {
	padding: 0px 23px;
    width: 100%;
    position: absolute;
    bottom: 2px;
}

.counter{
	margin-left:10px;
   font-size:11px;
   margin:0 1px;
}

.rateButton {
    font-size: 13px;
    margin: 0 10px 0 0;
	color:#f0f1f2;
}

.star-grey{
  color:#ccc;
}


.star-highlight{
  color:#ffcc00 !important;
}
</style>