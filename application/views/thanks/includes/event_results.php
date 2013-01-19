<!-- thank event result row -->

	<?php foreach($results as $val) { ?>
		<li class='result_row event_row'>
			<div class='row-fluid event_header'>
				
				<div class='span2'>
					<a class="result_image" href='<?php echo site_url("people/".$val->thank->recipient_id); ?>'>
						 <img src ="<?php echo $val->thank->recipient_default_photo->thumb_url; ?>">
					</a>
				</div>
				<div class='span8 header_text'>
					<span class='event_summary'>
						<?php echo $val->thank->summary;?>
					</span>                  
					<div class='event_body'>
						<span class='event_data'><?php echo $val->thank->thanker_screen_name;?> wrote: </span>
						<span class='user_copy'><?php echo $val->thank->body;?> </span>
					</div>
				</div>
				<div class='span1 minidata'>
					<span>
						<?php echo user_date($val->event_created,"n/j/o"); ?>
					</span>
				</div>
			</div>
		</li>
<?php } ?>
