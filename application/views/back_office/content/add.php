
<?php
$content_name="content";
$id_content="8";
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
<li><a href="<?=base_url();?>back_office/content">content</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/content/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["title"] = "";
$info["title_page"] = "";
$info["course"] = "";
$info["type"] = "";
$info["image"] = "";
$info["file"] = "";
$info["link"] = "";
$info["description"] = "";
$info["hours"] = "";
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
<label class="field-title">Title page
</label>
<label>
<input type="text" class="input-xxlarge" name="title_page" value="<?= set_value("title_page",$info["title_page"]); ?>" />
<?= form_error("title_page","<span class='text-error'>","</span>"); ?>
</label>
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
<label class="field-title">Type
</label>
<label>

<? $items = $this->fct->getAll("type","sort_order");?>
<select name="type"  class="input-xxlarge">
<option value="" > - select type - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_type"]; ?>" <? if(isset($id)){  if($info["id_type"] == $valll["id_type"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("type","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Image
</label>
<label>
<input type="file"  class="input-xxlarge"  name="image" />
<span >
<?
if($info["image"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/content/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/content/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_content"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>
</span>
<?= form_error("image","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">File
</label>
<label>
<input type="file"  class="input-xxlarge"  name="file" />
<?= form_error("file","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Minnutes&nbsp;<em>*</em>:
</label>
<label>
<input type="text" class="input-xxlarge" name="hours" value="<?= set_value("hours",$info["hours"]); ?>" />
<?= form_error("hours","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Link
</label>
<label>
<input type="text" class="input-xxlarge" name="link" value="<?= set_value("link",$info["link"]); ?>" />
<?= form_error("link","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Description
</label>
<label>

<textarea class="ckeditor" id="description" rows="15" cols="100" name="description" >
<?= set_value("description",$info["description"]); ?>
</textarea>

<?= form_error("description","<span class='text-error'>","</span>"); ?>
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