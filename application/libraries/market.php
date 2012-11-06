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
		$this->CI->load->library('Event_logger');
		$this->CI->load->library('Notify');
		$this->data = $this->CI->util->parse_globals();
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
	*	@param strin $options['hook']
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
			show_error('Error saving transaction');
		}
	
		// Person not demanding Good. Passed in $options seperate from $demands
		if(!empty($options['decider_id'])) 
		{
			$this->Decider = new User($options['decider_id']);
		}		
		
		//iterate through the demands, saving each one in $this->Demands
		foreach($options['demands'] as $key=>$val)
		{

			// Good being requested, include disabled goods!
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
			show_error('Error finding Decider');
		}
		
		// Save each Demand in $this->Demands array
		foreach($this->Demands as $key => $val)
		{
			if(!$val->save())
			{
				show_error('Error saving demands array');
			}
		}
	
		// Associate the users with this transaction
		// Creates 2 rows in transactions_users table
		if(!$Transaction->save_user($this->Demander))
		{
			show_error('Error saving Demander to transaction');
		}
			
		if(!$Transaction->save_user($this->Decider))
		{
			show_error('Error saving Decider to transaction');
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
			}
		}
			// Load fully formed transaction factory result of new transaction
			$TS = new Transaction_search;
			$event_data = (object) array(
				"transaction"=> $TS->get(array(
					"transaction_id"=>$Transaction->id,
					"include_messages" => FALSE			
					)),
				"note" => $this->note,
				'return_url' => site_url('login/?return_url=you/view_transaction/'.$Transaction->id),
				'notify_id' => $this->Decider->id
			);

		$this->updateTransactionTimestamp($Transaction->id);

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
		$event_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages"=>FALSE
			)),
			"message" => $options['message'],
			'return_url' => site_url('login/?return_url=you/view_transaction/'.$Transaction->id)
		);
		$event_data->notify_id = $event_data->transaction->decider->id;
		
		$this->CI->event_logger->transaction_cancelled($event_data);
		
		$this->updateTransactionTimestamp($Transaction->id);
		
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
		$event_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages" => FALSE
			)),
			"message" => $options['message'],
			'return_url' => site_url('login/?return_url=you/view_transaction/'.$Transaction->id)
		);

		$event_data->notify_id = $event_data->transaction->demander->id;
		
		
		$this->event_logger->transaction_declined($event_data);
		$this->updated('transaction_declined',$event_data);
		$this->updateTransactionTimestamp($Transaction->id);
		
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
		$event_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages" => FALSE
			)),
			"message" => $options['message'],
			'return_url' => site_url('you/view_transaction/'.$Transaction->id)
		);
		$event_data->notify_id = $event_data->transaction->demander->id;
				
		//$E = new Event_logger();
		//$E->transaction_activated('transaction_activated',$event_data);
		$this->CI->event_logger->transaction_activated($event_data);

		//$N = new Notify();
		//$N->alert_transaction_activated('transaction_activated',$event_data);
		$this->CI->notify->alert_transaction_activated($event_data);
		
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
	*	@param string $options['hook']			which hook should be called review/thankyou
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
		$event_data = (object) array(
			"transaction"=> $TS->get(array(
				"transaction_id"=>$Transaction->id,
				"include_messages" => FALSE,
				"include_reviews" => TRUE
			)),
			'return_url' => site_url('you/view_transaction/'.$Transaction->id),
			'notify_id' => $R->reviewed_id
		);
		//iterate over the transaction and add ReviewER and ReviewED user arrays to event_data
		foreach($event_data->transaction->users as $key=>$val)
		{
			if($val->id == $options['reviewer_id'])
			{
				$event_data->reviewer = $val;
			}
			else if($val->id != $options['reviewer_id'])
			{
				$event_data->reviewed = $val;
			}
		}
		
		$this->CI->event_logger->review_new($event_data);
		$this->CI->notify->review_new($event_data);

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
	
		/*Eliminated the has_both_reviews check 
		 * changed it to a simple status check
		 * This check is called from market::review in two different ways
		 * One when a user writes another a review and the other when they write a thankyou
		 * A thankyou creates a 'pending' transaction, so this test won't pass.
		*/

		if($Transaction->status == 'active')
		{
			$Transaction->status = "completed";
		
			if(!$Transaction->save())
			{
				//@todo handle error
				return FALSE;
			}
		
			// Prep hook data
			$TS = new Transaction_search;
			$event_data = (object) array(
				"transaction"=> $TS->get(array(
					"transaction_id"=>$Transaction->id
			))
			);
		
			$E = new Event_logger();
			$E->basic('transaction_completed',$event_data);
		}
		
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


		$notify_data = array();

		foreach($Conversation->users as $val) 
		{
			if($val->id != $this->data['logged_in_user_id'])
			{
				$notify_data['recipient_id'] = $val->id;
				$notify_data['recipient_email'] = $val->email;
				$notify_data['recipient'] = $val->screen_name;
				$notify_data['notify_user_id'] = $val->id;
			}
		}

		
		$notify_data["message"] = $options['body'];
		$notify_data['return_url'] = site_url('you/view_transaction/'.$options['transaction_id']);
		

		// Prep hook data
		$TS = new Transaction_search;

		$notify_data["transaction"] = $TS->get(array("transaction_id"=>$options['transaction_id']));
		$notify_data["message_id"] = $Message->id;
		$notify_data["transaction_id"] = $options['transaction_id'];

		$notify_data = (object) $notify_data;

		$this->CI->event_logger->transaction_message($notify_data);

		$this->CI->notify->alert_transaction_message($notify_data);
		$this->updateTransactionTimestamp($options['transaction_id']);
		
		return TRUE;
	}
	
	/**
	*	Updates a transaction's `updated` timestamp
	* 	Fired as a callback to all transaction-related hooks
	*	@param array $params
	*	@param object $data
	*/
	public function updateTransactionTimestamp($transactionId)
	{
		$this->CI->db->where('id', $transactionId)
			->update('transactions', array(
				"updated"=> date("Y-m-d H:i:s")
			));
	}

}
