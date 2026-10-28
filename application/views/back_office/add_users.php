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
<li><a href="<?=base_url();?>back_office/users">List Users</a> <span class="divider">/</span></li>
<li><?= $title; ?></li>
</ul>
</div>    
<div class="hundred pull-left">  
<form action="<?= base_url(); ?>back_office/users/submit" method="post" class="middle-forms"  enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){
	echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('name'=>'','email'=>'','phone'=>'','address'=>'','username'=>'','password'=>'');
}
?>
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
<legend><?=$title;?></legend>

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


<label class="field-title">Address:</label>
<label>
<textarea class="hundred" name="address" rows="7" cols="25"><?= set_value('address',$info["address"]); ?></textarea>
</label>
<label class="field-title">User Name<em>*</em>:</label>
 <label><input type="text" class="input-xlarge" name="username" autocomplete="off" value="<?= set_value('username',$info["username"]); ?>" />
 <?= form_error('username','<span class="text-error">','</span>'); ?>
 </label>
<label class="field-title">Password<em>*</em>:</label>
 <label><input type="password" class="input-xlarge" name="password" value="<?= set_value('password',$info["password"]); ?>" />
  <?= form_error('password','<span class="text-error">','</span>'); ?>
 </label>  						

</fieldset>
<p class="align-right"><input type="image" src="<?= base_url(); ?>images/bt-send-form.png" value="Save Changes" class="btn btn-primary" /></p>
</form>
</div>


</div><!-- end of div#mid-col -->

<!-- end of div#right-col -->     
<span class="clearFix">&nbsp;</span>     
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 