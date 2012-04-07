<div id='account_profile' class='two_panels'>
	
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
	<p>Delete Account</p>
	<form action="<?php echo site_url('account/delete_user'); ?>" method="post">
	<p>Please take a moment to tell us why you are deleting your account:</p>
	<textarea name='reason'></textarea>
	<input type='submit' name='delete' value='Please delete my account' id='delete_button'/>

	</form>		

	</div>
	<!-- eof div.right_content -->
</div>
<!-- eof div.two_panels -->


<script type='text/javascript'>
$("#delete_user").click(function(){
		var answer = confirm('Are you sure you want to delete your account?');
		if(answer) return true;
		else return false;
	});


</script>
