<?php
class You extends CI_Controller {

	var $data;
	var $has_reviewed = FALSE;
	var $hook_data;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals($options = array('geocode_ip' => TRUE));
		$this->auth->bouncer(1);
		$this->data['welcome'] = FALSE;
     //See if user has active or pending transactions
    //If so, light up the Your Inbox menu option
    $this->data['trans_check'] = FALSE;
		$this->load->library('Search/Transaction_search');
		$TS = new Transaction_search;
		
		// Compile Search Options
		$options = array(
			"user_id"=>$this->data['logged_in_user_id'],
			"transaction_status"=>array(
				"active",
				"pending"
			),
			"limit"=>200
		);
		$trans_checker = $TS->find($options);
	  if(count($trans_checker) > 0)
    {
      $this->data['trans_check'] = TRUE;
      $this->data['new_trans'] = count($trans_checker);
    }  
		
	}

	/**
	*	User dashboard
	*/
	function index()
	{
		Console::logSpeed('You::index()');
		
		// Load libraries
		$this->load->library('Search/Good_search');
		$GS = new Good_search();
		
		$options = array (
			"location" => $this->data['userdata']['location'], 
			"limit"=>10, 
			"type"=>'gift'
		);
		
		$this->data['giftflow'] = $GS->find($options);
	
		
		$this->data['your_gifts'] = $GS->find(array(
			"type"=>"gift",
			"user_id"=>$this->data['logged_in_user_id']
		));
		
		$this->data['your_needs'] = $GS->find(array(
			"type"=>"need",
			"user_id"=>$this->data['logged_in_user_id']
		));
		
		//Load event reader for activity feed
		$this->load->library('event_reader');
		$E = new Event_reader();
    $options = array(
      'event_type_id' => array(2,8),
      'limit' => 100,
      'location' => $this->data['userdata']['location']
      );
				
    //Total hack job, rigging the event feed to show more completed transactions
		$unsorted_events = $E->get_events($options);	

		// TODO: get_events should return an empty array instead of false
		
		if ($unsorted_events != FALSE) {	
			foreach($unsorted_events as $raw)
			{
				if($raw->event_type_id == 2)
				{
					$this->data['events'][] = $raw;
				}
				$sorter = rand() % 7;
				if($raw->event_type_id == 8 && $sorter == 0 )
				{   
					$this->data['events'][] = $raw;
				}
			}
		}

		// Set view variables
		$this->data['title'] = "Your Dashboard";
		$this->data['googlemaps'] = TRUE;
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Load Views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/index2', $this->data);
		$this->load->view('footer', $this->data);
		
		Console::logSpeed('You::index(): done.');
	}
	
	function welcome()
	{
		//detects when user clicks 'welcome' link in you/menu
		if(!empty($_GET) && $_GET['welcome'] == 'show')
		{
			return $this->show_welcome();
		}
		//determine whether to show welcome page when user is logging in/registering
		else
		{
			//Check if they've hidden the welome pag
			$this->db->select('E.id AS event_id, 
								E.event_type_id AS event_type,
								E.user_id AS user_id')
						->from('events AS E')
						->where('E.user_id', $this->data['logged_in_user_id'])
						->where('E.event_type_id',13);
						$result = $this->db->get()->result();
			//If event hide_welcome is not there, then show welcome			
			if(empty($result))
			{
				$this->data['welcome'] = TRUE;
				return $this->show_welcome();
			}
			else
			{
				redirect('you');
			}
		}
		
	}
	
	function show_welcome()
	{
		$this->data['title'] = "Welcome";
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/welcome', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	/**
	*	Your gifts
	*/
	function gifts()
	{
		Console::logSpeed('You::gifts()');
		
		$this->load->library('Search/Good_search');

		$G = new Good_search;
		$this->data['goods'] = $G->find(array(
			"user_id" => $this->data['logged_in_user_id'],
			"count_transactions" => TRUE,
			"type"=>"gift"
		));
		
		// Set view variables
		$this->data['title'] = "Your Gifts";
		$this->data['js'][] = "GF.Tags.js";
		$this->data['js'][] = "jquery-validate.php";
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['type'] = "gift";
		$this->data['user_default_location'] = $this->data['userdata']['location']->address;
		$this->data['categories'] = $this->db->order_by("name","ASC")
			->get("categories")
			->result();
		
    $this->data['addthis'] = TRUE;

		// Load views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/goods', $this->data);
		$this->load->view('footer');
		
		Console::logSpeed('You::gifts(): done.');
	}
	
	public function needs()
	{
		$this->load->library('Search/Good_search');
		$G = new Good_search;
		
		$this->data['goods'] = $G->find(array(
			"user_id" => $this->data['logged_in_user_id'],
			"count_transactions" => TRUE,
			"type"=>"need",
			"limit"=>100
		));
		
		// Set view variables
		$this->data['title'] = "Your Needs";
		$this->data['js'][] = "GF.Tags.js";
		$this->data['js'][] = "jquery-validate.php";
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['type'] = "need";
		$this->data['user_default_location'] = $this->data['userdata']['location']->address;
		$this->data['categories'] = $this->db->order_by("name","ASC")
			->get("categories")
			->result();
		
		
		// Load views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/goods', $this->data);	
		$this->load->view('footer', $this->data);
	}
	
	public function transactions( $id = NULL )
	{
		// If ID provided, load individual transaction
		if(!empty($id))
		{
			return $this->_view_transaction($id);
		}
		
		// Load Libraries
		$this->load->library('Search/Transaction_search');
		$TS = new Transaction_search;
		
		// Compile Search Options
		$options = array(
			"user_id"=>$this->data['logged_in_user_id'],
			"transaction_status"=>array(
				"active",
				"pending",
				"declined",
				"completed",
				"cancelled"
			),
			"limit"=>200
		);
		if(!empty($_GET['good_id']))
		{
			$options['good_id'] = $_GET['good_id'];
		}
		if(!empty($_GET['status']))
		{
			$options['transaction_status'] = $_GET['status'];
		}
		$this->data['transactions'] = $TS->find($options);
		
		//Sort into "incoming" and "outgoing" arrays
		if(!empty($this->data['transactions']) && ($this->input->get("direction")=="incoming" || $this->input->get("direction")=="outgoing"))
		{
			$sorted = array();
			foreach($this->data['transactions'] as $key=>$val)
			{
				$is_outgoing = ($val->demander->id == $this->data['logged_in_user_id']);
				if(($_GET['direction']=="outgoing" && $is_outgoing) || ($_GET['direction']=="incoming" && !$is_outgoing))
				{
					$sorted[] = $val;
				}
			}
			$this->data["transactions"] = $sorted;
		}
		
		// Set view variables
		$this->data['title'] = "Marketplace";
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Breadcrumbs
		$this->data['breadcrumbs'][0] = array(
			"title"=>"You", 
			"href"=>site_url('you')
		);
		
		$this->data['breadcrumbs'][1] = array (
			"title"=>"Marketplace"
		);
		
		// Breadcrumbs for filtered results
		
		// Filtering by good ID
		if(!empty($_GET['good_id']))
		{
			$this->data['breadcrumbs'][1]['href'] = site_url('you/transactions');
			
			$this->data['breadcrumbs'][2] = array(
				"title"=>"Good #".$_GET['good_id']
			);
		}
		
		// Filtering by direction
		elseif(!empty($_GET['direction']))
		{
			$this->data['breadcrumbs'][1]['href'] = site_url('you/transactions');
			
			$this->data['breadcrumbs'][2] = array(
				"title"=>ucfirst($_GET['direction'])." Transactions"
			);
		}
		
		// Load Views
		$this->load->view('header', $this->data);
		$this->load->view('you/transactions', $this->data);	
		$this->load->view('footer', $this->data);
		
	}

	public function _view_transaction( $id )
	{
		Console::logSpeed("You::_view_transaction()");
		
		// Loading libraries
		$this->load->library('datamapper');
		$this->load->library('Search/User_search');
		$this->load->library('Search/Review_search');
		$this->load->library('Search/Transaction_search');
		$this->load->library('Messaging/Conversation');
		$this->load->helper('form');
		$this->load->helper('language');
		$this->lang->load("transactions","english");
		
		// Load Transaction, User & Review Data
		
		// Load User model of logged in user
		$U = new User($this->data['logged_in_user_id']);

		//Load Transaction model
		$T = new Transaction($id);
				
		// Load Transaction data object
		$TS = new Transaction_search;
		$this->data['transaction'] = $TS->get(array(
			"transaction_id"=>$id,
			"include_events"=>TRUE
		));
		
		
		//Check for already existing review written by logged in user
		$this->data['has_reviewed'] = $T->has_review_by_user($U->id);
		
		//If existing review found, load it
		if($this->data['has_reviewed'])
		{
			$RS = new Review_search;
			$this->data['reviews'] = $RS->find(array(
				"transaction_id" => $id
			));
		}
		
		//Check if both users have submitted a review
		$this->data['both_reviews'] = $T->has_both_reviews();

		// Done Loading Data
		
		// Processing $_POST Data
		if(!empty($_POST))
		{
		
			$this->load->library('market');				
				
			// New message
			if($_POST['form_type'] == "transaction_message")
			{
				
				// New Message + Status Change
				if(!empty($_POST['decision']))
				{
					if($_POST['decision'] == "accept")
					{
						// Activate / change status to `active`
						$activated = $this->market->activate(array(
							"transaction_id"=>$T->id,
							"message"=>$this->input->post('body')
						));
						if($activated)
						{
							$this->session->set_flashdata('success','Transaction activated.');
						}

					}
					elseif($_POST['decision'] == "decline")
					{
						// Decline / change status to `declined`
						$declined = $this->market->decline(array(
							"transaction_id"=>$T->id,
							"message"=>$this->input->post('body')
						));
						if($declined)
						{
							$this->session->set_flashdata('success','Transaction declined.');
						}
					}
				}
				
				// New Message + No Status Change
				else
				{
					$messaged = $this->market->message(array(
						"transaction_id"=>$id,
						"body"=>$_POST['body']
					));
					
					if($messaged)
					{
						$this->session->set_flashdata('success','Message Sent!');
					}
				}
				redirect('you/transactions/'.$id);
			}
			
			// Accept/Decline transaction, 
			elseif($_POST['form_type'] == "decide_transaction")
			{
				$decision = ($_POST['decision'] == 'Accept') ? 'activate' : 'decline';
				
				$success = $this->market->$decision(array(
					"transaction_id"=>$id,
					"message"=>$this->input->post('body')
				));
				
				// If decision successful, set flashdata
				if($success)
				{
					// Note: hook 'transaction_'activate/decline has been called from 
					// within Market()

					// Set success flashdata and refresh page
					$this->session->set_flashdata('success','Transaction '.$decision.'d');
					redirect('you/transactions/'.$id);
				}
				
				else
				{
					// @todo handle unsuccssful decisions
				}
			}
			
			// New Review
			elseif($_POST['form_type'] == "transaction_review_new")
			{
				$reviewed = $this->market->review(array(
					"transaction_id"=>$id,
					"body"=>$this->input->post('body'),
					"rating"=>$this->input->post('rating'),
					"reviewer_id"=>$this->data['logged_in_user_id']
				));
				
				if($reviewed)
				{
					$this->session->set_flashdata('success','Review Saved!');
					redirect('you/transactions/'.$id);
				}
				else
				{
					// @todo handle error
				}

			}
			
			// Cancel transaction
			elseif($_POST['form_type'] == "transaction_cancel")
			{
				// Execute cancellation, call 'transaction_cancelled' hook
				// if succssful
				$cancelled = $this->market->cancel(array(
					"transaction_id"=>$id,
					"message"=>$this->input->post('body')
				));
				
				// If cancellation successful, set flashdata
				if($cancelled)
				{
					// Note: hook 'transaction_cancelled' has been called from 
					// within Market::cancel()

					// Set success flashdata and refresh page
					$this->session->set_flashdata('success','Transaction cancelled!');
					redirect('you/transactions/'.$id);
				}
				
				else
				{
					// @todo handle unsuccssful cancellations
				}
			}
		}
		
		// Load Conversation & Transaction messages
		$C = new Conversation;
		$C->transaction_id = $id;
		$C->get();
		$this->data['transaction']->messages = $C->messages;
		$this->data['conversation'] = $C;

		// Determine logged in user's role in this transaction
		if($this->data['transaction']->demander->id == $this->data['logged_in_user_id'])
		{
			$this->data['transaction_role'] = "demander";
			$this->data['demander'] = TRUE;
			$this->data['other_user'] = $this->data['transaction']->decider;
			$this->data['current_user'] = $this->data['transaction']->demander;
		}
		else
		{
			$this->data['transaction_role'] = "decider";
			$this->data['demander'] = FALSE;
			$this->data['other_user'] = $this->data['transaction']->demander;
			$this->data['current_user'] = $this->data['transaction']->decider;

		}
		//Prepare review data for View
		if($this->data['has_reviewed']) 
			{
				foreach($this->data['reviews']['reviews'] as $key=>$val)
				{ 
					if($val->reviewer_id == $this->data['logged_in_user_id']) 
					{
						$your_review = "Your review <br />";
						$your_review .= "Rating: ".$val->rating."<br />";
						$your_review .= $val->body."<hr /><br />";
						$this->data['your_review'] = $your_review;
					}
					elseif($val->reviewer_id != $this->data['logged_in_user_id'])
					{
						if($this->data['transaction_role'] == "demander")
						{
							$other_review = $this->data['transaction']->demander->screen_name;
						}
						elseif($this->data['transaction_role'] == "decider")
						{
							$other_review = $this->data['transaction']->decider->screen_name;
						}
								
						$other_review .= "<br /> Rating: ".$val->rating."<br />".$val->body."<hr /><br />";
						$this->data['other_review'] = $other_review;
					}
					
				}				
			}
		
		// Hook: `transaction_viewed`
		$hook_data = (object) array(
			"user_id"=>$this->data['logged_in_user_id'],
			"transaction_id"=>$id
		);
		$this->hooks->call('transaction_viewed', $hook_data);
		
		Console::logSpeed("You::_view_transaction(): transaction_viewed hook fired, preparing views");
		
		// Title
		$this->data['title'] = "Transaction with ".$this->data['other_user']->screen_name;
				
		// Breadcrumbs
		$this->data['breadcrumbs'][] = array(
			"title"=>"You", 
			"href"=>site_url('you')
		);
		$this->data['breadcrumbs'][] = array (
			"title"=>"Transactions",
			"href" =>site_url('you/transactions')
		);
		$this->data['breadcrumbs'][] = array (
			"title"=>$id
		);
				
		// Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Load Views
		$this->load->view('header', $this->data);
		$this->load->view('you/transaction', $this->data);	
		$this->load->view('footer', $this->data);
		
	}
	
	public function reviews($include)
	{
		//$include is a boolean for whether or not to include transactions in results
		$this->load->library('Search/Review_search');
		$this->load->library('Search/Transaction_search');
		$R = new Review_search();
		$options = array(
			"transaction_id"=>'',
			"user_id" => $this->data['logged_in_user_id'],
			"reviewed_id"=>'',
			"reviewer_id"=>'',
			"transaction_status"=>"completed",
			"rating" => '',
			"include_transactions" => $include
		);
		
		$results = $R->find($options);
		return $results;
	}
	
	/**
	*	Loads both the Add a Gift and the Add a Need forms.
	*	Which form is loaded varies based on the $type variable.
	*	@param string $type		Type of good to create ('gift' or 'need')
	*/
	public function add_good()
	{
		$type = !empty($_GET['type']) ? $_GET['type'] : "gift";
		
		$this->data['categories'] = $this->db->order_by("name","ASC")
			->get("categories")
			->result();
		$this->data['type'] = $type;
		$this->data['question'] = ($type == 'gift') ? 'What do you want to give?' : 'What do you need?';
		$this->data['title'] = ($type == 'gift') ? 'Add a Gift' : 'Add a Need';
		$this->data['user_default_location'] = $this->data['userdata']['location']->address;
		
		// Load Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['js'][] = 'jquery-validate.php';
		$this->data['js'][] = 'GF.Tags.js';
		
		// Load views
		if($this->data['is_ajax'])
		{
			$this->load->view('you/includes/add_good_form', $this->data);
		}
		else
		{
			$this->data['form'] = $this->load->view('you/includes/add_good_form',$this->data,TRUE);
			$this->load->view('header', $this->data);
			$this->load->view('you/includes/header',$this->data);
			$this->load->view('you/add_good', $this->data);
			$this->load->view('footer', $this->data);
		}

	}
}
