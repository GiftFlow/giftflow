<h3><?php echo $is_ajax ? $title : $question; ?></h3>
<form id='add_good_form' name='add_good' action="<?php echo site_url('goods/add');?>" method='post' class="form-horizontal">
	<fieldset>
	
    <div class="control-group">
    	<label class="control-label" for="title">Title</label>
    	<div class="controls">
    		<input type="text" name="title" id="title" value="" class="required input-xlarge"/>	
    	</div>
    </div>
    
	<?php if(!$is_ajax){ ?>
    <div class="control-group">
    	<label class="control-label" for="description">Description</label>
    	<div class="controls">
    		<textarea rows="5" name="description" id="description" value="" class="required"></textarea>
    	</div>
    </div>
	<?php } ?>

	
    <div class="control-group">
    	<label class="control-label" for="category">Category</label>
    	<div class="controls">
			<select name='category' id='category' title='Category' class="required">
				<option></option>
				<?php foreach($categories as $key=>$val){ ?>
					<option value="<?php echo $val->id;?>">
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
			<input type="text" value='' name='tags' id='tags' class="required"/>
			<label class="error" for="tags" style="display: none;">Please enter at least one tag</label>
			<p class="help-block">e.g. sweater, wool, knit, clothes (separate with commas)</p>
    	</div>
    </div>

	<!-- Location -->
    <div class="control-group">
    	<label class="control-label" for="location">Location</label>
    	<div class="controls">
			<input type="text" name="location" id="location" value="<?php echo $user_default_location ?>" class="required" />
			<p class="help-block">e.g city or zip code</p>
    	</div>
    </div>
    
    <!-- Submit Button -->
    <div class="form-actions">
		<input type="hidden" name="type" value="<?php echo $type; ?>"/>
		<input type='submit' class="btn btn-primary" value='Add <?php echo $type; ?>'>
    </div>

    
</fieldset>
</form>

<script type="text/javascript">
$(function(){
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