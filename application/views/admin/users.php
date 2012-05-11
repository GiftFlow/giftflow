<table id='users' class='ui-widget'>
<thead>
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Email</th>
		<th>Facebook</th>
		<th>Joined</th>
		<th>Goods</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php foreach($users as $key=>$val) { ?>
<tr>
	<td><?php echo $val->id; ?></td>
	<td><?php echo "<a href=". site_url('people/'.urlencode($val->id)) .">" . $val->screen_name . "</a>"; ?></td>
	<td><?php echo $val->email; ?></td>
	<td><?php if(!empty($val->facebook_id)) echo "Yes"; else echo "No"; ?></td>
	<td><?php echo user_date($val->created,"m/d/Y");?></td>
	<td><?php echo $val->good_count; ?></td>
	<td>
		<?php
			if ($val->role != "admin") {
				echo "<a onclick=\"toggleUserDisable( $val->id , '$val->screen_name', '$val->status')\">";
				if($val->status == "disabled")
					echo "enable";
				else echo "disable";
				echo "</a>";
			}
		?>

</td>
</tr>
<?php } ?>
</tbody>
</table>
<script type='text/javascript'>
$(function(){
	$("#users").dataTable( {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"iDisplayLength": 10
		}
	);
});
</script>
