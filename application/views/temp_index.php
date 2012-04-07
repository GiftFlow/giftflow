<div id='dash_header' style='padding: 0px 0px 0px 100px;'> 

<p class='nicebigtext'>
	<span class='green'>Give</span> something away.
	<span class='green'>&nbsp&nbspReceive</span> someone else's gift.
	<span class='green'>&nbsp&nbspPay</span> it forward.
	</p>
</div>
<div id='landing_left' >
			
			<div id='video'>
				<object><param name="movie" value="http://www.youtube.com/v/0wLNXFeZbBU&hl=en_US&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/0wLNXFeZbBU&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
			</div>
</div>
<div id='landing_right'>
		<div class='landing_panel' id='holiday' >
			<p class='nicebigtext'>Welcome to our holiday campaign:<br /> <span class='green'>Give New Haven!</span></p>
			<p>Check back in soon to hear about the gifts and needs of participating businesses and non-profits in the New Haven area.</p>
		</div>
		
		<p class ='nicebigtext'>Log in now and start giving</p>
		<div class='landing_panel'>
			<a class='button' href='<?php echo site_url('register'); ?>'>Sign Up Now</a>
			<a class='button secondary' href='<?php echo site_url('about/tour'); ?>'>Take The Tour</a>
			<p id='one'>GiftFlow is a non-profit. Please <a href='about/donate'>Donate Here</a></p>
			<p>Welcome to our new Beta version! </p>
		</div>
</div>


<script type='text/javascript'>
$(function(){
	$("input:submit, a.button").button();
	
	var video = $('#video');
	var thumb = $('#video_thumb');
	var overlay = $('#landing_overlay');
	
	$('#video_thumb').click(function() {
		video.fadeIn('slow');
		overlay.fadeOut('fast');
		thumb.fadeOut('fast');
	});
	
});
</script>
