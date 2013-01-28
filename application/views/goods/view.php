	
<div class="row" id='goods_view'>
	<div class='span7 chunk'>
		<div class='row'>
			<div class='span3'>
				<!-- Image -->
					<?php if(!isset($G->default_photo->thumb_url)) { ?>	
						<a href='#' class="result_image good_icon <?php echo $G->default_photo->class;?>">
						</a>
				<?php } else { ?>
					<a href="<?php echo site_url('gifts/'.$G->id);?>" class="good_icon" title="<?php echo $G->title;?>">
					<img src="<?php echo $G->default_photo->url; ?>"/>
					</a>
				<?php }?>
			</div>
			<div class='span4'>	
				<h1 class="good_title <?php if($G->type == 'need') { echo 'need'; } ?>" >
					<?php echo $G->title; ?>
				</h1>
			
			<!-- action buttons -->
				<p>
				<?php if($logged_in && !$is_owner){ ?>
					<a href="#" id='demand_button' class='btn btn-medium btn-primary'><?php echo $demand_text; ?></a>
				<?php } ?>
				<?php if($is_owner) { ?>
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

				<?php if(!$is_owner && !$logged_in) { ?>
						<a href="<?php echo site_url('goods/visitor_request/'.$G->type.'/'.$G->id);?>" class='btn btn-primary btn-large'><?php echo $button_text; ?></a>
				<?php } ?>
				</p>
			<!-- close action buttons -->


				<div id='demand_form' style='display:none;'>
					<?php echo $demand_form; ?>
				</div>
		
				<p>
				<?php if(!empty($G->location->city)&&!empty($G->location->state)) { ?>
				<!-- Location -->
				<p id='location'>
					<span class='key'>Located in</span>
					<span class= 'value'>
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
				
			
				<!-- AddThis Button BEGIN -->
				<div class="clearfix shareSet addthis_toolbox addthis_default_style">
					<a class="addthis_button_preferred_1"></a>
					<a class="addthis_button_preferred_2"></a>
					<a class="addthis_button_preferred_3"></a>
					<a class="addthis_button_preferred_4"></a>
					<a class="addthis_button_compact"></a>
					<a class="addthis_counter addthis_bubble_style"></a>
				</div>
				<!-- AddThis Button END -->
			
			</div>

		</div>
		<div class='row'>
			<div class='span6'>

				<?php if(!empty($G->description)) { ?>
				<!-- Description -->
				<p id='description'>
					<span class='key'>Description</span>
					<span class='value'>
						<?php echo $G->description; ?>
					</span>
				</p>
				<?php } ?>
			</div>
		</div>
	
		<div class='row-fluid'>
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
			<?php } ?>

			<?php if(!empty($G->photos)) { ?>
			<div id='goods_photos'  class='thumb_grid'>
				<p class='nicebigtext'>More Photos</p>
				<p>
					<?php foreach($G->photos as $val) { ?>
					<a class='photoMod'	style='text-decoration:none;'id="<?php echo site_url($val->url); ?>" href='#photoModal' role='button' data-toggle='modal'>
							<img src='<?php echo site_url($val->thumb_url);?>' />
						</a>
					<?php } ?>
				</p>
			</div>
			<!-- PHOTO Modal window -->
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
			<?php } ?>
	</div><!-- close row -->
<div class='row-fluid posted_by'>
		<div class='span2'>
			<!-- Image -->
			<a  class='user_image' href="<?php echo site_url('people/'.$G->user->id);?>">
				<img src="<?php echo $G->user->default_photo->thumb_url; ?>" />
			</a>
		</div>
		<div class='span6'>
			<!-- Name -->
			<p class='nicebigtext'>Posted by 
				<a class='title' href="<?php echo site_url('people/'.$G->user->id);?>">
					<?php echo $G->user->screen_name; ?>
				</a>
			</p>
		</div>
		<div class='span4'>
			<p>
			<?php echo $G->location->city.", ".$G->location->state;?>
			<br />Joined <?php echo user_date($G->user->created, "n/j/o"); ?>
			</p>
		</div>
	</div>
</div><!-- close content -->


<div class='span4'><!-- open sidebar -->
	<!-- Gifts Sidebar -->
	<?php if(!empty($other_goods)) { ?>
		<div class='row-fluid'>
			<div class='span10 chunk sidebar_chunk'>
			<h2>
			Similar <?php echo $othergoods_type; ?>
			</h2>
				<?php echo UI_Results::goods(array(
					"results"=> $other_goods,
					"size" => "medium"

				)); ?>
			</div>
		</div>
	<?php }?>
</div>
</div>

<script type='text/javascript'>	
$(function(){
		

$('#photoModal').modal({show:false});

$('.photoMod').click(function() {
	var imgUrl = $(this).attr('id');
	$('#modImage').attr('src',imgUrl);
});

$('#demand_button').click(function() {
	$('#demand_form').show();
	$(this).hide();

});

});
</script>
