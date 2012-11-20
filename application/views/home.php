<div class='row'>
<!-- set header for visitor -->
<?php if(!isset($this->data['userdata']['user_id'])) { ?>
	<div class='span6'>
		<p class='nicebigtext'><span class='smaller'>Welcome to GiftFlow </span><?php echo $userdata['location']->city; ?></p>
	</div>

<?php } else { ?>

	<div class='span4'>
		<a href="<?php echo site_url("you");?>" class="user_image medium left">
			<img src="<?php echo $userdata['default_photo_thumb_url'];?>" alt="<?php echo $userdata['screen_name'];?>" />
		</a>
		<p style='margin-left: 80px;' class='nicebigtext'>Welcome <?php echo $userdata['screen_name']; ?></p>
	</div>
	<div class='span8'>
		<div class='btn-group profile_actions'>
			<?php if(!isset($userdata['bio'])) { ?>
				<a class='btn' href="<?php echo site_url('account'); ?>"><i class='icon-plus'></i>Update profile</a>
			<?php } ?>
				<a class='btn' href="<?php echo site_url('account/photos'); ?>"><i class='icon-plus'></i>Upload photos</a>
				<a class='btn' href="<?php echo site_url('you/list_goods/need'); ?>"><i class='icon-plus'></i>Your Needs</a>
				<a class='btn' href="<?php echo site_url('you/list_goods/gift'); ?>"><i class='icon-plus'></i>Your Gifts</a>
				<a class='btn' href="<?php echo site_url('you/watches'); ?>"><i class='icon-plus'></i>Your Watches</a>
		</div>
	</div>
<?php } ?>
	</div><!-- close header row -->

<div class='row' id='dashboard'>
		<div class='span6'>	
		<div class='row-fluid dash_left'>
			<div class='span12 chunk' id='dashBlog'>
				<h3>Latest from the <a href='http://blog.giftflow.org'>GiftFlow Blog</a></h3>

				<!-- Loading Message -->
				<div class="results_loading" style="display: none;">
					<img src="<?php echo base_url();?>assets/images/loading.gif" alt="Loading" />
				</div>
		
				<ul id='blogFeed'>
					<span id='post_title'></span>
					<span id='post_blurb'></span>
				</ul>
			</div>
		</div>
		
		<?php if(!empty($featured->id)) { ?>
		<div class='row-fluid dash_left' id='dashFeatured'>
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
			
		<div class='row-fluid dash_left'>
<?php if(!$logged_in) { ?>
		<div class='span6 dashList chunk'>
		<h3>Nonprofits</h3>
			<?php echo UI_Results::users(array(
				'results' => $nonprofits,
				"mini" => TRUE,
				"border" => FALSE,
				"follow" => FALSE,
				"home_results" => TRUE
			)); ?>
		</div>
		<div class='span6 dashList chunk'>
		<h3>Gifts + Needs</h3>
			<?php echo UI_Results::goods(array(
				"results" => $goods,
				"mini" => TRUE,
				"border" => FALSE,
				"home_results" => TRUE
			)); ?>
		</div>
	<?php } else { ?>
		
		<div class='span6 dashList chunk'>
		<h3>Following</h3>
			<?php echo UI_Results::users(array(
				'results' => $following,
				"mini" => TRUE,
				"border" => FALSE,
				"follow" => FALSE,
				"home_results" => TRUE
			)); ?>
		</div>
		<div class='span6 dashList chunk'>
		<h3>Your GiftFlow</h3>
		<span class='minidata'>(Posted by those you follow)</span>
			<?php echo UI_Results::goods(array(
				"results" => $following_goods,
				"mini" => TRUE,
				"border" => FALSE,
				"home_results" => TRUE
			)); ?>
		</div>
	<?php } ?>
	</div>
	</div>
	
	<div class='span5 chunk dashList' id='dash_activity'>
			<h3>Recent Activity</h3>
			<?php echo UI_Results::events(array(
				"results" => $activity,
				"row" => FALSE,
				"mini" => TRUE,
				"border" => FALSE
			)); ?>

</div>
</div>

<script type='text/javascript'>

$(function() {

	var url = 'http://blog.giftflow.org/?feed=rss2';

		//callback for blog RSS, appends posts to DOM
	function logfeed(data) {

		$('.results_loading').hide();

		for(var i=0; i<3; i++) {

			var latest = data.entries[i];

			var blurb = latest.content.replace(/(<([^>]+)>)/ig,"");
			blurb = blurb.substring(0,150)+'... by '+latest.author;
			
			var entry = "<li><span class='entryTitle'><a target='blank' href='"+latest.link+"'>"+latest.title+"</a></span><span class='entryBlurb'>  "+blurb+"</span></li>";
			$('#blogFeed').append(entry);
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
