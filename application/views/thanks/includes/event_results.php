<!-- thank event result row -->

	<?php foreach($results as $val) { ?>
		<li>
			<div class='row-fluid'>
				
				<div class='span2'>
					<a class="result_image" href='<?php echo site_url("people/".$val->thank->recipient_id); ?>'>
						 <img src ="<?php echo $val->thank->recipient_default_photo->thumb_url; ?>">
					</a>
				</div>
				<div class='span8 review_summary'>
					<span>
						<?php echo $val->thank->summary;?>
					</span>                  
				</div>
				<div class='span1 result_date'>
					<span>
						<?php echo user_date($val->event_created,"n/j/o"); ?>
					</span>
				</div>
			</div>
			<div class='row-fluid'>
				<div class='span2'></div>
				<div class='span10'>
				<div class='row-fluid'>
					<div class='span2'>		
						<a class="result_image" href='<?php echo site_url("people/".$val->thank->thanker_id); ?>'>
							 <img src ="<?php echo $val->thank->thanker_default_photo->thumb_url; ?>">
						</a>
					</div>
					<div class='span10'>
						<span class='minidata'><?php echo $val->thank->thanker_screen_name;?> wrote: </span>
						<span class='usercopy'><?php echo $val->thank->body;?> </span>
					</div>
				</div>
			</div>
		</div>
	</li>
<?php } ?>
