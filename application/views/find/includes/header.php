<div id='dash_header'>
	<?php if($args['type']=="gift"||$args['type']=="need"){ ?>
		<img src="<?php echo base_url()."assets/images/headings/".$args['type']."s.png";?>" class="heading" alt="<?php echo ucfirst($args["type"]."s");?>" />
	<?php } else {  ?>
		<h1>People</h1>
	<?php } ?>
</div>
