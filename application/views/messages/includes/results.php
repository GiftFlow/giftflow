<ul class='results_list'>
<?php foreach($results as $M) 
	{ ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>

				<a class='result_image thankimg' href='<?php echo site_url('people/'.$Y->thanker_id); ?>'>
					<img src="<?php echo $M->user->default_photo->thumb_url;?>"/>
				</a>

				<!-- Metadata -->
				<div class='result_meta clearfix thankdata'>
					<span class='metadata'>	
						<php echo user_date($M->created, "F jS Y"); ?>
					</span>
					<!-- Title --> 
					<span class="title small">Message from <?php echo $M->user->screen_name; ?></span>
					<span id='full_review'>
						<?php echo $M->body; ?>
					</span>
				</div> <!-- result_meta  -->
			</li>
	<?php } ?>
		
</ul>
<!-- eof Results List -->
	
