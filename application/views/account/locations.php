<div class='row'>
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	<div class='span6 chunk' id='locations_list'>
            <form class="well" method="post" id="add_form" name="add_location" action="<?php echo site_url('account/locations/add'); ?>">
            	<div class="input-append">
                <input placeholder="Add a Location" id='add_location' type="text" name="location" class="required" />
                <input type="submit" class="btn" value="Add" id="add_location"/>
                </div>
			</form>

		<?php if(!empty($locations)) { ?>
			<ul class='list list_menu'>
			<?php foreach($locations as $key=>$val){ ?>
				<li id='<?php echo $val['id']; ?>'>
					<div class='row-fluid'>
						<div class='span6'>
							<?php if($val['default']){ ?>
								<span class="label label-success">Default</span>
							<?php } ?>
							<span id='city_state'>
								<?php echo $val['address'];?>
							</span>
						</div>
						<div class="btn-group span4">
							<?php if(!$val['default']) { ?>
									<a href="<?php echo site_url('account/locations/'.$val['id'].'/default/'); ?>" 
									class ='btn-small btn'>Make Default</a> 
							<?php } ?>
							<a href="<?php echo site_url('account/locations/'.$val['id'].'/delete/'); ?>"
									class='btn-small btn'>Delete</a>
						</div>
					</div>

				</li>
			<?php } ?>
			</ul>
		</div>
		<div class='span3 chunk'>
			<img id='locations_map' class='css_right' src='http://maps.google.com/maps/api/staticmap?sensor=false&size=230x230&markers=color:blue<?php foreach($locations as $key=>$val) { echo '|'.$val['latitude'].','.$val['longitude']; } ?>' />
		</div>
		<?php } else { ?>
			<p>You do not yet have any locations.</p>
		<?php } ?>
		
		 
	</div>
</div>

<script type='text/javascript'>
$(function(){
	$('#add_form').validate();

	$('.dropdown-toggle').dropdown();

	GF.Locations.initialize($('#add_location'));

});
</script>
