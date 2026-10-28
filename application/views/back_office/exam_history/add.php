
<?php
$content_name="exam_history";
$id_content="14";
if($this->session->userdata("level") == 0){
$cond=array("id_role" => $this->session->userdata("id_role"),"id_content"=>$id_content);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
} else { 
$write_priv=1;
}
if($write_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }	
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?=base_url();?>back_office/exam_history">exam history</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/exam_history/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["course"] = "";
$info["exam"] = "";
$info["user"] = "";
$info["score"] = "";
$info["grade"] = "";
$info["exam_date"] = "";
$info["number_attempts"] = "";
$info["certificate"] = "";
}
?>
<? if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success">
<?= $this->session->userdata("success_message"); ?>
</div>
<? } ?>
<? if($this->session->userdata("error_message")){ ?>
<div class="alert alert-error">
<?= $this->session->userdata("error_message"); ?>
</div>
<? } ?>
</p>
<fieldset>
<legend></legend>

<label class="field-title">Course
</label>
<label>

<? $items = $this->fct->getAll("course","sort_order");?>
<select name="course"  class="input-xxlarge">
<option value="" > - select course - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_course"]; ?>" <? if(isset($id)){  if($info["id_course"] == $valll["id_course"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("course","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Exam
</label>
<label>

<? $items = $this->fct->getAll("exam","sort_order");?>
<select name="exam"  class="input-xxlarge">
<option value="" > - select exam - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_exam"]; ?>" <? if(isset($id)){  if($info["id_exam"] == $valll["id_exam"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("exam","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">User
</label>
<label>

<? $items = $this->fct->getAll("user","sort_order");?>
<select name="user"  class="input-xxlarge">
<option value="" > - select user - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_user"]; ?>" <? if(isset($id)){  if($info["id_user"] == $valll["id_user"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("user","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Score
</label>
<label>
<input type="text" class="input-xxlarge" name="score" value="<?= set_value("score",$info["score"]); ?>" />
<?= form_error("score","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Grade
</label>
<label>

<? $items = $this->fct->getAll("grade","sort_order");?>
<select name="grade"  class="input-xxlarge">
<option value="" > - select grade - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_grade"]; ?>" <? if(isset($id)){  if($info["id_grade"] == $valll["id_grade"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("grade","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Exam date
</label>
<label>

    <div class="input-append date" data-date="24-08-2024" data-date-format="mm/dd/yyyy">
    <input class="span2" size="16" name="exam_date" type="text" 
    value="<?= set_value("exam_date",$this->fct->date_out_formate($info["exam_date"])); ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>
<?= form_error("exam_date","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Number attempts
</label>
<label>
<input type="text" class="input-xxlarge" name="number_attempts" value="<?= set_value("number_attempts",$info["number_attempts"]); ?>" />
<?= form_error("number_attempts","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Certificate
</label>
<label>
<input type="file"  class="input-xxlarge"  name="certificate" />
<?= form_error("certificate","<span class='text-error'>","</span>"); ?>
</label>
</ol>
</fieldset>
<p class="align-right">
<input type="image" src="<?= base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary" />
</p>
<span class="clearFix">&nbsp;</span>
</form>
</div>
</div>     
</div>
<? $this->session->unset_userdata("success_message"); ?> 
<? $this->session->unset_userdata("error_message"); ?> 