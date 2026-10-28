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

<form action="<?php echo base_url(); ?>back_office/newsletter/send_newsletter" method="post" class="middle-forms" enctype="multipart/form-data">
<?php
if(isset($id)){
echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
$info=array('email'=>'');
}

if(count($ids) == 0){
echo '<div class="alert alert-error-">No Emails Selected</div>';
} else{
$ss= join(',',$ids);
echo '<input type="hidden" name="ids" value="'.$ss.'" />';
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
<fieldset>
<legend></legend>
<label class="field-title">Attach PDF :</label> <label>
<input class="input-xxlarge" name="image" type="file"/>
</label><label class="field-title">Message :</label> <label>
<textarea class="ckeditor" id="message" name="message" rows="60" cols="75" style="width:100%" >
</textarea>	
</label>
</fieldset>
<p class="pull-left"><input type="image" src="<?php echo base_url(); ?>images/bt-send-form-1.png" class="btn btn-primary" /></p>
</form>

</div>
</div>       
</div>
<?php  $this->session->unset_userdata('success_message'); ?>
<?php  $this->session->unset_userdata('error_message'); ?>