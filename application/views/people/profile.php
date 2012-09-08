<!-- profile is divided into two columns! -->
<div class = 'row-fluid' id='profile_header'>

	<div class='profile_column span6' id='profile_header_left'>
		
		<div id='profile_masthead'>

			<a href="<?php echo current_url();?>" class="user_image left medium">
				<img src="<?php echo $profile_thumb; ?>" />
			</a>	
			
			<h1 id='profile_name'><?php echo $u->screen_name; ?></h1>
			<span class='metadata'>
			<?php if(!empty($u->default_location)) { ?>
							<?php if(!empty($u->default_lcoation->city)) { echo $u->default_location->city.','; } ?>
							<?php if(!empty($u->default_location->state)) { echo $u->default_location->state; } ?>
							<?php if(!empty($u->default_location->country)) { echo $u->default_location->country.', '; } ?>
			<?php } ?>
				<?php echo 'member since '.user_date($u->created,"F jS Y"); ?>
				<?php //echo $u->type; ?>
			</span>
		</div>

		<div id='profile_info'>
			<p class='nicebigtext'>Bio</p>
			<p><?php if(!empty($u->bio)) { echo $u->bio; } else { echo "This user has yet to fill out their Bio";} ?></p>
			<p><?php if(!empty($u->url)) { echo $u->url; } ?></p>
		</div> <!-- close profile info -->

		<div id='profile_reviews' class='profile_chunk'>
			<span class='pTitle'>Reviews</span>

				<?php if(!empty($giver)) { ?>
					<?php echo UI_Results::reviews(array(
						'results'=>$giver
					)); ?>

				<?php } else { ?>
						<p class='chunk_empty'>This user has not yet given or received any gifts through GiftFlow</p>
				<?php } ?>

		</div><!--close reviews_list -->

		<div id='profile_thanks' class='profile_chunk'>
			<span class='pTitle'>Thanks</span>	

			<?php if(!empty($thanks)) { ?>
				<?php echo UI_Results::thanks(array(
					'results' => $thanks
				)); ?>
				
				<?php } else { ?>
						<p class='chunk_empty'>This user has not yet received any thanks from others on GiftFlow</p>
				<?php } ?>
		</div><!-- close profile_thanks -->
	</div> <!-- close profile left -->


	<div id='profile_header_right' class='profile_column span6'><!-- open right column -->
		<div id='thankFollow' class='profile_chunk'>
			<div class='btn-group'>
			<?php if(isset($logged_in_user_id)) { ?>
				<a href='<?php echo site_url("people/follow/".$u->id); ?>' id='follow_button' class='btn btn-action'>Follow</a>
					<?php if($visitor) { ?>
						<a id='thank_button' class='btn profile_action btn-success'>Thank</a>
					<?php } ?>
			<?php } ?>
			</div>

			<div id='follow_deets'>
				<span class='metadata'>
					<?php echo count($following).' Following';?>
					<?php echo count($followers).' Followers';?>
				
				</span>

				<div class='thumb_grid'>
				<?php foreach($followers as $val) { ?>
					<a href="<?php echo site_url('people/'.$val->id); ?>" title="<?php echo $val->screen_name;?>">
						<img src='<?php echo $val->default_photo->thumb_url;?>' />
					</a>
				 <?php } ?>
				</div>
			</div>

			<div id='profile_thank_form' style= 'display:none;' >
				<form name = 'thankyou' id='thankyouform' method='post'>
					<p>
					<label for='gift'>What did <?php echo $u->screen_name; ?> give you? (brief title)</label>
					</p>
					<p>
						<input type='text' class='big-border' name='gift' id='thankyou_gift' value='' class='required'/>
					</p>
					<p>
					<label for='body'>Be sure to describe the gift.</label>
					</p>
					<p>
						<textarea rows='5' class='big-border required' name='body' id='body' value=''></textarea>
					</p>
						<!-- hidden fields -->
						<input type='hidden' id='recipient_id' name='recipient_id' value='<?php echo $u->id; ?>'/>
						<input type='hidden' id='recipient_email' name='recipient_email' value='<?php echo $u->email; ?>'/>
						<input type='hidden' name='formtype' value='thankyou'/>
					<p>
						<input type='submit' class='btn' value='Send'/>
						<a id='thank_cancel' class='btn' href='#'>Cancel</a>
					</p>
					<span id='errortext'></span>
				</form>
			</div>
		</div>

			<!--- Gifts and Needs Column -->
			<div id='profile_gifts' class='profile_chunk'>

				<?php if(!empty($gifts)) { ?>

					<span class='pTitle'>Gifts</span>

					<?php echo UI_Results::goods(array(
						"results"=> $gifts,
						'mini' => TRUE,
						'border'=> FALSE
					)); ?>
					
				<?php } else { ?>
					<span class='pEmpty'>Gifts</span>
					<p class='chunk_empty'>This user does not have any gifts listed.</p>
				<?php } ?>

			</div><!--close profile_gifts -->


			<!-- NEEDS column -->
			<div id='profile_needs' class='profile_chunk'>
				<!--<a class='btn profile_action' id='offer'>Offer</a>-->
				<?php if(!empty($needs)) { ?>

					<span class='pTitle'> Needs</span>	

					<?php echo UI_Results::goods(array(
				"results"=> $needs,
				'mini' => TRUE
			)); ?>
					
			<?php } else { ?>
				<span class='pEmpty'> Needs</span>	
				<p class='chunk_empty'>This user does not have any needs listed.</p>
			<?php } ?>
		</div><!-- close profile_needs-->


	</div><!-- close row-fluid -->
</div>


<script type='text/javascript'>


$('#thank_button').click( function() {
	$('#follow_deets').hide();
	$('#profile_thank_form').show();
});
$('#thank_cancel').click( function() {
	$('#profile_thank_form').hide();
	$('#follow_deets').show();
});

/*
var more_form, top_form, column;

$('.more_form').click(function() {

	column.find('.top_form').hide();
	column.find('.more_request').show();
	
	//$(this).hide();
	//var top = $(this).attr('id');
	//$('span.'+top).hide();

});

$('.less_form').click(function() {
	$('.profile_form').hide();
});

$('.profile_action').click(function() {
	$('.top_form').show();
	$('.more_request').hide();
	$('.results_list').show();

	column = $(this).closest('.profile_column');
	list = column.children('.list');
	list.children('ul.results_list').hide();

	$('.profile_form').hide();
	var which = '#'+$(this).attr('id')+'_form';
	$(which).show();
	$('.more_form').show();
});
*/

</script>
