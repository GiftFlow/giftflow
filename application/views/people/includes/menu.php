<ul id='find_sidebar' class='sidebar_menu filters'>

				<li <?php if($segment[2]=='find'){ echo  "class='active'"; } ?>>
					<a href='<?php echo site_url('find/people');?>'> 
					Find
					</a>
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
