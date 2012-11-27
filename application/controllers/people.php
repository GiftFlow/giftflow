<?php
/**
*	Community central will help link people to other people.
*
*	@author Brandon Jackson
*	@package Controllers
*/
class People extends CI_Controller {

	var $data;
	var $G;
	var $U;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		//$this->load->library('finder');
		$this->load->library('geo');
		$this->load->library('datamapper');
		$this->load->library('Search/User_search');
		$this->load->library('Search/Good_search');
		$this->load->library('Search/Transaction_search');
		$this->load->library('Search/Review_search');
		$this->load->library('Search/Thankyou_search');
		$this->load->library('market');
		$this->load->library('event_logger');
		$this->load->library('notify');
		
		if(!empty($this->data['logged_in_user_id']))
		{
			$this->U = new User($this->data['logged_in_user_id']);
		}
	}
	

	public function index()
	{
					redirect('find/people');
	}

	/**
	 *  Loads users facebook friends 
	 *  NON FUNCTIONAL
	 *  @todo fix this! 
	 */
	public function friends()
	{
		// Page Title
		$this->data['title'] = "People | Find Your Friends on GiftFlow";		

		if( $this->auth->is_logged_in() )
		{
			$this->U = new User($this->session->userdata['user_id']);
			
			// Search for Facebook friends
			if(!empty($this->U->facebook_id))
			{
				// Load list of friends' IDs from Facebook API
				$this->load->library('facebook');
				$friend_ids = $this->facebook->friend_ids($this->U->id);
				
				// Search for matching users
				if($friend_ids)
				{
					$this->data['friends']['facebook'] = $this->user_search->find(array(
						"facebook_id"=>$friend_ids,
						"following_stats"=>TRUE,
						"limit"=>null
					));
				}
			}
			
			// Search for Google Contacts / Gmail Friends
			if(!empty($this->U->google_token) )
			{
				// Query Google Contacts API, returning array of email addresses
				$this->load->library('google');
				$email_list = $this->google->contacts_email_list();
				
				// Search for matching users
				$this->data['friends']['google'] = $this->user_search->find(array(
					"email"=>$email_list,
					"following_stats"=>TRUE,
					"limit"=>null
				));
			}
			
		}				
		
		$this->load->view('header', $this->data);
		$this->data['menu'] = $this->load->view('people/includes/menu',$this->data, TRUE);
		$this->load->view('people/friends', $this->data);
		$this->load->view('footer', $this->data);
	}	

	/**
	 *  Displays lists of users, i.e. following, giftcircle, etc
	 * @param type $type 
	 */
	function lists($type = "following")
	{
		$this->auth->bouncer(1);
		
		$this->U = new User($this->session->userdata['user_id']);
		$GC = new User_search;
		
		if($type=="giftcircle")
		{
			$this->data['title'] = "Gift Connections";	
			$this->data['heading'] = "Members connected to you via gifts.";
			$this->data['results'] = $GC->gift_circle($options = array('user_id' => $this->U->id));

		}
		elseif($type=="following")
		{
			$this->data['title'] = "Following";	
			$this->data['heading'] = "Following";
			$this->data['results'] = $GC->following(array(
				"user_id"=>$this->session->userdata['user_id']
			));
		}
		
		$this->load->view('header', $this->data);
		$this->data['menu'] = $this->load->view('people/includes/menu',$this->data, TRUE);

		$this->load->view('people/list', $this->data);
		$this->load->view('footer', $this->data);
	
	}

	/**
	*   Displays user profile
	*   handles incoming user actions from profile
	*/
	function profile($id=NULL)
	{
		
 		if(!empty($_POST))
 		{
			$this->message();
			//The thank form on the user profile goes to the thank controller
 		}

		// Default behavior:
		// Segment one == "people" and  segment two == user id and segment three == method
		
		// Route method (if present)
		if(is_numeric($this->data['segment'][2]) && !empty($this->data['segment'][3]))
		{
			if( method_exists( __CLASS__, $this->data['segment'][3] ) )
			{
				return $this->{$this->data['segment'][3]}($this->data['segment'][2]);
			}
		}
		// Handle special cases
		
		// If segment 2 is empty
		if (is_numeric($this->data['segment'][2]))
		{
				$user_id = $this->data['segment'][2];
		}
		if (is_numeric($this->data['segment'][3]))
		{
				$user_id = $this->data['segment'][3];
		}
		
		
		// If URI segment one is people and two is profile,
		// redirect to get rid of the word member so that the
		// uri segment variables used to load the proper user work
		
		if( $this->data['segment'][2] == "profile" )
		{
			
			if(isset($this->data['segment'][3]))
			{
				$user_id = $this->data['segment'][3];
			}
			else
			{
				redirect('people/');
			}
		
		}
		
		if(isset($id) && !isset($user_id))
		{
			$user_id = $id;
		}
				

		// End routing
		
		// Begin loading user information
		

		// Construct user object
		$Search = new User_search();
		$Search->user_id = $user_id;
		$U = $Search->get(array('user_id' => $user_id, 'include_photos' => TRUE));

		$U_model = new User($user_id);

		$this->data['profile_thumb'] = $U->default_photo->url;

		// Load user's gift
		$G = new Good_search;
		$this->data['gifts'] = $G->find( array(
			"user_id" => $U->id, 
			"count_transactions" => FALSE, 
			"type"=>"gift",
			"status" => 'active'
		
		));
		
		// Load user's needs
		$this->data['needs'] = $G->find( array(
			"user_id" => $U->id, 
			"count_transactions" => FALSE, 
			"type"=>"need",
			"status" => 'active'	
		));
		
		// Generate stats about the user
		$U_model->stats();

		// Load user's completed Transactions - to get reviews
		$T_s = new Transaction_search();
		$search_options = array(
			"user_id" => $U->id,
			"transaction_status" => "completed",
			"limit" => 20
			);
		$this->data['reviews'] = $T_s->find($search_options);

		$T_y = new Thankyou_search();
		$search_options_thank = array('recipient_id' => $U->id, 'status'=>'accepted');
		$this->data['thanks'] = $T_y->find($search_options_thank);

		//Load gifts for "Give to" panel
		if(!empty($this->data['logged_in_user_id']))
		{
			$this->data['potential_gifts'] = $G->find( array("user_id" => $this->data['logged_in_user_id'], "type"=>"gift", 'status' => 'active'));
		}
		
		// Get list of people this user is following
		$this->data['followers'] = $Search->followers(array(
			"user_id"=>$U->id
		));
		
		// Get list of people this user is following
		$this->data['following'] = $Search->following(array(
			"user_id"=>$U->id
		));
		
		$this->data['visitor'] = TRUE;
		
		// If logged in, check to see if visitor is following this user
		if(!empty($this->data['logged_in_user_id']))
		{
			$this->data['is_following'] = $U_model->is_followed_by($this->data['logged_in_user_id']);
			
			// Check to see if viewing your own profile (then you are NOT a 
			// visitor, you're at home, looking in the mirror)
			$this->data['visitor'] = ($U->id != $this->data["logged_in_user_id"]);
		}
		
		//Check for "gift circle overlap"
		if($this->data['visitor'] && !empty($this->data['userdata']['user_id']))
		{
			$options = array(
				'user_one' => $this->data['userdata']['user_id'],
				'user_two' => $U->id
				);
			$this->data['gift_circle_overlap'] = $Search->gift_circle_overlap($options);
		}


		// Send User object to the view
		$this->data['u'] = $U;
		$this->data['title'] = $U->screen_name." | Profile";
		$this->data['rss'] = '<link rel="alternate" type="application/rss+xml" title="'.$U->screen_name.'\'s Latest Gifts" href="'.site_url('rss/user/'.$user_id).'">
		<link rel="alternate" type="application/rss+xml" title="'.$U->screen_name.'\'s Latest Needs" href="'.site_url('rss/user/'.$user_id.'/needs').'">';

		$this->data['thankform'] = $this->load->view('forms/thankform', $this->data, TRUE);
		$this->data['messageform'] = $this->load->view('forms/messageform', $this->data, TRUE);
		
		// Load views
		$this->load->view('header', $this->data);
		$this->load->view('people/profile', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	/**
	*	Logged in user follows user of provided user id
	*	@param int $user_id		User ID of user to follow
	*/
	function follow( $user_id )
	{
		$this->auth->bouncer(1);

		// Create User objects for both logged in user and user to follow
		$U = new User($this->data['logged_in_user_id']);
		$F = new User($user_id);
		
		// Save "Following" relationship between 2 Users
		$U->save_following($F);
		
		// Prep hook data
		$event_data = array(
			"following_user_id"=>$user_id,
			"follower_user_id"=>$this->data['logged_in_user_id']
		);

		$this->event_logger->follower_new($event_data);

		if( $this->data['is_ajax'] )
		{
			echo "Now following ".$F->screen_name;
		}
		else
		{
			$this->session->set_flashdata('success', 'Now following '.$F->screen_name.'.');
			redirect('people/'.$user_id);
		}
	}
	
	/**
	*	Logged in user no longer follows user of provided user id
	*	@param int $user_id		User ID of user to no longer follow
	*/
	function unfollow( $user_id )
	{
		// Create User objects for both logged in user and user to not follow
		$U = new User($this->data['logged_in_user_id']);
		$F = new User($user_id);
		
		// Delete "Following" relationship between 2 Users
		$U->delete_following($F);

		if( $this->data['is_ajax'] )
		{
			// @todo return proper HTTP status code for "deleted" result
			echo "No longer following ".$F->screen_name;
		}
		else
		{
			$this->session->set_flashdata('success', 'No longer following '.$F->screen_name.'.');
			redirect('people/'.$user_id);
		}
	}


	public function facebook()
	{
		if(!empty($this->U->facebook_id))
		{
			// Load list of friends' IDs from Facebook API
			$this->load->library('facebook');
			$friend_ids = $this->facebook->friend_ids($this->U->id);
			
			// Search for matching users
			$this->data['friends'] = $this->user_search->find(array(
				"facebook_id"=>$friend_ids,
				"following_stats"=>TRUE,
				"limit"=>null
			));
		}

		// Page Title
		$this->data['title'] = "People | Find Your Friends on Facebook";
		
		// Includes the needed Google Maps references in the header
		$this->data['googlemaps'] = TRUE;
		
		$this->data['active_link'] = 'facebook';
		
		$this->load->view('header', $this->data);
		$this->load->view('people/menu', $this->data);
		$this->data['menu'] = $this->load->view('people/includes/menu',$this->data, TRUE);
		$this->load->view('people/facebook', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	/**
	 * NON FUNCTIONAL 
	 * Lists Gmail friends 
	 * @todo rebuild integration with google
	 */
	public function gmail()
	{
		// Page Title
		$this->data['title'] = "People | Find Your Friends on Gmail";
		
		// Includes the needed Google Maps references in the header
		$this->data['googlemaps'] = TRUE;
		
		$this->data['active_link'] = 'gmail';
		
		if( $this->auth->is_logged_in() )
		{
			$this->U = new User($this->session->userdata['user_id']);
			
			if(!empty($this->U->google_token) && !empty( $this->U->google_token_secret ) )
			{
				// Query Google Contacts API, returning array of email addresses
				$this->load->library('openauth');
				$email_list = $this->openauth->google_contacts_emails();
				
				// Search for matching users
				$this->data['friends']['google'] = $this->user_search->find(array(
					"email"=>$email_list,
					"following_stats"=>TRUE,
					"limit"=>null
				));
			}
		}				
		
		$this->load->view('header', $this->data);
		$this->load->view('people/menu', $this->data);
		$this->data['menu'] = $this->load->view('people/includes/menu',$this->data, TRUE);
		$this->load->view('people/gmail', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	/**
	*  Handles incoming message form from profile 
	*/
	function message ()
	{

		if(!empty($_POST)) {
			$input = $this->input->post();

			$this->load->library('Messaging/Conversation');

			$C = new Conversation();
			$C->type ='thread';

			$data = array(
				'body' => $input['body'],
				'user_id' => $this->data['logged_in_user_id'],
				'subject' => $this->data['userdata']['screen_name']." wrote you a message.",
				'recip_id' => $input['recip_id'],
				'type' => 'thread'
			);
			if(!$C->compose($data)){
				show_error("Error saving Conversation");
			}


		$notify_data = new stdClass();

		foreach($C->users as $val) 
		{
			if($val->id != $this->data['logged_in_user_id'])
			{
				$notify_data->recipient_id = $val->id;
				$notify_data->recipient_email = $val->email;
				$notify_data->recipient = $val->screen_name;
				$notify_data->notify_id = $val->id;
			}
		}
		
		$Message = $C->get_latest_message();

		$notify_data->subject = $this->data['userdata']['screen_name']." wrote you a message.";
		$notify_data->message = $input['body'];
		$notify_data->message_id = $Message->id;
		$notify_data->return_url = site_url('you/inbox');


		$this->notify->alert_user_message($notify_data);
		$this->event_logger->user_message($notify_data);
			

		}
	}

}

