<?php if(!$row) { ?>
	<ul class='results_list'>
<?php } ?>
	<?php foreach($results as $val) { ?>
		<li>
			<div class='row-fluid'>
				
				<div class='span1'>
					<a class="result_image" href='<?php echo site_url("people/".$val->thank->thanker_id); ?>'>
						 <img src ="<?php echo $val->thank->default_photo->thumb_url; ?>">
					</a>
				</div>
				<div class='span8 metadata'>
					<span class = 'title'>
						<?php echo $val->thank->summary;?>
					</span>                  
				</div>
				<div class='span2 metadata result_date'>
					<span>
						<?php echo user_date($val->event_created,"n/j/o"); ?>
					</span>
				</div>
			</div>
		</li>
	<?php } ?>

<?php if(!$row) { ?>
</ul>
<?php } ?>
