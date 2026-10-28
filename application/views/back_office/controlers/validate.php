<script src="<?php echo base_url(); ?>sted_theme/assets/vendor/libs/jquery/jquery.js"></script>

<script>
	$(document).ready(function() {
		$('.open_validate').click(function() {
			var id = this.id;
			var sel = "#validate_" + id;
			$('.validate_cont').slideUp('fast');
			$(sel).slideDown('fast');
		});
		$('.min_length').click(function() {
			var id = this.id;
			var sel = '#min_num' + id;
			$(sel).toggle();
		});
		$('.max_length').click(function() {
			var id = this.id;
			var sel = '#max_num' + id;
			$(sel).toggle();
		});

		$('#submit_validate').click(function() {
			$('#form_validate').submit();
		});
	});
</script>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
			<? $this->load->view('back_office/includes/left_box'); ?>
		</div>
		<div class="span10">
			<div class="span10-fluid">
				<ul class="breadcrumb">
					<li><?= $title; ?> </li>
				</ul>
			</div>
			<div class="hundred pull-left">
				<div id="result"></div>
				<?
				$cond = array('id_content' => $id_content);
				$pos = $this->fct->getonecell('content_type', 'position', $cond);
				?>
				<form id="form_validate" action="<?= base_url(); ?>back_office/install/finish/<?= $id_content; ?>" method="post" class="middle-forms" enctype="multipart/form-data">

					<fieldset>
						<legend>If Set Content Menu</legend>
						<label>
							<!-- <input type="text" class="input-xxlarge" name="label_menu" value="dashboard"> -->
							<!-- <br> -->
							<input type="radio" name="position" value="2" <? if ($pos == 2) echo 'checked="checked"'; ?> />&nbsp;In Menu
							<!-- &nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="position" value="3" <? if ($pos == 3) echo 'checked="checked"'; ?> />&nbsp;Bottom Menu -->
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="position" value="0" <? if ($pos == 0) echo 'checked="checked"'; ?> />&nbsp;None
						</label>
						<p class="pull-left">
							<input type="hidden" name="id_content" value="<?= $id_content; ?>" />
						<ul class="pager">
							<li class="previous"><a href="<?= base_url(); ?>back_office/install/settings/<?= $id_content; ?>">Prev</a></li>
							<li><a class="btn" id="submit_validate">Finish&nbsp;&raquo;</a></li>
						</ul>
						</p>
					</fieldset>
				</form>
			</div>
		</div>
	</div>