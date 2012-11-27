<div class='row'>
	
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	<div class='span8 chunk'>
	<legend>Manage Photos </legend>
		<ul class="thumbnails photos-list">
			<?php foreach($photos as $key=>$photo){ ?>
				<li>
					<div class="thumbnail">
						<img src="<?php echo $photo->thumb_url;?>" alt="<?php echo $photo->caption;?>" title="<?php echo $photo->caption;?>" />
						<p class="caption"><?php echo $photo->caption;?></p>
						<div class="btn-group" style="margin-top: 5px;">
						<?php if($U->default_photo_id == $photo->id){ ?>
							<a href="#" class="btn disabled btn-mini">
								<i class="icon-star"></i>
								Default Photo
							</a>
						<?php } else { ?>
							<a href="<?php echo site_url('account/photos/default_photo/'.$photo->id);?>" class="btn btn-mini">
								Make Default
							</a> 				
						<?php } ?>
						<?php if($photo->id!=NULL){ ?>
						<a href="<?php echo site_url('account/photos/photo_delete/'.$photo->id);?>" class="btn btn-mini">
							Delete
						</a>
						<?php } ?>
						</div>
					</div>
				</li>
			<?php } ?>
			<?php if($facebook_connected) { ?>
				<li>
					<a href="<?php echo site_url('account/photos/default_photo/facebook'); ?>" class='btn btn-medium'>Use Facebook Photo</a>
				</li>
			<?php } ?>
		</ul>
				
		<form method="post" enctype="multipart/form-data" class="form-horizontal" action="<?php echo site_url('account/photos/add'); ?>">
			<fieldset>
				<legend>Upload a Photo</legend>
					
				<div class='control-group'>
					<label class="control-label" for="photo">Select File</label>
					<div class="controls">
						<input type="file" name="photo" id="photo"/>
						<span class="help-block">Maximum allowed size is 200KB</span>
					</div>
				</div>
				<div class='control-group'>
					<label for="caption" class="control-label">Caption</label>
					<div class="controls">
						<input type="textarea" name="caption"/>
					</div>
				</div>
			
			<div class='form-actions'>
				<input type='submit' name="Submit" value='Upload' class='btn btn-primary' /> 
			</div>
		</form>
		

			
	</div>
</div>

<script type='text/javascript'>
$(function(){
	$("ul.gray_toolbar li a").click(function(){
		$("ul.gray_toolbar li a").removeClass("active");
		$(this).addClass('active');
		$(".gift_pane").hide();
		$("#"+$(this).attr("rel")).show();
	});
});
</script>
