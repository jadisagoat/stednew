
<?php
$content_name="answer";
$id_content="12";
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
<li><a href="<?=base_url();?>back_office/answer">answer</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/answer/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["question"] = "";
$info["true"] = "";
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

<label class="field-title">Title&nbsp;<em>*</em>:
</label>
<label>

<textarea class="ckeditor" id="title" rows="15" cols="100" name="title" >
<?= set_value("title",$info["title"]); ?>
</textarea>

<?= form_error("title","<span class='text-error'>","</span>"); ?>
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
<label class="field-title">True
</label>
<label>
<select name="true"  class="input-xxlarge">
<option  value="0" > - select option - </option>
<option  value="1" <? if($info["true"] == 1) echo 'selected="selected"'; ?> > Active </option>
<option  value="0" <? if($info["true"] == 0) echo 'selected="selected"'; ?> > InActive </option>
</select>
<?= form_error("true","<span class='text-error'>","</span>"); ?>
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