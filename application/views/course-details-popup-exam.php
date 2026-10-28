<?php
/* ==========================================================================
   EXAM POPUP (modern design - backup: course-details-popup-exam____28_10_2026.php)
   Included by course-details.php when the course has questions. Styles: css/pages/course.css (#examModal)
   The script below is unchanged. Kept for it and for js/timer/myjs.js: form[name=cd] input[name=disp],
   #timeExamLimit, .exam-details-popup-time, .nav-link-course, .tab-panecourse-course, #review(-tab),
   #exam-form, .answer_radio, .answerradio, #getdataanswer*, stedanswer* ids, .save-exam, .prevtab-course / .nexttab-course
   ========================================================================== */
?>
<!-- Modal -->
<div class="modal fade mx-exam" id="examModal" tabindex="-1" role="dialog" aria-labelledby="examModal" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="mx-pl-logo" aria-hidden="true"><i class="fas fa-graduation-cap"></i></span>
        <h5 class="modal-title exam-details-popup" id="examModalLabel">
          <span class="mx-ex-title"><small>Final exam</small><?php echo $coursedetail['coursetitle']; ?></span>

          <?php
          $examrxist = '';
          if ($id_exam && isset($exam['id_user_exam']) &&  count($exam_history)) {
            $examrxist = 'display:none';
          } ?>
          <form name="cd" class="exam-details-popup-time" style="<?php echo $examrxist; ?>">
            <label><i class="far fa-clock"></i> <span>Remaining time</span></label>
            <input type="hidden" name="" id="timeExamLimit" value="<?php echo $exam['time']; ?>">
            <input name="disp" type="text" class="clock-time" id="txt" value="00:00" size="5" readonly="true">
          </form>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fas fa-xmark"></i></span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        if ($id_exam && isset($exam['id_user_exam']) &&  count($exam_history)) { ?>
          <section class="tab-content-exam">
            <?php
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
            <span class="mx-pl-side-label">Questions · <?php echo count($questions); ?></span>
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
                      <input type="radio" class="tabRadio-course" name="tabRadio-course<?php echo $question['id_question']; ?>" hidden />
                      <span class="mx-ex-qnum"><?php echo $k; ?></span>
                      <span class="mx-ex-qtxt"><?php echo strip_tags(htmlspecialchars_decode($question['question'])); ?></span>
                    </div>
                  </a>
                </li>
              <?php }   ?>
              <li class="nav-item-course">
                <div class="row d-flex pb-3 tak-left" style=" display:block !important">
                  <a class="col-md-12 link cursor-pointer mx-ex-review-btn" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="review">
                    <i class="fas fa-list-check"></i> Review &amp; submit
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
                <span class="mx-ex-step">Question <?php echo $k; ?> of <?php echo count($questions); ?></span>
                <h1 class="question-title"><?php echo strip_tags(htmlspecialchars_decode($question['question'])); ?></h1>
                <input type="text" name="question[<?php echo $question['id_question']; ?>]" value="<?php echo $question['id_question']; ?>" hidden>
                <?php
                echo "<div class='content'>"; ?>
                <?php foreach ($question['answers'] as $index => $answer) :
                  $id_exam = $question['id_exam']; ?>
                  <label class="label-question">
                    <input id="stedanswer<?php echo $question['id_question']; ?>_<?php echo $answer['id_answer']; ?>" class="answer_radio" type="radio" value="<?php echo $answer['id_answer']; ?>">
                    <div class="plabel"><?php echo ($answer['answertitle']) ?></div>
                  </label>
                <?php endforeach; ?>
                <?php echo "</div>"; ?>
              </div>
            <?php } ?>

            <div class="tab-panecourse-course fade" id="review" role="tabpanecoursel" aria-labelledby="review-tab">
              <div class="mx-ex-review-head">
                <span class="mx-ex-step">Review &amp; submit</span>
                <h1>Check your answers</h1>
                <p>Your selected answers are highlighted. Go back with the arrows to change one, then press <b>Save</b>.</p>
              </div>
              <form action="post" action="" id="exam-form">
                <?php $k = 0;
                foreach ($questions as $key => $question) {
                  $active = '';
                  $k++; ?>
                  <div class="aaa-tab-panecourse-course" id="<?php echo $question['id_question']; ?>">
                    <h1 class="question-title-finish"><span class="mx-ex-qnum"><?php echo $k; ?></span> <?php echo strip_tags(htmlspecialchars_decode($question['question'])); ?></h1>
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

              </form>
            </div>
            <!-- End Content will be loaded here -->
            <div class="footerpopup">
              <div class="nextprev">
                <button class="prevtab-course dx-icon-arrowleft" aria-label="Previous question"><i class="fas fa-arrow-left"></i></button>
                <span class="tab-number-exam"></span>
                <button class="nexttab-course dx-icon-arrowright" aria-label="Next question"><i class="fas fa-arrow-right"></i></button>
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
<script>
  // phones: the question list is a horizontal strip - keep the current question in view
  (function () {
    var box = document.querySelector('#examModal .nav-container-course');
    if (!box || !window.MutationObserver) return;
    new MutationObserver(function () {
      var on = box.querySelector('.nav-link-course.active');
      if (on && box.scrollWidth > box.clientWidth) box.scrollTo({ left: on.parentNode.offsetLeft - 10, behavior: 'smooth' });
    }).observe(box, { subtree: true, attributes: true, attributeFilter: ['class'] });
  })();
</script>
