<script>
$(function(){
$('.checkAllAuto').click(function(){
var id=this.id;
$("INPUT.checkAllAuto"+id+"[type='checkbox']").attr('checked', $('.checkAllAuto_'+id).is(':checked'));    
});
});
</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>
<div class="span10" >
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">  
<form action="<?= base_url(); ?>back_office/control/update_admin_rules" method="post" class="middle-forms">
<?
if(isset($id)){
	echo '<input type="hidden" name="id" value="'.$id.'" />';
} else {	
//$info=array('name'=>'','email'=>'','phone'=>'','address'=>'','username'=>'','password'=>'');
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
<div id="to-do-list">
<? 
$i = 0;
foreach($info as $val){ 
$i++;
if($i  % 2 == 0 )
$even ='even';
else
$even ='';
?>
<label>
<input type="hidden" name="" value="cehcklist"  />
<? // if($val["rules"] != "VIEWS"){ ?>
<h5>
<input type="checkbox" name="cehcklist[]" value="<?= $i; ?>"  <? if($val["active"] == 1) echo 'checked="checked"'; ?>/>
<input type="hidden" value="<?= $val["rules"]; ?>" name="rule<?= $i; ?>"  />
<?= $val["rules"]; ?></h5>
<? //  } ?>
</label>
<? } ?>
<label><input type="checkbox" id="checkAllAuto" /></label>

</div>
<label>
<input type="image" src="<?= base_url(); ?>images/bt-send-form.gif" class="btn btn-primary " /></label>

</form>
</div>
</div>     
</div>
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 