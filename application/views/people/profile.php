<?php 

if(!$active) { echo "DISABLED"; }


?>
<div id='good_header'>
	<a href="<?php echo current_url();?>" class="user_image left medium">
		<img src="<?php echo $profile_thumb; ?>" />
	</a>
	<div id='good_header_info'>
	    	<?php if($u->type=="nonprofit") { echo "<a href='#' id='nonprofit_link' class=' link'>Non-Profit </a>"; } ?>

		<h1 id='profile_name'><?php echo $u->screen_name; ?></h1>
		<p>
			<?php if(!empty($u->default_location)&&!empty($u->default_location->city)){ ?>
						<?php echo $u->default_location->city.',';?>
							<?php if($u->default_location->country=="United States")
							{
									echo $u->default_location->state.' / ';
							} 
							else 
							{
									echo $u->default_location->country.' / ';
							} ?> 
			<?php } ?>
			<?php echo $u->total_followers; ?> Followers</p>
	</div>
</div>
<div id='profile_left'>

<ul id='profile_toolbar_left' class='gray_toolbar'>
	
	<!-- 
<li>
		<a href='#' class='overview' rel='overview'>Overview</a>
	</li>
 -->
	
	<li>
		<a href='#' class="active_goods active" rel='active_goods'>Gifts & Needs</a>
	</li>
	<li>
		<a href='#' class="reviews" rel='reviews'>Reviews</a>
	</li>
	<li>
		<a href='#' class='profile' rel='bio'>Bio</a>
	</li>
	<!-- 
<li>
		<a href='#' class="history" rel='history'>History</a>
	</li> 
 -->
 	<li>
		<a href='#' class='profile' rel='photos'>Photos</a>
	</li>
	<li>
		<a href='#' rel='give_to'><?php if($visitor){?>Give to <?php echo substr($u->screen_name, 0, 20); }?></a>
	</li>
	
	
	
	
</ul>
<div id='profile_left_content'>


<!-- Active Gifts and Needs -->
	<div id='active_goods'  class='profile_pane active'>
	
		<h3>Gifts</h3>
		
		<?php if(!empty($gifts)) { ?>
		
			<?php echo UI_Results::goods(array(
				"results"=>$gifts,
				"mini"=>TRUE
			)); ?>
			
		<?php } else { ?>
			<p>This user does not have any gifts to give at the moment.</p>
		<?php } ?>
		
		<br />
		<h3>Needs</h3>

		<?php if(!empty($needs)) { ?>
		
			<?php echo UI_Results::goods(array(
				"results"=>$needs,
				"mini"=>TRUE
			)); ?>
			
		<?php } else { ?>
			<p>This user does not have any needs at the moment.</p>
		<?php } ?>
		
	</div>

<!-- Reviews Section -->
	<div id='reviews' style='display: none;' class='profile_pane'>
			<?php if(!empty($transactions)) 
			{   
				echo UI_Results::reviews(array(
					"results"=>$transactions
				));
			 } else { ?>
				<p>This user has not yet received any reviews</p>
			<?php } ?>

		<?php if(!empty($thankyous)) {
			echo UI_Results::thankyous(array(
				'results'=>$thankyous
			));
		}?>
	</div>
	
	<!-- Photos -->
	<div id='photos' class='profile_pane' style="display:none;">
		
		<?php if(!empty($photos)) { ?>
			<div id="slideshow">
		 
			<ul class="slides">
				<?php foreach($photos as $val) { ?>
				 <li id="<?php echo $val['id']; ?>">
				 	<span class = "<?php echo $val['id']; ?>" style="display:none;"><?php echo $val['caption']; ?></span>
				 <img src="<?php echo $val['url']; ?>" width="510" height="320" alt="<?php echo $val['caption']; ?>" />
				 </li>
				
				<?php } ?>
			</ul>
		 
			<span class="arrow previous"></span>
			<span class="arrow next"></span>
			</div>
		<?php } else {?>
		<!-- empty state for photos -->
			<p> <?php echo $u->screen_name; ?> has not uploaded additional photos yet </p>
		<?php } ?>
	</div>
	
	<div id='give_to' style='display: none;' class='profile_pane'>
		<h3>Offer a Gift to <?php echo $u->screen_name; ?> </h3>
		<p>Add a message</p>
		<form method="post" action="<?php echo site_url('people/profile');?>">
		<input type="hidden" name="type" value="give"/>
		<input type='hidden' name='formtype' value='offer'/>
		<input type="hidden" name="decider_id" value="<?php echo $this->uri->segment(2); ?>" />
		<textarea name="reason"></textarea>
		<br />
		<p><input style="float:left;" type="submit" value="Send" class="btn btn-large" /></p>
		<br />
		
			<?php if(!empty($potential_gifts)) {
				
				echo UI_Results::goods(array(
				"results"=>$potential_gifts,
				"include" => array("offer_links")
				)); 
				?> 
				</form>
		<?php }
			else
			{
				echo "You need to post some gifts first! Log in and go to My Gifts and Click 'Add Gift'";
			}?>
	
	</div>
	<!-- Bio -->
	<div id='bio' class='profile_pane profile_item' style="display:none;">
			
				<h3>Bio</h3>
				<p><?php if(!empty($u->bio)) { echo $u->bio; }?></p>
				<h3>URL</h3>
				<p><?php if(!empty($u->url)) { echo $u->url; }?>
				<h3>Occupation</h3>
				<p><?php if(!empty($u->occupation)) { echo $u->occupation; }?>
		 
	</div>
	<div id ='thankyouwrapper' class='profile_pane' style='display:none;'>
		<form name = 'thankyou' id='thankyouform' method='post' action=''>
			<p>
			Make your gratitude public! Write a thank you note to go on <?php echo $u->screen_name; ?>'s profile.
			</p>
			<br/>
			<p>
			<label for='gift'>What did <?php echo $u->screen_name; ?> give you?</label>
			</p>
			<p>
				<input type='text' class='big-border' maxlength ='100' name='thankyou_gift' id='thankyou_gift' value='' class='required'/>
			</p>
			<p>
			<label for='body'>Write a thank you note. Be sure to describe your experience.</label>
			</p>
			<p>
				<textarea rows='5' class='big-border' name='body' id='body' value='' class='required'/></textarea>
			</p>
			<!-- hidden fields -->
			<input type='hidden' id='recipient_id' name='recipient_id' value='<?php echo $u->id; ?>'/>
			<input type='hidden' id='recipient_email' name='recipient_email' value='<?php echo $u->email; ?>'/>
			<input type='hidden' name='formtype' value='thankyou'/>
			<p>
				<input type='submit' class='button' value='Send'/>
				<a class='button closeClass' style='margin-left:100px;' href='#'>Cancel</a>
			</p>
			<span id='errortext'></span>
		</form>
	</div>
	<!-- close thankyou form -->

</div>
</div>
<div id='profile_right'>
<?php if($logged_in) { ?>
<div class='profile_item'>
	<?php if($u->id!=$logged_in_user_id) { ?>
		<?php if(isset($is_following)&&$is_following) { ?>
			<a class='btn btn-large disabled btn-primary' id='already_following'>
				<i class="icon-ok icon-white"></i>
				Following
			</a>
			<br />
			<a href='<?php echo site_url('people/unfollow/'.$u->id); ?>' class='btn btn-primary btn-large'>
				<i class ='icon-plus icon-white'></i>
				Unfollow
			</a> 
		<?php } else { ?>
			<a href='<?php echo site_url('people/follow/'.$u->id); ?>' class='btn btn-primary btn-large' id='follow_this'>
				<i class="icon-plus icon-white"></i>
				Follow
			</a>
		<?php } ?>
		
	</div>
	<!-- thank you button that triggers modal dialog form -->
	<div class='profile_item'>
		<a class='btn btn-primary btn-large jqModal' id='thankyou'>
			Thank 
		</a>
		<span class='metadata' id='thanktext'></span>
	</div>
<?php } ?>
<?php } ?>

<?php if(!empty($u->default_location->city)) { ?>
	<div class='profile_item'>
		<h3>Location</h3>
		<p>
			<?php echo $u->default_location->city.", ".$u->default_location->state; ?>
		</p>
	</div>
<?php } ?>
<?php if(!empty($gift_circle_overlap)) { ?>
	<div class='profile_item'>
		<h3>Connected through</h3>
			<?php echo UI_Results::users(array(
					"results"=>$gift_circle_overlap,
					"include" => array("offer_links"),
					'mini' => TRUE
					));  ?>
		<!-- NOTE Follow button hidden by javascript -->
	</div>
<?php } ?>


<div class='profile_item'>
	<h3>Member Since</h3>
	<p><?php echo user_date($u->created,"F jS Y");?></p>
</div>
<!-- 
<div class='profile_item'>
	<h3>Occupation</h3>
	<p><?php if(!empty($u->occupation)) { echo $u->occupation; } ?></p>
</div>
 -->

<?php if(!empty($followers)) { ?>
<div class='profile_item'>
	<h3>Followers</h3>
	<div class='thumb_grid'>
	<?php foreach($followers as $val) { ?>
	 	<a href="<?php echo site_url('people/'.$val->id); ?>" title="<?php echo $val->screen_name;?>">
	 		<img src='<?php echo $val->default_photo->thumb_url;?>' />
	 	</a>
	 <?php } ?>
	</div>
</div>
<?php } ?>

<?php if(!empty($following)) { ?>
<div class='profile_item'>
	<h3>Following</h3>
	<div class='thumb_grid'>
	<?php foreach($following as $val) { ?>
	 	<a href="<?php echo site_url('people/'.$val->id); ?>" title="<?php echo $val->screen_name;?>">
	 		<img src='<?php echo $val->default_photo->thumb_url;?>' />
	 	</a>
	 <?php } ?>
	</div>
</div>
<?php } ?>
</div>
</div><!-- close wrapper -->
<script type='text/javascript'>
$(function(){

	$('#thankyou').click(function() {
		$('.profile_pane').hide();
		$('ul.gray_toolbar li a').removeClass('active');
		$('#thankyouwrapper').show();
	});

	$('.follow').hide();
	$(".thumb_grid a").tipTip({
		delay: 0
	});
	$("ul.results_list.grid li a.result_image").tipTip({
		delay: 0
	});
	$("ul.results_list.grid").each(function(){
		$(this).children("li:last").css("margin-right",0);
	});
	$("ul.gray_toolbar li a").click(function(){
		$("ul.gray_toolbar li a").removeClass("active");
		$(this).addClass('active');
		$(".profile_pane").hide();
		$("#"+$(this).attr("rel")).show();
	});
	$(".view_all").click(function(){
		$("ul.gray_toolbar li a").removeClass("active");
		$("ul.gray_toolbar li").find("a."+$(this).attr("rel")).addClass("active");
		$(".profile_pane").hide();
		$("#"+$(this).attr("rel")).show();
	});


	var show = <?php echo $show_gallery; ?>;
	
	var slides = $('#slideshow li'),
		current = 0,
		slideshow = {width:0,height:0};
	if(show)		
	{
		$('.<?php echo $photos[0]["id"]; ?>').show();
		
		
		$('#slideshow .arrow').click(function(){
			var li            = slides.eq(current),
				canvas        = li.find('canvas'),
				nextIndex    = 0;
				console.log(slides.length);
			if(slides.length > 1)
			{
				
				// Depending on whether this is the next or previous
				// arrow, calculate the index of the next slide accordingly.
	
				if($(this).hasClass('next')){
					nextIndex = current >= slides.length-1 ? 0 : current+1;
				}
				else {
					nextIndex = current <= 0 ? slides.length-1 : current-1;
				}
		
				var next = slides.eq(nextIndex);
			
					current=nextIndex;
					next.addClass('slideActive').show();
					$("."+$(next).attr("id")).show();
					li.removeClass('slideActive').hide();
					$("."+$(li).attr("id")).hide();
			}
		});
	}
});
</script>
