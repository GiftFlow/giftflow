<?php if(!empty($results)) { ?>

<?php if(!$row) { ?>
	<ul class='inbox results_list'>
<?php } ?>

	<?php foreach($results as $val) { ?>
		<li class='thank_row <?php echo $val->status; ?>'>
			<div class='row-fluid'>	
				<div class='span1'>	
					<img src="<?php echo base_url()."assets/images/status_icons/".$val->status.".png";?>" title="<?php echo ucfirst($val->status);?>" alt="<?php echo ucfirst($val->status);?>" class="left status_icon" />
				</div>
				<div class='span2'>
					<a href="#" class="user_image medium left">
						<img src="<?php echo $val->default_photo->thumb_url; ?>" alt="<?php echo $val->screen_name;?>" />
					</a>
				</div>
				<div class='span9 metadata'>	
						<a href="<?php echo site_url('you/view_thankyou/'.$val->id);?>" class="title">
							Thank you from <?php echo $val->screen_name; ?>
						</a>
						<span class="summary">
							For: " <?php echo $val->gift_title; ?>"
						</span>
					
					<span class="updated">
						<?php echo user_date($val->updated,"n/j/o");?>
					</span>
				</div>
			</div>
		</li>
	<?php } ?>

<?php if(!$row) { ?>
</ul>
<?php } ?>

<?php } ?>
