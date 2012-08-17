<div id='account_photos' class='two_panels'>
	
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
	
	<ul class='gray_toolbar'>
			<li>
				<a href='#' class='active' rel='choose'>Choose Profile Photo</a>
			</li>
			<li>
				<a href='#' rel='upload'>Upload Photos</a>
			</li> 
	</ul>
	<div class='center'>
			<div id="choose" class="gift_pane">
			<form name='photo_source' method='post' action="<?php echo site_url('account/photos/choose_profile_photo');?>" id='photo_source' >

					<h3>Select Profile Photo</h3>
					
					<?php foreach($photos as $key=>$val) 
							{?>
								<p>
								<label>
									<input type='radio' name='source' value='<?php echo $val->id; ?>' <?php if($U->default_photo_id == $val->id) { echo "checked='checked'"; } ?>  />
									<img src="<?php echo $val->thumb_url; ?>"/>
									<?php echo $val->caption; ?>
								</label>
								</p>
								
						<?php }?>
							
					<p>
						<label class="radio">
							<input type='radio' <?php if(empty($U->default_photo_id)){ echo "checked='checked'"; } ?> name='source' value='giftflow' />
							Default Icon
						</label>
					</p>
					
					<p>
						<label class="radio">
							<input type='radio' name='source' value='facebook' <?php if(!$facebook_connected){ echo "disabled='disabled' "; } else if($U->photo_source=="facebook"){ echo "checked='checked' "; } ?>/>
							Facebook Profile Photo<?php if(!$facebook_connected){?>(<a href="<?php echo site_url('account/link/facebook');?>">Click here to connect with your Facebook profile</a>)<?php } ?>
							
						</label>
					</p>
					
					<p>
						<label style='color: #ccc;' class="radio">
							<input type='radio' name='source' value='twitter' disabled='disabled' />
							Coming Soon: Use Twitter Profile Photo
						</label>
					</p>
					
					<p>
						<label style='color: #ccc;' class="radio">
							<input type='radio' name='source' value='gravatar' disabled='disabled' />
							Coming Soon: Use Gravatar
						</label>
					</p>
					
					<p>
						<input type="hidden" name="form_type" value="choose" />
						<input type='submit' class="button btn btn-primary" value='Save' />
					</p>
				</form>
		</div>
				
		<div id="upload" style="display:none;" class="gift_pane">
			<h4>Share photos of yourself!</h4><br />
			You can upload up to 5 photos. Max size 0.5 MB
			<p>
			<?php if($num_photos < 5) { ?>
				<form name="photo_uplaod" action="<?php echo site_url('account/photos/add'); ?>" enctype="multipart/form-data" method='post'>
					<input type="file" class='optional' name="photo" id="photo" />
					<p>Add a caption</p>
					<input type="text" name="caption"/>
					<input type="hidden" name="form_type" value="photo_upload"/>
					<p>
					<input type="submit" class="btn btn-primary" value="Upload"/>
					</p>
				</form>
			<?php } else { ?>
				You have reached your photo limit. You must delete a photo before you can upload another
				<?php } ?>
			</p>
			
		</div>
	</div><!-- end of center -->
	</div>
	<!-- eof div.right_content -->
</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$("ul.gray_toolbar li a").click(function(){
		$("ul.gray_toolbar li a").removeClass("active");
		$(this).addClass('active');
		$(".gift_pane").hide();
		$("#"+$(this).attr("rel")).show();
	});
});
</script>