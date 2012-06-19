<ul id='find_sidebar' class='sidebar_menu filters'>
	<li>
		<span class="filter_title">
		  Search by Keyword
      	</span>

			<form name='find_goods' id="find_goods" action="" method='post'>
			
			<div>
				<div class='css_left'>
					<input type='text' class='' style='width: 110px;' id="q" name='q' value='<?php echo $args["q"];?>' />
				</div>
				<div class='css_left'>
					<input class='btn' type='submit' id="find" value='Find' />
				</div>
			</div>
				
			</form>

	</li>
  <li style='clear:both;'>
			<span id="order_by_label">
				Sort By
			</span>
			<select name="order_by" id="order_by">
				<option value="newest">Newest</option>
				<option value="nearby">Nearby</option>
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
