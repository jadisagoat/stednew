<?
$content_name = $table_name;
$cond1["name"] = str_replace("_", " ", $content_name);
$id_content = $this->fct->getonecell("content_type", "id_content", $cond1);
$add_view = '
<?php
$content_name="' . $content_name . '";
$id_content="' . $id_content . '";
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
<li><a href="<?=base_url();?>back_office/' . $table_name . '">' . $content_type . '</a> <span class="divider">/</span></li>
<li class="active"><?= $title; ?></li>
</ul> 
</div>
<div class="hundred pull-left">    
<form action="<?= base_url(); ?>back_office/' . $table_name . '/submit" method="post" class="middle-forms" enctype="multipart/form-data">
<p class="alert alert-info">Please complete the form below. Mandatory fields marked <em>*</em>
<?
if(isset($id)){?>
<input type="hidden" name="id" value="<?= $id; ?>" /> 
<?
} else {
';
foreach ($attr as $val) {
    $add_view .= '$info["' . str_replace(" ", "_", $val["name"]) . '"] = "";
';
}
$add_view .= '}
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
';
$i = 0;

foreach ($attr as $val) {
    $i++;
    $add_view .= '
<label class="field-title">' . ucfirst($val["name"]);
    if ($val["validation"] != "trim") {
        $add_view .= '&nbsp;<em>*</em>:';
    }
    $add_view .= '
</label>
<label>
';
    if ($val["type"] == 3) {
        $add_view .= '
<textarea class="ckeditor" id="' . str_replace(" ", "_", $val["name"]) . '" rows="15" cols="100" name="' . str_replace(" ", "_", $val["name"]) . '" >
<?= set_value("' . str_replace(" ", "_", $val["name"]) . '",$info["' . str_replace(" ", "_", $val["name"]) . '"]); ?>
</textarea>
';
    } elseif ($val["type"] == 4) {
        $add_view .= '<input type="file"  class="input-xxlarge"  name="' . str_replace(" ", "_", $val["name"]) . '" />
<span >
<?
if($info["' . str_replace(" ", "_", $val["name"]) . '"] != ""){ ?>	
<a href="<?= base_url(); ?>uploads/' . $table_name . '/<?= $info["' . str_replace(" ", "_", $val["name"]) . '"]; ?>" class="blue gallery" title="<?= $info["title"]; ?>" >
show image
</a>
&nbsp;&nbsp;&nbsp;
<a class="cur" onclick="if(confirm(\'Are you sure you want to delete this image ?\')){ window.location=\' <?= base_url(); ?>back_office/' . $table_name . '/delete_image/' . str_replace(" ", "_", $val["name"]) . '/<?= $info["' . str_replace(" ", "_", $val["name"]) . '"]; ?>/<?= $info["id_' . $table_name . '"] ?> \' } " >
<img src="<?= base_url(); ?>images/delete.png"  /></a>
<? } else { echo "<span class=\'blue\'>No Image Available</span>"; } ?>
</span>';
    } elseif ($val["type"] == 1) {
        $add_view .= '
    <div class="input-append date" data-date="' . date("d-m-Y") . '" data-date-format="mm/dd/yyyy">
    <input class="span2" size="16" name="' . str_replace(" ", "_", $val["name"]) . '" type="text" 
    value="<?= set_value("' . str_replace(" ", "_", $val["name"]) . '",$this->fct->date_out_formate($info["' . str_replace(" ", "_", $val["name"]) . '"])); ?>">
    <span class="add-on"><i class="icon-th"></i></span>
    </div>';
    } elseif ($val["type"] == 5) {
        $add_view .= '<input type="file"  class="input-xxlarge"  name="' . str_replace(" ", "_", $val["name"]) . '" />';
    } elseif ($val["type"] == 6) {
        $add_view .= '<select name="' . str_replace(" ", "_", $val["name"]) . '"  class="input-xxlarge">
<option  value="0" > - select option - </option>
<option  value="1" <? if($info["' . str_replace(" ", "_", $val["name"]) . '"] == 1) echo \'selected="selected"\'; ?> > Active </option>
<option  value="0" <? if($info["' . str_replace(" ", "_", $val["name"]) . '"] == 0) echo \'selected="selected"\'; ?> > InActive </option>
</select>';
    } elseif ($val["type"] == 7) {
        $cond = array("id_content" => $val["foreign_key"]);
        $content001 = $this->fct->getonecell("content_type", "name", $cond);
        if (str_replace(" ", "_", $content001) == str_replace(" ", "_", $table_name))
            $frn2 = "parent";
        else
            $frn2 = str_replace(" ", "_", $content001);
        $add_view .= '
<? $items = $this->fct->getAll("' . str_replace(" ", "_", $content001) . '","sort_order");?>
<select name="' . str_replace(" ", "_", $val["name"]) . '"  class="input-xxlarge">
<option value="" > - select ' . $content001 . ' - </option>
<? foreach($items as $valll){ ?>
<option value="<?= $valll["id_' . str_replace(" ", "_", $content001) . '"]; ?>" <? if(isset($id)){  if($info["id_' . $frn2 . '"] == $valll["id_' . str_replace(" ", "_", $content001) . '"]){ echo \'selected="selected"\'; } } ?> ><?= $valll["title"]; ?></option>
<? } ?>
</select>';
    } else {
        $add_view .= '<input type="text" class="input-xxlarge" name="' . str_replace(" ", "_", $val["name"]) . '" value="<?= set_value("' . str_replace(" ", "_", $val["name"]) . '",$info["' . str_replace(" ", "_", $val["name"]) . '"]); ?>" />';
    }

    $add_view .= '
<?= form_error("' . str_replace(" ", "_", $val["name"]) . '","<span class=\'text-error\'>","</span>"); ?>
</label>';
}
$add_view .= '
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
<? $this->session->unset_userdata("error_message"); ?> ';
