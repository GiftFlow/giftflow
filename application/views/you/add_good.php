<div id="add_gift" class="row">
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
		
	<div class='span8 chunk'>
	
		<?php echo $form; ?>
			
	</div>
</div>
<!-- eof div.two_panels -->
<script type='text/javascript'>
$(function(){
	$('form').validate();
	
	GF.Tags.initialize($("input#tags"));
	GF.Locations.initialize($("input#location"));
});
</script>

