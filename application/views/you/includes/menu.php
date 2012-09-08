<ul id='you_sidebar' class='sidebar_menu'>
	<!--<li style='margin-bottom: 22px;'>
		<img alt="You" src="<?php echo base_url();?>assets/images/headings/you_menu.png">
	</li>-->
	<li <?php if($segment[2] == 'welcome') { echo "class='active'"; } ?> >
		<a href='<?php echo site_url('you/welcome/?welcome=show'); ?>'> 
			<?php echo lang("nav_you_welcome");?>
		</a>
	</li>
	
	<li <?php if($segment[1]=="you" && !$welcome  && (empty($segment[2])) || $segment[2]=='index'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('you');?>'> 
			<?php echo lang("nav_you_activity_feed");?>
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
	<li <?php if($segment[2]=='transactions'){ echo  "class='active'"; } else if($transactions_active) { echo "id='inbox_new'"; } ?>>
		<a href='<?php echo site_url('you/transactions');?>'> 
      <?php echo lang("nav_you_inbox");?><?php if($transactions_active) { echo "(".$transactions_active_count.")"; }?>
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
			<?php echo lang("nav_you_account");?>
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
			<?php echo lang("nav_donate");?>
		</a>
	</li>
</ul>
