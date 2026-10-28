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
                            <li class="breadcrumb-item active" aria-current="page">FAQs</li>
                        </ol>
                    </nav>
                </div> 

            </div>	 <!-- End row -->
        </div>	<!-- End container -->		
    </div>	<!-- END BREADCRUMB -->	

    <!-- CATEGORIES-2
    ============================================= -->



				<!-- FAQs-1
				============================================= -->
				<section id="faqs-1" class="bg-fixed wide-70 faqs-section division">				
					<div class="container">
						<div class="row">


							<!-- TABS NAVIGATION -->
							<div class="col-lg-5">
								<div class="tabs-nav clearfix">
							 		<ul class="tabs-1">
                                    <?php  $ii=0;
                                    foreach($faqs as $row){ 
                                        if($row['id_faqs']== '5') {  if(!empty($login['idUser'])) {
                                    ?> 
	<a href="<?php echo base_url();?>listapidata" class="nav-link">
                                   
										<!-- TAB-1 LINK -->
										<li class="tab-link  <?php if($ii==0){ ?> current <?php } $ii++; ?>" data-tab="tab-<?php echo $row['id_faqs'];?>">
                                            <!-- Image --> 
                                            <?php if(is_file("uploads/faqs/".$row['icon'])) { ?> 
                                             <img class="img-55" src="<?php echo base_url(); ?>uploads/faqs/<?php echo $row['icon']; ?>" alt="<?php echo $row['title'];?>" />
                                            <?php }?>
					                    	<div class="tab-link-txt">
						                    	<h5 class="h5-xs"><?php echo $row['title'];?></h5>
						                    	<p><?php echo $row['small_description'];?></p>
						                    </div>
										</li>
    </a>   <?php } } else {?>
        	<!-- TAB-1 LINK -->
            <li class="tab-link  <?php if($ii==0){ ?> current <?php } $ii++; ?>" data-tab="tab-<?php echo $row['id_faqs'];?>">
                                            <!-- Image --> 
                                            <?php if(is_file("uploads/faqs/".$row['icon'])) { ?> 
                                             <img class="img-55" src="<?php echo base_url(); ?>uploads/faqs/<?php echo $row['icon']; ?>" alt="<?php echo $row['title'];?>" />
                                            <?php }?>
					                    	<div class="tab-link-txt">
						                    	<h5 class="h5-xs"><?php echo $row['title'];?></h5>
						                    	<p><?php echo $row['small_description'];?></p>
						                    </div>
										</li>
                                        <?php } ?>
                                        <?php } ?>
									</ul>
								</div>
							</div>	<!-- END TABS NAVIGATION -->


							<!-- TABS CONTENT -->
							<div class="col-lg-7">
								<div class="tabs-content">
<?php 
$ii=0;
foreach($faqs as $row){ 
 ?> 
									<!-- TAB-1 CONTENT -->
									<div id="tab-<?php echo $row['id_faqs'];?>" class="tab-content <?php if($ii==0){ ?>current<?php } $ii++; ?>">

										<!-- CATEGORY TITLE -->
										<div class="category-title">

											<!-- Title -->
											<!-- <h4 class="h4-md"><?php echo $row['title'];?></h4> -->

											<!-- Text -->
											<span class="p-md">
                                              <?php echo $row['description'];?>
                                            </span>
										</div>

									</div>	<!-- END TAB-1 CONTENT -->	
<?php } ?>
								</div>
							</div>	<!-- END TABS CONTENT -->
						</div>	  <!-- End row -->	
					</div>	   <!-- End container -->		
				</section>	<!-- END FAQs-1 -->	
























				<!-- ABOUT-2
				============================================= -->
<section id="about-2" class="about-section division">
<?php 
$kk=0;
foreach($aboutus as $row){ 
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