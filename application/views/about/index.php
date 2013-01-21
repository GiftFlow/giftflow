<div class='row page_wrapper'>
	<div class='span2 chunk'>
		<?php echo $menu; ?>
	</div>
	<div class='span9 chunk' id='home_blog'>
		<p class='nicebigtext mission'>
			GiftFlow's mission is to help individuals and organizations give and share goods and services with their communities by providing an online platform that is open-source and free.
		</p>

	</div>
	<div class='span9 chunk'>
		<h3>
			Latest from the <a href='http://blog.giftflow.org'>GiftFlow Blog</a>
			<div class="about_addthis addthis_toolbox addthis_32x32_style addthis_default_style">
			<a class="addthis_button_facebook_follow" addthis:userid="giftflow"></a>
			<a class="addthis_button_twitter_follow" addthis:userid="giftflow"></a>
			</div>
		</h3>

		<!-- Loading Message -->
		<div class="results_loading" style="display: none;">
			<img src="<?php echo base_url();?>assets/images/loading.gif" alt="Loading" />
		</div>

		<ul id='blog_feed'>
		</ul>
	</div>
</div>

<script type='text/javascript'>

$(function(){

	var url = 'http://blog.giftflow.org/?feed=rss2';
		//callback for blog RSS, appends posts to DOM
	function logfeed(data) {
		console.log(data);

		$('.results_loading').hide();

		for(var i=0; i<3; i++) {

			var latest = data.entries[i];

			var blurb = latest.content.replace(/(<([^>]+)>)/ig,"");
			blurb = blurb.substring(0,200);
			var published = latest.publishedDate.substring(0,14);
			
			var entry = "<li class='blog_post'><span class='entry_title'><a target='blank' href='"+latest.link+"'>"+latest.title+"</a></span><span class='entry_metadata'>posted on: "+published+"    by: "+latest.author+"</span><span class='entry_blurb'>  "+blurb+"</span></li>";
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
