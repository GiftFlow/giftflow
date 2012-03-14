<h3><?php echo $is_ajax ? $title : $question; ?></h3>
<form id='add_good_form' name='add_good' action="<?php echo site_url('goods/add');?>" method='post'>
	<table class="form">

	<tr class="row" id="title">
		<td class="label">
			<label for="title">Title:</label>
		</td>
		<td class="field">
			<input type="text" name="title" id="email" value="" class="required"/>			
		</td>
	</tr>

	<?php if(!$is_ajax){ ?>
	<tr class="row" id="description">
		<td class="label">
			<label for="description">Description</label>
		</td>
		<td class="field">
			<textarea rows="5" class="big-border" name="description" id="description" value="" class="required"></textarea>			
		</td>
	</tr>
	<?php } ?>
	
	<tr>
		<td class='label'>
			<span class="label">Category</span>
		</td>
		<td class="field">
			<select name='category' id='category' title='Category' class="required">
				<option></option>
				<?php foreach($categories as $key=>$val){ ?>
					<option value="<?php echo $val->id;?>">
						<?php echo $val->name; ?>
					</option>
				<?php } ?>
			</select>
		</td>
	</tr>

	
	<tr>
		<td class='label'>
			<span class="label">Tag It!</span>
		</td>
		<td class="field">
			<input type="text" value='' name='tags' id='tags' title='Tag It!' class="required"/>
			<label for='tags'>e.g. sweater, wool, knit, clothes (separate with commas)</label>
		</td>
	</tr>
	<tr class="row" id="location">
		<td class="label">
			<span class = "label">Location</span>
		</td>
	<td class="field">
		<input type="text" name="location" id="location" value="<?php echo $user_default_location ?>" class="required" />
		<label for='location'>e.g city or zip code</label>
	</td>
</tr>
</table>
<input type="hidden" name="type" value="<?php echo $type; ?>"/>
<input type='submit' value='Add <?php echo $type; ?>'>
</form>
