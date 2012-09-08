<div id='login'>
	<?php echo heading('sign_up', array('id'=>'login_heading')); ?>
<table>
<tr>
	<td><h3>Use Facebook</h3></td>
	<td style='padding: 0px 70px; vertical-align: bottom;'><span>or</span></td>
	<td class="span6">
		<h3>Create a GiftFlow Account</h3>
	</td>
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
	<td class="span6"><?php echo form_errors(); ?>
	<form name='register' id='register' method="post">
		<div class="control-group">
			<label for="email">Email Address</label>
			<input maxlength="255" size="30" class="email span6 required" type="text" name="email" id="email" value="" />
		</div>
		<div class="control-group">
			<label for="screen_name">Name</label>
			<input maxlength="75" size="30" class="required span6" type="text" name="screen_name" id="screen_name" value=""/>
		</div>
		<div class="control-group">
			<label for="zipcode">Zip Code</label>
			<input maxlength="20" class="span6" size="10" type="text" name="zipcode" id="zipcode" value=""/>
		</div>
		<div class="control-group">			
			<label for="profile_type">Profile Type</label>
			<select name="profile_type" id="profile_type" class="span6" />
			    <option value='individual'>Individual</option>
			    <option value='nonprofit'>Non-Profit</option>
			    <option value='business'>Business</option>
			 </select>
		</div>
		<div class="control-group">
			<label for="password">Password</label>
			<input maxlength="45" size="30" class="span6 required" type="password" name="password" id="register_password" value="" />			
		</div>
		<div class="control-group">
			<label for="confirm_password">Confirm Password</label>
			<input maxlength="45" size="30" class="required span6" type="password" name="confirm_password" id="confirm_password" value="" />
		</div>
		<div class="control-group">
			<label for="captcha">Type the two words that appear below</label>
			<?php echo $recaptcha; ?>	
		</div>	
		<div class="control-group" id='service'>
		<label class="checkbox"><input type='checkbox' checked='checked' id='terms_box' class='required span6' name='terms'/>I agree to the 
		<a href="<?php echo site_url('member/terms'); ?>">Terms of Service </a></label>
		</div>
		<div class="control-group">
			<input type="submit" class="btn btn-primary btn-large" value="Sign Up" />
		</div>
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
				equalTo: "#register_password"
			}
		},
		messages: {
			confirm_password: {
				equalTo: "Passwords Must Match"
			}
		},
		highlight: function(label) {
			$(label).closest('.control-group').addClass('error').removeClass('success');
	  	},
	  	success: function(label) {
		  	label.hide().closest('.control-group').addClass('success');
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
