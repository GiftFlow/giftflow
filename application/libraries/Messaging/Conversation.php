<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Conversations object
*	A component of the Messaging library.
*	
*	@author Brandon Jackson
*	@package Messaging
*/

class Conversation
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	/**
	*	Conversation ID
	*	@var int
	*/
	var $id;
	
	/**
	*	Type of conversation (e.g. thread or transaction)
	*	Default value is transaction
	*	@var string
	*/
	var $type = 'transaction';
	
	/**
	*	Transaction ID
	*	@var int
	*/
	var $transaction_id;
	
	/**
	*	Thread ID
	*	@var int
	*/
	var $thread_id;
	
	/**
	*	Logged in user ID
	*	@var int
	*/
	var $user_id;
	
	/**
	*	Total number of messages in the conversation
	*	@var int
	*/
	var $total_messages;
	
	/**
	*	Total number of unread messages in the conversation for user 
	*	specified in $this->user_id
	*	@var int
	*/
	var $unread_messages;
	
	/**
	*	Thread subject
	*	@var string
	*/
	var $subject;
	
	/**
	*	Thread object
	*	@var object
	*/
	var $thread;
	
	/**
	*	Transaction object
	*	@var object
	*/
	var $transaction;
	
	/**
	*	Array holding Message objects
	*	@var array
	*/
	var $messages = array();
	
	/**
	*	Array holding User objects for each participant in this conversation
	*	@var array
	*/
	var $users = array();
	
	/**
	*	List of message ID's found in this conversation
	*	@var array
	*/
	private $message_id_list = array();
	
	/**
	*	Constructor
	*/
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('datamapper');
	}
	
	/**
	*	Populate Conversation
	*
	*	NB: Either $this->transaction_id or $this->thread_id must be set 
	*	before calling this function.
	*
	*	Usage:
	*	$C = new Conversation;
	*	$C->transaction_id = 42;
	*	$C->get();
	*
	*	@access public
	*	@return $this
	*/
	public function get()
	{
		Console::logSpeed('Conversation::get() start');
		
		if(!empty($this->transaction_id))
		{
			// Set properties
			$this->type = "transaction";
			$this->id = $this->transaction_id;
			
			// Load transaction
			$this->get_transaction();
		}
		elseif (!empty($this->thread_id))
		{
			// Set properties
			$this->type = "thread";
			$this->id = $this->thread_id;
			
			// Load Thread
			$this->get_thread();
		}
		
		// Load All Messages
		Console::logSpeed('Conversation::get() getting messages');
		$this->get_messages();
		
		// Load Users
		Console::logSpeed('Conversation::get() getting users');
		$this->get_users();
		
		// Find Unread Messages
		Console::logSpeed('Conversation::get() getting unread messages');
		$this->set_opened();
		
		Console::logSpeed('Conversation::get() done.');

		// Return instance of $this
		return $this;
	}
	
	/**
	*	Load all the conversations associated with this conversation,
	*	use it to set $this->messages.
	*
	*	@access protected
	*	@return array		Array of Datamapper Message objects
	*/
	protected function get_messages()
	{
		// Filter messages based on either thread_id or transaction_id,
		// depending on value of $type
		$M = new Message();
		$M->where( $this->type . '_id', $this->id);
		$M->order_by('id','asc');
		$M->include_related('user','*',FALSE,TRUE);
		$M->get();
		
		// Set total number of messages
		$this->total_messages = count($M->all);
		
		// Loop over results
		foreach ($M->all as $key=>$row)
		{
			// Save ID to $this->message_id_list
			$this->message_id_list[] = $row->id;
			
			// Define status as read (by default)
			$row->opened = TRUE;
			
			// Save message object to $this->messages
			$this->messages[$row->id] = $row;
		}
		
		// Return array of Message objects
		return $this->messages;
	}
	
	/**
	*	Load the users participating in this conversation
	*	Utilizes the get_users() method of the Transaction
	*	and Thread models.
	*
	*	@access protected
	*	@return array		Array of Datamapper User objects
	*/
	protected function get_users()
	{
		$this->users = $this->{$this->type}->get_users();
		return $this->users;
	}
	
	/**
	*	Mutates $this->messages objects with unopened/opened info.
	*	Checks to see if any of the messages in $this->messages
	*	are unread. 
	*
	*	@todo redesign using new notifications table
	*
	*	@access protected
	*/
	protected function set_opened()
	{
		
	}
	
	/**
	*	Load a Datamapper Thread object using $this->id and 
	* 	and save it as $this->thread
	*
	*	@access protected
	*	@return object		Thread object
	*/
	protected function get_thread()
	{
		if(empty($this->thread))
		{
			$this->thread = new Thread($this->id);
		}
		
		// Load subject
		$this->get_subject();
		
		return $this->thread;
	}
	
	/**
	*	Load a Datamapper Transaction object using $this->id and 
	* 	and save it as $this->transaction
	*
	*	@access protected
	*	@return object		Transaction object
	*/
	protected function get_transaction()
	{
		if(empty($this->transaction))
		{
			$this->transaction = new Transaction($this->id);
		}
		return $this->transaction;
	}
	
	/**
	*	Load $this->subject using $this->thread
	*
	*	@access protected
	*	@return string		Subject of conversation
	*/
	protected function get_subject()
	{
		// Load thread if not already set
		if(empty($this->thread))
		{
			$this->get_thread();
		}
		
		// Set subject
		$this->subject = $this->thread->subject;
		
		return $this->subject;
	}
	
	public function get_latest_message()
	{
		return end($this->messages);
	}
	
	/**
	*	Creates and sends a new message.
	*
	*	The method is passed an array of options. The body of the message is 
	*	passed as one of the elements of this array, with a key of 'body'.
	*
	*	There are two methods for using this function.
	*
	*	Method 1: use if a conversation has already been loaded:
	*
	*	$C = new Conversation;
	*	$C->transaction_id = $id;
	*	$C->get();
	*	$C->compose(array(
	*		"body"=>$_POST['body']
	*	));
	*
	*	Method 2: use if a conversation has not already been loaded:
	*
	*	$C = new Conversation;
	*	$C->compose(array(
	*		"transaction_id"=>$_POST['transaction_id'],
	*		"body"=>$_POST['body']
	*	));
	*
	*	@access public
	*	@param string $body		Body text of message to be sent
	*	@return boolean
	*/
	public function compose($options)
	{
		$default_options = array(
			"body"=>"",
			"transaction_id"=>NULL,
			"thread_id"=>NULL,
			"user_id"=>$this->CI->session->userdata('user_id'),
			'recip_id' => NULL,
			'type'=> NULL
		);
		$options = array_merge($default_options,$options);

		if($this->type == 'transaction' || $options['type']== 'transaction')
		{	
			// Load transaction or thread conversations if an ID passed via $options
			if(!empty($options['transaction_id']) && empty($this->transaction))
			{
				$this->transaction_id = $options['transaction_id'];
				$this->get();
			}
			if($this->type=="transaction" && empty($this->transaction))
			{
				if(!empty($this->id))
				{
					$this->get_transaction();
				}
				else
				{
					// @todo throw error: "no transaction found"
					show_error("libraries/Messaging/Conversation.php: No Transaction Found");
					return FALSE;
				}
			}
		}
		else if($this->type =='thread' || $options['type'] == 'thread')
		{
			if(!empty($options['thread_id']))
			{
				$this->thread_id = $options['thread_id'];
				$this->get();
			}
			
			// Create new thread
			elseif(empty($options['thread_id']) && empty($this->thread_id))
			{

				//Check to see if users already have a thread between them
				$existing_threads = $this->get_existing($options);
					
				if(!empty($existing_threads))
				{
					$this->get();
				} else {

					//if no existing thread, create a new one

					$this->thread = new Thread();
					$this->thread->subject = $options['subject'];
					if(!$this->thread->save())
					{
						show_error('Error saving thread');
					} 
					$this->thread_id = $this->thread->id;
				}
			}

			//Save users to new thread
			$this->users[] = new User($options['user_id']);
			$this->users[] = new User($options['recip_id']);

			// Loop over each user and deliver to inbox
			foreach($this->users as $User)
			{
				if($this->type=="thread")
				{
					// Associate each user with the thread
					if(!$this->thread->save($User))
					{
						show_error('Error saving threads_users');
					}
				}
			}		
		}

			// Create new message
		$M = new Message();
		$M->body = $options['body'];
		$M->user_id = $options['user_id'];

		// Determine foreign key name dynamically using $this->type
		// e.g. transaction_id or thread_id

		$M->{$this->type."_id"} = $this->{$this->type}->id;

		// Save Message
		if(!$M->save())
		{
			// @todo handle message saving error
			show_error('Error saving Message');
		}
		
		// Loop over each user and deliver to inbox
		foreach($this->users as $User)
		{
			if($this->type=="thread")
			{
				// Associate each user with the thread
				if(!$this->thread->save($User))
				{
					show_error('Error saving threads_users');
				}
			}
		}
		// Populate Thread object for return
		if($this->type=="thread")
		{
			$this->thread->user->get();
			$this->thread->message->get();
		}
		
		// Refresh Conversation object's message data
		$this->get_messages();
		
		// Success!
		return TRUE;
	}


	public function get_existing($options)
	{
		$id = NULL;
		if(isset($options['user_id']) && isset($options['recip_id']))
		{
			//Check to see if users already have a thread between them
			$existing_threads = $this->CI->db->select('T.user_id, T.thread_id, TU.user_id')
						->from('threads_users AS T')
						->join('threads_users AS TU', 'T.thread_id = TU.thread_id AND TU.user_id !='.$options['user_id'])
						->where('T.user_id ='.$options["user_id"])
						->where('TU.user_id ='.$options['recip_id'])
						->get()
						->result();

			//Each pair of users can only have ONE thread between them.
			if(!empty($existing_threads))
			{
				$id = $existing_threads[0]->thread_id;
				$this->thread_id = $id;
			} 
			
			return $id;
		}
	}
			
}


