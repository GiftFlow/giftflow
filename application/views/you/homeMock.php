<div class='row'>
	<div class='span4'>
		<a href="<?php echo site_url("you");?>" class="user_image medium left">
			<img src="<?php echo $userdata['default_photo_thumb_url'];?>" alt="<?php echo $userdata['screen_name'];?>" />
		</a>
		<p class='nicebigtext'>Welcome <?php echo $userdata['screen_name']; ?></p>
	</div>
	<div class='span6'>
		<p class='nicebigtext'><?php echo $userdata['location']->address; ?></p>  <span class='metadata'>Click to change</span>
	</div>
</div>

<div class='row chunk' id='dashboard'>
	<div class='span8'>	
		<div class='row'>
		
			<div class='span2'>

					<p>Make yourself visible</p>
						<ul style='list-style:none; margin-left:0px;'>
						<?php if(!isset($userdata['bio'])) { ?>
							<li>
								<a href="<?php site_url('account'); ?>"><i class='icon-plus'></i>Update profile</a></p>
							</li>
						<?php } ?>
							<li>
								<a href="#"><i class='icon-plus'></i>Upload photos</a>
							</li>
						</ul>
								
			</div>
			<div class='span6'>
				<h3>Latest from the <a href='http://blog.giftflow.org'>GiftFlow Blog</a></h3>
				<ul id='blogFeed'>
					<span id='post_title'></span>
					<span id='post_blurb'></span>
				</ul>
			</div>
		</div>
		<div class='row'>
			<h3>Recent Activity</h3>
			<?php echo UI_Results::events(array(
				"results" => $activity,
				"row" => FALSE,
				"mini" => TRUE,
				"border" => FALSE
			)); ?>

		</div>
		
	</div>
	
	<div class='span2 dashList'>
		<div class='row'>
		<h3>Top Users</h3>
			<?php echo UI_Results::users(array(
				"results" => $new_peeps,
				"mini" => TRUE,
				"border" => FALSE,
				"follow" => FALSE
			)); ?>
		</div>
		<div class='row'>
		<h3>Nonprofit Members</h3>
			<?php echo UI_Results::users(array(
				'results' => $nonprofits,
				"mini" => TRUE,
				"border" => FALSE,
				"follow" => FALSE
			)); ?>
		</div>
		
	</div>
	<div class='span2 dashList'>
		<h3>Recent Gifts and Needs</h3>
			<?php echo UI_Results::goods(array(
				"results" => $goods,
				"mini" => TRUE,
				"border" => FALSE,
				"home_results" => TRUE
			)); ?>
	</div>




</div>

<script type='text/javascript'>

$(function() {

var url = 'http://blog.giftflow.org/?feed=rss2';

function logfeed(data) {
	console.log(data);

	for(var i=0; i<3; i++) {

		var latest = data.entries[i];

		var blurb = latest.content.replace(/(<([^>]+)>)/ig,"");
		blurb = blurb.substring(0,120)+'... by '+latest.author;
		
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

parseRSS(url, logfeed);

});


</script>
