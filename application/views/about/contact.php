<div class='row'>

	<div class="span8">
	
		<h3>Send all inquiries to :</h3>  <h2>info@giftflow.org </h2>
		
		
		<p>You can also send us an email using the form below:</p>
		
		<form action="<?php echo site_url('about/contact_giftflow'); ?>" method="post">
			<table class="form">
					<tr class="row" id="name">
						<td class="label">
							<label for="name">Name:</label>
						</td>
						<td class="field">
							<input type="text" name="name" id="name" value="" class=""/>			
						</td>
					</tr>
					<tr class="row" id="email">
						<td class="label">
							<label for="email">Email:</label>
						</td>
						<td class="field">
							<input type="text" name="email" id="email" value="" class="required"/>			
						</td>
					</tr>
					<tr class="row" id='message'>
						<td class="label">
							<label for="message">Message:</label>
						</td>
						<td class="field">
							<textarea rows="5" class="big-border" name="message" id="message" value="" class="required"></textarea>		
						</td>
					</tr>
			</table>
			<input type="submit" name="submit" class="button btn" value="Submit">
			<input type="reset" name="reset" class="button btn" value="Reset">
		</form>
			
		
	</div>
</div>

<script type='text/javascript'>
$(function(){

});
</script>
	
