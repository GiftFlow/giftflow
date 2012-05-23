<div id='login'>
	<?php echo heading('sign_up', array('id'=>'login_heading')); ?>
<table>
<tr>
	<td><h3>Use Facebook</h3></td>
	<td style='padding: 0px 70px; vertical-align: bottom;'><span>or</span></td>
	<td><h3>Create a GiftFlow Account</h3></td>
</tr>
<tr>
	<td style='vertical-align: top;'>
		<p>
			<a style='border-bottom: 0px;' href='<?php echo $registerUrl; ?>'>
				<img src='<?php echo base_url(); ?>assets/images/facebook_signup.png' style='border: 0;' />
			</a>
		</p>
		<p>
			<fb:facepile width="280"></fb:facepile>
		</p>
	</td>
	<td></td>
	<td style='width: 500px;'><?php echo form_errors(); ?>
	<form name='register' id='register' method="post">
		<p>
			<label for="email">Email Address</label>
		</p>
		<p>
			<input maxlength="255" size="30" class="email required" type="text" name="email" id="email" value="" />
		</p>
		<p>
			<label for="screen_name">Name</label>
		</p>
		<p>
			<input maxlength="75" size="30" class="required" type="text" name="screen_name" id="screen_name" value=""/>
		</p>
		<p>
			<label for="zipcode">Zip Code</label>
		</p>
		<p>
			<input maxlength="20" size="10" type="text" name="zipcode" id="zipcode" value=""/>
		</p>
		<p>
			<label for="profile_type">Profile Type</label>
		</p>
		<p>
      <select name="profile_type" id="profile_type" />
          <option value='individual'>Individual</option>
          <option value='nonprofit'>Non-Profit</option>
          <option value='business'>Business</option>
       </select>
		</p>
		<p>
			<label for="password">Password</label>
		</p>
		<p>
			<input maxlength="45" size="30" class="required" type="password" name="password" id="password" value="" />			
		</p>
		<p>
			<label for="confirm_password">Confirm Password</label>
		</p>
		<p>
			<input maxlength="45" size="30" class="required" type="password" name="confirm_password" id="confirm_password" value="" />
		</p>
		<p>
			<label for="captcha">Type the two words that appear below</label>
			<?php echo $recaptcha; ?>		
		</p>
		<p id='service'>
		<input type='checkbox' id='terms_box' class='required' name='terms'/>I agree to the 
		<a href="<?php echo site_url('member/terms'); ?>">Terms of Service </a>
		</p>
		<p>
			<input type="submit" class="btn" value="Sign Up" />
		</p>
	</form>
</td>
</tr></table>
</div>
<?php //echo $facebook_sdk; ?>
<script type='text/javascript'>
$(function(){
	$("#register").validate({
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
	
	// Style reCaptcha element
	$("table#recaptcha_table").css("cssText", "border: none !important");
	$("#recaptcha_image").css("margin", "0px");
	$(".recaptcha_input_area").parent().css("padding", "15px 0px 0px");
	$(".recaptcha_image_cell").removeAttr("width").css("cssText", "padding: 0px !important");
	$("#recaptcha_reload").attr("src", "<?php echo base_url(); ?>assets/images/recaptcha_refresh.png");
	$("#recaptcha_switch_audio").attr("src", "<?php echo base_url(); ?>assets/images/recaptcha_sound.png");
	$("#recaptcha_whatsthis").attr("src", "<?php echo base_url(); ?>assets/images/recaptcha_whatsthis.png");
	$("#recaptcha_tagline").hide();
});
</script>
<!-- Google Code for register Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1013121847;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "000000";
var google_conversion_label = "A6WHCJHflwIQt4aM4wM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1013121847/?label=A6WHCJHflwIQt4aM4wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
