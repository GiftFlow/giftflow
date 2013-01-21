<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Transaction model for select operations
*	
*	@author Brandon Jackson
*	@package Search
*/

class Transaction_search extends Search
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	var $good_id;
	
	var $user_id;
	
	var $transaction_id;
	
	var $demand_id;
	
	/**
	*	Constructor
	*/
	public function __construct()
	{
		parent::__construct();
		
		$this->CI =& get_instance();
	}
	
	/**
	*	Retrieve single Transaction object
	*	@param array $options
	*	@return object
	*/
	public function get($options=array())
	{
		Console::logSpeed("Transaction_search::get()");
		
		$options['limit'] = 1;
		
		$result = $this->find($options);

		// Return only first item in this array
		if(count($result)>0)
		{
			return $result[0];
		}
	}
	
	/**
	*	Find transactions involving certain goods
	*	@param array $options
	*	@param array $options['good_id']	List of Good IDs
	*	@return array
	*/
	public function find_by_good($options = array())
	{
		$default_options = array(
			"good_id"=>NULL
		);
		$options = (object) array_merge($default_options,$options);
		
		// Query demands with matching goods
		$demands = $this->CI->db->select('transaction_id')
			->distinct()
			->from('demands')
			->where_in('good_id',$options->good_id)
			->get()
			->result();
		
		// Update options and call $this->find()
		// Add list of transaction IDs to options, remove good ID
		if(!empty($demands))
		{
			$options->transaction_id = array_map(function($demand){ return $demand->transaction_id; }, $demands);
			$options->good_id = NULL;
			return $this->find((array) $options);
		}
	}
	
	/**
	*	Find transactions
	*
	*	This method finds and populates full Transaction factory result
	*	objects from the database. To use this method, provide a list of
	*	transaction IDs in the options array. Due to the complexity of the
	*	result, complete the searching, filtering and processing needed to 
	*	build this list of transaction IDs before calling this method.
	*
	*	The one exception is with users: filtering by user id is allowed.
	*
	*	@param array $options
	*	@param array $options['transaction_id']
	*	@param array $options['transaction_status']
	*	@param array $options['user_id']
	*	@param boolean $options['include_reviews']
	*	@param int $options['limit']
	*	@return array
	*/
	public function find($options = array())
	{
		Console::logSpeed("Transaction_search::find()");
		
		// Compile Options
		$default_options = array(
			"transaction_id"=>$this->transaction_id,
			"user_id"=>NULL,
			"good_id"=>NULL,
			"order_by"=>"updated", // other option: id
			"transaction_status"=>array(),
			"include_reviews"=>TRUE,
			"include_messages"=>FALSE,
			"include_events"=>FALSE,
			"include_unread"=>TRUE,
			"limit"=>2000
		);
		$options = (object) array_merge($default_options,$options);
		// Redirect find by good queries
		if(!empty($options->good_id))
		{
			return $this->find_by_good((array) $options);
		}

		// Load required libraries, create instances
		$this->CI->load->library('Factory/Transaction_factory');
		$this->CI->load->library('Search/Good_search');
		$this->CI->load->library('Search/User_search');
		$Factory = new Transaction_factory;
		$User_search = new User_search;
		$Good_search = new Good_search;
	
		// Basic query
		$this->CI->db->select("T.id, T.status,T.updated, T.created")
			->from("transactions AS T ");
		
		// Add transaction_status filter
		if(!empty($options->transaction_status))
		{
			$this->CI->db->where_in('T.status',$options->transaction_status);
		}
		
		// Add transaction_id filter
		if(!empty($options->transaction_id))
		{
			if(is_array($options->transaction_id))
			{
				$this->CI->db->where_in("T.id",$options->transaction_id);
			}
			elseif(is_numeric($options->transaction_id))
			{
				$this->CI->db->where("T.id",$options->transaction_id);
			}
		}
		
		// Add user_id filter
		if(!empty($options->user_id))
		{
			$this->CI->db->join("transactions_users AS TU ","TU.transaction_id = T.id")
				->where_in("TU.user_id",$options->user_id);
		}
		
		// Order by
		if($options->order_by=="updated")
		{
			$this->CI->db->order_by("T.updated","DESC");
		}
		elseif($options->order_by=="id")
		{
			$this->CI->db->order_by("T.id","DESC");
		}
		
		// Find Transactions and pass to factory
		$transactions = $this->CI->db->limit($options->limit)
			->get()
			->result();

		if(empty($transactions))
		{
			// @todo handle empty results / throw error
			return array();
		}
		$Factory->set_transactions($transactions);
		
		// Get list of transactions plucked from result for use later
		$transaction_id_list = $Factory->get_ids("transactions");
		
		// Find Users by using the User_search library and then passing to the 
		// factory
		$users = $User_search->find(array(
			"transaction_id"=>$transaction_id_list,
			"limit" => 2000
		));
		$Factory->set_users($users);
		
		// Find Demands that match the transaction ID list, then pass
		// result to the factory
		$demands = $this->CI->db->from('demands')
			->where_in('transaction_id',$transaction_id_list)
			->get()
			->result();
		$Factory->set_demands($demands);
		
		// Find Goods by getting Good ID list plucked from the Demands,
		// using the Good_search library and then passing to the factory
		$good_id_list = $Factory->get_ids("goods");
		$goods = $Good_search->find(array(
			"good_id"=>$good_id_list,
			'radius' => 10000,
                        'status' => array('active','disabled', 'unavailable')
		));
		$Factory->set_goods($goods);

		// Load Reviews and pass them to the factory
		if($options->include_reviews)
		{
			$reviews = $this->CI->db->from("reviews")
				->where_in("transaction_id",$transaction_id_list)
				->get()
				->result();
			$Factory->set_reviews($reviews);
		}
		
		// Load Messages and pass them to the factory
		// Warning: loading messages is a resource-intense process!
		if($options->include_messages)
		{
			$this->CI->load->library('Messaging/Conversation');
			
			$messages = array();
			
			foreach($transaction_id_list as $id)
			{
				$C = new Conversation;
				$C->transaction_id = $id;
				$C->get();
				$messages[$id] = $C->messages;
			}
			
			$Factory->set_messages($messages);
		}
		
		if($options->include_events)
		{
			// @todo load events
			$result = $this->CI->db->from('events AS E ')
				->select('E.*, ET.title AS event_type')
				->where_in('E.transaction_id', $transaction_id_list)
				->join('event_types AS ET ','E.event_type_id=ET.id')
				->get()
				->result();
			
			$Factory->set_events($result);
		}
		
		if($options->include_unread)
		{
			Console::logSpeed("Transaction_search::find(): Searching for unread notifications");
			$result = $this->CI->db->select("E.transaction_id, N.created")
				->where("N.enabled",1)
				->where_in('E.transaction_id', $transaction_id_list)
				->where("N.user_id",$options->user_id)
				->join("events AS E","N.event_id=E.id")
				->from("notifications AS N")
				->get()
				->result();
			$Factory->set_unread($result);
			
		}
		
		// Compile final result object from its components
		$result = $Factory->result();
		Console::logSpeed("Transaction_search::find(): done.");
		
		return $result;
	}

	/*
	 * Function to validate if a user is involved in a given transaction
	 * takes transaction_id and user_id
	 * used on the you/controller for security purposes
	 *
	 * @author hans schoenburg
	 */
	public function check_user($options)
	{
		$result = $this->CI->db->select('TU.user_id, TU.transaction_id')
								->from('transactions_users AS TU')
								->where('TU.user_id',$options['user_id'])
								->where('TU.transaction_id', $options['transaction_id'])
								->get()
								->result();

		return(count($result) > 0);
	}

}
