<div id="add_gift" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
	
		<?php echo $form; ?>
			
	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->
<script type='text/javascript'>
$(function(){
	$("input:submit").button();
	$('form').validate();
	GF.Tags.initialize($("input#tags"));
});
</script>

