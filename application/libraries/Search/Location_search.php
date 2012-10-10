<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/** Location searcher
 * Intended to reduce the number of calls made to the Google maps api
 *
 *@author Hans SChoenburg
*/

class Location_search extends Search
{
	protected $CI;

	var $clue;

	// flag for if state match was found
	var $stated = FALSE;


	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->load->library('datamapper');
	}


	public function match($options)
	{
		
		$result = new stdClass;
		
		if(!isset($options['string'])){
			return $result;
		}
		
		// first search for an exact match
		
		$L = new Location();
		$L->where('address', $options['string'])->get();
		
		if ($L->exists()) {
			
			// Shortcut to the part of $data we're interested in
			$result->address = $L->address;
			$result->latitude = $L->latitude;
			$result->longitude = $L->longitude;
			//		$object->street_address = $L->street_address;	
			//		$object->street_address .= " ".$val->long_name;

			$result->city = $L->city;
			$result->postal_code = $L->postal_code;
			$result->state = $L->state;
			$result->country = $L->country;
			return $result;			
		}
			
		
		// if the above does not work, search for a more general match

		$this->clue = $this->CI->db->escape_like_str($options['string']);

		//Check if clue is a state abbreviation, convert to full state name
		$this->check_state($this->clue);

		$this->CI->db->select('L.city, L.state, L.id, L.latitude, L.longitude')
			->from('locations AS L')
			->or_like('city', $this->clue)
			->or_like('address', $this->clue)
			->or_like('state', $this->clue)
			->limit(1);

		$match = $this->CI->db->get()->result();

		if(!empty($match)) {
			//don't get more specific than city	
			$match[0]->address = $match[0]->city.", ".$match[0]->state;
			return $match[0];
		} else {
			return $match;
		}

	}

	private function check_state($clue)
	{
		//check is user entered full name of state
		$this->load->helper('states');

		$states = $states_list();
		
		//vals are state names, keys are state abbreviations
		//convert state abbreviations into full state names for more 
		//accurate match
		foreach($states as $key => $val)
		{
			if($clue == $key)
			{
				$clue = $val;
			}
		}
		return $clue;
	}

	/**
	 * Gets a specific location by id
	 * 
	 * @param type $location_id
	 * @return database entries
	 */
	public function get($location_id)
	{
		$this->CI->db->select("L.address AS location_address,
			L.city AS location_city,
			L.state AS location_state,
			L.latitude AS location_latitude,
			L.longitude AS location_longitude,
			L.postal_code AS location_postal_code,
			L.country AS location_country
			")
			->from('locations AS L')
			->where('L.id', $location_id);

		$result = $this->CI->db->get()->result();
		return $result;
	}

}
