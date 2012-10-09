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
	}


	public function match($options)
	{
		if(!isset($options['string']))
			return null;
		
		
		// first search for an exact match
		
		$L = new Location();
		$L->where('address', $options['string'])->get();
		
		if ($L->exists()) {
			
			$object = new stdClass;
			
			// Shortcut to the part of $data we're interested in
			$object->address = $L->address;
			$object->latitude = $L->latitude;
			$object->longitude = $L->longitude;
			//		$object->street_address = $L->street_address;	
			//		$object->street_address .= " ".$val->long_name;

			$object->city = $L->city;
			$object->postal_code = $L->postal_code;
			$object->state = $L->state;
			$object->country = $L->country;
			return $object;			
		}
			
		
		// if the above does not work, search for a more general match

		$this->clue = $this->CI->db->escape_like_str($options['string']);

		$this->check_state($this->clue);

		$this->CI->db->select('L.city, L.state, L.id, L.latitude, L.longitude')
			->from('locations AS L');

		if(!$this->stated) {
			$this->CI->db->or_like('city', $this->clue)
				->or_like('address', $this->clue);
		}

		$this->CI->db->or_like('state', $this->clue);

		$this->CI->db->limit(1);

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
		
		$this->CI->db->select('S.state_abbr AS state_abbr')
					->from('states AS S');

		if(strlen($clue > 2)) {
			$this->CI->db->like('state', $clue);
		} else {
			$this->CI->db->where('S.state_abbr',$clue);
		}
	
		$state = $this->CI->db->get()->result();
	
		//if state match found, call fill_out with abbreviated version
		if(!empty($state[0]))
		{
			$this->clue = $state[0]->state_abbr;
			$this->stated = TRUE;
		}
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
