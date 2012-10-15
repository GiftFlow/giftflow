<div class='two_panels'>

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>

	<div class='right_content'>
	
		<h1>Friend Finder</h1>
		
		<!-- Find Your Friends Module-->
			<div id='community_sources'>
				<ul>
					<li>
						<a href="#" id="facebook">
							Facebook Friends (<?php if(!empty($friends['facebook'])){ echo count($friends['facebook']); } else { echo 0; } ?>)
						</a>
					</li>
					<li>
						<a href="#" id="gmail">
							Gmail Contacts (<?php if(!empty($friends['google'])){ echo count($friends['google']); } else { echo 0; } ?>)
						</a>
					</li>
				</ul>
				<div id="facebook">
				<?php if(!empty($friends['facebook'])) { ?>
				
					<?php echo UI_Results::users(array(
						"results"=>$friends['facebook'],
						"mini"=>TRUE
					)); ?>
					
				<?php }  else { ?>
					<p>Your account is not yet linked with Facebook. But don't worry—that is easy to fix! Click the link below and you'll be connected with your friends on GiftFlow in no time.</p>
					<a href='<?php echo site_url('account/link/facebook'); ?>'>
						<img style='border: none;' src='<?php echo base_url(); ?>assets/images/facebook_login.png' />
					</a>
				<?php } ?>
				</div>
				<div id='gmail'>
				<?php if(!empty($friends['google'])) { ?>
					
					<?php echo UI_Results::users(array(
						"results"=>$friends['google'],
						"mini"=>TRUE
					)); ?>
					
				<?php }  else { ?>
					<p>Your account is not yet linked with your Gmail account. But don't worry—that is easy to fix! Click the link below and you'll be connected with your friends on GiftFlow in no time.</p>
					<a href='<?php echo site_url('account/link/google'); ?>'>
						<img style='border: none;' src='<?php echo base_url(); ?>assets/images/google_connect.png' />
					</a>
				<?php } ?>
				</div>
			</div>
		<!-- eof Find Your Friends Module -->
		
	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$(".follow").click(function(){ 
		var id = $(this).attr('rel');
		$.post("<?php echo site_url('people/follow/'); ?>/"+id);
		$(this).after("<div class='css_right'><i class='icon-ok'></i>  Following</div>");
		$(this).remove();
		return false;
	});
	$("#community_sources").buttonTabs();
});
</script>