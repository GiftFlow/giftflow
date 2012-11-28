<?php if(!$row) { ?>
	<ul class='results_list'>
<?php } ?>
	<?php foreach($results as $val) { ?>
	<li>
		<div class='row-fluid'>
			<div class='span1 result_image'>
				<a href="<?php echo site_url('gifts/'.$val->transaction->demands[0]->good->id);?>" class="result_image" title="<?php echo $val->transaction->demands[0]->good->title;?>">
					<?php if(!isset($val->transaction->demands[0]->good->default_photo->thumb_url)) { ?>	
						<a class="<?php if(!$mini) { echo $val->transaction->demands[0]->good->default_photo->thumb_class; } else { echo $val->transaction->demands[0]->good->default_photo->mini_class; } ?>">
						</a>
					<?php } else { ?>
						<img src="<?php echo $val->transaction->demands[0]->good->default_photo->thumb_url; ?>"/>
					<?php }?>
				</a>
			</div>
			<div class='span8 metadata'>
				<span class='title'>
					<?php echo $val->transaction->language->overview_summary; ?>
				</span>
			</div>
			<div class='span2 metadata result_date'>
				<span>
					<?php echo user_date($val->event_created, "n/j/o"); ?>
				</span>
			</div>
		</div>
	</li>
<?php } ?>

<?php if(!$row) { ?>
	</ul>
<?php }?>
