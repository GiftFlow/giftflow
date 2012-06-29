<?php
/**
*	Transaction factory
*
*	Combines various types of data (users,demands,goods,reviews) into a complete 
*	representation of a Transaction.
*
*	For usage example, see Transaction_search::find().
*
*	@author Brandon Jackson
*/
class Transaction_factory {

	/**
	*	The final fully-formatted result
	*	@var array
	*/
	var $Result = array();

	/**
	*	Array of Transaction DB result objects
	*	@var array
	*/
	var $Transactions = array();
	
	/**
	*	Array of Demand DB result objects
	*	@var array
	*/
	var $Demands = array();
	
	/**
	*	Array of Good  factory result objects
	*	@var array
	*/
	var $Goods = array();
	
	/**
	*	Array of User factory result objects
	*	@var array
	*/
	var $Users = array();
	
	/**
	*	Array of Review DB result objects
	*	@var array
	*/
	var $Reviews = array();

	/**
	*	Initial sorting order of the transactions
	*	Since transactions are mapped by their IDs onto an associative array,
	*	$this->Transactions, the initial sort order of the database result
	*	must be preserved. This is a single-dimensional array of Transaction
	*	IDs.
	*
	*	@var array
	*/
	var $Transaction_order = array();

	/**
	*	Array of Object ID lists
	*	@var array
	*/
	protected $ids = array(
		"transactions"=>array(),
		"demands"=>array(),
		"users"=>array(),
		"goods"=>array(),
		"reviews"=>array()
	);
	
	function __construct()
	{
		
	}
	
	/**
	*	Build and return the final result object.
	*
	*	Compiles Transactions, Demands, Users, Goods and Reviews.
	*	See JSON prototype at end of file for result data structure reference
	*
	*	@return array
	*/
	function result()
	{
		Console::logSpeed("Transaction_factory::result()");
		
		// Validate that transactions and demands exist
		if(empty($this->Transactions) || empty($this->Demands))
		{
			// @todo throw error
			return FALSE;
		}
		
		// Build Demands
		foreach($this->Demands as $key=>$Demand)
		{
			// Add Goods
			if(!empty($this->Goods[$Demand->good_id]))
			{
				$this->Demands[$key]->good = $this->Goods[$Demand->good_id];
			}
			
			// Add User
			if(!empty($this->Users[$Demand->user_id]))
			{
				$this->Demands[$key]->user = $this->Users[$Demand->user_id];
			}
			
			// Add Demand to its parent Transaction
			$this->Transactions[$Demand->transaction_id]->demands[] = $this->Demands[$key];
		}
		// Build Transactions
		foreach($this->Transactions as $key=>$Transaction)
		{
			// Add Demander
			if(!empty($this->Users[$Transaction->demands[0]->user_id]))
			{
				$this->Transactions[$key]->demander = $this->Users[$Transaction->demands[0]->user_id];
			}
			else
			{
				// @todo handle error
				show_error("Demander not found for transaction #".$key);
			}
			
			// Add Decider
			foreach($Transaction->users as $User)
			{
				if($User->id != $this->Transactions[$key]->demander->id)
				{
					$this->Transactions[$key]->decider = $User;
				}
			}
			
			// If decider or demander empty, remove from results
			if(empty($Transaction->demander)||empty($Transaction->decider))
			{
				// Remove all matching entries from $this->Transaction_order
				while(array_search($Transaction->id, $this->Transaction_order)!==FALSE)
				{
					unset($this->Transaction_order[array_search($Transaction->id, $this->Transaction_order)]);
				}
				
				// Remove from Transactions array
				unset($this->Transactions[$key]);
			}
		}
		
		// Generate language data
		$this->set_language();
		
		// After transaction objects fully constructed, restore the original 
		// Transaction sort order
		foreach($this->Transaction_order as $id)
		{
			$this->Result[] = $this->Transactions[$id];
		}
		
		Console::logSpeed("Transaction_factory::result(): done.");
		
		return $this->Result;
	}

	/**
	*	Set transactions
	*	Loops over array of DB result objects and maps each transaction
	*	onto an associative array, $this->Transactions. Since this mapping 
	*	process destroys the result's original sort order, each transaction's ID 
	*	is added to the array $this->Transaction_order, which maintains the 
	*	original sort order of the result until it can be restored in 
	*	$this->result().
	*
	*	@param array $transactions		Array of result objects
	*/
	function set_transactions($transactions)
	{
		foreach($transactions as $key=>$transaction)
		{
			$transaction->demands = array();
			$transaction->reviews = array();
			$transaction->users = array();
			$transaction->messages = array();
			$transaction->events = array();
			$transaction->demander = new stdClass;
			$transaction->language = new stdClass;
			$transaction->unread = FALSE;
			$this->Transactions[$transaction->id] = $transaction;
			$this->Transaction_order[] = $transaction->id;
		}
		
		$this->ids['transactions'] = array_keys($this->Transactions);
	}
	
	/**
	*	Set users
	*	Loops over array of User factory result objects and maps each good onto 
	*	an associative array, $this->Users.
	*
	*	@param array $users		Array of result objects
	*/
	function set_users($users)
	{
		foreach($users as $user)
		{
			if(!empty($user->transaction) && !empty($this->Transactions[$user->transaction->id]))
			{
				$this->Transactions[$user->transaction->id]->users[] = $user;
			}
			$this->Users[$user->id] = $user;
		}
		
		$this->ids['users'] = array_keys($this->Users);
	}
	
	/**
	*	Set demands
	*	Loops over array of DB result objects and maps each demand onto an 
	*	associative array, $this->Demands. Also, a list of good IDs is generated
	*	during the looping process. This can be used to load related Goods.
	*
	*	@param array $demands	Array of result objects
	*/
	function set_demands($demands)
	{
		foreach($demands as $demand)
		{
			$this->Demands[$demand->id] = $demand;
			$this->ids['goods'][] = $demand->good_id;
		}
		
		$this->ids['demands'] = array_keys($this->Demands);
		
		// Remove duplicate Good IDs
		$this->ids['goods'] = array_unique($this->ids['goods']);
	}
	
	/**
	*	Set goods
	*	Loops over array of Good factory result objects and maps each good onto 
	*	an associative array, $this->Goods.
	*
	*	@param array $goods		Array of result objects
	*/
	function set_goods($goods)
	{
		foreach($goods as $good)
		{
			$this->Goods[$good->id] = $good;
		}
		
		$this->ids["goods"] = array_keys($this->Goods);
	}
	
	/**
	*	Set reviews
	*	Loops over array of DB result objects and maps each demand onto an 
	*	associative array, $this->Reviews.
	*
	*	@param array $reviews	Array of result objects
	*/
	function set_reviews($reviews)
	{
		foreach($reviews as $review)
		{
			$this->Reviews[$review->id] = $review;
			
			if(!empty($this->Transactions[$review->transaction_id]))
			{
				$this->Transactions[$review->transaction_id]->reviews[] = $review;
			}
		}
		
		$this->ids['reviews'] = array_keys($this->Reviews);
	}
	
	function set_messages($messages)
	{
		foreach($messages as $key=>$message)
		{
			$this->Transactions[$key]->messages = $message;
		}
	}
	
	/**
	*	Processes database resultset from Transaction_search::find() which
	*	returns a list of notifications marked as enabled for the user's
	*	transactions.
	*	@param object $unread_transactiosn		CI DB result
	*/
	function set_unread($unread_transactions)
	{
		Console::logSpeed("Transaction_factory::set_unread()");
		// Loop over each notification row and mark its transaction as unread
		foreach($unread_transactions as $key=>$value)
		{
			$this->Transactions[$value->transaction_id]->unread = TRUE;
		}
	}
	
	/**
	*	Assign events search results to their relevant transactions
	*	Used to generate transaction histories
	*	@param array $events	CI DB resultset
	*/
	function set_events($events)
	{
		Console::logSpeed("Transaction_factory::set_events()");
		
		// Loop over event results and assign each individual result object to
		// its transaction
		foreach($events as $key=>$value)
		{
			// Add the screen name of the event's user to the row
			$value->user_screen_name = $this->Users[$value->user_id]->screen_name;
			
			// Add row to transaction's object
			$this->Transactions[$value->transaction_id]->events[] = $value;
		}
		Console::logSpeed("Transaction_factory::set_events(): done.");
	}
	
	/**
	*	Generate language data which is added to Transactions
	*/
	function set_language()
	{
		Console::logSpeed("Transaction_factory::set_language()");
		
		// Loop over new transactions array and generate language text
		foreach($this->Transactions as $key=>$val)
		{
			$demander_link = "<a href='".site_url('people/'.$val->demander->id)."'>".$val->demander->screen_name."</a> ";
			$decider_link = "<a href='".site_url('people/'.$val->decider->id)."'>".$val->decider->screen_name."</a> ";

			// Decider summary
			$decider_summary_demands = array();
			$decider_summary = "";
			

			foreach($val->demands as $demand)
			{
				$brief="";
				if($demand->type == "give" && $demand->good->type == "need")
				{
					//This is a bit of a band-aid fix for when someone offers to "give to" another's need. requires different language than the usual "give"
					$type = "give to";
				}
				else
				{
				 $type = $demand->type;
				}
				
				if($demand->type != "fulfill")
				{
					$brief = $demander_link;
				}
				switch($type)
				{
					case "take":
						$brief .= " has requested your";
						break;
					case "borrow":
						$brief .= " would like to borrow your";
						break;
					case "fulfill":
						$brief .= " to fulfill ";
						break;
					case "give to":
						$brief .= "would like to help with your need";
						break;
					case "share":
						$brief .= "has offered to share ";
						break;
					case "give":
						$brief .= "has offered you ";
						break;
					case "thank":
						$brief .= 'has thanked you for ';
						break;
					default:
						$brief .= "wants to ".$type." your";
				}
				
				$brief .= " <a href='".site_url($demand->good->type.'s/'.$demand->good->id)."'>".$demand->good->title."</a>";
				$decider_summary_demands[] = $brief;
			}
			$decider_summary .= implode(" ",$decider_summary_demands);
			
			$this->Transactions[$key]->language->decider_summary = $decider_summary;
			
			// Demander summary
			$demander_summary = "";
			$demander_summary_demands = array();
			
			foreach($val->demands as $demand)
			{
				$brief="";
				$thing = " <a href='".site_url($demand->good->type.'s/'.$demand->good->id)."'>".$demand->good->title."</a>";
				
				switch($demand->type)
				{
					case "take":
						$brief = "You asked for ".$thing." from ";
						break;
					case "borrow":
						$brief = "You asked to borrow ".$thing." from ";
						break;
					case "fulfill":
						$brief = "to fulfill ".$thing;
						break;
					case "share":
						$brief = "You offered to share ".$thing." with ";
						break;
					case "give":
						$brief = "You offered to give ".$thing." to ";
						break;
					case "thank":
						$brief = "You thanked ".$decider_link." for ".$thing;
					default:
						$brief = "want to ".$type." your ".$thing." to ";
				}
				
				if($demand->type != "fulfill" || $demand->type != 'thank')
				{
					$brief .= $decider_link;
				}
				
				$demander_summary_demands[] = $brief;
			}
			$demander_summary .= implode(" ",$demander_summary_demands);
			
			$this->Transactions[$key]->language->demander_summary = $demander_summary;
			
			//Overview_summary
			//Hans - I think the only time we would use the overview summary is in a 'news feed'
			// like context. I don't think it is a good idea to publish active transactions in the news feed, but completed ones should be fine
			// So I re-wrote this to be completed overview, refering to transactions in past tense
			$overview_summary = "";
			$overview_summary_demands = array();
			
			
			foreach($val->demands as $demand)
			{
				$brief = "";
				$thing = " <a href='".site_url($demand->good->type.'s/'.$demand->good->id)."'>".$demand->good->title."</a>";
				
				if($demand->type != "fulfill")
				{
					$brief = $demander_link;
				}
				
				switch($demand->type)
				{
					case "take":
						$brief .= " received ".$thing." from ";
						break;
					case "borrow":
						$brief .= " borrowed ".$thing." from ";
						break;
					case "fulfill":
						$brief .= " to fulfill ".$thing;
						break;
					case "share":
						$brief .= " share ".$thing." with ";
						break;
					case "give":
						$brief .= " gave ".$thing." to ";
						break;
					case 'thank':
						$brief .= " thanked ".$decider_link." for ".$thing;
					default:
						$brief .= " want to ".$type." your ".$thing." to ";
				}
				if($demand->type != "fulfill" || $demand->type != 'thank')
				{
					$brief .= $decider_link;
				}
				
				$overview_summary_demands[] = $brief;
			}
			$overview_summary .= implode(" ",$overview_summary_demands);
			$this->Transactions[$key]->language->overview_summary = $overview_summary;
			
			// Set type
			$this->Transactions[$key]->type = (count($val->demands)>1) ? "Fulfill" : $val->demands[0]->type;
		}
		Console::logSpeed("Transaction_factory::set_language(): done.");
	}

	
	/**
	*	Returns a list of object IDs, useful for creating SQL WHERE IN clauses.
	*
	*	@param string $type		transactions,demands,users,goods or reviews
	*	@return array
	*/
	function get_ids($type)
	{
		return $this->ids[$type];
	}
}
/*
JSON Prototype

var transactions = [
	{
		status: '',
		demands: [
			{
				type: '',
				good: {
					id: '',
					type: '',
					user: {}
				}
			},
			{}...
		],
		demander: {
			id: '',
			screen_name: ''
		},
		decider: {
			id: '',
			screen_name: ''
		},
		language: {
			demander_summary: '',
			decider_summary: ''
		}
	},
	{}
];
*/

?>
