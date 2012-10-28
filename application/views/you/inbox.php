<!-- Formerly known as transactions -->

<div id="your_transactions" class="two_panels">


	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	<div class='right_content'>
		<?php if(!empty($thankyous) || !empty($transactions) || !empty($threads)) { ?>
			<ul class='transactions'>
		
				<!-- put thank yous on top followed by transactions, they'll disappear once approved -->
				<?php if(!empty($thanks)) { ?>
					<li class='section_header'><h3 class='messages_title'>Thanks</h3></li>
					<?php foreach($thanks as $val) { ?>
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

				<?php if(!empty($transactions)) { ?>	
					<li class='section_header'><h3 class='messages_title'>Gifts</h3></li>
				<?php foreach($transactions as $val) 
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
					<li class='section_header'><h3 class='messages_title'>Messages</h3></li>
				<?php foreach($threads as $T) { ?>
					<?php if(!empty($T->messages)) { ?>
					<li class='clearfix'>

						<img src="<?php echo base_url()."assets/images/status_icons/active.png";?>" title="" alt="" class="left status_icon" />

						<a class='user_image medium left' href='<?php echo site_url('people/'.$T->other_user->id); ?>'>
							<img src="<?php echo $T->other_user->default_photo->thumb_url;?>"/>
						</a>

						<!-- Metadata -->
						<div class='metadata left'>
							<!-- Title --> 
							<a href="<?php echo site_url('you/view_thread/'.$T->thread_id);?>" class="title">Conversation with <?php echo $T->other_user->screen_name; ?></a>
							<span class='summary'>
								<?php echo substr($T->recent->message_body, 0, 150); ?>
							</span>
						</div> <!-- result_meta  -->
							<span class='updated css_right'>	
								<?php echo user_date($T->recent->message_created, "n/j/o"); ?>
							</span>
					</li>
				<?php }?>
				<?php } ?>
			<?php }?>
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
