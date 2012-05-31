
<div class = 'row-fluid'>
	<div  class='span6'>
		
		<a href="<?php echo current_url();?>" class="user_image left medium">
			<img src="<?php echo $profile_thumb; ?>" />
		</a>

		<h1 id='profile_name'><?php echo $u->screen_name; ?></h1>
		<p>
			<?php if(!empty($u->default_location)) { ?>
							<?php if(!empty($u->default_lcoation->city)) { echo $u->default_location->city.','; } ?>
							<?php if(!empty($u->default_location->state)) { echo $u->default_location->state; } ?>
							<?php if(!empty($u->default_location->country)) { echo $u->default_location->country; } ?>
						
			<?php } ?>
			<?php echo $u->total_followers; ?> 
				Followers
		</p>
	</div>
	<div id='profile_buttons' class='span6'>
		<span class='label'><?php echo $u->type; ?></span>
		<a class = 'profile_action btn'>Follow</a>
		<a class ='profile_action btn'>Thank</a>
	</div> <!-- close profile_buttons -->
</div> <!-- close first row -->

<div class='row-fluid'>
	<div id='profile_info' class='span4'>
		<h2>Bio</h2>
		<p><?php if(!empty($u->bio)) { echo $u->bio; } ?></p>
		<h2>URL</h2>
		<p><?php if(!empty($u->url)) { echo $u->url; } ?></p>
	</div> <!-- close profile info -->

	<div id='profile_goods' class ='span4'>
		<h2>Gifts</h2>
			
			<?php if(!empty($gifts)) { ?>
			
				<?php echo UI_Results::goods(array(
					"results"=> $gifts,
					'mini' => TRUE
				)); ?>
				
			<?php } else { ?>
				<p>This user does not have any gifts to give at the moment.</p>
			<?php } ?>

		<h2> Needs</h2>	
			<?php if(!empty($needs)) { ?>
			
				<?php echo UI_Results::goods(array(
					"results"=> $needs,
					'mini' => TRUE
				)); ?>
				
			<?php } else { ?>
				<p>This user does not have any gifts to give at the moment.</p>
			<?php } ?>
		
			
		
	</div><!-- close goods -->
	<div class='span4' id='profile_reviews'>
		<h2>Reviews</h2>

		<?php if(!empty($giver)) { ?>
			<?php echo UI_Results::reviews(array('results'=>$giver)); ?>
		<?php } else { ?>
				<p>This user has not yet given any gifts</p>
		<?php } ?>


	</div> <!-- close reviews -->
</div> <!-- close second row -->

</div>
