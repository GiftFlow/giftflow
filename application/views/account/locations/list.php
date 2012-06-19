<style>

</style>

<div id='account_profile' class='two_panels'>
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>

		<?php if(!empty($locations)) { ?>
			<ul id='locations_list' class='list list_menu'>
			<?php foreach($locations as $key=>$val){ ?>
				<li id='<?php echo $val['id']; ?>' class='clearfix'>
					<div class='list_content'>
						<span id='address'>
							<?php echo $val['title'];?>
						</span>
						<?php if($val['default']){ ?>
							<span class="label label-success">Default</span>
						<?php } ?>
						<span id='city_state'>
							<?php echo $val['address'];?>
						</span>
					</div>
					
					<div class="btn-group css_right list_controls">
					  <button class="btn btn-large dropdown-toggle" data-toggle="dropdown">
						<i class="icon-cog"></i>
						<span class="caret"></span>
					  </button>
					 
					  <ul class="dropdown-menu">
						<?php if(!$val['default']) { ?>
							<li>
								<a href="<?php echo site_url('account/locations/'.$val['id'].'/default/'); ?>" >
									<i class='icon-star'></i>
									Make Default Location
								</a>
							</li>
						<?php } ?>
						<li>
							<a href="<?php echo site_url('account/locations/'.$val['id'].'/edit'); ?>" class='edit'>
								<i class='icon-pencil'></i>
								Rename
							</a>
						</li>
						<li class="divider"></li>
						<li><a href="<?php echo site_url('account/locations/'.$val['id'].'/delete/'); ?>">
							<i class='icon-trash'></i>
							Delete
						</a></li>
						<!-- dropdown menu links -->
					  </ul>
					</div>
				</li>
			<?php } ?>
			</ul>
			<img id='locations_map' src='http://maps.google.com/maps/api/staticmap?sensor=false&size=230x230&markers=color:blue<?php foreach($locations as $key=>$val) { echo '|'.$val['latitude'].','.$val['longitude']; } ?>' />
			<div id='edit_location' class='jqmWindow'>
			</div>
		<?php } else { ?>
			<p>You do not yet have any locations.</p>
		<?php } ?>
		
		 <div class="module bottom">
            <form method="post" id="add_form" name="add_location" action="<?php echo site_url('account/locations/add'); ?>">
            	<div class="input-append">
                <input type="text" name="location" class="required" />
                <input type="submit" class="btn" value="Add" id="add_location"/>
                </div>
            </form>
        </div> 
	</div>
	<!-- eof div.right_content -->
	
</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$('#add_form').validate();

	$("#edit_location").jqm({ 	
		ajax: '@href', 
		trigger: '.edit', 
		onLoad: function(){
			$('#edit_location').jqmAddClose('#close_this'); 
		} 
	});
});
</script>