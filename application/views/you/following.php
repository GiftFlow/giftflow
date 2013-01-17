<div class='row'>
	<div class='span2 chunk'>
		<?php echo $menu; ?>
	</div>

	<div class='span9 chunk'>
		<h3>People you are following: </h3>
		<?php if(!empty($following)) { ?>
		<?php echo UI_Results::users(array(
				"results" => $following,
				"mini" => FALSE,
				"include" => array('created'),
				"row" => FALSE,
				"follow" => TRUE
			)); ?>
		<?php } ?>	
		<h3>People Following You: </h3>
		<?php if(!empty($followers)) { ?>
		<?php echo UI_Results::users(array(
				"results" => $following,
				"include" => array('created'),
				"mini" => FALSE,
				"row" => FALSE,
				"follow" => TRUE
			)); ?>
		<?php } ?>	
	</div>
</div>
