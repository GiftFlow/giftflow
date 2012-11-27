<div class='row'>
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	
	<div class='span8 chunk'>
		<h3>Watch a Keyword</h3>

		<?php echo $form; ?>
		
		
		<?php if(!empty($watches)) { ?>
			<ul class='interaction'>
				<li class='section_header'>
					<h3 class='inbox_title'>Currently Watching</h3>
				</li>
				<?php 
					foreach($watches as $val) 
					{ ?>
						<li class='watch'>
						
						  <a class="btn btn-mini delete_watch" href="<?php echo site_url('watches/'.$val->id.'/delete'); ?>" title='click to delete'>
							<i class="icon-trash"></i>
						  </a>
							
							<a class='keyword' title='Click to Search' href='<?php echo site_url("find/gifts/".$val->keyword); ?>'>
								<?php echo $val->keyword; ?>
							</a>
								
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
	
</div>

<script type='text/javascript'>
$(function(){

$('.delete_watch').tooltip();

});
</script>
