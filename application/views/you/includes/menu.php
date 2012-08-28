<ul id='you_sidebar' class='sidebar_menu'>
	<li <?php if($segment[2]=='inbox'){ echo  "class='active'"; } else if($inbox_active) { echo "id='inbox_new'"; } ?>>
		<a href='<?php echo site_url('you/inbox');?>'> 
			Inbox<?php if($inbox_active) { echo " (".$inbox_active_count.")"; }?>
		</a>
	</li>
	<li <?php if($segment[2]=='gifts'||($segment[1]=="gifts"&&$segment[3]=="edit")){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/gifts');?>'> 
			Gifts
		</a>
	</li>
	<li <?php if($segment[2]=='needs'||($segment[1]=="needs"&&$segment[3]=="edit")){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/needs');?>' > 
			Needs
		</a>
	</li>
	<li <?php if($segment[2]=='watches'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/watches');?>' > 
			Watches
		</a>
	</li>
	<li <?php if($segment[1]=='account'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('account');?>' > 
			Account
		</a>
		<ul>
			<li>
				<a href="<?php echo site_url("account/profile");?>">
					Edit Profile
				</a>
			</li>
			<li>
				<a href="<?php echo site_url("account/photos");?>">
					Photos
				</a>
			</li>
			<li>
				<a href="<?php echo site_url("account/locations");?>">
					Locations
				</a>
			</li>
			<li>
				<a href="<?php echo site_url("account/links");?>">
					Linked Accounts
				</a>
			</li>
			<li>
				<a href="<?php echo site_url("account/settings");?>">
					Settings
				</a>
			</li>
			<li>
				<a href="<?php echo site_url('account/delete_user');?>" id='delete_user'>
					Delete account
				</a>
			</li>
			
		</ul>
	</li>
</ul>
