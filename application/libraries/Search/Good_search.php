<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Good model for select operations
*	
*	@author Brandon Jackson
*	@package Search
*/

class Good_search extends Search
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	var $good_id;
		
	/**
	*	Constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	
	/**
	*	Returns simple list of good ids which meet criteria
	*	@param array $options
	*	@param return array
	*/
	function find_id_list($options = array())
	{
		$options["id_search"] = TRUE;
		return $this->find($options);
	}

	function find($options = array())
	{
		Console::logSpeed("Good_search::find()");
		
		// Build $options object using defaults
		$default_options = array(
			"good_id"=>NULL,
			"type"=>NULL,
			"category_id"=>NULL,
			"order_by"=>"G.created",
			"sort"=>"ASC",
			"limit"=>100,
			"offset"=>0,
			"include_tags"=>FALSE,
			"include_photos"=>FALSE,
			"count_transactions"=>FALSE,
			"radius"=>300,
			"user_id"=>NULL,
			"id_search"=>FALSE,
			"keyword"=>""
		);
		
		$default_like_options = array(
			"title"=>NULL,
			"description"=>NULL,
		);
		$options = (object) array_merge(
			$default_like_options, 
			$default_options, 
			$options
		);	
		
		// If keyword option set, reroute to find_by_keyword()
		if(!empty($options->keyword))
		{
			return $this->find_by_keyword((array) $options);
		}
		
		// If good_id list not already provided, run initial searching query
		// before loading full resultset
		elseif(empty($options->good_id) && !$options->id_search)
		{
			$options->good_id = $this->find_id_list((array) $options);

			if(empty($options->good_id))
			{
				// No results found
				return FALSE;
			}
		}
		
		// Assemble basic SELECT query
		if(!$options->id_search)
		{
			$this->_basic_query();
		}
		else
		{
			$this->_basic_id_search_query();
		}
		
			// Include related tags
			if($options->include_tags)
			{
				$this->CI->db->select('GROUP_CONCAT(T.name) AS tags_list')
					->join('goods_tags AS GT','G.id = GT.good_id','left')
					->join('tags AS T','GT.tag_id = T.id','left');
			}
			
			// Include related photos
			if($options->include_photos)
			{
				//default photo included in basic_query
			}
						
			// Filter by good_id
			if(!empty($options->good_id))
			{
				$this->CI->db->where_in('G.id',$options->good_id);
			}
			
			// Set WHERE G.type clause, TYPE IS SINGULAR (Gift,Need)
			if(!empty($options->type))
			{
				$this->CI->db->where('G.type',$options->type);
			}
			if(!empty($options->user_id))
			{
				$this->CI->db->where_in('G.user_id',$options->user_id);
			}
			
			// Filter by status
			if(!empty($options->status))
			{
				$this->CI->db->where("G.status",$options->status);
			}
			

			// Filter by category_id
			if(!empty($options->category_id))
			{
				$this->CI->db->where_in("G.category_id",$options->category_id);
			}
			if((!empty($options->location->latitude) || !empty($options->location->longitude) || !empty($options->location->address))||!empty($options->location))
			{
				// If running full search, include all location-related fields
				// in the select clause
				if(!$options->id_search)
				{
					$this->_join_locations("inner");
				}
				
				// If running ID search, only added JOIN clause
				else
				{
					// Force use of geospatial index if no other useful
					// WHERE clauses
					if(empty($options->category_id) && empty($options->user_id))
					{
						$this->CI->db->join("locations AS L FORCE INDEX (latlng) ","G.location_id = L.id");
					}
					else
					{
						$this->CI->db->join("locations AS L ","G.location_id = L.id");
					}
				}
				
			}
			// Else simply include location for those who have it
			else
			{
				$this->_join_locations("left");
			}
			
			if(!empty($options->location))
				{
					$this->_geosearch_clauses($options->location);
				}
			$this->CI->db->order_by($options->order_by, $options->sort);

			
			// Execute query
			Console::logSpeed("Good_search::find(): executing...");
			
			// Get full results
			if(!$options->id_search)
			{	
				// Set ORDER BY
				
				// Set LIMIT
				$this->CI->db->limit($options->limit, $options->offset);

				$result = $this->CI->db->get()->result();
				// Hydrate & return results
				Console::logSpeed("Good_search::find(): ".count($result)." records found. done.");
				
				// Count total transactions
				if($options->count_transactions)
				{
					// Load transaction search library
					$this->CI->load->library('Search/Transaction_search');
					$TS = new Transaction_search;
	
					// Loop over each good, search for transactions and count them				
					foreach($result as $key=>$val)
					{
						$Transactions = $TS->find_by_good(array(
							"good_id"=>$val->good_id
						));
						$result[$key]->transaction_count = count($Transactions);
					}
				}
				
				$raw_result = Factory::good($result);
				return $raw_result;
				
			}
			
			// Get simple ID search results
			else
			{
				$this->CI->db->limit($options->limit, $options->offset);
				$result = $this->CI->db->get()->result_array();
				$good_ids = array_map( function($good){ return $good['id']; }, $result);

				return $good_ids;
			}
		}
	

	/**
	*	Retrieve single Good object
	*	@param array $options
	*/
	public function get($options)
	{
		Console::logSpeed("Good_search::get()");
		
		$options['limit'] = 1;
		
		$result = $this->find($options);

		// Return only first item in this array
		if(count($result)>0)
		{
			return $result[0];
		}
	}
	
	function find_by_keyword($options)
	{
		$default_options = array(
			"keyword"=>"",
			"category_id"=>NULL,
			"location"=>NULL,
			"order_by"=>"created", // or "distance"
			"sort"=>"ASC",
			"offset"=>0,
			"limit"=>100
		);
		$options = (object) array_merge($default_options, $options);
		
		// Find matching Tag IDs if search keyword 3 characters long or more
		// @todo move to Tag_search library
		if(strlen($options->keyword)>2)
		{
			Console::logSpeed('Good_search::find_by_keyword(): finding matching tags.');
			
			// Load first 25 matches, extract their IDs
			$tags = $this->CI->db->select("id")
				->from("tags")
				->like("name",$options->keyword)
				->limit(100)
				->get()
				->result_array();
			// @todo move to get_ids() utility function
			$tag_ids = array_map( function($tag){ return $tag['id']; }, $tags);
		}
		
		// Process location data one time only
		$this->CI->load->library('geo');
		$options->location = $this->CI->geo->process($options->location);

		$queries = array(
			"keyword"=>"",
			"tag"=>""
		);

		foreach($queries as $query_type=>$sql)
		{
			Console::logSpeed('Good_search::find_by_keyword(): assembling '.$query_type.' query');
			
			// Perform subquery-specific logic first so that we can skip
			// building tag queries if no matches were found.
			if($query_type=="keyword")
			{
				//Notice the extra bracket added before G.title - used to group the like or_like clauses 
				$this->CI->db->where(sprintf("( G.title LIKE '%s' OR 
												G.description LIKE '%s')",
												$options->keyword,
												$options->keyword));
			}
			elseif($query_type=="tag")
			{
				// If no matching tags found, abort
				if(empty($tag_ids))
				{
					break;
				}

				$this->CI->db->join("goods_tags AS GT ","GT.good_id=G.id", "left")
					->where_in("GT.tag_id",$tag_ids);
			}
			
			$this->CI->db->select("G.id, G.created")
				->from("goods as G");
			
			
			if(!empty($options->type))
			{
				$this->CI->db->where("G.type",$options->type);
			}
			
			if(!empty($options->category_id))
			{
				$this->CI->db->where("G.category_id",$options->category_id);
			}
			
		//	if(!empty($options->location->bounds))
		//	{
		//		$this->CI->db->join("locations AS L ","G.location_id = L.id");
		//		$this->_geosearch_clauses($options->location);
		//	}
						
			$queries[$query_type] = $this->CI->db->_compile_select();
			$this->CI->db->_reset_select();
		}
		
		// Set field to order by
	//	$order_by = ($options->order_by=="distance") ? "location_distance" : "created";
		
		// Build combined SQL Query
		
		$SQL = $queries["keyword"];
		
		if(!empty($queries["tag"]))
		{
			$SQL .= " UNION ".$queries["tag"];
		}
		//$SQL .= " ORDER BY ".$order_by." ".$options->sort;
		$SQL .= " LIMIT ".$options->limit.' ';
	
		// Perform first SQL query to get list of IDs
		Console::logSpeed('Good_search::find_by_keyword(): searching for matching Good IDs...');
		$keyword_matches = $this->CI->db->query($SQL)->result_array();
		
		// Extract good IDs from result
		$good_ids = array_map( function($good){ return $good['id']; }, $keyword_matches);
				
		Console::logSpeed('Good_search::find_by_keyword(): ID list generated, fetching full resultset.');
		
		if(empty($good_ids))
		{
			return FALSE;
		}
		
		$options_array = get_object_vars($options);
	
		$options_array['good_id'] = $good_ids;
		$options_array['keyword'] = '';
		$options_array['id_search'] = FALSE;
		
		
		$results = $this->find($options_array);
		
		Console::logSpeed("Good_search::find_by_keyword(): done.");
		
		return $results;
		
	}
	
	/**
	*	Find new goods
	*	
	*	@param array $options
	*	@param string $options['type']
	*	@param int $options['limit']
	*	@return array
	*/
	public function latest_goods($options)
	{
		Console::logSpeed("Good_search::latest_goods()");
		
		// Build $options object using defaults
		$default_options = array(
			"type"=>"gift",
			"limit"=>20,
		);
		$options = (object) array_merge($default_options,$options);	

		$result = $this->CI->db
			->select('G.id')
			->from('goods AS G')
			->where('G.type',$options->type)
			->order_by('G.created','desc')
			->limit($options->limit)
			->get()
			->result_array();
		$good_ids = array_map( function($good){ return $good['id']; }, $result);
		
		return $this->find(array(
			"good_id"=>$good_ids,
			"order_by"=>"G.created",
			"sort"=>"desc"
		));
	}
	
	/**
	*	@deprecated
	*	Use Transaction_search::find_by_good() instead
	*/
	public function transactions($status=array("active","pending"), $user_id = NULL)
	{
		$this->CI->db->select('D.id AS demand_id, 
			D.type AS demand_type, 
			DU.id AS demander_id,
			DU.screen_name AS demander_screen_name,
			D.good_id, 
			T.id AS transaction_id,
			T.status AS transaction_status,
			T.created AS transaction_created')
			->from('demands AS D ')
			->join('transactions AS T ','T.id=D.transaction_id')
			->join('transactions_users AS TU ','T.id=TU.transaction_id')
			->join('users AS DU ','D.user_id=DU.id')
			->where('D.good_id',$this->good_id)
			->where_in('T.status',$status)
			->group_by('D.id');
		
		// Add optional user filter
		if(!empty($user_id))
		{
			$this->CI->db->where('D.user_id',$user_id);
		}
		
		$query = $this->CI->db->get();
		return $query->result();
	}
	
	public function pending_transactions($user_id=NULL)
	{
		return $this->transactions(array("pending"), $user_id);
	}
	
	public function active_transactions($user_id=NULL)
	{
		return $this->transactions(array("active"), $user_id);
	}
	
	public function declined_transactions($user_id=NULL)
	{
		return $this->transactions(array("declined"), $user_id);
	}
	
	public function completed_transactions($user_id=NULL)
	{
		return $this->transactions(array("completed"), $user_id);
	}
	public function cancelled_transactions($user_id=NULL)
	{
		return $this->transactions(array("cancelled"), $user_id);
	}
	
	/**
	*	Assembles basic SELECT and JOIN clauses related to the location
	*	components of a query.
	*
	*	@param string $type		type of join (left, right, inner, outer)
	*/
	public function _join_locations( $type = NULL )
	{
		$this->CI->db->select("L.address AS location_address,
			L.city AS location_city,
			L.state AS location_state,
			L.latitude AS location_latitude,
			L.longitude AS location_longitude,
			L.postal_code AS location_postal_code,
			L.country AS location_country
			")
			->join("locations AS L ","G.location_id = L.id",$type);
	}

	/**
	*	Assembles basic SELECT query. The resulting SQL provides
	*	a foundation for more complex queries. Conceptually it's similar
	*	to a SQL view.
	*/
	protected function _basic_query()
	{
		$this->CI->db->select("G.id AS good_id,
			G.type AS good_type,
			G.title AS good_title,
			G.description AS good_description,
			G.created AS good_created,
			G.location_id,
			G.status AS good_status,
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
			->join("photos AS P ", "G.default_photo_id = P.id", "left")
			->join("photos AS UP ", "U.default_photo_id = UP.id", "left")
			->join("categories AS C", "C.id = G.category_id", "left");
	}
	
	protected function _basic_id_search_query()
	{
		$this->CI->db->select("G.id")
			->from("goods AS G ");
	}
	
	/**
	*	Adds clauses to query which limit search to a geographic area
	*	The $location object is just the $options object from the find() method,
	*	however since the schema of its location-related data is the same 
	*	as the standard location object, we call it that here for simplicity.
	*
	*	@param object $location		Standard location object w/ radius property
	*/
	protected function _geosearch_clauses($location)
	{
		$this->CI->load->library('geo');
		
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
