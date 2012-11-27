<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Modeling system for select operations
*	
*	@author Brandon Jackson
*	@package Search
*/

class Search {

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;

	/**
	*	Constructor
	*/
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	
	/**
	*	Adds clauses to query which limit search to a geographic area
	*	The $location object is just the $options object from the find() method,
	*	however since the schema of its location-related data is the same 
	*	as the standard location object, we call it that here for simplicity.
	*
	*	@param object $location		Standard location object w/ radius property
	*/

	protected function geosearch_query($options)
	{
		$this->CI->load->library('geo');
		$this->CI->geo->radius = $options->radius;
		$location = $options->location;
		$location->radius = $options->radius;
		
		// Process Location object (geocodes if needed, generates bounds)
		if(!isset($location->bounds) || empty($location->bounds))
		{
			$location = $this->CI->geo->process($location);
		}
		
		// Assemble SQL Clauses
		
		// Add latitude WHERE BETWEEN clause
		$this->CI->db->where("L.latitude BETWEEN ".$location->bounds['latitude']['min']." AND ".$location->bounds['latitude']['max']);
		
		// Add longitude WHERE BETWEEN clause
		$this->CI->db->where("L.longitude BETWEEN ".$location->bounds['longitude']['min']." AND ".$location->bounds['longitude']['max']);
		
		// Add default_location_id WHERE clause
		// $this->CI->db->where("U.default_location_id IS NOT NULL");
		
		// Add location_distance SELECT clause
		$this->CI->db->select("( 3959 * acos( cos( radians( ".$location->latitude." ) ) * cos( radians( L.latitude ) ) * cos( radians( L.longitude ) - radians(".$location->longitude.") ) + sin( radians(".$location->latitude.") ) * sin( radians( L.latitude ) ) ) ) AS location_distance");
		
	}
}
