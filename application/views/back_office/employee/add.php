
<?php
$content_name="employee";
$id_content="47";
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
<li><a href="<?=base_url();?>back_office/employee">employee</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/employee/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["first_name"] = "";
$info["last_name"] = "";
$info["email"] = "";
$info["job_position"] = "";
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
<input type="text" class="input-xxlarge" name="title" value="<?= set_value("title",$info["title"]); ?>" />
<?= form_error("title","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">First name
</label>
<label>
<input type="text" class="input-xxlarge" name="first_name" value="<?= set_value("first_name",$info["first_name"]); ?>" />
<?= form_error("first_name","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Last name
</label>
<label>
<input type="text" class="input-xxlarge" name="last_name" value="<?= set_value("last_name",$info["last_name"]); ?>" />
<?= form_error("last_name","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Email
</label>
<label>
<input type="text" class="input-xxlarge" name="email" value="<?= set_value("email",$info["email"]); ?>" />
<?= form_error("email","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Job position
</label>
<label>
<input type="text" class="input-xxlarge" name="job_position" value="<?= set_value("job_position",$info["job_position"]); ?>" />
<?= form_error("job_position","<span class='text-error'>","</span>"); ?>
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