<ul  class='sidebar_menu filters'>
	<li>
	<p>Choose a member type.</p>
	<?php if($type == 'people') { ?>
			<button class='ptype btn btn-large btn-success <?php if($args["profile_type"] == "people") {echo "disabled";}?> ' value='individual'>People</button>
			<button class='ptype btn btn-large btn-success<?php if($args["profile_type"] == "nonprofit") {echo "disabled";}?> ' value='nonprofit'>Nonprofits</button>
			<button class='ptype btn btn-large btn-success<?php if($args["profile_type"] == "business") {echo "disabled";}?> ' value='business'>Businesses</button>
	<?php } ?>
	</li>
<!-- find sidebar closing ul tag is in find index view -->
