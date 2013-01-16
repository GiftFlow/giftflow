<div class='you_dashboard chunk'>
	<div class='row-fluid'>
		<div class='span1'>
			<a href="<?php echo site_url("you");?>" class="user_image">
				<img src="<?php echo $userdata['default_photo_thumb_url'];?>" alt="<?php echo $userdata['screen_name'];?>" />
			</a>
		</div>
		<div class='span4'>
		<p class='nicebigtext'>Dashboard:<a title='Visit your Profile' href='<?php echo site_url("people/".$userdata["user_id"]);?>'> <?php echo $userdata['screen_name'];?></a></p>
		</div>
		<div class='span6'>
			<div id='dashboard_city'>
				<p class='nicebigtext'>Location: <a class='welcome_location' href='#' title='Click to change city'><?php echo $userdata['location']->city; ?></a>
					<button title='Click to change location' id='location_change_button' class='welcome_location btn btn-small'>Change City</button>
				</p>
			</div>
			<div id='home_location_change' style='display:none;'>
				<form name='relocate' class='find_form form-inline' id="relocate" method="post" action="<?php echo site_url('account/relocate'); ?>">
					<label for="welcome_relocate">New City</label>
					<input id ='welcome_relocate' size='16' class='input-medium' type="text"  placeholder="" name="header_relocation" />
					<input type='hidden' name='relocate_redirect' value="<?php echo current_url(); ?>"/>
					<button type='submit' class='btn'>Submit</button>
					<button type='button' class='btn' title='cancel' id='cancel_change'>Cancel</button>
				</form>
			</div>
		</div>
	</div>
	<div class='row-fluid dashboard_actions'>

		<div class='btn-group span8'>
			<span class='nicebigtext dash_actions'>Actions:</span>
			<a href='<?php echo site_url("you/add_good/gift");?>' class='btn btn-large'><i class='icon-plus'></i> Gift</a>
			<a href='<?php echo site_url("you/add_good/need");?>' class='btn btn-large'><i class='icon-plus'></i> Need</a>
			<a href='<?php echo site_url("you/add_thank");?>' class='btn btn-large'><i class='icon-plus'></i> Thank</a>
			<a href='<?php echo site_url("you/watches");?>' class='btn btn-large'><i class='icon-plus'></i> Watch</a>
		</div>
	</div>
</div>

<script type='text/javascript'>

$(function() {

	$('.welcome_location').tooltip();
	$('.welcome_location').click(function() {
		$('#dashboard_city').hide();
		$('#home_location_change').show();
	});
	$('#cancel_change').click(function() {
		$('#home_location_change').hide();
		$('#dashboard_city').show();
	});

	GF.Locations.initialize($('#welcome_relocate'));
});

</script>

