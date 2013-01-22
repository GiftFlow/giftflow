<?php
class You extends CI_Controller {

	var $data;
	var $has_reviewed = FALSE;
	//var $hook_data;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->load->library('Search/Review_search');
		$this->load->library('Search/Transaction_search');
		$this->load->library('Search/Good_search');
		$this->load->library('Search/Thankyou_search');
		$this->load->library('datamapper');
		$this->load->library('Search/Message_search');
		$this->load->library('Search/User_search');
		$this->load->library('market');
		
		if(!$this->data['logged_in']) 
		{
			$this->session->set_userdata('visitor_redirect_url', current_url());

		}
		$this->auth->bouncer(1);
		
	}

	function index()
	{
		$this->activity();
	}

	/* displays activity feed */
	function activity()
	{
		$this->load->library('Search/event_search');

		$E = new Event_search();
		$this->data['activity'] = $E->get_events(array(
			'event_type_id' => array(17,2),
			'limit' => 40
		));


		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['title'] = 'Your Dashboard';
		
		// Load views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/activity', $this->data);
		$this->load->view('footer');
		
	}

	
	/**
	*	Your gifts or needs
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

		Console::logSpeed('You::list_goods()');
		
		$G = new Good_search;
		$this->data['goods'] = $G->find(array(
			"user_id" => $this->data['logged_in_user_id'],
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


	/*
	 * Manages user inbox
	 * three main objects - transactions (aka gifts), thanks and messages
	 */	

	public function inbox()
	{
		
		//mark all messages as read
		//$this->util->clearActiveInbox($this->data['logged_in_user_id']);

		//Load Thankyou
		$this->load->library('Search/Thankyou_search');
		$TY = new Thankyou_search;
		$thank_status = array(
			'pending'=> 0,
			'accepted'=>0,
			'declined'=> 0
		);
		$this->data['thank_status'] = $thank_status;
	
		$thanks = $TY->find(array(
			'recipient_id'=>$this->data['logged_in_user_id'],
			'status' => array_keys($thank_status),
		));

		foreach($thanks as $val) {
			$thank_status[$val->status] += 1;
		}
		$this->data['thanks'] = $thanks;


		//Load Threads
		//@todo Threads need an easy way to identify if they are unread or not
		$M = new Message_search();
		$threads = $M->get_threads(array(
			'user_id'=> $this->data['logged_in_user_id']
		));
		$this->data['threads'] = $threads;



		//Load Transactions		
		// Load Libraries
		$TS = new Transaction_search;
		
		//some last minute arranging of the transactions
		$trans_status = array(
			"active" => 0,
			"completed" => 0,
			"cancelled" => 0
		);
		$this->data['trans_status'] = $trans_status;
				
		// Compile Search Options
		$options = array(
			"user_id"=>$this->data['logged_in_user_id'],
			"transaction_status"=>array_keys($trans_status),
			"limit"=> '2000'
		);
		$transactions = $TS->find($options);
					
		foreach($transactions as $val) {
			
			$trans_status[$val->status] += 1;

			if($val->demander->id == $this->data['logged_in_user_id']) {
				$val->is_demander = TRUE;
				$val->other_user = $val->decider;
			} else {
				$val->is_demander = FALSE;
				$val->other_user = $val->demander;
			}
		}
		$this->data['transactions'] = $transactions;


		//create flag for whether inbox is empty and should be replaced by welcome view
		$this->data['show_welcome'] = (count($thanks) + count($threads) + count($transactions) < 1)? TRUE : FALSE;
		$this->data['welcome_view'] = $this->load->view('you/welcome_view', $this->data, TRUE);

		$this->data['counts'] = array(
			'gifts' => array(
				'total' => count($transactions),
				'active' => $trans_status['active'],
				'completed' => $trans_status['completed'],
				'cancelled' => $trans_status['cancelled'],
			),
			'thanks' => array(
				'total' => count($thanks),
				'pending' => $thank_status['pending'],
				'accepted' => $thank_status['accepted'],
				'declined' => $thank_status['declined']
			),
			'conversations' => count($threads),
		);

		// Set view variables
		$this->data['title'] = "Inbox";
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Load Views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('you/inbox', $this->data);	
		$this->load->view('footer', $this->data);
		
	}

	public function view_transaction( $id )
	{
		Console::logSpeed("You::view_transaction()");
		
		
		// Loading libraries
		$this->load->library('Messaging/Conversation');
		$this->load->helper('form');
		$this->load->helper('language');
//		$this->lang->load("transactions","en");
		
		// Load Transaction, User & Review Data
		
		// Load User model of logged in user
		$U = new User($this->data['logged_in_user_id']);

		//Load Transaction model
		$T_model = new Transaction($id);
				
		// Load Transaction data object
		$TS = new Transaction_search;
		$T_result = $TS->get(array(
			"transaction_id"=>$id,
			"include_events"=>TRUE
		));
		$this->data['transaction'] = $T_result;

		//Security Check
		//Only allow transaction participants to view transaction page
		$is_participant = FALSE;
		for($i=0; $i<2; $i++) {
			if($T_result->users[$i]->id == $this->data['logged_in_user_id']) {
				$is_participant = TRUE;
			}
		}
		if(!$is_participant) {
			$this->session->set_flashdata('error', 'Sorry an error has occured');
			redirect('welcome/home');
		}
					
		// Processing $_POST Data
		if(!empty($_POST))
		{
			// New message
			if($_POST['form_type'] == "transaction_message")
			{
				// New Message + No Status Change
				$messaged = $this->market->message(array(
					"transaction_id"=>$id,
					"body"=>$_POST['body']
				));
				
				if($messaged)
				{
					$this->session->set_flashdata('success','Message Sent!');
				}
				redirect('you/view_transaction/'.$id);
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

		
			
		//Set data for delete link, allowing user to easily delete good after transaction is over	
		$this->data['delete_link'] = site_url()."/".$T_result->demands[0]->good->type."s/".$T_result->demands[0]->good->id."/disable";

		$this->data['is_owner'] = ($T_result->demands[0]->good->user->id == $this->data['logged_in_user_id'])? TRUE : FALSE;
		$this->data['delete_prompt'] = ($T_result->demands[0]->good->type == 'gift')? "Can you give this gift again? If not, click this button to delete it." : "Has your need been fulfilled? If so, click this button to delete it.";

		//Check for already existing review written by logged in user
		$this->data['has_reviewed'] = $T_model->has_review_by_user($U->id);
		$this->data['other_reviewed'] = $T_model->has_review_by_user($this->data['other_user']->id);
		$this->data['both_reviews'] = $T_model->has_both_reviews();
		
		$this->data['helper_text'] = $this->data['transaction_role']."_".$this->data['transaction']->status;

		
		//If existing review found, load it
		if($this->data['has_reviewed'])
		{
			$RS = new Review_search;
			$this->data['reviews'] = $RS->find(array(
				"transaction_id" => $id
			));
		}		
		
		//Prepare review data for View
		if($this->data['has_reviewed']) 
		{
			$this->data['helper_text'] .= '_reviewed';

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

		$this->data['helper_text'] = json_encode($this->data['helper_text']);

		// Title
		$this->data['title'] = "Gift  with ".$this->data['other_user']->screen_name;


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
			"title"=>$this->data['title'],
		);


		// Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['review_form'] = $this->load->view('you/includes/review_form', $this->data, TRUE);
		
		// Load form validation plugin
		$this->data['js'][] = 'jquery-validate.php';

		
		// Load Views
		$this->load->view('header', $this->data);
		//$this->load->view('you/includes/header', $this->data);
		$this->load->view('you/transaction', $this->data);	
		$this->load->view('footer', $this->data);
		
	}


	/* Updates transaction status to cancelled or completed
	 * Called from cancel and complete buttons on you/transaction page
	 * @params $transaction_id and $status
	 *
	 * @author Hans Schoenburg
	 */
	function update_transaction($status, $id)
	{
		if(empty($id) || empty($status)) {
			$this->session->set_flashdata('error', 'Error updating interaction');
			redirect('you/index');
		}

		$T = new Transaction_search();

		//make sure transaction belongs to user
		if($T->check_user(array('user_id' => $this->session->userdata['user_id'],'transaction_id' => $id))) {
			
				// Execute cancellation, call 'transaction_cancelled' hook
				// if succssful

			if($status == 'cancelled') {	
				$result = $this->market->cancel(array(
					"transaction_id"=>$id,
					"message"=>$this->input->post('body')
				));
				$this->session->set_flashdata('success', 'Gift interaction cancelled');
			} else if($status == 'completed') {
				
				$result = $this->market->complete(array(
					"transaction_id"=>$id,
				));
				$this->session->set_flashdata('success', 'Gift interaction completed!');
			} else {
				show_error('Error updating transaction');
			}


			// If update successful, set flashdata
			if($result)
			{
				// Set success flashdata and refresh page
				redirect('you/view_transaction/'.$id);
			} else {
				$this->session->set_flashdata('error', 'Error cancelling interaction, please contact info@giftflow.org');
				redirect('you/index/');
			}

		} else {
			$this->session->set_flashdata('error', 'You do not have permission to cancel this interaction');
			redirect('you/index');
		}
	}
		
	public function reviews($include)
	{
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

	public function following()
	{

		$F = new User_search();

		$this->data['following'] = $F->following(array(
			'user_id' => $this->session->userdata['user_id']
		));
		
		$this->data['followers'] = $F->following(array(
			'user_id' => $this->session->userdata['user_id']
		));
		
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['title'] = "Following";
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header', $this->data);
		$this->load->view('you/following',$this->data);
		$this->load->view('footer', $this->data);

	}
	public function view_thankyou($id)
	{
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
				$event_data = $TY->get(array('id'=> $T->id));
				$event_data->decision = $decided;

				$this->event_logger->basic('thankyou_updated', $event_data);
				$this->notify->thankyou_updated($event_data);
				
				$this->session->set_flashdata('success','Thank You '.$decided);
				redirect('you/view_thankyou/'.$thankyou_id);
			}
		}
				
		$T = new Thankyou_search();
		$thankyou = $T->find(array('id'=>$id));
		$this->data['thankyou'] = $thankyou[0];
	

		// Breadcrumbs
		$this->data['breadcrumbs'][] = array(
			"title"=>"You", 
			"href"=>site_url('you')
		);
		$this->data['breadcrumbs'][] = array (
			"title"=>"Thanks",
			"href" =>site_url('you/inbox')
		);
		$this->data['breadcrumbs'][] = array (
			"title"=>'from '.$this->data['thankyou']->thanker_screen_name
		);


		// Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['title'] = 'Thank you';
		
		$this->load->view('header',$this->data);
		$this->load->view('you/includes/header', $this->data);
		$this->load->view('you/thankyou',$this->data);
		$this->load->view('footer',$this->data);


	}

	public function view_thread($id) 
	{
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
	*	Loads both the Add a Gift and the Add a Need forms.
	*	Which form is loaded varies based on the $type variable.
	*	@param string $type		Type of good to create ('gift' or 'need')
	*/
	public function add_good($type = NULL)
	{
		if(!empty($_GET['type'])) {
			$type = $this->input->get('type');
		}
		if(empty($type)) {
			show_error('Error determining type');
		}

		$this->data['default_location'] = $this->data['userdata']['location']->address;

		$this->data['add']=TRUE;
		$this->data['categories'] = $this->db->order_by("name","ASC")
			->get("categories")
			->result();
		$this->data['type'] = $type;
		$this->data['question'] = ($type == 'gift') ? 'What do you want to give?' : 'What do you need?';
		$this->data['title'] = ($type == 'gift') ? 'Add a Gift' : 'Add a Need';
		$this->data['default_location'] = $this->data['userdata']['location']->address;
		
		// Load Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['js'][] = 'jquery-validate.php';
		$this->data['js'][] = 'GF.Tags.js';
		$this->data['js'][] = 'GF.Locations.js';
		
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

	public function add_thank()
	{
		$this->auth->bouncer('1');
		$this->data['js'][] = 'GF.Users.js';
		$this->data['title'] = 'Add a thank!';

		$this->data['menu'] = $this->load->view('you/includes/menu', $this->data, TRUE);

		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header', $this->data);
		$this->load->view('you/add_thank', $this->data);
		$this->load->view('footer', $this->data);
	}
}
