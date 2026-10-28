
<?php
$content_name="icons_table";
$id_content="26";
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
<li><a href="<?=base_url();?>back_office/icons_table">icons table</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/icons_table/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["image"] = "";
$info["description"] = "";
$info["home_page"] = "";
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
<label class="field-title">Image
</label>
<label>
<input type="file"  class="input-xxlarge"  name="image" />
<span >
<?
if($info["image"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/icons_table/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/icons_table/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_icons_table"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>
</span>
<?= form_error("image","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Description
</label>
<label>

<textarea class="ckeditor" id="description" rows="15" cols="100" name="description" >
<?= set_value("description",$info["description"]); ?>
</textarea>

<?= form_error("description","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Home page
</label>
<label>

<? $items = $this->fct->getAll("home_page","sort_order");?>
<select name="home_page"  class="input-xxlarge">
<option value="" > - select home page - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_home_page"]; ?>" <? if(isset($id)){  if($info["id_home_page"] == $valll["id_home_page"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("home_page","<span class='text-error'>","</span>"); ?>
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