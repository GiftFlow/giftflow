<ul id='you_sidebar' class='sidebar_menu'>
	<!--<li style='margin-bottom: 22px;'>
		<img alt="You" src="<?php echo base_url();?>assets/images/headings/you_menu.png">
	</li>-->
	<li <?php if($segment[2] == 'welcome') { echo "class='active'"; } ?> >
		<a href='<?php echo site_url('you/welcome/?welcome=show'); ?>'> 
			Welcome
		</a>
	</li>
	
	<li <?php if(!$welcome && $segment[1]=="you" && (empty($segment[2])) || $segment[2]=='index'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you');?>'> 
			Activity Feed
		</a>
	</li>
	<li <?php if($segment[2]=='gifts'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/gifts');?>'> 
			Your Gifts
		</a>
	</li>
	<li <?php if($segment[2]=='needs'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you/needs');?>' > 
			Your Needs
		</a>
	</li>
	<li <?php if($segment[2]=='transactions'){ echo  "class='active'"; } else if($trans_check) { echo "id='inbox_new'"; } ?>>
		<a href='<?php echo site_url('you/transactions');?>'> 
      Your Inbox<?php if($trans_check) { echo "(".$new_trans.")"; }?>
		</a>
		<ul>
			<li>
				<a href="<?php echo site_url("you/transactions/?direction=incoming");?>">
					Incoming
				</a>
			</li>
			<li>
				<a href="<?php echo site_url("you/transactions/?direction=outgoing");?>">
					Outgoing
				</a>
			</li>
			<li>
				<a href="<?php echo site_url("you/transactions/?status=completed");?>">
					Reviews
				</a>
			</li>
		</ul>
	</li>
	<li <?php if($segment[1]=='account'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('account');?>' > 
			Your Account
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
	<li <?php if($segment[2]=='donate' || $segment[1]=="donate"){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/donate');?>' > 
			Donate
		</a>
	</li>
</ul>
