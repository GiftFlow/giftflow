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
		<img src="<?php if(isset($G->photo->thumb_url)) { echo $G->photo->thumb_url; } else { echo $G->default_photo->url; }?>" />		
		<!-- Title, Description, Tags and More -->
		<div class='right'>
		
			<h1>
				<?php echo $G->title; ?>
			</h1>
			
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
						<a href="<?php echo UI::tag_url($tag);?>" class='tag'>
							<?php echo $tag; ?>
						</a>
					<?php } ?>
				</span>
			</p>
			
			<?php } ?>
			
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
						<input type="submit" class="blue" value="Request This Gift" />
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
						<label>Enter a message below (optional)</label><br />
						<textarea name='note'></textarea>
					</p>
					<input type="hidden" name="method" value="demand">
					<input type="hidden" name="type" value="give">
					<input type="hidden" name="good_id" value="<?php echo $G->id;?>" />
					<input type="hidden" name="decider_id" value="<?php echo $G->user->id; ?>" />
					<input type="submit" class="blue" value="Offer to Give" />
				</form>
			</div>
			<?php }?>
		<?php }?>
	
	
	<div class='alt_bottom'>
	
		<?php if($is_owner){ ?>
		
			<!-- Edit Gift Buttons -->
			<a href="<?php echo site_url($G->type."s/".$G->id."/edit");?>" id="toolbar_edit_gift" class="gift_toolbar">
				<span class='ui-icon ui-icon-pencil left'></span>
				Edit Info
			</a>
			
			<a href="<?php echo site_url($G->type."s/".$G->id."/photos");?>" id="toolbar_edit_gift" class="gift_toolbar">
				<span class='ui-icon ui-icon-image left'></span>
				Add Photos!
			</a>
		
		<?php } ?>
		<?php if(!empty($photos)) { ?>
			<a  id="show_photos" class="gift_toolbar" >
				<span class='ui-icon ui-icon-image left'></span>
				See more photos
			</a>
		<?php } ?>
		
			
	
	</div>
	
</div>

<!-- Requests Sidebar-->
<?php if($is_owner || $requested){ ?>
	<div class="sidebar" id="giver">		
		<div class="top">
			<h2>
				<?php echo $G->type=="gift" ? "Requests" : "Offers";?>
			</h2>
		</div>
		<div class="center">
			
			<?php if($is_owner || count($transactions['pending'])>0) { ?>
				<p><?php echo count($transactions['pending']); ?> Pending <?php echo $G->type=="gift" ? "Requests" : "Offers";?></p>
			<?php } ?>
			
			<?php if($is_owner || count($transactions['active'])>0) { ?>
				<p><?php echo count($transactions['active']); ?> Active <?php echo $G->type=="gift" ? "Requests" : "Offers";?></p>
			<?php } ?>
			
			<?php if($is_owner || count($transactions['completed'])>0) { ?>
				<p><?php echo count($transactions['completed']); ?> Completed <?php echo $G->type=="gift" ? "Requests" : "Offers";?></p>
			<?php } ?>
			
			<?php if($is_owner || count($transactions['declined'])>0) { ?>
				<p><?php echo count($transactions['declined']); ?> Declined <?php echo $G->type=="gift" ? "Requests" : "Offers";?></p>
			<?php } ?>
			
			<?php if($is_owner || count($transactions['cancelled'])>0) { ?>
				<p><?php echo count($transactions['cancelled']); ?> Cancelled <?php echo $G->type=="gift" ? "Requests" : "Offers";?></p>
			<?php } ?>
			
			<a href="<?php echo site_url("you/transactions/?good_id=".$G->id);?>">View Transactions</a>
			
		</div>
		<div class="bottom"></div>
	</div>
<?php } ?>



<?php if(!$is_owner){ ?>
	<!-- More About This Person Sidebar-->
	<div class="sidebar" id="giver">		
		<div class="top">
			<h2>More About This Person</h2>
		</div>
		<div class="center">
		
			<!-- Image -->
			<img src="<?php if(isset($G->user->photo->thumb_url)){ echo $G->user->photo->thumb_url; } else { echo $G->user->default_photo->url; }?>" alt="<?php echo $G->user->screen_name; ?>" />
			
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

<!-- Sharing Sidebar -->
<div class="sidebar">		
	<div class="top">
		<h2>Share This</h2>
	</div>
	<div class="center">
	
		<!-- AddThis Toolbox-->
		<div class="addthis_toolbox">
			
			<!-- Facebook Like Button -->
			<a class="addthis_button_facebook_like"></a>
		
			<!-- Other Sharing Destinations -->
			<div class="two_column">
				<div class="top"></div>
				<div class="clear"></div>
				<div class="column1">					
					<a class="addthis_button_facebook">Facebook</a>
					<a class="addthis_button_email">Email</a>
					<a class="addthis_button_myspace">MySpace</a>
				</div>
				<div class="column2">
					<a class="addthis_button_twitter">Twitter</a>
					<a class="addthis_button_digg">Digg</a>
					<a class="addthis_button_delicious">Delicous</a>
				</div>
				<div class="clear"></div>
				<div class="more">
					<a class="addthis_button_expanded">More Destinations...</a>
				</div>
			</div>
		</div>
	</div>
	<div class="bottom"></div>
</div>

<div id="more_photos">
<?php 
//foreach($photos as $row) { echo "<img src='".$row['thumb_url']."'/>"; } 
?>

</div>

<script type='text/javascript'>	
$(function(){
		
	
	$('#show_photos').click(function() {
		var photos = <?php if(!empty($photos)) { echo $photos; } else { echo "empty"; } ?>;

		if(photos != 'empty' && $('#more_photos').children().length == 0)
		{
			for (var data in photos)
			{		
				var d = document.createElement("div");
				text = document.createTextNode(photos[data].caption);

				var img = document.createElement("IMG");
				img.src = photos[data].thumb_url;

				d.appendChild(img);
				d.appendChild(text);

				$('#more_photos').append(d);
			}
			$('#more_photos').addClass('photo_block');
		}
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