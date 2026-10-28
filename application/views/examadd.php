<?php
/* ==========================================================================
   EXAM BUILDER - add / edit an exam and its questions
   (modern design - backup: examadd____28_10_2026.php)
   Routes: editexam · editexam/<id_exam> · editexam/<id_course>/addincourse
   Saving: insertcourse() in template.php -> POST addexam (exam details)
           #questionForm submit (below)  -> POST course/saveQuestions
   ========================================================================== */
$old_start_date = isset($info["start_date"]) && $info["start_date"] ? $info["start_date"] : date('Y-m-d h:i:00');
$middle = strtotime($old_start_date);
$info["start_date"] = date('Y-m-d h:i:00', $middle);
$old_close_date = isset($info["close_date"]) && $info["close_date"] ? $info["close_date"] : date('Y-m-d h:i:00');
$middle = strtotime($old_close_date);
$info["close_date"] = date('Y-m-d h:i:00', $middle);

$mx_is_edit   = (isset($info['id_exam']) && $info['id_exam']);
$mx_course_tt = (isset($infocourse['title']) && $infocourse['title']) ? $infocourse['title'] : '';
$mx_val = function ($k) use ($info) {
	return isset($info[$k]) ? $info[$k] : '';
};

$mx_crumbs = array(
	array('label' => 'Home', 'url' => base_url()),
	array('label' => 'Exams', 'url' => base_url() . 'examcourse'),
);
if ($mx_course_tt) {
	$mx_crumbs[] = array('label' => $mx_course_tt);
}
$mx_crumbs[] = array('label' => ($mx_is_edit ? 'Edit' : 'Add') . ' exam');

$mx_stats = array();
if ($mx_is_edit) {
	if (isset($question) && is_array($question)) {
		$mx_stats[] = array('icon' => 'fas fa-circle-question', 'value' => count($question), 'label' => 'Questions');
	}
	if ($mx_val('time') !== '') {
		$mx_stats[] = array('icon' => 'fas fa-stopwatch', 'value' => (int)$mx_val('time') . ' min', 'label' => 'Duration');
	}
	if ($mx_val('number_of_attempts') !== '') {
		$mx_stats[] = array('icon' => 'fas fa-rotate', 'value' => (int)$mx_val('number_of_attempts'), 'label' => 'Attempts');
	}
}

$this->load->view('include/_page_hero', array(
	'ph_title'   => $mx_is_edit ? 'Edit exam' : 'New exam',
	'ph_eyebrow' => $mx_course_tt ? $mx_course_tt : 'Exam builder',
	'ph_text'    => 'Set the schedule, duration and attempts, then write the questions and tick the correct answers.',
	'ph_crumbs'  => $mx_crumbs,
	'ph_stats'   => $mx_stats,
));
?>

<div class="mx-inner mx-exam mx-exam-builder">
	<section class="mx-exam-main">
		<div class="container">

			<!-- STEP 1 - EXAM DETAILS (saved by insertcourse() in template.php) -->
			<div class="mx-exam-card mx-form mx-exam-form" data-reveal>
				<div class="mx-exam-card-head">
					<span class="mx-exam-step">1</span>
					<div class="mx-exam-card-ttl">
						<h2>Exam details</h2>
						<p><?php echo $mx_course_tt ? htmlspecialchars($mx_course_tt, ENT_QUOTES, 'UTF-8') . ' &middot; ' : ''; ?>Name, course, schedule and rules of the exam.</p>
					</div>
				</div>

				<div class="mx-form-grid">
					<div class="mx-field">
						<label for="title">Exam name</label>
						<div class="mx-input"><i class="fas fa-pen" aria-hidden="true"></i>
							<input type="text" class="input-xxlarge" name="title" id="title" placeholder="e.g. Final assessment" value="<?= set_value("title", $mx_val("title")); ?>" />
						</div>
					</div>
					<div class="mx-field">
						<label for="id_course">Course</label>
						<div class="mx-input mx-input-select"><i class="fas fa-book-open" aria-hidden="true"></i>
							<select name="id_course" id="id_course" class="input-xxlarge">
								<option value=""> - select course - </option>
								<?php foreach ($usercourse as $valll) { ?>
									<option value="<?= $valll["id_course"]; ?>" <?php
										if (isset($infocourse["id_course"]) && $infocourse["id_course"] == $valll["id_course"]) {
											echo 'selected="selected"';
										} ?>><?= $valll["title"]; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="mx-field">
						<label for="start_date">Start date</label>
						<div class="mx-input datetimepicker input-xxlarge"><i class="far fa-calendar" aria-hidden="true"></i>
							<input type="datetime-local" id="start_date" name="start_date" value="<?= set_value("start_date", $info["start_date"]); ?>">
						</div>
					</div>
					<div class="mx-field">
						<label for="close_date">Close date <small>(date of completion)</small></label>
						<div class="mx-input datetimepicker input-xxlarge"><i class="far fa-calendar-check" aria-hidden="true"></i>
							<input type="datetime-local" id="close_date" name="close_date" value="<?= set_value("close_date", $info["close_date"]); ?>">
						</div>
					</div>

					<div class="mx-exam-trio mx-field-full">
						<div class="mx-field">
							<label for="time">Duration <small>(minutes)</small></label>
							<div class="mx-input"><i class="fas fa-stopwatch" aria-hidden="true"></i>
								<input min="0" type="number" class="input-xxlarge" name="time" id="time" placeholder="30" value="<?= set_value("time", $mx_val("time")); ?>" />
							</div>
						</div>
						<div class="mx-field">
							<label for="number_of_questions">Number of questions</label>
							<div class="mx-input"><i class="fas fa-circle-question" aria-hidden="true"></i>
								<input min="0" type="number" class="input-xxlarge" name="number_of_questions" id="number_of_questions" placeholder="10" value="<?= set_value("number_of_questions", $mx_val("number_of_questions")); ?>" />
							</div>
						</div>
						<div class="mx-field">
							<label for="number_of_attempts">Number of attempts</label>
							<div class="mx-input"><i class="fas fa-rotate" aria-hidden="true"></i>
								<input min="0" type="number" class="input-xxlarge" name="number_of_attempts" id="number_of_attempts" placeholder="1" value="<?= set_value("number_of_attempts", $mx_val("number_of_attempts")); ?>" />
							</div>
						</div>
					</div>

					<div class="mx-field mx-field-full">
						<label for="description">Description</label>
						<div class="mx-input mx-input-area"><i class="fas fa-align-left" aria-hidden="true"></i>
							<textarea class="ckeditor input-xxlarge" id="description" rows="5" name="description" placeholder="Instructions shown to the learners before they start"><?php echo set_value("description", $mx_val("description")); ?></textarea>
						</div>
					</div>
				</div>

				<div class="mx-form-actions">
					<?php
					if (isset($info['id_exam']) && $info['id_exam'])
						$savesubmit = "Edit";
					else
						$savesubmit = "Add";
					?>
					<span class="mx-exam-submit">
						<input type="button" value="<?php echo ($savesubmit == "Edit") ? "Save changes" : "Create exam"; ?>" onClick="insertcourse()" class="contactbutt" />
						<i class="fas fa-check" aria-hidden="true"></i>
					</span>
					<input type="hidden" class="input-xxlarge" name="id_exam" id="id_exam" value="<?php echo $mx_val('id_exam'); ?>" />
					<span class="mx-form-note"><i class="fas fa-circle-info" aria-hidden="true"></i><?php echo $mx_is_edit ? 'Saves the changes to the exam details.' : 'Save the details first, then add the questions below.'; ?></span>
				</div>
				<div class="dataresponse" id="dataresponse" role="status" aria-live="polite"></div>
			</div>

			<!-- STEP 2 - QUESTIONS (saved by the #questionForm submit handler below) -->
			<form id="questionForm" class="mx-exam-card mx-exam-qcard" data-reveal>
				<div class="mx-exam-card-head">
					<span class="mx-exam-step">2</span>
					<div class="mx-exam-card-ttl">
						<h2>Questions &amp; answers</h2>
						<p>Write each question, add its possible answers and tick the correct one.</p>
					</div>
				</div>

				<div id="questionsContainer">
<?php
$i = 1;

if (isset($question)) { // Retrieve questions
	foreach ($question as $rowq) {
		$j = 1;
?>
					<div class="questionRow">
						<label for="question<?php echo $i; ?>" class="active">Question <?php echo $i; ?>:</label>
						<br><input type="text" id="question<?php echo $i; ?>" name="question[<?php echo $i; ?>]" class="question" placeholder="Type the question" required="" value="<?php echo $rowq['title']; ?>">
						<button type="button" class="removeQuestion  dx-icon-trash" title="Remove Question"></button>
						<div class="answersContainer">
							<?php foreach ($answer[$rowq['id_question']] as $rowa) { ?>
								<div class="answerRow">
									<label for="answer<?php echo $i; ?>-<?php echo $j; ?>" class="active">Answer <?php echo $j; ?>:</label><br>
									<input type="text" id="answer<?php echo $i; ?>-<?php echo $j; ?>" name="answer[<?php echo $i; ?>][<?php echo $j; ?>]" class="answerText aaaaaaaaaaaa" placeholder="Enter answer" required="" value="<?php echo $rowa['title']; ?>">
									<label class="labeltrue active">Correct</label>
									<input type="checkbox" <?php if ($rowa['true'] == 1) { echo 'checked'; } ?> name="istrue[<?php echo $i; ?>][<?php echo $j; ?>]" class="radiotrue" title="Correct answer">
									<button type="button" class="removeAnswer dx-icon-trash" title="Remove answer"></button>
								</div>
							<?php $j++;
							} ?>
						</div>
						<button type="button" class="addAnswer">Add Answer</button>
					</div>
<?php $i++;
	}
} else { ?>
					<div class="questionRow">
						<label for="question1">Question 1:</label><br>
						<input type="text" id="question1" name="question[1]" class="question" placeholder="Type the question" required>
						<div class="answersContainer">
							<div class="answerRow">
								<label for="answer1">Answer 1:</label>
								<input type="text" id="answer1" name="answer[1][1]" class="answerText" placeholder="Enter answer" required>
								<label class="labeltrue">Correct</label><input type="checkbox" name="istrue[1][1]" class="radiotrue" title="Correct answer">
								<button type="button" class="removeAnswer dx-icon-trash" title="Remove answer"></button>
							</div>
						</div>
						<button type="button" class="addAnswer">Add Answer</button>
					</div>
<?php } ?>
				</div>
				<button type="button" id="addQuestion">Add Question</button>

				<div class="mx-form-actions mx-exam-qactions">
					<span class="mx-exam-submit">
						<input type="submit" value="Save questions" class="contactbutt" />
						<i class="fas fa-check" aria-hidden="true"></i>
					</span>
					<div class="dataresponsequestion" role="status" aria-live="polite"></div>
				</div>
			</form>

		</div>
	</section>
</div> <!-- END INNER PAGE WRAPPER -->
<script>
// Get the datetime-local input element
var input = document.getElementById("start_date");
// Remove the seconds part from the input field
input.addEventListener("focus", function() {
    // Check if the value contains seconds
    if (input.value.includes(":")) {
        // Remove seconds by keeping only the date and hour:min part
        input.value = input.value.split(":").slice(0, 2).join(":");
    }
});


// // Add question
$("#addQuestion").click(function() {
    var questionCount = $(".questionRow").length + 1;
    var questionRow = '<div class="questionRow">';
    questionRow += '<label for="question' + questionCount + '">Question ' + questionCount + ':</label><br>';
    questionRow += '<input type="text" id="question' + questionCount + '" name="question[' + questionCount + ']" class="question" placeholder="Type the question" required>';

	questionRow += '<button type="button" class="removeQuestion  dx-icon-trash" title="Remove Question"></button>';


    questionRow += '<div class="answersContainer">';
    questionRow += '<div class="answerRow">';
    questionRow += '<label for="answer' + questionCount + '-1">Answer 1:</label><br>';
    questionRow += '<input type="text" id="answer' + questionCount + '-1" name="answer['+questionCount+'][1]" class="answerText aaaaaaaaaaaa" placeholder="Enter answer" required>';

	questionRow += '<label class="labeltrue">Correct</label><input type="checkbox"  name="istrue['+questionCount+'][1]" class="radiotrue" title="Correct answer">';
    questionRow += '<button type="button" class="removeAnswer dx-icon-trash" title="Remove answer"></button>';
    questionRow += '</div></div>';
    questionRow += '<button type="button" class="addAnswer">Add Answer</button>';
    questionRow += '</div>';
    $("#questionsContainer").append(questionRow);
});

////////////////////////////////////



 	// Add answer to a question
	 $(document).on("click", ".addAnswer", function() {
    var $answersContainer = $(this).prev(".answersContainer");
    var answerCount = $answersContainer.find(".answerRow").length + 1;
    var questionIndex = $(this).closest(".questionRow").index() + 1;
    var answerRow = '<div class="answerRow">';
    answerRow += '<label for="answer' + questionIndex + '-' + answerCount + '">Answer ' + answerCount + ':</label><br>';
    answerRow += '<input type="text" id="answer' + questionIndex + '-' + answerCount + 'sssssssssssss" name="answer[' + questionIndex + '][' + answerCount + ']"  class="answerText" placeholder="Enter answer" required>';

	answerRow += '<label class="labeltrue">Correct</label><input type="checkbox"  name="istrue[' + questionIndex + '][' + answerCount + ']" class="radiotrue" title="Correct answer">';

    answerRow += '<button type="button" class="removeAnswer dx-icon-trash" title="Remove answer"></button></div>';
    $answersContainer.append(answerRow);
});

// Remove answer
$(document).on("click", ".removeAnswer", function() {
    $(this).parent().remove();
});

// Remove entire question block
$(document).on("click", ".removeQuestion", function() {
    $(this).closest(".questionRow").remove(); // New functionality to remove the question block
});

$("#questionForm").submit(function(e) {
    e.preventDefault();


	var id_exam = $("#id_exam").val();
    var id_course = $("#id_course").val(); // Ensure you're calling val() as a function


    var formData = $(this).serializeArray();

    // Append id_course to the formData
    formData.push({ name: "id_exam", value: id_exam });
	formData.push({ name: "id_course", value: id_course });


    $.ajax({
        type: "POST",
        url: base_url+"course/saveQuestions",
        data: formData,
        success: function(response) {
            if(response)
            $('.dataresponsequestion').html("Information was updated successfully");

            // Optional: Reset form and adjust UI as needed after success
        },
        error: function() {
            mxAlert('Unable to submit the questions. Please try again.', 'error');
        }
    });
});




					</script>
