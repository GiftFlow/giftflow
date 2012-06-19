
<form id='add_watch_form' name='add_watch' action="<?php echo site_url('watches/add');?>" method='post'>
	<table class="form">
	
	<tr>
		<td valign=top>
			<input type='submit' value='Add Watch'>
		</td>
		<td class="field">
			<input type="text" name="keyword" id="keyword" value="" class="required"/>
			<label for='tags'>this keyword will be automatically looked for in new gifts</label>
		</td>
	</tr>
	
</table>
</form>
