	<?php foreach($results as $R) 
		{  ?>
		<ul class='results_list'>
			<!-- Result Row -->
			<li class='result_row clearfix'>
				<!-- Metadata -->
				<div class='result_meta'>
				<!-- Title --> 
				<?php foreach ($R->users as $val) 
					{
						if($val->id != $segment[2])
						{ ?>
							<a class="title small" href="<?php echo site_url('people/'.$val->id);?>" id='title'>
							<?php echo $val->screen_name; ?></a>
						<?php }
					} ?>
				<span class="metadata"><?php echo strip_tags($R->language->overview_summary); ?></span>
			
				<?php foreach ($R->reviews as $val) 
				{
					if($val->reviewed_id == $segment[2])
					{ ?>
						<p>Rating: <a class="<?php echo $val->rating;?>"><?php echo $val->rating; ?></a></p>
						<div id="snippet">
							<?php $body = substr($val->body, 0,200);
								$body .= "...";
								echo $body;?> 	
							<a id="read_more"><em>read more</em></a>
						</div>
						<div id="full_review" style="display:none;">
							<?php echo $val->body; ?>
						</div>
						
				<?php } ?>
					
		<?php } ?>
				</div> <!-- close metadata -->
			</li>
	<?php } ?>
		</ul>
			<!-- eof Result Row -->
		
	<?php if(!$row) { ?>
		</ul>
		<!-- eof Results List -->
	<?php } ?>
	
<script type ="text/javascript">
$(function(){
	
	$("ul.results_list li a#read_more").click(function(){
      var $snippet = $(this).parent();
      var $full_review = $(this).parent().next();
      $snippet.hide();
      $full_review.show();
      return false;
    });
});
</script>