<div id ='thankyouwrapper'>
<form name = 'thankyou' id='thankyouform' method='post' action='thankyou'>
	<p>
		Thank you note for: <span id='reviewed' class='thank_title'></span>
	</p>
	<!--<p>
		<label for='gift'>What did they give you? (brief title)</label>
	</p>
	<p>
		<input type='text' class='big-border' name='gift' id='thankyou_gift' value='' class='required'/>
	</p> -->
	<p>
	<label for='body'>Be sure to describe the gift.</label>
	</p>
	<p>
		<textarea rows='5' class='big-border' name='body' id='body' value='' class='required'/>
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
