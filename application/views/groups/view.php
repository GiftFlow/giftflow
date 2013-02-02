<div class='row'>

<div class='span6'>
	
<h1><?php echo $group->name; ?></h1>

		<p><?php echo $group->description; ?></p>
		<p>Location: <?php echo $group->location_city; ?> </p>
		<p><?php echo "invite:".$group->members_can_invite." privacy: ".$group->privacy;?>


		<?php if(!empty($group->users)) { ?>
			<h3>Users</h3>
			<ul>
				<?php foreach($group->users as $user) { ?>
					<li><?php echo $user->screen_name; ?>
						<?php if($admin) { ?>
							<a href="<?php echo site_url('groups/remove_user/'.$group->id.'/'.$user->user_id); ?>" class='btn btn-small'>x</a>
						<?php } ?>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>

		<?php if(!empty($group->goods)) { ?>
			<h3>Goods</h3>
			<ul>
				<?php foreach($group->goods as $good) { ?>
					<li>
						<?php echo $good->title; ?>
						<a href="<?php echo site_url('groups/remove_good/'.$group->id.'/'.$good->good_id); ?>" class='btn btn-small'>x</a>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>



</div>


<div class='span4'>

<?php if($in_group) { ?>
	<h1>Add your Gifts and Needs!</h1>
		<?php if(!empty($goods)) { ?>
			<ul>
				<?php foreach($goods as $my_good) { ?>
					<li>
						<?php echo $my_good->title; ?>
						<a href="<?php echo site_url('groups/add_good/'.$group->id.'/'.$my_good->id); ?>">Add</a>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
<?php } else { ?>
	<?php if($group->admission == 'open') { ?>
		<a href="<?php echo site_url('groups/join_group/'.$group->id); ?>" class='btn btn-success'>Join Group</a>
	<?php } else { ?>
		<a href="<?php echo site_url('groups/join_group/'.$group->id); ?>" class='btn btn-success'>Request to Join Group</a>
	<?php } ?>
<?php } ?>
</div>

</div>

<div class='row'>
<?php if($can_invite) { ?>
	<?php echo $invite_form; ?>
<?php } ?>

</div>


