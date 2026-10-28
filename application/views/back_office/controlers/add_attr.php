<? if ($length_attr == 0) { ?>
	<script>
		$(document).ready(function() {
			$('.text_attr').css('display', 'none');
		});
	</script>
<? }
if ($thumb_attr == 0) { ?>
	<script>
		$(document).ready(function() {
			$('.image_attr').css('display', 'none');
			$(".thumb_attr").css('display', 'none');
		});
	</script>
<? } ?>
<? if ($foreign_attr == 0) { ?>
	<script>
		$(document).ready(function() {
			$('.foreign_attr').css('display', 'none');
		});
	</script>
<? } ?>
<script>
	$(document).ready(function() {
		$('#type').change(function() {
			var type = $(this).val();
			if (type == 4) {
				$('.text_attr').fadeOut('fast');
				$('.foreign_attr').fadeOut('fast');
				$('.image_attr').fadeIn('fast');
			} else if (type == 2 || type == 8 || type == 9) {
				$('.text_attr').fadeIn('fast');
				$('.image_attr').fadeOut('fast');
				$(".thumb_attr").fadeOut('fast');
				$('.foreign_attr').fadeOut('fast');
			} else if (type == 7) {
				$('.foreign_attr').fadeIn('fast');
				$('.text_attr').fadeOut('fast');
				$('.image_attr').fadeOut('fast');
				$(".thumb_attr").fadeOut('fast');
			} else {
				$('.foreign_attr').fadeOut('fast');
				$('.image_attr').fadeOut('fast');
				$('.text_attr').fadeOut('fast');
				$(".thumb_attr").fadeOut('fast');
			}
		});

		$('#thumbs').click(function() {
			if (this.checked == true)
				$(".thumb_attr").fadeIn('fast');
			else
				$(".thumb_attr").fadeOut('fast');
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
					<li><?= $title1; ?> <span class="divider">/</span></li>
					<li class="active"><?= $con_type["name"]; ?></li>
				</ul>
			</div>


			<div class="hundred pull-left">
				<div id="result"></div>

				<form action="<?= base_url(); ?>back_office/control/submit_attr" method="post" class="middle-forms" enctype="multipart/form-data">
					<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
						<?
						if (isset($id)) {
							echo '<input type="hidden" name="id" value="' . $id . '" />';
						} else {
							$info = array('name' => '', 'type' => '', 'length' => '', 'thumb_val' => '', 'display' => '', 'foreign_key' => '');
						}
						?>
						<? if ($this->session->userdata('success_message')) { ?>
					<div class="alert alert-success">
						<?= $this->session->userdata('success_message'); ?>
					</div>
				<? } ?>
				<? if ($this->session->userdata('error_message')) { ?>
					<div class="alert alert-error">
						<?= $this->session->userdata('error_message'); ?>
					</div>
				<? } ?>
				</p>
				<fieldset>
					<legend></legend>
					<label class="field-title">Attribute Name<em>*</em>:</label> <label>
						<input type="text" class="input-xxlarge" name="name" value="<?= set_value('name', $info["name"]); ?>" />
						<?= form_error('name', '<span class="text-error">', '</span>'); ?>
					</label>
					<label class="field-title">Type <em>*</em>:</label> <label>
						<select name="type" id="type" class="input-xxlarge">
							<option value=""> - select attrubute type - </option>
							<? foreach ($attr_type as $val) { ?>
								<option value="<?= $val["id_type"]; ?>" <? if (set_value('type', $info["type"]) == $val["id_type"]) echo 'selected="selected"'; ?>><?= $val["name"]; ?></option>
							<? } ?>
						</select>
						<?= form_error('type', '<span class="text-error">', '</span>'); ?>
					</label>
					<div class="text_attr"><label class="field-title">Length <em>*</em>:</label> <label>
							<input type="text" class="input-xxlarge" name="length" value="<?= set_value('length', $info["length"]); ?>" />
							<?= form_error('length', '<span class="text-error">', '</span>'); ?>
						</label></div>

					<div class="image_attr"><label class="field-title">Thumbnails:</label> <label>
							<input type="checkbox" name="thumbs" id="thumbs" value="1" />&nbsp;Active
						</label></div>
					<div class="thumb_attr"><label class="field-title">Thumbnails Values <em>*</em>:</label>
						<label>
							<input type="text" class="input-xxlarge" name="thumb_val" value="<?= set_value('thumb_val', $info["thumb_val"]); ?>" />
							<?= form_error('thumb_val', '<span class="text-error">', '</span>'); ?>
						</label>
						<label>
							<input type="radio" name="resize_status" value="0" checked="checked" />&nbsp;Crop&nbsp;&nbsp;
							<input type="radio" name="resize_status" value="1" />&nbsp;Resize
						</label>
					</div>
					<div class="foreign_attr"><label class="field-title">Content Type <em>*</em>:</label> <label>
							<?
							$q = "SELECT * FROM `content_type` WHERE deleted = 0 AND id_content NOT IN ( SELECT  foreign_key FROM `content_type_attr` WHERE id_content='" . $con_type["id_content"] . "' AND deleted =0 ) ORDER BY sort_order";
							echo $q;

							$query = $this->db->query($q);
							$foreign_content = $query->result_array();
							?>
							<select name="foreign_key" class="input-xxlarge">
								<option value="">- choose content type -</option>
								<? foreach ($foreign_content as $val) { ?>
									<option value="<?= $val["id_content"]; ?>" <? if ($info["foreign_key"] == $val["id_content"]) echo 'selected="selected"'; ?>>
										<?= $val["name"]; ?>
									</option>
								<? } ?>
							</select>
							<?= form_error('foreign_key', '<span class="text-error">', '</span>'); ?>
						</label></div>
					<label class="field-title">List in table:</label> <label>
						<input type="checkbox" name="display" value="1" <? if ($info["display"] == 1) echo 'checked="checked"'; ?> />&nbsp;Display
					</label>
				</fieldset>
				<p class="pull-left">
					<input type="hidden" name="id_content" value="<?= $con_type["id_content"]; ?>" />
					<input type="image" src="<?= base_url(); ?>images/bt-send-form.png" class="btn btn-primary" value="Save Changes" />
				</p>
				</form>

			</div>
		</div>
	</div>

	<? $this->session->unset_userdata("success_message"); ?>
	<? $this->session->unset_userdata("error_message"); ?>