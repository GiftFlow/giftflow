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
		$this->data = $this->util->parse_globals(array(
			"geocode_ip"=>TRUE
		));
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
    $newest_options = array(
      'order_by' => 'U.created',
      'sort' => 'DESC',
      'exclude_logged_in_user'=>TRUE
    );

    $newest_search = new User_search();
		$this->data['results'] = $newest_search->find($newest_options);

		$this->data['title'] = "People";	
		$this->load->view('header', $this->data);
		$this->data['menu'] = $this->load->view('people/includes/menu',$this->data, TRUE);
		$this->load->view('people/index', $this->data);
		$this->load->view('footer', $this->data);
	}

  public function browse()
  {
      
    $order = ($_POST['order_by'] == 'newest' ? 'U.created' : 'location_distance');
    $sort = ($_POST['order_by'] == 'newest' ? 'DESC' : 'ASC');

    $type = $_POST['type'];

    $P = new User_search();

    $options = array(
      'order_by' => $order,
      'type' => $type,
      'sort' => $sort,
      'limit' => 10,
      'location' => $this->data['userdata']['location']
    );

    $this->load->library('factory');
    $results = $P->find($options);
    $this->data['results'] = $this->factory->users_ajax($results, $sort);


    //Encode in JSON
    $data = array(
      "center" => '',
      "total_results" =>count($this->data['results']),
      "results"=>$this->data['results']
    );
    $this->data['results_json'] = json_encode($data);

        return $this->util->json($this->data['results_json']);
  }

  public function test () 
  {

		$nearby_search = new User_search;
		
		// If user logged in and location data is available, filter by it
		if(!empty($this->data['logged_in_user_id']) && !empty($this->data['userdata']['location']))
		{
			$nearby_options = array(
				"location"=>$this->data['userdata']['location'],
				"order_by"=>"location_distance",
				"sort"=>"ASC",
				"exclude_logged_in_user"=>TRUE,
				"radius" => 100
			);
		}
		
		// Else try to geolocate via their IP address.
		// If that doesn't work, skip geo filtering
		else
		{
			//$location = $this->geo->geocode_ip();
			
			// Geocode via IP successful, filtering
			if(!empty($location))
			{
				$nearby_options = array(
					"location"=>$location,
					"order_by"=>"location_distance",
					"sort"=>"ASC",
					"radius" => 100
				);
			}
			
			// No location found
			else
			{
				$nearby_options = array();
			}
		}
  }
		

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
					$this->load->library('Search/User_search');
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
				$this->load->library('Search/User_search');
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
	
	function lists($type = "following")
	{
		$this->auth->bouncer(1);
		
		$this->U = new User($this->session->userdata['user_id']);
		$GC = new User_search;
		
		if($type=="giftcircle")
		{
			$this->data['title'] = "Gift Connections";	
			$this->data['heading'] = "People connected to you via gifts.";
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
	*	Displays user profile
	*/
	function profile($id=NULL)
	{
		
 		if(!empty($_POST))
 		{
			switch($_POST['formtype']) {
			case 'offer':
				$this->_offer();
				break;
			case 'thankyou':
				$this->_thank();
				break;
			};
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
		$U = new User();
		// Fetch proper user
		$U  ->where('id',$user_id)
			->include_related('default_photo', '*', NULL, TRUE)
			->get();
			
		
		$this->data['active'] = ($U->status == 'disabled' ? FALSE : TRUE);

		
		$U->default_location->get();
			
		$U->default_photo->get();
		
		if($U->photo_source == 'facebook' && !empty($U->facebook_id))
		{
			$this->data['profile_thumb'] = "http://graph.facebook.com/".$U->facebook_id."/picture?type=square";
		}
		elseif($U->default_photo->exists())
		{
			$this->data['profile_thumb'] = base_url($U->default_photo->thumb_url);
		}
		else
		{
			$this->data['profile_thumb'] = base_url("assets/images/user.png");
		}		
		
		$U->photos->get();
		$show = ($U->photos->exists() ? true : false );
		$this->data['show_gallery'] = json_encode($show);
		$this->data['photos'] = NULL;

		foreach($U->photos->all as $val)
		{
			$data = array (
					"id" => $val->id,
					"caption" => $val->caption,
					"url" => base_url($val->url),
					"thumb_url" => base_url($val->thumb_url),
					"default" => FALSE
				);
			$this->data['photos'][] = $data;
		}
		

		// New User_search object
		$Search = new User_search;
		$Search->user_id = $U->id;
		
		// Load user's gift
		$this->load->library('Search/Good_search');
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
		$U->stats();

		//Load user's thank yous - transactions without goods or demands
		$R = new Review_search();
		$review_options = array('user_id' => $U->id);
		//to be appended to gifts_given list in view
		$this->data['reviews'] = $R->find($review_options);

		// Load user's completed Transactions - to get reviews
		$T_s = new Transaction_search();
		$search_options = array(
			"user_id" => $U->id,
			"transaction_status" => "completed",
			"limit" => 20
			);
		$this->data['transactions'] = $T_s->find($search_options);

		$T_y = new Thankyou_search();
		$search_options = array('recipient_id' => $U->id);
		$this->data['thankyous'] = $T_y->find($search_options);

		//Load gifts for "Give to" panel
		if(!empty($this->data['logged_in_user_id']))
		{
			$this->data['potential_gifts'] = $G->find( array("user_id" => $this->data['logged_in_user_id'], "type"=>"gift"));
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
			$this->data['is_following'] = $U->is_followed_by($this->data['logged_in_user_id']);
			
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
		$hook_data = array(
			"following_user_id"=>$user_id,
			"follower_user_id"=>$this->data['logged_in_user_id']
		);

		$E = new Event_logger();
		$E->follower_new('follower_new', $hook_data);
		$E->following_new('following_new',$hook_data);


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
		
		// Hook: 'follower_deleted'
		$this->hooks->call('follower_deleted', $this);

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


	/** 
	 * The thank you function is for the Thank you button on the user profile
	 * The idea is to enable users to write quick reviews for one another without
	 * needing to go through the whole transaction process
	 */
	function _thank()
	{
		$this->auth->bouncer('1');

		//ok lets try the new route. forget working it into the data structure.
		$form = $this->input->post(NULL,TRUE);

		$TY = new Thankyou();

		$TY->thanker_id = $this->data['logged_in_user_id'];
		$TY->recipient_id = $form['recipient_id'];
		$TY->gift_title = $form['gift'];
		$TY->body = $form['body'];

		if(!$TY->save()) {
			show_error('Error saving Thankyou');
		} else {
			// Set flashdata & redirect
			$this->session->set_flashdata('success', 'Thank sent!');


			//Get filled out thankyou object from thankyouSearch 
			$newThank = new Thankyou_search();
			
			$hook_data = $newThank->get(array('id'=>$TY->id));

			//record event and send notification
			$E = new Event_logger();
			$E->basic('thankyou', $hook_data);

			$N = new Notify();
			$N->thankyou('thankyou', $hook_data);

			redirect('people/'.$form['recipient_id']);
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
			$this->load->library('Search/User_search');
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
				$this->load->library('Search/User_search');
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
	
	function find()
	{
		if(!empty($this->data['userdata']['location']))
		{
			$this->data['location'] = $this->data['userdata']['location'];
		}
		else
		{
			$this->data['location'] = $this->geo->geocode_ip();
		}
		
		if(!empty($_POST))
		{
			$options = array(
				"keyword"=> $_POST['keyword']);
			$this->data['results'] = $this->user_search->find($options);
		}
				
		$this->data['title'] = "Find People";
		$this->load->view('header', $this->data);
		$this->data['menu'] = $this->load->view('people/includes/menu',$this->data, TRUE);
		$this->load->view('people/find', $this->data);
		$this->load->view('footer', $this->data);

	}
	function results()
	{
		//$this->load->library("search/user_search");
		if(!empty($_POST))
		{
			$options = array(
				"first_name" => $_POST['keyword'],
				"screen_name"=> $_POST['keyword'],
				"last_name" => $_POST['keyword'],
				"location" => $this->data['userdata']['location']
			);
			
			$this->data['results'] = $this->user_search->find($options);
		}
		
		$this->data['title'] = "Search Results";
		$this->load->view('header', $this->data);
		$this->data['menu'] = $this->load->view('people/includes/menu',$this->data, TRUE);
		$this->load->view('people/find', $this->data);
		$this->load->view('footer', $this->data);
	}
	function _offer()
	{
		// Restrict access to logged in users
		$this->auth->bouncer('1');
		
		if(empty($_POST['good_id']))
		{
			$this->session->set_flashdata('error', 'You forgot to select a gift!');
			redirect('people/'.$_POST['decider_id']);
		}

				
		// Arguments to send to Market::create_transaction()
		$options = array(
			"demands" => array(
				array (
					"user_id" => $this->data["logged_in_user_id"],
					"good_id" => $_POST['good_id'],
					"type" => $_POST['type'],
					"hook" => 'hook',
					"note" => $_POST['reason']
				)
			),
			"decider_id" => $_POST['decider_id']
		);
		
		
		
		// Make request
		if(!$this->market->create_transaction($options))
		{
			// @todo handle request failure
			return FALSE;
		}
		// Set flashdata & redirect
			$this->session->set_flashdata('success', 'Offer sent!');
			redirect('people/'.$_POST['decider_id']);
	
	}
}

