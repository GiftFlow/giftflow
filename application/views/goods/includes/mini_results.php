<?php if(!empty($results)) { ?>
	<ul class='results_list mini'>
	
	<?php foreach($results as $G){ ?>
		<?php if($G->status == 'active') { ?>
			<!-- Result Row -->
			<li>
						
			<div class='row-fluid'>			
			<div class='span2 result_image'>
					<!-- Image -->
						<?php if(!isset($G->default_photo->thumb_url)) { ?>	
							<a href="<?php echo site_url('gifts/'.$G->id);?>" class="result_image <?php if(!$mini) { echo $G->default_photo->thumb_class; } else { echo $G->default_photo->mini_class; } ?>">
							</a>
						<?php } else { ?>
							<a href="<?php echo site_url('gifts/'.$G->id);?>" class="result_image" title="<?php echo $G->title;?>">
							<img src="<?php echo $G->default_photo->thumb_url; ?>"/>
							</a>
						<?php }?>
					
				<!-- Metadata -->
				</div>
				<div class='span8 metadata'>
					<a class="title proper_title <?php if($G->type == 'need') { echo 'need'; } ?>" href="<?php echo site_url($G->type.'s/'.$G->id);?>">
						<?php echo$G->title; ?>
					</a>
					<span class='minidata'>
						<a href="<?php echo site_url('people/'.$G->user->id);?>"><?php echo $G->user->screen_name; ?></a> 
					</span>
					<span class='minidata result_date'>
						posted <?php echo user_date($G->created, 'n/j/o'); ?>
					</span>
				</div>
			</li>
			<!-- eof Result Row -->
		<?php } ?>
	<?php } ?>
	</ul>
<?php } ?>
