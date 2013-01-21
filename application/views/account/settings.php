<div class='row'>
	<div class='span2 chunk'>		
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	<div class='span9 chunk'>

		<form id='settings' name='settings' method='post'>
		<table class="form">
			<div class='control-group'>
				<label for="email">Email:</label>
					<input class='required big-border' type="text" name="email" id="email" value="<?php echo $email;?>" />			
			</div>
			<div class='control-group'>	
				<label for="new_password">New Password:</label>
					<input class='required big-border' type="password" name="new_password" id="new_password" value="" autocomplete='false' />			
			</div>
			<div class='control-group'>
				<label for="confirm_password">Confirm New Password:</label>
					<input class='required big-border' type="password" name="confirm_new_password" id="confirm_new_password" value="" />			
			</div>
			<div class='control-group'>
				<label for="time_zone">Time Zone:</label>
					<select name="timezone" id="timezone">
						<option>Select a Timezone</option>
						<?php
						foreach($timezone_list as $key=>$val){ ?>
						<option value="<?php echo $key;?>" <?php if(!empty($userdata['timezone'])&&$userdata['timezone'] == $key){ echo " selected='selected' "; } ?>>
							<?php echo $val; ?>
						</option>
						<?php } ?>
					</select>
			</div>
			<div class='control-group'>
				<label for="language">Language</label>
					<select name="language" id="language">
						<?php
						foreach($language_list as $key=>$val){ ?>
						<option value="<?php echo $key;?>" <?php if(!empty($userdata['language'])&&$userdata['language'] == $key){ echo " selected='selected' "; } ?>>
							<?php echo $val; ?>
						</option>
						<?php } ?>
					</select>
			</div>

		</table>
		
		<input type='submit' class="btn btn-primary" value='Update Settings'>
		</form>

	</div>
</div>

<script type='text/javascript'>
$(function(){
	$("input#new_password").val('');
});
</script>
