<?php 
// GZip all of the files
ob_start ("ob_gzhandler");
header("Content-type: text/javascript; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 5 * 365 * 24 * 60 * 60 ;
$ExpStr = "Expires: " . 
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);
?>

/*
 * Fluster2 0.1.1
 * Copyright (C) 2009 Fusonic GmbH
 *
 * This file is part of Fluster2.
 *
 * Fluster2 is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * Fluster2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Creates a new Fluster to manage masses of markers in a Google Maps v3.
 *
 * @constructor
 * @param {google.maps.Map} the Google Map v3
 * @param {bool} run in debug mode or not
 */
function Fluster2(_map, _debug)
{
	// Private variables
	var map = _map;
	var projection = new Fluster2ProjectionOverlay(map);
	var me = this;
	var clusters = new Object();
	var markersLeft = new Object();
	
	// Properties
	this.debugEnabled = _debug;
	this.gridSize = 60;
	this.markers = new Array();
	this.currentZoomLevel = -1;
	this.styles = {
		0: {
			image: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m1.png',
			textColor: '#FFFFFF',
			fontSize: '14px',
			fontWeight: 'bold',
			width: 53,
			height: 52,
			size: 'small'
		},
		10: {
			image: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m2.png',
			textColor: '#FFFFFF',
			fontSize: '14px',
			fontWeight: 'bold',
			width: 56,
			height: 55,
			size: 'medium'
		},
		20: {
			image: 'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m3.png',
			textColor: '#FFFFFF',
			fontSize: '14px',
			fontWeight: 'bold',
			width: 66,
			height: 65,
			size: 'large'
		}
	};
	
	// Timeouts
	var zoomChangedTimeout = null;
	
	/**
	 * Create clusters for the current zoom level and assign markers.
	 */
	function createClusters()
	{
		var zoom = map.getZoom();
		
		if(clusters[zoom])
		{
			me.debug('Clusters for zoom level ' + zoom + ' already initialized.');
		}
		else
		{
			// Create clusters array
			var clustersThisZoomLevel = new Array();
			
			// Set cluster count
			var clusterCount = 0;
			
			// Get marker count
			var markerCount = me.markers.length;
			
			// Walk all markers
			for(var i = 0; i < markerCount; i++)
			{
				var marker = me.markers[i];
				var markerPosition = marker.getPosition();
				var done = false;
				
				// Find a cluster which contains the marker
				for(var j = clusterCount - 1; j >= 0; j--)
				{
					var cluster = clustersThisZoomLevel[j];
					if(cluster.contains(markerPosition))
					{
						cluster.addMarker(marker);
						done = true;
						break;
					}
				}
				
				if(!done)
				{
					// No cluster found, create a new one
					var cluster = new Fluster2Cluster(me, marker);
					clustersThisZoomLevel.push(cluster);
					
					// Increase cluster count
					clusterCount++;
				}
			}
			
			clusters[zoom] = clustersThisZoomLevel;
			
			me.debug('Initialized ' + clusters[zoom].length + ' clusters for zoom level ' + zoom + '.');
		}
		
		// Hide markers of previous zoom level
		if(clusters[me.currentZoomLevel])
		{
			for(var i = 0; i < clusters[me.currentZoomLevel].length; i++)
			{
				clusters[me.currentZoomLevel][i].hide();
			}
		}
		
		// Set current zoom level
		me.currentZoomLevel = zoom;
		
		// Show clusters
		showClustersInBounds();
	}
	
	/**
	 * Displays all clusters inside the current map bounds.
	 */
	function showClustersInBounds()
	{
		var mapBounds = map.getBounds();
		
		for(var i = 0; i < clusters[me.currentZoomLevel].length; i++)
		{
			var cluster = clusters[me.currentZoomLevel][i];
			if(mapBounds.contains(cluster.getPosition()))
			{
				cluster.show();
			}
		}
	}
	
	/**
	 * Callback which is executed 500ms after the map's zoom level has changed.
	 */
	this.zoomChanged = function()
	{
		window.clearInterval(zoomChangedTimeout);
		zoomChangedTimeout = window.setTimeout(createClusters, 500);
	};
	
	/**
	 * Returns the map assigned to this Fluster.
	 */
	this.getMap = function()
	{
		return map;
	};
	
	/**
	 * Returns the map projection.
	 */
	this.getProjection = function()
	{
		return projection.getP();
	};
	
	/**
	 * Prints debug messages to console if debugging is enabled.
	 */
	this.debug = function(message)
	{
		if(me.debugEnabled)
		{
			console.log('Fluster2: ' + message);
		}
	};
	
	/**
	 * Adds a marker to the Fluster.
	 */
	this.addMarker = function(_marker)
	{
		me.markers.push(_marker);
	};
	
	/**
	 * Returns the currently assigned styles.
	 */
	this.getStyles = function()
	{
		return me.styles;
	};
	
	/**
	 * Sets map event handlers and setup's the markers for the current
	 * map state.
	 */
	this.initialize = function()
	{		
		// Add event listeners
		google.maps.event.addListener(map, 'zoom_changed', this.zoomChanged);
		google.maps.event.addListener(map, 'dragend', showClustersInBounds);

		// Setup markers for the current state
		window.setTimeout(createClusters, 1000);
	};
}

/**
 * Cluster which holds one or more markers of the map.
 *
 * @constructor
 * @private
 * @param {Fluster2} the Fluster2 itself
 * @param {google.maps.Marker} the first marker
 */
function Fluster2Cluster(_fluster, _marker)
{	
	// Get properties from marker
	var markerPosition = _marker.getPosition();
	
	// Properties
	this.fluster = _fluster;
	this.markers = [];
	this.bounds = null;
	this.marker = null;
	this.lngSum = 0;
	this.latSum = 0;
	this.center = markerPosition;
	this.map = this.fluster.getMap();
	
	var me = this;
	
	// Get properties from fluster
	var projection = _fluster.getProjection();
	var gridSize = _fluster.gridSize;
	
	// Calculate bounds
	var position = projection.fromLatLngToDivPixel(markerPosition);
	var positionSW = new google.maps.Point(
		position.x - gridSize,
		position.y + gridSize
	);
	var positionNE = new google.maps.Point(
		position.x + gridSize,
		position.y - gridSize
	);
	this.bounds = new google.maps.LatLngBounds(
		projection.fromDivPixelToLatLng(positionSW),
		projection.fromDivPixelToLatLng(positionNE)
	);
	
	/**
	 * Adds a marker to the cluster.
	 */
	this.addMarker = function(_marker)
	{
		this.markers.push(_marker);
	};

	/**
	 * Shows either the only marker or a cluster marker instead.
	 */
	this.show = function()
	{
		// Show marker if there is only 1
		if(this.markers.length == 1)
		{
			this.markers[0].setMap(me.map);
		}
		else if(this.markers.length > 1)
		{
			// Hide all markers
			for(var i = 0; i < this.markers.length; i++)
			{
				this.markers[i].setMap(null);
			}
			
			// Create marker
			if(this.marker == null)
			{
				this.marker = new Fluster2ClusterMarker(this.fluster, this);
				
				if(this.fluster.debugEnabled)
				{
					google.maps.event.addListener(this.marker, 'mouseover', me.debugShowMarkers);
					google.maps.event.addListener(this.marker, 'mouseout', me.debugHideMarkers);
				}
			}
			
			// Show marker
			this.marker.show();
		}
	};
	
	/**
	 * Hides the cluster
	 */
	this.hide = function()
	{
		if(this.marker != null)
		{
			this.marker.hide();
		}
	};
	
	/**
	 * Shows all markers included by this cluster (debugging only).
	 */
	this.debugShowMarkers = function()
	{
		for(var i = 0; i < me.markers.length; i++)
		{
			me.markers[i].setVisible(true);
		}
	};
	
	/**
	 * Hides all markers included by this cluster (debugging only).
	 */
	this.debugHideMarkers = function()
	{
		for(var i = 0; i < me.markers.length; i++)
		{
			me.markers[i].setVisible(false);
		}
	};
	
	/**
	 * Returns the number of markers in this cluster.
	 */
	this.getMarkerCount = function()
	{
		return this.markers.length;
	};
	
	/**
	 * Checks if the cluster bounds contains the given position.
	 */
	this.contains = function(_position)
	{
		return me.bounds.contains(_position);
	};
	
	/**
	 * Returns the central point of this cluster's bounds.
	 */
	this.getPosition = function()
	{
		return this.center;
	};

	/**
	 * Returns this cluster's bounds.
	 */
	this.getBounds = function()
	{
		return this.bounds;
	};

	/**
	 * Return the bounds calculated on the markers in this cluster.
	 */
	this.getMarkerBounds = function()
	{
		var bounds = new google.maps.LatLngBounds(
			me.markers[0].getPosition(),
			me.markers[0].getPosition()
		);
		for(var i = 1; i < me.markers.length; i++)
		{
			bounds.extend(me.markers[i].getPosition());
		}
		return bounds;
	};
	
	// Add the first marker
	this.addMarker(_marker);
}


/**
 * A cluster marker which shows a background image and the marker count 
 * of the assigned cluster.
 *
 * @constructor
 * @private
 * @param {Fluster2} the Fluster2 itself
 * @param {Fluster2Cluster} the Fluster2Cluster assigned to this marker
 */
function Fluster2ClusterMarker(_fluster, _cluster)
{
	this.fluster = _fluster;
	this.cluster = _cluster;
	this.position = this.cluster.getPosition();
	this.markerCount = this.cluster.getMarkerCount();
	this.map = this.fluster.getMap();
	this.style = null;
	this.div = null;
	
	// Assign style
	var styles = this.fluster.getStyles();
	for(var i in styles)
	{
		if(this.markerCount > i)
		{
			this.style = styles[i];
		}
		else
		{
			break;
		}
	}
	
	// Basics
	google.maps.OverlayView.call(this);
	this.setMap(this.map);
	
	// Draw
	this.draw();
};

Fluster2ClusterMarker.prototype = new google.maps.OverlayView();

Fluster2ClusterMarker.prototype.draw = function()
{
	if(this.div == null)
	{
		var me = this;
		
		// Create div
		this.div = document.createElement('div');
		
		// Set styles
		this.div.style.position = 'absolute';
		this.div.style.width = this.style.width + 'px';
		this.div.style.height = this.style.height + 'px';
		this.div.style.lineHeight = this.style.height + 'px';
		this.div.style.background = 'transparent url("' + this.style.image + '") 50% 50% no-repeat';
		this.div.style.color = this.style.textColor;
		this.div.className += 'fluster_marker';
		this.div.className += ' fluster_marker_' + this.style.size;
		
		// Marker count
		this.div.style.textAlign = 'center';
		this.div.style.fontFamily = 'Arial, Helvetica';
		this.div.style.fontSize = this.style.fontSize;
		this.div.style.fontWeight = this.style.fontWeight;
		this.div.innerHTML = this.markerCount;
		
		// Cursor and onlick
		this.div.style.cursor = 'pointer';
		google.maps.event.addDomListener(this.div, 'click', function() {
			me.map.fitBounds(me.cluster.getMarkerBounds());
		});
		google.maps.event.addDomListener(this.div, 'mouseover', function() {
			me.style.color = '#4EE049';
		});
		
		this.getPanes().overlayLayer.appendChild(this.div);
	}
	
	// Position
	var position = this.getProjection().fromLatLngToDivPixel(this.position);
	this.div.style.left = (position.x - parseInt(this.style.width / 2)) + 'px';
	this.div.style.top = (position.y - parseInt(this.style.height / 2)) + 'px';
};

Fluster2ClusterMarker.prototype.hide = function()
{
	// Hide div
	this.div.style.display = 'none';
};

Fluster2ClusterMarker.prototype.show = function()
{
	// Show div
	this.div.style.display = 'block';
};


/**
 * An empty overlay which is used to retrieve the map projection panes.
 *
 * @constructor
 * @private
 * @param {google.maps.Map} the Google Maps v3
 */
function Fluster2ProjectionOverlay(map)
{
	google.maps.OverlayView.call(this);
	this.setMap(map);
	
	this.getP = function()
	{
		return this.getProjection();
	};
}

Fluster2ProjectionOverlay.prototype = new google.maps.OverlayView();

Fluster2ProjectionOverlay.prototype.draw = function()
{
};