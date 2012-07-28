
<div class = 'row-fluid' id='profile_header'>
	<div  class='span1'>
		
		<a href="<?php echo current_url();?>" class="user_image left medium">
			<img src="<?php echo $profile_thumb; ?>" />
		</a>	
	</div>
	<div class='span6'>
		<h1 id='profile_name'><?php echo $u->screen_name; ?></h1>
			<?php if(!empty($u->default_location)) { ?>
			<p>
							<?php if(!empty($u->default_lcoation->city)) { echo $u->default_location->city.','; } ?>
							<?php if(!empty($u->default_location->state)) { echo $u->default_location->state; } ?>
							<?php if(!empty($u->default_location->country)) { echo $u->default_location->country; } ?>
			</p>
			<?php } ?>
			<p>
				Member since 
				<?php echo user_date($u->created,"F jS Y"); ?>
			</p>
			<?php //echo $u->type; ?>
		</p>
	</div>
	<div class='span3 offset6'>
</div>
<div class = 'row-fluid' id ='profile_bio'>
	<div id='profile_info' class='span12'>
		<p class='nicebigtext'>Bio</p>
		<p><?php if(!empty($u->bio)) { echo $u->bio; } else { echo "This user has yet to fill out their Bio";} ?></p>
		<p><?php if(!empty($u->url)) { echo $u->url; } ?></p>
	</div> <!-- close profile info -->
</div><!-- close vbio and info row -->

<div class ='row-fluid'>
	<div id='profile_goods' class ='span5'>
		<ul class='profile_column'>
			<li class='chunk'>
				<h2>Gifts</h2>
					<a class='btn'>Request</a>
					
					<?php if(!empty($gifts)) { ?>
					
						<?php echo UI_Results::goods(array(
							"results"=> $gifts,
							'mini' => TRUE
						)); ?>
						
					<?php } else { ?>
						<p>This user does not have any gifts listed.</p>
					<?php } ?>
			</li>
			<li class='chunk'>
				<h2> Needs</h2>	
				<a class='btn'>Offer</a>
					<?php if(!empty($needs)) { ?>
					
						<?php echo UI_Results::goods(array(
							"results"=> $needs,
							'mini' => TRUE
						)); ?>
						
					<?php } else { ?>
						<p>This user does not have any needs listed.</p>
					<?php } ?>
			</li>
		</ul>
	</div><!-- close needs -->
	<div class='span5' id='profile_reviews'>
		<ul class='profile_column'>
			<li class='chunk'>
				<h2>Thanks</h2>
				<a class='btn'>Thank</a>
			</li>
			<li class='chunk'>
				<h2>Reviews</h2>

				<?php if(!empty($giver)) { ?>
					<?php echo UI_Results::reviews(array('results'=>$giver)); ?>
				<?php } else { ?>
						<p>This user has not yet given any gifts</p>
				<?php } ?>
			</li>
		</ul>

	</div> <!-- close reviews -->
	<div class='span2' id='profile_social'>
		<ul class='profile_column'>
			<li class='chunk'>
			<h2>Social</h2>
			<a class='btn'>Follow</a>
				<p>13 Following</p>
				<p>30 Followers</p>
			</li>
			<li class='chunk'>
				<p>Connected to you through</p>
				lots of people
			</li>
		</ul>
	</div> <!-- close social -->

</div> <!-- close second row -->
</div>
