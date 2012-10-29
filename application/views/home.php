<div class='row'>
	<div class='span6'>
		<p class='nicebigtext'><span class='smaller'>Welcome to GiftFlow </span><?php echo $userdata['location']->city; ?></p>
	</div>
	<div class='span4 offest4'>
		<button id='relocate_button' class='btn'>Change Location</button>
		<div style='display:none;' id ='relocate_form'>
			<form name='relocate' class='find_form' id="relocate" method="post" action="">
				<div class='input-append'>
				<input id ='location' size='16' class='input-medium' type="text"  placeholder="<?php echo $userdata['location']->city;?>" name="location" />
					<button  type='submit' class='btn btn-medium'><i class= 'icon-refresh'></i> Change</button>
				</div>
			</form>
		</div>
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


$('#relocate_button').click(function() {
	$(this).hide();
	$('#relocate_form').show();
});

$('#relocate').focusout(function() {
	$('#relocate_form').hide();
	$('#relocate_button').show();
});

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
