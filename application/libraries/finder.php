<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	The Finder library is the core of GiftFlow's search functionality.
*	
*	@author Brandon Jackson
*	@package Libraries
*/
class Finder
{
	/**
	*	CodeIgniter super-object
	*	
	*	@var object
	*/
	protected $CI;
	
	// Typical search parameters
	
	/**
	*	Search keyword. The text found in the "what" search box.
	*
	*	@var string
	*/
	var $search_keyword;
		
	// Location details
	
	/**
	*	Stores location information, returned via Geo::geocode()
	*	Object properties: latitude, longitude, address, street_address, city, 
	*	state, postal_code, country
	*
	*	@var object
	*/
	var $location;
	
	/**
	*	LatLng bounds defined using search radius
	*	@var array
	*/
	var $bounds;
	
	/**
	*	List of users to search
	*	@var array
	*/
	var $users;
	
	/**
	*	Type of search
	*	@var string
	*/
	var $type = 'gift';
	
	/**
	*	Tag ID
	*	@var int
	*/
	var $tag_id;
	
	/**
	*	Category ID
	*	@var int
	*/
	var $category_id;
	
	/**
	*	Stores subquery strings which will be merged in a union
	*	@var array
	*/
	var $subquery;

	// Sorting & Pagination variables
	
	/**
	*	Default ORDER BY field
	*	@var string
	*/
	var $default_order_by_field = 'good_created';
	
	/**
	*	Default ORDER BY direction
	*	@var string
	*/
	var $default_order_by_direction = 'ASC';
	
	/**
	*	Default LIMIT size
	*	@var int
	*/
	var $default_limit_size = 10;
	
	/**
	*	Default LIMIT offset
	*	@var int
	*/
	var $default_limit_offset = 0;

	/**
	*	User-specified ORDER BY field
	*	@var string
	*/
	var $order_by_field;
	
	/**
	*	User-specified ORDER BY direction
	*	@var string
	*/
	var $order_by_direction;
	
	/**
	*	User-specified LIMIT size
	*	@var int
	*/
	var $limit_size;
	
	/**
	*	User-specified LIMIT offset
	*	@var int
	*/
	var $limit_offset;
	
	/**
	*	@var array
	*/
	var $where;

	/**
	*	Raw database result object
	*	The raw database result object from the database query is usually stored here.
	*	@var object
	*/
	var $result;
	
	/**
	*	Formatted search result object
	*	This object mimics datamapper's format. $this->result is usually
	*	converted to an object and then saved here after Factory::good().
	*	@var object
	*/
	var $object;
	
	/**
	*	Return data format
	*
	*	@var string
	*/
	var $format;
	
	public function __construct()
	{
		// Load CI super-object
		$this->CI =& get_instance();
		
		// If the request is being made via AJAX, set the output to be in JSON
		if( $this->CI->input->is_ajax_request() )
		{
			$this->format = "JSON";
		}
		
		// If not in AJAX, return an object
		else
		{
			$this->format = "object";
		}
		
		// Create default location object
		$this->location = (object) array(
			"latitude"=>NULL,
			"longitude"=>NULL,
			"address"=>NULL,
			"street_address"=>NULL,
			"city"=>NULL,
			"state"=>NULL,
			"postal_code"=>NULL,
			"country"=>NULL
		);
	}
	
	/**
	*	Gets the most recently added gifts
	*
	*	High-level search function
	*
	*	@return object
	*/
	public function query()
	{
		if( empty( $this->order_by_field ) && empty( $this->order_by_direction ) )
		{
			$this->order_by("good_created", "DESC");
		}
		$this->_set_geographic_bounds();
		$this->_basic_query();
		$this->result = $this->CI->db->get()->result();
		$this->object = Factory::good($this->result);
		return $this->object;
	}
	
	/**
	*	Gets the most recently added gifts
	*
	*	High-level search function
	*
	*	@return object
	*/
	public function latest_gifts()
	{
		$this->order_by("good_created", "DESC");
		$this->_basic_query();
		$this->result = $this->CI->db->get()->result();
		$this->object = Factory::good($this->result);
		return $this->object;
	}
	
	/**
	*	Gets the most recently added needs
	*
	*	High-level search function
	*
	*	@return object
	*/
	public function latest_needs()
	{
		$this->order_by("good_created", "DESC");
		$this->type = 'need';
		$this->_basic_query();
		$this->result = $this->CI->db->get()->result();
		$this->object = Factory::good($this->result);
		return $this->object;
	}
	
	/**
	*	 Returns array of nearby user IDs
	*/
	public function nearby_users()
	{
		$this->_set_logged_in_user_location();
		$this->_set_geographic_bounds();
				
		$query = $this->CI->db->select('U.id AS user_id ')
			->from('users AS U ') 
			->join('locations AS L ', 'L.id = U.default_location_id')
			->where('U.id != ', $this->CI->session->userdata['user_id'])
			->order_by('location_distance', 'ASC')
			->get();
		foreach( $query->result() as $key=>$val )
		{
			$this->users[] = $val->user_id;
		}
 		return $this->users;
	}
	
	/**
	*	Gets nearby gifts
	*
	*	High-level search function
	*
	*	@return object
	*/
	public function nearby_gifts()
	{
		$this->_set_logged_in_user_location();
		$this->_set_geographic_bounds();
		$this->_exclude_logged_in_user();
		$this->_basic_query();
		$this->result = $this->CI->db->get()->result();
		$this->object = Factory::good($this->result);
		return $this->object;
	}
	
	/**
	*	Searches gifts based on their tags
	*	@param string $tag	 Tag name to search by
	*	@return object/JSON
	*/
	public function tag_search ( $tag = NULL )
	{
		if(!empty($tag))
		{
			// Find the tag_id
			$tag_query = $this->CI->db->select("id")->like('name', $tag)->get('tags');
			if($tag_query->num_rows==0)
			{
				return FALSE;
			}
			$tag = $tag_query->row();
			$this->tag_id = $tag->id;
		}
		$this->result = $this->CI->db->query( $this->_tag_query() );

		return $this->_render_output();
	}
	
	/**
	*	Generates results for the find page.
	*	It uses the search keyword ($this->keyword) to search for gifts based
	*	on location, tags and titles.
	*	@return JSON
	*/
	public function geosearch()
	{
		Console::logSpeed('Finder::geosearch()');

		// @todo Exclude the logged in user from current search
	
		// Searches good titles and tags for the provided keyword
		$this->object = $this->keyword_search();

		// Render output as either JSON or an object
		$output = $this->_render_output();
		
		Console::logSpeed('end Finder::geosearch()');
		
		return $output;
	}
	
	public function find_search()
	{
		$this->object = $this->keyword_search();
		$this->_format_result();
		return $this->object;
	}
	
	public function giftflow()
	{
		Console::logSpeed('Finder::giftflow()');
		
		if(!empty($_REQUEST['source']) )
		{
			$source = $_REQUEST['source'];
		}
		else
		{
			$source = 'following';
		}
				
		if( $source == "following" )
		{
			$this->follower_search();
		}
		elseif( $source == "nearby_users" )
		{
			$this->nearby_gifts();
		}
		elseif( $source == "nearby_gifts" )
		{
			$this->nearby_gifts();
		}
		
		$output = $this->_render_output();
		
		Console::logSpeed('end Finder::giftflow()');
		
		return $output;
	}
	
	/**
	*	Searches for the goods from the users the logged in user is following.
	*
	*	This method generates an array of user IDs and then stores them in 
	*	$this->users. The actual searching is then handled by the user_search()
	*	method.
	*
	*	@return object
	*/
	public function follower_search()
	{
		Console::logSpeed('Finder::follower_search()');
		$user_id = $this->CI->session->userdata['user_id'];
		$q =$this->CI->db->select('U.id AS user_id')
			->from("users AS U ")
			->join("followings_users AS FU ", "FU.following_id=U.id")
			->where("FU.user_id", $user_id)
			->get();
		if( $q->num_rows > 0 )
		{
			foreach( $q->result() as $key=>$val )
			{
				$this->users[] = $val->user_id;
			}
			$this->CI->db->where('U.id !=', $user_id); 
			return $this->user_search();
		}
		Console::logSpeed('end Finder::follower_search()');
	}
	
	/**
	*	Searches for the goods from the users provided in $this->users.
	*	$this->users may either be an array of multiple user IDs or a single user ID.
	*
	*	@return object
	*/
	function user_search()
	{
		Console::logSpeed('Finder::user_search()');

		if( is_array( $this->users ) )
		{
			$this->CI->db->where_in('U.id', $this->users);
		}
		elseif( is_numeric( $this->users ) )
		{
			$this->CI->db->where('U.id', $this->users);
		}
		
		$this->_basic_query();
		
		$this->result = $this->CI->db->get()->result();
		
		$this->object = Factory::good($this->result);
		
		Console::logSpeed('end Finder::user_search()');
		return $this->object;
	}
	
	/**
	*	Searches based on tag and title similarity
	*
	*	$this->search_keyword is required
	*/
	public function keyword_search()
	{
		Console::logSpeed('Finder::keyword_search()');

		// Find matching Tag IDs if search keyword 3 characters long or more
		// @todo move to Tag_search library
		if(strlen($this->search_keyword)>2)
		{
			Console::logSpeed('Finder::keyword_search(): tag query...');
			
			// Load first 25 matches
			$tags = $this->CI->db->select("id")
				->from("tags")
				->like("name",$this->search_keyword)
				->limit(25)
				->get()
				->result_array();
				
			// Extract tag IDs from result
			// @todo move to get_ids() utility function
			$tag_ids = array_map( function($tag){ return $tag['id']; }, $tags);
			
			Console::logSpeed('Finder::keyword_search(): tag query...done.');
		}
				
		// Set geographic bounds, save as $this->bounds
		// the FALSE parameter ensures it doesn't update active record
		$this->_set_geographic_bounds(FALSE);
		
		// Location ON statements
		if(!empty($this->bounds))
		{
			$ON = "L.id=G.location_id AND ";
			$ON .= "L.latitude BETWEEN ".$this->bounds['latitude']['min']." AND ".$this->bounds['latitude']['max']." AND ";
			$ON .= "L.longitude BETWEEN ".$this->bounds['longitude']['min']." AND ".$this->bounds['longitude']['max'];
		}
		
		// Find by keyword search
		Console::logSpeed('Finder::keyword_search(): find by keyword query...');
		$this->CI->db->select('G.id')
			->from('goods AS G ')
			->where('G.type',$this->type);
		if(!empty($this->category_id))
		{
			$this->CI->db->where("G.category_id",$this->category_id);
		}
		
		// If search keyword set, add LIKE clause, ORDER BY G.created
		if(!empty($this->search_keyword))
		{
			if(!empty($this->bounds))
			{
				$this->CI->db->join('locations AS L ',$ON);
			}
			
			if(!empty($this->search_keyword))
			{
				$this->CI->db->like("G.title", $this->search_keyword);
			}
			
			$this->CI->db->order_by('G.created','asc')
				->limit(30);
		}
		
		// If no search parameters, return many IDs but don't sort
		// them via SQL. Instead the array will be sorted in PHP since
		// IDs have such a strong correlation to date created.
		else
		{
			if(!empty($this->bounds))
			{
				$this->CI->db->join('locations AS L FORCE INDEX(latlng) ',$ON);
			}

			$this->CI->db->limit(300);
		}
		
		// Execute Query
		$keyword_matches = $this->CI->db->get()->result_array();

		// Extract good IDs from result
		$good_ids = array_map( function($good){ return $good['id']; }, $keyword_matches);
		
		// Modify array if no search keyword
		if(empty($this->search_keyword))
		{
			// Sort IDs in descending order
			rsort($good_ids);
			
			// Return only first 30 elements
			$good_ids = array_slice($good_ids,0,30);
		}
		
		Console::logSpeed('Finder::keyword_search(): find by keyword query...done.');
		
		// If matching tags found, find Goods with those tags
		if(!empty($tag_ids))
		{
			Console::logSpeed('Finder::keyword_search(): find by tag query...');
			
			// Build query
			$this->CI->db->select('G.id')
				->from('goods AS G ')
				->join("goods_tags AS GT ","GT.good_id=G.id","left")
				->where('G.type',$this->type)
				->where_in("GT.tag_id", $tag_ids)
				->order_by('G.created','asc')
				->limit(30);
					
			if(!empty($this->category_id))
			{
				$this->CI->db->where("G.category_id",$this->category_id);
			}
				
			// Add geographic bounds if set
			if(!empty($this->bounds))
			{
				$this->CI->db->join('locations AS L ',$ON);
			}
			
			// Execute query
			$tag_matches = $this->CI->db->get()->result_array();

			// Extract good IDs
			$good_ids = array_merge($good_ids, array_map( function($good){ return $good['id']; }, $tag_matches));
			
			Console::logSpeed('Finder::keyword_search(): find by tag query...executing...done.');
		}
		
		// If no goods found, exit
		if(empty($good_ids))
		{
			return FALSE;
		}
		
		// Populate good IDs into full goods
		// @todo sort by relevance
		$this->CI->load->library('Search/Good_search');
		$result = $this->CI->good_search->find(array(
			"good_id"=>$good_ids,
			"type"=>$this->type
		));
		Console::logSpeed('Finder::keyword_search(): done.');
		return $result;
	}

	/**
	*	Saves ORDER BY clause
	*	@param string $field
	*	@param string $direction
	*/
	public function order_by( $field, $direction = 'ASC' )
	{
		$this->order_by_field = $field;
		$this->order_by_direction = $direction;
		return $this;
	}
	
	/**
	*	Sets limit information which is translated into SQL LIMIT clauses later.
	*
	*	@param int $size
	*	@param int $offset
	*/
	public function limit( $size, $offset = 0 )
	{
		$this->limit_size = $size;
		$this->limit_offset = $offset;
		return $this;
	}
	
	/**
	*	Limits search results by type
	*
	*	@param string $type
	*	@return $this
	*/
	public function where_type( $type )
	{
		$this->type = $type;
		return $this;
	}
	
	/**
	*	Adds a where clause
	*
	*	@param string $key
	*	@param string $val
	*	@return $this
	*/
	public function where( $key, $val = NULL )
	{
		$this->CI->db->_reset_select();
		if( is_array( $key ) )
		{
			$this->CI->db->where($key);
		}
		elseif( !empty($val))
		{
			$this->CI->db->where($key, $val);
		}
		else
		{
			return FALSE;
		}
		$this->where[] = substr($this->CI->db->_compile_select(),15);
		$this->CI->db->_reset_select();

		return $this;
	}
	
	public function photo_url($data, $param = 'thumb' )
	{
		Console::logSpeed('Finder::photo_url()');
		
		if( $data->photo_source == "facebook" && !empty( $data->facebook_id ) )
		{
			if($param=='thumb')
			{
				$url = "http://graph.facebook.com/".$data->facebook_id."/picture?type=square";
			}
			else
			{	
				$url = "http://graph.facebook.com/".$data->facebook_id."/picture?type=large";
			}
		}
		elseif( $data->photo_source == "gravatar" )
		{
			// @todo implement gravatar support
		}
		elseif( !empty($data->url) || ( $param=='thumb' && !empty($data->thumb_url) ) )
		{
			if($param=='thumb')
			{
				$url = $data->thumb_url;
			}
			else
			{
				$url = $data->url;
			}
			
			// Check to see if local file exists
			
			// first, generate path
			$path = $this->CI->config->item('base_path');
			if($param == "thumb" )
			{
				$path  .= "uploads/thumbnails/".basename($url);
			}
			else
			{
				$path .= "uploads/".basename($url);
			}
		 		
			// If it doesn't, use default URL
			if( !file_exists( $path ) )
			{
				$url =  base_url()."assets/images/user.png";
			}
		}
		else
		{
			$url =  base_url()."assets/images/user.png";
		}
		Console::logSpeed('Finder::photo_url(): done.');
		return $url;
	}
	/**
	*	Basic query that is run every time for both subqueries and normal queries.
	*
	*	There are several subroutines which are run that add to the active record
	*	object's methods.
	*/
	protected function _basic_query()
	{
		Console::logSpeed('Finder::_basic_query()');

		// Determine geographic boundaries
		$this->_set_geographic_bounds();
		
		// Find goods
		$this->CI->db->select("G.id AS good_id,
			G.type AS good_type,
			G.title AS good_title,
			G.description AS good_description,
			G.created AS good_created,
			L.id AS location_id,
			L.title AS location_title,
			L.address AS location_address,
			L.latitude AS location_latitude,
			L.longitude AS location_longitude,
			L.street_address AS location_street_address,
			L.city AS location_city,
			L.state AS location_state,
			L.postal_code AS location_postal_code,
			P.id AS photo_id,
			P.url AS photo_url,
			P.thumb_url AS photo_thumb_url,
			P.caption AS photo_caption,
			U.id AS user_id,
			U.email AS user_email,
			U.type AS user_type,
			U.first_name AS user_first_name,
			U.last_name AS user_last_name,
			U.screen_name AS user_screen_name,
			U.bio AS user_bio,
			U.created AS user_created,
			U.occupation AS user_occupation,
			U.photo_source AS user_photo_source,
			U.facebook_id AS user_facebook_id,
			UP.id AS user_photo_id,
			UP.url AS user_photo_url,
			UP.thumb_url AS user_photo_thumb_url,
			UP.caption AS user_photo_caption,
			C.id AS category_id,
			C.name AS category_name")
			->from("goods AS G ")
			->join("users AS U", "G.user_id = U.id")
			->join("locations AS L ", "L.id = G.location_id")
			->join("photos AS P ", "G.default_photo_id = P.id", "left")
			->join("photos AS UP ", "U.default_photo_id = UP.id", "left")
			->join("categories AS C", "C.id = G.category_id", "left")
			->where('G.type', $this->type);
			
		// Iterate through defined where conditions
		if( !empty( $this->where ) )
		{
			foreach( $this->where as $val )
			{
				$this->CI->db->where($val);
			}
		}
		
		// Sort & Paginate
		$this->_sort_and_paginate();
		
		Console::logSpeed('Finder::_basic_query(): done.');
	}
    	
	/**
	*	If the user is logged in, this function fetches their default location 
	*	and sends its information into Finder's location-related properties.
	*
	*	@return boolean
	*/
	protected function _set_logged_in_user_location()
	{
		Console::logSpeed('Finder::_set_logged_in_user_location()');
		
		if( !empty( $this->CI->session->userdata['user_id'] ))
		{
			$this->CI->load->library('datamapper');
			$this->U = new User($this->CI->session->userdata['user_id']);
			$this->U->default_location->get();
			
			// If the user has a default location, use it
			if( $this->U->default_location->exists() )
			{
				$this->location->latitude = $this->U->default_location->latitude;
				$this->location->longitude = $this->U->default_location->longitude;
				$this->location->address = $this->U->default_location->address;
				return TRUE;
			}
			// If the user doesn't have a default location, try to load the 
			// location via IP address
			else
			{
				$this->CI->load->library('geo');
				$ip = $this->CI->geo->geocode_ip();
				if(!empty($ip))
				{
					$this->location = $ip;
					return TRUE;
				}
			}
		}
		return FALSE;
	}


	/**
	*	Adds where clauses that limit results to a specific geographic area
	*
	*	Calculates bounds by determining how many degrees of latitude and 
	*	longitude the search radius encompasses. This is not a complete 
	*	solution, however, since the shape of the bounds is a square, not a 
	*	circle. Thus to be truly accurate another where clause limiting results 
	*	by the calculated distance field should be used.
	*
	*	@param boolean $SQL		Add relevant WHERE clauses to CI Active record?
	*	@return boolean
	*/
	protected function _set_geographic_bounds($SQL = TRUE)
	{
		Console::logSpeed("Finder::_set_geographic_bounds()");
		
		// Do we have a valid lat/lng pair?
		$has_coords = (!empty($this->location->latitude) && !empty($this->location->longitude));
		
		// If no lat/lng, try to find them
		if(!$has_coords)
		{
			// Get lat/lng from session userdata
			if(empty($this->location->address) && !empty($this->CI->session->userdata['location_latitude']) && !empty($this->CI->session->userdata['location_longitude']))
			{
				$this->location->latitude = $this->CI->session->userdata['location_latitude'];
				$this->location->longitude = $this->CI->session->userdata['location_longitude'];
				return $this->_set_geographic_bounds();
			}
			
			// Get lat/lng via geocoding
			elseif(!empty($this->location->address))
			{
				$this->geocode();
				return $this->_set_geographic_bounds();
			}
			
			return FALSE;
		}
		
		// If bounds aren't set, find and set them
		if( empty($this->bounds) )
		{
			$this->CI->load->library('geo');
			$this->bounds = $this->CI->geo->get_bounds($this->location->latitude, $this->location->longitude);
		}
		
		// If bounds found and $SQL is true, add SQL clauses
		if( !empty( $this->bounds ) && $SQL)
		{
			$this->CI->db->where("L.latitude BETWEEN ".$this->bounds['latitude']['min']." AND ".$this->bounds['latitude']['max']);
			$this->CI->db->where("L.longitude BETWEEN ".$this->bounds['longitude']['min']." AND ".$this->bounds['longitude']['max']);
			$this->CI->db->where("G.location_id IS NOT NULL");
			$this->CI->db->select("( 3959 * acos( cos( radians( ".$this->location->latitude." ) ) * cos( radians( L.latitude ) ) * cos( radians( L.longitude ) - radians(".$this->location->longitude.") ) + sin( radians(".$this->location->latitude.") ) * sin( radians( L.latitude ) ) ) ) AS location_distance");
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	*	Sorts and paginates search results
	*
	*	First, it checks to see if the user has input any order_by or limit
	*	information using the Finder::order_by() or Finder::limit() methods.
	*
	*	If not, then reverts to default values specified in the model's 
	*	properties
	*/
	protected function _sort_and_paginate()
	{
		if(!empty($this->order_by_field))
		{
			$this->CI->db->order_by($this->order_by_field, $this->order_by_direction);
		}
		else
		{
			$this->CI->db->order_by($this->default_order_by_field, $this->default_order_by_direction);
		}
		
		if(!empty($this->limit_size))
		{
			$this->CI->db->limit($this->limit_size, $this->limit_offset);
		}
		else
		{
			$this->CI->db->limit($this->default_limit_size, $this->default_limit_offset);
		}
	}
	
	
	/*
	*	Subqueries
	*/
	
	/**
	*	Searches by tag
	*
	*	@return string
	*/
	protected function _tag_query()
	{
		// Only proceed if tag id is already set or if a search finds a match
		if( !empty( $this->tag_id ) ||  $this->_set_tag( $this->search_keyword ) )
		{
			// Start logging
			Console::logSpeed('Finder::_tag_query()');

			// reset ActiveRecord
			$this->CI->db->_reset_select();
			
			// Run basic query
			$this->_basic_query();
			
			// Add extra SQL clauses
			$this->CI->db->join("goods_tags AS GT ", "GT.good_id=G.id")
				->join("tags AS T ", "GT.tag_id=T.id")
				->where('T.id = '.$this->tag_id);
			
			// Generate SQL statement
			$subquery = $this->CI->db->_compile_select();
			
			// re-reset ActiveRecord
			$this->CI->db->_reset_select();
			
			// Stop logging
			Console::logSpeed('Finder::_tag_query(): done.');

			return $subquery;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	*	Searches by title
	*
	*	@return string	SQL sentence
	*/
	protected function _title_query()
	{
		Console::logSpeed('Finder::_title_query()');

		// reset ActiveRecord
		$this->CI->db->_reset_select();
		
		// Run basic query
		$this->_basic_query();
		
		$this->CI->db->like("G.title", $this->search_keyword);
		$subquery = $this->CI->db->_compile_select();
		$this->CI->db->_reset_select();
		
		Console::logSpeed('Finder::_title_query(): done.');
		
		return $subquery;
	}
	
	/**
	*	Performs a union query if there are multiple subqueries.
	*/
	protected function _subquery_union()
	{
		Console::logSpeed('Finder::_subquery_union()');
		if(count($this->subquery)==2)
		{
			// In order to get the proper order_by and limit clauses,
			// we have to perform some trickery.
			
			// $this->_sort_and_paginate() sets the database active record
			// object with the proper clauses, but it has SELECT * at the
			// beginning which we don't need. So we substring that part out. Done.
			$this->CI->db->_reset_select();
			$this->_sort_and_paginate();
			$sorting = substr($this->CI->db->_compile_select(),9);
			$this->CI->db->_reset_select();
			$union = "(".$this->subquery[0]." ) UNION ( ".$this->subquery[1].")". $sorting;
			$this->query = $this->CI->db->query($union);
		}
		elseif(count($this->subquery)==1)
		{
			$this->query = $this->CI->db->query($this->subquery[0]);
		}
		Console::logSpeed('Finder::_subquery_union(): done.');

		if(!empty($this->query))
		{
			return $this->query;
		}
	}

	/*
	*	Utility functions
	*/
	
	/**
	*	Exclude logged in user
	*
	*	If the user is logged in, adds a where clause that filters out rows
	*	associated with the user.
	*/
	protected function _exclude_logged_in_user()
	{
		if( !empty( $this->CI->session->userdata['user_id'] ) )
		{
			$this->where('U.id != ', $this->CI->session->userdata['user_id']);
		}
	}

	/**
	*	Geocodes this->address into a usable location object via Geo::geocode()
	*	@return boolean
	*/
	public function geocode()
	{
		// Get Geocoded Location Data
		$this->CI->load->library('geo');
		$result = $this->CI->geo->geocode($this->location->address);
		
		if(!empty($result))
		{
			$this->location = $result;
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	*	Formats results into HTML for display on find page
	*/
	protected function _format_result()
	{
		Console::logSpeed('Finder::_format_result()');

		if(!empty($this->object))
		{
			foreach($this->object as $key=>$val)
			{
				$val->html = UI_Results::goods(array(
					"results"=>$val,
					"include"=>array("author","location"),
					"row"=>TRUE
				));
			}
		}
		else
		{
			return false;
		}
		
		Console::logSpeed('Finder::_format_result(): done.');
	}
	
	/**
	*	Takes the keyword input and tries to find a matching tag
	*
	*	@param string $tag 	Name of tag to search for
	*/
	protected function _set_tag( $tag )
	{
		Console::logSpeed("Finder::_set_tag()");
		// Find the tag_id
		$tag_query = $this->CI->db->select("id")->like('name', $tag)->get('tags');
		if($tag_query->num_rows==0)
		{
			return false;
		}
		
		$tag = $tag_query->row();
		// Set tag_id
		$this->tag_id = $tag->id;
		return true;
	}
	
	/**
	*	Renders the output of the query as either an object or JSON.
	*
	*	The type of the output depends on the $this->format field. By default,
	*	this function outputs JSON if the request was made via AJAX and it
	*	outputs an object if the request was not AJAX.
	*/
	protected function _render_output()
	{
		if( $this->format == "JSON" )
		{
			$this->_format_result();
			if( empty($this->location->latitude) || empty($this->location->longitude))
			{
				$this->_set_logged_in_user_location();
			}
			
			if(!empty($this->location))
			{
				$data['center'] = (object) $this->location;
			}
			$data['total_results'] = count($this->object);
			$data['results'] = $this->object;
			return json_encode( $data );
		}
		else
		{
			$this->_format_result();
			return $this->object;
		}
	}

}
