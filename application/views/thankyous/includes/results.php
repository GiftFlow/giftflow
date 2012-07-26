<ul class='results_list'>
<?php foreach($results as $Y) 
	{ ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>
				<!-- Metadata -->
				<div class='result_meta'>
					<!-- Title --> 
					<span class="title small"><?php echo $Y->summary; ?></span>
						<a class='result_image' ref='<?php echo site_url('people/'.$Y->thanker_id); ?>'>
							<img src="<?php echo $Y->default_photo->thumb_url;?>"/>
						</a>
					<p class='metadata'>	
						<?php echo user_date($Y->summary, "F jS Y"); ?>
					</p>
					<div id='full_review'>
						<?php echo $Y->body; ?>
					</div>
				</div> <!-- result_meta  -->
			</li>
	<?php } ?>
		
</ul>
<!-- eof Results List -->
	
