<div id="content">
<div id="content-top">
<h2>Dashboard</h2>
<a href="<?= base_url(); ?>back_office/manage_db/create" id="topLink">Create New DataBase</a> 
<span class="clearFix">&nbsp;</span>
</div><!-- end of div#content-top -->
<div id="left-col">
<div class="box">
<h4 class="yellow">Side Menu</h4>
<div class="box-container"><!-- use no-padding wherever you need element padding gone -->
<ul class="list-links">
<li><a href="#">Manage Filters</a></li>
<li><a href="#">Setup a New Site</a>
<ul>
<li><a href="#">Configure Paths</a></li>
<li><a href="#">Define Database Name</a></li>
</ul>
</li>
<li><a href="#">Manage Site Accounts</a></li>
</ul>
</div><!--end of div.box-container -->
</div><!-- end of div.box --><!--end of div.box --><!-- end of div.box -->
</div> <!-- end of div#left-col -->

<div id="mid-col" class="full-col"><!-- end of div.box -->

<div class="box">
<h4 class="white">Data Bases Table</h4>
<div class="box-container">     		
    

<div class="box-container">
<form action="<?= base_url(); ?>back_office/manage_db/submit" name="add_db" method="post" class="middle-forms">
<h3>Add New Database</h3>
<p>Please complete the form below. Mandatory fields marked <em>*</em></p>
<fieldset>
<legend>Fieldset Title</legend>
<ol>
<li class="even">
<label class="field-title" style="width:105px">Database Name <em>*</em>:</label>
 <label><input class="txtbox-short" name="db_name"  value="<?= set_value('db_name'); ?>" />
<? if($this->session->userdata('error_message')){ ?>
<span class="form-error-inline">
<?= $this->session->userdata('error_message'); ?>
</span>
<? } ?>
<?php echo validation_errors('<span class="form-error-inline">','</span>'); ?></label>
<span class="clearFix">&nbsp;</span></li>

	     						
</ol><!-- end of form elements -->
</fieldset>
<p class="align-right">
<input type="image" src="<?= base_url(); ?>images/bt-send-form.gif" onclick="document.forms['add_db'].submit();" /></p>
<span class="clearFix">&nbsp;</span>
</form>
</div><!-- end of div.box-container -->
	
</div><!-- end of div.box-container -->
</div> <!-- end of div.box -->

</div><!-- end of div#mid-col --><!-- end of div#right-col -->     

<span class="clearFix">&nbsp;</span>     
</div>

<? $this->session->unset_userdata('error_message'); ?>