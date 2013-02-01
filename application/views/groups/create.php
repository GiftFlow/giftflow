<div>


<form method='post' name='new_group' id='new_group' action="create">


<label for='group_name'>Group NAME</label>
<input type='text' name='group_name' value='' id='group_name'/>

<label for='group_description'>DeSCRIUBE</label>

<textarea name='group_description' value='' rows='5'></textarea> 

<label for='group_privacy'>Privacy level</label>
<select name='group_privacy' id='group_privacy'>
	<option value='public'>Public</option>
	<option value='findable'>Private</option>
</select>

<label for='group_location'>Location</label>
<input type='text' name='group_location' id='group_location' value=''/>

<label class='radio'>
<input type='radio' name='invite' id='inviteY' value='yes'/>
All users can invite others to join
</label>

<label class='radio'>
<input type='radio' name='invite' id='inviteN' value='no'/>
Only group administrator can invite new members.
</label>

<label for='group_admission'>Group Admissions</label>
<select name='group_admission' id='group_admission'>
	<option value='open'>Open</option>
	<option value='by_request'>By Request</option>
</select>


<input type='submit' value='submit'/>


</form>








</div>
