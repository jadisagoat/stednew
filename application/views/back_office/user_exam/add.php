
<?php
$content_name="user_exam";
$id_content="48";
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
<li><a href="<?=base_url();?>back_office/user_exam">user exam</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/user_exam/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["user"] = "";
$info["exam"] = "";
$info["question"] = "";
$info["answer"] = "";
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

<label class="field-title">User&nbsp;<em>*</em>:
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
<label class="field-title">Question
</label>
<label>

<? $items = $this->fct->getAll("question","sort_order");?>
<select name="question"  class="input-xxlarge">
<option value="" > - select question - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_question"]; ?>" <? if(isset($id)){  if($info["id_question"] == $valll["id_question"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("question","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Answer
</label>
<label>

<? $items = $this->fct->getAll("answer","sort_order");?>
<select name="answer"  class="input-xxlarge">
<option value="" > - select answer - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_answer"]; ?>" <? if(isset($id)){  if($info["id_answer"] == $valll["id_answer"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("answer","<span class='text-error'>","</span>"); ?>
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