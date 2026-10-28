<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php  $this->load->view('back_office/includes/left_box'); ?>
</div>

<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><a href="<?php echo base_url();?>back_office/newsletter">List Emails</a> <span class="divider">/</span></li>
<li class="active"><?php echo $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">  
     

<form action="<?php echo base_url(); ?>back_office/newsletter/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?php
if(isset($id)){
echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('email'=>'');
}
?>
<?php  if($this->session->userdata('success_message')){ ?>
<div class="alert alert-success">
<?php echo $this->session->userdata('success_message'); ?>
</div>
<?php  } ?>
<?php  if($this->session->userdata('error_message')){ ?>
<div class="alert alert-error">
<?php echo $this->session->userdata('error_message'); ?>
</div>
<?php  } ?>
</p>
<fieldset>
<legend></legend>
<label class="field-title">E-mail <em>*</em>:</label> <label>
<div class="input-prepend">
<span class="add-on"><i class="icon-envelope"></i></span>
<input type="text" class="input-xxlarge" name="email" value="<?php echo set_value('email',$info["email"]); ?>" /></div>
<?php echo form_error('email','<span class="text-error">','</span>'); ?>
</label>
</fieldset>
<p class="pull-left"><input type="image" src="<?php echo base_url(); ?>images/bt-send-form.png" class="btn btn-primary" /></p>
</form>

</div>
</div>    
</div>
<?php  $this->session->unset_userdata('success_message'); ?>
<?php  $this->session->unset_userdata('error_message'); ?>