<!-- View for event feed. This takes 3 different kinds of events - user_new, good_new and transaction completed -->


<?php if(!empty($results)) {?>

	<?php if(!$row) { ?>
		<!-- Results List -->
		<ul class='results_list events <?php if($mini) {echo "mini"; }?>'>
	<?php } ?>
		
	<?php foreach($results as $key=>$val) {?>
		  <li class='result_row event clearfix'>
			<!-- Result Row start -->
			


		<?php if($val->event_type_id == 4) {  ?>
		<!-- begin USER_NEW aka event_type_id 4 -->

				<!-- profile Image -->
				<a class='result_image' href='<?php echo site_url("people/".$val->user->id); ?>'>
					<img src='<?php if(isset($val->user->photo->url)) 
						{ echo $val->user->photo->thumb_url; } 
						else { echo $val->user->default_photo->thumb_url; }?>'/>
				</a>
				
				<!-- Metadata -->
				<div class='result_meta clearfix'>
				
				  <!-- Screen Name -->
				  <a class='title' href='<?php echo site_url('people/'.$val->user->id); ?>'>
						<?php echo $val->user->screen_name;?>
				  </a>

				 <!-- Location --> 
				  <?php if(!empty($val->user->location->city)){ ?>
					<span class='metadata location'>
					  <?php echo $val->user->location->city;?>
					</span>
				  <?php } ?>

					<!-- Created -->
					<span class="metadata created">
					  <em>joined
						<?php echo user_date($val->event_created,"F jS Y");?>
					  </em>
					</span>
				</div>
			<!-- close user new -->
		   <?php } else if($val->event_type_id == 2) { ?>
			<!--Open transaction completed -->
				<div class='row-fluid'>
				<?php if($mini) { ?>
					<div class='span1'>
					<!-- Image -->
						<a href="<?php echo site_url('gifts/'.$val->transaction->demands[0]->good->id);?>" class="result_image" title="<?php echo $val->transaction->demands[0]->good->title;?>">
							<?php if(!isset($val->transaction->demands[0]->good->default_photo->thumb_url)) { ?>	
								<a class="<?php if(!$mini) { echo $val->transaction->demands[0]->good->default_photo->thumb_class; } else { echo $val->transaction->demands[0]->good->default_photo->mini_class; } ?>">
								</a>
							<?php } else { ?>
								<img src="<?php echo $val->transaction->demands[0]->good->default_photo->thumb_url; ?>"/>
							<?php }?>
						</a>
					</div>
					<div class='span8 result_meta clearfix'>
					  <span class='title'>
						<?php echo $val->transaction->language->overview_summary; ?>
					  </span> 
					</div>
					<div class='span3 result_meta event_date'>
						<span class= 'minidata'>
							<?php echo user_date($val->event_created, "n/j/o"); ?>
						</span>
					</div>

				<?php } else { ?>
				<?php foreach($val->transaction->users as $user) { ?>
					<div class='span2'>
						<a class='result_image' href = '<?php echo site_url("people/".$user->id);?>'>
								<img src='<?php echo $user->default_photo->thumb_url; ?>'>
						</a>
					</div>
					  
							<?php foreach($val->review['reviews'] as $review) { ?>
							   <span class='event_review'>
								  <?php if($review->reviewer_id == $user->id) {
									echo $review->user_reviewer_name." gave a ";
									echo "<span class='".$review->rating."'>";
									echo $review->rating;
									echo "</span> rating. They wrote: \"";
									echo substr($review->body, 0,50)."...\"";?>
									<a href='<?php echo site_url("people/".$review->reviewer_id); ?>'>read more</a>
								  <?php } ?>
								</span>
							 <?php } ?>
						<?php } ?>
					</span>
				<?php } ?>
			</div> <!-- close row -->
					
	<!-- close trans_completed -->

	<?php } else if($val->event_type_id == 8) { ?>

		<!-- Open good_new -->		
			<a class="result_image" href='<?php echo site_url($val->good->type.'s/'.$val->good->id); ?>'>
				 <img src ="<?php if(isset($val->good->photo->thumb_url)) { echo $val->good->photo->thumb_url; } else { echo $val->good->default_photo->url; } ?>" />
			</a>
				<span class = 'title'>
					<a href = '<?php echo site_url("people/".$val->good->user->id); ?>'>
						<?php echo $val->good->user->screen_name; ?>
					</a>
					<?php switch ($val->good->type) {
							case 'need':
							  echo " needs ";
							  break;
							case 'gift':
							  echo " offered ";
							  break;
					} ?>
					<a href='<?php echo site_url("goods/".$val->good->id); ?>'>
						<?php echo $val->good->title; ?>
					</a>
				</span>                  
				<span class = 'metadata created'>
					<em>posted</em>
					<?php echo user_date($val->event_created,"F jS Y"); ?>
				</span>
			<?php if(!$mini) { ?>
				<div class='result_meta clearfix'> 
					<span class='event_text'>
					  <?php echo substr($val->good->description, 0, 120)."..."; ?>
					</span>
				</div>
			<?php } ?>
    <!-- close good_new -->
	<?php } else if($val->event_type_id == 17) { ?>
	<!-- open thankyou -->
		<div class='row-fluid'>
			<div class='span1'>
				<a class="result_image" href='<?php echo site_url("people/".$val->thank->thanker_id); ?>'>
					 <img src ="<?php echo $val->thank->default_photo->thumb_url; ?>">
				</a>
			</div>
			<div class='span8 result_meta clearfix'>
				<span class = 'title'>
					<?php echo $val->thank->summary;?>
				</span>                  
			</div>
			<div class='span3 event_date'>
				<span class='minidata'>
					<?php echo user_date($val->event_created,"n/j/o"); ?>
				</span>
			</div>
		</div>
	<?php } ?>


	<!-- eof Result Row -->
	</li>
	<?php } ?>
	<!-- close thankyou -->

	<?php } ?>
	<?php if(!$row) { ?>
		</ul>
		<!-- eof Results List -->
	<?php } ?>
	
