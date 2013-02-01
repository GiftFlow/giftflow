<div class='row'>

<div class='span6'>
		<ul>
			<?php foreach($groups as $g) { ?>

				<li>
					<h3><a href="<?php echo site_url('groups/view/'.$g->id); ?>"><?php echo $g->name; ?></a></h3>
					<p><?php echo $g->description; ?></p>
					<p>Location: <?php echo $g->location_city; ?> </p>
					<p><?php echo "invite:".$g->members_can_invite." privacy: ".$g->privacy;?>
					<p>Users <?php echo count($g->users); ?></p>
					<p>Gifts and Needs <?php echo count($g->goods); ?></p>
				</li>
			<?php } ?>

		</ul>
	</div>
	<div class='span6'>
		<a href="<?php echo site_url('groups/create'); ?>" class='btn btn-success'>Create Group</a>
	</div>



</div>
