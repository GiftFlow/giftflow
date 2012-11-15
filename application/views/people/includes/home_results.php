<?php if(!empty($results)) { ?>

		<!-- Results List -->
		<ul class='results_list users <?php if($mini){ echo "mini"; } ?>'>
	
		<?php foreach($results as $key=>$val) {?>
			<?php if($val->status != 'disabled') { ?>
		
			<!-- Result Row -->
			<li class='result_row clearfix'>
			<div class='row-fluid'>		
				<div class='span2'>		
					<!-- Image -->
					<a class='result_image' href='<?php echo site_url('people/'.$val->id); ?>'>
						<img src='<?php if(isset($val->photo->url)) { echo $val->photo->thumb_url; } else { echo $val->default_photo->thumb_url; }?>'>
					</a>
				</div>
					
				<div class='span10'>
				
					<!-- Screen Name -->
					<a href='<?php echo site_url('people/'.$val->id); ?>' class='title'>
						<?php echo $val->screen_name;?>
					</a>
					
					<!-- Location -->
					<span class='minidata'>
						<?php echo $val->location->address; ?> 
					</span>
						
					<!-- Created -->
					<span class="minidata">
						joined <?php echo user_date($val->created,"n/j/o");?>
					</span>
					</div>
				</div>
			</li>
			<!-- eof Result Row -->
			
		<?php } ?>
	<?php } ?>
	<?php if(!$row) { ?>
		</ul>
		<!-- eof Results List -->
	<?php } ?>
	
<?php } ?>
