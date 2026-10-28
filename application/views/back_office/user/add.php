
<?php
$content_name="user";
$id_content="23";
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
<li><a href="<?=base_url();?>back_office/user">user</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/user/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
$info["name"] = "";
$info["username"] = "";
$info["password"] = "";
$info["hashsendcode"] = "";
$info["signature"] = "";
$info["global_role"] = "";
$info["image"] = "";
$info["years_of_exp"] = "";
$info["phone"] = "";
$info["age"] = "";
$info["active"] = "";
$info["id_countries"] = "";
$info["email"] = "";
$info["nationality"] = "";
$info["resident"] = "";
$info["profession"] = "";
$info["facebook"] = "";
$info["twitter"] = "";
$info["occupation"] = "";
$info["gender"] = "";
$info["bio"] = "";
$info["linkedin"] = "";
$info["website"] = "";
$info["title"] = "";
$info["aboutme"] = "";
$info["address"] = "";
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

<label class="field-title">Name&nbsp;<em>*</em>:
</label>
<label>
<input type="text" class="input-xxlarge" name="name" value="<?= set_value("name",$info["name"]); ?>" />
<?= form_error("name","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Username
</label>
<label>
<input type="text" class="input-xxlarge" name="username" value="<?= set_value("username",$info["username"]); ?>" />
<?= form_error("username","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Password
</label>
<label>
<input type="text" class="input-xxlarge" name="password" value="<?= set_value("password",$info["password"]); ?>" />
<?= form_error("password","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Hashsendcode
</label>
<label>
<input type="text" class="input-xxlarge" name="hashsendcode" value="<?= set_value("hashsendcode",$info["hashsendcode"]); ?>" />
<?= form_error("hashsendcode","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Signature
</label>
<label>
<input type="file"  class="input-xxlarge"  name="signature" />
<span >
<?
if($info["signature"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/user/<?= $info["signature"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/user/delete_image/signature/<?= $info["signature"]; ?>/<?= $info["id_user"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>
</span>
<?= form_error("signature","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Global role
</label>
<label>

<? $items = $this->fct->getAll("role","sort_order");?>
<select name="global_role"  class="input-xxlarge">
<option value="" > - select role - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_role"]; ?>" <? if(isset($id)){  if($info["id_role"] == $valll["id_role"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("global_role","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Image
</label>
<label>
<input type="file"  class="input-xxlarge"  name="image" />
<span >
<?
if($info["image"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/user/<?= $info["image"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location=' <?= base_url(); ?>back_office/user/delete_image/image/<?= $info["image"]; ?>/<?= $info["id_user"] ?> ' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>
</span>
<?= form_error("image","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Years of exp
</label>
<label>
<input type="text" class="input-xxlarge" name="years_of_exp" value="<?= set_value("years_of_exp",$info["years_of_exp"]); ?>" />
<?= form_error("years_of_exp","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Phone
</label>
<label>
<input type="text" class="input-xxlarge" name="phone" value="<?= set_value("phone",$info["phone"]); ?>" />
<?= form_error("phone","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Age
</label>
<label>
<input type="text" class="input-xxlarge" name="age" value="<?= set_value("age",$info["age"]); ?>" />
<?= form_error("age","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Active
</label>
<label>
<select name="active"  class="input-xxlarge">
<option  value="0" > - select option - </option>
<option  value="1" <? if($info["active"] == 1) echo 'selected="selected"'; ?> > Active </option>
<option  value="0" <? if($info["active"] == 0) echo 'selected="selected"'; ?> > InActive </option>
</select>
<?= form_error("active","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Id_countries
</label>
<label>

<? $items = $this->fct->getAll("countries","sort_order");?>
<select name="id_countries"  class="input-xxlarge">
<option value="" > - select countries - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_countries"]; ?>" <? if(isset($id)){  if($info["id_countries"] == $valll["id_countries"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("id_countries","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Email
</label>
<label>
<input type="text" class="input-xxlarge" name="email" value="<?= set_value("email",$info["email"]); ?>" />
<?= form_error("email","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Nationality
</label>
<label>
<input type="text" class="input-xxlarge" name="nationality" value="<?= set_value("nationality",$info["nationality"]); ?>" />
<?= form_error("nationality","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Resident
</label>
<label>
<input type="text" class="input-xxlarge" name="resident" value="<?= set_value("resident",$info["resident"]); ?>" />
<?= form_error("resident","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Profession
</label>
<label>
<input type="text" class="input-xxlarge" name="profession" value="<?= set_value("profession",$info["profession"]); ?>" />
<?= form_error("profession","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Facebook
</label>
<label>
<input type="text" class="input-xxlarge" name="facebook" value="<?= set_value("facebook",$info["facebook"]); ?>" />
<?= form_error("facebook","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Twitter
</label>
<label>
<input type="text" class="input-xxlarge" name="twitter" value="<?= set_value("twitter",$info["twitter"]); ?>" />
<?= form_error("twitter","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Occupation
</label>
<label>
<input type="text" class="input-xxlarge" name="occupation" value="<?= set_value("occupation",$info["occupation"]); ?>" />
<?= form_error("occupation","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Gender
</label>
<label>

<? $items = $this->fct->getAll("gender","sort_order");?>
<select name="gender"  class="input-xxlarge">
<option value="" > - select gender - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_gender"]; ?>" <? if(isset($id)){  if($info["id_gender"] == $valll["id_gender"]){ echo 'selected="selected"'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>
<?= form_error("gender","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Bio
</label>
<label>
<input type="text" class="input-xxlarge" name="bio" value="<?= set_value("bio",$info["bio"]); ?>" />
<?= form_error("bio","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Linkedin
</label>
<label>
<input type="text" class="input-xxlarge" name="linkedin" value="<?= set_value("linkedin",$info["linkedin"]); ?>" />
<?= form_error("linkedin","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Website
</label>
<label>
<input type="text" class="input-xxlarge" name="website" value="<?= set_value("website",$info["website"]); ?>" />
<?= form_error("website","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Title
</label>
<label>
<input type="text" class="input-xxlarge" name="title" value="<?= set_value("title",$info["title"]); ?>" />
<?= form_error("title","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Aboutme
</label>
<label>

<textarea class="ckeditor" id="aboutme" rows="15" cols="100" name="aboutme" >
<?= set_value("aboutme",$info["aboutme"]); ?>
</textarea>

<?= form_error("aboutme","<span class='text-error'>","</span>"); ?>
</label>
<label class="field-title">Address
</label>
<label>

<textarea class="ckeditor" id="address" rows="15" cols="100" name="address" >
<?= set_value("address",$info["address"]); ?>
</textarea>

<?= form_error("address","<span class='text-error'>","</span>"); ?>
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