<div class="two panels">

	<!-- Sidebar Menu -->
	<?php echo $menu;?>
	
	<div class='right_content'>
		<h1>
			Gifts &mdash; <a href="<?php echo site_url('find/?type=gift'); ?>"> Find </a>
		</h1> 
		<ul class='gray_toolbar'>
			<li>
				<a href='#' class='active' rel='nearby'>Nearby</a>
			</li>
			<li>
				<a href='#' rel='newest'>Newest</a>
			</li> 
			<li>
				<a href='#' rel='popular_tags'>Popular Tags</a>
			</li> 
		</ul>
		
		<div class='center'>
			<div id="newest" style="display:none;" class="gift_pane">
			<?php if(!empty($new_gifts)){ ?>
					<?php echo UI_Results::goods(array(
						"results"=>$new_gifts,
						//"include"=>array("distance"),
						"mini"=>FALSE
					));?>
				<?php } ?>
			
				<h2 style='font-weight: 300;'>Popular Tags</h2>
				<?php foreach($tags as $key=>$obj){?>
					<?php if($key<25){ ?>
						<a rel='<?php echo $key;?>' href='<?php echo UI::tag_url($obj->tag,"gifts");?>' class='tag'>
							<?php echo $obj->tag;?>
						</a>
					<?php } ?>
				<?php } ?>
				
				
			</div>
			
			<div id="nearby" class="gift_pane">
			
				<?php if(!empty($nearby_gifts)){ ?>
					<?php echo UI_Results::goods(array(
						"results"=>$nearby_gifts,
						"mini"=>FALSE
					));?>
				<?php } ?>
				
			</div>
			<div id="popular_tags" style="display:none;" class="gift_pane">
			<!-- Tags -->
				<?php foreach($tags as $key=>$obj){?>
					<?php if($key<25){ ?>
						<a rel='<?php echo $key;?>' href='<?php echo UI::tag_url($obj->tag,"gifts");?>' class='tag'>
							<?php echo $obj->tag;?>
						</a>
					<?php } ?>
				<?php } ?>
		</div>
			
		</div>
	
	</div>
	<!-- close left content -->

</div>
<!-- close two panels -->

<script type="text/javascript">
$(function() {
	$("ul.gray_toolbar li a").click(function(){
			$("ul.gray_toolbar li a").removeClass("active");
			$(this).addClass('active');
			$(".gift_pane").hide();
			$("#"+$(this).attr("rel")).show();
		});
});
		
</script>
		