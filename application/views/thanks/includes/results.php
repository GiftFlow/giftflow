<ul class='results_list'>
<?php foreach($results as $Y) { ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>
			<div class='row'>
				<div class='span4'>
					<a class='result_image thankimg' href="<?php echo site_url('people/'.$Y->thanker_id); ?>">
						<img src="<?php echo $Y->default_photo->thumb_url;?>"/>
					</a>
				</div>

				<!-- Metadata -->
				<div class='span8 thankdata'>
					<span class='metadata'>	
						<?php echo user_date($Y->created, "n/j/o"); ?>
					</span>
					<!-- Title --> 
					<p class="title small"><?php echo $Y->summary; ?></p>
			</div>
			<div class='row-fluid'>
				<div class='span12 entry_body'>
					<span class='metadata'>They wrote: </span>
					<span id='full_review'>
						<?php echo $Y->body; ?>
					</span>
				</div> <!-- result_meta  -->
			</div>
			</li>
	<?php } ?>
		
</ul>
<!-- eof Results List -->
	
