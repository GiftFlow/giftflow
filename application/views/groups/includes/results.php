
<?php if(!empty($results)) { ?>

	<?php if(!$row) { ?>
		<ul  class='results_list'>
	<?php } ?>
	
	<?php foreach($results as $G){ ?>
			<!-- Result Row -->
			<li class='clearfix'>
				<div class='row-fluid'>			
					<div class='span12'>
						<h3><a href="<?php echo site_url('groups/view'.$G->id);?>">
							<?php echo $G->name; ?>
						</a></h3>
					</div>
				</div>
				<div class='row-fluid'>
					<div class='span12 result_text'>
						
					<span class='metadata'><?php echo $G->description; ?></span>

					<?php if(!empty($G->location_city)) { ?>
						<span class='metadata'>Location: <?php echo $G->location_city; ?> </span>
					<?php } ?>

					<span class='label'><?php echo $G->privacy;?></span>
					<span class='metadata'>Users <?php echo count($G->users); ?></span>
					<span class='metadata'>Gifts and Needs <?php echo count($G->goods); ?></span>
				</div>
			</li>			<!-- eof Result Row -->
	<?php } ?>

	<?php if(!$row) { ?>
		</ul>
	<?php } ?>

<?php } ?>
