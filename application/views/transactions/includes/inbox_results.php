<?php if(!empty($results)) { ?>

<?php if(!$row) { ?>
	<ul class='inbox results_list'>
<?php } ?>

	<?php foreach($results as $val) { ?>
	
	<li class="transaction_row <?php if($val->unread) { echo 'unread'; } ?> <?php echo $val->status ?>">
			<div class='row-fluid'>
				<div class='span2'>
					<a href="#" class="user_image medium left">
						<img src="<?php echo $val->other_user->default_photo->thumb_url;?>" alt="<?php echo $val->other_user->screen_name;?>" />
					</a>		
				</div>
				<div class='span9 metadata'>
					<a href="<?php echo site_url('you/view_transaction/'.$val->id);?>" class="title">
						Request <?php echo $val->is_demander ? "to":"from"; echo " ".$val->other_user->screen_name;?>
					</a>
					<span class="summary">
						<?php echo strip_tags($val->is_demander ? $val->language->demander_summary : $val->language->decider_summary); ?>
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

