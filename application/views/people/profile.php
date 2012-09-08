<!-- profile is divided into two columns! -->
<div class = 'row-fluid' id='profile_header'>

	<div class='profile_column span4' id='profile_masthead'>

			<a href="<?php echo current_url();?>" class="user_image left medium">
				<img src="<?php echo $profile_thumb; ?>" />
			</a>	
			
			<h1 id='profile_name'><?php echo $u->screen_name; ?></h1>
	</div><!-- close profile_masthead -->
	<div class='span4'></div>
	<div class='span4'>	
		<div class='btn-group profile_actions'>
				<a href='<?php echo site_url("people/follow/".$u->id); ?>' id='follow_button' class='btn btn-small'>Follow</a>
				<!--<a href='#' id='message_button' class='btn profile_action btn-small'>Message</a>-->
					<?php if($visitor) { ?>
						<a href='#' id='thank_button' class='btn profile_action btn-small btn-success'>Thank</a>
					<?php } ?>
			</div>
	</div>

</div> <!-- close profile_header row -->
<div class='row-fluid' id='profile_top' -->
	<div class='span4'>
				<div id='follow_deets'>
					<?php if(!empty($u->location)) { ?>
								Location:
									<?php if(!empty($u->location->city)) { echo $u->location->city.','; } ?>
									<?php if(!empty($u->location->state)) { echo $u->location->state; } ?>
									<?php if(!empty($u->location->country)) { echo $u->location->country.', '; } ?>
					<?php }  ?>
				<p>
						<?php echo 'Member since '.user_date($u->created,"F jS Y"); ?>
						<?php //echo $u->type; ?>
				</p>
		

				<span class='metadata'>
					<?php echo count($following).' Following';?>
					<?php echo count($followers).' Followers';?>
				
				</span>
			</div>

			<div class='thumb_grid'>
			<?php foreach($followers as $val) { ?>
				<a href="<?php echo site_url('people/'.$val->id); ?>" title="<?php echo $val->screen_name;?>">
				<img src='<?php echo $val->default_photo->thumb_url;?>' />
				</a>
			 <?php } ?>
			</div>
	</div>

	<div class='span4 profile_chunk'id='profile_info'>
			<p class='nicebigtext'>Bio</p>
			<p><?php if(!empty($u->bio)) { echo $u->bio; } ?></p>
			<p><?php if(!empty($u->url)) { echo $u->url; } ?></p>
	</div> <!-- close profile info -->

	<div id='profile_photos' class='span4 profile_chunk thumb_grid'>
			<p class='nicebigtext'>Photos</p>
			<p>
			<?php foreach($u->photos as $val) { ?>
			<a class='photoMod' style='text-decoration:none;'id="<?php echo site_url($val->url); ?>" href='#photoModal' role='button' data-toggle='modal'>
					<img src='<?php echo site_url($val->thumb_url);?>' />
				</a>
			<?php } ?>
			
			</p>
			<!--<button class='btn' href='#photoModal' role='button' data-toggle='modal'>BUTTON</button>-->
			<div class='modal hide' id='photoModal' tabindex='-1' role='dialog' aria-labelledby='photoModalLabel' aria-hidden='true'>
				<div class='modal-header'>
					<h3 id='photoModalLabel'>Photo of <?php echo $u->screen_name; ?></h3>
				</div>
				<div class='modal-body'>
					<img src='' id = 'modImage'/>
				</div>
				<div class='modal-footer'>
					<button class='btn' data-dismiss='modal' aria-hidden='true'>Close</button>
				</div>
			</div>
	</div>
</div><!--close header row -->
<div class='row-fluid' id='buttonFormRow'>

	<div class='span12'>
			<div id='profile_thank_form' style= 'display:none;' >
				<?php echo $thankform; ?>
			</div>
	</div>

</div><!-- close formButtonRow -->
<div class = 'row-fluid' id='profile_content'>
	<div id='profile_content_left' class='profile_column span6'><!-- open left content column -->

			<!--- Gifts and Needs Column -->
			<div id='profile_gifts' class='profile_chunk'>

				<?php if(!empty($gifts)) { ?>

					<span class='nicebigtext'>Gifts</span>

					<?php echo UI_Results::goods(array(
						"results"=> $gifts,
						'mini' => TRUE,
						'border'=> FALSE
					)); ?>
					
				<?php } else { ?>
					<span class='nicebigtext'>Gifts</span>
					<p class='chunk_empty'>This user does not have any gifts listed.</p>
				<?php } ?>

			</div><!--close profile_gifts -->


			<!-- NEEDS column -->
			<div id='profile_needs' class='profile_chunk'>
				<!--<a class='btn profile_action' id='offer'>Offer</a>-->
				<?php if(!empty($needs)) { ?>

					<span class='nicebigtext'> Needs</span>	

					<?php echo UI_Results::goods(array(
				"results"=> $needs,
				'mini' => TRUE
			)); ?>
					
			<?php } else { ?>
				<span class='nicebigtext'> Needs</span>	
				<p class='chunk_empty'>This user does not have any needs listed.</p>
			<?php } ?>
			</div><!-- close profile_needs-->
	</div><!-- close profile content left -->

	<div id='profile_content_right' class='profile_column span6'><!-- open right content column -->


		<div id='profile_reviews' class='profile_chunk'>
			<span class='nicebigtext
'>Reviews</span>

				<?php if(!empty($giver)) { ?>
					<?php echo UI_Results::reviews(array(
						'results'=>$giver
					)); ?>

				<?php } else { ?>
				<?php } ?>

		</div><!--close reviews_list -->

		<div id='profile_thanks' class='profile_chunk'>
			<span class='nicebigtext'>Thanks</span>	

			<?php if(!empty($thanks)) { ?>
				<?php echo UI_Results::thanks(array(
					'results' => $thanks
				)); ?>
				
				<?php } else { ?>
				<?php } ?>
		</div><!-- close profile_thanks -->

	</div><!-- close content right -->
</div><!-- close row fluid -->


<script type='text/javascript'>
$(function() {



$('#photoModal').modal({show:false});

$('.photoMod').click(function() {
	var imgUrl = $(this).attr('id');
	console.log(imgUrl);
	$('#modImage').attr('src',imgUrl);
});

$('#thank_button').click( function() {
	$('#profile_top').hide();
	$('#profile_thank_form').show();
});
$('#thank_cancel').click( function() {
	$('#profile_thank_form').hide();
	$('#profile_top').show();
});

});

</script>
