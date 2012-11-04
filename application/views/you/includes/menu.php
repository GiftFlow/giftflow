<ul id='you_sidebar' class='sidebar_menu'>
	<li <?php if($segment[2]=='inbox'){ echo  "class='active'"; } else if($activeInbox) { echo "id='inbox_new'"; } ?>>
		<a href='<?php echo site_url('you/inbox');?>'> 
			Inbox<?php if($activeInbox) { echo " (".$inboxCount.")"; }?>
		</a>
	</li>
	<li <?php if($segment[3]=='gift'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/list_goods/gift');?>'> 
			Gifts
		</a>
	</li>
	<li <?php if($segment[3]=='need'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/list_goods/need');?>' > 
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
		 <ul id='account_menu'>
			<li <?php if($segment[2]=='profile'){ echo  "class='active'"; } ?>>
				<a href="<?php echo site_url("account/profile");?>">
					Edit Profile
				</a>
			</li>
			<li <?php if($segment[2]=='photos'){ echo  "class='active'"; } ?>>
				<a href="<?php echo site_url("account/photos");?>">
					Photos
				</a>
			</li>
			<li <?php if($segment[2]=='locations'){ echo  "class='active'"; } ?>>
				<a href="<?php echo site_url("account/locations");?>">
					Locations
				</a>
			</li>
			<li <?php if($segment[2]=='links'){ echo  "class='active'"; } ?>>
				<a href="<?php echo site_url("account/links");?>">
					Linked Accounts
				</a>
			</li>
			<li <?php if($segment[2]=='settings'){ echo  "class='active'"; } ?>>
				<a href="<?php echo site_url("account/settings");?>">
					Settings
				</a>
			</li>
			<li <?php if($segment[2]=='delete_user'){ echo  "class='active'"; } ?>>
				<a href="<?php echo site_url('account/delete_user');?>" id='delete_user'>
					Delete account
				</a>
			</li>
			
		</ul>
	</li>
</ul>
