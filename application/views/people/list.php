<div class='two_panels'>

	<?php echo $menu; ?>

	<div class='right_content'>
	
		<h2><?php echo $heading;?></h2>
		
		
		<!-- Nearby Users Module -->
		<?php if(!empty($results)){ ?>
			<?php echo UI_Results::users(array(
				"results"=>$results,
				"mini"=>FALSE
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
		$(this).after("<div style='float: right;'><span style='float: left; margin-right: 8px; margin-top: 1px;' class='ui-icon ui-icon-check'><\/span><span style='font-size: .9em; color: #666;'>Following<\/span><\/div>");
		$(this).remove();
		return false;
	});
	$(".button").button();
	$(".follow").button( { icons: { primary: 'ui-icon-plusthick'}   } );
	$("#community_sources").buttonTabs();
});
</script>