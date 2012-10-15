<div class='two_panels'>

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
     <span id='quick_buttons'>
     <a class='button btn' href='<?php echo site_url("you/add_good/?type=gift");?>'> Add a Gift</a>
     <a class='button btn' href='<?php echo site_url("you/add_good/?type=need"); ?>'> Add a Need</a>
      </span>
		<!-- GiftFlow module -->
			<div id='dash_map'>
			</div>
			<div id='giftflow_results'>
				<ul style='margin: 20px 0px;'>
					<li>
						<a href='#'  id='following'>
							Following
						</a>
					</li>
					<li>
						<a href='#' class="active" id='nearby_gifts'>
							Nearby Gifts
						</a>
					</li>
				</ul>
				</div>
					<?php if(!empty($giftflow)){ ?>
						<?php echo UI_Results::goods(array(
							"results"=>$giftflow,
							"mini"=>TRUE,
							"include"=>array("author","location","created")
						));?>
						
					<?php } else{ ?>
						<!-- EMPTY STATE for GIFTFLOW -->
						<p>There doesn't appear </p>
					<?php } ?>			
			</div>
			
		<!-- eof GiftFlow module -->

	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$(".tip").tipTip( { delay: 0, fadein: 0, defaultPosition: 'top' } );

	$("ul.invite_grid li").click(function(){ $(this).toggleClass('selected'); });
	$("#giftflow_results").buttonTabs( { onLoad: function() {
		var ul = $("#goods_includes_results");
		ul.empty();
		$.post("<?php echo site_url('ajax/giftflow'); ?>", { source: $(this).find('a.active').attr('id') }, function(data){
			if(data.results)
			{
				$.each(data.results, function( key, val){
					ul.append($(val.html));
				});
				generateCenteredMap( data );
			}
			
			if(!data.results) 
			{
				ul.append('<li>You do not seem to be following anyone. To stay updated on the gifts and needs of your friends, visit their profiles and click on the Follow button</li>');
			}	
		}, 'json');
	} 
		
	
	});
	$("#your_stuff_buttons").buttonTabs();
	
	// define map
	var map;
	
	// define the fluster object
	var fluster;
   		 
	// Write preloaded data
	// @todo make this reusable by storing in a view; it's used in find/index
	var dataset = { 
		<?php if(!empty($userdata['location'])) { ?>
		center: {
			address:"<?php if(!empty($userdata['location']->address)) echo $userdata['location']->address; ?>",
			latitude:"<?php if(!empty($userdata['location']->latitude)) echo $userdata['location']->latitude; ?>",
			longitude:"<?php if(!empty($userdata['location']->longitude)) echo $userdata['location']->longitude; ?>"
		},
		<?php } ?>
		results: [
		<?php if(!empty($giftflow)) { foreach($giftflow as $key=>$val){ ?>
			{
				location: { 		
					latitude:"<?php echo $val->location->latitude; ?>",
					longitude:"<?php echo $val->location->longitude;?>"
				},
				title: "<?php echo $val->title;?>"
			}<?php if($key<(count($giftflow))){ echo ","; } ?>
		<?php } } ?>
		]
	};
	
	// Process preloaded data
	if(dataset.results.length>0){
		generateCenteredMap(dataset);
	}
	else {}
	
	/**
	*	Sets the center of the map. There are 3 cases:
	*	1. center is defined in the json's center property
	*	2. location of first result is used as the center
	*	3. location is set to be new haven
	*/
	function generateCenteredMap(data){
		if(data.center != null && ( data.center.latitude != null || data.center.latitude != '' ) && ( data.center.longitude != null && data.center.longitude != '' ) ){
			var default_location = new google.maps.LatLng( data.center.latitude, data.center.longitude );
		} else if(data.results[0]!=null) {
			var default_location = new google.maps.LatLng( data.results[0].location.latitude, data.results[0].location.longitude );
		} else {
			var default_location = new google.maps.LatLng( 41, -71);
		}
		
		// Options
		var mapOptions = {
			scrollwheel: false,
     			zoom: 12,
     			center: default_location,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			navigationControlOptions:  {
				style: google.maps.NavigationControlStyle.SMALL
 			}
   		 };
   		
   		// Create new map element
		map = new google.maps.Map(document.getElementById("dash_map"), mapOptions);
		fluster = new Fluster2(map);
		
		// Set styles
		// These are the same styles as default, assignment is only for demonstration ...
		fluster.styles = {
			// This style will be used for clusters with more than 0 markers
			0: {
				image: '<?php echo base_url()."assets/images/cluster_ring_padded_medium.png"; ?>',
				textColor: '#45A841',
				fontSize: '20px',
				fontWeight: 'bold',
				width: 90,
				height: 90,
				size: 'small'
			},
			// This style will be used for clusters with more than 10 markers
			10: {
				image: '<?php echo base_url()."assets/images/cluster_ring_padded_medium.png"; ?>',
				textColor: '#45A841',
				fontSize: '20px',
				fontWeight: 'bold',
				width: 90,
				height: 90,
				size: 'medium'
			},
			20: {
				image: '<?php echo base_url()."assets/images/cluster_ring_padded_medium.png"; ?>',
				textColor: '#45A841',
				fontSize: '20px',
				fontWeight: 'bold',
				width: 90,
				height: 90,
				size: 'large'
			}
		};

		
		/*
		*	important: ensures that the event listener that is about to be created doesn't fire every time
		*	the map is reloaded
		*/
		var init = 0;
		
		/*
		*	When the tiles are finished loading, the markers are added. The getBounds function
		*	will not work properly unless this is the case. The if condition makes sure that the 
		*	event listener doesn't fire every time the map is moved or zoomed.
		*/
		google.maps.event.addListener(map, 'tilesloaded', function () {
			if(init==0){
				init =1
				// Add center marker
				if(data.center!=null)
				//	add_marker( null, null, 'Your Location', null, default_location);

				// Add gift markers
				$.each(data.results, function(key, val){
					add_marker(val.location.latitude, val.location.longitude, val.title, giftIcon);
				
					fluster.initialize();

				});		
			}
		});
	}

	function add_marker( latitude, longitude, title, icon, location_obj ){
		// If this is the center marker (which we have already defined and therefore
		// passed the object as the parameter location_obj), then we don't skip the
		// LatLng object creation process
		if(location_obj!=null){
			location = location_obj;
		} else {
			// marker location
			var location = new google.maps.LatLng( latitude, longitude);
		}
		
		// Zooms out until marker is visible
		expander( location );
		
		// Create marker object
		var marker = new google.maps.Marker({
			position: location, 
			map: map, 
			icon: icon,
			title: title
		});
		
		// If the marker isn't the center, add it to the marker clustering
		// queue.
		if( location_obj == null ){
			fluster.addMarker(marker);
		}
	}
	/*
	*	function recursively expands the bounds to include markers,
	*	while ensuring that the map is still centered on the user's location.
	*/
	function expander( location ){
		var boundaries = map.getBounds();
		var fits = boundaries.contains( location );
		if(fits == false){
			var zoom = map.getZoom();
			map.setZoom(zoom-1);
			expander(location);
		} else {
			return true;
		}
	}
	
	

});
</script>

<!-- Google Code for registration Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1013121847;
var google_conversion_language = "en";
var google_conversion_format = "1";
var google_conversion_color = "000000";
var google_conversion_label = "q5_hCJnelwIQt4aM4wM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1013121847/?label=q5_hCJnelwIQt4aM4wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
