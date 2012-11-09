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
		<p class='nicebigtext'>Welcome <?php echo $userdata['screen_name']; ?></p>
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

<div class='row chunk' id='dashboard'>
		<div class='span8'>	
		<div class='row'>
			<div class='span6' id='homeBlog'>
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
		<div class='row-fluid'>
		<div class='span4 dashList'>
			<h3>Top Users</h3>
				<?php echo UI_Results::users(array(
					"results" => $new_peeps,
					"mini" => TRUE,
					"border" => FALSE,
					"follow" => FALSE
				)); ?>
		</div>
		<div class='span4 dashList'>
		<h3>Nonprofits</h3>
			<?php echo UI_Results::users(array(
				'results' => $nonprofits,
				"mini" => TRUE,
				"border" => FALSE,
				"follow" => FALSE
			)); ?>
		</div>
		<div class='span4 dashList'>
		<h3>Gifts + Needs</h3>
			<?php echo UI_Results::goods(array(
				"results" => $goods,
				"mini" => TRUE,
				"border" => FALSE,
				"home_results" => TRUE
			)); ?>
		</div>
		</div>
	</div>
	
	<div class='span4 dashList'>
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
