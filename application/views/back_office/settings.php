<div class="grid_16" id="content">
<!-- CONTENT TITLE -->
<div class="grid_9">
<h1 class="content_edit"><?= $title; ?></h1>
</div>
<!-- CONTENT TITLE RIGHT BOX -->
<div class="grid_6" id="eventbox"><a href="#" class="inline_tip">Here would come a small tip of using this admin</a></div>
<div class="clear">
</div>
<!--    TEXT CONTENT OR ANY OTHER CONTENT START     -->
<div class="grid_15" id="textcontent">
<!--<h2>This is a h2 subheading</h2>
<p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla accumsan  mauris a enim aliquet at elementum diam condimentum. Donec et sem eros.  Morbi mollis accumsan pellentesque. Duis ultricies, purus in sodales  luctus, urna dolor ultrices ligula, luctus faucibus ante dolor sit amet  tortor. Fusce non purus eros, id pulvinar ligula. Quisque ullamcorper  placerat libero. Mauris pretium purus eu nibh adipiscing pretium. Nam  libero ipsum, laoreet quis convallis id, viverra id dolor. Praesent  dignissim nisl a mauris ultrices eget ultrices libero adipiscing. Fusce  eget pretium nunc. </p>-->
<!--<p>Phasellus elit ipsum, euismod sit amet dignissim sed, varius at  velit. Praesent in tortor sem. Suspendisse potenti. Sed auctor laoreet  metus, quis luctus augue ullamcorper ut. Aenean ultricies interdum  pellentesque. Integer eget quam leo, ut vulputate purus. Suspendisse  semper commodo tellus, quis commodo ligula condimentum vel. </p>-->
<!--<form id="edit" action="" method="post">
<label>Title</label>
<input type="text" class="largeInput wide" />
<label>Edit Content</label>
<textarea id="wysiwyg" class="largeInput wide" rows="7" cols="30">asdsad</textarea>
<label>Please select some option below</label>
<select class="largeInput">
<option>This is one option</option>
<option>This is second option</option>
<option>This is third option</option>            
</select>
<label>You can use any if the buttons below to submit this form (these are A tags)</label>

<a class="button"><span>Submit Form</span></a>
<a class="button_grey"><span>Submit Form</span></a>
<a class="button_ok"><span>Update information</span></a>
<a class="button_notok"><span>Delete this record</span></a>
<a class="button_grey_round"><span>This is a rounded button</span></a>
</form>--><br />

<?
//print_r($admin);

?>

<table width="100%" cellpadding="0" cellspacing="0" id="box-table-a" summary="ADMIN SETTINGS">
<tr>
<th>Admin Name</th>
<td><input type="text" class="largeInput wide" value="<?= $admin["admin_name"]; ?>" /></td>
</tr>
<tr>
<th>UserName</th>
<td><input type="text" class="largeInput wide" value="<?= $admin["user_name"]; ?>"  /></td>
</tr>
<tr>
<th>Password</th>
<td><input type="password" class="largeInput wide" value="<?= $admin["password"]; ?>"  /></td>
</tr>
<tr>
<th>E-mail</th>
<td><input type="text" class="largeInput wide" value="<?= $admin["email"]; ?>" /></td>
</tr>
<tr>
<th>Website Title</th>
<td width="80%"><input type="text" class="largeInput wide" value="<?= $admin["website_title"]; ?>" /></td>
</tr>
<tr>
<th>URL</th>
<td><input type="text" class="largeInput wide" value="<?= $admin["URL"]; ?>"  /></td>
</tr>
<tr style="display:none">
<th>FaceBook</th>
<td><input type="text" class="largeInput wide" value="<?= $admin["facebook"]; ?>" /></td>
</tr>
<tr style="display:none">
<th>Twitter</th>
<td><input type="text" class="largeInput wide" value="<?= $admin["twitter"]; ?>"  /></td>
</tr>
<tr style="display:none">
<th >Skype</th>
<td><input type="text" class="largeInput wide" value="<?= $admin["skype"]; ?>"  /></td>
</tr>
<tr style="display:none">
<th>YouTube</th>
<td><input type="text" class="largeInput wide" value="<?= $admin["youtube"]; ?>"  /></td>
</tr>
<tr><td colspan="2" align="right" width="100%" style="text-align:right" ><a href="" class="button_ok" ><span>Save</span></a></td></tr>
</table>
<div class="clear"></div><br />
<!--NOTIFICATION MESSAGES-->
<!--<p class="info" id="success"><span class="info_inner">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</span></p>
<p class="info" id="error"><span class="info_inner">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</span></p>
<p class="info" id="warning"><span class="info_inner">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</span></p>
<p class="info" id="info"><span class="info_inner">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</span></p>    -->
</div>
<div class="clear"> </div>
<!-- END CONTENT-->    
</div>
<div class="clear"> </div>


<div class='hidden'>
<div id='inline_example2' title="This is a modal" style='padding:10px; background:#fff;'>
<p><strong>This content comes from the second hidden element on this page.</strong></p>
</div>
</div>