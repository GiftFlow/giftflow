<?php if(!empty($results)) { ?>

	<?php if(!$row) { ?>
		<ul class='inbox results_list'>
	<?php } ?>
		<?php foreach($results as $T) { ?>
		<li class='message_row'>
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
		<?php } ?>
<?php if(!$row) { ?>
	</ul>
<?php } ?>

<?php } ?>
