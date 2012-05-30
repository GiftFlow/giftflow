<div id='your_gifts' class='two_panels'>
	
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
		
		<!-- <img src='<?php echo base_url(); ?>assets/images/headings/your_gifts.png' style='margin-top: 30px;' />-->
		
		<a href="<?php echo site_url('you/gifts/add');?>" class="button btn">Add a Gift</a>
		
		<?php if(!empty($goods)) { ?>
		<table id='goods' class='sortable'>
		<thead>
		<tr>
			<th>Title</th>
			<th>Posted</th>
			<!--<th>Requests</th>-->
			<th>Action</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($goods as $key=>$val) {
			echo "<tr ";
			echo ($key%2==1) ? "class='odd'>" : ">";
			echo "<td><a href='".site_url('gifts/'.$val->id)."'>".$val->title."</a></td>";
			echo "<td>".user_date($val->created,"n/j/o")."</td>";
			//echo "<td>".$val->transaction->count."</td>";
			echo "<td><a href='".site_url('gifts/'.$val->id.'/edit')."'>Edit</a></td>";
			echo "</tr>";
			
		}
		?>
		</tbody>
		</table>
		
		<?php } else { ?>
		<div id='empty_gift_history'>
		<p>When you have posted your gifts, this is where they will show up. Start posting!</p>
		</div>
		<?php  } ?>
	
	</div>
	<!-- eof div.right_content -->
</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	var table = $("#goods").dataTable( {
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength": 10
	});

});
</script>