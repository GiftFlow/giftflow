<div id="manage_photos" class="row">

	<div class='span2'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	<!-- Main Content -->
	<div class='span8 chunk'>
	<legend>Manage Photos for <?php echo $G->title;?></legend>
		<ul class="thumbnails photos-list">
			<?php foreach($photos as $key=>$photo){ ?>
				<li>
					<div class="thumbnail">
						<img src="<?php echo $photo->thumb_url;?>" alt="<?php echo $photo->caption;?>" title="<?php echo $photo->caption;?>" />
						<p class="caption"><?php echo $photo->caption;?></p>
						<div class="btn-group" style="margin-top: 5px;">
						<?php if($photo->default){ ?>
							<a href="#" class="btn disabled btn-mini">
								<i class="icon-star"></i>
								Default Photo
							</a>
						<?php } else { ?>
							<a href="<?php echo site_url($G->type.'s/'.$G->id.'/photo_default/'.$photo->id);?>" class="btn btn-mini">
								Make Default
							</a> 				
						<?php } ?>
						<?php if($photo->id!=NULL){ ?>
						<a href="<?php echo site_url($G->type.'s/'.$G->id.'/photo_delete/'.$photo->id);?>" class="btn btn-mini">
							Delete
						</a>
						<?php } ?>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
				
		<form method="post" enctype="multipart/form-data" class="form-horizontal">
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
				<input type="hidden" name="method" value="_photos"/>
				<input type="hidden" name="good_id" value="<?php echo $G->id; ?>"/>
				<input type='submit' name="Submit" value='Upload' class='btn btn-primary' /> 
			</div>
		</form>
		
			<a href="<?php echo site_url($G->type."s/".$G->id."/edit");?>" class="btn">
		<i class="icon-chevron-left"></i>
		Back
	</a>

			
	</div>
</div>
<script type='text/javascript'>
$(function(){
	
});
</script>

