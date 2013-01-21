<div class='row'>
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	<div class='span9 chunk'>
	
		<form  class='form_wrapper' action="<?php echo site_url('account/profile'); ?>" method="post">
			<div class='control-group'>
				<label for="screen_name" class='control-label'>
					Screen Name:</label>
						<input class='big-border required' type="text" name="screen_name" id="screen_name" value="<?php echo $U->screen_name; ?>" />			
			</div>

			<?php if($individual) { ?>
			<div class='control-group'>		
				<label class='control-label' for="first_name">First Name:</label>
					<input class='required big-border' type="text" name="first_name" id="first_name" value="<?php echo $U->first_name; ?>" />			
			</div>
			<div class='control-group'>
			<label class='control-label' for="last_name">Last Name:</label>
					<input class='required big-border' type="text" name="last_name" id="last_name" value="<?php echo $U->last_name; ?>" />			
			</div>
			<?php } ?>

			<div class='control-group'>
				<label class='control-label' for="type">Profile Type:</label>
					<select name="type" id="type">
						<option  value="individual">Personal / Individual</option>
						<option value="nonprofit">Non-Profit Organization</option>
						<option value="business">Business</option>
					</select>			
			</div>
			
			<div class='control-group'>
				<label class='control-label' for="email">Email Address:</label>
					<input maxlength="255" size="30" class="email big-border required" type="text" name="email" id="email" value="<?php echo $U->email; ?>" />			
				</label>
			</div>

			<div class='control-group'>
				<label class='control-label 'for="bio">Bio</label>
						<textarea rows='5'  class='big-border required' name="bio" id="bio"><?php echo $U->bio; ?></textarea>			
			</div>

			<?php if($individual) { ?>
			<div class='control-group'>
				<label class='control-label' for="occupation">Occupation:</label>
					<input class='big-border' type="text" name="occupation" id="occupation" value="<?php echo $U->occupation; ?>" />			
			</div>
			<?php } ?>
			<div class='control-group'>
				<label class='control-label' for="url">URL:</label>
					<input class='big-border' type="text" name="url" id="url" value="<?php echo $U->url; ?>" />			
			</div>

				<input class="btn btn-primary btn-large" type="submit" value="Save" /></td>

		</form>		

	</div>
</div>

<script type='text/javascript'>
$(function(){

	$('#type option[value=<?php echo $U->type; ?>]').attr('selected', 'selected')
	
});
</script>
