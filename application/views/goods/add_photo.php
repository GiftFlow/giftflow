<!-- Main Module -->
<div class="gift_module" id="view_gift">
	<div class="top"></div>
	<div class="middle">
		<div id='edit_gift_form'>
		<img src="<?php if(isset($G->photo->thumb_url)) { echo $G->photo->thumb_url; } else { echo $G->default_photo->url;} ?>" />
			<h1>Add a photo for: <?php echo $G->title; ?></h1>			
			<div class='main'>
			<br />
				<!-- Edit Info -->
				<div id='edit_info'>
					<form action="<?php echo site_url($G->type.'s/'.$G->id.'/photo_add');?>" method="post" enctype="multipart/form-data" >
					<input type="file" name="photo" id="photo"/>
					<p>
					<label> add a caption </label>
					<input type="textarea" name="caption"/>
					</p>
					<input type="hidden" name="method" value="_add_photo"/>
					<input type="hidden" name="good_id" value="<?php echo $G->id; ?>"/>
				</div>
				<input type='submit' name="Submit" value='' class='blue' /> 
				</form>
				<p> Maximum allowed size is 200KB</p>
			</div>
			</div>
		</div>		
	</div>
	
	<!-- show all photos of the good -->
	<div class="sidebar">		
		<h3>Photos you've uploaded so far </h3>
		<ul id='locations_list' class='list_menu list photos' style="width:300px;">
		<?php if(!empty($photos)) { ?>
			<?php foreach($photos as $key=>$val) { ?>
				<li id='<?php echo $val['id']; ?>' class='clearfix'>
					
					<!-- Options Dropdown Menu -->
					<div class="btn-group css_right">
					  <button class="btn btn-large dropdown-toggle" data-toggle="dropdown">
						<i class="icon-cog"></i>
						<span class="caret"></span>
					  </button>
					 
					  <ul class="dropdown-menu">
						<?php if(!$val['default']) { ?>
							<li>
								<a href="<?php echo site_url($G->type.'s/'.$G->id.'/photo_default/'.$val['id']); ?>" >
									<i class="icon-star"></i>
									Make Default Photo
								</a>
							</li>
						<?php } ?>
						<li class="divider"></li>
						<li><a href="<?php echo site_url('account/locations/'.$val['id'].'/delete/'); ?>">
							<i class='icon-trash'></i>
							Delete
						</a></li>
					  </ul>
					</div>
					
					<div class="list_content">
						<img src="<?php echo $val['thumb_url']; ?>"/>
						<span id='address'>
							<?php echo $val['caption']; ?>
						</span>
						<?php if($val['default']){ print_r($val['default']);?>
							<span class="label label-success">Default</span>
						<?php } ?>
						
						
					</div>
					
				</li>
			<?php } ?>
			</ul>
		<?php } ?>
	</div>
			
	</div>

<div class="bottom"></div>

<script type='text/javascript'>
$(function(){
	
	// Style buttons
	$('.button').button();
	$(".buttonset").buttonset();
	$("input:submit").button();
});

</script>