<?php if(!empty($results)) { ?>

	<?php if(!$row) { ?>
		<!-- Results List  PLEASE LEAVE THE ID HERE IT IS FOR ajax::giftflow on VIEWS/YOU/INDEX -->
		<ul id="goods_includes_results" class='results_list goods <?php if($mini){ echo "mini"; } elseif($grid){ echo "grid clearfix"; } ?>'>
	<?php } ?>
	
	<?php foreach($results as $G){ ?>
		<?php if($G->status == 'active') { ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>
			
			<?php if(!in_array("offer_links",$include)){ ?>
				<?php if(!in_array("no_pic",$include)){ ?>
				<!-- Image -->
				<a href="<?php echo site_url('gifts/'.$G->id);?>" class="result_image" title="<?php echo $G->title;?>">
					<img src="<?php if(isset($G->photo->thumb_url)) { echo $G->photo->thumb_url; } else { echo $G->default_photo->url;} ?>" />
				</a>
				<?php } ?>
				
				<!-- Metadata -->
				<div class='result_meta clearfix'>
				
					<!-- Title -->
					
					<a class="title" href="<?php echo site_url($G->type.'s/'.$G->id);?>">
						<span class= "title_<?php echo $G->type; ?>">
						<?php echo $G->title;?>
						</span>
					</a>
			<?php }?>
			<?php if(in_array("offer_links",$include)){ ?>
				
				<!--Offer button -->
				<input type="radio" name="good_id" class="offer_link" value="<?php echo $G->id;?>" />
					
				<!-- Image -->
				<a href="<?php echo site_url('gifts/'.$G->id);?>" class="result_image">
					<img src="<?php if(isset($G->photo->thumb_url)) { echo $G->photo->thumb_url; } else { echo $G->default_photo->url;}?>" />
				</a>
				
				<!-- Metadata -->
				<div class='result_meta clearfix'>
				
					<!-- Title -->
					<a class="title" href="#">
						<span class='title <?php echo $G->type; ?>'> 
							<?php echo $G->title;?>
						</span>
					</a>
					
			<?php } ?>
					
					<?php if(in_array("requests",$include)){ ?>
					
						<!-- # Times Requested -->
						<span class='metadata requests'>
							<em>requested</em> <?php echo count($G->requests->all);?> times
						</span>
						
					<?php } ?>
					<?php if(in_array("author", $include) && !empty($G->user)){ ?>
					
						<!-- Author -->
						<span class='metadata author'>
							<em>from</em> 
							<a href='<?php echo site_url('people/'.$G->user->id);?>'>
								<?php echo $G->user->screen_name;?>
							</a>
						</span>
					
					<?php } ?>
					<?php if( in_array("location", $include) && !empty($G->location->city) && !empty($G->location->state) ) { ?>
						
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
			</li>
			<!-- eof Result Row -->
		<?php } ?>
	<?php } ?>

	<?php if(!$row) { ?>
		</ul>
	<?php } ?>

<?php } ?>
