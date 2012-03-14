<h1><?php if(!empty($section_title)){ echo $section_title; } else { echo "Your Account"; } ?></h1>
<ul class='gray_toolbar'>
	<li><a href="<?php echo site_url('account/profile'); ?>" <?php if($active_link=="profile"){ echo "class='active'";} ?>>Profile</a></li>
	<li><a href="<?php echo site_url('account/photos'); ?>"  <?php if($active_link=="photos"){ echo "class='active'"; } ?>>Photos</a></li>
	<li><a href="<?php echo site_url("account/locations"); ?>"  <?php if($active_link=="locations"){ echo "class='active'"; } ?>>Locations</a></li>
	<li><a href="<?php echo site_url("account/links"); ?>"  <?php if($active_link=="links"){ echo "class='active'"; } ?>>Linked Accounts</a></li>
	<li><a href="<?php echo site_url('account/settings'); ?>"  <?php if($active_link=="settings"){ echo "class='active'"; } ?>>Settings</a></li>
   
</ul>