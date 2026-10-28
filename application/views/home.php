<?php
/* ==========================================================================
   MODERN HERO (replaces the old materialize slider - backup: home____27_10_2026.php)
   ========================================================================== */
if (!function_exists('mx_clean')) {
	function mx_clean($s, $limit = 0)
	{
		$s = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string)$s), ENT_QUOTES, 'UTF-8')));
		if ($limit && mb_strlen($s) > $limit) {
			$s = rtrim(mb_substr($s, 0, $limit));
			$s = preg_replace('/\s+\S*$/u', '', $s) . '…';
		}
		return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
	}
	function mx_title($s)
	{
		$s = trim(html_entity_decode(strip_tags((string)$s), ENT_QUOTES, 'UTF-8'));
		// Banner titles are stored in CAPS - turn them into readable Title Case
		if ($s !== '' && $s === mb_strtoupper($s)) $s = mb_convert_case(mb_strtolower($s), MB_CASE_TITLE, 'UTF-8');
		return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
	}
}
$mx_slides = array();
foreach ($banners as $row) {
	$img = '';
	if (is_file("uploads/banner/" . $row['image'])) {
		$img = base_url() . 'uploads/banner/' . (is_file("uploads/banner/1520x600/" . $row['image']) ? "1520x600/" : "") . $row['image'];
	}
	$mx_slides[] = array('img' => $img, 'title' => $row['title'], 'text' => $row['description'], 'url' => isset($row['url']) ? $row['url'] : '');
}
$mx_total_courses = 0;
foreach ($subcategory as $row) $mx_total_courses += (int)$row['coursenbr'];
?>
<section id="hero-modern" class="mx-hero" data-interval="7000">

	<!-- Background slides -->
	<div class="mx-hero-slides" aria-hidden="true">
		<?php foreach ($mx_slides as $i => $s) { ?>
			<div class="mx-slide<?php echo $i == 0 ? ' is-active' : ''; ?>">
				<?php if ($s['img']) { ?><div class="mx-slide-bg" style="background-image:url('<?php echo $s['img']; ?>')"></div><?php } ?>
			</div>
		<?php } ?>
	</div>
	<div class="mx-hero-overlay"></div>
	<div class="mx-hero-dots"></div>
	<span class="mx-blob mx-blob-1"></span>
	<span class="mx-blob mx-blob-2"></span>
	<span class="mx-blob mx-blob-3"></span>

	<div class="container mx-hero-inner">
		<div class="row align-items-center">

			<!-- Captions -->
			<div class="col-lg-7">
				<div class="mx-hero-captions">
					<?php foreach ($mx_slides as $i => $s) { ?>
						<div class="mx-caption<?php echo $i == 0 ? ' is-active' : ''; ?>">
							<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span><?php echo mx_clean($generalinfo['website_title']); ?></span>
							<h2 class="mx-hero-title" data-split><?php echo mx_title($s['title']); ?></h2>
							<p class="mx-hero-text"><?php echo mx_clean($s['text'], 210); ?></p>
							<div class="mx-hero-actions">
								<?php if ($s['url']) { ?>
									<a class="mx-btn mx-btn-primary" href="<?php echo htmlspecialchars($s['url'], ENT_QUOTES, 'UTF-8'); ?>">View More <i class="fas fa-arrow-right"></i></a>
								<?php } ?>
								<a class="mx-btn mx-btn-glass" href="#courses-1">Explore Courses <i class="fas fa-play"></i></a>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>

			<!-- Floating category cards (real data) -->
			<div class="col-lg-5 d-none d-lg-block">
				<div class="mx-hero-visual">
					<div class="mx-orbit"><span></span><span></span><span></span></div>
					<?php $k = 0;
					foreach ($subcategory as $row) {
						if ($k >= 3) break; ?>
						<a class="mx-float-card mx-float-<?php echo $k + 1; ?>" href="<?php echo base_url(); ?>category/<?php echo $row['id_category']; ?>">
							<span class="mx-float-img">
								<?php if (is_file("uploads/category/" . $row['image'])) { ?>
									<img src="<?php echo optimized_image_url('category', $row['image'], 400); ?>" alt="<?php echo mx_clean($row['title']); ?>">
								<?php } else { ?><i class="fas fa-graduation-cap"></i><?php } ?>
							</span>
							<span class="mx-float-txt">
								<strong><?php echo mx_clean($row['title'], 34); ?></strong>
								<small><?php $cn = (int)$row['coursenbr']; echo $cn . ($cn == 1 ? ' Course' : ' Courses'); ?></small>
							</span>
						</a>
					<?php $k++;
					} ?>
					<div class="mx-float-stat">
						<span class="mx-float-stat-num" data-count="<?php echo $mx_total_courses; ?>"><?php echo $mx_total_courses; ?></span>
						<span class="mx-float-stat-lbl">Courses in<br><?php echo count($subcategory); ?> categories</span>
					</div>
				</div>
			</div>

		</div>
	</div>

	<!-- Controls -->
	<?php if (count($mx_slides) > 1) { ?>
		<div class="mx-hero-controls container">
			<div class="mx-hero-nav">
				<button type="button" class="mx-hero-prev" aria-label="Previous slide"><i class="fas fa-arrow-left"></i></button>
				<button type="button" class="mx-hero-next" aria-label="Next slide"><i class="fas fa-arrow-right"></i></button>
			</div>
			<div class="mx-hero-indicators">
				<?php foreach ($mx_slides as $i => $s) { ?>
					<button type="button" class="mx-ind<?php echo $i == 0 ? ' is-active' : ''; ?>" data-go="<?php echo $i; ?>" aria-label="Slide <?php echo $i + 1; ?>">
						<span class="mx-ind-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
						<span class="mx-ind-bar"><i></i></span>
					</button>
				<?php } ?>
			</div>
		</div>
	<?php } ?>

	<a class="mx-scroll-cue" href="#about-1" aria-label="Scroll down"><span></span></a>
</section>


<!-- FEATURE STRIP (was ABOUT-1)
============================================= -->
<section id="about-1" class="mx-features division">
	<div class="container">
		<div class="row">
			<?php $fi = 0;
			$mx_feature_icons = array('flaticon-004-computer', 'flaticon-028-learning-1', 'flaticon-006-diploma');
			foreach ($subheadericons as $row) { ?>
				<div class="col-md-4">
					<div class="mx-feature" data-reveal style="--d:<?php echo $fi * 120; ?>ms">
						<span class="mx-feature-ico"><span class="<?php echo $mx_feature_icons[$fi % 3]; ?>"></span></span>
						<div>
							<h5><?php echo $row['title'] ?></h5>
							<p><?php echo $row['description'] ?></p>
						</div>
					</div>
				</div>
			<?php $fi++;
			} ?>
		</div>
	</div>
</section>


<!-- CATEGORY MARQUEE
============================================= -->
<?php if (!empty($subcategory)) {
	// two rows of category chips gliding in opposite directions; each chip opens its category.
	// Every row is printed twice so the loop is seamless - the copy is hidden from screen readers and the keyboard.
	$mx_mq_rows = count($subcategory) > 7 ? array_chunk($subcategory, (int)ceil(count($subcategory) / 2)) : array($subcategory);
	$mx_mq_courses = 0;
	foreach ($subcategory as $row) $mx_mq_courses += (int)$row['coursenbr'];
	?>
	<section class="mx-mq-wrap">
	<div class="container">
	<div class="mx-mq-band" data-reveal>
		<span class="mx-mq-glow" aria-hidden="true"></span>
		<span class="mx-mq-dots" aria-hidden="true"></span>
		<div class="mx-mq-head">
			<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Explore</span>
			<h3>Our fields</h3>
			<p><?php echo count($subcategory); ?> categories &middot; <?php echo $mx_mq_courses; ?> courses</p>
			<a class="mx-btn mx-btn-glass" href="<?php echo base_url(); ?>category/<?php echo $subcategory[0]['id_category']; ?>">Browse all <i class="fas fa-arrow-right"></i></a>
		</div>
	<div class="mx-marquee" role="navigation" aria-label="Course categories">
		<?php foreach ($mx_mq_rows as $ri => $mq_row) { ?>
			<div class="mx-marquee-row<?php echo $ri % 2 ? ' is-reverse' : ''; ?>">
				<div class="mx-marquee-track">
					<?php for ($r = 0; $r < 2; $r++) { ?>
						<div class="mx-marquee-group"<?php echo $r ? ' aria-hidden="true"' : ''; ?>>
							<?php foreach ($mq_row as $row) {
								$n = (int)$row['coursenbr']; ?>
								<a class="mx-marquee-item" href="<?php echo base_url(); ?>category/<?php echo $row['id_category']; ?>"<?php echo $r ? ' tabindex="-1"' : ''; ?>>
									<span class="mx-marquee-ico">
										<?php if (!empty($row['image']) && is_file("uploads/category/" . $row['image'])) { ?>
											<img src="<?php echo optimized_image_url('category', $row['image'], 120); ?>" alt="" loading="lazy">
										<?php } else { ?>
											<i class="fas fa-layer-group"></i>
										<?php } ?>
									</span>
									<span class="mx-marquee-txt"><b><?php echo mx_clean($row['title']); ?></b><small><?php echo $n; ?> <?php echo $n == 1 ? 'course' : 'courses'; ?></small></span>
									<i class="fas fa-arrow-right mx-marquee-go" aria-hidden="true"></i>
								</a>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
	</div>
	</div>
	</section>
<?php } ?>




			<!-- ABOUT-2
			============================================= -->
			<section id="about-2" class="pt-50 pb-50 about-section division">
				<div class="container">
					<div class="row d-flex align-items-center">
					<?php
						if(isset($home_page['1']) && $home_page['1'])
							$subheadbanner=$home_page['1'];
						else $subheadbanner='';
					?>

						<!-- ABOUT IMAGE -->
						<div class="col-md-5 col-lg-6">
							<div class="img-block pc-25">
							<?php   if(is_file("uploads/home_page/".$subheadbanner['image'])) { ?> 
								<img class="img-fluid" loading="lazy" src="<?php echo optimized_image_url('home_page', $subheadbanner['image'], 900); ?>" alt="about-image">
								<?php } ?>
					 		</div>
						</div>


						<!-- ABOUT TEXT -->
					 	<div class="col-md-7 col-lg-6">
					 		<div class="txt-block pc-25 mb-40">

					 			<!-- Title -->	
								<h3 class="h3-sm"><?php    echo $subheadbanner['title'] ?></h3>

								<!-- Text -->
								<p><?php echo $subheadbanner['description'];  ?>
								</p> 

								<!-- List -->	
								<!-- <ul class="txt-list mb-15">

									<li>Nullam rutrum eget nunc varius etiam mollis risus undo</li>
													
									<li>Integer congue magna at pretium purus pretium ligula at rutrum risus luctus dolor auctor 
										ipsum blandit purus		
									</li>

									<li>Risus at congue etiam aliquam sapien egestas posuere</li>

									<li>At pretium purus integer congue magna pretium ligula at ipsum blandit purus	rutrum risus 
										luctus dolor auctor 	
									</li>

								</ul> -->

					 		</div>
					 	</div>	  <!-- END ABOUT TEXT -->


					</div>    <!-- End row --> 	
				</div>	   <!-- End container --> 	
			</section>	<!-- End ABOUT-2 --> 


<!-- COURSES-1
			============================================= -->
			<section id="courses-1" class="pt-50 pb-20 courses-section division">
				<div class="container">
					<?php
						if(isset($home_page['3'])&&$home_page['3'])
							$bestcourses=$home_page['3'];
						else $bestcourses='';
					?>

					<!-- SECTION TITLE -->	
					<div class="row">	
						<div class="col-md-12">
							<div class="section-title mb-60">

								<span class="mx-eyebrow"><span class="mx-pulse"></span>Top Courses</span>
								<h3 class="h3-sm"><?php echo $bestcourses['title']; ?></h3>	

								<!-- Text -->	
								<p class="p-md"><?php echo $bestcourses['description']; ?>
								</p>

								<!-- Button -->	
								<!-- <div class="title-btn">
									<a href="courses-list" class="btn btn-tra-grey rose-hover">View All Courses</a>
								</div> -->

							</div>	
						</div>
					</div>


					<!-- COURSE BOXES CAROUSEL -->
					<div class="row">
						<div class="col-lg-12">
							<div class="owl-carousel owl-theme owl-loaded courses-carousel">

                                  <?php foreach($topcourse as $row){  		
									?>
								<!-- COURSE #1 -->
								<div class="cbox-1">
									<?php if( isset($login['idUser']) && !empty($login['idUser'])) { ?>
<span class="clikfavorite button_favorite favorite_<?php echo $row['id_course'];?>" data-active="<?php echo $favorit[$row['id_course']]; ?>" data-id-course="<?php echo $row['id_course']; ?>">
<i title="favorite" class="fa fa-bookmark <?php if($favorit[$row['id_course']]){ ?> fa-yellow <?php } ?>" class="btn-text"></i>
</span><?php } ?>
									<a href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>">
									
										<!-- Image -->
										<?php   if(is_file("uploads/course/".$row['image'])) { ?> 
										<img class="img-fluid" loading="lazy" src="<?php echo optimized_image_url('course', $row['image'], 600); ?>" alt="course-preview" />	
											<?php }?>
										<!-- Text -->
										<div class="cbox-1-txt">

											<!-- Course Tags -->
											<p class="course-tags">
												<!-- <span>Languages</span>
												<span>English</span> -->
												<?php /* <span><?php echo $row['subtitle']; ?></span> */ ?>
											</p>	

											<!-- Course Title -->
											<h5 class="h5-xs"><?php echo $row['coursetitle']; ?></h5>

											<!-- Course Rating
											<div class="course-rating clearfix">
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star-half-alt"></i>
												<span>4.5 (26 Ratings)</span>
											</div> -->

											<!-- Course Price -->
											<!-- <span class="course-price"><span class="old-price">$<?php echo $row['old_price']; ?></span> $<?php echo $row['price']; ?></span> -->

										</div>

									</a>


<div class="form-group rating-class DOMContentLoaded">
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


								</div>	<!-- END COURSE #1 -->								
                                  <?php } ?>

								 						


							</div>
						</div>		
					</div>	  <!-- END COURSES BOXES CAROUSEL -->

  
				</div>     <!-- End container -->
			</section>	<!-- END COURSES-1 -->

			




			<!-- ABOUT-3
			============================================= -->
			<section id="about-3" class="pt-50 about-section division">
				<div class="container">
					<div class="row d-flex align-items-center">
					<?php
						if(isset($home_page['13'])&&$home_page['13'])
							$startlearn=$home_page['13'];
						else $startlearn='';
					?>	
						<!-- ABOUT TEXT -->
					 	<div class="col-md-7 col-lg-6">
					
					 		<div class="txt-block pc-25">
							
					 			<!-- Title -->	
								<h3 class="h3-sm"><?php echo $startlearn['title']; ?></h3>
								 
								<!-- Text -->
								<p class="h3-sm"><?php echo $startlearn['description']; ?>
								</p> 
								<!-- Button -->
								<!-- <a href="categories-list" class="btn btn-md btn-rose tra-black-hover">Start Learning Now</a> -->

					 		</div>
					 	</div>	  <!-- END ABOUT TEXT -->


					 	<!-- ABOUT IMAGE -->
						<div class="col-md-5 col-lg-6">
							<div class="img-block">
							<?php   if(is_file("uploads/home_page/".$startlearn['image'])) { ?> 
								<img class="img-fluid" loading="lazy" src="<?php echo optimized_image_url('home_page', $startlearn['image'], 900); ?>" alt="about-image">
								<?php } ?>
					 		</div>
						</div>


					</div>    <!-- End row --> 	
				</div>	   <!-- End container --> 	
			</section>	<!-- End ABOUT-3 --> 




			<!-- SERVICES-6
			============================================= -->
			<section id="services-6" class="pt-50 pb-50 bg-03 services-section division">
				<div class="container white-color">
<?php
	if(isset($home_page['2'])&&$home_page['2'])
		$qualityedu=$home_page['2'];
	else $qualityedu='';
?>
					<!-- SERVICES TEXT -->
					<div class="row">
						<div class="col-lg-10 offset-lg-1 text-center">
							<div class="services-6-txt">

								<span class="mx-eyebrow mx-eyebrow-light"><span class="mx-pulse"></span>Why STED</span>
								<h3 class="h3-md"><?php echo $qualityedu['title']; ?></h3>	
				
								<!-- Text -->
								<p class="p-md"><?php echo $qualityedu['description']; ?>
								</p>

								<!-- Button -->
								<a href="#" class="btn btn-md btn-rose tra-white-hover">Start Learning Now!</a>

							</div>
						</div>
					</div>	<!-- END SERVICES TEXT -->


					<!-- SERVICE BOXES -->
					<div class="row">
						<div class="col-lg-10 offset-lg-1">
							<div class="row">

							<?php $i=1;$icon=''; foreach($Qualityeducationicons as $row) { 
									 if($i==1) $icon='flaticon-031-select';
									 elseif($i==2) $icon='flaticon-028-learning-1';
									 elseif($i==3) $icon='flaticon-006-diploma';
									 elseif($i==4) $icon='flaticon-013-elearning-5';  
									 else $icon='flaticon-001-book'; ?>
									 
								<!-- SERVICE BOX #1 -->		
								<div class="sbox-6 icon-xl">
									<span class="<?php echo $icon; ?>"></span>
									<h5 class="h5-xs"><?php echo $row['title']; ?></h5>	
								</div>
							<?php $i++; }?>

							 

							</div>
						</div>
					</div>	<!-- END SERVICE BOXES -->


				</div>	   <!-- End container --> 	
			</section>	<!-- End SERVICES-6 --> 




			<!-- CATEGORIES-3
			============================================= -->
			<section id="categories-3" class="pt-50 pb-50 categories-section division">
				<div class="container"  >
					<?php
						if(isset($home_page['12'])&&$home_page['12'])
							$topcateg=$home_page['12'];
						else $topcateg='';
					?>	
					<!-- SECTION TITLE -->	
					<div class="row">	
						<div class="col-md-12">
							<div class="section-title mb-60">

								<span class="mx-eyebrow"><span class="mx-pulse"></span>Categories</span>
								<h3 class="h3-sm"><?php echo $topcateg['title'] ?></h3>	

								<!-- Text -->	
								<p class="p-md"><?php echo $topcateg['description'] ?>
								</p>

								<!-- Button -->	
								<!-- <div class="title-btn">
									<a href="categories-list" class="btn btn-tra-grey rose-hover">View All Categories</a>
								</div>  -->

							</div>	
						</div>
					</div>


					<!-- CATEGORIE BOXES CAROUSEL -->
					<div class="row">
						<div class="col-lg-12" >
							<div class="owl-carousel owl-theme owl-loaded categories-carousel" >


									<?php $i=0; $color='bg-blue';
									foreach($subcategory as $row) { ?>
								<!-- CATEGORIE BOX #1 -->
								<?php  
								 if($i==1) $color='bg-green';
								elseif($i==2) $color='bg-red';
								elseif($i==3) $color='bg-teal';
								elseif($i==4) $color='bg-yellow';
								elseif($i==5) $color='bg-violet';
								elseif($i==6) $color='bg-orange';
								elseif($i==7) $color='bg-lightgreen';
								elseif($i==8) $color='bg-skyblue';
								else $color='bg-olive'; ?>
								<div class="<?php echo $color; ?> c3-box text-center icon-md white-color">
								<a href="<?php echo base_url(); ?>category/<?php echo $row['id_category']; ?>" title="STED - <?php echo $row['title']; ?>" alt="STED - <?php echo $row['title']; ?>">
										<!-- Icon --> 
										<div class="c3-box-icon">
										<?php   if(is_file("uploads/category/".$row['image'])) { ?> 
											<img loading="lazy" src="<?php echo optimized_image_url('category', $row['image'], 400); ?>" alt="category-icon" />
											<?php }else { ?>
												<div class="no-picture-subcat">No Picture</div>
											<?php } ?>
										</div>

										<!-- Text -->
										<div class="cbox-3-txt">
											<h5 class="h5-md cbox-3-txt-title"><?php echo $row['title']; ?></h5>
											<p><?php echo $row['coursenbr']; ?> <?php echo $row['coursenbr'] == 1 ? 'Course' : 'Courses'; ?></p>
										</div>

									</a>
								</div>	
								<?php $i++;} ?>
								<!-- END CATEGORIE BOX #1 -->								
 					
							</div>
							 
				</div>     <!-- End container -->
			</section>	<!-- END CATEGORIES-3 -->




			 


			<!-- TESTIMONIALS-3
			============================================= -->
			<section id="reviews-3" class="bg-01 reviews-section division">
				<span class="mx-rv-orb mx-rv-orb-1" aria-hidden="true"></span>
				<span class="mx-rv-orb mx-rv-orb-2" aria-hidden="true"></span>
				<div class="container">
					<div class="row">
						<div class="col-md-12">
					<?php
						if(isset($home_page['10'])&&$home_page['10'])
							$Testimonials=$home_page['10'];
						else $Testimonials='';
					?>	
							<!-- TESTIMONIAL TEXT -->
							<div class="review-3">
								<span class="mx-rv-spot" aria-hidden="true"></span>
								<i class="fas fa-quote-right mx-rv-mark" aria-hidden="true"></i>

								<!-- Quote Icon -->
								<div class="quote-ico"><img loading="lazy" src="<?php echo base_url(); ?>theme_academy/images/left-quote.png" alt="quote-image" /></div>
				
								<!-- Testimonial Text -->
								<div class="mx-rv-text" data-words><?php echo $Testimonials['description']; ?></div>		

								<!-- Testimonial Author -->
								<div class="review-3-author">

									<!-- Rating -->
									<div class="tst-rating clearfix">
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star-half-alt"></i>
									</div>	

									<h5 class="h5-md"><?php echo $Testimonials['title']; ?></h5>	
									<!-- <span>Online Student (Public Relations)</span> -->
								</div>	

							</div>	<!-- END TESTIMONIAL TEXT -->	


						</div>
					</div>	   <!-- End row -->
				</div>	    <!-- End container -->
			</section>	 <!-- END TESTIMONIALS-3 -->



<?php if (false) : /* Performance: section not shown on the site (HTML comment / hidden) - skip its PHP and HTML. Change false to true to bring it back */ ?>
<!-- COURSES-3
			============================================= -->
			<!-- <section id="courses-3" class="pt-50 pb-20 bg-gray courses-section division">
				<div class="container">
					<?php
						if(isset($home_page['11'])&&$home_page['11'])
							$popularecourse=$home_page['11'];
						else $popularecourse='';
					?>	
					<div class="row">	
						<div class="col-md-12">
							<div class="section-title mb-20">
								<h3 class="h3-sm"><?php echo $popularecourse['title']; ?></h3>	
								<p class="p-md"><?php echo $popularecourse['description']; ?>
								</p>
							</div>	
						</div>
					</div>
					<div class="row courses-grid">
				<?php foreach($topcourse as $row){    ?> 
				<div class="col-md-6 col-lg-4 col-xl-3">
							<div class="cbox-1">
 				<?php if( isset($login['idUser']) && !empty($login['idUser'])) { ?>
				<span class="clikfavorite button_favorite favorite_<?php echo $row['id_course'];?>" data-active="<?php echo $favorit[$row['id_course']]; ?>" data-id-course="<?php echo $row['id_course']; ?>">
					<i title="favorite" class="fa fa-bookmark <?php if($favorit[$row['id_course']]){ ?> fa-yellow <?php } ?>" class="btn-text"></i>
				</span>
				<?php } ?>

								<a href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>">
								<?php   if(is_file("uploads/course/".$row['image'])) { ?> 
									<img class="img-fluid" loading="lazy" src="<?php echo optimized_image_url('course', $row['image'], 600); ?>" alt="course-preview" />
                                   <?php }?>
									<div class="cbox-4-txt">	
										<p class="course-tags">
											<span><?php echo $row['coursetitle']; ?></span>
										</p>
										<h5 class="h5-xs"><?php echo $row['summary']; ?></h5>
									</div>
								</a>
							<div class="form-group rating-class DOMContentLoaded">
							<?php
							$average = $row['avg_course_rating'];
							for ($i = 1; $i <= 5; $i++) {
							$ratingClass = "star-grey";
							if($i <= $average) {
							$ratingClass = "star-highlight";
							}
							echo '<i class="fa fa-star fa-lg rateButton '.$ratingClass. '"  aria-hidden="true"></i>';
							}
							?>
							</div>
							</div>
						</div>		
						<?php }?>
					</div>	  
				</div>    
			</section>	 -->
<?php endif; ?>
			





<?php if (false) : /* Performance: section not shown on the site (HTML comment / hidden) - skip its PHP and HTML. Change false to true to bring it back */ ?>
		<!-- VIDEO-3 BRANDS      ------------  -->
		<!-- <div class="video-3-brands division">
					<div class="container">	
						<div class="row">
							<div class="col text-center">	

								
								<p class="p-md font-weight-bold pt-60 bp-30 white-color">Certified by World's Leading Bureaus:</p>

								
								<div class="owl-carousel brands-carousel">
												<?php foreach($brands as $row) {?>								
									
									<div class="brand-logo">
										<?php if(is_file("uploads/brands/".$row['image'])) ?> 
										<img class="img-fluid" src="<?php echo base_url(); ?>uploads/brands/<?php echo $row['image']; ?>" alt="brand-logo" />
									</div>
										
									<?php } ?>
									
								 

								</div>

							</div>  
						</div>  
					</div>  
				</div>	 -->
<?php endif; ?>

			<!-- BANNER-5
			============================================= -->
		 			<!-- ABOUT-4
			============================================= -->
			<section id="about-4" class=" about-section division">
				<div class="container">
			<?php
				if(isset($home_page['8'])&&$home_page['8'])
					$goals=$home_page['8'];
				else $goals='';
			?>	
				<div class="row d-flex align-items-center">
					<div class="col-md-7 col-lg-6">
						<div class="txt-block pc-25">
							<!-- Title -->	
							<span class="mx-eyebrow"><span class="mx-pulse"></span>Our Mission</span>
							<h5 class="h5-xl"><?php echo $goals['title']; ?></h5>

							<!-- Text -->
							<p>
								<?php echo $goals['description']; ?>
							</p> 
						</div>
					</div>	  <!-- END ABOUT TEXT -->
					<!-- ABOUT IMAGE -->
					<div class="col-md-5 col-lg-6">
						<div class="img-block">
						<?php   if(is_file("uploads/home_page/".$goals['image'])) { ?>
							<img class="img-fluid" loading="lazy" src="<?php echo optimized_image_url('home_page', $goals['image'], 900); ?>" alt="about-image">
							<!-- cards that pop out of the image when the mouse moves over the section (modern.js: initMission) -->
							<span class="mx-pop mx-pop-1" style="--dep:1.3" aria-hidden="true"><i class="fa-solid fa-graduation-cap"></i><span><b><?php echo $mx_total_courses; ?>+</b><small>Online courses</small></span></span>
							<span class="mx-pop mx-pop-2" style="--dep:-1" aria-hidden="true"><i class="fa-solid fa-award"></i><span><b>Certified</b><small>Recognised certificates</small></span></span>
							<span class="mx-pop mx-pop-3" style="--dep:.8" aria-hidden="true"><i class="fa-solid fa-clock"></i><span><b>24/7</b><small>Learn at your pace</small></span></span>
						<?php } ?>
						</div>
					</div>
				</div>    <!-- End row --> 	
						</div>

			 <div class="pt-50 pb-50 about-section division bg-gray mx-ab-band">
				<span class="mx-ab-orb mx-ab-orb-1" aria-hidden="true"></span>
				<span class="mx-ab-orb mx-ab-orb-2" aria-hidden="true"></span>
                <div class="container">
					<!-- ABOUT BOXES -->
					<div class="a4-boxes">
						<div class="row d-flex align-items-center">

						<?php $i=1; $icon=''; foreach($popularcoursesicons as $row) { 
							 if($i==1) $icon='flaticon-004-computer';
							 elseif($i==2) $icon='flaticon-028-learning-1';  
							 else $icon='flaticon-032-tablet';   ?>

							<!-- BOX #1 -->		
							<div class="col-md-4">
								<div class="abox-4 icon-sm" style="--n:<?php echo $i; ?>">
									<span class="mx-ab-num" aria-hidden="true">0<?php echo $i; ?></span>
									<span class="mx-ab-shine" aria-hidden="true"></span>

									<!-- Icon --> 
									<span class="<?php echo $icon; ?> "></span>

									<!-- Text -->
									<div class="abox-4-txt">
										<h5 class="h5-md"><?php echo $row['title']; ?></h5>
										<p><?php echo $row['description']; ?></p>
									</div>

								</div>
							</div>	<!-- END BOX #1 -->	
								<?php $i++;} ?>
 


						</div>  <!-- End row --> 
					 </div>	  <!-- END ABOUT BOXES -->
				</div>	   <!-- End container --> 	
			 </div>
			</section>	<!-- End ABOUT-4 --> 


			

			 
			<!-- VIDEO-3
			============================================= -->	
			<section id="video-3" class="bg-scroll video-section division">
				<?php
					if(isset($home_page['9'])&&$home_page['9'])
						$homevideo=$home_page['9'];
					else $homevideo='';
				?>	
				<!-- VIDEO TEXT video-3-txt -->
				<div class="bg-scroll video-3-txt division">
					<div class="container white-color">		
						<div id="video-3-content" class="row">
							<div class="col-lg-8 offset-lg-2 video-txt text-center">
	
								<!-- Title -->
								<h3 class="h3-md"><?php echo $homevideo['title']; ?></h3>	
				
								<!-- Text -->
								<p class="p-lg"><?php echo $homevideo['description']; ?></p>

							</div>							
						</div>													
					</div>	 <!-- End container -->		
				</div> 	  <!-- END VIDEO TEXT -->


				<!-- VIDEO LINK -->
				<div class="video-3-link mb-40 division">
					<div class="container">		
						<div class="row">								
							<div class="col-md-12 video-link text-center">	
				 					
								<!-- Change the link HERE!!! -->						
								<div class="play-btn play-btn-rose text-center">
<!-- 
									<a class="video-popup3 video-play-button" href="<?php echo $homevideo['link']; ?>">
									  <span></span>
									</a> -->

									<!-- Preview Image: tilts up into place on scroll (modern.js: initVideoStage), floating badges follow the mouse -->
									<div class="mx-vstage" id="mx-vstage">
										<div class="mx-vframe">
											<div class="mx-vimg">
												<img width="75%" class="img-fluid" loading="lazy" src="<?php echo optimized_image_url('home_page', $homevideo['image'], 900); ?>" alt="video-preview">
											</div>
										</div>
										<div class="mx-vbadge mx-vb-1" data-reveal style="--d:250ms"><span class="mx-vbadge-in" style="--k:1.4"><i class="fas fa-user-tie"></i><span><b>Industry experts</b><small>MEP &amp; commissioning engineers</small></span></span></div>
										<div class="mx-vbadge mx-vb-2" data-reveal style="--d:400ms"><span class="mx-vbadge-in" style="--k:-1.1"><i class="fas fa-certificate"></i><span><b>Certificates</b><small>After passing the exam</small></span></span></div>
										<div class="mx-vbadge mx-vb-3" data-reveal style="--d:550ms"><span class="mx-vbadge-in" style="--k:.9"><i class="fas fa-laptop"></i><span><b>100% online</b><small>Study at your own pace</small></span></span></div>
									</div>

								</div>

							</div>							
						</div>												
					</div>
				</div> 


		
	
			</section>	<!-- END VIDEO-3 -->




			<!-- SERVICES-5
			============================================= -->
			<section id="services-5" class="pt-50 pb-50 bg-lightgrey services-section division">
				<!-- decorative: soft orbs + floating icons around the title -->
				<span class="mx-op-orb mx-op-orb-1" aria-hidden="true"></span>
				<span class="mx-op-orb mx-op-orb-2" aria-hidden="true"></span>
				<span class="mx-op-float mx-op-f1" aria-hidden="true"><i class="fas fa-graduation-cap"></i></span>
				<span class="mx-op-float mx-op-f2" aria-hidden="true"><i class="fas fa-book-open"></i></span>
				<span class="mx-op-float mx-op-f3" aria-hidden="true"><i class="fas fa-award"></i></span>
				<span class="mx-op-float mx-op-f4" aria-hidden="true"><i class="fas fa-lightbulb"></i></span>
				<div class="container">
						<?php
							if(isset($home_page['7'])&&$home_page['7'])
								$opportunities=$home_page['7'];
							else $opportunities='';
						?>
				 	<!-- SECTION TITLE -->	
					<div class="row">	
						<div class="col-md-12">
							<div class="section-title title-centered mb-50 mx-op-head">
								<span class="mx-eyebrow"><span class="mx-pulse"></span>Opportunities</span>
								<!-- Title 	-->	
								<h3 class="h3-sm"><?php echo $opportunities['title']; ?></h3>	

								<!-- Text -->	
								<div class="p-md mx-op-text" data-words><?php echo $opportunities['description']; ?></div>

							</div>	
						</div>
					</div>


					<div class="row">

					<?php foreach($learnoppotrunities as $row) {?>
						<!-- SERVICE BOX #1 -->		
						<div class="col-md-6 col-lg-4"  >
							<div class="sbox-5">

								<!-- Icon --> 
								<?php if (is_file("uploads/icons_table/".$row['image'])) ?>
								<img class="img-70" loading="lazy" src="<?php echo optimized_image_url('icons_table', $row['image'], 200); ?>" alt="service-icon" />

								<!-- Text -->
								<div class="sbox-5-txt">

									<!-- Title -->
									<h5 class="h5-md"><?php echo $row['title']; ?></h5>
									
									<!-- Text -->	
									<p class="grey-color"><?php echo $row['description']; ?> 
									</p>

								</div>

							</div>
						</div>	<!-- END SERVICE BOX #1 -->	
							<?php }?>

						 
					</div>    <!-- End row --> 	
				</div>	   <!-- End container --> 	
			</section>	<!-- End SERVICES-5 --> 

 
			<!-- BANNER-3
			============================================= -->
			<section id="banner-3" class="bg-lightdark bg-map banner-section division">
				<div class="container">
					<div class="banner-3-holder bg-lightgrey">
						<div class="row d-flex align-items-center">

						<?php
	if(isset($home_page['17'])&&$home_page['17'])
	$learn=$home_page['17'];
else $learn='';
 
					?>
							<!-- BANNER IMAGE -->
							<div class="col-lg-8">
							<div class="banner-3-img 17">
 
									<?php if(is_file("uploads/home_page/".$learn['image'])) ?>   
									<img class="img-fluid" loading="lazy" src="<?php echo optimized_image_url('home_page', $learn['image'], 900); ?>" alt="banner-image" />
								</div>
							</div>	<!-- END BANNER IMAGE -->


							<!-- BANNER TEXT -->
							<div class="col-lg-4">
								<div class="banner-3-txt">

									<!-- Title -->	
									<h4 class="h4-xs"><span><?php if(isset($learn['title']))echo $learn['title']; ?> </span></h4>

									<!-- Button -->	
									<!-- <a href="courses-list" class="btn btn-rose tra-black-hover">Find Out More</a> -->

								</div>
							</div>	<!-- END BANNER TEXT -->



						</div>   <!-- End row -->
					</div>    <!-- End banner-3-holder -->
				</div>	   <!-- End container -->
				<!-- WhatsApp Contact Button -->
<a href="https://wa.me/+966509296490" id="whatsappButton" style="display:none;  position: fixed;
    width: 50px;
    height: 50px;
    bottom: 40px;
    background-color: #25d366;
    color: #FFF;
    border-radius: 50px;
    text-align: center;
    font-size: 30px;
    box-shadow: 3px 4px 3px #999;
    left: 15px;
    z-index: 100;" target="_blank">
  <img loading="lazy" src="<?php echo base_url() ?>uploads/website/whatsapp-icon.png" alt="Contact us on WhatsApp" style="width: 50px; height: 50px;">
</a>
			</section>	<!-- END BANNER-3 -->


 




<?php if (false) : /* Performance: section not shown on the site (HTML comment / hidden) - skip its PHP and HTML. Change false to true to bring it back */ ?>
			<!-- Teachers-1
			============================================= -->
			<section id="courses-1" class="pt-50 pb-20 courses-section division" hidden>
				<div class="container">
					<?php
						if(isset($home_page['19'])&&$home_page['19'])
							$bestcourses=$home_page['19'];
						else $bestcourses='';
					?>
					<!-- SECTION TITLE -->	
					<div class="row">	
						<div class="col-md-12">
							<div class="section-title mb-60">
								<!-- Title 	-->	
								<h3 class="h3-sm"><?php echo $bestcourses['title']; ?></h3>	

								<!-- Text -->	
								<p class="p-md"><?php echo $bestcourses['description']; ?>
								</p>

							</div>	
						</div>
					</div>

					<!-- COURSE BOXES CAROUSEL -->
					<div class="row" >
						<div class="col-lg-12">
							<div class="owl-carousel owl-theme owl-loaded courses-carousel">
                                  <?php foreach($lecturer as $row){  		
									?>
								<!-- COURSE #1 -->
								<div class="cbox-1">
									<!-- <?php if( isset($login['idUser']) && !empty($login['idUser'])) { ?>
							<span class="clikfavorite button_favorite favorite_<?php echo $row['id_user'];?>" data-active="<?php echo $favorit[$row['id_user']]; ?>" data-id-course="<?php echo $row['id_user']; ?>">
							<i title="favorite" class="fa fa-bookmark <?php if($favorit[$row['id_user']]){ ?> fa-yellow <?php } ?>" class="btn-text"></i>
							</span><?php } ?> -->
									<a href="<?php echo base_url(); ?>teacherprofile/<?php echo $row['id_user']; ?>">
									
										<!-- Image -->
										<?php   if(is_file("uploads/user/".$row['image'])) { ?> 
										<img class="img-fluid image-carousel" src="<?php echo base_url(); ?>uploads/user/<?php echo $row['image']; ?>" alt="Lecturer-preview" />	
											<?php }?>
										<!-- Text -->
										<div class="cbox-1-txt">

											<!-- Course Tags -->
											<p class="course-tags">
												<!-- <span>Languages</span>
												<span>English</span> -->
												<span><?php echo $row['title']; ?></span>
											</p>	

											<!-- Course Title -->
											<h5 class="h5-xs"><?php echo $row['name']; ?></h5>
										</div>
									</a>

										<div class="form-group rating-class DOMContentLoaded">
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
											?>
											</div>
								</div>	<!-- END COURSE #1 -->								
                                  <?php } ?>
							</div>
						</div>		
					</div>	  <!-- END COURSES BOXES CAROUSEL -->
				</div>     <!-- End container -->
			</section>	<!-- END COURSES-1 -->
<?php endif; ?>












<script>
  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      document.getElementById("whatsappButton").style.display = "block";
    } else {
      document.getElementById("whatsappButton").style.display = "none";
    }
  }
</script>
<style >
	#about-2 {
		display:none;
	}
	.owl-carousel .owl-item img.image-carousel
	 {
       height:auto !important;
	 }
	 #about-3 {
		background: #eee;
	 }
	 </style>