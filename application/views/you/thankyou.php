<div class='row'>
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	<div class='span8 chunk'>
		<div class="row-fluid">
			<div class='span2'>	
				<a href="<?php echo site_url('people/'.$thankyou->thanker_id);?>" class="user_image medium left">
					<img src="<?php echo $thankyou->default_photo->thumb_url;?>" alt="<?php echo $thankyou->screen_name;?>" />
				</a>
			</div>
				
			<div class="span10">
				<a href="<?php echo site_url('you/inbox/'.$thankyou->id);?>" class="title">
					Thank you from  <?php echo $thankyou->screen_name; ?>
				</a>
				<span>
					Updated <?php echo user_date($thankyou->updated,"n/j/o");?>
				</span>
				<p>They thanked you for: 
					<span><?php echo $thankyou->gift_title;?></span>
				</p>
				<p>They wrote:
				<span><?php echo $thankyou->body; ?></span> 
				</p>

				<div>
					<form method='post' id='decide_thankyou'>
						<input type='hidden' name='form_type' value='decide_thankyou'/>
						<input type='hidden' name='thankyou_id' value='<?php echo $thankyou->id; ?>' />
						<div class="btn-group">
						<?php if($thankyou->status == 'pending') { ?>
							<input type="submit" class="left btn btn-large btn-success" name='decision' value="Accept" />
							<input type="submit" class="left btn btn-large btn-danger" name='decision' value="Decline" />
						<?php } else { ?>
							<input type='submit' class='left btn btn-large btn-danger' name='decision' value='Edit' />	
						<?php } ?>
						</div>
					</form>
				</div>
			<p>Accepting this thank will display it on your profile</p>
		</div>
	</div>
</div>
</div>

<script type='text/javascript'>
$(function(){
});
</script>
