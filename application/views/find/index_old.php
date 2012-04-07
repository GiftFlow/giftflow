<div id="find_top" class="clearfix">

	<!-- Heading -->
	<img src='<?php echo base_url(); ?>assets/images/headings/find.png' id="find_heading" />
	
	<!-- Search Form -->
	<form method="post" id="find_form">
	
		<div id="find_what" class="field">
			<label class="inline" for="q">
				<?php if($type=="people") { ?>
					Who?
				<?php } else { ?>
					What?
				<?php } ?>
			</label>
			<input name="q" type="text" id="q" value="<?php if(!empty($keyword)){ echo urldecode($keyword); } ?>" />
			
			<!-- Hidden field stores `type` of search -->
			<input name="type" type="hidden" id="find_type" value="<?php echo $type;?>" />
			
			<!-- @todo replace this button menu with radio buttons or a select dropdown for graceful degradation -->			
			<ul id="find_type_buttons" class="clearfix">
				<li>
					<a rel="gifts" <?php if($type=="gifts"){ echo 'class="active"'; } ?>>Gifts</a>
				</li>
				<li>
					<a rel="needs" <?php if($type=="needs"){ echo 'class="active"'; } ?>>Needs</a>
				</li>
				<li>
					<a rel="people" <?php if($type=="people"){ echo 'class="active"'; } ?>>People</a>
				</li>
			</ul>
		</div>
		
		<div id="find_where" class="field">
			<label class="inline" for="location">
				Where?
			</label>
			<input name="location" type="text" id="location" value='<?php if(!empty($args["location"]->address)){ echo $args["location"]->address; } ?>' />
			<div id="find_radius">
				<span id="label">Search within </span>
				<span id="value">100 miles</span>
			</div>
		</div>
		
		<div id="find_submit">
			<input type='submit' value='Find' />
		</div>
	
	</form>

</div>
<div id="find_content" class="clearfix">

	<!-- Tags -->
	<?php if($display == "tags"){ ?>
	<div id='find_tags'>
		<h2 style='font-weight: 300;'>Popular Tags</h2>
		<?php foreach($tags as $key=>$obj){?>
			<?php if($key<25){ ?>
				<a rel='<?php echo $key;?>' href='<?php echo UI::tag_url($obj->tag);?>' class='tag'>
					<?php echo $obj->tag;?>
				</a>
			<?php } ?>
		<?php } ?>
	</div>
	<?php } ?>
	
	<!-- Google Map -->
	<div id='find_map'>
	</div>
	
	<!-- Search Results -->
	<div id='find_results'>
	<div class='no_results <?php if( $display != 'no_results' ) { echo "hidden"; } ?>'>
		<h2>Oops!</h2>
		<p>No results found. You should check out some of the site's most popular tags:</p>
	<div id='find_tags'>
		<?php
		$i=0;
		/*foreach($tags as $obj) 
		{
			if ( $i<10){
				echo "<a rel='".$i."' href='".UI::tag_url($obj->tag);."' class='tag'>".$obj->tag."</a>";
			}
			$i++;
		}*/
		?>
	</div>

	</div>
	<?php 		
	if( $display == 'results' )
	{
		echo "<ul class='results_list'>";
		foreach($results as $obj)
		{ 
			echo $obj->html;  
		} 
		echo "</ul>";
	}
	else
	{
		echo "<ul class='results_list' style='display: none;'></ul>";
	}
	?>
	</div>
</div>
<script type='text/javascript'>
$(function(){

	$("input:submit").button();
	
	$("label.inline").inFieldLabels();


	// define map
	var map;
	
	// define the fluster object
	var fluster;

	
	// define location of gift icon
	var giftIcon = '<?php echo base_url(); ?>assets/images/icon_gift.png';
   		 
	// Write preloaded data
	// @todo make this reusable by storing in a view; it's used in you/index
	var dataset = { 
		<?php if(!empty($userdata['location'])) { ?>
		center: {
			address:"<?php if(!empty($userdata['location']->address)) echo $userdata['location']->address; ?>",
			latitude:"<?php if(!empty($userdata['location']->latitude)) echo $userdata['location']->latitude; ?>",
			longitude:"<?php if(!empty($userdata['location']->longitude)) echo $userdata['location']->longitude; ?>"
		},
		<?php } ?>
		results: [
		<?php if(!empty($results)) { foreach($results as $key=>$val){ ?>
			{
				location: { 		
					latitude:"<?php echo $val->location->latitude; ?>",
					longitude:"<?php echo $val->location->longitude;?>"
				},
				title: "<?php echo $val->title;?>"
			}<?php if($key<(count($results))){ echo ","; } ?>
		<?php } } ?>
		]
	};
	
	var map = (function(){
		var api = {};
		
		return api;
	}());
	
	// Process preloaded data
	if(dataset.results.length>0){
		generateCenteredMap(dataset);
	} else {
	
	}
	
	var latestQuery;
	
	function updateType(type){
	
		// Update hidden input value
		$("#find_type").val(type);
		
		// Update search label
		if(type=="people"){
			$("#find_what label").text("Who?");
		} else {
			$("#find_what label").text("What?");
		}
	}
	
	function updateForm(data){
		$("#find_what input").val(data.q);
		updateType(data.type);
		$("#find_where input").val(data.location);
		
	}
	
	$(window).bind( 'hashchange', function(e) {
		var merged = $.extend($.deparam.querystring(),$.deparam.fragment());
		// console.log("hashchange event fired");
		if(latestQuery!=location.hash){
			// console.log("new hash detected, reloading with this data:");
			// console.log(merged);
			updateForm(merged);
			$("#find_form").ajaxSubmit(formOptions);
		}
	});
	function addHash(array){
		var hash = $.param(array);
		// console.log("addHash(): new hash: "+hash);
		window.location.hash = hash;
		latestQuery = window.location.hash
	}

	// Ajaxify the form
	var formOptions = {
		beforeSubmit: addHash,
		success: processAjax,
		dataType: 'json'
	};
	$("#find_form").ajaxForm(formOptions);
	
	$("ul#find_type_buttons li a").click(function(){
		$("ul#find_type_buttons li a").removeClass("active");
		$(this).addClass("active");
		updateType($(this).attr("rel"));
		$("#find_form").ajaxSubmit(formOptions);
	});
	
	/*
	*	Process the AJAX data returned by the form.
	*/
	function processAjax(data){
		if(data.results[0]!=null){
			// Display search results
			$("#find_tags").hide();
			$(".results_list").empty().show();
			$.each(data.results, function( key, val){
				$(".results_list").append($(val.html));
			});
			paginate();
			generateCenteredMap( data );
		} else {
			$("ul.simplePagerNav").remove();
			generateCenteredMap( data );
			$(".results_list").hide();
			$(".no_results").show();
		}
	}
	function generateCenteredMap(data){
		$("#find_map").show();
		$(".no_results").hide();
		
		/*
		*	Sets the center of the map. There are 3 cases.
		*	1. center is defined in the json's center property
		*	2. location of first result is used as the center
		*	3. location is set to be new haven
		*/
		if(data.center!=null){
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
		map = new google.maps.Map(document.getElementById("find_map"), mapOptions);
		fluster = new Fluster2(map);
		
		// Set styles
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
		google.maps.event.addListener(map, 'tilesloaded', function (){
			if(init==0){
				init = 1;
				
				// Add center marker
				if(data.center!=null){
					add_marker({
						title: 'Your Location',
						location_obj: default_location
					});
				}

				// Add gift markers
				$.each(data.results, function(key, val){
				
					// Add marker
					add_marker({
						latitude: val.location.latitude,
						longitude: val.location.longitude,
						title: val.title,
						icon: giftIcon
					});

					fluster.initialize();
				});		
			}
		});
	}

	/**
	*	Add marker to map
	*
	*	@param options
	*	@arg float latitude
	*	@arg float longitude
	*	@arg string title
	*	@arg object icon
	*	@arg object location_obj
	*/
	function add_marker(options){

		// If this is the center marker (which we have already defined and 
		// therefore passed the object as the parameter location_obj), then 
		// we don't skip the LatLng object creation process
		if(options.location_obj!=null){
			location = options.location_obj;
		} else {
			// marker location
			var location = new google.maps.LatLng(options.latitude, options.longitude);
		}
		
		// Zooms out until marker is visible
		expander( location );
		
		// Create marker object
		var marker = new google.maps.Marker({
			position: location, 
			map: map, 
			icon: options.icon,
			title: options.title
		});
		
		// If the marker isn't the center, add it to the marker clustering
		// queue.
		if( options.location_obj == null ){
			fluster.addMarker(marker);
		}

	}
	
	/*
	*	function recursively expands the bounds to include markers,
	*	while ensuring that the map is still centered on the user's location.
	*/
	function expander(location){
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
	
	<?php if($display=="no_results" && (!empty($userdata['location']->latitude) && !empty($userdata['location']->longitude))) { ?>
		generateCenteredMap(dataset);
		$(".no_results").show();
	<?php } ?>

	paginate();
	
	function paginate(){
		$("ul.simplePagerNav").remove();
		$("ul.results_list").quickPager({ pageSize: 5});
	}
});

</script>