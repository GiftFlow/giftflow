<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Geography-related Functionality
*	
*	@author Brandon Jackson
*/

class Geo
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	/**
	*	@todo move to config file
	*	@var string
	*/
	var $ipinfodb_api_key = "";	// Enter your IPInfoDB.com API key here
	
	/**
	*	Default search radius in miles
	*	@var int
	*/
	var $radius = 60;
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	/**
	*	Processes a location object, adding data from initial state. Runs two
	*	routines to do this:
	*	1) Geocodes based on "address" property
	*	2) Generates lat/lng bounds
	*	After routines, returns autofilled object.
	*
	*	@param object $location
	*	@return object $location
	*/
	public function process($location)
	{
		// Geocode if needed
		if( !empty($location->address) && (empty($location->latitude) || empty($location->longitude)) && ($geocoded == $this->geocode($location->address,$location)))
		{
			$location = $geocoded;
		}
		
		// Make sure latitude and longitude are present
		if( empty($location->latitude) || empty($location->longitude) )
		{
			// @todo throw exception here
			return $location;
		}
		
		// Set default radius
		if(empty($location->radius))
		{
			// @todo store radius in a global config file
			$location->radius = $this->radius;
		}
		
		// Get lat/lng bounds
		if(!isset($location->bounds) || empty($location->bounds))
		{
			$location->bounds = $this->get_bounds($location->latitude, $location->longitude, $location->radius);
		}
		
		return $location;
	}

	/**
	*	Convert an address into a lat/lng pair
	*	Optionally, an object (such as a DataMapper model object, can be
	*	passed to the method. It then assigns the geocoding results as
	*	properties of this object instead of creating and then returning
	*	a new object.
	*
	*	Documentation Here:
	*	http://code.google.com/apis/maps/documentation/geocoding/
	*
	*	@param string $address		Location keyword / address to encode
	*	@param object $object		Object to store results in (optional)
	*	@return object
	*/
	function geocode( $address, $object = NULL )
	{
		Console::logSpeed("Geo::geocode()");
		
		// Build URL
		$data = array ( 
			"address" => $address, 
			"sensor"=>"false", 
			"language"=>"en"
		);
		$url = "http://maps.googleapis.com/maps/api/geocode/json?";
		$url .= http_build_query($data);
		
		// Get & Decode Data
		$data = json_decode(file_get_contents($url));
		

		// If result invalid, exit
		if($data->status != "OK" || empty($data->results[0]))
		{
			return FALSE;
		}
		
		// Else start parsing
		
		// Object will store location components
		if(!is_object($object))
		{
			$object = new stdClass;
		}
		
		// Shortcut to the part of $data we're interested in
		$object->address = $data->results[0]->formatted_address;
		$object->latitude = $data->results[0]->geometry->location->lat;
		$object->longitude = $data->results[0]->geometry->location->lng;
		
		foreach($data->results[0]->address_components as $key=>$val)
		{
			if(in_array("street_number",$val->types))
			{
				$object->street_address = $val->long_name;
			}
			elseif(in_array("route",$val->types))
			{
				$object->street_address .= " ".$val->long_name;
			}
			elseif(in_array("locality",$val->types))
			{
				$object->city = $val->long_name;
			}
			elseif(in_array("postal_code",$val->types))
			{
				$object->postal_code = $val->long_name;
			}
			elseif(in_array("administrative_area_level_1",$val->types))
			{
				$object->state = $val->long_name;
			}
			elseif(in_array("country",$val->types))
			{
				$object->country = $val->long_name;
			}
		}
		
		Console::logSpeed("Geo::geocode(): done.");
		
		// Return finished object
		return $object;
	}
	
	/**
	*	Geocodes an IP address using an external IP address API.
	*	If the IP is encoded successfully, then it will populate an array and 
	*	various properties of $this.
	*
	*	API Documentation: http://www.ipinfodb.com/ip_location_api.php
	*
	*	@param string $ip
	*	@return array
	*/
	public function geocode_ip($ip = NULL)
  {
		Console::logSpeed("Geo::geocode_ip()");
	
		// Grab IP Address
		if(empty($ip))
		{
			$ip = $this->CI->input->ip_address();
		}
		
		// If localhost, manually override IP address to be one from within the
		// Yale network for testing purposes
    if($ip=="0.0.0.0" || $ip = "127.0.0.1")
		{
			$ip = "128.36.160.90";
		}
		
		// Build API Query
		$endpoint = "http://api.ipinfodb.com/v2/ip_query.php";
		$options = array(
			"ip"=>$ip,
			"key"=>$this->ipinfodb_api_key,
			"timezone"=>true
		);
		$url = $endpoint . "?" . http_build_query($options);

		// Load XML File
		@$xml = simplexml_load_file($url);
		// Parse XML into Object
		if(!empty($xml->City)&&!empty($xml->RegionName))
		{
			Console::logSpeed("Geo::geocode_ip(): saving valid result...");

			$object = (object) array(
				"address"=>$xml->City.", ".$xml->RegionName,
				"city"=> (string) $xml->City,
				"state"=> (string) $xml->RegionName,
				"postal_code"=> (string) $xml->ZipPostalCode,
				"country"=> (string) $xml->CountryName,
				"latitude"=> (double) $xml->Latitude,
				"longitude"=> (double) $xml->Longitude
			);
		}
		Console::logSpeed("Geo::geocode_ip(): done.");

		return isset($object) ? $object : FALSE;
	}

	/**
	*	Calculates a min/max latitude and longitude range that
	*	approximates a distance search
	*
	*	@param float $latitude
	*	@param float $longitude
	*	@param float $radius
	*	@return array
	*/
	public function get_bounds($latitude,$longitude, $radius = NULL)
	{
		// Set default search radius
		if(empty($radius))
		{
			$radius = $this->radius;
		}
		
		$latitude_degree = 69;
		$longitude_degree = ((3.14*cos(deg2rad($latitude))*6367449)/(180000))/1.609;
		
		// How wide is the search radius (in degrees)?
		$latitude_radius = $radius / $latitude_degree;
		$longitude_radius = $radius / $latitude_degree;
		
		$bounds = array (
			"latitude"=>array (
				"max"=> ( $latitude + $latitude_radius ),
				"min" => ( $latitude - $latitude_radius ) 
			),
			"longitude"=>array(	
				"max"=> ( $longitude + $longitude_radius ),
				"min" => ( $longitude - $longitude_radius )
			) 
		);
		
		return $bounds;
	}

	/**
	*	Calculates the distance between two points (given the latitude/longitude 
	*	of those points).
	*
	*	@param float $lat1		1st Latitude
	*	@param float $lng2		1st Longitude
	*	@param float $lat2		2nd Latitude
	*	@param float $lng2		2nd Longitude
	*	@param string $unit		Unit of Result ('m' [default], 'km')
	*/
	function distance( $lat1, $lng1, $lat2, $lng2, $unit = 'm' ) 
	{ 
		$miles = rad2deg(acos(sin(deg2rad($lat1))*sin(deg2rad($lat2))+cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($lng1-$lng2))))*69.09; 
		
		if($unit == "km") 
		{
			return ($miles * 1.609344);
		}
		else
		{
			return $miles;
		} 
	}

}
