<?php if(!empty($results)) { ?>

	<?php if(!$row) { ?>
		<ul  class='results_list'>
	<?php } ?>
	
	<?php foreach($results as $G){ ?>
			<!-- Result Row -->
			<li class='clearfix'>
				<div class='row-fluid'>			
					<div class='span2'>
							<!-- Image -->
								<?php if(!isset($G->default_photo->thumb_url)) { ?>	
									<a href='#' class="result_sprite <?php echo $G->default_photo->thumb_class;?>">
									</a>
							<?php } else { ?>
								<a href="<?php echo site_url($G->type's/'.$G->id);?>" class="result_image" title="<?php echo $G->title;?>">
								<img src="<?php echo $G->default_photo->thumb_url; ?>"/>
								</a>
							<?php }?>
						
					</div>
					<div class='span6 result_text'>
						
						<a class="title <?php if($G->type == 'need') { echo 'need'; } ?>" href="<?php echo site_url($G->type.'s/'.$G->id);?>">
								<?php echo $G->title; ?>
						</a>

					<?php if(in_array("author", $include) && !empty($G->user)){ ?>
					
						<!-- Author -->
						<span class='metadata author'>
							<em>from</em> 
							<a href='<?php echo site_url('people/'.$G->user->id);?>'>
								<?php echo $G->user->screen_name;?>
							</a>
						</span>
					
					<?php } ?>
					<?php if(in_array("location", $include)) { ?>
						
						<!-- Location -->
						<span class='metadata location'>
							<em>in</em> <?php echo $G->location->address; ?> <?php if(!in_array('created', $include)) { echo '('.round($G->location->distance).' miles away)'; } ?>
						</span>
					
					<?php } ?>
					<?php if(in_array("created", $include)){ ?>
						
						<!-- Date Created -->
						<span class='metadata created'>
							<em>posted</em> <?php echo user_date($G->created,"F jS Y"); ?>
						</span>
						
					<?php } ?>
					</div>
					<?php if(!empty($G->description)) { ?>
						<div class='span4'>
							<span class='metadata row_description'>
								<b>Description:</b> <?php echo substr($G->description, 0, 150)."..."; ?>
							</span>
						</div>
					<?php } ?>
				</div>
			</li>
			<!-- eof Result Row -->
		<?php } ?>

	<?php if(!$row) { ?>
		</ul>
	<?php } ?>

<?php } ?>
