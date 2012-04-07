/*
*  DEPRECATED
*
*/


<?php echo form_open_multipart('account/do_upload');?>
<p><label>Select a photo</label>
<input type='file' name='userfile' />
</p>
<input type='submit' value='Upload Photo' />
</form>
<script type='text/javascript'>
$(function(){
	$("input:submit").button();
});
</script>