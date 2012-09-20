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
