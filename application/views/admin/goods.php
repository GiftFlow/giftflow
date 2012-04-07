<table id='goods' class='ui-widget'>
<thead>
	<tr>
		<th>ID</th>
		<th>Title</th>
		<th>Quantity</th>
		<th>Location</th>
		<th>Created</th>
		<th>Tags</th>
	</tr>
</thead>
<tbody>
<?php foreach($goods as $key=>$val) { ?>
<tr>
	<td><?php echo $val->id; ?></td>
	<td><?php echo "<a href=". site_url('gifts/'.urlencode($val->id)) .">" . $val->title . "</a>"; ?></td>
	<td><?php echo $val->quantity; ?></td>
	<td><?php echo $val->location->address; ?></td>
	<td><?php echo user_date($val->created,"m/d/Y"); ?></td>
	<td><?php echo $val->tag_count ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<script type='text/javascript'>
$(function(){
	$("#goods").dataTable( {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"iDisplayLength": 10
		}
	);
});
</script>
