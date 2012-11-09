<div id="your_watches" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	
	<div class='right_content'>
		<h3>Watch a Keyword</h3>

		<?php echo $form; ?>
		
		
		<?php if(!empty($watches)) { ?>
			<ul class ="transactions goods_list list_menu float_right" id='watch_list'>
				<li class='section_header'>
					<h3 class='messages_title'>Currently Watching</h3>
				</li>
				<?php 
					foreach($watches as $val) 
					{ ?>
					
							<li class="clearfix">
							
							<!-- Options Dropdown Menu -->
							  <a class="css_right btn btn-large delete_watch" href="<?php echo site_url('watches/'.$val->id.'/delete'); ?>">
								<i class="icon-trash"></i>
							  </a>
								
							<div class="metadata left watchword">
								<a id='keyword' title='Click to Search' href='<?php echo site_url("find/gifts/".$val->keyword); ?>'>
									<?php echo $val->keyword; ?>
								</a>
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
/*
	$('#keyword').tipTip({
		defaultPosition: "right",
		delay: 0,
		fadein: 0,
		keepAlive:'true'
	});
 */	
});
</script>
