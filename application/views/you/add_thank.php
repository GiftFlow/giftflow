<div class='row'>
	<div class='span2 chunk'>
		<?php echo $menu;?>
	</div>
<div class='span9 chunk'>
	
		<h3 class='dash_title'>Record a gift. Thank the giver.</h3>
		

	<form  class='form-horizontal form_wrapper' name = 'thankyou' id='thankyouform' method='post' action="<?php echo site_url('thank/add_thank'); ?>">
		<fieldset>
				<div class='control-group'>
					<label class='control-label' for = 'thank_name'>Screen name or email:</label>
						<div class='controls'>
						<input type='text' class='big-border required' id='thank_name' name='thank_name' value=''/>
						</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='gift'>Title the gift:</label>
					<div class='controls'>
						<input type='text' class='big-border required' maxlength='30' name='gift' id='thankyou_gift' value=''/>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='body'>Express your gratitude:</label>
					<div class='controls'>
						<textarea rows='5' class='big-border required' name='body' id='body' value=''></textarea>
					</div>
				</div>
						<input type='hidden' name='thank_id' id='thank_id' value=''/>
						<input type='submit' class='btn btn-primary btn-medium' value='Send'/>
						<a id='thank_cancel' class='btn' href="<?php echo site_url('you');?>">Cancel</a>
					</p>
					<span id='thank_errortext'></span>
			</fieldset>
				</form>

				<p>The person you choose to thank will receive a notification asking them to accept or decline. If they accept, your thank will become visible on their profile.</p>
				<p>Go ahead and thank someone who does not yet use GiftFlow. They will receive an email asking them to join. </p>
				
		</div>
		</div>


<script type='text/javascript'>
$(function() {

	GF.Users.initialize($('#thank_name'));
	//When the user selects a name from the list, the users id
	//is set as the thank_id hidden form field.


});

</script>
