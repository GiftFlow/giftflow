<div id='login'>
<?php echo heading('login', array('id'=>'login_heading')); ?>
<table>
<tr>
	<td><h3>Use Facebook</h3></td>
	<td style='padding: 0px 70px; vertical-align: bottom;'>
		<span>or</span>
	</td>
	<td>
		<h3>Use Your GiftFlow Account</h3>
	</td>
</tr>
<tr>
	<td style='vertical-align: top;'>
		<p>
			<a style='border-bottom: 0px;' href='<?php echo $fbookUrl; ?>'>
				<img src='<?php echo base_url(); ?>assets/images/facebook_login.png' style='border: 0;' />
			</a>
		</p>
	</td>
	<td></td>
	<td class="span6">
		<form action="<?php echo site_url('member/login'); ?>" method="post">
			<p>
				<label>Email Address</label>
			</p>
			<p>
				<input type="text" name='email' class='required email span6' id='email' value='' />
			</p>
			<p>
				<label>Password</label>
			</p>
			<p>
				<input type='password' name='password' class='required span6' id='password' value='' />
			</p>
			<p>
				<input type='hidden' name='redirect' value="<?php if(!empty($redirect)) echo $redirect; ?>" />
				<input type='submit' class='btn btn-primary btn-large' value="Login Now" />
			</p>
		</form>
		<p style='font-size: .8em;'>No account? <a href="<?php echo site_url('register'); ?>">Click here to sign up.</a></p>
		<p style='font-size: .8em;'>Forgot your password?  <a href="<?php echo site_url('member/forgot_password'); ?>">Click here to reset it.</a>
	</td>
</tr>
</table>
</div>
<script type='text/javascript'>
$(function(){
	$("#login form").validate();
});
</script>
