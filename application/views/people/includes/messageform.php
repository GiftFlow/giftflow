<form name='profile_message' id='messageform' method='post' class='profile_form_container'>
		<p>
		<label for 'message'>Write your message for <?php echo $u->screen_name; ?> here</label>
			<textarea rows='5' class='big-border required' name='body' id='message_body' value=''></textarea>
		</p>
		<p>
			<input type='hidden' name='formtype' value='message'/>
			<input type='hidden' name='recip_id' value ='<?php echo $u->id; ?>'/>
			<input type='submit' class='btn btn-primary' value='Send'/>
			<a href='#' id='message_cancel' class='btn'>Cancel</a>
		</p>
		<span id='message_errortext'></span>
</form>

		
