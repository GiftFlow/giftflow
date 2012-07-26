<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	Reads data from Events table and generate various information feeds
 *	
 *	@author Hans Schoenburg
 * 	@package Libraries
 */
 



class Event_reader
{
	/**
	*	CodeIgniter super-object
	*
	*	@var object
	*/
	protected $CI;
	
	
//	List of event types with their event_type_ids
// Class-wide variables created for those events considered relavant

	// event_type_id = 1 for transaction_new

	// 2 	transaction_completed
	var $transaction_completed = array();

	// 3 	transaction_message

	// 4 	for user_new
	var $user_new = array();
	// 5 	transaction_cancelled

	// 6 	transaction_activated

	// 7 	review_new

	//	8 	good_new
	var $good_new = array();

	//	9 	good_edited

	//	10 	following_new
	//	11 	follower_new
	//	12 	transaction_declined
	//	13 	hide_welcome
	//	14 	reset_password
	//	15 	new_password
	//	16 	email
	
	
	/*
	*	variable for holding get_events() results
	* @var array of objects
	*/
	var $raw_results;
	
	
	/*
	*	Stores all events with their attached user, good or transaction objects
	*	@var array
	*/
	var $events;
	
	
	/**
	*	Constructor function
	*/
	public function __construct()
	{
		// Load CI super object
		$this->CI =& get_instance();
		$this->CI->load->library('datamapper');
	}
	
	/*
	*	Get 20 most recent events, pass them to process_events
	*
	*/
	
	public function get_events($options = NULL)
	{
		$default_options = array(
			'event_type_id' => NULL,
			'user_id' => NULL,
			'limit' => 30,
      'order_by' => 'E.created',
      'radius' => '100'
		);

		$options = (object) array_merge(
			$default_options, 
			$options
		);	
		
		$this->basic_query();
	  if(!empty($options->location))
    {
		    //$this->_geosearch_clauses($options->location);
		}
		
		if(!empty($options->event_type_id))
		{
			$this->CI->db->where_in('E.event_type_id',$options->event_type_id);
		}
		if(!empty($options->user_id))
		{
			$this->CI->db->where('E.user_id',$options->user_id);
		}
		
		//set limit
		$this->CI->db->limit($options->limit,0);
		//set order_by
		$this->CI->db->order_by($options->order_by, 'DESC');
		
		$this->raw_results = $this->CI->db->get()->result();
		
		if(!empty($this->raw_results))
		{
			return $this->process_events();
		}
		else
		{
			return false;
		}
	
	}
	
	function process_events()
	{
		$this->CI->load->library('Search/User_search');
		$this->CI->load->library('Search/Good_search');
		$this->CI->load->library('Search/Transaction_search');
    $this->CI->load->library('Search/Review_search');
		$U = new User_search();
		$G = new Good_search();
		$R = new Review_search();
		$T = new Transaction_search();
		
		//sort events by event_type
		if(!empty($this->raw_results))
		{
			
			foreach($this->raw_results as $event)
			{				
				switch($event->event_type_title)
				{
					case "user_new":
						$user = $U->get($options = array('user_id' => $event->event_user_id));
						//append user object to user_new event
						if(!empty($user))
						{
							$event->user = $user;
							  if(isset($user->location)) 
							  {
								  $event->location = $user->location;
							  }

							$this->events[] = $event;
						}
						break;
						
					case "good_new":
						$json_data = json_decode($event->event_data);
						
						if(!empty($json_data->good_id))
						{
							$good = $G->get($options = array('good_id' => $json_data->good_id));
						}
						//append good object to good_new event
						if(!empty($good))
						{
							$event->good = $good;
							$event->location = $good->location;
							$this->events[] = $event;
						}
						break;
						
					case "transaction_completed":

						$json_data = json_decode($event->event_data);
										
						if(!empty($json_data->transaction->id))
						{
							$trans = $T->get($options = array('transaction_id' => $json_data->transaction->id, 'include_reviews' => FALSE));
							$review = $R->find($options = array('transaction_id' => $json_data->transaction->id));
						}
						//append transaction object to transaction event
						if(!empty($trans))
						{
							$event->transaction = $trans;
							$event->review = $review;
							$event->location = $trans->demands[0]->good->location;
							$this->events[] = $event;
						}
						break;
				}
				
			}
			
			//So folks, this is what we have so far -- $this->events is an array of event objects,
			// each one with it's attached good, user or transaction
			//The next step is to translate each one of these into a list element. 
			//Should I make one view or three?
			
			return $this->events;
		}
		else
		{
			//@todo error handling
			return FALSE;
		}
	
	}
	
	function basic_query()
	{
		$this->CI->db->select("E.id AS event_id,
					E.event_type_id AS event_type_id,
					E.data AS event_data,
					E.user_id AS event_user_id,
					E.transaction_id As event_transaction_id,
					E.message_id AS event_message_id,
					E.created AS event_created,
					ET.title AS event_type_title")
				->from('events AS E')
				->join('event_types AS ET','E.event_type_id = ET.id','left');
	}
			
	/** COPIED STRAIGHT FROM GOOD_SEACH
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
	
	/** COPIED STRAIGHT FROM GOOD_SEARCH
	*	Assembles basic SELECT and JOIN clauses related to the location
	*	components of a query.
	*
	*	@param string $type		type of join (left, right, inner, outer)
	*/
	public function _join_locations($id)
	{
		$this->CI->db->select("L.address AS location_address,
			L.city AS location_city,
			L.state AS location_state,
			L.latitude AS location_latitude,
			L.longitude AS location_longitude,
			L.postal_code AS location_postal_code,
			L.country AS location_country
			")
			->join("locations AS L ","$id = L.id",'left');
	}
}
	

