<form method="post" action="<?php echo site_url('goods/view'); ?>">
	<label>Send <?php echo $G->user->screen_name; ?> a note:</label><br />
		<textarea name='note' rows='5'></textarea>
	</p>
	<input type="hidden" name="method" value="demand">
	<input type='hidden' name='good_type' value="<?php echo $G->type; ?>"/>
	<input type="hidden" name="type" value="<?php echo $demand; ?>">
	<input type="hidden" name="good_id" value="<?php echo $G->id;?>" />
	<input type="hidden" name="decider_id" value="<?php echo $G->user->id; ?>" />
	<input type="submit" class="btn btn-primary" value="<?php echo $demand_text; ?>" />
</form>
