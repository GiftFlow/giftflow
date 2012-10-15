<div align="right">
 <a onclick="update_alert_template(0, '', '')" href="#">add new alert template</a>
</div>
<table id='users' class='ui-widget'>
<thead>
	<tr>
		<th>Alert Name</th>
		<th>Alert Body</th>
		<th>Action</th>
	</tr>
</thead>
<tbody>
<?php foreach($tags as $key=>$val) { ?>
<tr>
	<td><?php echo $val->name; ?></td>
	<td><?php echo $val->body; ?></td>
	<td>
    	<a onclick="update_alert_template(<?php echo $val->id ?>, '<?php echo $val->name ?>', '<?php 
				// TODO: fully serialize and unserialize to handle special characters
				//echo str_replace(array("\r\n", "\r", "\n"), "+++", $val->body) 
			  echo $val->body;
							?>')" href="#">edit</a> |
      <a onclick="delete_alert_template(<?php echo $val->id ?>, '<?php echo $val->name ?>')" href="#">delete</a> 
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