
<?php
$content_name="contactform";
$id_content="36";
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
<li><a href="<?=base_url();?>back_office/contactform">contactform</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/contactform/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["name"] = "";
$info["subject"] = "";
$info["email"] = "";
$info["comments"] = "";
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

<label class="field-title">Name
</label>
<label>
<input type="text" class="input-xxlarge" name="name" value="<?= set_value("name",$info["name"]); ?>" />
<?= form_error("name","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Subject
</label>
<label>
<input type="text" class="input-xxlarge" name="subject" value="<?= set_value("subject",$info["subject"]); ?>" />
<?= form_error("subject","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Email
</label>
<label>
<input type="text" class="input-xxlarge" name="email" value="<?= set_value("email",$info["email"]); ?>" />
<?= form_error("email","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Comments
</label>
<label>
<input type="text" class="input-xxlarge" name="comments" value="<?= set_value("comments",$info["comments"]); ?>" />
<?= form_error("comments","<span class='text-error'>","</span>"); ?>
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