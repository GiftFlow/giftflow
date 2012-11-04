<?php if(!empty($results)) { ?>
	<ul id="goods_includes_results" class='results_list goods mini'>
	
	<?php foreach($results as $G){ ?>
		<?php if($G->status == 'active') { ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>
						
			<div class='row-fluid'>			
			<div class='span2'>
					<?php if(!in_array("no_pic",$include)){ ?>
					<!-- Image -->
					<a href="<?php echo site_url('gifts/'.$G->id);?>" class="result_image" title="<?php echo $G->title;?>">
						<?php if(!isset($G->default_photo->thumb_url)) { ?>	
							<a class="<?php if(!$mini) { echo $G->default_photo->thumb_class; } else { echo $G->default_photo->mini_class; } ?>">
							</a>
						<?php } else { ?>
							<img src="<?php echo $G->default_photo->thumb_url; ?>"/>
						<?php }?>
					</a>
					<?php } ?>
					
				<!-- Metadata -->
				</div>
				<div class='span8 result_meta clearfix'>
					
							<!-- Title -->
							
							<a class="title sideBarTitle <?php if($G->type == 'need') { echo 'need'; } ?>" href="<?php echo site_url($G->type.'s/'.$G->id);?>">
								<span class= "title">
									<?php echo substr($G->title, 0, 30); ?>
								</span>
							</a>
						<span class='metadata'>posted by <?php echo $G->user->screen_name; ?></span>
				</div>
			</li>
			<!-- eof Result Row -->
		<?php } ?>
	<?php } ?>
	</ul>
<?php } ?>
