<ul id='you_sidebar' class='sidebar_menu'>
	<li <?php if($segment[2]=='inbox'){ echo  "class='active'"; } else if($inbox_active) { echo "id='inbox_new'"; } ?>>
		<a href='<?php echo site_url('you/inbox');?>'> 
			<?php echo lang("nav_you_inbox");?><?php if($inbox_active) { echo " (".$inbox_active_count.")"; }?>
		</a>
	</li>
	<li <?php if($segment[2]=='gifts'||($segment[1]=="gifts"&&$segment[3]=="edit")){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/gifts');?>'> 
			<?php echo lang("nav_you_gifts");?>
		</a>
	</li>
	<li <?php if($segment[2]=='needs'||($segment[1]=="needs"&&$segment[3]=="edit")){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/needs');?>' > 
			<?php echo lang("nav_you_needs");?>
		</a>
	</li>
	<li <?php if($segment[2]=='watches'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/watches');?>' > 
			<?php echo lang("nav_you_watches");?>
		</a>
	</li>
	<li <?php if($segment[1]=='account'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('account');?>' > 
			<?php echo lang("nav_you_account");?>
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
