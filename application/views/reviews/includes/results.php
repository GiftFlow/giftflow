<ul class='results_list'>
<?php foreach($results as $R) 
	{ ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>
				<!-- Metadata -->
				<div class='result_meta'>
				<!-- Title --> 
				<span class="title small"><?php echo ($R->language->overview_summary); ?></span>
					<?php foreach($R->users as $use) { ?>
						<?php foreach($R->reviews as $rev) { ?>
							<?php if($use->id == $rev->reviewer_id) { ?>
								<p class = 'metadata' href='<?php echo site_url('people/'.$use->id); ?>'>
									 <?php echo $use->screen_name.' wrote: ' ?>
								</p>
								<div id='full_review'>
									<?php echo $rev->body; ?>
								</div>
								<p>Rating: <span class="<?php echo $rev->rating;?>"><?php echo $rev->rating; ?></span></p>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					
				</div> <!-- result_meta  -->
			</li>
	<?php } ?>
		
</ul>
<!-- eof Results List -->
	
