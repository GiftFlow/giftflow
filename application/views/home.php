<div class='row'>
<!-- set header for visitor -->
	<div class='span12 welcome_user'>
<?php if(isset($this->data['userdata']['user_id'])) { ?>
		<a href="<?php echo site_url('you');?>" class="user_image">
			<img src="<?php echo $userdata['default_photo_thumb_url'];?>" alt="<?php echo $userdata['screen_name'];?>" />
		</a>
<?php } ?>
		<span class='nicebigtext'><span class='smaller'>Welcome to GiftFlow </span><strong><a class='welcome_location' href='#' title='Click to change city'><?php echo $userdata['location']->city; ?></a></strong></span>
			<div class='home_location_change' style='display:none;'>
				<form name='relocate' class='find_form' id="relocate" method="post" action="<?php echo site_url('account/relocate'); ?>">
					<div class='control group'>
						<label for="welcome_relocate">New City</label>
						<input id ='welcome_relocate' size='16' class='input-medium' type="text"  placeholder="" name="header_relocation" />
					</div>
					<input type='hidden' name='relocate_redirect' value="<?php echo current_url(); ?>"/>
					<button type='submit' class='btn-small'>Submit</button>
					<button type='button' class='btn-small' title='cancel' id='cancel_change'>Cancel</button>
				</form>
			</div>
		<button title='Click to change location' id='location_change_button' class='welcome_location btn btn-small'>Change City</button>
	</div>

</div><!-- close header row -->

<div class='row' id='home_page'>
		<div class='span4'>	
		<div class='row-fluid home_left'>
			<div class='span12 chunk' id='home_blog'>
				<h3>Latest from the <a href='http://blog.giftflow.org'>GiftFlow Blog</a></h3>

				<!-- Loading Message -->
				<div class="results_loading" style="display: none;">
					<img src="<?php echo base_url();?>assets/images/loading.gif" alt="Loading" />
				</div>
		
				<ul id='blog_feed'>
					<span id='post_title'></span>
					<span id='post_blurb'></span>
				</ul>
			</div>
		</div>
			
		<?php if(!empty($featured->id)) { ?>
		<div class='row-fluid home_left' id='home_featured'>
			<div class='media span12 chunk'>	
				<!-- Image -->
				<a class='pull-left user_image' id='featured_image' href='<?php echo site_url('people/'.$featured->id); ?>'>
					<img class='media-object' src='<?php if(isset($featured->photo->url)) { echo $featured->photo->thumb_url; } else { echo $featured->default_photo->thumb_url; }?>'>
				</a>
				<div class='media-body'>

					<p>Featured User:</p>	
					<h3 class='media-heading'><a href="<?php echo site_url('people/'.$featured->id);?>">
											<?php echo $featured->screen_name; ?></a>
					</h3>
					<div class='media'> 
					<p><b>Member since:</b> <?php echo user_date($featured->created, "F jS Y"); ?></p>
						<p><b>Location:</b> <?php echo $featured->location->city.', '.$featured->location->state; ?></p>
						<p><b>Bio:</b>  <?php echo $featured->bio; ?></p>
						
						<?php if(!empty($featured->gifts)) { ?>			
							<p><b>Gifts</b>:
								<?php foreach($featured->gifts as $val) { ?>
										<a href="<?php echo site_url($val->type.'s/'.$val->id); ?>">
											<?php echo $val->title; ?>
										</a>
								<?php } ?>
							</p>
						<?php } ?>
						<?php if(!empty($featured->needs)) { ?>
							<p><b>Needs</b>:
								<?php foreach($featured->needs as $val) { ?>
										<a href="<?php echo site_url($val->type.'s/'.$val->id); ?>">
											<?php echo $val->title; ?>
										</a>
								<?php } ?>
							</p>
						<?php } ?>

					</div>
				</div>
			</div>

		</div>
		<?php } ?>
		<div class='row-fluid home_left'>
			<div class='span12 home_list chunk'>
			<span class='minidata'><?php echo $userdata['location']->city; ?>:</span>
			<h3>Gifts + Needs</h3>
			
				<?php echo UI_Results::goods(array(
					"results" => $goods,
					"size" => 'mini',
					"border" => FALSE,
				)); ?>
			</div>
		</div>
	</div>
	
	<div class='span7 chunk' id='home_activity'>
			<h3>Recent Activity</h3>
			<?php echo UI_Results::events(array(
				"results" => $activity,
				"row" => FALSE,
				"mini" => TRUE,
				"border" => FALSE
			)); ?>
	</div>

</div>
</div>

<script type='text/javascript'>

$(function() {

	$('.welcome_location').tooltip();
	$('.welcome_location').click(function() {
		$('.home_location_change').show();
		$('#location_change_button').hide();
	});
	$('#cancel_change').click(function() {
		$('.home_location_change').hide();
		$('#location_change_button').show();
	});

	GF.Locations.initialize($('#welcome_relocate'));

	var url = 'http://blog.giftflow.org/?feed=rss2';

		//callback for blog RSS, appends posts to DOM
	function logfeed(data) {

		$('.results_loading').hide();

		for(var i=0; i<3; i++) {

			var latest = data.entries[i];

			var blurb = latest.content.replace(/(<([^>]+)>)/ig,"");
			blurb = blurb.substring(0,60)+'... by '+latest.author;
			
			var entry = "<li><span class='entry_title'><a target='blank' href='"+latest.link+"'>"+latest.title+"</a></span><span class='entry_blurb'>  "+blurb+"</span></li>";
			$('#blog_feed').append(entry);
		}

	};
	//Pulls latest blog posts via RSS
	function parseRSS(url, callback) {
	  $.ajax({
		url: document.location.protocol + '//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=10&callback=?&q=' + encodeURIComponent(url),
		dataType: 'json',
		success: function(data) {
		  callback(data.responseData.feed);
		}
	  });
	}

	//On page load, show loading gif and get blog RSS feed
	$('.results_loading').show();
	parseRSS(url, logfeed);

	
});


</script>
