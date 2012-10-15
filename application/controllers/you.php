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
		$this->data['welcome'] = FALSE;
		$this->load->library('Search/Review_search');
		$this->load->library('Search/Transaction_search');
		$this->load->library('Search/Good_search');
		$this->load->library('Search/Thankyou_search');
		$this->load->library('datamapper');
		$this->load->library('Search/Message_search');
		$this->load->library('Search/User_search');
		
	}

	/**
	*	DEPRECATED - REROUTED TO INBOX
	*/
	function index()
	{

		$this->auth->bouncer(1);
		return $this->inbox();
	/*	
		// Load libraries
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


			//In case they have no transactions
		$this->data['welcome_view'] = $this->load->view('you/welcome_view', $this->data, TRUE);

		// Set view variables
		$this->data['title'] = "Your Dashboard";
		$this->data['googlemaps'] = TRUE;
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Load Views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/inbox', $this->data);
		$this->load->view('footer', $this->data);
		
		Console::logSpeed('You::index(): done.');
	 */
	}
	
	function welcome()
	{
		$this->auth->bouncer(1);
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
		$this->auth->bouncer(1);
		$this->data['title'] = "Welcome";
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/welcome', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	/**
	*	Your gifts
	*	Takes GET param of type - singular gift or need
	*/
	function list_goods($type = 'gift')
	{
		$get = NULL;
		$shareId = NULL;

		if(!empty($_GET))
		{
			$get = $this->input->get();
			$type = (isset($get['type'])) ? $get['type'] : 'gift';
			$shareId = (isset($get['id'])) ? $get['id'] : NULL;
		}

		$this->auth->bouncer(1);
		Console::logSpeed('You::list_goods()');
		
		$G = new Good_search;
		$this->data['goods'] = $G->find(array(
			"user_id" => $this->data['logged_in_user_id'],
			"count_transactions" => TRUE,
			"type"=>$type,
            'status' => 'active'
		));
		
		// Set view variables
		$this->data['title'] = "Your ".ucfirst($type)."s";
		$this->data['js'][] = "GF.Tags.js";
		$this->data['js'][] = "jquery-validate.php";
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['type'] = $type;
		$this->data['user_default_location'] = $this->data['userdata']['location']->address;
		$this->data['categories'] = $this->db->order_by("name","ASC")
			->get("categories")
			->result();

		//If id is passed via GET, load sharing modal window
		$promptShare = (isset($shareId))? TRUE : FALSE;
		$this->data['promptShare'] = json_encode($promptShare);
		$this->data['shareId'] = $shareId;
		
		// Load views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/goods', $this->data);
		$this->load->view('footer');
		
		Console::logSpeed('You::list_goods(): done.');
	}
	
	function watches()
	{
		$this->auth->bouncer(1, 'you/watches');
		$this->load->model('watch');

		// Execute tag search
		$this->data['watches'] = $this->watch->get_mine($this->data['logged_in_user_id']);
	
		// Set view variables
		$this->data['title'] = "My Watches";
		
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// show the form for adding new watches
		$this->data['form'] = $this->load->view('you/includes/add_watch_form',$this->data,TRUE);

		// Load views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/watches', $this->data);
		$this->load->view('footer', $this->data);
	}


	//opens the Inbox 

	public function inbox()
	{
		
		$this->auth->bouncer(1);
		//Load Thankyou
		$this->load->library('Search/Thankyou_search');
		$TY = new Thankyou_search;
		$this->data['thanks'] = $TY->find(array(
			'recipient_id'=>$this->data['logged_in_user_id']
		));

		//Load Threads
		$M = new Message_search();
		$this->data['threads'] = $M->get_threads(array('user_id'=> $this->data['logged_in_user_id']));

		//Load Transactions		
		// Load Libraries
		$TS = new Transaction_search;
		
		// Compile Search Options
		$options = array(
			"user_id"=>$this->data['logged_in_user_id'],
			"transaction_status"=>array(
				"active",
				"pending",
				"completed"
			),
			"limit"=> '2000'
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

		$this->data['welcome_view'] = $this->load->view('you/welcome_view', $this->data, TRUE);

		//Messaging system IN PROGRESS!!!!
		$M = new Message_search();
		$this->data['threads'] = $M->get_threads(array('user_id'=> $this->data['logged_in_user_id']));

		// Set view variables
		$this->data['title'] = "Inbox";
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Breadcrumbs
		/*$this->data['breadcrumbs'][0] = array(
			"title"=>"You", 
			"href"=>site_url('you')
		);
		
		$this->data['breadcrumbs'][1] = array (
			"title"=>"Inbox"
		);
		
		// Breadcrumbs for filtered results
		
		// Filtering by good ID
		if(!empty($_GET['good_id']))
		{
			$this->data['breadcrumbs'][1]['href'] = site_url('you/inbox');
			
			$this->data['breadcrumbs'][2] = array(
				"title"=>"Good #".$_GET['good_id']
			);
		}
		 */	
		// Load Views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/inbox', $this->data);	
		$this->load->view('footer', $this->data);
		
	}

	public function view_transaction( $id )
	{
		$this->auth->bouncer(1);
		Console::logSpeed("You::view_transaction()");
		
		// Loading libraries
		$this->load->library('Messaging/Conversation');
		$this->load->helper('form');
		$this->load->helper('language');
		$this->lang->load("transactions","en");
		
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
							$this->session->set_flashdata('success','Gift activated.');
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
							$this->session->set_flashdata('success','Gift  declined.');
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
				redirect('you/view_transaction/'.$id);
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
					$this->session->set_flashdata('success','Gift '.$decision.'d');
					redirect('you/view_transaction/'.$id);
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
					"reviewer_id"=>$this->data['logged_in_user_id'],
					'hook' => 'review_new'
				));
				
				if($reviewed)
				{
					$this->session->set_flashdata('success','Review Saved!');
					redirect('you/view_transaction/'.$id);
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
					$this->session->set_flashdata('success','Gift cancelled!');
					redirect('you/view_transaction/'.$id);
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
		
		/**
		*	Mark notifications as read
		*	Note: handwritten SQL query used because the active record library
		*	appears not to support the usage of JOIN clauses in UPDATE queries
		*/
		Console::logSpeed("Notify::transaction_viewed()");
		$this->db->query("UPDATE `notifications` AS N JOIN events AS E ON N.event_id=E.id SET `N`.`enabled` = 0 WHERE `E`.`transaction_id` = ? AND `N`.`user_id` = ?",array($id, $this->data["logged_in_user_id"]));		

		// Title
		$this->data['title'] = "Gift  with ".$this->data['other_user']->screen_name;
	/*	
		// Breadcrumbs
		$this->data['breadcrumbs'][] = array(
			"title"=>"You", 
			"href"=>site_url('you')
		);
		$this->data['breadcrumbs'][] = array (
			"title"=>"Inbox",
			"href" =>site_url('you/inbox')
		);
		$this->data['breadcrumbs'][] = array (
			"title"=>$id,
			'href' => site_url('you/view_transaction/'.$id)
		);
	 */			
		// Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['review_form'] = $this->load->view('you/includes/review_form', $this->data, TRUE);
		
		// Load form validation plugin
		$this->data['js'][] = 'jquery-validate.php';

		
		// Load Views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header', $this->data);
		$this->load->view('you/transaction', $this->data);	
		$this->load->view('footer', $this->data);
		
	}
	
	public function reviews($include)
	{
		$this->auth->bouncer(1);
		//$include is a boolean for whether or not to include transactions in results
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


	public function view_thankyou($id)
	{
		$this->auth->bouncer(1);
		//accept or decline thankyou
		if(!empty($_POST)) {	
			$thankyou_id = $this->input->post('thankyou_id');
			$decision = $this->input->post('decision');
			$decided = '';

			$T = new Thankyou($thankyou_id);

			switch($decision) {
			case 'Accept':
				$T->status = 'accepted';
				$decided = 'accepted';
				break;
			case 'Decline':
				$T->status = 'declined';
				$decided = 'declined';
				break;
			case 'Edit':
				$T->status = 'pending';
				$decided = 'reset to pending';
				break;
			}

			if(!$T->save())
			{
				show_error('Error saving thankyou status');
			} else {

				//Nofity and log Event
				$this->load->library('event_logger');
				$this->load->library('notify');

				$TY = new Thankyou_search();
				$hook_data = $TY->get(array('id'=> $T->id));
				$hook_data->decision = $decided;

				$E = new Event_logger();
				$N = new Notify();

				$E->basic('thankyou_updated', $hook_data);
				$N->thankyou_updated('thankyou_updated', $hook_data);
					
				$this->session->set_flashdata('success','Thank You '.$decided);
				redirect('you/view_thankyou/'.$thankyou_id);
			}
		}
				
		$T = new Thankyou_search();
		$thankyou = $T->find(array('id'=>$id));
		$this->data['thankyou'] = $thankyou[0];
	
		// Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		$this->load->view('header',$this->data);
		$this->load->view('you/includes/header', $this->data);
		$this->load->view('you/thankyou',$this->data);
		$this->load->view('footer',$this->data);


	}

	public function view_thread($id) 
	{
		$this->auth->bouncer(1);
		if(!empty($_POST))
		{
			$input = $this->input->post();
			$this->load->library('Messaging/Conversation');
			$C = new Conversation();
			$C->type = 'thread';
			$C->thread_id = $input['thread_id'];
			//$C->users = array($this->data['logged_in_user_id'], $input['recip_id']);

			if(!$C->compose(array(
				'body' => $input['body'],
				'user_id' => $this->data['logged_in_user_id'],
				'subject' => 'profile_message',
				'recip_id' => $input['recip_id'],
				'type' => 'thread',
				'thread_id' => $input['thread_id']
			))){

				show_error("Error saving Conversation");
			}
		}

			
		$M = new Message_search();
		$thread = $M->get_threads(array(
			'thread_id'=>$id, 
			'user_id' => $this->data['logged_in_user_id']
		));
		$this->data['thread'] = $thread[0];

		//load logged in user - need this object to match the other_user
		$U = new User_search();
		$this->data['u'] = $U->get(array('user_id'=>$this->data['logged_in_user_id']));

		// Title
		$this->data['title'] = "Conversation with ".$this->data['thread']->other_user->screen_name;
			
		// Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		$this->load->view('header',$this->data);
		$this->load->view('you/includes/header');
		$this->load->view('you/thread',$this->data);
		$this->load->view('footer',$this->data);
	}


	/**
	 * Routes requests to the add_good function 
	 *
	 */
	public function add_gift()
	{
		$type = 'gift';
		$this->add_good($type);
	}

	public function add_need()
	{
		$type = 'need';
		$this->add_good($type);
	}

	/**
	*	Loads both the Add a Gift and the Add a Need forms.
	*	Which form is loaded varies based on the $type variable.
	*	@param string $type		Type of good to create ('gift' or 'need')
	*/
	public function add_good($type = NULL)
	{
		$this->auth->bouncer('1', 'you/add_good/'.$type);
		$this->data['default_location'] = $this->data['userdata']['location']->address;

		if(!empty($_GET['type']))
		{
			$type =	$this->input->get('type');
		} 

		$this->data['add']=TRUE;
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
