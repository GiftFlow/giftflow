<div id="your_transactions" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>

		<?php if(!empty($transactions)) { ?>
			<ul class='transactions'>
				<?php 
					foreach($transactions as $val) 
					{
						if($val->demander->id == $logged_in_user_id)
							{
								$demander = TRUE;
								$other_user = $val->decider;
							} 
							else 
							{
								$demander = FALSE;
								$other_user = $val->demander;
							}
							//print_r($other_user);
							?>
						<li class="clearfix <?php if($val->unread) { echo "unread"; } ?>">
						
							<img src="<?php echo base_url()."assets/images/status_icons/".$val->status.".png";?>" title="<?php echo ucfirst($val->status);?>" alt="<?php echo ucfirst($val->status);?>" class="left status_icon" />
							
							<a href="#" class="user_image medium left">
								<img src="<?php echo $other_user->default_photo->thumb_url;?>" alt="<?php echo $other_user->screen_name;?>" />
							</a>
							
							<div class="metadata left">
								<a href="<?php echo site_url('you/transactions/'.$val->id);?>" class="title">
									Request <?php echo $demander ? "to":"from"; echo " ".$other_user->screen_name;?>
								</a>
								<span class="summary">
								<?php echo strip_tags($demander ? $val->language->demander_summary : $val->language->decider_summary); ?>
								</span>
							</div>
							
							<!--<span class="status left">
								<?php echo $val->status; ?>
							</span>-->


							<span class="updated css_right">
								<?php echo user_date($val->updated,"n/j/o");?>
							</span>
					</li>
				<?php } ?>
				</ul>
		<?php } else { ?>
		
			<!-- Empty State -->
			<p>
				No transactions found.
			</p>
		<?php } ?>

	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$("img.status_icon").tipTip({ delay: 0, fadein: 0 });
});
</script>