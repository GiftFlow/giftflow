<div class='row'>
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	<div class='span9 chunk'>
		<a href="<?php echo site_url('you/add_good/'.$type);?>" id='add_good' class="btn btn-large btn-success">Add a <?php echo ucfirst($type); ?></a>
		<ul class ="results_list">
			<?php if(!empty($goods)) { ?>
				<?php foreach($goods as $val) { ?>
						<li class='clearfix'>
						<div class='row-fluid'>
							<div class='span2'>	
								<a href="<?php echo site_url($type.'s/'.$val->id);?>" class="result_image" title="<?php echo $val->title;?>">
									<?php if(!isset($val->default_photo->thumb_url)) { ?>	
										<a href='#' class="<?php echo $val->default_photo->thumb_class; ?>">
										</a>
									<?php } else { ?>
										<img src="<?php echo $val->default_photo->thumb_url; ?>"/>
									<?php }?>
								</a>
							</div>
				
							<div class="span4 metadata">
								<a href="<?php echo site_url($val->type.'s/'.$val->id);?>" class="title">
								 <?php echo $val->title; ?>
								</a>
								<span class="summary">
								<?php echo substr($val->description, 0, 100); ?>
								</span>
							</div>
							
							<!-- Options Dropdown Menu -->
							<div class="span6 btn-group good_buttons">
								<a href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=http://giftflow.org/gifts/<?php echo $val->id; ?>" class='btn shareBtn' title='on Facebook'>Share</a>
								<a href="<?php echo site_url($val->type.'s/'.$val->id.'/edit'); ?>" class='btn'>Edit</a>
								<a href="<?php echo site_url($val->type.'s/'.$val->id.'/photos'); ?>" class='btn'>Add Photos</a>
								<a href="<?php echo site_url('gifts/'.$val->id.'/disable');?>" class='btn'>Delete</a>

							</div>
							<!-- eof Options Dropdown Menu -->
						</div>
							
					</li>

			<?php } ?>
		<?php } else { ?>
		
			<!-- Empty State -->
			<li>
				No <?php echo $type.'s'; ?> found.
			</li>
		<?php } ?>
		</ul>
	</div>
</div>

	<div class='modal hide fade' id='shareModal'>
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button>
			<h3 style ='text-align:center;'>Congratulations! Your <?php echo ucfirst($type);?> has been added.</h3>
		</div>
		<div class='modal-body row-fluid spreadWord'>
			
			<div class='span3'>
				<a class='result_image'</a>
				<a class='medium-16'></a>
			</div>
			<div class="span6 addthis_toolbox addthis_default_style addthis_32x32_style"
				addthis:url="<?php echo site_url($type.'s/'.$shareId);?>">
					<a class="addthis_button_preferred_1"></a>
					<a class="addthis_button_preferred_2"></a>
					<a class="addthis_button_preferred_3"></a>
					<a class="addthis_button_preferred_4"></a>
					<a href="http://www.addthis.com/bookmark.php?v=250&pubid=giftflow" class="addthis_button_compact"></a>
				<p class='nicebigtext'>Spread the word!</p>
			</div>
			<div class='span3'>
				<a class='result_image'></a>
				<a class=' medium-16'></a>
			</div>
		</div>
		<div class='modal-footer'>
			<a href="<?php echo site_url($type.'s/'.$shareId.'/photos'); ?>" class='btn pull-left'>Add Photos</a>
			<a href='#' data-dismiss='modal' class='btn'>Close</a>
		</div>
	</div>


<script type='text/javascript'>
$(function(){

	if(<?php echo $promptShare; ?>)
	{
		$('#shareModal').modal({
			'toggle': 'true',
			'backdrop': 'static'
		});
	}


	$("img.status_icon").tooltip({ delay: 0, fadein: 0 });

	$('.shareBtn').tooltip({ delay: 0, fadein: 0 });
	
	$("#delete_gift").click(function(){
		return confirm('Are you sure you want to delete this gift? Doing so will cancel all transactions involving this gift');
	});

});
</script>
