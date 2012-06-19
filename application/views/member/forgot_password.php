<div id='login'>
<h1>Forgot Your Password?</h1>

<table>

<tr>
	
	<td style='width: 500px;'>
		<p style='font-size: .8em;'>Enter your email below and we will send you a link to reset your password</p> <?php echo form_errors(); ?>
		<form id="new_password" name="new_password" action="<?php echo site_url('member/forgot_password'); ?>" method="post">
			<p>
				<label>Email Address</label>
			</p>
			<p>
				<input type="text" name='email' class='required email' id='email' value='' />
			</p>

				<input type='submit' class='btn-primary btn' value="Reset My Password" />
			</p>
		</form>
	</td>
</tr>
</table>
</div>
<script type='text/javascript'>
$(function(){
	$("#new_password").validate();
	$("p.alert_error").css('margin-bottom', '0px');
	
});
</script>