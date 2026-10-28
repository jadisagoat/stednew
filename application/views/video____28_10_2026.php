<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/vplayer.css" />

<!-- PAGE CONTENT
		============================================= -->
<!-- <div id="page" class="page">
			<div class="inner-page-wrapper">
					<div class="container">
						<div class="row"> -->



<?php if (!empty($login['idUser'])) { ?>
  <div class="row">


    <div class="col-lg-12">

      <div class="video-gallery">
        <div class="videogallery-con" style="width:100%;">
          <!-- <div class="col-md-12"> -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <?php foreach ($coursedetail as $row) { ?>
                <!-- <li class="breadcrumb-item"><a href="courses-list.html"><?php echo $row['categtitle']; ?></a></li> -->
                <li class="breadcrumb-item"><?php echo $row['categtitle']; ?>
                  <!-- <li class="breadcrumb-item active" aria-current="page"><?php echo $row['subcategtitle']; ?></li> -->
                <li class="breadcrumb-item active" aria-current="page"><?php echo $row['coursetitle']; ?></li>
              <?php } ?>
            </ol>
          </nav>
          <!-- </div>  -->
          <!--START VIDEO GALLERY-->
          <style id="html5vg-preview-style"></style>
          <div id="html5vg-preview" class="videogallery skin_pro" style="width:100%; height:374px;">

            <?php foreach ($video_course as $row) {
              $var = '';
              if ($row['youtube']) {
                $var = ' data-type="youtube" data-src="' . $row['youtube'] . '" ';
              } else {
                $var = ' data-videoTitle="' . $row['title'] . '" data-sourcemp4=" ' . base_url() . 'uploads/video/' . $row['video_file'] . '" data-sourceogg="' . base_url() . 'uploads/video/' . $row['video_file'] . '" ';
              } ?>
              <div class="vplayer-tobe" <?php echo $var; ?>>
                <div class="menuDescription">
                  <div style="background-image: url('<?php echo base_url() . 'uploads/video/' . $row['image']; ?>');" class="imgblock"></div>
                  <div class="the-title">
                    <?php echo $row['title']; ?>
                  </div>
                  <?php echo $row['summary']; ?>
                </div>
              </div>
            <?php } ?>

          </div>
          <div class="cs-content cd-block">

            <!-- Small Title -->
            <h5 class="h5-xl">Course content</h5>

            <!-- Text -->
            <?php foreach ($coursesummary as $row) ?>
            <p class="p-md">Level Beginner &bull; <?php echo $row['contentnbr'] ?> Lectures &bull; <?php echo $row['sumhrs'] ?> Total Hours</p>

            <!-- ACCORDION -->
            <div id="accordion" role="tablist">

              <?php $i = 0;
              $headline = '';
              $collapse = '';
              foreach ($coursecontent as $row) { ?>
                <!-- CARD #1 -->
                <!-- <div class="card">

          
        <?php if ($i == 0) {
                  $headline = "headingOne";
                  $collapse = 'collapseOne';
                } elseif ($i == 1) {
                  $headline = "headingTwo";
                  $collapse = 'collapseTwo';
                } else {
                  $headline = "headingThree";
                  $collapse = 'collapseThree';
                }
        ?>
    
        <div class="card-header" role="tab" id="<?php echo $headline; ?>">
         
              <h5 class="h5-xs">
                   <a class="collapsed"  data-toggle="collapse" href="#<?php echo $collapse; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $collapse; ?>">
                     <?php echo $row['title'] ?>
                </a>
             </h5>

         
            <div class="hdr-data"><p> <?php echo $row['hours']; ?> hrs</p></div>

        </div>

         
        <div id="<?php echo $collapse; ?>" class="collapse show" role="tabpanel" aria-labelledby="<?php echo $headline; ?>" data-parent="#accordion">
             <div class="card-body">

             
                <ul class="txt-list mb-10"> 
                    <li><?php echo $row['description'] ?></li>
                </ul>

             
                  <p class="cb-video"><a class="video-popup2" href="<?php echo $row['link'] ?>">
                      <i class="fas fa-play-circle"></i> 
                    Course Link
                  </a></p>
                 
                 

             </div>
           </div>

    </div> -->
                <!-- END CARD #1 -->


                <!-- CARD #2 -->
                <div class="card">


                  <div class="card-header" role="tab" id="<?php echo $headline; ?>">
                    <h5 class="h5-xs">
                      <a class="collapsed" data-toggle="collapse" href="#<?php echo $collapse; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $collapse; ?>">
                        <?php echo $row['title'] ?>
                      </a>
                    </h5>


                    <div class="hdr-data">
                      <p> <?php echo $row['hours']; ?> hrs</p>
                    </div>

                  </div>


                  <div id="<?php echo $collapse; ?>" class="collapse" role="tabpanel" aria-labelledby="<?php echo $headline; ?>" data-parent="#accordion">
                    <div class="card-body">
                      <?php echo $row['description']; ?>

                      <!-- Video link -->

                      <!-- <p class="cb-video"><a class="video-popup2" href="<?php echo $row['link']; ?>">
                      <i class="fas fa-play-circle"></i> Course Link
                  </a></p> -->

                      <!-- <p class="cb-video"><a class="video-popup3 video-play-button" href="<?php echo $row['link']; ?>">
                      <i class="fas fa-play-circle"></i> Course Link
                  </a></p> -->

                      <!-- <?php if (is_file("uploads/content/" . $row['file'])) { ?>
                 <p class="download-file">
                    <i class="fas fa-file-archive"></i> 
                    <a href="<?php echo base_url(); ?>uploads/content/<?php echo $row['file']; ?> " download="your-file">Download File</a>
                </p>  
            <?php } ?> -->
                    </div>
                  </div>

                </div>
              <?php $i = $i + 1;
              } ?>
              <!-- END CARD #2 -->




            </div> <!-- END ACCORDION -->

          </div> <!-- END COURSE CONTENT -->
        </div>
      </div>
    </div>
  </div>

<?php } ?>





<style>
  .active .the-title {
    font-weight: bold !important;
  }

  .the-logo {
    width: 50px;
  }

  .video-gallery {
    margin: 100px auto;
  }

  .ytp-chrome-top-buttons {
    display: none !important;
  }

  .course-detail {
    position: relative !important;
    padding: 0 !important;
  }

  .similarcourses {
    bottom: -2px;
  }
</style>
<script>
  // rating form hide/show
  $("#rateProduct").click(function() {
    $("#ratingSection").show();
  });

  // implement start rating select/deselect
  $(".rateClick").click(function() {
    alert($('.star-selected').length)

    if ($(this).hasClass('star-grey')) {
      $(this).removeClass('star-grey').addClass('star-highlight star-selected');
      $(this).prevAll('.rateClick').removeClass('star-grey').addClass('star-highlight star-selected');
      $(this).nextAll('.rateClick').removeClass('star-highlight star-selected').addClass('star-grey');
    } else {
      $(this).nextAll('.rateClick').removeClass('star-highlight star-selected').addClass('star-grey');
    }
    $("#ratingClick").val($('.star-selected').length); //count the num of star selected
  });
</script>