<!-- Modal -->
<div class="modal fade" id="examModal" tabindex="-1" role="dialog" aria-labelledby="examModal" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title exam-details-popup" id="examModalLabel">
          <?php echo $coursedetail['coursetitle']; ?>

          <?php
          $examrxist = '';
          if ($id_exam && isset($exam['id_user_exam']) &&  count($exam_history)) {
            $examrxist = 'display:none';
          } ?>
          <form name="cd" class="exam-details-popup-time" style="<?php echo $examrxist; ?>">
            <label>Remaining Time : </label>
            <input type="hidden" name="" id="timeExamLimit" value="<?php echo $exam['time']; ?>">
            <input name="disp" type="text" class="clock-time" id="txt" value="00:00" size="5" readonly="true">
          </form>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        // echo "xxxxxxxxxxxxxxxxxxxxxxxxx";
        // echo  "<pre>";
        // print_r($user_exam);
        // echo "-----------------------------";
        // echo  "<pre>";
        // print_r($exam_history);
        // echo  "<br><pre>-------------";
        // print_r($exam_history);
        //count($user_exam) == 0 &&

        if ($id_exam && isset($exam['id_user_exam']) &&  count($exam_history)) { ?>
          <section class="tab-content-exam">
            <?php

            // echo "<pre>";
            // print_r($exam_history);
            // exit(); 

            $data['id_course'] = $id_course;
            $data['id_exam'] = $id_exam;
            $data['examdetails'] = $examdetails;
            $data['exam_history'] = $exam_history;
            $this->load->view('exam_result', $data);
            ?>
          </section>
        <?php
        } else {
        ?>
          <!-- start  question will be loaded here -->
          <div class="nav-container-course">
            <ul class="nav nav-tabs-course" id="myTab" role="tablist">
              <?php $k = 0;
              foreach ($questions as $key => $question) {
                $ariaselected = 'false';
                $active = '';
                if ($k == 0) {
                  $ariaselected = 'true';
                  $active = 'active';
                }
                $k++; ?>
                <li class="nav-item-course">
                  <a class="nav-link-course <?php echo $active; ?>" id="<?php echo $question['id_question']; ?>-tab" data-toggle="tab" href="#<?php echo $question['id_question']; ?>" role="tab" aria-controls="<?php echo $question['id_question']; ?>" aria-selected="<?php echo $ariaselected; ?>">
                    <div class="label-title cursor-pointer ">
                      <input type="radio" class="tabRadio-course" name="tabRadio-course<?php echo $question['id_question']; ?>" <?php // echo  (in_array($question['id_question'], $id_content_complete_by_course)) ? 'checked' : ''; 
                                                                                                                                ?> hidden />
                      <?php echo strip_tags(htmlspecialchars_decode($question['question'])); ?>
                    </div>
                  </a>
                </li>
              <?php }   ?>
              <li class="nav-item-course">
                <div class="row d-flex pb-3 tak-left" style=" display:block !important">
                  <a class="col-md-12 link cursor-pointer  btn btn-md btn-turquoise tra-g re y-hover" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="review">
                    Review
                  </a>
                </div>
              </li>
            </ul>
          </div>

          <div class="tab-content" id="myTabContent">

            <?php $k = 0;
            foreach ($questions as $key => $question) {
              $active = '';
              if ($k == 0) {
                $active = 'show active';
              }
              $k++; ?>
              <div class="tab-panecourse-course fade <?php echo $active; ?>" id="<?php echo $question['id_question']; ?>" role="tabpanecoursel" aria-labelledby="<?php echo $row['id_content']; ?>-tab">
                <h1 class="question-title"><?php echo strip_tags(htmlspecialchars_decode($question['question'])); ?></h1>
                <input type="text" name="question[<?php echo $question['id_question']; ?>]" value="<?php echo $question['id_question']; ?>" hidden>
                <?php
                echo "<div class='content'>"; ?>
                <?php foreach ($question['answers'] as $index => $answer) :
                  $id_exam = $question['id_exam']; ?>
                  <label class="label-question">
                    <input id="stedanswer<?php echo $question['id_question']; ?>_<?php echo $answer['id_answer']; ?>" class="answer_radio" type="radio" value="<?php echo $answer['id_answer']; ?>">
                    <div class="plabel"><?php echo ($answer['answertitle']) ?></div>
                  </label><br>
                <?php endforeach; ?>
                <br>
                <?php echo "</div>"; ?>
              </div>
            <?php } ?>

            <div class="tab-panecourse-course fade" id="review" role="tabpanecoursel" aria-labelledby="review-tab">
              <!-- show active -->
              <form action="post" action="" id="exam-form">
                <?php $k = 0;
                foreach ($questions as $key => $question) {
                  $active = '';
                  $k++; ?>
                  <div class="aaa-tab-panecourse-course" id="<?php echo $question['id_question']; ?>">
                    <h1 class="question-title-finish"><?php echo strip_tags(htmlspecialchars_decode($question['question'])); ?></h1>
                    <!-- <input  name="question[<?php echo $question['id_question']; ?>]"
                                value="<?php echo $question['id_question']; ?>" hidden> -->
                    <?php
                    echo "<div class='content'>"; ?>
                    <?php foreach ($question['answers'] as $index => $answer) : ?>
                      <label class="label-question-finish">

                        <input id="<?php echo $answer['id_answer']; ?>" value="<?php echo $answer['id_answer']; ?>" type="radio" class="answerradio answer_radio">

                        <div class="plabel"><?php echo ($answer['answertitle']) ?></div>
                      </label>
                    <?php endforeach; ?>
                    <?php echo "</div>"; ?>
                  </div>

                  <input id="getdataanswer<?php echo $question['id_question']; ?>" name="answer[<?php echo $question['id_question']; ?>]" type="hidden" value="" class="getdataanswer answer_radio">

                <?php } ?>

                <input type="hidden" name="id_course" value="<?php echo $id_course; ?>">
                <input type="hidden" name="id_exam" value="<?php echo $id_exam; ?>">
                <div class="loader_button">
                  <input type="button" value="Save" class="save-exam desgin-button">
                  <div class="loader_loader"></div>
                </div>
                <input type="hidden" name="count_questions" id="count_questions" value="<?php echo count($questions); ?>">

              </form> <br><br><br> <br><br><br> <br><br><br>
            </div>
            <!-- End Content will be loaded here -->
            <div class="footerpopup">
              <div class="nextprev">
                <button class="prevtab-course dx-icon-arrowleft"><!--Prev--></button>
                <span class="tab-number-exam"></span>
                <button class="nexttab-course dx-icon-arrowright"><!--Next--></button>
              </div>
            </div>
          </div>
        <?php } ?>









      </div>
    </div>
  </div>
</div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script type="text/javascript">
  $(document).ready(function() {
    $('input[type="radio"]').change(function() {
      // Disable and hide all radio buttons with the class "answerradio"
      $('.answerradio').prop("disabled", true).hide();

      // Uncheck all radio buttons with the class "answerradio"
      $('input[type="radio"].answerradio').prop("checked", false);
      $('input[type="radio"].answerradio').removeAttr("checked");


      $('input[type="radio"].getdataansweranswerradio').prop("checked", false);
      $('input[type="radio"].getdataansweranswerradio').removeAttr("checked");
      // Check other radio buttons based on the checked ones with ID starting with "stedanswer"
      setTimeout(function() {
        $('input[type="radio"][id^="stedanswer"]:checked').each(function() {
          var id = $(this).attr("id");
          var dataAfterStedanswer = id.substring("stedanswer".length);

          // Explode dataAfterStedanswer into id_answers and id_question
          var explodedData = dataAfterStedanswer.split("_");
          var id_question = explodedData[0];
          var id_answers = explodedData[1];

          // Display the extracted parts in an alert (for testing purposes)
          // alert("id_answers: " + id_answers + ", id_question: " + id_question);

          // Perform some action on an element with ID stored in the variable `id_answers`
          $("#" + id_answers).prop("checked", true);

          // Perform another action on an element with ID formed using `id_question`
          $("#getdataanswer" + id_question).val(id_answers);


        });
      }, 100);
    });


    $(".save-exam").click(function() {
      $(".loader_loader").show(); // Show the loader
      //$(this).removeClass("save-exam"); // Remove the class or disable the button
      $(this).prop("disabled", true); // Optionally disable the button

      // Add click event listener to the Save button
      // Perform your save operation here
      // For example:
      var selectedAnswers = $('.content input[type="radio"]:checked').map(function() {
        return $(this).val();
      }).get();
      console.log(selectedAnswers); // Output selected answers
      /////////////////////////////////

      // Serialize form data
      var formData = $("#exam-form").serialize();
      console.log(formData);

      // Send form data via AJAX
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>home/save_exam", // Specify the URL to handle form data on the server
        data: formData,
        success: function(response) {
          //$(".tab-content").css("width", "100%");
          // $(".tab-content").css("background", "#fff");
          // $(".tab-content").css("left", "0");
          // $(".nav-item-course").css("display", "none");
          // $(".footerpopup").css("display", "none");
          // $('#review').html(response); // assuming response is HTML content

          $(".modal-body").css("display", "revert");
          $('.result-finish-tab-panecourse-course').css("width", "100%");
          $('.modal-body').html(response); // assuming response is HTML content
          $(".timeExamLimit").val("1");

          $(".exam-details-popup-time").css("display", "none");
        },
        error: function(xhr, status, error) {
          // Handle errors here
          console.error(xhr.responseText);
        }
      });
    });
  });

  function bootstrapTabControlcourse() {
    var itemscourse = $('.nav-link-course'),
      panecourse = $('.tab-panecourse-course'),
      totalTabscourse = itemscourse.length;

    function updateTabNumbercourse(currentIndexcourse) {
      $('.tab-number-exam').text((currentIndexcourse + 1) + '/' + totalTabscourse);
    }

    // Function to show the content of the clicked tab
    function showTabContent(index) {
      // Remove 'active' class from all tabs and panecourses
      itemscourse.removeClass('active');
      panecourse.removeClass('show active');

      // Add 'active' class to the clicked tab and corresponding panecourse
      $(itemscourse[index]).addClass('active');
      $(panecourse[index]).addClass('show active');

      // Get the ID of the clicked panecourse
      //ayman  var panecourseId = $(panecourse[index]).attr('id');
      //ayman   $("#"+panecourseId+"-tab  .tabRadio-course").attr('checked', true);
      //    user_content_data(panecourseId)

      // Update the tab number display
      updateTabNumbercourse(index);
    }

    // Handle click on tab links
    itemscourse.on('click', function() {
      // Get the index of the clicked tab
      var index = itemscourse.index($(this));
      showTabContent(index);
    });

    $("#review-tab").click(function() { // click to start exam
      var itemscourse = $('.nav-link-course'),
        panecourse = $('.tab-panecourse-course'),
        totalTabscourse = itemscourse.length;
      var currentIndexcourse = totalTabscourse;
      showTabContent(currentIndexcourse - 1);

      $(".nexttab-course").addClass("fade");
      $(".prevtab-course").removeClass("fade");
      $(".nav-link-course").removeClass("active");
      $(".tab-panecourse-course.fade").removeClass("show active");
      $("#review").addClass("show active");
    });



    // Next
    $('.nexttab-course').on('click', function() {
      $(".prevtab-course").removeClass("fade");
      var currentIndexcourse = itemscourse.index($('.nav-link-course.active'));

      if (currentIndexcourse < totalTabscourse - 1 && currentIndexcourse >= 0) {
        showTabContent(currentIndexcourse + 1);
      } else
      if (currentIndexcourse == totalTabscourse - 1) {
        $(".tab-panecourse-course.fade").removeClass("show active");
        $("#review").addClass("show active");
        $(".nexttab-course").addClass("fade");
        $(".nav-link-course").removeClass("active");
      }

    });

    // Prev
    $('.prevtab-course').on('click', function() {

      $(".nexttab-course").removeClass("fade");
      var currentIndexcourse = itemscourse.index($('.nav-link-course.active'));
      // alert(currentIndexcourse)
      if (currentIndexcourse == "-1") {
        var count_questions = $("#count_questions").val();
        currentIndexcourse = count_questions;
      }

      if (currentIndexcourse != 0) {
        showTabContent(currentIndexcourse - 1);
        if (currentIndexcourse == 1) {
          $(".prevtab-course").addClass("fade");
        }
      } else {
        $(".prevtab-course").addClass("fade");
      }


    });

    // Initial tab number display
    updateTabNumbercourse(itemscourse.index($('.nav-link-course.active')));
  }

  // Initialize the tab control logic
  $(document).ready(function() {
    bootstrapTabControlcourse();
  });
</script>
<style>
  .desgin-button {
    background: #00ADB1;
    border-radius: 5px;
    color: #fff;
    font-size: 1.1em;
    border: none;
    margin-left: 20px;
    width: 200px;
    min-width: 100px;
    outline: none;
    font-weight: 600;
    padding: 0.6em 1em;
    transition: 0.5s all;
    -webkit-transition: 0.5s all;
  }


  .loader_button {
    position: relative;
    width: 200px;
  }

  .loader_loader {
    display: none;
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #ff4500;
    width: 20px;
    height: 20px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
    position: absolute;
    top: 13%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  @-webkit-keyframes spin {
    0% {
      -webkit-transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
    }
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>
<style>
  .clock-time {
    border: none;
    background-color: transparent;
    color: white;
    font-weight: bold;
    font-size: 25px;
    outline: 0;
  }

  .exam-details-popup {
    width: 100%;
  }

  .exam-details-popup-time {
    float: right;
  }

  <?php if ($id_exam) { ?>.tab-content-exam {
    width: 100%;
    background: #fff;
    left: 0;
  }

  .footerpopup {
    display: none;
  }

  <?php } ?>.tabRadio-course {
    margin-right: 14px;
    margin-left: 0px;
  }

  .modal-body {
    display: flex;
  }

  .nav-container-course {
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
    overflow-y: scroll;
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

  .nav-tabs-course .nav-item-course,
  .nav-link-course {
    width: 100%;
  }

  .nav-tabs-course .nav-item-course.show .nav-link-course,
  .nav-tabs-course .nav-link-course.active {
    color: #fff !important;
    background-color: #00ADB1 !important;
    border: 1px solid #00ADB1 !important;
    border-radius: 0;
  }

  .tab-content form>.tab-panecourse-course {
    overflow-y: auto;
    /* position: fixed;
    width: calc(100% - 300px) !important;
    height: calc(100% - 110px) !important; */
    position: relative;
    width: calc(100%) !important;
    height: calc(100%) !important;
    padding: 0 20px;
    margin: 0;
    background: #fff;
  }

  .tab-panecourse-course.fade:not(.show) {
    display: none;
  }

  .tab-content form>.tab-panecourse-course .content {
    padding: 0 20px;
  }


  .active .the-title {
    font-weight: bold !important;
  }

  .the-logo {
    width: 50px;
  }

  .course-detail {
    position: relative !important;
    padding: 0 !important;
  }

  #examModal {
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

  .cursor-pointer {
    cursor: pointer;
  }

  .footerpopup button {
    margin-top: 5px
  }

  .footerpopup button,
  .footerpopup span {
    float: right;
    background: none;
    margin-right: 7px;
    color: #fff;
    font-weight: bold;
    border: 0;
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

  .tab-panecourse-course {
    padding: 10px 0;
  }

  .nav-tabs-course .nav-link-course {
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
  }

  .nav-link-course {
    display: block;
    padding: 0.5rem 1rem;
  }

  #scrollUp {
    bottom: 50px;
  }

  .save-exam {

    background: #00ADB1;
    border-radius: 5px;
    color: #fff;
    font-size: 1.1em;
    border: none;
    margin-left: 20px;
    width: 200px;
    min-width: 100px;
    outline: none;
    font-weight: 600;
    padding: 0.6em 1em;
    transition: 0.5s all;
    -webkit-transition: 0.5s all;
  }



  h1 p,
  .question-title,
  .question-title p {
    color: #000 !important;
    margin-bottom: 30px;
  }

  .question-title-finish,
  .question-title-finish p {
    color: #000 !important;
  }

  .label-question-finish {
    display: flex;
    margin-left: 40px;
    letter-spacing: normal;
  }

  .label-question-finish .plabel {
    margin: 0 0 0 10px;
  }

  .label-question {
    display: flex;
    margin-left: 50px;
    letter-spacing: normal;
  }

  .label-question .plabel {
    margin: 0 0 0 10px;
  }

  .label-title {
    display: flex;
  }

  .tak-left {
    text-decoration: none;
    text-align: center;
  }

  .tak-left a {
    text-decoration: none;
    width: 125px;
    margin: 10px auto;
  }

  @media (max-width: 576px) {

    /* Adjust the breakpoint as needed */
    .nav-container-course {
      display: none;
    }

    .tab-content {
      left: 0;
      width: calc(100%);
      height: calc(100%);
    }
  }
</style>