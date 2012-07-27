<div class ='row-fluid'>

	<div class='span12' id='home_header'> 

		<p class='nicebigtext' style='text-align:center'>
			<span class='green'>Give</span> something away.
			<span class='green'>&nbsp&nbspReceive</span> someone else's gift.
			<span class='green'>&nbsp&nbspPay</span> it forward.
		</p>
	</div>
</div>
<div class='row-fluid'>
	<div class='span6' id='home_left' >
		<div id = 'top'>
			<div id='activity'>
			<p class='nicebigtext'>Recent Activity</p>
				  <?php echo UI_Results::events(array(
					'results' => (isset($events)) ? $events : array(),
					'mini' => TRUE,
					'row' => FALSE,
					'activity' => TRUE
				  )); ?>
			</div>
		</div>
		<div id='bottom' style='display:none' >
				<p class='nicebigtext'>Log In Here </p>
				<p id='home_fbook'> 
					<a style='border-bottom: 0px;' href='<?php echo $fbookUrl; ?>'>
						<img src='<?php echo base_url(); ?>assets/images/facebook_logo.jpeg' style='border: 0; width:100px;' />
					</a>
				</p>
			<p>	OR</p>
			<div id='login_form' class='home_form'>
				<form name='login' id='login' action="<?php echo site_url('member/login'); ?>" method="post">
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
						<input style ='float:left'type='submit' class='btn btn-primary btn-large' value="Login Now" />
					</div>
				</form>
			</div><!-- close login form -->
			<div id='forgot'>
				<a href="<?php echo site_url('member/forgot_password'); ?>">Forgot your password?</a>
			</div>
		</div><!-- close bottom -->
	</div><!-- close home_left -->

	<div class='span6' id='home_right'>
		<div class='top'>			
			<p class ='nicebigtext'>Log in now and start giving</p>
			<p>
				<a class='btn btn-primary btn-large' id='register' href='<?php echo site_url("member/register"); ?>'>Sign Up Now</a>
				<a class='flip btn btn-large' id='login_flip' href='#'>Log In</a>
			</p>
				<p id='one'>GiftFlow is a non-profit. Please <a href='about/donate'>Donate Here</a></p>
				<p>Welcome to our new Beta version! </p>
		</div>
	</div>

</div>

<script type='text/javascript'>

			

$('#login_flip').click(function() {
	$('#top').hide();
	$('#bottom').show();
	
});



</script>
