<?php
/* ==========================================================================
   COURSE PLAYER POPUP (modern design - backup: course-details-popup____28_10_2026.php)
   Included by course-details.php for enrolled users. Styles: css/pages/course.css (#courseModal)
   The script below is unchanged: it relies on .navdesignlink, .tab-pane, .activedetails,
   .tabRadio*, #loading-spinner, #content_prevtab / #content_nexttab, .tab-number, .closevideo
   ========================================================================== */
$mxp_icons = array('1' => 'fas fa-play', '2' => 'far fa-file-lines', '3' => 'far fa-file-pdf');
$mxp_types = array('1' => 'Video', '2' => 'Reading', '3' => 'PDF');
?>
<!-- Modal -->
<div class="modal fade mx-player" id="courseModal" tabindex="-1" role="dialog" aria-labelledby="courseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="mx-pl-logo" aria-hidden="true"><i class="fas fa-graduation-cap"></i></span>
        <h5 class="modal-title" id="courseModalLabel">
          <small>Now learning</small>
          <?php echo $coursedetail['coursetitle']; ?>
        </h5>
        <button type="button" class="close closevideo" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fas fa-xmark"></i></span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Content will be loaded here -->
        <div class="nav-container">
          <span class="mx-pl-side-label">Course content · <?php echo count($contents); ?> lessons</span>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <?php $k = 0;

            foreach ($contents as $row) {
              $ariaselected = 'false';
              $activedetails = '';
              $nav = '';
              $pointer = 'no-pointer';
              if ($k == 0 || in_array($row['id_content'], $id_content_complete_by_course) || $k <= count($id_content_complete_by_course) - 1) {

                if ($k == 0) {
                  $activedetails = 'activedetails';
                }
                if ($k < count($id_content_complete_by_course) - 1) {  //$k==0 ||
                  $ariaselected = 'true';
                  $nav = 'nav-link ';
                  $pointer = 'cursor-pointer';
                } else {
                  // $ariaselected='true'; $nav='nav-link '; $pointer='cursor-pointer';
                }
              }
              $k++; ?>
              <li class="nav-item">
                <a class="navdesignlink <?php echo $nav;
                                        echo $activedetails; ?>" id="<?php echo $row['id_content']; ?>-tab" data-toggle="tab" role="tab" aria-controls="<?php echo $row['id_content']; ?>" aria-selected="<?php echo $ariaselected; ?>">
                  <div class="label-title  <?php echo $pointer; ?>">
                    <input type="radio" class="tabRadio tabRadio<?php echo $row['id_content']; ?>" name="tabRadio<?php echo $row['id_content']; ?>" <?php echo (in_array($row['id_content'], $id_content_complete_by_course)) ? 'checked' : ''; ?> hidden />
                    <span class="mx-pl-ltxt">
                      <b><?php echo $row['title']; ?></b>
                      <small><i class="<?php echo isset($mxp_icons[$row['id_type']]) ? $mxp_icons[$row['id_type']] : 'far fa-file'; ?>"></i> <?php echo isset($mxp_types[$row['id_type']]) ? $mxp_types[$row['id_type']] : 'Lesson'; ?> · <?php echo str_pad($k, 2, '0', STR_PAD_LEFT); ?></small>
                    </span>
                  </div>
                </a>
              </li>
            <?php }
            ?>
            <?php
            if (count($questions)) {
              if ($avgcourse[$coursedetail['id_course']] == "100") {
                $style = " style=' display:block !important'";
              } else {
                $style = " style=' display:none !important'";
              }
              $attributes = '';
              $ClickExam = 'ClickExamRateFeedback';
              if ($my_user_course_rating['0']['rating'] && $my_user_course_rating['0']['feedback']) {
                $ClickExam = 'ClickExam';
                $attributes = ' data-toggle="modal" data-target="#examModal" ';
              }


            ?>
              <div class="row d-flex pb-3 tak-exam tak-left" <?php echo $style; ?>>
                <div id="examClick" <?php echo $attributes; ?> class="<?php echo $ClickExam; ?> mx-pl-exam" role="button" tabindex="0">
                  <i class="fas fa-graduation-cap"></i> Take Exam
                </div>
              </div>
            <?php  } ?>
          </ul>
        </div>

        <div class="tab-content" id="myTabContent">
          <?php $k = 0;
          foreach ($contents as $row) {
            $activedetails = '';
            if ($k == 0) {
              $activedetails = 'show activedetails';
            }
            $k++; ?>
            <div class="tab-pane fade <?php echo $activedetails; ?><?php echo $row['id_type'] == "1" ? ' mx-pl-video' : ''; ?>" id="<?php echo $row['id_content']; ?>" role="tabpanel" aria-labelledby="<?php echo $row['id_content']; ?>-tab">
              <?php if ($row['id_type'] != "1") { ?>
                <div class="mx-pl-pane-head">
                  <span class="mx-pl-step">Lesson <?php echo $k; ?> of <?php echo count($contents); ?></span>
                  <h1><?php echo $row['title']; ?></h1>
                </div>
              <?php } ?>
              <?php
              if ($row['id_type'] == "1") // video
              {
                if ($row['link']) {  ?>
                  <!-- Include the YouTube IFrame API script-->
                  <iframe class="yvideo" width="100%" height="100%" src="<?php echo $row['link']; ?>?enablejsapi=1" frameborder="0" allowfullscreen></iframe>
                <?php } else {  ?>
                  <video width="100%" height="100%" controls controlsList="nodownload" class="video">
                    <source src="<?php echo base_url() . 'uploads/content/' . $row['file']; ?>" type="video/mp4">
                  </video>

                  <div id="current" style="display:none">0:00</div>
                  <div id="duration_content" style="display:none">0:00</div>
                <?php } ?>
              <?php }
              if ($row['id_type'] == "3") {  // pdf
              ?>
                <div class="mx-pl-pdf">
                  <span class="mx-pl-pdf-ico"><i class="far fa-file-pdf"></i></span>
                  <div>
                    <b>Please refer to the following PDF for a more comprehensive understanding of the course:</b>
                    <a target="_blank" href="<?php echo base_url() . 'uploads/content/' . $row['file']; ?>"><i class="fas fa-arrow-up-right-from-square"></i> <?php echo $row['file'] ?></a>
                  </div>
                </div>
              <?php }
              if ($row['id_type'] == "2") // content
                echo "<div class='content'>" . $row['description'] . "<br></div>";
              ?>
            </div>
          <?php   } ?>

          <div class="footerpopup">
            <div class="nextprev">
              <div id="loading-spinner">
                <input type="hidden" value="<?php echo count($contents); ?>" id="total_content_course">
                <div class="spinner-border text-primary" role="status"></div>
                <button class="dx-icon-arrowright" disabled aria-hidden="true"><i class="fas fa-arrow-right"></i></button>
              </div>

              <button class="prevtab dx-icon-arrowleft" id="content_prevtab" disabled='disabled' aria-label="Previous lesson"><i class="fas fa-arrow-left"></i></button>
              <span class="tab-number"></span>
              <button class="nexttab dx-icon-arrowright" id="content_nexttab" disabled='disabled' aria-label="Next lesson"><i class="fas fa-arrow-right"></i></button>
            </div>
          </div><!-- next previous -->
        </div><!-- /.container -->
      </div>
    </div>
  </div>
</div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script type="text/javascript">
  // start bootstrapTabControl
  function bootstrapTabControl() {


    var $tabLinks = $('.navdesignlink'),
      $tabPanes = $('.tab-pane'),
      totalTabs = $tabLinks.length;

    function updateTabNumber(currentIndex) {
      $('.tab-number').text((currentIndex + 1) + '/' + totalTabs);
    }

    function showTabContent(index) {
      $tabLinks.removeClass('activedetails');
      $tabPanes.removeClass('show activedetails');
      $($tabLinks[index]).addClass('nav-link activedetails ayman');
      $($tabPanes[index]).addClass('show activedetails ayman');
      updateTabNumber(index);
    }

    // start course code  
    $('.startcourse').on('click', async function() {
      // Get the ID of the first content li in the list
      var id = $('.nav-tabs li:first-child a').attr('aria-controls');
      // Show loading spinner to indicate that data is being saved
      $('#loading-spinner').show();
      try {
        if (!$('.nav-tabs li:first-child a').hasClass('nav-link')) {
          showTabContent(0);
          // Simulate saving data with a setTimeout

          ///////////////////////// ayman
          // Check if the video element exists
          var video = $(".activedetails video")[0];
          var duration = "6000";
          console.log("duration1__" + duration);

          $("#duration_content").text(duration);
          if (video)
            if (video.duration) {
              var duration = video.duration.toFixed(2) * 1000;
              // alert("Videoxxxx Duration: " + duration + " seconds");
              $("#duration_content").text(duration);
              console.log("duration" + duration);
              //alert("start load..." + duration)

            }


          ///////////////////////// ayman
          await new Promise(resolve => {
            setTimeout(resolve, duration); // Simulate a delay of 6 seconds (6000 milliseconds)
          });
          //alert(duration)
          //alert("end1 load...")
          // Call   function to save data in the database
          await user_content_data(id);
          // alert("end2 load...")
        }

        $('#loading-spinner').hide();
        $('#content_nexttab').removeAttr("disabled");
        $('#content_prevtab').removeAttr("disabled");
        console.log("11");
      } catch (error) {
        // Handle any errors
        console.error("Error saving data:", error);
        // Ensure loading spinner is hidden in case of error
        $('#loading-spinner').hide();
        $('#content_nexttab').removeAttr("disabled");
        $('#content_prevtab').removeAttr("disabled");
        console.log("22");
      }
    });
    // end start course code 

    // $tabLinks.on('click', function() {
    //     if ($(this).hasClass('nav-link')) {
    //         var index = $tabLinks.index($(this));
    //         var paneId = $($tabPanes[index]).attr('id');
    //         user_content_data(paneId);
    //         $("#" + paneId + "-tab  .tabRadio").attr('checked', true);
    //         showTabContent(index);
    //     }
    // });

    $('#content_nexttab').on('click', function() {
      // alert("testtesttesttest11");
      // Show loading spinner to indicate that data is being saved
      // Get the current index
      var currentIndex = $tabLinks.index($('.nav-link.activedetails'));
      var nextcurssor = currentIndex + 1;

      if (nextcurssor < totalTabs) {
        var paneId = $($tabPanes[nextcurssor]).attr('id');
        $("#" + paneId + "-tab  .tabRadio").attr('checked', true);
        $('#loading-spinner').show();
        // diasble by ayman
        $('#content_nexttab').attr('disabled', 'disabled');
        $('#content_prevtab').attr('disabled', 'disabled');
        console.log("44");
        // alert(nextcurssor)
        /////////////////////////////////////////////
        // Define an async function to use await
        async function saveDataAndHideLoader() {
          try {
            if ($tabLinks.eq(nextcurssor).length) {
              var $nextTab = $tabLinks.eq(nextcurssor);
              // Get the id attribute of the next tab
              // var nextTabId = $nextTab.attr('id');
              // console.log(nextcurssor);
              // console.log($tabLinks.eq(nextcurssor).hasClass('nav-link'))

              if (!$tabLinks.eq(nextcurssor).hasClass('nav-link')) {
                // alert("nav-link exist");
                // Next element after nextcurssor exists and not has the class 'nav-link'
                // console.log("test1");
                // Simulate saving data with a setTimeout
                showTabContent(nextcurssor); // ayman

                var video = $(".activedetails video")[0];
                var duration = "6000";
                // console.log("duration1----" + duration);
                $("#duration_content").text(duration);

                // alert("start load......")
                // alert(video.duration)
                // alert(video.duration.toFixed(2))
                if (video)
                  if (video.duration) {
                    var duration = video.duration.toFixed(2) * 1000;
                    // alert("Videoxxxx Duration: " + duration + " seconds");
                    // alert("duration-ayman-----------" + duration);
                    $("#duration_content").text(duration);
                    // $("#duration_content").html(duration);
                    console.log("duration-ayman-----------" + duration);
                  }
                // alert("start load1......")
                // alert(duration)
                // alert("end load......")
                await new Promise(resolve => {
                  let timeoutId = setTimeout(resolve, duration); // Simulate a delay of 6 seconds (6000 milliseconds)
                });
                console.log("end loadsettimeout......")
                // clearTimeout(timeoutId);
                // console.log("test2");
                // Call   function to save data in the database for the first tab
                await user_content_data(paneId); //$($nextTabId[0]).attr('id')
                console.log("end load......")
                console.log("test3");

              } else {
                // await  (paneId); //$($nextTabId[0]).attr('id')
                showTabContent(nextcurssor); // ayman
                // alert("nav-link noot exist");
                $('#loading-spinner').hide();

                $('#content_nexttab').removeAttr("disabled");
                $('#content_prevtab').removeAttr("disabled");
                console.log("55");
              }
            } else {
              // alert("nav-link  not exist");
              // Next element after currentIndex+1 does not exist or does not have the class 'nav-link'
              //console.log("Next element after currentIndex+1 does not exist or does not have the class 'nav-link'.");
              $('#loading-spinner').hide();

              $('#content_nexttab').removeAttr("disabled");
              $('#content_prevtab').removeAttr("disabled");
              console.log("66");
              // Show the first tab
              // showTabContent(0);
            }
          } catch (error) {
            // Handle any errors
            console.error("Error saving data:", error);
            // Ensure loading spinner is hidden in case of error
            $('#loading-spinner').hide();

            $('#content_nexttab').removeAttr("disabled");
            $('#content_prevtab').removeAttr("disabled");
            console.log("77");
          }
        }
        // Call the async function
        saveDataAndHideLoader();
        /////////////////////////////////////////////
        // showTabContent(nextcurssor);
        // (paneId);
      }
    });




    ///////////////////////////////////////

    $('#content_prevtab').on('click', function() {
      // alert("aaaaa")
      var currentIndex = $tabLinks.index($('.nav-link.activedetails'));
      if (currentIndex != 0) {
        showTabContent(currentIndex - 1);
        $('#loading-spinner').hide();
        $('#content_nexttab').removeAttr("disabled");
        $('#content_prevtab').removeAttr("disabled");
        console.log("88");
        // alert("bbbbbb")
      }
      // alert("cccccc")
    });

    updateTabNumber($tabLinks.index($('.nav-link.activedetails')));
    // alert("ddddd")
  }
  // end bootstrapTabControl 

  $(document).ready(function() {
    $(' .closevideo').on('click', function() {
      var baseURL = window.location.origin + window.location.pathname;
      var targetURL = baseURL;
      window.location.href = targetURL;
    });


    // stop video next and prev
    $('#content_nexttab, #content_prevtab, .closevideo').on('click', function() {
      // pause video iframe
      $('.yvideo').each(function() {
        this.contentWindow.postMessage('{"event":"command","func":"stopVideo","args":""}', '*')
      });
      // Pause HTML5 videos
      $('video').each(function() {
        if (!this.paused && !this.ended && this.currentTime > 0) {
          this.pause();
        }
      });
    });
  });
  /////////////////////////////////////
  $(document).ready(function() {

    $('#loading-spinner').show();
    // diasble by ayman
    $('#content_nexttab').attr('disabled', 'disabled');
    $('#content_prevtab').attr('disabled', 'disabled');
    console.log("99");
    bootstrapTabControl();
  });
  // start save data in database 

  function onTrackedVideoFrame(currentTime, duration) {
    $("#current").text(currentTime.toFixed(2)); // Change #current to currentTime with 2 decimal places
    $("#duration_content").text(duration.toFixed(2)); // Format duration with 2 decimal places
  }

  function user_content_data(id) {
    // Retrieve necessary data from DOM elements
    var total_content_course = $('#total_content_course').val();
    var id_course = $('#id_course').val();
    // Show loading spinner to indicate data is being saved
    $('#loading-spinner').show();

    // Make AJAX request
    $.ajax({
      url: "<?php echo base_url(); ?>home/user_content_data/" + id,
      dataType: "json",
      type: 'POST',
      data: {
        id: id,
        id_course: id_course,
        total_content_course: total_content_course
      },
      success: function(data) {
        // alert("testtesttesttest");
        console.log(data);
        /// initial load 
        // Hide loading spinner after saving data
        $('#loading-spinner').hide();
        $('#content_nexttab').removeAttr("disabled");
        $('#content_prevtab').removeAttr("disabled");
        console.log("100");
        // Update content or perform other actions with the received data
        $(".tabRadio" + id).attr('checked', true);
        // Show elements conditionally based on data received
        console.log(total_content_course)
        console.log(data)

        if (total_content_course == data) {
          $('.tak-exam').show();
          $('.add_list_reviews').show();
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Hide loading spinner if an error occurs
        $('#loading-spinner').hide();
        $('#content_nexttab').removeAttr("disabled");
        $('#content_prevtab').removeAttr("disabled");
        console.log("1");
        // Log the error
        console.error("AJAX Error: " + textStatus, errorThrown);
        // Show error message to the user or perform other error handling actions
      }
    });
    $('#loading-spinner').hide();
    $('#content_nexttab').removeAttr("disabled");
    $('#content_prevtab').removeAttr("disabled");
    console.log("2");

  }

  function onTrackedVideoFrame(currentTime, duration) {

    $("#current").text(currentTime.toFixed(2)); // Change #current to currentTime
  }
  // end save data
</script>
<script>
  // phones: the lesson list is a horizontal strip - keep the current lesson in view
  (function () {
    var box = document.querySelector('#courseModal .nav-container');
    if (!box || !window.MutationObserver) return;
    new MutationObserver(function () {
      var on = box.querySelector('.navdesignlink.activedetails');
      if (on && box.scrollWidth > box.clientWidth) box.scrollTo({ left: on.parentNode.offsetLeft - 10, behavior: 'smooth' });
    }).observe(box, { subtree: true, attributes: true, attributeFilter: ['class'] });
  })();
</script>
