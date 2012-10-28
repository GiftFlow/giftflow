<div class='row'>
	<div class='span1'>
		<a href="<?php echo site_url("you");?>" class="user_image medium left">
			<img src="<?php echo $userdata['default_photo_thumb_url'];?>" alt="<?php echo $userdata['screen_name'];?>" />
		</a>
	</div>
	<div class='span2'
		<p class='nicebigtext'>Welcome <?php echo $userdata['screen_name']; ?></p>
		<ul class='homeList'>
		<?php if(!isset($userdata['bio'])) { ?>
			<li>
				<a href="<?php echo site_url('account'); ?>"><i class='icon-plus'></i>Update profile</a></p>
			</li>
		<?php } ?>
			<li>
			<a href="<?php echo site_url('account/photos'); ?>"><i class='icon-plus'></i>Upload photos</a>
			</li>
		</ul>
	</div>
	<div class='span3'>
		<ul class='homeList' id='yourOptions'>
		<?php if(!isset($userdata['bio'])) { ?>
			<li>
				<a href="<?php echo site_url('you/needs'); ?>"><i class='icon-plus'></i>Your Needs</a>
			</li>
		<?php } ?>
			<li>
			<a href="<?php echo site_url('you/gifts'); ?>"><i class='icon-plus'></i>Your Gifts</a>
			</li>
			<li>
			<a href="<?php echo site_url('you/watches'); ?>"><i class='icon-plus'></i>Your Watches</a>
			</li>
		</ul>
	</div>
	<div class='span4'>
		<p id='home_location'><?php echo $userdata['location']->address; ?></p>  <span class='metadata'>Click to change</span>
	</div>
</div>

<div class='row chunk' id='dashboard'>
		<div class='span8'>	
		<div class='row'>
			<!-- Loading Message -->
			<div class="results_loading" style="display: none;">
				<img src="<?php echo base_url();?>assets/images/loading.gif" alt="Loading" />
			</div>
		
			<div class='span6' id='homeBlog'>
				<h3>Latest from the <a href='http://blog.giftflow.org'>GiftFlow Blog</a></h3>
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

function logfeed(data) {

	$('.results_loading').hide();

	for(var i=0; i<3; i++) {

		var latest = data.entries[i];

		var blurb = latest.content.replace(/(<([^>]+)>)/ig,"");
		blurb = blurb.substring(0,150)+'... by '+latest.author;
		
		var entry = "<li><span class='entryTitle'><a href='"+latest.link+"'>"+latest.title+"</a></span><span class='entryBlurb'>  "+blurb+"</span></li>";
		$('#blogFeed').append(entry);
	}

};

function parseRSS(url, callback) {
  $.ajax({
    url: document.location.protocol + '//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=10&callback=?&q=' + encodeURIComponent(url),
    dataType: 'json',
    success: function(data) {
      callback(data.responseData.feed);
    }
  });
}

$('.results_loading').show();
parseRSS(url, logfeed);

});


</script>
