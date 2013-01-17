<div class='row'>
	<div class='span2 chunk'>
		<?php echo $menu; ?>
	</div>

	<div class='span9 inbox_summary'>
		<div class='row-fluid chunk'>
			<h3>Recent Activity</h3>
			<?php echo UI_Results::events(array(
				"results" => $activity,
				"row" => FALSE,
				"mini" => TRUE,
				"border" => FALSE
			)); ?>
		</div>
	</div>
</div>
