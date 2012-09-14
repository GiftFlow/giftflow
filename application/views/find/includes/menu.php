<ul id='find_sidebar' class='sidebar_menu filters'>
	<li>
	</li>
  <li style='clear:both;'>
			<span id="order_by_label">
				Sort By
			</span>
			<select name="order_by" id="order_by" class='span2'>
				<option value="newest">Newest</option>
				<option value="nearby">Nearby</option>
			</select>
  </li>

  <li style='clear:both;'>
			<span>
			Search Within	
			</span>
			<select name="radius" id="radius" class='span2'>
				<option value="10">10 miles</option>
				<option value="100" selected>100 miles</option>
				<option value="1000">1000 miles</option>
				<option value="100000">Global</option>
			</select>
  </li>
	<li>
			<span class="filter_title">
				Categories
			</span>
			
			<ul id="categories">
				<li>
					<a href="<?php echo site_url("find/gifts/");?>">
						All Categories
					</a>
				</li>
				
			<?php foreach($categories as $key=>$val){ ?>
				<li>
					<a href="<?php echo site_url("find/gifts")."/?category_id=".$val->id;?>" rel="<?php echo $val->id;?>">
						<?php echo $val->name; ?>
					</a>
				</li>
			<?php } ?>
			
			</ul>
	</li>
</ul>
