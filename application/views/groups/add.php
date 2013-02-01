<div>


<form method='post' name='new_group' id='new_group' action="groups/create">


<label for='group_name'>Group NAME</label>
<input type='text' name='group_name' value='' id='group_name'/>

<label for='group_description'>DeSCRIUBE</label>

<textarea name='group_description' value='' rows='5'/> 

<label for='group_privacy'>Privacy level</label>
<select name='group_privacy' id='group_privacy'>
	<option value='public'>Public</option>
	<options value='findable'>Private</option>
</select>

<label for='group_location'>Location</label>
<input type='text' name='group_location' id='group_location' value=''/>

<label class='checkbox'>
<input type='checkbox' name='invite' id='invite' value=''/>
All users can invite others to join
</label>


<label for='group_admission'>Group Admissions</label>
<select name='group_admission' id='group_admission'>
	<option value='open'>Open</option>
	<options value='by_request'>By Request</option>
</select>


</form>








</div>
