


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