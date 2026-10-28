<script>
$(document).ready(function(){
	$('.open_validate').toggle(function(){
		var id=this.id;
		var sel="#validate_"+id;
		$(sel).slideDown('fast');
	},function(){
	var id=this.id;
		var sel="#validate_"+id;
		$(sel).slideUp('fast');	
	});
	$('#open_all1').toggle(function(){
		$('.recycle_col').css('height','auto');
		$(this).html('&and;&nbsp;Close All');
	},function(){
	$('.recycle_col').css('height','0');
		$(this).html('&or;&nbsp;Open All');	
	});
$('.min_length').click(function () {
	var id = this.id;
	var sel='#min_num'+id;
	$(sel).toggle();
});
$('.max_length').click(function () {
	var id = this.id;
	var sel='#max_num'+id;
	$(sel).toggle();
});

$('#submit_validate').click(function(){
	$('#form_validate').submit();
});
});
</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>
<div class="span10">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">  
<div id="result"></div>   
<form id="form_validate" action="<?= base_url(); ?>back_office/recycle/delete_all" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Delete Or Restore any item you want.

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
<input type="hidden" name="count_content" value="<?= count($info); ?>"  />
<div class="accordion" id="accordion4">
<?
$i=0;
foreach($info as $val){ 
$i++;
?>
<div class="accordion-group">
<div class="accordion-heading" style="padding:8px 15px;">
<input type="checkbox" id="checkAllAuto<?=$i;?>" />
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion4" href="#collapsess_<?=$i;?>" style="display:inline;">
<input type="hidden" name="check_parent_<?=$i;?>" value="<?= $val["name"]; ?>"   />
&nbsp;<?= ucfirst($val["name"])." Section"; ?>
</a>
</div>
<div id="collapsess_<?=$i;?>" class="accordion-body collapse recycle_col">
<div class="accordion-inner">
<ul class="nav nav-list">
<li class="active" >
<a >Item Title
<span class="pull-right" >Deleted Date     </span></a>
</li>
<?
$q= "SELECT * FROM `".str_replace(" ","_",$val["name"])."` WHERE deleted = 1 ORDER BY deleted_date DESC";
$query=$this->db->query($q);
$res= $query->result_array();
$j=0;
foreach($res as $v){
$j++;
($j %2 !=0)? $row_color='#ffffff' : $row_color='$F3F3F3';
?>
<li><a>
<input type="checkbox" name="cehcklist_<?=$i; ?>[]" value="<?= $v["id_".str_replace(" ","_",$val["name"])];  ?>"  />&nbsp;&nbsp;
<?= $v["title"]; ?>
<small class="pull-right" ><?= $v["deleted_date"]; ?></small>
</a></li>
<? } ?>
</ul>
</div>
</div>
</div>
<?	
}
?>
</div>
<table border="0" width="100%" style="margin-top:15px;" >
<tfoot>
<tr>
<td class=""><input type="checkbox" id="checkAllAuto" />&nbsp;Select All</td>
<td colspan=" "><span id="open_all1" class="btn"> &or;&nbsp;Open All</span></td>
<td colspan="4"><div class="pull-right">
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="restore_all">Restore </option>
<option value="delete_all">Delete </option></select>
<a class="btn btn-primary btn_mrg" onclick="document.forms['form_validate'].submit();" style="cursor:pointer;"><span>perform action</span></a></div></td>
</tr>
</tfoot>
</table>
</fieldset>

</form>
</div>
</div>    
</div>
<? $this->session->unset_userdata("success_message"); ?> 
<? $this->session->unset_userdata("error_message"); ?> 