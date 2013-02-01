<div class='span2 chunk'>
	<h1><?php echo $type; ?></h1>
		<?php foreach($monthly as $year => $month) { ?>

		<h3><?php echo "20".$year; ?></h3>

			<ul>
				<?php foreach($month as $key=>$num) { ?>
					<li><?php echo $calendar[$key]." = ".$num; ?> </li>
				<?php } ?>
			</ul>

		<?php } ?>				

</div>

