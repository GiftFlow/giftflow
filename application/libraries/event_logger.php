<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	Saves data passed to hooks into the events database table
 *	The hooks config file calls a method matching the name of the event type.
 *	If only a basic event row (ie one without any indices) is to be created,
 *	then a method for that event doesn't need to be created. If custom indices
 *	are required, however, a method with the name of the event_type should be
 *	written.
 *
 *	@author Brandon Jackson
 * 	@package Libraries
 */

class Event_logger
{
	/**
	*	CodeIgniter super-object
	*
	*	@var object
	*/
	protected $CI;
	
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
	*	PHP magic method that will automatically create the basic event
	*	entry using the name of the method that was called as the event
	*	type if the method called isn't defined explicitly
	*
	*	@param string $name			Name of method called
	*	@param array $arguments		Arguments passed
	*/
	function __call($name,$arguments)
	{
		$E = $this->basic($name,$arguments[1]);
	}
	
	/**
	*	Creates basic event entry using only event name and data
	*
	*	@param string $name
	*	@param array $data
	*	@return object $E
	*/
	function basic($name,$data)
	{
		$E = new Event();
		$E->type = $name;
		$E->data = json_encode($data);
		
		$E->user_id = $this->CI->session->userdata('user_id');
		
		if(!$E->save())
		{
			echo $E->error->string;
			return FALSE;
		}
		return $E;
	}
	
	
	/**
	* Saves User_New event. 
	* $data contains user_id of newly registered user
	* can't use $this->basic because userdata isn't set yet.
	*/
	public function user_new($params,$data)
	{
		$E = new Event();
		$E->type = 'user_new';
		$E->data = json_encode($data);
		
		$E->user_id = $data['user_id'];
		
		if(!$E->save())
		{
			echo $E->error->string;
			return FALSE;
		}
		return TRUE;
	
	}
	
	/**
	* Saves Follower_new event AND sends alert email to person being followed
	*
	*/
	public function follower_new($params, $data)
	{
	
		$E = $this->basic("follower_new",$data);
		$E->save();
		$E->notify_user($data['following_user_id']);
		
		
	}
	/**
	*	Saves transaction_id to event object when transaction_new hook
	*	called and then creates notification db row
	*	
	*	@param array $params
	*	@param object $data
	*/
	function transaction_new($params,$data)
	{
		$data->conversation = NULL;
		
		// Create basic event, and then save transaction_id
		$E = $this->basic("transaction_new",$data);
		$E->transaction_id = $data->transaction->id;
		$E->save();
		
		// Deliver notification to the transaction's decider
		$E->notify_user($data->transaction->decider->id);
	}
	
	/**
	*	Saves transaction status change
	*	notifies demander that trans-status has changed.
	*
	*	@param array $params
	*	@param object $data
	*/
	function transaction_activated($params, $data)
	{
		$E = $this->basic("transaction_activated",$data);
		$E->transaction_id = $data->transaction->id;
		$E->save();
		
		// Deliver notification to the transaction's decider
		$E->notify_user($data->transaction->demander->id);
	}
	
	
	/**
	*	Saves transaction_id to event object when transaction_cancelled hook
	*	called and then creates notification db row
	*	
	*	@param array $params
	*	@param object $data
	*/
	function transaction_cancelled($params,$data)
	{
		$data->conversation = NULL;
		
		// Create basic event, and then save transaction_id
		$E = $this->basic("transaction_cancelled",$data);
		$E->transaction_id = $data->transaction->id;
		$E->save();
		
		// Deliver notification to the transaction's decider
		$E->notify_user($data->transaction->decider->id);
	}
	
	/**
	*	Saves transaction_id to event object when transaction_declined hook
	*	called and then creates notification db row
	*	
	*	@param array $params
	*	@param object $data
	*/
	function transaction_declined($params,$data)
	{
		$data->conversation = NULL;
		
		// Create basic event, and then save transaction_id
		$E = $this->basic("transaction_declined",$data);
		$E->transaction_id = $data->transaction->id;
		$E->save();
		
		// Deliver notification to the transaction's demander
		$E->notify_user($data->transaction->demander->id);
	}

	/**
	*	Saves transaction_id and message_id to event object when 
	*	transaction_message hook called, and then creates notification db row
	*	
	*	@param array $params
	*	@param object $data
	*/
	function transaction_message($params,$data)
	{
		// Make a copy of the data object and remove conversation object
		// since we don't want to save it to the database
		$event_data = clone $data;
		$event_data->conversation = NULL;
		
		// Create basic event, and then save transaction_id
		$E = $this->basic("transaction_message",$event_data);
		$E->transaction_id = $data->transaction->id;
		$E->message_id = $data->message_id;
		$E->save();
		
		// Deliver notification to the message's recipients
		foreach($data->conversation->users as $user)
		{
			if($user->id != $E->user_id)
			{
				$E->notify_user($user->id);
			}
		}
	}
	
	function reset_password($params, $data)
	{
		$E = new Event();
		$E->type = 'reset_password';
		$E->data = json_encode($data);
		$E->user_id = $data['user_id'];
		
		if(!$E->save())
		{
			echo $E->error->string;
			return FALSE;
		}
	
	}
	
	/**
	*	called at market::review
	*	saves Transaction object and notifies reviewED user
	*
	*	@param array $params
	*	@param object $data
	*/
	
	function review_new($params, $data)
	{
		$E = $this->basic("review_new",$data);
		$E->save();
		$E->notify_user($data->reviewed->id);
	}

}