
<div class = 'row-fluid' id='profile_header'>
	<div  class='span1'>
		
		<a href="<?php echo current_url();?>" class="user_image left medium">
			<img src="<?php echo $profile_thumb; ?>" />
		</a>	
	</div>
	<div class='span4'>
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
	<div class='span6' style='text-align:right;'>
			<span class='metadata'>
				13 Following, 30 Followers
			</span>
			<a class='btn'>Follow</a>
	</div>
</div><!-- close profile-header -->
<div class = 'row-fluid' id ='profile_bio'>
	<div id='profile_info' class='span12'>
		<p class='nicebigtext'>Bio</p>
		<p><?php if(!empty($u->bio)) { echo $u->bio; } else { echo "This user has yet to fill out their Bio";} ?></p>
		<p><?php if(!empty($u->url)) { echo $u->url; } ?></p>
	</div> <!-- close profile info -->
</div><!-- close vbio and info row -->

<div class ='row-fluid'>
	<div id='profile_gifts' class ='span4 profile_column'>
		<div class='list'>
			<span class='lineup'>
					<span class='pTitle'>Gifts</span>
					<a id='request' class='btn profile_action'>Request</a>
			</span>
					
					<?php if(!empty($gifts)) { ?>
					
						<?php echo UI_Results::goods(array(
							"results"=> $gifts,
							'mini' => TRUE,
							'border'=> FALSE
						)); ?>
						
					<?php } else { ?>
						<p>This user does not have any gifts listed.</p>
					<?php } ?>
		</div>


		<!-- GIFTS request form -->
		<div class='profile_form' id = 'request_form' style='display:none'>

			<form  name='gift_request' method='post'>
				<?php if(!empty($gifts)) { ?>
				<span class='top_form'>
					<label for='gift_select'>Select a gift to request</label>
					<select class='gift_select' name='gift_select'>
							<?php foreach($gifts as $val) { ?>
								<option value="<?php echo $val->id; ?>"><?php echo substr($val->title,0,50); ?></option>
							<?php } ?>
					</select>
					<label for='select_message'>Include a message:</label>
					<input type='text' size='10' name='select_message' id='gift_select_message'/>
				</span>

				<div class='more_request gift_new' style='display:none;'>	
				<?php } else { ?>
					<div class='more_request'>
				<?php } ?>
						<label for='gift_request'>Name the gift you need:</label>
						<input type='text' size='10' value='' name='gift_request' id='gift_request'/>
						
						<label for='request_message'>Include a descriptive message:</label>
						<textarea rows='4' name='request_message' value='' class='request_message'></textarea>
					
					</div>
					<input type='submit' value='Submit' class='btn btn-small'/>
			</form>
			<?php if(!empty($gifts)) { ?>
				<a href='#' class='more_form' id='gift_top_form'>Ask for something new.</a>
				<a href='#' class='less_form'>Cancel</a>
			<?php } ?>
		</div><!-- close request_form -->
	</div><!--close profile_gifts -->


	<!-- NEEDS column -->
	<div class='span4 profile_column' id='profile_needs'>
			<div class='list'>
				<span class='pTitle'> Needs</span>	
				<a class='btn profile_action' id='offer'>Offer</a>
					<?php if(!empty($needs)) { ?>
					
						<?php echo UI_Results::goods(array(
							"results"=> $needs,
							'mini' => TRUE
						)); ?>
						
					<?php } else { ?>
						<p>This user does not have any needs listed.</p>
					<?php } ?>
			</div><!-- close needs_list -->

			<!-- NEEDS offer form -->
			<div class='profile_form'  id='offer_form' style='display:none'>
					<form name='need_offer' method='post'>
						<?php if(!empty($needs)) { ?>
						<span class='top_form'>
							<label for='gift_select'>Select a need to request</label>
							<select class='gift_select' name='gift_select'>
									<?php foreach($needs as $val) { ?>
										<option value="<?php echo $val->id; ?>"><?php echo substr($val->title,0,50); ?></option>
									<?php } ?>
							</select>
							<label for='select_message'>Include a message:</label>
							<input type='text' size='10' name='select_message' id='gift_select_message'/>
						</span>
							
							<div class='more_request need_new' style='display:none;'>
						<?php } else {?>
							<div class='more_request'>
						<?php } ?>
								<label for='gift_request'>Short title of your offer:</label>
								<input type='text' size='10' value='' name='gift_request' id='gift_request'/>
								
								<label for='request_message'>Include a descriptive message:</label>
								<textarea rows='5'name='request_message' value=''class='request_message'></textarea>
							
							</div>
							<input type='submit' value='Submit' class='btn btn-small'/>
					</form>
			<?php if(!empty($needs)) { ?>
				<a href='#' class='more_form' id='need_top_form'>Offer something new.</a>
			<?php } ?>
				<a href='#' class='less_form'>Cancel</a>

			</div><!-- close request_form -->
	</div><!-- close profile_needs-->



	<div class='span4' id='profile_reviews profile_row'>
		<div class='thanks_list'>
			<span class='pTitle'>Thanks & Reviews</span>
				<a class='btn profile_action'>Thank</a>

				<?php if(!empty($giver)) { ?>
					<?php echo UI_Results::reviews(array('results'=>$giver)); ?>
				<?php } else { ?>
						<p>This user has not yet given any gifts</p>
				<?php } ?>
		</div><!--close reviews_list -->
		<div class='thanks_form'>
		

		</div>
	</div> <!-- close reviews -->
	</div> <!-- close second row -->
</div>


<script type='text/javascript'>

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

</script>
