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
				<div class="addthis_toolbox addthis_32x32_style addthis_default_style">
				<a class="addthis_button_facebook_follow" addthis:userid="giftflow"></a>
				<a class="addthis_button_twitter_follow" addthis:userid="giftflow"></a>
				</div>
			</li>
			<li>
				<a href="<?php echo site_url('about'); ?>">About</a>
			</li>
			<li>
				<a href="http://giftflow.uservoice.com">Feedback Forum</a>
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
	</div>
</div>


<!-- Javascript UI Compilation -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/masterJS.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/javascript/bootstrap.min.js"></script>
<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=giftflow"></script>

<?php if(!empty($googlemaps)&&$googlemaps==TRUE){ ?>
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
	
	// logIn dropdown
	$('.dropdown-toggle').dropdown();
	$('#login-form').css('left', '-50px');
	$('.dropdown-menu').find('form').click(function (e) {
		e.stopPropagation();
	});

	$('#logged_in_dropdown').css('left', 250);

	//header location bar
	$('#header_location').tooltip({
		placement: 'bottom',
	});

	GF.Locations.initialize($('input#header_relocate'));

	GF.header_switch = function(form) {
		if(form) {
			$('#header_location_form').hide();
			$('#home_find_buttons').hide();
			$('#relocate_form').show();
		} else {
			$('#relocate_form').hide();
			$('#home_find_buttons').show();
			$('#header_location_form').show();
		}
	};


	$('#header_relocate').click(function() {
		$(this).val('');
	});

	$('#header_location').click(function() {
		GF.header_switch(1);
	});

	$('#relocate_cancel').click(function() {
		GF.header_switch(0);
		return false;
	});

	GF.relocate = function(data) {
		var updated = data.city+", "+data.state;
		$('#header_location_text').text(updated);
		$('#relocate_form').hide();
		$('#home_find_buttons').show();
		$('#header_location_form').show();
	};

	GF.process_relocate = function(locate) {
		var data = {'location' : locate};
		$.post("<?php echo site_url('ajax/relocate'); ?>", data, GF.relocate, "json");
	};
	$('#relocate').submit(function() {
		var location_string = $('input#header_relocate').val();
		console.log('submit!');
		GF.process_relocate(location_string);
		return false;
	});

	$('#relocate_button').click(function() {
		var location_string = $('input#header_relocate').val();
		console.log('WHAT'+location_string);
		GF.process_relocate(location_string);
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


	$("ul.results_list li, ul.transactions li").live("click",function(){
		window.location.href = $(this).find("a.title").attr("href");
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
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
try {
	var pageTracker = _gat._getTracker("	UA-16470536-1");
	pageTracker._trackPageview();
} catch(err) {}

  var uservoiceOptions = {
    key: 'giftflow',
    host: 'giftflow.uservoice.com', 
    forum: '50431',
    alignment: 'left',
    background_color:'#65b15b', 
    text_color: 'white',
    hover_color: '#93ff85',
    lang: 'en',
    showTab: true
  };
  function _loadUserVoice() {
    var s = document.createElement('script');
    s.src = ("https:" == document.location.protocol ? "https://" : "http://") + "uservoice.com/javascripts/widgets/tab.js";
    document.getElementsByTagName('head')[0].appendChild(s);
  }
  _loadSuper = window.onload;
  window.onload = (typeof window.onload != 'function') ? _loadUserVoice : function() { _loadSuper(); _loadUserVoice(); };
</script>

</body>
</html>
