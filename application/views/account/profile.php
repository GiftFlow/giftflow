<div id='account_profile' class='two_panels'>
	
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
	<form action="<?php echo site_url('account/profile'); ?>" method="post">
<table class="form">
	<tr class="row" id="row_screen_name">
		<td class="label"><label for="screen_name">Screen Name:</label></td>

		<td class="field">
			<input type="text" name="screen_name" id="screen_name" value="<?php echo $U->screen_name; ?>" />			
		</td>
	</tr>
	<?php if($individual) { ?>
	<tr class="row" id="row_first_name">
		<td class="label"><label for="first_name">First Name:</label></td>

		<td class="field">
			<input type="text" name="first_name" id="first_name" value="<?php echo $U->first_name; ?>" />			
		</td>
	</tr>
	<tr class="row" id="row_last_name">
		<td class="label"><label for="last_name">Last Name:</label></td>
		<td class="field">
			<input type="text" name="last_name" id="last_name" value="<?php echo $U->last_name; ?>" />			
		</td>

	</tr>
	<?php } ?>
	<tr class="row" id="row_type">
		<td class="label"><label for="type">Profile Type:</label></td>
		<td class="field">
			<select name="type" id="type">
				<option  value="individual">Personal / Individual</option>
				<option value="nonprofit">Non-Profit Organization</option>
				<option value="business">Business</option>
			</select>			
		</td>

	</tr>
	<tr class="row required" id="row_email">
		<td class="label"><label for="email">Email Address:</label></td>
		<td class="field">
			<input maxlength="255" size="30" class="email required" type="text" name="email" id="email" value="<?php echo $U->email; ?>" />			
		</td>
	</tr>
	<tr class="row" id="row_bio">
		<td class="label"><label for="bio">About</label></td>

		<td class="field">
			<textarea name="bio" id="bio"><?php echo $U->bio; ?></textarea>			
		</td>
	</tr>
	<tr class="row" id="row_phone">
		<td class="label"><label for="phone">Phone (emergencies only):</label></td>
		<td class="field">
			<input type="text" name="phone" id="phone" value="<?php echo $U->phone; ?>" />			
		</td>
	</tr>
	<?php if($individual) { ?>
	<tr class="row" id="row_occupation">
		<td class="label"><label for="occupation">Occupation:</label></td>
		<td class="field">
			<input type="text" name="occupation" id="occupation" value="<?php echo $U->occupation; ?>" />			
		</td>
	</tr>
	<?php } ?>
	<tr class="row" id="row_url">
		<td class="label"><label for="url">URL:</label></td>
		<td class="field">
			<input type="text" name="url" id="url" value="<?php echo $U->url; ?>" />			
		</td>
	</tr>
	<tr class="buttons">
		<td colspan="2"><input class="btn btn-primary btn-large" type="submit" value="Save" /></td>
	</tr>

</table>
</form>		

	</div>
	<!-- eof div.right_content -->
</div>
<!-- eof div.two_panels -->


<script type='text/javascript'>
$(function(){
	//$('#type').fadeOut('fast');
	$('#type option[value=<?php echo $U->type; ?>]').attr('selected', 'selected')

	
	
});
</script>