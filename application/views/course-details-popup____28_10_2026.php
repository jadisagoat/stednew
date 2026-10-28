<!-- Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" role="dialog" aria-labelledby="courseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="courseModalLabel">
          <?php //foreach($coursedetail as $row){ 
          ?>
          <?php echo $coursedetail['coursetitle'];
          // } 
          ?>
        </h5>
        <button type="button" class="close closevideo" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Content will be loaded here -->
        <div class="nav-container">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <?php $k = 0;

            // echo "<pre>";
            // print_r($id_content_complete_by_course);
            // exit();

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
                <!-- href="#<?php echo $row['id_content']; ?>" -->
                <a class="navdesignlink <?php echo $nav;
                                        echo $activedetails; ?>" id="<?php echo $row['id_content']; ?>-tab" data-toggle="tab" role="tab" aria-controls="<?php echo $row['id_content']; ?>" aria-selected="<?php echo $ariaselected; ?>">
                  <!--  -->

                  <div class="label-title  <?php echo $pointer; ?>">
                    <input type="radio" class="tabRadio tabRadio<?php echo $row['id_content']; ?>" name="tabRadio<?php echo $row['id_content']; ?>" <?php echo (in_array($row['id_content'], $id_content_complete_by_course)) ? 'checked' : ''; ?> hidden />
                    <?php echo $row['title']; ?>
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
              <!-- aaaaaaaaaaaaaaaaaaaaa -->
              <div class="row d-flex pb-3 tak-exam tak-left" <?php echo $style; ?>>
                <div id="examClick" <?php echo $attributes; ?> class="<?php echo $ClickExam; ?> col-md-12 link cursor-pointer  btn btn-md btn-turquoise tra-g re y-hover">
                  Take Exam
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
            <div class="tab-pane fade <?php echo $activedetails; ?>" id="<?php echo $row['id_content']; ?>" role="tabpanel" aria-labelledby="<?php echo $row['id_content']; ?>-tab">
              <?php if ($row['id_type'] != "1") { ?>
                <h1><?php echo $row['title']; ?></h1>
              <?php } ?>
              <?php
              if ($row['id_type'] == "1") // video
              {
                if ($row['link']) {  ?>
                  <!-- http://www.youtube.com/embed/YbjmphKVVpA?autoplay=1 -->
                  <!-- Include the YouTube IFrame API script-->
                  <iframe class="yvideo" width="100%" height="100%" src="<?php echo $row['link']; ?>?enablejsapi=1" frameborder="0" allowfullscreen></iframe>
                <?php } else {  ?>
                  <!-- autoplay -->
                  <video width="100%" height="100%" controls controlsList="nodownload" class="video">
                    <source src="<?php echo base_url() . 'uploads/content/' . $row['file']; ?>" type="video/mp4">
                  </video>

                  <div id="current" style="display:none">0:00</div>
                  <div id="duration_content" style="display:none">0:00</div>
                  <!-- zzzzzzzzzzz <br>zzzzzzzzzzz <br>zzzzzzzzzzz <br>zzzzzzzzzzz <br>zzzzzzzzzzz <br> -->
                  <!-- activedetails -->
                <?php } ?>
              <?php }
              if ($row['id_type'] == "3") {  // pdf 
              ?> <br><b>Please refer to the following PDF for a more comprehensive understanding of the course:</b> <br><br>
                <a target="_blank" href="<?php echo base_url() . 'uploads/content/' . $row['file']; ?>"> <?php echo $row['file'] ?> </a>
              <?php }
              // echo "<div class='content'>".$row['pdf']."</div>";}
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
                <button class="dx-icon-arrowright" disabled><!--Next--></button>
              </div>

              <button class="prevtab dx-icon-arrowleft" id="content_prevtab" disabled='disabled'><!--Prev Prev--></button>
              <span class="tab-number"></span>
              <button class="nexttab dx-icon-arrowright" id="content_nexttab" disabled='disabled'><!--Next Next--></button>
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
<style>
  #examClick {
    text-decoration: none;
  }

  .tak-left {
    margin: 10px auto;
    text-decoration: none;
  }

  .tak-exam {
    display: none;
  }

  .navdesignlink {
    padding: .5rem 1rem !important;
    display: block;
  }

  .tab-content>.activedetails {
    display: block;
  }

  .nav-item a {
    cursor: unset;
  }

  .cursor-pointer {
    cursor: pointer;
  }

  .no-pointer {
    cursor: unset;
  }

  .nav-tabs .nav-link {
    cursor: pointer;
  }

  .label-title {
    display: flex;
  }

  input[type=checkbox],
  input[type=radio] {
    display: block !important;
  }

  .tabRadio {
    margin-right: 14px;
    margin-left: 0px;
  }

  .modal-body {
    display: flex;
  }

  .nav-container {
    background: #eee;
    margin-top: 0;
    max-height: calc(100% - 30px);
    overflow-y: auto;
    width: 300px;
    height: 100%;
    position: absolute;
    z-index: 0;
  }

  .tab-content {
    position: absolute;
    left: 300px;
    width: calc(100% - 300px);
    height: calc(100% - 20px) !important;
  }

  .footerpopup {
    padding: 10px 30px 10px 0px;
    background: #00ADB1;
    position: fixed;
    display: flex;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 60px;
  }

  .nav-tabs .nav-item,
  .nav-link {
    width: 100%;
  }

  .nav-tabs .nav-item.show .nav-link,
  .nav-tabs .nav-link.activedetails {
    color: #fff !important;
    background-color: #00ADB1 !important;
    border: 1px solid #00ADB1 !important;
    border-radius: 0;
  }

  .tab-content>.tab-pane {
    overflow-y: auto;
    position: fixed;
    width: calc(100% - 300px) !important;
    height: calc(100% - 110px) !important;
    padding: 0 20px;
    margin: 0;
  }

  /* .nav-tabs .nav-item { display: none;} */
  .ytp-embed:not(.ad-showing).ytp-small-mode .ytp-overflow-button .ytp-chrome-top .ytp-overflow-button {
    display: none !important;
  }

  .tab-content>.tab-pane .content {
    padding: 0 20px;
  }

  h1 {
    text-transform: capitalize;
    font-size: 1.5rem;
    padding: 20px 20px 0 20px;
  }

  .activedetails .the-title {
    font-weight: bold !important;
  }

  .the-logo {
    width: 50px;
  }

  .video-gallery {
    margin: 0;
    padding: 0;
    height: 100%;
    width: 100%;
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

  #courseModal {
    padding-right: 0 !important;
    border-radius: 0 !important;
  }

  .modal-header,
  .modal .modal-content,
  .modal-dialog,
  .login-wrap,
  .modal show {
    border-radius: 0 !important;
  }

  .modal.show .modal-dialog {
    border-radius: 0 !important;
  }

  .modal-fullscreen .modal-body,
  .modal-dialog.modal-fullscreen {
    background: #fff;
    overflow-y: auto;
    padding: 0;
    border-radius: 0;
  }

  .link {
    text-decoration: underline
  }

  .modal-dialog.modal-fullscreen {
    max-width: 100%;
  }

  .modal-fullscreen {
    width: 100%;
    height: 100%;
    margin: 0;
  }

  .modal-fullscreen .modal-content {
    height: 100%;
    border-radius: 0;
  }

  .modal-fullscreen .modal-body {
    overflow-y: auto;
    /* Enable vertical scrolling if content overflows */
  }

  .btn-danger {
    color: #fff;
    background-color: #E74C3C;
    border-color: #E74C3C;
  }

  .btn-primary {
    color: #fff;
    background-color: #2C3E50;
    border-color: #2C3E50;
  }

  .tab-pane {
    padding: 10px 0;
  }



  .footerpopup button {
    margin-top: 5px
  }

  .footerpopup button,
  .footerpopup span {
    background: none;
    margin-right: 7px;
    color: #fff;
    font-weight: bold;
    border: 0;
  }

  #loading-spinner {
    position: absolute;
    /* right: -10px; */
    right: 4px;
    /* background: #00ADB1; */
    width: 48px;
    top: 0px;
  }

  #loading-spinner>button {
    position: absolute;
    top: 36%;
    left: 36%;
    transform: translate(-50%, -50%);
    pointer-events: none;
    /* Disable pointer events on the button */
  }

  button:focus {
    outline: 0
  }

  .tab-number {
    font-size: 25px;
    width: 38px;
  }

  .footerpopup button,
  .footerpopup span {
    font: 30px / 1 DXIcons;
    margin-top: 6px;
  }

  .nextprev {
    display: flex;
    width: 150px;
    /* width: 135px; */
    margin: 0 auto;
    position: relative;
  }

  .spinner-border {
    width: 3rem;
    margin-left: -7px;
    margin-top: -2px;
    height: 3rem;
  }

  @media (max-width: 576px) {
    /* Adjust the breakpoint as needed */

    .nav {
      display: flex !important;
      flex-wrap: nowrap;
    }

    .tab-content {
      left: 0;
      width: 100%;
      top: 36px;
      height: 100% !important;
    }

    .tab-content>.tab-pane {
      width: calc(100%) !important;
      height: calc(100%) !important;
    }

    .nav-container {
      height: 44px !important;
      width: 100%;
      overflow-x: auto;
      white-space: nowrap;
    }

    .nav-tabs .nav-item {
      display: inline-block;
      vertical-align: top;
      /* Align items to the top */
    }

    .nav-link {
      padding: 10px 15px;
      /* Adjust padding as needed */
      font-size: 14px;
      /* Adjust font size as needed */
    }
  }
</style>