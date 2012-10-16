<ul  class='sidebar_menu filters'>
	<li>
	<?php if($type == 'people') { ?>
			<div class='btn-group' id='peopleTypes'>
			<button class='ptype btn btn-small <?php if($args["profile_type"] == "people") {echo "disabled";}?> ' value='individual'>People</button>
			<button class='ptype btn btn-small <?php if($args["profile_type"] == "nonprofit") {echo "disabled";}?> ' value='nonprofit'>Nonprofits</button>
			<button class='ptype btn btn-small <?php if($args["profile_type"] == "business") {echo "disabled";}?> ' value='business'>Businesses</button>
			</div>
	<?php } ?>
	<li >
		<span id="order_by_label">
				Sort By
			</span>
			<select name="order_by" id="order_by" class='input-medium'>
			<option value="newest"<?php if($args['order_by'] == 'newest') { echo "selected"; }?>>Newest</option>
			<option value="nearby" <?php if($args['order_by'] =='location_distance') { echo "selected"; } ?>>Nearby</option>
			</select>
  </li>

<!-- find sidebar closing ul tag is in find index view -->
