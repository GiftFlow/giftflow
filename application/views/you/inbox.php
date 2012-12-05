<div class='row' id='you_inbox'>

<div class='span2 chunk'>
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
</div>
<div class='span9 chunk'>
		<?php if(!empty($thanks) || !empty($transactions) || !empty($threads)) { ?>
			<ul class='inbox results_list'>
		
				<!-- put thank yous on top followed by transactions, they'll disappear once approved -->
				<?php if(!empty($thanks)) { ?>
					<li class='section_header'><h3 class='inbox_title'>Thanks</h3></li>
					<?php foreach($thanks as $val) { ?>
						<li>
							<div class='row-fluid'>	
								<div class='span1'>	
									<img src="<?php echo base_url()."assets/images/status_icons/".$val->status.".png";?>" title="<?php echo ucfirst($val->status);?>" alt="<?php echo ucfirst($val->status);?>" class="left status_icon" />
								</div>
								<div class='span2'>
									<a href="#" class="user_image medium left">
										<img src="<?php echo $val->default_photo->thumb_url; ?>" alt="<?php echo $val->screen_name;?>" />
									</a>
								</div>
								<div class='span9 metadata'>	
										<a href="<?php echo site_url('you/view_thankyou/'.$val->id);?>" class="title">
											Thank you from <?php echo $val->screen_name; ?>
										</a>
										<span class="summary">
											For: " <?php echo $val->gift_title; ?>"
										</span>
									
									<span class="updated">
										<?php echo user_date($val->updated,"n/j/o");?>
									</span>
								</div>
							</div>
						</li>
					<?php } ?>
				<?php } ?>

				<?php if(!empty($transactions)) { ?>	
					<li class='section_header'><h3 class='inbox_title'>Gifts</h3></li>
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
							} ?>

						<li class="<?php if($val->unread) { echo "unread"; } ?>">
							<div class='row-fluid'>
								<div class='span1'>
									<img src="<?php echo base_url()."assets/images/status_icons/".$val->status.".png";?>" title="<?php echo ucfirst($val->status);?>" alt="<?php echo ucfirst($val->status);?>" class="left status_icon" />
								</div>
								<div class='span2'>
									<a href="#" class="user_image medium left">
										<img src="<?php echo $other_user->default_photo->thumb_url;?>" alt="<?php echo $other_user->screen_name;?>" />
									</a>		
								</div>
								<div class='span9 metadata'>
									<a href="<?php echo site_url('you/view_transaction/'.$val->id);?>" class="title">
										Request <?php echo $demander ? "to":"from"; echo " ".$other_user->screen_name;?>
									</a>
									<span class="summary">
										<?php echo strip_tags($demander ? $val->language->demander_summary : $val->language->decider_summary); ?>
									</span>
									<span class="updated">
										<?php echo user_date($val->updated,"n/j/o");?>
									</span>
								</div>
							</div>
						</li>
				<?php } ?>
			<?php } ?>
			<?php if(!empty($threads)) { ?>
					<li class='section_header'><h3 class='inbox_title'>Messages</h3></li>
				<?php foreach($threads as $T) { ?>
					<?php if(!empty($T->messages)) { ?>
					<li>
						<div class='row-fluid'>		
							<div class='span1'>
								<img src="<?php echo base_url()."assets/images/status_icons/active.png";?>" title="" alt="" class="left status_icon" />
							</div>
							<div class='span2'>
								<a class='user_image medium left' href='<?php echo site_url('people/'.$T->other_user->id); ?>'>
									<img src="<?php echo $T->other_user->default_photo->thumb_url;?>"/>
								</a>
							</div>
							<!-- Metadata -->
							<div class='span9 metadata'>
								<!-- Title --> 
								<a href="<?php echo site_url('you/view_thread/'.$T->thread_id);?>" class="title">Conversation with <?php echo $T->other_user->screen_name; ?></a>
								<span class='summary'>
									<?php echo substr($T->recent->message_body, 0, 150); ?>
								</span>
								<span class='updated'>	
									<?php echo user_date($T->recent->message_created, "n/j/o"); ?>
								</span>
							</div>
						</li>
					<?php }?>
				<?php } ?>
			<?php }?>
			</ul>
			<?php } else { ?>	


				<!--welcome view -->
				<p class='nicebigtext'> You don't have any messages! It's time to get with the flow.</p>
				<?php echo $welcome_view; ?>
			<?php } ?>

		</div>
</div>

<script type='text/javascript'>
$(function(){
	$("img.status_icon").tooltip();
});
</script>
