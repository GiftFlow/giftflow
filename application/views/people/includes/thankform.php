<form name = 'thankyou' class='profile_form_container' method='post' action="<?php echo site_url('thank/profileThank'); ?>">
					<p>
					<label for='gift'>What did <?php echo $u->screen_name; ?> give you? (brief title)</label>
					</p>
					<p>
						<input type='text' class='big-border required' name='gift' id='thankyou_gift' value=''/>
					</p>
					<p>
					<label for='body'>Be sure to describe the gift.</label>
					</p>
					<p>
						<textarea rows='5' class='big-border required' name='body' id='body' value=''></textarea>
					</p>
						<!-- hidden fields -->
						<input type='hidden' id='recipient_id' name='recipient_id' value='<?php echo $u->id; ?>'/>
						<input type='hidden' id='recipient_email' name='recipient_email' value='<?php echo $u->email; ?>'/>
						<input type='hidden' name='formtype' value='thankyou'/>
					<p>
						<input type='submit' class='btn btn-primary' value='Send'/>
						<a id='thank_cancel' class='btn' href='#'>Cancel</a>
					</p>
					<span id='thank_errortext'></span>
				</form>
