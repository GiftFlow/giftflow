<div id='dash_header'>
		<img src="<?php echo base_url().'assets/images/headings/people_menu.jpg';?>" class="heading people_header" alt="" />
</div>

<ul id='find_sidebar' class='sidebar_menu filters'>
	
				<li <?php if((empty($segment[2])) || $segment[2]=='index' || $segment[2]=="community"){ echo  "class='active'"; } ?>>
					<a href='<?php echo site_url('people');?>' > 
					Community
					
					</a>
				</li>
				<li <?php if($segment[2]=='find'){ echo  "class='active'"; } ?>>
					<a href='<?php echo site_url('people/find');?>'> 
					Find
					</a>
				</li>
				
      <li style='clear:both;'>
			<span id="order_by_label">
				Sort By
			</span>
			<select name="order_by" id="order_by">
				<option value="newest" selected='selected'>Newest</option>
				<option value="nearby">Nearby</option>
			</select>
  </li>
				<?php if($logged_in){?>
				
					<li <?php if($segment[2]=='friends'){ echo  "class='active'"; } ?>>
						<a href='<?php echo site_url('people/friends');?>'> 
							Friend Finder
						</a>
					</li>
					<li <?php if($segment[3]=='giftcircle'){ echo  "class='active'"; } ?>>
						<a href='<?php echo site_url('people/lists/giftcircle');?>'> 
							Gift Connections
						</a>
					</li>
					<li <?php if($segment[3]=='following'){ echo  "class='active'"; } ?>>
						<a href='<?php echo site_url('people/lists/following');?>'> 
							Following
						</a>
					</li>
					<li <?php if($segment[2]=='invite'){ echo  "class='active'"; } ?>>
						<a href='#<?php // echo site_url('people/invite');?>'> 
							Invite
						</a>
					</li>
				<?php } ?>
		
</ul>
