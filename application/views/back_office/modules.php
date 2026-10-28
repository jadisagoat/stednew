<script type="text/javascript" src="<?= base_url(); ?>js/jquery.tablednd_0_5.js"></script>
<script>
function activate_row(id){
var sel='#cell_'+id;
$(sel).load('<?= base_url(); ?>back_office/modules/activate/'+id);

}


$(function(){

$('#table-1').tableDnD({
onDrop: function(table, row) {
var ser=$.tableDnD.serialize();
$('#result').load('<?= base_url(); ?>back_office/modules/sorted?'+ser);
}
});
});
</script>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<? $this->load->view('back_office/includes/left_box'); ?>
</div>
<!-- end of div#left-col -->

<div class="span10 cont_h">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li><?= $title; ?></li>
</ul>
</div>
<div class="hundred pull-left">  
<div id="result"></div>   
<form action="<?= base_url(); ?>back_office/users/delete_all" method="post" name="list_form" >    		
<table  class="table table-striped">
<thead>
<? if($this->session->userdata('success_message')){ ?>
<tr><td colspan="4" align="center" style="text-align:center">
<span class="form-confirm-inline">
<?= $this->session->userdata('success_message'); ?>
</span>
</td>
<tr>
<? } ?>
<tr>
<td>&nbsp;</td>
<td>Module Name</td>
<td>Activate</td>
<td>Action</td>
</tr>
</thead>
<tfoot>

<!--<tr>
<td class="col-chk"><input type="checkbox" id="checkAllAuto" /></td>
<td colspan="4"><div class="align-right">
<select class="form-select" name="check_option">
<option value="option1">Bulk Options</option>
<option value="delete_all">Delete All</option></select>
<a class="button" onclick="document.forms['list_form'].submit();" style="cursor:pointer;"><span>perform action</span></a></div></td>
</tr>-->

</tfoot>
<tbody>
<? 
if(count($info) > 0){
$i=0;
foreach($info as $val){
$i++; 
($i%2==0)? $odd='' : $odd='odd';
?>
<tr class="<?= $odd; ?>" id="<?=$val["id_module"]; ?>">
<td class="col-chk">&nbsp;&raquo;&nbsp;</td>
<td class="" width=""><?= $val["title"] ; ?></td>
<td class="" width="" id="cell_<?=$val["id_module"];?>">
<? if($val["activate"]==1){ echo "<span class='label label-success'>enable</span>"; } else { echo "<span class='label label-important'>disable</span>"; } ?></td>
<td class="row-nav">
<a onclick="if(confirm('Are you sure you want activate this module ?')){ activate_row('<?=$val["id_module"];?>'); }" class="table-activate-link cur" >
Activate</a></td>
</tr>
<? }  } else { echo '<tr class="odd"><td colspan="4" style="text-align:center;">No records available . </td></tr>'; } ?>
</tbody>
</table>  	
</form>

</div>



</div>

<!-- end of div#right-col -->     
<span class="clearFix">&nbsp;</span>     
</div>
<? $this->session->unset_userdata('success_message'); ?> 
<? $this->session->unset_userdata('error_message'); ?> 