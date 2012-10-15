<h3><?php if($add){ echo $is_ajax ? $title : $question; }
		  else { echo "Edit ".$G->title; } ?></h3>
<form id='add_good_form' name='add_good' action="<?php if($add){ echo site_url('goods/add'); } ?>" method='post' class="form-horizontal">
	<fieldset>
	
    <div class="control-group">
    	<label class="control-label" for="title">Title</label>
    	<div class="controls">
    		<input type="text" name="title" id="title" value="<?php if(isset($G) && !empty($G->title)){ echo $G->title; } ?>" class="required input-xlarge"/>	
    	</div>
    </div>
    
	<?php if(!$is_ajax){ ?>
    <div class="control-group">
    	<label class="control-label" for="description">Description</label>
    	<div class="controls">
    		<textarea rows="5" name="description" id="description" value="" class="required"><?php if(isset($G) && !empty($G->description)){ echo $G->description; }?></textarea>
    	</div>
    </div>
	<?php } ?>

	
    <div class="control-group">
    	<label class="control-label" for="category">Category</label>
    	<div class="controls">
			<select name='category' id='category' title='Category' class="required input-xlarge">
				<option></option>
				<?php foreach($categories as $key=>$val){ ?>
					<option value="<?php echo $val->id;?>" <?php if(isset($G) && $val->id == $G->category->id) { echo "selected='yes'";} ?>>
						<?php echo $val->name; ?>
					</option>
				<?php } ?>
			</select>
			<label for="category" class="error" style="display: none;">Please select a category.</label>
    	</div>
    </div>
    
    <!-- Tags -->
    <div class="control-group">
    	<label class="control-label" for="title">Tags</label>
    	<div class="controls">
			<input type="text" value='<?php if(isset($G) && !empty($G->tags)){ echo implode($G->tags,", "); } ?>' name='tags' id='tags' class="required input-xlarge"/>
			<label class="error" for="tags" style="display: none;">Please enter at least one tag</label>
			<p class="help-block">e.g. sweater, wool, knit, clothes (separate with commas)</p>
    	</div>
    </div>

	<!-- Location -->
    <div class="control-group">
    	<label class="control-label" for="location">Location</label>
    	<div class="controls">
			<input type="text" name="location" id="location" value="" class="required input-xlarge" />
			<p class="help-block">e.g city or zip code</p>
    	</div>
    </div>
    
    <?php if(!$add){ ?>
    <div class="control-group">
    	<label class="control-label" >Photos</label>
    	<div class="controls">
    	
    	
			<a href="<?php echo site_url($G->type."s/".$G->id."/photos");?>" class="btn clearfix edit-photos">
				<?php foreach($photos as $key=>$photo){ ?>
					<img src="<?php echo $photo->thumb_url;?>" alt="<?php echo $photo->caption;?>" title="<?php echo $photo->caption;?>" class="css_left" />
				<?php } ?>
					
				<div class="css_left" style="margin: 17px 0 0 10px;">
					Add / Edit Photos
					<i class="icon-chevron-right"></i>
				</div>
			</a>

			<ul class="thumbnails mini-photos-list" style="display: none;">
				<li>
					<div class="thumbnail clearfix">
					<?php foreach($photos as $key=>$photo){ ?>
						<img src="<?php echo $photo->thumb_url;?>" alt="<?php echo $photo->caption;?>" title="<?php echo $photo->caption;?>" class="css_left" />
					<?php } ?>
					<a href="#" id="edit-photos" class="btn css_left">
						Edit Photos
						<i class="icon-chevron-right"></i>
					</a>
					</div>
				</li>
			</ul>

    	</div>
    </div>
    <?php } ?>

    
    <!-- Submit Button -->
    <div class="form-actions">
    <?php if($add){ ?>
		<input type="hidden" name="type" value="<?php echo $type; ?>"/>
		<input type='submit' class="btn btn-primary" value='Add <?php echo $type; ?>'>
	<?php } else { ?>
		<input type='hidden' name='method' value='edit' />
		<input type='hidden' value='<?php echo $G->id; ?>' name='good_id' />
		<input type='submit' class="btn btn-primary" value='Save Changes'>
		<a href="<?php echo site_url($G->type.'s/'.$G->id);?>" class="btn">Cancel</a>
	<?php } ?>
    </div>

    
</fieldset>
</form>

<script type="text/javascript">
$(function(){
	$("#edit-photos").click(function(){
		$(".mini-photos-list").hide();
		$(".photos-list").fadeIn();
	});
	$("#add_good_form").validate({
		highlight: function(label) { 
			$(label).closest('.control-group') 		
			.addClass('error').removeClass('success');
	  	},
	  	success: function(label) {
		  	label.hide().closest('.control-group').addClass('success');
	  	}
	});
});
</script>
