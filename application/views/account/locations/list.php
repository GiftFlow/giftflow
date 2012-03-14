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
							<span id='default'>
								Default
							</span>
						<?php } ?>
						<span id='city_state'>
							<?php echo $val['address'];?>
						</span>
					</div>
					<div class='list_controls control_right' >
						<a href="#" class="button left options secondary" style='height: 2.2em;'></a>
						<ul class='tooltip_menu' >
							<?php if(!$val['default']) { ?>
								<li>
									<a href="<?php echo site_url('account/locations/'.$val['id'].'/default/'); ?>" >
										<span class='ui-icon ui-icon-star'></span>
										Make Default Location
									</a>
								</li>
							<?php } ?>
							<li>
								<a href="<?php echo site_url('account/locations/'.$val['id'].'/edit'); ?>" class='edit'>
									<span class='ui-icon ui-icon-pencil'></span>
									Rename
								</a>
							</li>
							<li>
								<a href="<?php echo site_url('account/locations/'.$val['id'].'/delete/'); ?>" >
									<span class='ui-icon ui-icon-trash'></span>
									Delete
								</a>
							</li>
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
                <input type="text" name="location" class="required" />
                <input type="submit" value="Add" id="add_location"/>
            </form>
        </div> 
	</div>
	<!-- eof div.right_content -->
	
</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$("#add_form").button();
	$('#add_form').validate();
	$(".button.options").button({
		icons: {
			primary: 'ui-icon-gear',
			secondary: 'ui-icon-triangle-1-s'
		}, 
		text:false
	}).click(function(){
		close_menu();
		$(this).toggleClass('clicked');
		$(this).addClass('ui-state-focus');
		$(this).siblings(".tooltip_menu").toggle();
		$('body').bind('click', function(e){
    		if($(e.target).closest('ul.tooltip_menu').length == 0){
				// click happened outside of menu, hide any visible menu items
				close_menu();
    		}
		});
		return false;
	});
	
	function close_menu(){
		$('ul.tooltip_menu').hide();
		$(".button.options").removeClass('clicked');
	}

	$("#edit_location").jqm({ 	
		ajax: '@href', 
		trigger: '.edit', 
		onLoad: function(){
			$('#edit_location').jqmAddClose('#close_this'); 
			$("input:submit").button();
			$("button").button();
		} 
	});
});
</script>