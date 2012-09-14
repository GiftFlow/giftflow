<!-- Formerly known as transactions -->

<div id="your_transactions" class="two_panels">


	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
		<?php if(!empty($thankyous) || !empty($transactions)) { ?>
			<ul class='transactions'>
		<?php } ?>
		
				<!-- put thank yous on top followed by transactions, they'll disappear once approved -->
				<?php if(!empty($thankyous)) { ?>
					<?php foreach($thankyous as $val) { ?>

						<li class="clearfix unread">
					
							<img src="<?php echo base_url()."assets/images/status_icons/".$val->status.".png";?>" title="<?php echo ucfirst($val->status);?>" alt="<?php echo ucfirst($val->status);?>" class="left status_icon" />
							
							<a href="#" class="user_image medium left">
								<img src="<?php echo $val->default_photo->thumb_url; ?>" alt="<?php echo $val->screen_name;?>" />
							</a>
							
							<div class="metadata left">
								<a href="<?php echo site_url('you/view_thankyou/'.$val->id);?>" class="title">
									Thank you from <?php echo $val->screen_name; ?>
								</a>
								<span class="summary">
									For: " <?php echo $val->gift_title; ?>"
								</span>
							</div>
							
							<span class="updated css_right">
								<?php echo user_date($val->updated,"n/j/o");?>
							</span>
					</li>
						</li>
					<?php } ?>
				<?php } ?>

				<?php if(!empty($transactions)) {	
					foreach($transactions as $val) 
					{
						if($val->demander->id == $this->data['logged_in_user_id'])
							{
								$demander = TRUE;
								$other_user = $val->decider;
							} 
							else 
							{
								$demander = FALSE;
								$other_user = $val->demander;
							}
							?>
						<li class="clearfix <?php if($val->unread) { echo "unread"; } ?>">
						
							<img src="<?php echo base_url()."assets/images/status_icons/".$val->status.".png";?>" title="<?php echo ucfirst($val->status);?>" alt="<?php echo ucfirst($val->status);?>" class="left status_icon" />
							
							<a href="#" class="user_image medium left">
								<img src="<?php echo $other_user->default_photo->thumb_url;?>" alt="<?php echo $other_user->screen_name;?>" />
							</a>
							
							<div class="metadata left">
								<a href="<?php echo site_url('you/view_transaction/'.$val->id);?>" class="title">
									Request <?php echo $demander ? "to":"from"; echo " ".$other_user->screen_name;?>
								</a>
								<span class="summary">
								<?php echo strip_tags($demander ? $val->language->demander_summary : $val->language->decider_summary); ?>
								</span>
							</div>
							
							<!--<span class="status left">
								<?php echo $val->status; ?>
							</span>-->


							<span class="updated css_right">
								<?php echo user_date($val->updated,"n/j/o");?>
							</span>
					</li>
				<?php } ?>
			<?php } ?>
			<?php if(!empty($threads)) { ?>
				<?php foreach($threads as $T) { ?>
					<li class='result_row clearfix'>

						<a class='result_image thankimg' href='<?php echo site_url('people/'.$M->user->id); ?>'>
							<img src="<?php //echo $M->user->default_photo->thumb_url;?>"/>
						</a>

						<!-- Metadata -->
						<div class='result_meta clearfix thankdata'>
							<span class='metadata'>	
								<?php echo user_date($M->created, "F jS Y"); ?>
							</span>
							<!-- Title --> 
							<span class="title small">Message from <?php echo $M->user->screen_name; ?></span>
							<span id='full_review'>
								<?php echo $M->body; ?>
							</span>
						</div> <!-- result_meta  -->
					</li>
				<?php } ?>
			<?php } else { ?>
		
			<!-- Empty State -->
			<p class='nicebigtext'> You don't have any messages! It's time to get with the flow.</p>
			<?php echo $welcome_view; ?>
		<?php } ?>

	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$("img.status_icon").tipTip({ delay: 0, fadein: 0 });
});
</script>
