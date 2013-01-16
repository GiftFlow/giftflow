
	<form name = 'thankyou' id='thankyouform' method='post' action="<?php echo site_url('thank/addThank'); ?>">
			<label for = 'thankEmail'>What is their email?</label>
			<p>
				<input type='text' class='big-border required' name='thankEmail' id='thankEmail'value=''/>
			<p>
			<label for='gift'>Add a brief title for the gift.</label>
			</p>
			<p>
				<input type='text' class='big-border required' maxlength='30' name='gift' id='thankyou_gift' value=''/>
			</p>
			<p>
			<label for='body'>Write your thank you note here.</label>
			</p>
			<p>
				<textarea rows='5' class='big-border required' name='body' id='body' value=''></textarea>
			</p>
				<!-- hidden fields -->
			<p>
				<input type='submit' class='btn' value='Send'/>
				<a id='thank_cancel' class='btn' href="<?php echo site_url('you');?>">Cancel</a>
			</p>
			<span id='thank_errortext'></span>
		</form>

