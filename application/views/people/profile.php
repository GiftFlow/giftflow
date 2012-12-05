<!-- profile is divided into two columns! -->
<div class = 'row-fluid' class='profile_header'>

	<div class='span1' id='profile_masthead'>

			<a href="<?php echo current_url();?>" class="user_image left medium">
				<img src="<?php echo $profile_thumb; ?>" />
			</a>	
	</div>
	<div class='span3'>
			<p class='nicebigtext' id='profile_name'><?php echo $u->screen_name; ?></p>
	</div>
	<div class='span8'>
			<div class='btn-group profile_actions'>
				<?php if($visitor) { ?>
					<?php if(isset($is_following) &&($is_following)) { ?>
						<a href='<?php echo site_url("people/unfollow/".$u->id);?>' id='unfollow_button' class='btn btn-medium'><i class='icon-eye-close'></i>Unfollow</a>
					<?php } else { ?>
					<a href='<?php echo site_url("people/follow/".$u->id); ?>' id='follow_button' class='btn btn-medium <?php if(empty($logged_in_user_id)) { echo "disabled"; }?>'><i class='icon-eye-open'></i> Follow</a>
					<?php } ?>
					<a href='#' id='message_button' class='btn profile_action btn-medium <?php if(empty($logged_in_user_id)) { echo "disabled";}?>'><i class='icon-pencil'></i> Message</a>
					<a href='#' id='thank_button' class='btn profile_action btn-medium btn-success <?php if(empty($logged_in_user_id)){echo "disabled";}?>'><i class='icon-gift icon-white'></i> Thank</a>
				<?php } else { ?>
					<?php if(!isset($userdata['bio'])) { ?>
						<a class='btn' href="<?php echo site_url('account'); ?>"><i class='icon-user'></i>Update profile</a>
					<?php } ?>
						<a class='btn' href="<?php echo site_url('account/photos'); ?>"><i class='icon-camera'></i>Upload photos</a>
						<a class='btn' href="<?php echo site_url('you/list_goods/need'); ?>"><i class='icon-minus-sign'></i>Your Needs</a>
						<a class='btn' href="<?php echo site_url('you/list_goods/gift'); ?>"><i class='icon-gift'></i>Your Gifts</a>
						<a class='btn' href="<?php echo site_url('you/watches'); ?>"><i class='icon-time'></i>Your Watches</a>
				<?php } ?>
			</div>
				
	</div><!-- close profile_masthead -->

</div> <!-- close profile_header row -->


<div class='row-fluid'>
	<div class='span6'>
			<div class='profile_form' id='profile_thank_form'style='display:none;' >
				<?php echo $thankform; ?>
			</div>
			<div class='profile_form' id='profile_message_form' style='display:none;'>
				<?php echo $messageform;?>
			</div>
	</div>

</div><!-- close formButtonRow -->

<div class='row-fluid'>
	<div class='span4'>
		<div class='chunk'>
				<p class='nicebigtext'>Bio</p>

					<?php if(!empty($u->location)) { ?>
							<b>	Location:</b>
									<?php if(!empty($u->location->city)) { echo $u->location->city.','; } ?>
									<?php if(!empty($u->location->state)) { echo $u->location->state; } ?>
									<?php if(!empty($u->location->country)) { echo $u->location->country.', '; } ?>
						<?php }  ?>
					<p>
						<b>Member since: </b><?php echo user_date($u->created,"F jS Y"); ?>
					</p>
		
				<p><?php if(!empty($u->bio)) { echo $u->bio; } ?></p>
				<p><?php if(!empty($u->url)) { ?>
					<a href="<?php echo "http://".$u->url; ?>"> <?php echo $u->url; ?></a>
				</p><?php } ?>


		</div> <!-- close profile info -->

		<div class='chunk thumb_grid'>
				<p class='nicebigtext'>Photos</p>
				<p>
				<?php foreach($u->photos as $val) { ?>
				<a class='photo_mod' id="<?php echo site_url($val->url); ?>" href='#photo_modal' role='button' data-toggle='modal'>
						<img src='<?php echo site_url($val->thumb_url);?>' />
					</a>
				<?php } ?>
				
				</p>
				<div class='modal hide' id='photo_modal' tabindex='-1' role='dialog' aria-labelledby='photo_modal_label' aria-hidden='true'>
					<div class='modal-header'>
						<h3 id='photo_modal_label'>Photo of <?php echo $u->screen_name; ?></h3>
					</div>
					<div class='modal-body'>
						<img src='' id = 'mod_image'/>
					</div>
					<div class='modal-footer'>
						<button class='btn' data-dismiss='modal' aria-hidden='true'>Close</button>
					</div>
				</div>
		</div>
		<?php if(!empty($followers) || !empty($following)) { ?>
		<div class='chunk'>
				<div class='thumb_grid'>
				<p class='nicebigtext'>Followers</p>
				<?php foreach($followers as $val) { ?>
				<a href="<?php echo site_url('people/'.$val->id); ?>" title="<?php echo $val->screen_name;?>">
					<img src='<?php echo $val->default_photo->thumb_url;?>' />
				</a>
			 <?php } ?>
				</div>
				<div class='thumb_grid'>
				<p class='nicebigtext'>Following</p>
				<?php foreach($following as $val) { ?>
				<a href="<?php echo site_url('people/'.$val->id); ?>" title="<?php echo $val->screen_name;?>">
					<img src='<?php echo $val->default_photo->thumb_url;?>' />
				</a>
			 <?php } ?>
				</div>
		</div>
		<?php } ?>
	</div><!-- close span -->
	<div class = 'span4'>
			<!--- Gifts and Needs Column -->
		<div class='chunk'>

				<span class='nicebigtext'>Gifts</span>
			<?php if(!empty($gifts)) { ?>
				<?php echo UI_Results::goods(array(
					"results"=> $gifts,
					'size' => "medium",
					'border'=> FALSE
				)); ?>
				
			<?php } else { ?>
				<p class='chunk_empty'><?php echo $u->screen_name; ?> does not have any gifts listed.</p>
			<?php } ?>

		</div><!--close profile_gifts -->


		<!-- NEEDS column -->
		<div class='chunk'>
				<span class='nicebigtext'> Needs</span>	
			<?php if(!empty($needs)) { ?>

				<?php echo UI_Results::goods(array(
				"results"=> $needs,
				'size' => "medium",
				)); ?>
				
			<?php } else { ?>
				<p class='chunk_empty'><?php echo $u->screen_name; ?> does not have any needs listed.</p>
			<?php } ?>
		</div><!-- close profile_needs-->
	</div><!-- close span -->

	<div class='span4'><!-- open right content column -->

		<div class='chunk'>
			<span class='nicebigtext'>Reviews</span>

				<?php if(!empty($reviews)) { ?>
					<?php echo UI_Results::reviews(array(
						'results'=>$reviews
					)); ?>

				<?php } else { ?>
					<p class='chunk_empty'><?php echo $u->screen_name;?> has not yet received any reviews.</p>
				<?php } ?>

		</div><!--close reviews_list -->

		<div class='chunk'>
			<span class='nicebigtext'>Thanks</span>	

			<?php if(!empty($thanks)) { ?>
				<?php echo UI_Results::thanks(array(
					'results' => $thanks
				)); ?>
				
				<?php } else { ?>
					<p class='chunk_empty'><?php echo $u->screen_name;?> has not yet received any thanks.</p>
				<?php } ?>
		</div><!-- close profile_thanks -->

	</div><!-- close span -->
</div><!-- close row fluid -->
<script type='text/javascript'>
$(function() {

var logged_in = <?php echo json_encode($logged_in); ?>;


$('#photo_modal').modal({show:false});

$('.photo_mod').click(function() {
	var imgUrl = $(this).attr('id');
	console.log(imgUrl);
	$('#mod_image').attr('src',imgUrl);
});

var fadeSection = function(param) {

	if(param === 'in') {
		$('#profile_top').css('opacity',1);
	}else if(param == 'out') {
		$('#profile_top').css('opacity',0.5);
	}
};
if(logged_in) {
	$('.profile_action').click( function() {
		fadeSection('out');
		$('.profile_form').hide();
	});

	$('#thank_button').click( function() {
		$('#profile_thank_form').show();
	});
	$('#thank_cancel').click( function() {
		fadeSection('in');
		$('#profile_thank_form').hide();
	});

	$('#message_button').click(function() {
		$('#profile_message_form').show();
	});
	$('#message_cancel').click(function() {
		fadeSection('in');
		$('#profile_message_form').hide();
	});
}

});

</script>
