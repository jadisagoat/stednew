<script>
$(function(){
$('input[name=variable]:radio').change(function(){
var val=$(this).val();
if(val == "exist"){
$('#db_names').css('display','block');	
} else { $('#db_names').css('display','none'); }
});
});
</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>

<div class="span10 cont_h">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li>Personal Information</li>
</ul>
</div>
    
<div class="hundred pull-left">  
<? if($this->session->userdata('level') ==0){ ?>
<form action="<?= base_url(); ?>back_office/settings/submit_user" method="post" class="middle-forms" enctype="multipart/form-data">
<? } else { ?>
<form action="<?= base_url(); ?>back_office/settings/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<? } ?>
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>

<? if($this->session->userdata('success_message')){ ?>
<div class="alert alert-success">
<?= $this->session->userdata('success_message'); ?>
</div>
<? } ?>
<? if($this->session->userdata('error_message')){ ?>
<div class="alert alert-error">
<?= $this->session->userdata('error_message'); ?>
</div>
<? } ?>
</p>
<fieldset>
<legend></legend>
<? if($this->session->userdata('level') == 0){ ?>
<?
if(isset($id)){
	echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('name'=>'','email'=>'','phone'=>'','address'=>'','username'=>'','password'=>'');
}
?>

<label class="field-title">Full Name <em>*</em>:</label> <label>
<input type="text" class="input-xxlarge" name="name" value="<?= set_value('name',$info["name"]); ?>" />
<?= form_error('name','<span class="text-error">','</span>'); ?>
</label>

<label class="field-title">Email<em>*</em>: </label> <label>
<input type="text" class="input-xxlarge" name="email" value="<?= set_value('email',$info["email"]); ?>" />
<?= form_error('email','<span class="text-error">','</span>'); ?>
</label>


<label class="field-title" >Phone:</label> <label>
<input type="text" class="input-xxlarge" name="phone" value="<?= set_value('phone',$info["phone"]); ?>"   />
</label>

<label class="field-title">Address:</label>
<label>
<textarea class="hundred" name="address" rows="7" cols="25"><?= set_value('address',$info["address"]); ?></textarea>
</label>


<label class="field-title">User Name<em>*</em>:</label>
 <label><input type="text" class="input-xxlarge" name="username" autocomplete="off" value="<?= set_value('username',$info["username"]); ?>" />
 <?= form_error('username','<span class="text-error">','</span>'); ?>
 </label>


<label class="field-title">Password<em>*</em>:</label>
 <label><input type="password" class="input-xxlarge" name="password" autocomplete="off" value="<?= set_value('password',$info["password"]); ?>" />
  <?= form_error('password','<span class="text-error">','</span>'); ?>
 </label>
    						

<?
} else { ?>

<label class="field-title">Admin Name <em>*</em>:</label> <label>
<input type="text" class="input-xxlarge" name="a_name" value="<?= set_value('a_name',$admin["admin_name"]); ?>" />
<?= form_error('a_name','<span class="text-error">','</span>'); ?>
</label>

<label class="field-title">User Name<em>*</em>: </label> <label>
<input type="text" class="input-xxlarge" name="user_name" value="<?= set_value('user_name',$admin["user_name"]); ?>" />
<?= form_error('user_name','<span class="text-error">','</span>'); ?>
</label>


<label class="field-title">Password<em>*</em>:</label> <label>
<input type="text" class="input-xxlarge" name="password" value="<?= set_value('password',$admin["password"]); ?>" />
<?= form_error('password','<span class="text-error">','</span>'); ?>
</label>
<? if($admin["level"] != 1){?>
<div style="display:none">

<label class="field-title">Twitter:</label>
<label><input type="text" class="input-xxlarge" name="twitter" value="<?= $admin["twitter"]; ?>" />
</label>
<label class="field-title">Instagram:</label>
<label><input type="text" class="input-xxlarge" name="linkedin" value="<?= $admin["linkedin"]; ?>" />
</label>

<label class="field-title">Facebook:</label>
 <label><input type="text" class="input-xxlarge" name="skype" value="<?= $admin["skype"]; ?>" />
 </label>

<label class="field-title">FaceBook:</label>
 <label><input type="text" class="input-xxlarge" name="facebook" value="<?= $admin["facebook"]; ?>" />
 </label>
<label class="field-title">You Tube:</label>
 <label><input type="text" class="input-xxlarge" name="youtube" value="<?= $admin["youtube"]; ?>" />
 </label>
</div>
<? } ?>

<label class="field-title">E-mail<em>*</em>:</label>
 <label><input type="text" class="input-xxlarge" name="email" value="<?= set_value('email',$admin["email"]); ?>" />
 <?= form_error('email','<span class="text-error">','</span>'); ?>
 <input type="hidden" name="id" value="<?= $admin["admin_id"]; ?>"  />
 </label>

<? if($admin["level"] != 1){?>

<label class="field-title">Website Title:</label>
<label><input type="text" class="input-xxlarge" name="website_title" value="<?=$admin["website_title"]; ?>" />
</label>	

<label class="field-title">Website Logo:</label>
<label><input class="input-xxlarge" type="file" name="image" />
<?
if( $admin["image"]!= ""){ ?>	
<a href="<?= base_url(); ?>uploads/website/<?= $admin["image"]; ?>" class="blue gallery" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm('Are you sure you want to delete this image ?')){ window.location='<?= base_url(); ?>back_office/settings/delete_image/image/<?= $admin["image"]; ?>/2';  }"  ><img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class='blue'>No Image Available</span>"; } ?>
</span>
 </label>

<!--<label class="field-title">Phone Number:</label>
<label><input type="text" class="input-xxlarge" name="phone" value="<?=$admin["phone"]; ?>" />
</label>

<label class="field-title">Fax Number:</label>
<label><input type="text" class="input-xxlarge" name="mobile" value="<?=$admin["mobile"]; ?>" />
</label>-->

<label class="field-title">Website URL:</label>
<label><input type="text" class="input-xxlarge" name="website" value="<?=$admin["website"]; ?>" />
</label>

<!--<label class="field-title">Address:</label>
<label><textarea class="input-xxlarge" name="address" > <?=$admin["address"]; ?></textarea>
</label>-->

<!--<label class="field-title">Google Map:</label>
<label><textarea class="input-xxlarge" name="google_map" > <?=$admin["google_map"]; ?></textarea>
</label>-->
<? } ?>     						

<? } ?>
</fieldset>
<p class="align-right"><input type="image" src="<?= base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary btn_mrg" /></p>

</form>
</div>


</div><!-- end of div#mid-col -->

<!-- end of div#right-col -->     
<span class="clearFix">&nbsp;</span>     
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 