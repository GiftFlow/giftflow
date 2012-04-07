<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Tag model for select operations
*	
*	@author Brandon Jackson
*	@package Search
*/

class Tag_search extends Search
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	/**
	*	@var object
	*/
	protected $location;
	
	/**
	*	@var array
	*/
	protected $bounds;
	
	/**
	*	Constructor
	*/
	public function __construct()
	{
		parent::__construct();
		
		$this->CI =& get_instance();
	}
	
	/**
	*	Gets the most popular tags for the site
	*
	*	@param array $options
	*	@return object
	*/
	public function popular_tags( $options = array() )
	{
		Console::logSpeed("Tag_search::popular_tags()");
		
		// Compile Options
		$default_options = array(
			"type"=>"gift",
			"location"=>NULL,
			"limit"=>30
		);
		$options = (object) array_merge($default_options, $options);
		
		// Set Location
		if(!empty($options->location))
		{
			$this->set_location($options->location);
		}
		
		// Basic Query
		$this->CI->db->select('T.name AS tag, 
			COUNT(G.id) AS count, 
			T.id AS tag_id')
			->from('goods_tags AS GT ')
			->join('goods AS G ', 'G.id = GT.good_id')
			->join('tags AS T ', 'T.id = GT.tag_id')
			->group_by('T.name')
			->order_by('count', 'desc')
			->limit($options->limit);
		
		// Filter by Good Type
		if(!empty($options->type))
		{
			$this->CI->db->where('G.type',$options->type);
		}

		// Filter by Location
		if(!empty($this->bounds))
		{
			$this->CI->db->join('locations AS L FORCE INDEX(latlng) ','L.id=G.location_id');
			$this->CI->db->where("L.latitude BETWEEN ".$this->bounds['latitude']['min']." AND ".$this->bounds['latitude']['max']);
			$this->CI->db->where("L.longitude BETWEEN ".$this->bounds['longitude']['min']." AND ".$this->bounds['longitude']['max']);
		}
		$query = $this->CI->db->get()->result();
		
		Console::logSpeed("Tag_search::popular_tags(): done.");
		return $query;
	}
	
	public function set_location($location)
	{
		Console::logSpeed("Tag_search::set_location()");
		$this->location = $location;
		if(!empty($this->location->latitude) && !empty($this->location->longitude))
		{
			$this->CI->load->library('geo');
			$this->bounds = $this->CI->geo->get_bounds($this->location->latitude, $this->location->longitude);
		}
	}
}