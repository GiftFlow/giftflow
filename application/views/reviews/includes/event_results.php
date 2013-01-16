	<!-- Result Row -->
<?php foreach($results as $R) { ?>
	<?php $good = $R->transaction->demands[0]->good; ?>

	<li class='result_row event_row clearfix'>
		<!-- Metadata -->
		<div class='row-fluid event_header'>
		<!-- Title --> 	
			<div class='span2'>
				<!-- Image -->
				<?php if(!isset($good->default_photo->thumb_url)) { ?>	
					<a href='#' class="result_sprite <?php echo $good->default_photo->thumb_class;?>">
					</a>
				<?php } else { ?>
					<a href="<?php echo site_url($good->type.'s/'.$good->id);?>" class="result_image" title="<?php echo $good->title;?>">
					<img src="<?php echo $good->default_photo->thumb_url; ?>"/>
					</a>
				<?php } ?>
			</div>
			<div class='span8 header_text'>
				<span class='event_summary'><?php echo ($R->transaction->language->overview_summary); ?></span>
			</div>
			<div class='span1'>
				<span class='minidata'><?php echo user_date($R->event_created, 'n/j/o'); ?></span>
			</div>
		</div>
		<div class='row-fluid'>
			<div class='span2'></div>
			<div class='span8'>
			<?php foreach($R->transaction->reviews as $rev) { ?>
				<div class='row-fluid event_review'>
					<div class='span12 event_body'>
						<span class='event_data'>
							<a href="<?php echo site_url('people/'.$rev->reviewer->id); ?>">
							 <?php echo $rev->reviewer->screen_name; ?></a> wrote: 
						</span>
						<span class='user_copy'>
							<?php echo $rev->body; ?>
						</span>
						<span class='event_data'>Rating: <?php echo $rev->rating; ?></span>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</li>
<?php } ?>


