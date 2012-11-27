<?php if(!empty($results)) { ?>
	<ul class='results_list mini'>
	
	<?php foreach($results as $G){ ?>
		<?php if($G->status == 'active') { ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>
						
			<div class='row-fluid'>			
			<div class='span2'>
					<!-- Image -->
					<a href="<?php echo site_url('gifts/'.$G->id);?>" class="result_image" title="<?php echo $G->title;?>">
						<?php if(!isset($G->default_photo->thumb_url)) { ?>	
							<a class="<?php echo $G->default_photo->mini_class; } ?>">
							</a>
						<?php } else { ?>
							<img src="<?php echo $G->default_photo->thumb_url; ?>"/>
						<?php }?>
					</a>
					<?php } ?>
					
				<!-- Metadata -->
				</div>
				<div class='span10'>
						<a class="title <?php if($G->type == 'need') { echo 'need'; } ?>" href="<?php echo site_url($G->type.'s/'.$G->id);?>">
								<?php echo$G->title; ?>
						</a>
					<span class='metadata'>posted by <?php echo $G->user->screen_name; ?> on <?php echo user_date($G->created, 'n/j/o'); ?> near <?php echo $G->location->city.', '.$G->location->state; ?></span>
				
				</div>
			</li>
			<!-- eof Result Row -->
		<?php } ?>
	<?php } ?>
	</ul>
<?php } ?>
