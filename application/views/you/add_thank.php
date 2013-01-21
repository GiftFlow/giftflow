<div class='row'>
	<div class='span2 chunk'>
		<?php echo $menu;?>
	</div>
<div class='span9 chunk'>
	
		<h3>Record a gift. Thank the giver.</h3>
		

	<form name = 'thankyou' id='thankyouform' method='post' action="<?php echo site_url('thank/addThank'); ?>">
			<label for = 'thankEmail'>What is their email?</label>
			<p>
				<input type='text' class='big-border required' name='thankEmail' id='thankEmail'value=''/>
			<p>
			<label for='gift'>Write a brief title for the gift you received.</label>
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

		<p>The person you choose to thank will receive a notification asking them to accept or decline. If they accept, your thank will become visible on their profile.</p>
		<p>Go ahead and thank someone who does not yet use GiftFlow. They will receive an email asking them to join. </p>
		
</div>
</div>


<script type='text/javascript'>
$(function() {


GF.Users.initialize($('#thankEmail'));

});

</script>
