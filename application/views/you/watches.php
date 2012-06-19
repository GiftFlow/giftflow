<div id="your_watches" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	
	<div class='right_content'>
		<h3>Watch a Keyword</h3>

		<?php echo $form; ?>
		
		<h3>Keywords you are currently watching</h3>
		
		<?php if(!empty($watches)) { ?>
			<ul class ="transactions goods_list list_menu float_right">
				<?php 
					foreach($watches as $val) 
					{ ?>
					
							<li class="clearfix">
							
							<!-- Options Dropdown Menu -->
							  <a class="css_right btn btn-large delete_watch" href="<?php echo site_url('watches/'.$val->id.'/delete'); ?>">
								<i class="icon-trash"></i>
							  </a>
								
							<div class="metadata left">
								<?php echo $val->keyword; ?>
							</div>
								
						</li>
				<?php } ?>
				</ul>
		<?php } else { ?>
		
			<!-- Empty State -->
			<p>
				No watches found.
			</p>
		<?php } ?>
		
	</div>
	<!-- eof div.right_content -->
	

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){

	$(".delete_watch").click(function(){
		return confirm('Are you sure you want to delete this watch?');
	});
	
});
</script>
