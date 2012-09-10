<form name='profile_message' id='messageform' method='post'>
		<p>
		<label for 'message'>Write your message for <?php echo $u->screen_name; ?> here</label>
			<textarea rows='5' class='big-border required' name='body' id='message_body' value=''></textarea>
		</p>
		<p>
			<input type='submit' class='btn' value='Send'/>
			<input type='hidden' name='formtype' value='message'/>
			<button id='message_cancel' class='btn'>Cancel</button>
		</p>
		<span id='message_errortext'></span>
</form>

		
