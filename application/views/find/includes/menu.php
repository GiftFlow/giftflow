<ul  class='sidebar_menu filters'>
  <li >
			<span id="order_by_label">
				Sort By
			</span>
			<select name="order_by" id="order_by" class='input-medium'>
			<option value="newest"<?php if($args['order_by'] == 'newest') { echo "selected"; }?>>Newest</option>
			<option value="nearby" <?php if($args['order_by'] =='location_distance') { echo "selected"; } ?>>Nearby</option>
			</select>
  </li>

  <li>
			<span>
			Search Within	
			</span>
			<select name="radius" id="radius" class='input-medium'>
				<option value="10">10 miles</option>
				<option value="100" selected>100 miles</option>
				<option value="1000">1000 miles</option>
				<option value="100000">Global</option>
			</select>
  </li>

<!-- find sidebar closing ul tag is in find index view -->
