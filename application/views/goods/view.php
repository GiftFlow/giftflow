<?php
/*
* The Gift & Need Template 
*/
?>

<?php 
	if(!$active) { echo 'DISABLED'; }
?>
	
	
	
<!-- Main Module -->
<div class="gift_module" id="view_gift">
	<div class="top"></div>
	<div class="middle">
		<!-- Main Image-->
		<img class="<?php echo $G->default_photo->class; ?>" src="<?php echo $G->default_photo->url; ?>"/>
		<!-- Title, Description, Tags and More -->
		<div class='right'>
		
			<h1>
				<?php echo $G->title; ?>
			</h1>

			<!-- AddThis Button BEGIN -->
			<div class="clearfix shareSet addthis_toolbox addthis_default_style addthis_32x32_style">
				<a class="addthis_button_preferred_1"></a>
				<a class="addthis_button_preferred_2"></a>
				<a class="addthis_button_preferred_3"></a>
				<a class="addthis_button_preferred_4"></a>
				<a class="addthis_button_compact"></a>
				<a class="addthis_counter addthis_bubble_style"></a>
			</div>
		<!-- AddThis Button END -->
			
			<?php if(!empty($G->location->city)&&!empty($G->location->state)) { ?>
			<!-- Location -->
			<p id='location'>
				<span class='key'>Located in</span>
				<span class='value'>
					<?php echo $G->location->city.", ".$G->location->state; ?>
				</span>
			</p>
			<?php } ?>
			
			<!-- Date Posted -->
			<p id='date'>
				<span class='key'>Posted on</span>
				<span class='value'>
					<?php echo user_date($G->created,"F jS Y"); ?>
				</span>
			</p>
			
			<?php if(!empty($G->description)) { ?>
			<!-- Description -->
			<p id='description'>
				<span class='key'>Description</span>
				<span class='value'>
					<?php echo $G->description; ?>
				</span>
			</p>
			<?php } ?>
			
			<?php if(!empty($G->tags[0]) && count($G->tags)>0) { ?>
			<!-- Tags -->
			<p id='tags'>
				<span class='key'>Tags</span>
				<span class='value'>
					<?php foreach($G->tags as $tag) { ?>
						<a href="<?php echo UI::tag_url($tag,$G->type);?>" class='btn tag'>
							<i class="icon-tag"></i>
							<?php echo $tag; ?>
						</a>
					<?php } ?>
				</span>
			</p>
			<?php }?>
			
		<?php if(!$is_owner){ ?>
			<?php if($is_gift){ ?>
			
				<!-- Request Gift Button -->
				<a href="#" id='request_gift_button' class='open'></a>
				
			<?php } else { ?>
				
				<!-- Offer Gift Button -->
				<a href="#" id='offer_gift_button' class='open'></a>
		
			<?php }?>
		<?php } ?>
		</div>	

		<?php if(!empty($G->photos)) { ?>
		<div id='goods_photos'  class='thumb_grid'>
			<p class='nicebigtext'>More Photos</p>
			<p>
			<?php foreach($G->photos as $val) { ?>
			<a class='photoMod' style='text-decoration:none;'id="<?php echo site_url($val->url); ?>" href='#photoModal' role='button' data-toggle='modal'>
					<img src='<?php echo site_url($val->thumb_url);?>' />
				</a>
			<?php } ?>
			</p>
			<!--<button class='btn' href='#photoModal' role='button' data-toggle='modal'>BUTTON</button>-->
			<div class='modal hide' id='photoModal' tabindex='-1' role='dialog' aria-labelledby='photoModalLabel' aria-hidden='true'>
				<div class='modal-header'>
					<h3 id='photoModalLabel'>Photo of <?php echo $G->title; ?></h3>
				</div>
				<div class='modal-body'>
					<img src='' id = 'modImage'/>
				</div>
				<div class='modal-footer'>
					<button class='btn' data-dismiss='modal' aria-hidden='true'>Close</button>
				</div>
			</div>
		</div>
		<?php } ?>
			
		<div style='clear: both;'></div>
	</div>
	
	<div class='bottom' style='display: none;'></div>
	
	<?php if(!$is_owner)
		{ 
			if($is_gift) 
			{?>
				<!-- Request Gift Form -->
				<div id="take_request_form" class="request_form ui-widget" style="display: none;">
					<h2>Request Gift</h2>
					<form method="post" action="<?php echo site_url('goods/view'); ?>">
						<p>
							<!-- @todo create message template similar to the new couchsurfing request form -->
							<label>Send <?php echo $G->user->first_name; ?> a note:</label><br />
							<textarea name='note'></textarea>
						</p>
						<input type="hidden" name="method" value="demand">
						<input type="hidden" name="type" value="take">
						<input type="hidden" name="good_id" value="<?php echo $G->id;?>" />
						<input type="hidden" name="decider_id" value="<?php echo $G->user->id; ?>" />
						<input type="submit" class="btn btn-primary" value="Request This Gift" />
					</form>
				</div>
			<?php } 
			elseif(!$is_gift) 
			{?>
			<!-- Offer to Give Form -->
			<div id="give_request_form" class="request_form ui-widget" style="display: none;">
				<h2>Offer to Help</h2>
				<form method="post" action="<?php echo site_url('goods/view'); ?>">
					<p>
						<!-- @todo create message template similar to the new couchsurfing request form -->
						<label>Enter a message below</label><br />
						<textarea name='note' rows='5'></textarea>
					</p>
					<input type="hidden" name="method" value="demand">
					<input type="hidden" name="type" value="give">
					<input type="hidden" name="good_id" value="<?php echo $G->id;?>" />
					<input type="hidden" name="decider_id" value="<?php echo $G->user->id; ?>" />
					<input type="submit" class="btn btn-primary" value="Send Offer" />
				</form>
			</div>
			<?php }?>
		<?php }?>
	
	
	<div class='alt_bottom'>
	
		<div class="css_right" style="margin: 5px 5px 0 0;">
		<?php if($is_owner){ ?>
		
			<!-- Edit Gift Buttons -->
			<a href="<?php echo site_url($G->type."s/".$G->id."/edit");?>" id="toolbar_edit_gift" class="btn">
				<i class="icon-pencil"></i>
				Edit Info
			</a>
			
			<a href="<?php echo site_url($G->type."s/".$G->id."/photos");?>" id="toolbar_edit_gift" class="btn">
				<i class="icon-camera"></i>
				Add Photos
			</a>
		
		<?php } ?>
		</div>
		
			
	
	</div>
	
</div>

<?php if(!$is_owner){ ?>
	<!-- More About This Person Sidebar-->
	<div class="sidebar" id="giver">		
		<div class="top">
			<h2>More About This Person</h2>
		</div>
		<div class="center">
		
			<!-- Image -->
			<img src="<?php echo $G->user->default_photo->thumb_url; ?>" />
			
			<!-- Name -->
			<a href="<?php echo site_url('people/'.$G->user->id);?>">
				<?php echo $G->user->screen_name; ?>
			</a>
			
			<!-- Location -->
			<span class="location">
				<?php echo $G->location->city.", ".$G->location->state;?>
			</span>
			
			<div style="clear: both;"></div>
		</div>
		<div class="bottom"></div>
	</div>
<?php } ?>


<!-- Gifts Sidebar -->
<?php if(!empty($gifts)) { ?>
	<div class="sidebar" id="giver">		
		<div class="top">
			<h2>
				Other Gifts
			</h2>
		</div>
		<div class="center">
				<?php echo UI_Results::goods(array(
					"results"=> $gifts,
					'mini' => TRUE,
					'border'=> FALSE,
					'sidebar' => TRUE
				)); ?>
		</div>
		<div class="bottom"></div>
	</div>
<?php }?>

<!-- Gifts Sidebar -->
<?php if(!empty($needs)) { ?>
	<div class="sidebar" id="giver">		
		<div class="top">
			<h2>
			Other Needs
			</h2>
		</div>
		<div class="center">
				<?php echo UI_Results::goods(array(
					"results"=> $needs,
					'mini' => TRUE,
					'border'=> FALSE,
					'sidebar'=>TRUE
				)); ?>
		</div>
		<div class="bottom"></div>
	</div>
<?php }?>

</div>

<script type='text/javascript'>	
$(function(){
		

$('#photoModal').modal({show:false});

$('.photoMod').click(function() {
	var imgUrl = $(this).attr('id');
	console.log(imgUrl);
	$('#modImage').attr('src',imgUrl);
});

	$("#request_gift_button.open").click(function(){
		$(this).slideUp();
		$(".gift_module .middle").addClass("shadow");
		$(".gift_module .bottom").addClass('shadow').slideDown();
		$("#take_request_form").delay(100).slideDown("slow");
		return false;
	});
	
	$("#offer_gift_button.open").click(function(){
		$(this).slideUp();
		$(".gift_module .middle").addClass("shadow");
		$(".gift_module .bottom").addClass('shadow').slideDown();
		$("#give_request_form").delay(100).slideDown("slow");
		return false;
	});
});
</script>
