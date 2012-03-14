<div id="your_needs" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
	
		<!--<img src='<?php echo base_url(); ?>assets/images/headings/your_needs.png' style='margin-top: 30px;' />-->
		
		<a href="<?php echo site_url('you/needs/add');?>" class="button">Add a Need</a>

		<?php if(!empty($goods)) { ?>
			<table id='goods' class='sortable'>
				<thead>
					<tr>
						<th>Title</th>
						<th>Posted</th>
						<!--<th>Offers</th>-->
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($goods as $key=>$val) {
					if ( $key%2 == 1 )
					{
						echo "<tr class='odd'>";
					}
					else
					{
						echo "<tr>";
					}
					echo "<td><a href='".site_url('needs/'.$val->id)."'>".$val->title."</a></td>";
					echo "<td>".user_date($val->created,"n/j/o")."</td>";
				//	echo "<td>".$val->transaction->count."</td>";
					echo "<td><a href='".site_url('gifts/'.$val->id.'/edit')."'>Edit</a></td>";
					echo "</tr>";
				}
				?>
				</tbody>
			</table>
		<?php } else { ?>
		<div id='empty_gift_history'>
		<p>When you have posted your needs, this is where they will show up.</p>
		</div>
		<?php  } ?>
		
	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){

	$(".button").button();

	var table = $("#goods").dataTable( {
			"bJQueryUI": true,
			"sPaginationType": "full_numbers",
			"iDisplayLength": 10
	});
});
</script>