<?php if(!empty($friends)) { ?>
	<h2>Found <?php echo count($friends); ?> friends on Facebook</h2>
	
	<?php echo UI_Results::users(array(
		"results"=>$friends,
		"mini"=>TRUE
	));?>

<?php }  else { ?>
<p>Your account is not yet linked with Facebook. But don't worry—that is easy to fix! Click the link below and you'll be connected with your friends on GiftFlow in no time.</p>
<a href='<?php echo site_url('account/link/facebook'); ?>'>
	<img style='border: none;' src='<?php echo base_url(); ?>assets/images/facebook_login.png' />
</a>

<?php } ?>

<script type='text/javascript'>
$(function(){
	$(".follow").click(function(){ 
		var id = $(this).attr('rel');
		$.post("<?php echo site_url('people/follow/'); ?>/"+id);
		$(this).after("<div style='float: right;'><span style='float: left; margin-right: 8px; margin-top: 1px;' class='ui-icon ui-icon-check'><\/span><span style='font-size: .9em; color: #666;'>Following<\/span><\/div>");
		$(this).remove();
		return false;
	});
	$(".follow").button( { icons: { primary: 'ui-icon-plusthick'}   } );
});
</script>