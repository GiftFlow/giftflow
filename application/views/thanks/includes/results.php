<ul class='results_list'>
<?php foreach($results as $Y) { ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>
			<div class='row'>
				<div class='span4'>
					<a class='user_image' href="<?php echo site_url('people/'.$Y->thanker_id); ?>">
						<img src="<?php echo $Y->thanker_default_photo->thumb_url;?>"/>
					</a>
				</div>

				<!-- Metadata -->
				<div class='span8'>
					<span class='metadata'>	
						<?php echo user_date($Y->created, "n/j/o"); ?>
					</span>
					<!-- Title --> 
					<p><?php echo $Y->summary; ?></p>
			</div>
			<div class='row-fluid'>
				<div class='span12 entry_body'>
				<span class='metadata'><?php echo $Y->thanker_screen_name; ?> wrote: </span>
					<span>
						<?php echo $Y->body; ?>
					</span>
				</div> <!-- result_meta  -->
			</div>
			</li>
	<?php } ?>
		
</ul>
<!-- eof Results List -->
	
