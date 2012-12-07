<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	User model for select operations
*	
*	@author Brandon Jackson
*	@package Search
*/

class User_search extends Search
{

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
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->load->library('Factory/User_factory');
	}
	
	/**
	*	Perform User Search
	*
	*	@param array $options					Array of configuration options
	*	@param string $options['screen_name'] 	Filter by LIKE screen name
	*	@param string $options['first_name'] 	Filter by LIKE first name
	*	@param string $options['last_name']		Filter by LIKE last_name
	*	@param string $options['bio']			Filter by LIKE bio
	*	@param string $options['occupation']	Filter by LIKE occupation
	*	@param int $options['user_id']			Filter by User ID
	*	@param string $options['email']			Filter by email
	*	@param int $options['facebook_id'] 		Filter by Facebook ID
	*	@param boolean $options['exclude_logged_in_user'] 	Excludes logged in user
	*	@param boolean $options['following_stats']	If true, populates am_following and is_follower fields
	*	@param float $optiorar'location']		Filter by location object
	*	@param string $options['order_by']		Field name to order by
	*	@param string $options['sort']			Sort order
	*	@param int $options['offset']			Offset
	*	@param int $options['limit']			Number of results to return
	*	@return object							Object from Factory::user()
	*/
	function find($options = array())
	{
		Console::logSpeed("User_search::find()");

		$default_options = array(
			"user_id"=>NULL,
			"email"=>NULL,
			"facebook_id"=>NULL,
			"exclude_logged_in_user"=>FALSE,
			"following_stats"=>TRUE,
			"location"=>NULL,
			"order_by"=>"U.created",
			"sort"=>"ASC",
			"offset"=>0,
			"limit"=>20,
			"forgotten_password_code"=>NULL,
			"include_forgotten_password_code" => FALSE,
			'include_photos' => FALSE,
			"keyword" => '',
			"type" => '',
			'radius' => 100
		);
		$options = (object) array_merge(
			$default_options, 
			$options
		);	
		
		// Assemble basic SELECT query
		$this->_basic_query();
		
		
		// Filter by user_id
		if(!empty($options->user_id))
		{
			$this->CI->db->where_in('U.id',$options->user_id);
		}
		
		// Filter by email
		if(!empty($options->email))
		{
			$this->CI->db->where_in('email',$options->email);
		}
		
		// Filter by facebook_id
		if(!empty($options->facebook_id))
		{
			$this->CI->db->where_in('facebook_id',$options->facebook_id);
		}
		//filter by forgotten_password_code
		if(!empty($options->forgotten_password_code))
		{
			$this->CI->db->where('forgotten_password_code', $options->forgotten_password_code);
		}
		//include forgotten password code
		if($options->include_forgotten_password_code)
		{
			$this->CI->db->select('U.forgotten_password_code AS user_forgotten_password_code');
		}

		// Filter text fields by WHERE LIKE
		if(!empty($options->keyword))
		{
			return $this->find_by_keyword($options);
		}	
		if(!empty($options->type))
		{
		  $this->CI->db->where('U.type',$options->type);
		}
		
		// Filter by location if lat/lng or un-geocoded address,
		// only returning users who have a location
		if(!empty($options->location))
		{
			$this->_join_locations("inner");
			$this->geosearch_query($options);
		}
		
		// Else simply include location for those who have it
		else
		{
			$this->_join_locations("left");
		}
		
		// Filter out current user
		if($options->exclude_logged_in_user)
		{
			$this->CI->db->where("U.id !=",$this->CI->session->userdata('user_id'));
		}
		
		// Filter by transactions
		if(!empty($options->transaction_id))
		{
			$this->CI->db->select('TU.transaction_id')
				->join('transactions_users AS TU ','TU.user_id = U.id')
				->where_in('TU.transaction_id',$options->transaction_id);
		}
		
		// Include stats about whether user is following this user
		if($options->following_stats && !empty($this->CI->session->userdata['user_id']))
		{
			$this->CI->db->select("Following.id IS NOT NULL AS user_am_following")
				->join("followings_users AS Following","U.id=Following.following_id AND Following.user_id = ".$this->CI->session->userdata['user_id'],"left");
			$this->CI->db->select("Follower.id IS NOT NULL AS user_is_follower")
				->join("followings_users AS Follower","U.id=Follower.user_id AND Follower.following_id = ". $this->CI->session->userdata['user_id'],"left");
		}
		
		// Set ORDER BY
		$this->CI->db->order_by($options->order_by, $options->sort);
		
		// Set LIMIT and offset
		$this->CI->db->limit($options->limit, $options->offset);
		
		// Return result
		$result = $this->CI->db->get()->result();

		// Hydrate & return results
		Console::logSpeed("User_search::find(): done.");
		$UF = new User_factory();
		return $UF->build_users($options,$result);
	}
	
	/**
	*	Returns individual user object
	*/
	function get($options = array())
	{
		Console::logSpeed("User_search::get()");
		
		$options['limit'] = 1;
		
		$result = $this->find($options);

		// Return only first item in this array
		if(count($result)>0)
		{
			return $result[0];
		}
		
		return new stdClass();
	}
		
	/**
	*	Find users followed by a specified user
	*	@param $options['user_id']
	*	@return array
	*/
	function following($options = array())
	{
		Console::logSpeed("User_search::following()");
		$this->_basic_query();
		$this->_join_locations('left');
		$result = $this->CI->db->select("FU.id IS NOT NULL AS user_am_following")
			->join('followings_users AS FU ','U.id=FU.following_id')
			->where('FU.user_id',$options['user_id'])
			->limit(10)
			->get()
			->result();

		
		Console::logSpeed("User_search::following(): done.");
		$F = new User_factory();
		return $F->build_users($options, $result);

	}
	
	/**
	*	Find users following a specified user
	*	@param $options['user_id']
	*	@return array
	*/
	function followers($options = array())
	{
		Console::logSpeed("User_search::followers()");
		$this->_basic_query();
		$result = $this->CI->db->select("FU.id IS NOT NULL AS user_am_following")
			->join('followings_users AS FU ','U.id=FU.user_id')
			->where('FU.following_id',$options['user_id'])
			->get()
			->result();
		
		Console::logSpeed("User_search::followers(): done.");
		$F = new User_factory();
		return $F->build_users($options, $result);
	}
	
	/**
	*	Returns all users who have transactions with the logged in user
	* 	@param user_id
	*	@return array
	*/
	function gift_circle($options)
	{	
		$default_options = array(
			"ids_only" => FALSE,
			'user_id' => NULL
			);
			
		$options = array_merge($default_options, $options);
		
		$result = $this->CI->db
			->select('U.id')
			->from('transactions_users AS T')
			->join('transactions AS TT','T.transaction_id = TT.id AND TT.status NOT IN ("cancelled", "disabled","declined")')
			->join('transactions_users AS TU','TU.transaction_id=TT.id AND TU.user_id!='.$options['user_id'])
			->join('users AS U','U.id=TU.user_id','left')
			->where('T.user_id',$options['user_id'])
			->get()
			->result_array();
		$user_ids = array_map( function($user){ return $user['id']; }, $result);
		
		if($options['ids_only'])
		{	
			return $user_ids;
		}
		else
		{
			return $this->find(array(
				"user_id"=>$user_ids,
				'include_location' => TRUE
			));
		}
	}
	
	/**
	*	Takes two user_ids and finds the overlap in their gift circles
	*	in other words, it looks for users that both have transacted with
	*/
	
	function gift_circle_overlap($options)
	{
		$overlap_users = array();
	
		$options_one = array("user_id" => $options['user_one'], "ids_only" => TRUE);
		$GC_one = $this->gift_circle($options_one);
		
		$options_two = array("user_id" => $options['user_two'], "ids_only" => TRUE);
		$GC_two = $this->gift_circle($options_two);
		
		$overlap_ids = array_intersect($GC_one, $GC_two);
		
		if(!empty($overlap_ids))
		{
			$overlap_users = $this->find($options = array ('user_id' => $overlap_ids));
		}
		return $overlap_users;
		
	}

	/**
	 * Seperate keyword search into seperate function to simplify filtering
	 */

	function find_by_keyword($options) 
	{
			
		// Filter text fields by WHERE LIKE
		$keywords = explode(' ',$options->keyword);
		$likewhere = '(';
		
		$i = 0;
		$len = count($keywords);
		foreach($keywords as $word) {
			$i++;
			$word = $this->CI->db->escape_like_str($word);
			$word = "'%".$word."%'";

			$likewhere .= "K.screen_name LIKE ".$word.
							" OR K.bio LIKE ".$word.
							" OR K.email LIKE ".$word.
							" OR K.occupation LIKE ".$word." ";
			if($i != $len){
				$likewhere.= ' OR ';
			}
		}
		$likewhere .= ")";
		
		$this->CI->db->select('K.id')
			->from('users AS K')
			->where($likewhere)
			->limit(100);
	
		
		$results = $this->CI->db->get()->result_array();
		$next_options = array(
			'user_id' => array_map(function($user) { return $user['id']; }, $results),
		);

		//just return empty array if no user_ids were found
		if(count($next_options['user_id']) > 0) {	
			return $this->find($next_options);
		} else {
			return $next_options['user_id'];
		}

	}

	/**
	*	Assembles basic SELECT query. The resulting SQL provides
	*	a foundation for more complex queries. Conceptually it's similar
	*	to a SQL view.
	*/
	function _basic_query()
	{
		$this->CI->db->select("U.id AS user_id,
			U.email AS user_email,
			U.screen_name AS user_screen_name,
			U.first_name AS user_first_name,
			U.last_name AS user_last_name,
			U.photo_source AS user_photo_source,
			U.default_photo_id AS user_photo_id,
			U.facebook_id AS user_facebook_id,
			U.status AS user_status,
			U.created AS user_created,
			U.bio As user_bio,
			U.url AS user_url,
			P.id AS photo_id,
			P.url AS photo_url,
			P.thumb_url AS photo_thumb_url")
			->from("users AS U ")
			->join("photos AS P ","U.default_photo_id = P.id AND U.default_photo_id IS NOT NULL","left");
	}
	/**
	*	Assembles basic SELECT and JOIN clauses related to the location
	*	components of a query.
	*
	*	@param string $type		type of join (left, right, inner, outer)
	*/
	function _join_locations( $type = NULL )
	{
		$this->CI->db->select("L.address AS location_address,
			L.city AS location_city,
			L.state AS location_state,
			L.latitude AS location_latitude,
			L.longitude AS location_longitude")
			->join("locations AS L ","U.default_location_id = L.id",$type);
	}
}
/*
Notes:

Mutual contacts SQL Query:

SELECT DISTINCT T2.user_id AS MutualContacts
FROM transactions_users as T1
JOIN transactions_users AS T2 ON T2.transaction_id = T1.transaction_id AND T2.user_id!=10
JOIN transactions_users AS T3 ON T3.user_id = T2.user_id AND T3.transaction_id!=T2.transaction_id
JOIN transactions_users as T4 ON T4.transaction_id = T3.transaction_id AND T4.user_id!=T3.user_id
WHERE T1.user_id=10 AND T4.user_id=77
*/
