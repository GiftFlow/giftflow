
<?php if(!empty($results)) { ?>

	<?php foreach($results as $G) { ?>
		<div class='brick'>
			<div class='brick_top'>
					<a class="title <?php if($G->type == 'need') { echo 'need'; } ?>" href="<?php echo site_url($G->type.'s/'.$G->id);?>">
							<?php echo $G->title; ?>
					</a>
						<!-- Image -->
						<?php if(!isset($G->default_photo->thumb_url)) { ?>	
								<a href='#' class="result_sprite <?php echo $G->default_photo->thumb_class;?>">
								</a>
						<?php } else { ?>
							<a href="<?php echo site_url('gifts/'.$G->id);?>" class="result_image" title="<?php echo $G->title;?>">
							<img src="<?php echo $G->default_photo->thumb_url; ?>"/>
							</a>
						<?php }?>
				
					<!-- Author -->
					<a class='author' href='<?php echo site_url('people/'.$G->user->id);?>'>
						<?php echo $G->user->screen_name;?>
					</a>
			</div>
			<div class='brick_bottom '>
				<!-- Location -->
				<span class='metadata location'>
					<?php echo $G->location->city; ?> 
				</span>	
				<?php if(!in_array('created', $include)) { ?>
					<span class='metadata location'>
					<?php echo '('.round($G->location->distance).' miles away)'; ?>
					</span>
				<?php } ?>
				
				<!-- Date Created -->
				<span class='metadata created'>
					<?php echo user_date($G->created,"F jS Y"); ?>
				</span>
			</div>
						
		</div>
	<?php } ?>
<?php } ?>

