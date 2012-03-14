<div id='login'>
<h1>Enter New Password</h1>

<table>

<tr>
	
	<td style='width: 500px;'>
		<p style='font-size: .8em;'>Reset your password</p> <?php echo form_errors(); ?>
		<form id="new_password" name="new_password" action="<?php echo site_url('member/enter_new_password'); ?>" method="post">
			<p>
				<label>Email Address</label>
			</p>
			<p>
				<input type="text" name='email' class='required email' id='email' value='' />
			</p>
			<p>
			<label for="password">New Password</label>
			</p>
			<p>
				<input maxlength="45" size="30" class="required" type="password" name="password" id="password" value="" />			
			</p>
			<p>
				<label for="confirm_password">Confirm New Password</label>
			</p>
			<p>
				<input maxlength="45" size="30" class="required" type="password" name="confirm_password" id="confirm_password" value="" />
			</p>

			<p>

				<input type='submit' class='submit' value="Reset Password" />
			</p>
		</form>
	</td>
</tr>
</table>
</div>
<script type='text/javascript'>
$(function(){
	$("input:submit").button();
	$("#new_password").validate({
		rules: { 
			confirm_password: {
				equalTo: "#password"
			}
		},
		messages: {
			confirm_password: {
				equalTo: "Passwords Must Match"
			}
		}
	});
	$("p.alert_error").css('margin-bottom', '0px');
	
});
</script>