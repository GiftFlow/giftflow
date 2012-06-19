<h1>Edit a Location</h1>
<form id='edit_location' method='post' action='<?php echo site_url('account/locations/'.$L->id.'/edit'); ?>'>
	<p><label>Title</label>
	<input type='text' name='title' value='<?php echo $L->title; ?>' />
	</p>
	<p>
		<input type='submit' value='Save' />
		<button class='secondary' id='close_this'>Cancel</button>
	</p>
</form>
