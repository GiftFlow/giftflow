<div class='two_panels'>

	<?php echo $menu; ?>

	<div class='right_content'>
	
		<h2><?php echo $heading;?></h2>
		
		
		<!-- Nearby Users Module -->
		<?php if(!empty($results)){ ?>
			<?php echo UI_Results::users(array(
				"results"=>$results,
				"mini"=>FALSE,
				"include" => array('created')
			));?>
		<?php } ?>
		<?php if(!empty($message)) { ?>
			<?php echo $message; ?>
		<?php } ?>
		<!-- eof Nearby Users Module -->
		
	</div>
	<!-- eof div.right_content -->
	
</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$(".follow").click(function(){ 
		var id = $(this).attr('rel');
		$.post("<?php echo site_url('people/follow/'); ?>/"+id);
		$(this).after("<div class='css_right'><i class='icon-ok'></i>Following</div>");
		$(this).remove();
		return false;
	});
});
</script>
