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
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>examcourse">Exam</a></li>

							
<?php if(isset($infocourse['id_course']) && $infocourse['id_course']){ ?>
							<li class="breadcrumb-item active" aria-current="page">
							  Course <span style="color:#00ADB1;font-size:12px"> (
							<?php echo  $infocourse['title']; ?>)
							</span></li>
<?php } ?>
                            <li class="breadcrumb-item active" aria-current="page">
								<?php if($info["id_exam"]) echo "Edit"; else echo "Add"; ?> Exam
							</li>
                        </ol>
                    </nav>
                </div> 

            </div>	 <!-- End row -->
        </div>	<!-- End container -->		
    </div>	<!-- END BREADCRUMB -->	

    <!-- CATEGORIES-2
    ============================================= -->
	<section id="about-2" class="about-section division">
 <? 
	$old_start_date=isset($info["start_date"])&&$info["start_date"]?$info["start_date"]:date('Y-m-d h:i:00');
	$middle = strtotime($old_start_date);
	$info["start_date"]= date('Y-m-d h:i:00', $middle); 
	$old_close_date=isset($info["close_date"])&&$info["close_date"]?$info["close_date"]:date('Y-m-d h:i:00');
	$middle = strtotime($old_close_date);
	$info["close_date"]= date('Y-m-d h:i:00', $middle); 
				?>
		<div class="container pt-60 pb-60">
			<div class="panell panell-primary">
				<div class="panell-heading">Exam <?php if( $infocourse['title'])echo "(".$infocourse['title'].")"; ?></div>
				<div class="panell-body">
						<div class="row d-flex pb-3">
							<div class="col-md-6">
								<div class="label">Exam Name </div>		
								<input type="text" class="input-xxlarge" name="title" id="title" value="<?= set_value("title",$info["title"]); ?>" />
							</div>
							<div class="col-md-6">
								<div class="label">course </div>	
								<select name="id_course" id="id_course"  class="input-xxlarge">
								<option value="" > - select course - </option>
								<? foreach($usercourse as $valll){ ?>
								<option value="<?= $valll["id_course"]; ?>" <?php   
									if($infocourse["id_course"] == $valll["id_course"])
									{ echo 'selected="selected"'; }   ?> ><?= $valll["title"]; ?></option>
								<? } ?>
								</select>
 							</div>
						</div>
						<div class="row d-flex pb-3">
							<div class="col-md-6">
								<div class="label">Start Date </div>			
								<div class="datetimepicker input-xxlarge">
									<input type="datetime-local" id="start_date" name="start_date" value="<?= set_value("start_date",$info["start_date"]); ?>">
									<!-- <span></span>
									<input type="time" id="start_date_time" value="<?= set_value("start_date_time",$info["start_date_time"]); ?>"> -->
								</div>
							</div>
							<div class="col-md-6">
								<div class="label">Close Date(Date of completion)</div>		
								<div class="datetimepicker input-xxlarge">
									<input type="datetime-local" id="close_date" name="close_date" value="<?= set_value("close_date",$info["close_date"]); ?>">
									<!-- <span></span>
									<input type="time" id="close_date_time" value="<?= set_value("close_date_time",$info["close_date_time"]); ?>"> -->
								</div>	
							</div>
						</div>
						<div class="row d-flex pb-3">
						    <div class="col-md-6">
								<div class="label">Time <span>(Minutes)</span></div>		
								<input  min="0" type="number" class="input-xxlarge" name="time" id="time" value="<?= set_value("time",$info["time"]); ?>" />
							</div>
							<div class="col-md-6">
								<div class="label">Numb Of questions</div>		
								<input  min="0" type="number" class="input-xxlarge" name="number_of_questions" id="number_of_questions" value="<?= set_value("number_of_questions",$info["number_of_questions"]); ?>" />
							</div>
							</div>
						<div class="row d-flex pb-3">
							<div class="col-md-6">
								<div class="label">Numb Of attempts</div>		
								<input  min="0" type="number" class="input-xxlarge" name="number_of_attempts" id="number_of_attempts" value="<?= set_value("number_of_attempts",$info["number_of_attempts"]); ?>" />
							</div>
							

							
						</div>
						<div class="row d-flex pb-3">
							<div class="col-md-12">
								<div class="label">Description</div>		
								<textarea class="ckeditor input-xxlarge" id="description" rows="5"  name="description" ><?php echo set_value("description",$info["description"]) ;?></textarea>
							</div>
						</div>
						<div class="row d-flex pb-3">
							<div class="col-md-12 d-flex align-items-center">
								<?php 
								if(isset($info['id_exam']) && $info['id_exam']) 
								  $savesubmit="Edit";
								else  
								   $savesubmit="Add";
								?>
								<input type="button"  value="<?php echo $savesubmit;?>" onClick="insertcourse()"  class="contactbutt" /> 
								<input type="hidden" class="input-xxlarge" name="id_exam" id="id_exam" value="<?php echo $info['id_exam']; ?>" />

								<br>
								<div class="dataresponse" id="dataresponse" ></div>



							</div>
						</div>
					</div>
				</div>
			</div>
		</div>




		<div class="container pt-0 pb-60">
		<form id="questionForm">
			<div class="panell panell-primary">
				<div class="panell-heading">Question </div>

				<div class="panell-body">

  <div id="questionsContainer">
<?php 
$i=1;

if(isset($question)){ // Retrieve questions
foreach ($question as $rowq) {
	$j=1;
?>
<div class="questionRow">
	<label for="question<?php echo $i;?>" class="active">Question <?php echo $i;?>:</label>
	<br><input type="text" id="question<?php echo $i;?>" name="question[<?php echo $i;?>]" class="question" required="" value="<?php echo $rowq['title'];?>">
	<button type="button" class="removeQuestion  dx-icon-trash" title="Remove Question"></button>
	<div class="answersContainer">
       <?php 
	   

	 
	 foreach ($answer[$rowq['id_question']]   as $rowa) { 
		?>
		<div class="answerRow">
			<label for="answer<?php echo $i;?>-<?php echo $j;?>" class="active">Answer <?php echo $j;?>:</label><br>
			<input type="text" id="answer<?php echo $i;?>-<?php echo $j;?>" name="answer[<?php echo $i;?>][<?php echo $j;?>]" class="answerText aaaaaaaaaaaa" placeholder="Enter answer" required="" value="<?php echo $rowa['title'];?>">
			<label class="labeltrue active">Is True Answer:</label>

			<input type="checkbox" <?php if($rowa['true']==1){ echo 'checked'; } ?> name="istrue[<?php echo $i;?>][<?php echo $j;?>]" class="radiotrue" >
			<button type="button" class="removeAnswer dx-icon-trash"></button>
		</div>
		<?php $j++; } ?>
	</div>
    <button type="button" class="addAnswer">Add Answer</button>
</div>
<?php  $i++;} }else{ ?>

    <div class="questionRow">   
      <label for="question1">Question 1:</label><br>
      <input type="text" id="question1" name="question[1]" class="question" required>
	  <div class="answersContainer">
        <div class="answerRow">
		   <label for="answer1">Answer 1:</label>
           <input type="text"  name="answer[1][1]" class="answerText" placeholder="Enter answer" required>
		   <label class="labeltrue">Is True Answer:</label><input type="checkbox"  name="istrue[1][1]" class="radiotrue" >
          <button type="button" class="removeAnswer dx-icon-trash">
			<!-- Remove -->
		</button>
        </div>
      </div>
      <button type="button" class="addAnswer">Add Answer</button>
    </div>
<?php }?>

  </div>
  <button type="button" id="addQuestion">Add Question</button>


				</div>

			 </div>



			 <div class="row d-flex pb-3">
				<div class="col-md-12 d-flex align-items-center">
	
<input type="submit"  value="Submit"   class="contactbutt" /> 

<div class="dataresponsequestion"></div>
				</div>
			</div> 
            </div>


</form>			
		</div>
    </section>
<style>
.dataresponsequestion{ 
	margin-left: 50px;
    color: red;
}
</style>
</div>	<!-- END INNER PAGE WRAPPER -->
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
    questionRow += '<input type="text" id="question' + questionCount + '" name="question[' + questionCount + ']" class="question" required>';

	questionRow += '<button type="button" class="removeQuestion  dx-icon-trash" title="Remove Question"></button>';
	

    questionRow += '<div class="answersContainer">';
    questionRow += '<div class="answerRow">';
    questionRow += '<label for="answer' + questionCount + '-1">Answer 1:</label><br>';
    questionRow += '<input type="text" id="answer' + questionCount + '-1" name="answer['+questionCount+'][1]" class="answerText aaaaaaaaaaaa" placeholder="Enter answer" required>';
	
	questionRow += '<label class="labeltrue">Is True Answer:</label><input type="checkbox"  name="istrue['+questionCount+'][1]" class="radiotrue" >';
    questionRow += '<button type="button" class="removeAnswer dx-icon-trash"></button>';
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

	answerRow += '<label class="labeltrue">Is True Answer:</label><input type="checkbox"  name="istrue[' + questionIndex + '][' + answerCount + ']" class="radiotrue" >';

    answerRow += '<button type="button" class="removeAnswer dx-icon-trash""></button></div>';
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
            alert("Error: Unable to submit questions.");
        }
    });
});




					</script>     