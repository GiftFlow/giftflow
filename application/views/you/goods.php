<div id="your_transactions" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
		<a href="<?php echo site_url('you/add_good/?type='.$type);?>" id='add_good' class="btn btn-large btn-success">Add a <?php echo ucfirst($type); ?></a>
		<ul class ="transactions goods_list list_menu float_right">
			<?php if(!empty($goods)) { ?>
				<?php foreach($goods as $val) { ?>
					<?php 
						if($val->status !='disabled')
						{?>
							<li class="clearfix">
							
								<!-- Options Dropdown Menu -->
								<div class="btn-group css_right good_buttons">
									<a href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=http://giftflow.org/gifts/<?php echo $val->id; ?>" class='btn shareBtn' title='on Facebook'>Share</a>
									<a href="<?php echo site_url($val->type.'s/'.$val->id.'/edit'); ?>" class='btn'>Edit</a>
									<a href="<?php echo site_url($val->type.'s/'.$val->id.'/photos'); ?>" class='btn'>Add Photos</a>
									<a href="<?php echo site_url('gifts/'.$val->id.'/disable');?>" class='btn'>Delete</a>

								</div>
								<!-- eof Options Dropdown Menu -->
								
								<a href="#" class="result_image left">
									<img class='thumb_image' src="<?php echo $val->default_photo->thumb_url;?>" />		
								</a>
								
								<div class="metadata left">
									<a href="<?php echo site_url($val->type.'s/'.$val->id);?>" class="title">
									 <?php echo $val->title; ?>
									</a>
									<span class="summary">
									<?php echo substr($val->description, 0, 100); ?>
									</span>
								</div>
								
								<!--<span class="status left">
									<?php echo $val->status; ?>
								</span>-->
							<!--	
								<span class="updated css_right">
									<?php echo user_date($val->created,"n/j/o");?>
								</span>
							-->
						</li>
						
						
						

					<?php } ?>
				<?php } ?>
		<?php } else { ?>
		
			<!-- Empty State -->
			<li>
				No <?php echo $type.'s'; ?> found.
			</li>
		<?php } ?>
		</ul>
	</div>
	<!-- eof div.right_content -->
	
	<div class='add jqmWindow' id='add_good'></div>

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){

	$("img.status_icon").tipTip({ delay: 0, fadein: 0 });

	$('.shareBtn').tipTip({ delay: 0, fadein: 0 });
	
	$("#delete_gift").click(function(){
		return confirm('Are you sure you want to delete this gift? Doing so will cancel all transactions involving this gift');
	});

});
</script>
