<?php if(!empty($results)) { ?>
	<ul id="goods_includes_results" class='results_list goods mini'>
	
	<?php foreach($results as $G){ ?>
		<?php if($G->status == 'active') { ?>
			<!-- Result Row -->
			<li class='result_row clearfix'>
						
							<!-- Title -->
							
							<a class="title sideBarTitle <?php if($G->type == 'need') { echo 'need'; } ?>" href="<?php echo site_url($G->type.'s/'.$G->id);?>">
								<span class= "title">
									<?php echo substr($G->title, 0, 30); ?>
								</span>
							</a>
						<span class='metadata'>posted by <?php echo $G->user->screen_name; ?></span>
			</li>
			<!-- eof Result Row -->
		<?php } ?>
	<?php } ?>
	</ul>
<?php } ?>
