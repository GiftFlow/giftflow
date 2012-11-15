<ul class='results_list'>
<?php foreach($results as $R) 
	{ ?>
	<!-- Result Row -->
	<li class='result_row clearfix'>
		<!-- Metadata -->
		<div class='row-fluid'>
		<!-- Title --> 	
			<div class='span12'>
			<span class='metadata'><?php echo user_date($R->created, 'n/j/o'); ?></span>
			<span class="title small"><?php echo ($R->language->overview_summary); ?></span>
			</div>
		</div>
	<?php foreach($R->reviews as $rev) { ?>
		<div class='row-fluid'>
			<div class='span4'>
				<a class='result_image thankimg' href="<?php echo site_url('people/'.$rev->reviewer_id); ?>">
					<img src="<?php echo $rev->reviewer->default_photo->thumb_url;?>"/>
				</a>
			</div>
			<div class='span8'>
				<p class = 'spaced metadata'>
					 <?php echo $rev->reviewer->screen_name.' wrote: ' ?>
				</p>

				<div id='full_review'>
					<?php echo $rev->body; ?>
				</div>
				<p>Rating: <span class="<?php echo $rev->rating;?>"><?php echo $rev->rating; ?></span></p>
			</div>
		</div>
	<?php } ?>
</li>
<?php } ?>

</ul>
<!-- eof Results List -->

