<div class='group_invite_form'>

<form class='form-horizontal form_wrapper' name='group_invite' id='group_invite' method='post' action="<?php echo site_url('groups/add_user'); ?>">

<input type='text' id='invite_user' name='invite_user' />
<input type='hidden' id='user_id' name='invite_user_id' value=''/>
<input type='hidden' id='invite_group_id' name='invite_group_id' value='<?php echo $group->id; ?>'/>
<input type='submit' value='submit' class='btn btn-primary'/>

</form>
</div>

<script type='text/javascript'>

$(function() {

	GF.Users.initialize($('#invite_user'));

});
</script>


