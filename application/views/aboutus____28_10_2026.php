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
                            <li class="breadcrumb-item active" aria-current="page">About us</li>
                        </ol>
                    </nav>
                </div> 

            </div>	 <!-- End row -->
        </div>	<!-- End container -->		
    </div>	<!-- END BREADCRUMB -->	

    <!-- CATEGORIES-2
    ============================================= -->
				<!-- ABOUT-2
				============================================= -->
				<section id="about-2" class="about-section division">
					

<?php 
$kk=0;
foreach($aboutus as $row){ 
    if($kk%2==0){
    ?> 
<div class="container pt-60 pb-60 aaa">
    <div class="row d-flex align-items-center bbb">
        <!-- ABOUT IMAGE -->
        <div class="col-md-5 col-lg-6">
            <div class="img-block mb-40">
                                                        <!-- Image -->
                <?php   if(is_file("uploads/about/".$row['image'])) { ?> 
                <img class="img-fluid" src="<?php echo base_url(); ?>uploads/about/<?php echo $row['image']; ?>" alt="co   w" />
                <?php }?>
            </div>
        </div>
        <!-- ABOUT TEXT -->
        <div class="col-md-7 col-lg-6">
            <div class="txt-block">
                <!-- Title -->	
                <h3 class="h3-sm"><?php echo $row['title'];?></h3>
                <!-- Text -->
                <div class="text-justify"> 
                    <?php echo $row['description'];?> 
                </div> 
            </div>
        </div>	  <!-- END ABOUT TEXT -->
    </div>    <!-- End row --> 
</div>	   <!-- End container --> 	
<?php }else{ ?>
<div class="block2 pt-60 pb-60 aaaa" >
    <div class="container bbbb">
        <div class="row d-flex align-items-center cccc">
            <!-- ABOUT TEXT -->
            <div class="col-md-7 col-lg-6">
                <div class="txt-block">
                    <!-- Title -->	
                    <h3 class="h3-sm"><?php echo $row['title'];?></h3>
                    <!-- Text -->
                    <div class="text-justify"> 
                        <?php echo $row['description'];?> 
                    </div> 
                </div>
            </div>	  <!-- END ABOUT TEXT -->
            <!-- ABOUT IMAGE -->
            <div class="col-md-5 col-lg-6">
                <div class="img-block mb-40">
                    <!-- Image -->
                    <?php   if(is_file("uploads/about/".$row['image'])) { ?> 
                    <img class="img-fluid" src="<?php echo base_url(); ?>uploads/about/<?php echo $row['image']; ?>" alt="co   w" />
                    <?php }?>
                </div>
            </div>
        </div>    <!-- End row -->
    </div>	   <!-- End container --> 	
</div>	   <!-- End container --> 	
<?php } $kk++;
 ?>
<?php } ?>

	</section>	<!-- End ABOUT-2 --> 
      
</div>	<!-- END INNER PAGE WRAPPER -->

<style>
    .block2 {
        background: #081D30 !important;
    }
    .block2 .txt-block h3.h3-sm {
      color:#fff;
    }
    .block2 ul li {
      color:#fff;
    }
</style>