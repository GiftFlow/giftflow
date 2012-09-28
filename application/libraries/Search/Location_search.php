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

	//flag for if state match was found
	var $stated = FALSE;


	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->load->library('geo');
	}


	public function match ($options)
	{
		if(isset($options['string']))
		{
			$this->clue = $options['string'];

			$this->check_state($options);

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


	}

	public function check_state ($options)
	{
		//check is user entered full name of state
		
		$this->CI->db->select('S.state_abbr AS state_abbr')
					->from('states AS S');

		if(strlen($options['string']) > 2) {
			$this->CI->db->like('state', $options['string']);
		} else {
			$this->CI->db->where('S.state_abbr',$options['string']);
		}
	
		$state = $this->CI->db->get()->result();
	
		//if state match found, call fill_out with abbreviated version
		if(!empty($state[0]))
		{
			$this->clue = $state[0]->state_abbr;
			$this->stated = TRUE;
		}
	}




}
