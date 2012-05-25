<div id ='thankyouwrapper'>
<form name = 'thankyou' id='thankyouform' method='post' action='thankyou'>
	<p>
		Thank you note for: <span id='reviewed' class='thank_title'></span>
	</p>
	<p>
		<label for='gift'>What did they give you?</label>
	</p>
	<p>
		<input type='text' class='big-border' maxlength ='100' name='thankyou_gift' id='thankyou_gift' value='' class='required'/>
	</p>
	<p>
		<label for='rating_select'>How would you rate the experience?</label>	
	</p>
	<p>
		<select name='rating_select' id='rating_select' class='required'>
			<option value='positive'>Positive</option>
			<option value='neutral'>Neutral</option>
			<option value='negaitve'>Negative</option>
		</select>
	</p>
	<p>
	<label for='body'>Write a thank you note:</label>
	</p>
	<p>
		<textarea rows='5' class='big-border' name='body' id='body' value='' class='required'/></textarea>
	</p>
	<!-- hidden fields -->
	<input type='hidden' id='reviewed_id' name='reviewed_id' value=''/>
	<input type='hidden' id='reviewed_email' name='reviewed_email' value=''/>
	<p>
		<input type='submit' class='button' value='Send'/>
		<a class='button closeClass' style='margin-left:100px;' href='#'>Cancel</a>
	</p>
	<span id='errortext'></span>
</form>
</div>
