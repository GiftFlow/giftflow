<form name = 'thankyou' id='thankyouform' method='post' action=''>
	<p>
		<label for='reviewed'>Recipient</label>
	</p>
	<p>
		<input type='text' maxlength='75' size='20' name='reviewed' id='reviewed' class='big-border' value='Who is it gonna be?' />
	</p>
	<p>
		<label for='body'>Write your note here: </label>
	</p>
	<p>
		<textarea rows='5' class='big-border' name='body' id='body' value='' class='required'/>
	</p>
	<!-- hidden fields -->
	<input type='hidden' id='reviewed_id' value=''/>
	<input type='hidden' id='reviewed_email' value=''/>
	<p>
		<input type='submit' class='button' value='Send'/>
		<a class='button closeClass' style='margin-left:100px;' href='#'>Cancel</a>
	</p>
</form>
