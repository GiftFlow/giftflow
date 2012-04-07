<div id='account_settings' class='two_panels'>
	
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>

		<form id='settings' name='settings' method='post'>
		<table class="form">
		
			<tr class="row" id="row_email">
				<td class="label"><label for="email">Email:</label></td>
				<td class="field">
					<input type="text" name="email" id="email" value="<?php echo $email;?>" />			
				</td>
			</tr>
			
			<tr class="row" id="row_new_password">
				<td class="label"><label for="new_password">New Password:</label></td>
				<td class="field">
					<input type="password" name="new_password" id="new_password" value="" autocomplete='false' />			
				</td>
			</tr>
			<tr class="row" id="row_confirm_new_password">
				<td class="label"><label for="confirm_password">Confirm New Password:</label></td>
				<td class="field">
					<input type="password" name="confirm_new_password" id="confirm_new_password" value="" />			
				</td>
			</tr>
			
			<tr class="row" id="row_time_zone">
				<td class="label"><label for="time_zone">Time Zone:</label></td>
				<td class="field">
					<select name="timezone" id="timezone">
						<option>Select a Timezone</option>
						<?php
						foreach($timezone_list as $key=>$val){ ?>
						<option value="<?php echo $key;?>" <?php if(!empty($userdata['timezone'])&&$userdata['timezone'] == $key){ echo " selected='selected' "; } ?>>
							<?php echo $val; ?>
						</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			
			<tr class="row" id="row_language">
				<td class="label"><label for="language">Language</label></td>
				<td class="field">
					<select name="language" id="language">
						<?php
						foreach($language_list as $key=>$val){ ?>
						<option value="<?php echo $key;?>" <?php if(!empty($userdata['language'])&&$userdata['language'] == $key){ echo " selected='selected' "; } ?>>
							<?php echo $val; ?>
						</option>
						<?php } ?>
					</select>
				</td>
			</tr>

		</table>
		
		<input type='submit' value='Update Settings'>
		</form>

	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$("input#new_password").val('');
	$("input:submit").button();
});
</script>
