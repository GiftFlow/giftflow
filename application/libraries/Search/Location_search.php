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

			if(strlen($options['string'] > 2))
			{
				$this->check_state($options);
			}

			$city = $this->CI->db->select('L.city, L.state, L.id, L.latitude, L.longitude')
				->from('locations AS L')
				->or_like('city', $this->clue)
				->or_like('address', $this->clue)
				->or_like('state', $this->clue)
				->limit(1);
			
			$match = $this->CI->db->get()->result();

			//don't get more specific than city	
			$match[0]->address = $match[0]->city;
			
			return $match[0];
		}


	}

	public function check_state ($options)
	{
		//check is user entered full name of state
		$state = $this->CI->db->select('S.state_abbr')
			->from('states AS S')
			->like('state', $options['string'])
			->get()->result();
	
		//if state match found, call fill_out with abbreviated version
		if(!empty($state[0]))
		{
			$this->clue = $state[0];
		}
	}




}
