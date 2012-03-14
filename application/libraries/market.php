<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	The Market library models the transaction process.
*	
*	@author Brandon Jackson
*	@package Search
*/

class Market
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	
	/**
	* array of datamapper objects to be passed to Transaction_language
	* follows standard set by Factory::transaction
	* @var object
	*/
	var $transaction;
	
	/**
	* Demands array to be saved to db
	* @var array
	*/
	var $Demands = array();
	
	
	
	/**
	* array of Good_search Goood objects, associative array Good/fulfill_good
	* 
	* @var array
	*/
	var $Goods = array();
	
	/** 
	* Demander User object
	* @var object
	*/
	var $Demander;
	
	/** 
	* Decider User object
	* @var object
	*/
	var $Decider;
	
	/**
	* Note set with demand, to be passed on into conversation
	* @var string
	*/
	var $note;
	
	var $C;
	/**
	*	Constructor
	*/
	public function __construct()
	{
		// CodeIgniter instance
		$this->CI =& get_instance();
		
		// Load libraries
		$this->CI->load->library('datamapper');
		$this->CI->load->library('Search/Good_search');
		$this->CI->load->library('Search/Transaction_search');
	}
	
	/**
	*	Creates new transaction
	*
	*	@param array $options
	*	@param array $options['demands']
	*	@param array $options['demands'][0]
	*	@param int $options['demands'][0]['user_id']
	*	@param int $options['demands'][0]['good_id']
	*	@param string $options['demands'][0]['type']
	*	@param string $options['note']
	*	@param int $options['decider_id']
	*	@return boolean
	*/
	public function create_transaction($options)
	{
		// Create pending transaction
		$Transaction = new Transaction();
		$Transaction->status = "pending";
		
		// Save Transaction
		if(!$Transaction->save())
		{
			// @todo handle transaction saving error
			return FALSE;
		}
	
		// Person not demanding Good. Passed in $options seperate from $demands
		if(!empty($options['decider_id'])) 
		{
			$this->Decider = new User($options['decider_id']);
		}		
		
		//iterate through the demands, saving each one in $this->Demands
		foreach($options['demands'] as $key=>$val)
		{

			// Good being requested
			$Good_search = new Good_search;
			$Good = $Good_search->get(array(
				"good_id"=>$val['good_id']
			));
			
			//Set message passed in demand
			if(!empty($val['note']) && empty($this->note))
			{
				$this->note = $val['note'];
			}
			
			// Create User object for person making demand if doesn't yet exist
			if(empty($this->Demander))
			{
				$this->Demander = new User($val['user_id']);
			}
				
			// Save Decider if the good's owner isn't the same as the Demander
			if(empty($this->Decider) && ($Good->user->id != $this->Demander->id))
			{
				$this->Decider = new User($Good->user->id);
			}

			// Create Demand object
			$D = new Demand();
			$D->type = $val['type'];
			$D->user_id = $this->Demander->id;
			$D->good_id = $Good->id;
			$D->transaction_id = $Transaction->id;
			
			// Add new Demand to $this->Demands array
			$this->Demands[] = $D;
		}
		
		// Before saving demands, validate that a Decider has been found
		if(empty($this->Decider))
		{
			return FALSE;
		}
		
		// Save each Demand in $this->Demands array
		foreach($this->Demands as $key => $val)
		{
			if(!$val->save())
			{
				// @todo handle demand saving error
				return FALSE;
			}
		}
	
		// Associate the users with this transaction
		// Creates 2 rows in transactions_users table
		if(!$Transaction->save_user($this->Demander))
		{
			// @todo handle user saving error
			return FALSE;
		}
			
		if(!$Transaction->save_user($this->Decider))
		{
			// @todo handle user saving error
			return FALSE;
		}
		if(!empty($this->note))
		{
			$this->CI->load->library('Messaging/Conversation');
			$C = new Conversation();
			if(!$C->compose(array(
				"transaction_id"=>$Transaction->id,
				"body"=>$this->note,
				"user_id"=>$this->Demander->id
				)))
			{
				show_error("Error saving conversation.");
				return FALSE;
			}
		}
		
		// Load fully formed transaction factory result of new transaction
		$TS = new Transaction_search;
		$hook_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages" => FALSE			
				)),
			"note" => $this->note
		);

		// Hook: `transaction_new`
 		$this->CI->hooks->call('transaction_new', $hook_data);

		return TRUE;
	}
	
	
	/**
	*	Cancel transaction at the request of the Demander. Can be passed a
	*	transaction_id directly or a good ID and a user ID which can be used
	*	to find the relevant transaction ID.
	*
	*	@param array $options
	*	@arg int user_id			ID of demanding User
	*	@arg int good_id			ID of Good
	*	@arg int transaction_id		ID of transaction
	*	@return boolean
	*/
	public function cancel($options)
	{
		
		// If transaction_id provided, populate object
		if(!empty($options['transaction_id']))
		{
			$Transaction = new Transaction($options['transaction_id']);
		}
		
		// Find Transaction based on Good & User IDs
		elseif(!empty($options['user_id']) && !empty($options['good_id']))
		{
			$Transaction = new Transaction();
			$Transaction->where_in('status',array('pending','active'));
			$Transaction->where_related_demand('user_id', $options['user_id']);
			$Transaction->where_related_demand('good_id', $options['good_id']);
			$Transaction->get();
		}
		
		else
		{
			return FALSE;
		}
		
		// Change status to cancelled
		// @todo encapsulate in Transaction model, create mechanical hook there
		$Transaction->status = "cancelled";
		
		// Save change
		if(!$Transaction->save())
		{
			// @todo handle saving errors
			return FALSE;
		}
		
		// Send cancellation message if provided
		if(!empty($options['message']))
		{
			$Conversation = new Conversation;
			$Conversation->compose(array(
				"transaction_id"=>$Transaction->id,
				"body"=>$options['message'],
				"type"=>"cancelled"
			));
		}
		
		// Load fully formed transaction factory result of new transaction
		$TS = new Transaction_search;
		$hook_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages"=>FALSE
			)),
			"message" => $options['message']
		);
		
		// Hook: 'transaction_cancelled'
		$this->CI->hooks->call('transaction_cancelled', $hook_data);
		
		return TRUE;
	}

	/**
	*	Decline transaction at the request of the Other user. Can be passed a
	*	transaction_id directly or a good ID and a user ID which can be used
	*	to find the relevant transaction ID.
	*
	*	@param array $options
	*	@param int $options['user_id']			ID of demanding User
	*	@param int $options['good_id']			ID of Good
	*	@param int $options['transaction_id']	ID of transaction
	*	@param string $options['message']		Message to be composed
	*	@return boolean
	*/
	public function decline($options)
	{
		
		// If transaction_id provided, populate object
		if(!empty($options['transaction_id']))
		{
			$Transaction = new Transaction($options['transaction_id']);
		}
		
		// Find Transaction based on Good & User IDs
		else
		{
			$Transaction = new Transaction();
			$Transaction->where_in('status',array('pending','active'));
			$Transaction->where_related_demand('user_id', $options['user_id']);
			$Transaction->where_related_demand('good_id', $options['good_id']);
			$Transaction->get();
		}
		
		// Change status to cancelled
		// @todo encapsulate in Transaction model, create mechanical hook there
		$Transaction->status = "declined";
		
		// Save change
		if(!$Transaction->save())
		{
			// @todo handle saving errors
			return FALSE;
		}
		
		// Send declined message if provided
		if(!empty($options['message']))
		{
			$Conversation = new Conversation;
			$Conversation->compose(array(
				"transaction_id"=>$Transaction->id,
				"body"=>$options['message'],
				"type"=>"declined"
			));
		}
		
		// Load fully formed transaction factory result of new transaction
		$TS = new Transaction_search;
		$hook_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages" => FALSE
			)),
			"message" => $options['message']
		);
		
		// Hook: 'transaction_declined'
		$this->CI->hooks->call('transaction_declined', $hook_data);
		
		return TRUE;
	}
	
	/**
	*	User accepts demands, transaction status changed to active
	*	@param array $options
	*	@param int $options['transaction_id']	ID of transaction
	*	@param string $options['message']		Message to be composed
	*	@return boolean
	*/
	public function activate($options)
	{
		// Load transaction
		$Transaction = new Transaction($options['transaction_id']);
		
		// Update status
		// @todo encapsulate in Transaction model, create mechanical hook there
		$Transaction->status = "active";
		
		// Save change
		if(!$Transaction->save())
		{
			// @todo handle transaction saving error
			return FALSE;
		}
		
		// Send activation message if provided
		if(!empty($options['message']))
		{
			$Conversation = new Conversation;
			$Conversation->compose(array(
				"transaction_id"=>$Transaction->id,
				"body"=>$options['message'],
				"type"=>"activated"
			));
		}
		
		// Load fully formed transaction factory result of new transaction
		$TS = new Transaction_search;
		$hook_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages" => FALSE
			)),
			"message" => $options['message']
		);
				
		// Hook: 'demand_activated'
		$this->CI->hooks->call("transaction_activated",$hook_data);
		
		return TRUE;
	}

	/**
	*	User posts new review, status may be changed to completed
	*	@param array $options
	*	@param int $options['transaction_id']	ID of transaction
	*	@param string $options['message']		Message to be composed
	*	@param string $options['body']			Body of review
	*	@param string $options['rating']		Rating of review
	*	@param int $options['reviewer_id']		Reviewer ID
	*	@param object $options['transaction_data']	Data of transaction
	*	@return boolean
	*/
	public function review($options)
	{
		Console::logSpeed("Market::review()");
		
		// Load transaction
		$Transaction = new Transaction($options['transaction_id']);
		
		// Load user/reviewer
		$User = new User($options['reviewer_id']);

		// Get transaction_data if not set
		if(empty($options->transaction_data))
		{
			$TS = new Transaction_search;
			$options['transaction_data'] = $TS->get(array(
				"transaction_id"=>$options['transaction_id']
			));
		}
		
		// Create and start populating new Review object
		$R = new Review();
		$R->transaction_id = $options['transaction_id'];
		$R->body = $options['body'];
		$R->rating = $options['rating'];
		$R->reviewer_id = $options['reviewer_id'];
		
		// Set reviewed_id
		if($options['transaction_data']->decider->id != $User->id)
		{
			$R->reviewed_id = $options['transaction_data']->decider->id;
		}
		else
		{
			$R->reviewed_id = $options['transaction_data']->demander->id;
		}
		
		
		
		// Try saving Review object
		if(!$R->save())
		{
			return FALSE;
		}
		
		// Prep hook data
		$TS = new Transaction_search;
		$hook_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages" => FALSE,
				"include_reviews" => TRUE
			))
		);
		//iterate over the transaction and add ReviewER and ReviewED user arrays to hook_data
		foreach($hook_data->transaction->users as $key=>$val)
		{
			if($val->id == $options['reviewer_id'])
			{
				$hook_data->reviewer = $val;
			}
			else if($val->id != $options['reviewer_id'])
			{
				$hook_data->reviewed = $val;
			}
		}
		
		
		// Hook: `transaction_reviewed`
		$this->CI->hooks->call("review_new", $hook_data);

		// Attempt to change status to completed
		$this->complete(array(
			"transaction_id"=>$Transaction->id
		));

		return TRUE;
	}
	
	/**
	*	Transaction status changed to completed if all reviews posted
	*	@param array $options
	*	@param int $options['transaction_id']	ID of transaction
	*	@return boolean
	*/
	function complete($options)
	{
		Console::logSpeed("Market::complete()");
		
		// Load transaction
		$Transaction = new Transaction($options['transaction_id']);
		
		// Make sure that both reviews have been written
		if(!$Transaction->has_both_reviews())
		{
			// @todo handle error
			return FALSE;
		}
	
		$Transaction->status = "completed";
		
		if(!$Transaction->save())
		{
			//@todo handle error
			return FALSE;
		}
		
		// Prep hook data
		$TS = new Transaction_search;
		$hook_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id
			))
		);
		
		// Hook: `transaction_reviewed`
		$this->CI->hooks->call("transaction_completed", $hook_data);
		
		return TRUE;
	}
	
	/**
	*	Send transaction message without changing the status of the transaction
	*	Although this operation was previously handled by the Conversation 
	*	library, it needed a wrapper for the specific case where there is no
	*	status change for the purpose of firing a specific hook
	*
	*	@param array $options
	*	@param int $options['transaction_id']	Transaction ID
	*	@param string $options['body']			Message body
	*/
	public function message($options)
	{
		Console::logSpeed("Market::message()");
		
		// Compose message using Conversation class
		$Conversation = new Conversation;
		if(!$Conversation->compose(array(
			"transaction_id"=>$options['transaction_id'],
			"body"=>$options['body']
		)))
		{
			show_error("Error saving conversation.");
			return FALSE;
		}
		
		$Message = $Conversation->get_latest_message();
		
		// Prep hook data
		$TS = new Transaction_search;
		$hook_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$options['transaction_id']
			)),
			"message_id"=>$Message->id,
			"conversation"=>$Conversation
		);
		
		// Hook: `transaction_message`
		$this->CI->hooks->call("transaction_message", $hook_data);
		
		return TRUE;
	}
	
	/**
	*	Updates a transaction's `updated` timestamp
	* 	Fired as a callback to all transaction-related hooks
	*	@param array $params
	*	@param object $data
	*/
	public function updated($params, $data)
	{
		$this->CI->db->where('id',$data->transaction->id)
			->update('transactions', array(
				"updated"=> date("Y-m-d H:i:s")
			));
	}

}