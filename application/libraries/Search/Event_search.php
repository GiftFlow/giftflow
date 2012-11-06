<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	Reads data from Events table and generate various information feeds
 *	
 *	@author Hans Schoenburg
 * 	@package Libraries
 */
 



class Event_search extends Search
{
	/**
	*	CodeIgniter super-object
	*
	*	@var object
	*/
	protected $CI;
	
	
    //List of event types with their event_type_ids
    // Class-wide variables created for those events considered relavant

	var $transaction_completed = array();
	var $user_new = array();
	var $good_new = array();

	
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
	
	/**
         * Gets events according to options params
         * @param type $options
         * @return array 
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
		    //$this->geosearch_query($options);
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
			$empty_results = array();
			return $empty_results;
		}
	
	}
	
        /**
         *  Builds event objects with needed data
         * @return type 
         */
	function process_events()
	{
		$this->CI->load->library('Search/User_search');
		$this->CI->load->library('Search/Good_search');
		$this->CI->load->library('Search/Transaction_search');
		$this->CI->load->library('Search/Review_search');
		$this->CI->load->library('Search/Thankyou_search');

		$U = new User_search();
		$G = new Good_search();
		$R = new Review_search();
		$T = new Transaction_search();
		$TY = new Thankyou_search();
		
		//sort events by event_type
		if(!empty($this->raw_results))
		{
			
			foreach($this->raw_results as $event)
			{				
				$json_data = json_decode($event->event_data);

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
										
						if(!empty($json_data->transaction->id))
						{
							$trans = $T->get($options = array('transaction_id' => $json_data->transaction->id, 'include_reviews' => TRUE));
							$review = $R->find($options = array('transaction_id' => $json_data->transaction->id, 'include_transactions' => TRUE));
						}
						//append transaction object to transaction event
						if(!empty($trans))
						{
							$event->transaction = $trans;
							//$event->review = $review;
							$event->location = $trans->demands[0]->good->location;
							$this->events[] = $event;
						}
						break;

					case "thankyou":

						$event->thank = $TY->get(array('id' => $json_data->id));

						//Only include thankyous with a current status of accepted
						if($event->thank->status == 'accepted') 
						{
							$this->events[] = $event;
						}

						break;
				}
				
			}
		
			return $this->events;
		}
		else
		{
			//@todo error handling
			$this->events = array();
			return $this->events;
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
	

