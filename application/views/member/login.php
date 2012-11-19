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

	<?php if(!empty($fbookUrl)){ ?>
	<!-- Facebook Login Button -->
	<td style='vertical-align: top;'>
		<p>
			<a style='border-bottom: 0px;' href='<?php echo $fbookUrl; ?>'>
				<img src='<?php echo base_url(); ?>assets/images/facebook_login.png' style='border: 0;' />
			</a>
		</p>
	</td>
	<td></td>

	<!-- eof Facebook Login Button -->
	<?php } ?>
	
	<td class="span6">
		<form action="<?php echo site_url('member/login'); ?>" method="post">
			<fieldset>
			<div class="control-group">
				<label for="email">Email Address</label>
				<input type="text" name='email' class='required email span6' id='email' value='' />
			</div>
			<div class="control-group">
				<label for="password">Password</label>
				<input type='password' name='password' class='required span6' id='password' value='' />
			</div>
			<div class="control-group">
				<input type='hidden' name='redirect' value="<?php if(!empty($redirect)) echo $redirect; ?>" />
				<input type='submit' class='btn btn-primary btn-large' value="Login Now" />
			</div>
		</form>
		<p><a href="<?php echo site_url('member/forgot_password'); ?>">Forgot your password?</a></p>
	</td>
</tr>
</table>
</div>
<script type='text/javascript'>
$(function(){
	$("#login form").validate({
		highlight: function(label) {
			$(label).closest('.control-group').addClass('error')
											  .removeClass('success');
	  	},
	  	success: function(label) {
		  	label.hide().closest('.control-group').addClass('success');
	  	}
	});
});
</script>
