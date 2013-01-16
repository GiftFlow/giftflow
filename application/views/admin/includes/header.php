<div class='row'>
	<h1>GiftFlow Admin Area</h1>
	<a href='<?php echo site_url('admin/toggle_profiler'); ?>' class='css_right btn <?php echo $profiler ? "btn-danger" : "btn-success"; ?>'>Turn Profiler <?php echo $profiler ? "Off" : "On"; ?>
	</a>
</div>

<ul class="nav nav-pills">
	<li <?php if($segment[2]==""){ echo 'class="active"'; } ?>><a href='<?php echo site_url('admin'); ?>'>Home</a></li>
	<li <?php if($segment[2]=="users"){ echo 'class="active"'; } ?>><a href='<?php echo site_url('admin/users'); ?>'>Users</a></li>
	<li <?php if($segment[2]=="gifts"){ echo 'class="active"'; } ?>><a href='<?php echo site_url('admin/gifts'); ?>'>Gifts</a></li>
	<li <?php if($segment[2]=="needs"){ echo 'class="active"'; } ?>><a href='<?php echo site_url('admin/needs'); ?>'>Needs</a></li>
	<li <?php if($segment[2]=="tags"){ echo 'class="active"'; } ?>><a href='<?php echo site_url('admin/tags'); ?>'>Tags</a></li>
</ul>

<div class="admin-wrapper">
