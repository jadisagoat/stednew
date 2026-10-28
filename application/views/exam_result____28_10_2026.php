<!-- <h1 class="<?php echo $exam['id_exam']; ?>"><?php echo $exam['examtitle']; ?></h1> -->
<input type="hidden" name="id_exam" value="<?php echo $exam['id_exam']; ?>" id="id_exam">
<?php
// echo "<pre>";
// print_r($exam_history);

// echo "-------------------";


// exit();

if (isset($exam_history) && isset($exam_history['0']) && isset($exam_history['0']['score'])) {

?>
    <div class="footerpopup-finish">
        <div class="nextprev-finish"> Score :
            <?php
            echo ($exam_history['0']['score']) . "/100";
            if ($exam_history['0']['score'] / 100 < 0.5) {
                echo "<span> Fail</span>";
            } else {
                // echo "<span> Pass</span><a href='" . base_url() . "uploads/exam_history/" . $certificate . " download >Download Certificate</a>";
                $certificate = $exam_history['0']['certificate'];
                $downloadLink = base_url() . 'uploads/exam_history/' . $certificate;
                // Display the link to the user
                echo "<span> Pass</span>";
                if (isset($certificate) && $certificate) {
                    echo '<a   style="cursor:pointer" href="' . $downloadLink . '" download>
                    						<span class="btn btn-md btn-turquoise tra-g re y-hover">Get Certificate</span>
                    					</a>';
                    // $certificate_pdf = str_replace('.pdf', '', $certificate);
                    // echo '<a style="cursor:pointer" href="' . base_url() . '/home/download_certificate/' .  $certificate_pdf . '" download>
                    //                     <span class="btn btn-md btn-turquoise tra-g re y-hover">Get Certificate</span>
                    //                 </a>';
                } else { ?>
                    <span id='delete_user_exam'>Retry</span>
                <?php }
            }

            // if number attempt not 0 and Cutting time "date end"
            // echo $exam_history['0']['score'] . "//<br>" .
            //     $exam_history['0']["number_attempts"] . "<br><" . $exam['number_of_attempts'];
            if (
                $exam_history['0']['score'] / 100 < 0.5 &&
                $exam_history['0']["number_attempts"] < $exam['number_of_attempts']
            ) { ?>
                <span id='delete_user_exam'>Retry</span>
            <?php
            }
            ?>

        </div>
    </div>
<?php  }

// echo "<pre>";
// print_r($examdetails);
// exit();

$k = 0;
foreach ($examdetails as $key => $question) {
    $active = '';
    $k++; ?>
    <div class="result-finish-tab-panecourse-course" id="<?php echo $question['id_question']; ?>">
        <h1 class="question-title-finish"><?php echo strip_tags(htmlspecialchars_decode($question['questiontitle'])); ?></h1>
        <?php
        echo "<div class='content'>"; ?>
        <?php $boolean = 0;
        foreach ($question['answers'] as $index => $answer) : ?>
            <label class="label-question-finish">
                <?php if ($answer['true'] == 1) {
                    if ($answer['answer_false'] == 1 || $answer['answer_true'] == 1)  $boolean = 1;

                    echo '<i class="green dx-icon dx-icon-check"></i>';
                } else 
                      if ($answer['answer_false'] == 1) {
                    echo '<i class="red dx-icon dx-icon-close"></i>';
                    $boolean = 1;
                } else 
                      if ($answer['answer_true'] == 1) {
                    echo '<i class="green dx-icon dx-icon-check"></i>';
                    $boolean = 1;
                } else {
                    echo '<i class="white dx-icon dx-icon-circle"></i> ';
                }
                ?>
                <div class="plabel"><?php echo ($answer['answertitle']) ?></div>
            </label>
        <?php endforeach;
        if ($boolean == 0) { ?>
            <label class="label-question-finish"><span class="red"><i class="red dx-icon dx-icon-close"></i>You did not choose any of the answers</span></label>
        <?php } ?>
        <?php echo "</div>"; ?>

    </div>
<?php } ?>
<input type="hidden" name="id_course" value="<?php echo $id_course; ?>" id="id_course">
<br><br><br><br><br><br>

<style>
    .nextprev-finish {
        display: flex;
        width: 460px;
        margin: 0 auto;
        position: relative;
        color: #fff;
        vertical-align: top;
        font-weight: bold;
        margin-top: 10px;
    }

    .nextprev-finish a {
        color: red;
        margin-left: 33px;
        margin-top: -14px;
    }

    .nextprev-finish a span {
        background: white !important;
        color: #00ADB1 !important;
    }

    .nextprev-finish span {
        padding-left: 20px;
    }

    .footerpopup-finish {
        padding: 10px 30px 10px 0px;
        background: #00ADB1;
        position: fixed;
        display: flex;
        bottom: 0;
        right: 0;
        width: 100%;
        height: 60px;
    }

    .result-finish-tab-panecourse-course label {
        margin-bottom: 0;
    }

    .result-finish-tab-panecourse-course h1 {
        margin-top: 0;
        padding-top: 10px;
        padding-bottom: 0px;
        margin-bottom: 0;
    }

    .white {
        margin-top: 6px;
        padding-left: 23px;
    }

    .green {
        margin-top: 6px;
        font-weight: bold;
        color: green;
        padding-left: 10px;
    }

    .red {
        margin-top: 6px;
        font-weight: bold;
        color: red;
        padding-left: 10px;
    }
</style>