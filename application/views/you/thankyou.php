<div class='row'>
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	<div class='span8 inbox_summary'>
		<div class="row-fluid chunk">
			<div class='span2'>	
				<a href="<?php echo site_url('people/'.$thankyou->thanker_id);?>" class="user_image medium left">
					<img src="<?php echo $thankyou->thanker_default_photo->thumb_url;?>" alt="<?php echo $thankyou->thanker_screen_name;?>" />
				</a>
			</div>
				
			<div class="span10">
				<span class='summary'>	
					<a href="<?php echo site_url('you/inbox/'.$thankyou->id);?>" class="title">
						Thank you from  <?php echo $thankyou->thanker_screen_name; ?>
					</a>
				</span>
				<span>
					Updated <?php echo user_date($thankyou->updated,"n/j/o");?>
				</span>
					<p>
						<a href="<?php echo site_url('people/'.$thankyou->thanker_id); ?>">
							<?php echo $thankyou->thanker_screen_name; ?> 
						</a>

						thanked you for: 
					<span><?php echo $thankyou->gift_title;?></span>
				</p>
				<p>
					<a href="<?php echo site_url('people/'.$thankyou->thanker_id); ?>">
						<?php echo $thankyou->thanker_screen_name; ?> 
					</a>
					wrote:
				</p>

				<span><?php echo $thankyou->body; ?></span> 
				</p>

			</div>
		</div>
		<div class='row-fluid chunk'>

					<form method='post' id='decide_thankyou'>
						<input type='hidden' name='form_type' value='decide_thankyou'/>
						<input type='hidden' name='thankyou_id' value='<?php echo $thankyou->id; ?>' />
						<?php if($thankyou->status == 'pending') { ?>
							<p> 
								<input type="submit" class="left btn btn-large btn-success" name='decision' value="Accept" />	
								Click Accept to have this Thank displayed on your profile.
							</p>
							<p>
							<input type="submit" class="left btn btn-large btn-danger" name='decision' value="Decline" />
								Click Decline if you would rather it not be public.
							</p>
						<?php } else { ?>
							<p>Thank <?php echo $thankyou->status; ?></p>
							<p>
								<input type='submit' class='left btn btn-large btn-danger' name='decision' value='Edit' />	
								Click Edit to change your decision.
							</p>
						<?php } ?>
					</form>
				</div>
		</div>
	</div>
</div>
</div>

<script type='text/javascript'>
$(function(){
});
</script>
