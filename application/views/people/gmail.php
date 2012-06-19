<?php if(!empty($friends)) { ?>
	<h2>Found <?php echo count($friends); ?> friends on Gmail</h2>

	<?php echo UI_Results::users(array(
		"results"=>$friends,
		"mini"=>TRUE
	));?>
	
<?php }  else { ?>
<p>Your account is not yet linked with your Gmail account. But don't worry—that is easy to fix! Click the link below and you'll be connected with your friends on GiftFlow in no time.</p>
<a href='<?php echo site_url('account/link/google'); ?>'>
	<img style='border: none;' src='<?php echo base_url(); ?>assets/images/google_connect.png' />
</a>

<?php } ?>
<script type='text/javascript'>
$(function(){
	// NB: follow button listener function located in footer view
});
</script>