
<div style='clear: both; margin-bottom: 20px;'>
<a id='go_back' class='secondary' href='<?php echo site_url('account/photos'); ?>'>Back to photos</a>
</div>
<div id='photo_edit_left'>
<img src='<?php echo $photo->url; ?>'  alt='<?php if(!empty($photo->caption)){ echo $photo->caption; } else { echo "Edit This Photo"; } ?>' class='bordered' />
</div>
<div id='photo_edit_right'>
<form method='post' name='photo_edit' class='ui-widget'>
<input type='hidden' name='photo_id' value="<?php echo $photo->id; ?>" />
<!--edit caption-->
<p><label>Caption</label><br />
<textarea name='caption'><?php echo $photo->caption; ?></textarea>
</p>
<p>
<?php if(!empty($default) && $default) { ?>
This is currently your default photo.
<?php } else { ?>
	<label><input type='checkbox' name='default' value='true' />Make this my default photo</label>

<?php } ?>
</p>
<p><input type='submit' id='submit' value='Save Changes'  /><a class='secondary' href='<?php echo site_url('account/photos'); ?>'>Cancel</a></p>
<p><a class='secondary' href='<?php echo site_url('account/photos/'.$photo->id.'/delete'); ?>'>Delete</a></p>
</form>
</div>
<script type='text/javascript'>
$(function(){
 $("label").inFieldLabels();
 $("a#go_back").button( { icons: { primary: 'ui-icon-arrowthick-1-w' } });
 $("input#submit").button( { icons: { primary: 'ui-icon-arrowthick-1-w' } });
 $("a.secondary").button();
 $(".alert_success").hide().slideDown(300);

// $("input:submit").button( { icons: {primary: 'ui-icon-circle-check' } });
});
</script>