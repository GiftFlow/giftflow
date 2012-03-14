<h1>GiftFlow Control Panel</h1>
<ul>
	<li><a href='<?php echo site_url('admin/users'); ?>'>Users</a></li>
	<li><a href='<?php echo site_url('admin/gifts'); ?>'>Gifts</a></li>
	<li><a href='<?php echo site_url('admin/needs'); ?>'>Needs</a></li>
	<li><a href='<?php echo site_url('admin/tags'); ?>'>Tags</a></li>
    <li><a href='<?php echo site_url('admin/alert_templates'); ?>'>Alert Templates</a></li>

</ul>

<a href='<?php echo site_url('admin/toggle_profiler'); ?>'>
	Turn Profiler <?php echo $profiler ? "Off" : "On"; ?>
</a>

<script type='text/javascript'>
$(function(){
	//$(".tabs").tabs();
});
</script>
