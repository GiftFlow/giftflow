<div id="transaction-view" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>

		<div class='clearfix transaction-summary'>
				
				<div class="clearfix thank-header">
					<a href="<?php echo site_url('people/'.$thankyou->thanker_id);?>" class="user_image medium left">
						<img src="<?php echo $thankyou->default_photo->thumb_url;?>" alt="<?php echo $thankyou->screen_name;?>" />
					</a>
					
					<div class="metadata left">
						<a href="<?php echo site_url('you/inbox/'.$thankyou->id);?>" class="title">
							Thank you from  <?php echo $thankyou->screen_name; ?>
						</a>
						<span class="summary">
							 <?php //echo $thankyou->gift_title; ?>
						</span>
					</div>
					<span class="updated css_right">
						Updated <?php echo user_date($thankyou->updated,"n/j/o");?>
					</span>
				</div>
				
				<div class="clearfix metadata-bottom">
					
						<p class="left meta-label">They thanked you for: </p>
						<p class="left field thank-text"><?php echo $thankyou->gift_title;?></p>
				</div>
				<div class='clearfix metadata-bottom'>
						<p class = 'left meta-label'>They wrote: </p>
						<p class='left field thank-text'><?php echo $thankyou->body; ?> </p>
				</div>
				<?php if($thankyou->status!="declined") { ?>
				<div class="clearfix metadata-bottom">
					<div class="left field">
						<form method='post' id='decide_thankyou'>
							<input type='hidden' name='form_type' value='decide_thankyou'/>
							<input type='hidden' name='thankyou_id' value='<?php echo $thankyou->id; ?>' />
							<div class="btn-group">
							<input type="submit" class="left btn btn-large btn-success" name='decision' value="Accept" />
							<input type="submit" class="left btn btn-large btn-danger" name='decision' value="Decline" />
							</div>
						</form>
					</div>
				<p>Accepting this 'thank you' will display it on your profile</p>
				<?php } ?>
				</div>
	
			</div>
		</div>
		<!-- eof div.transaction-main -->
	
	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$("table tr:even").addClass("odd");
	$("table tr:odd").addClass("even");
	$("form#review").validate({
		highlight: function(label) {
			$(label).closest('.control-group').addClass('error').removeClass('success');
	  	},
	  	success: function(label) {
		  	label.hide().closest('.control-group').addClass('success');
	  	}
	});
});
</script>
