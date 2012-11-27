<div class='row'>	
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	<div class='span8 chunk'>
		<p>Delete Account</p>
		<form action="<?php echo site_url('account/delete_user'); ?>" method="post">
		<p>Please take a moment to tell us why you are deleting your account:</p>
		<textarea name='reason'></textarea>
		<input type='submit' name='delete' class='btn' value='Please delete my account' id='delete_user'/>

		</form>		

	</div>
</div>


<script type='text/javascript'>
$("#delete_user").click(function(){
		var answer = confirm('Are you sure you want to delete your account?');
		if(answer) return true;
		else return false;
	});


</script>
