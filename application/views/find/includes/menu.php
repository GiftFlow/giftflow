<ul  class='sidebar_menu filters'>
	<li>
	<?php if($type == 'people') { ?>
			<div class='btn-group' id='peopleTypes'>
			<button class='ptype btn btn-small <?php if($args["profile_type"] == "people") {echo "disabled";}?> ' value='individual'>People</button>
			<button class='ptype btn btn-small <?php if($args["profile_type"] == "nonprofit") {echo "disabled";}?> ' value='nonprofit'>Nonprofits</button>
			<button class='ptype btn btn-small <?php if($args["profile_type"] == "business") {echo "disabled";}?> ' value='business'>Businesses</button>
			</div>
	<?php } ?>
<!-- find sidebar closing ul tag is in find index view -->
