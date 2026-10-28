<?php 
foreach ($my_user_course_rating as $row){ ?>
			<div class="comment-row col-md-12" id="<?php echo $row['id_user_course_rating'];?>">
				<div class="comment-info d-flex">
					<div id='preview' class="col-md-2">
					<?php  
					$target_file = base_url() ."images/nopic.jpg";
					if($row['image'])
					$target_file = base_url() ."uploads/user/".$row['image']; 
					echo '<img src="'.htmlspecialchars($target_file).'" alt="StedLb" class="preview">'; 
					?>		 
					</div> 
					<div  class="col-md-10 preview-right">
						<span class="posted-by"><?php echo $row['name']; ?></span> 
						<span class="posted-at postedat"><?php
						$timestamp = strtotime($row['created_date']);
						$formattedDate = date("j/n/Y", $timestamp);
						echo $formattedDate; // Output: 26/4/2024
						?></span>
						<div class="comment-text"><?php echo nl2br(htmlspecialchars($row['feedback'], ENT_QUOTES, 'UTF-8')); ?></div>				
						<!-- <span class="commentdelete" onclick="commentdelete(145)">
							<i class="fa fa-trash"></i>
						</span>
						<span class="commentedit" onclick="commentedit(145)">
							<i class="fa fa-edit"></i>
						</span> -->
					</div>

				</div>
			</div>
		<?php } ?>