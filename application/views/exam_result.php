<?php
/* ==========================================================================
   EXAM RESULT (modern design - backup: exam_result____28_10_2026.php)
   No template: rendered inside #examModal (course-details-popup-exam.php) and returned by
   home::save_exam (AJAX, injected into .modal-body). Styles: css/pages/course.css (.mx-er-*)
   Kept for template.php: #delete_user_exam (Retry), #id_exam, #id_course.
   ========================================================================== */
?>
<input type="hidden" name="id_exam" value="<?php echo $exam['id_exam']; ?>" id="id_exam">
<div class="mx-er">
<?php
if (isset($exam_history) && isset($exam_history['0']) && isset($exam_history['0']['score'])) {
	$er_score = (float) $exam_history['0']['score'];
	$er_fail  = ($exam_history['0']['score'] / 100 < 0.5);
	$er_shown = round($er_score);
?>
	<div class="footerpopup-finish mx-er-summary <?php echo $er_fail ? 'is-fail' : 'is-pass'; ?>">
		<div class="mx-er-ring" style="--p:<?php echo max(0, min(100, $er_shown)); ?>">
			<b><?php echo $er_shown; ?></b><small>/ 100</small>
		</div>
		<div class="nextprev-finish">
			<span class="mx-er-label">Your score</span>
			<?php
			if ($er_fail) {
				echo '<h2 class="mx-er-verdict"><i class="fas fa-circle-xmark"></i> Fail</h2>';
				echo '<p class="mx-er-msg">You need at least 50 to pass. Review the answers below before you try again.</p>';
			} else {
				$certificate = $exam_history['0']['certificate'];
				$downloadLink = base_url() . 'uploads/exam_history/' . $certificate;
				echo '<h2 class="mx-er-verdict"><i class="fas fa-circle-check"></i> Pass</h2>';
				echo '<p class="mx-er-msg">Congratulations - you passed the final exam of this course.</p>';
			}
			?>
			<div class="mx-er-actions">
				<?php
				if (!$er_fail) {
					if (isset($certificate) && $certificate) {
						echo '<a class="mx-btn mx-btn-primary" style="cursor:pointer" href="' . $downloadLink . '" download>
							<i class="fas fa-award"></i> Get Certificate
						</a>';
					} else { ?>
						<span id='delete_user_exam' class="mx-er-retry" role="button" tabindex="0"><i class="fas fa-rotate-right"></i> Retry</span>
					<?php }
				}

				// if number attempt not 0 and Cutting time "date end"
				if (
					$exam_history['0']['score'] / 100 < 0.5 &&
					$exam_history['0']["number_attempts"] < $exam['number_of_attempts']
				) { ?>
					<span id='delete_user_exam' class="mx-er-retry" role="button" tabindex="0"><i class="fas fa-rotate-right"></i> Retry</span>
				<?php
				}
				?>
			</div>
		</div>
	</div>
<?php } ?>

	<h3 class="mx-er-sub">Your answers</h3>
<?php
$k = 0;
foreach ($examdetails as $key => $question) {
	$active = '';
	$k++; ?>
	<div class="result-finish-tab-panecourse-course mx-er-q" id="<?php echo $question['id_question']; ?>">
		<h1 class="question-title-finish"><span class="mx-er-qnum"><?php echo $k; ?></span> <?php echo strip_tags(htmlspecialchars_decode($question['questiontitle'])); ?></h1>
		<div class='content'>
		<?php $boolean = 0;
		foreach ($question['answers'] as $index => $answer) :
			$er_state = 'is-none'; ?>
			<?php
			ob_start();
			if ($answer['true'] == 1) {
				if ($answer['answer_false'] == 1 || $answer['answer_true'] == 1)  $boolean = 1;
				echo '<i class="green fas fa-check"></i>';
				$er_state = ($answer['answer_true'] == 1) ? 'is-right is-mine' : 'is-right';
			} else
				if ($answer['answer_false'] == 1) {
				echo '<i class="red fas fa-xmark"></i>';
				$boolean = 1;
				$er_state = 'is-wrong is-mine';
			} else
				if ($answer['answer_true'] == 1) {
				echo '<i class="green fas fa-check"></i>';
				$boolean = 1;
				$er_state = 'is-right is-mine';
			} else {
				echo '<i class="white far fa-circle"></i> ';
			}
			$er_icon = ob_get_clean();
			?>
			<label class="label-question-finish <?php echo $er_state; ?>">
				<?php echo $er_icon; ?>
				<div class="plabel"><?php echo ($answer['answertitle']) ?></div>
				<?php if (strpos($er_state, 'is-mine') !== false) { ?><span class="mx-er-tag">Your answer</span><?php } ?>
			</label>
		<?php endforeach;
		if ($boolean == 0) { ?>
			<label class="label-question-finish is-wrong"><i class="red fas fa-xmark"></i><span class="plabel red">You did not choose any of the answers</span></label>
		<?php } ?>
		</div>

	</div>
<?php } ?>
</div>
<input type="hidden" name="id_course" value="<?php echo $id_course; ?>" id="id_course">
