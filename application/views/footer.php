	</div>
</div>
<!-- eof Main Wrapper -->

<!-- Footer -->
<div id="footer">
	<div class="wrapper">
		<ul>
			<li id="copyright">
				&copy; <?php echo date("Y"); ?> GiftFlow
			</li>
			<li>
				<a href="<?php echo site_url('about'); ?>">About</a>
			</li>
			<li>
				<a href="http://www.giftflow.uservoice.com">Feedback Forum</a>
			</li>
			<li>
				<a href="http://www.github.com/GiftFlow/GiftFlow">GitHub</a>
			</li>
			<li>
				<a href="<?php echo site_url('about/press'); ?>">Press</a>
			</li>
			<li>
				<a href="<?php echo site_url('about/contact_form'); ?>">Contact</a>
			</li>
		</ul>
				<div class="addthis_toolbox addthis_32x32_style addthis_default_style footer_addthis">
				<a class="addthis_button_facebook_follow" addthis:userid="giftflow"></a>
				<a class="addthis_button_twitter_follow" addthis:userid="giftflow"></a>
				</div>
	</div>
</div>


<!-- Javascript UI Compilation -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/masterJS.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/javascript/bootstrap.min.js"></script>

<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=giftflow"></script>

<?php if(!empty($googlemaps)&&$googlemaps==TRUE && !$localhost){ ?>
	<!-- Google Maps API -->
	<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false'></script>
	<script type='text/javascript' src="<?php echo  base_url();?>assets/javascript/fluster.php"></script>
<?php }?>

<?php if(isset($js)){ foreach($js as $val){ ?>
	<!-- Custom JavaScript Files -->
	<script type="text/javascript" src="<?php echo base_url('assets/javascript/'.$val);?>"></script>
<?php } } ?>


<script type="text/javascript">
$(function(){

		/* Redirect to link whenever results_list row clicked */
	$("ul.results_list li, ul.transactions li, .brick_wall div.brick").live("click",function(){
		if($(this).find("a.title").length >= 1 && !$(this).parent('ul').hasClass('events')) {
	       window.location.href = $(this).find("a.title").attr("href");
	   }
	 });


	<?php /**
	*	Follow user button click listener
	*	Used extensively in the people section. Included here to make it easier
	*	to maintain.
	*	@todo move to external javascript file that only loads in people section
	*/ ?>
	$(".follow").live("click",function(){ 
		var id = $(this).attr('rel');
		$.post("<?php echo site_url('people/follow/'); ?>/"+id);
		$(this).after("<div class='css_right'><i class='icon-ok'></i>  Following</div>");
		$(this).remove();
		return false;
	});
	function notify_success(){
		$.notifyBar({ 
			html: alert_success, 
			cls: 'success', 
			animationSpeed: 'normal', 
			delay: 4000,
			close: true
		});
	}
	
	function notify_error(){
		$.notifyBar({ 
			html: alert_error, 
			cls: 'error', 
			animationSpeed: 'normal', 
			delay: 4000,
			close:true
		});
	}
	
	if(alert_success){
		setTimeout(notify_success, 50);
	} else if(alert_error){
		setTimeout(notify_error, 50);
	}
});

/* Scripts for header menu items */
$('.dropdown-menu').find('form').click(function(e) {
	e.stopPropagation();
});

/*Google analytics */
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16470536-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();


/* GA event Tracking scripts */
$("#add_button").click(function(e) {
  _gaq.push(['_trackEvent',"post_menu", "open"]);
});

$("ul#add_actions li a").click(function(e) {
  var element = $(this);
  var label = $(this).attr("href");
  _gaq.push(['trackEvent', "post_menu", "choose",label]);
});

</script>

</body>
</html>
