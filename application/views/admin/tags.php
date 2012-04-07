<table id='users' class='ui-widget'>
<thead>
	<tr>
		<th>Tag</th>
		<th>Goods</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php foreach($tags as $key=>$val) { ?>
<tr>
	<td><?php echo $val->name; ?></td>
	<td><?php echo $val->good_count; ?></td>
	<td><a onclick="deleteTag(<?php echo $val->id ?>, '<?php echo $val->name ?>', <?php echo $val->good_count ?>)" href="#">delete</a> |
		<a onclick="renameTag(<?php echo $val->id ?>, '<?php echo $val->name ?>', <?php echo $val->good_count ?>)">rename</a> |
		<a onclick="mergeTag(<?php echo $val->id ?>, '<?php echo $val->name ?>', <?php echo $val->good_count ?>)">merge</a></td>
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