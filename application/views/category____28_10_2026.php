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
                            <li class="breadcrumb-item"><a href="<?php echo base_url();?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $categoryinfo['title']; ?></li>
                        </ol>
                    </nav>
                </div> 

            </div>	 <!-- End row -->
        </div>	<!-- End container -->		
    </div>	<!-- END BREADCRUMB -->	

    <!-- PAGE HERO
    ============================================= -->	
    <section class="bg-05 page-hero-section division">
        <div class="container">	
            <div class="row">	
                <div class="col-md-12">
                    <div class="hero-txt white-color">

                        <!-- Title -->
                        <h3 class="h3-xs"><?php echo $categoryinfo['title']; ?></h3>
                        
                        <!-- Share Icons -->
                        <div class="share-list">
                            <ul class="share-social-icons text-center clearfix">
                                <li><p><span>
                                    <?php foreach($countcousre as $row) echo $row['coursenbr']; ?> courses</span> found 
                                    <!-- in <span><?php  foreach($countsubcategory as $row) echo $row['subcatenbr']; ?>  categories</span><p></li>					 -->
                        <!--    <li><a href="#" class="share-ico ico-facebook"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#" class="share-ico ico-twitter"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="mailto:info@stedltd.com" class="share-ico ico-mail"><i class="far fa-envelope"></i></a></li>
                                <li><a href="#" class="share-ico ico-bookmark"><i class="far fa-bookmark"></i> 54.2k</a></li>
                            -->
                            </ul>
                        </div>
                                
                        <!-- Text -->
                        <p><span>Many students</span> are learning online on e-Learning STED</p>

                    </div>
                </div>	<!-- END PAGE HERO TEXT -->
            </div>    <!-- End row --> 
        </div>	   <!-- End container --> 
    </section>	<!-- END PAGE HERO -->	




    <!-- CATEGORIES-2
    ============================================= -->
    <section id="categories-2" class="pt-60 pb-25 categories-section division">
        <div class="container">
            <div class="row">


            <?php foreach($course as $row) { ?>
                <!-- CATEGORIE BOX #1 -->
                <div class="col-sm-6 col-lg-4 col-xl-4">
                    <a href="<?php echo base_url(); ?>coursedetails/<?php echo $row['id_course']; ?>" title="STED - <?php echo $row['title']; ?>" alt="STED - <?php echo $row['title']; ?>">
                        <div class="block" style="background: #081D30 url('<?php echo course_image_url($row['image']); ?>') center/cover no-repeat; min-height: 250px; position: relative; border-radius: 12px; overflow: hidden; margin-bottom: 30px;">         
                            <div class="text-center title" style="position: absolute; bottom: 0; left: 0; width: 100%; background: rgba(8, 29, 48, 0.95); color: #ffffff; padding: 12px; font-weight: bold; font-size: 15px; box-sizing: border-box;">
                                <!-- Text -->
                                <?php echo $row['title']; ?> 
                            </div>
                        </div>           
                    </a>
                </div>	<!-- END CATEGORIE BOX #1 -->
                <?php } ?>
            </div>    <!-- End row --> 	
        </div>	   <!-- End container --> 	
    </section>	<!-- End CATEGORIES-2 --> 
</div>	<!-- END INNER PAGE WRAPPER -->