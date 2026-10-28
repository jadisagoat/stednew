<?php
/* ==========================================================================
   FEEDBACK LIST (modern design - backup: feedback____28_10_2026.php)
   No template: returned by home::save_review (AJAX) and injected into #listComments
   on the course details page. Same markup as the review card in course-details.php.
   ========================================================================== */
foreach ($my_user_course_rating as $row) { ?>
	<div class="comment-row mx-cd-comment" id="<?php echo $row['id_user_course_rating']; ?>">
		<?php
		$target_file = base_url() . "images/nopic.jpg";
		if ($row['image'])
			$target_file = base_url() . "uploads/user/" . $row['image'];
		echo '<img src="' . htmlspecialchars($target_file) . '" alt="" class="preview mx-cd-avatar">';
		?>
		<div class="mx-cd-comment-body">
			<div class="mx-cd-comment-top">
				<span class="posted-by"><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></span>
				<span class="posted-at postedat"><i class="far fa-calendar"></i> <?php
				$timestamp = strtotime($row['created_date']);
				$formattedDate = date("j/n/Y", $timestamp);
				echo $formattedDate; // Output: 26/4/2024
				?></span>
			</div>
			<div class="comment-text"><?php echo nl2br(htmlspecialchars($row['feedback'], ENT_QUOTES, 'UTF-8')); ?></div>
		</div>
	</div>
<?php } ?>
