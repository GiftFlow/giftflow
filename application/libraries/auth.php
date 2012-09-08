<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	The Auth library handles both authentication and authorization functionality.
*
*	@author Brandon Jackson
* 	@package Libraries
*/
class Auth
{

	protected $CI;
	
	/**
	*	@var User
	*/
	var $U;
	
	var $user_id;

	/**
	*	Constructor
	*/
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->hooks =& load_class('Hooks');
	}
	
	/**
	*	Register new user
	*
	*	Reads from $_POST data
	*
	*	@return User
	*/
	public function register()
	{
		$this->CI->load->library('datamapper');
	
		// Construct new User object
		$this->U = new User();
		
		// Set $_POST data
		$this->U->email = $this->CI->input->post('email');
		$this->U->screen_name = $this->CI->input->post('screen_name');
		$this->U->password = $this->CI->input->post('password');
    $this->U->type = $this->CI->input->post('profile_type');

		
    //Create and set Location if user provided zipcode
    $zipcode = $this->CI->input->post('zipcode');
    if(isset($zipcode)) 
     {

			// Create location object and then try to save it
			$L = new Location();
			$this->CI->load->library('geo');
			$Geo = new geo();
			$full_location = $Geo->geocode($this->CI->input->post('zipcode'));
			
			if(!empty($full_location))
			{
        foreach($full_location as $key=>$val)
          {
            $L->$key = $val;
          }
        
        
        $L->validate();
        if(!empty($L->duplicate_id))
        {
          $L = new Location($L->duplicate_id);
        }
        elseif(!$L->save())
        {
            echo $L->error->string;
        }
        
      }
       $this->U->save($L);
    } 


		// Set default user role to 2, which is a normal user
		$this->U->role = 'user';

		// Set IP address
		$this->U->ip_address = $this->CI->input->ip_address();
		
		 
		// Generate forgotten password code
		$this->U->forgotten_password_code = sha1('$'.$this->U->ip_address.'$'.microtime(TRUE));
		
		// Save new user. If successful....
		if($this->U->register())
		{
			// Deactive user, generate activation code
      $this->U->deactivate();
		
			// Hook: 'user_registration_manual'
			$this->hooks->call('user_registration_manual', $this);
		}
		
		// Return new user
		return $this->U;
	}
	
	/**
	* Resets and saves new User password
	*
	*	@param options array
	*/
	function reset_password($U = NULL)
	{
		
		$this->U = new User;
		
		$this->U->where('id', $U->id)->get();
		
		$this->U->password = $this->CI->input->post('password');
		// Generate salt based on time and email
		$this->U->salt =  sha1('~'.$this->U->email.'~'.microtime(TRUE));
		// Generate forgotten password code
		$this->U->forgotten_password_code = sha1('$'.$this->U->ip_address.'$'.microtime(TRUE));
		
		$this->U->activation_code = "0";
		
		return($this->U->save_new_password());
			
	}
	
	/**
	*	Tests to see if user is logged in
	*	
	*	@return boolean
	*/
	function is_logged_in()
	{
		return $this->CI->session->userdata('user_id') ? TRUE : FALSE;
	}
	
	/**
	*	Handles logins. Reads credentials from $_POST and validates using 
	*	methods stored in the User model. If successful, creates new session.
	*
	*	@return User
	*/
	public function login()
	{
		$this->CI->load->library('datamapper');
	
		// Construct new User object
		$this->U = new User();
		
		// Parse $_POST input
		$this->U->email = $this->CI->input->post('email');
		$this->U->password = $this->CI->input->post('password');
		
		// Validates login info. If valid...
		if($this->U->login())
		{
			// Hook: 'user_logged_in'
			$this->hooks->call('user_logged_in', $this);
		
			// ... create new session
			$this->new_session();
		}
		// Return User object
		return $this->U;
	}
	
	/**
	*	Logs user out. Destroys CodeIgniter session.
	*
	*	@return void
	*/
	public function logout()
	{
		// Destroy CI session
		$this->CI->session->sess_destroy();
		
		// Hook: 'user_logged_out'
		$this->hooks->call('user_logged_out', $this);
	}
	
	/**
	*	Creates new CodeIgniter session for the user defined
	*	in $this->U
	*
	*	@return boolean
	*/
	public function new_session()
	{
		$this->CI->load->library('datamapper');
		if (empty($this->U))
		{
			if(!empty($this->CI->session->userdata['user_id']))
			{
				$this->U = new User($this->CI->session->userdata['user_id']);
			}
			else
			{
				return FALSE;
			}
		}
		$this->U->default_location->get();
		$this->U->default_photo->get();
		
		// Build userdata object
		$userdata = array ( 
			'logged_in' => TRUE,
			'role'=>$this->U->role,
			'level'=>$this->get_access_level($this->U->role),
			'user_id'=>$this->U->id,
			'email'=>$this->U->email,
			'username'=>$this->U->username,
			'screen_name'=>$this->U->screen_name,
			'first_name'=>$this->U->first_name,
			'last_name'=>$this->U->last_name,
			'location_latitude'=>$this->U->default_location->latitude,
			'location_longitude'=>$this->U->default_location->longitude,
			'location_street_address' => $this->U->default_location->street_address,
			'location_address'=>$this->U->default_location->address,
			'location_city'=>$this->U->default_location->city,
			'location_state'=>$this->U->default_location->state,
			'location_country'=>$this->U->default_location->country,
			'status'=>$this->U->status,
			'timezone'=>$this->U->timezone,
			'language'=>$this->U->language
		);
						
		// Determine appropriate photo thumbnail source, then add to userdata
		if($this->U->photo_source=="facebook" && !empty($this->U->facebook_id))
		{
			$userdata['photo_thumb_url'] = "http://graph.facebook.com/".$this->U->facebook_id."/picture?type=square";
			$userdata['photo_url'] = "http://graph.facebook.com/".$this->U->facebook_id."/picture?type=large";
		}
		elseif($this->U->photo_source == 'giftflow' && !empty($this->U->default_photo->thumb_url) && !empty($this->U->default_photo->url))
		{
			$userdata['photo_thumb_url'] = base_url().$this->U->default_photo->thumb_url;
			$userdata['photo_url'] = base_url().$this->U->default_photo->url;
		}
		else
		{
			$userdata['photo_thumb_url'] = base_url().'assets/images/user.png';
			$userdata['photo_url'] = base_url().'assets/images/user.png';
		}
		
		// Save to userdata array
		$this->CI->session->set_userdata($userdata);
		return TRUE;
	}
	
	
	/*
	*
	*	AUTHORIZATION FUNCTIONS
	*
	*/

	/**
	*	Validates whether the user has a high enough authentication level to view a page.
	*	
	*	@param int $min	The minimum authorization level needed to view this page
	*	@return boolean
	*/
	public function validate( $min )
	{
		if($this->CI->session->userdata('level'))
		{
			$level = $this->CI->session->userdata('level');
		}
		else 
		{
			$level = 0;
		}

		// If no minimum level specified, default to 100
		if(empty($min))
		{
			$min = 100;
		}
		
		// Test user's access $level vs $min level required
		if ($level >= $min)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	*	Lets authorized users view a page and redirects unauthorized users
	*	to either an access denied page or login page.
	*
	*	@param int $min 			Minimum auth level
	*	@param string $redirect	URL to redirect to
	*/
	
	public function bouncer ($min = NULL, $redirect = 'login')
	{
		Console::logSpeed('start Auth::bouncer()');

		if($this->validate($min))
		{
			return true;
			Console::logSpeed('end Auth::bouncer()');
		}
		else
		{
			if($this->CI->session->userdata('logged_in'))
			{
				redirect('restricted');
			}
			else
			{
				$this->CI->session->set_flashdata('error','You need to log in first');
				$this->CI->db->insert('redirects', array("url"=>$this->CI->uri->uri_string()));
				redirect($redirect."/".$this->CI->db->insert_id());
			}
		}
	}
	
	/** 	
	*	Handles Facebook connect auth requests
	*
	*	Outline
	* 	1: Is the Facebook data valid?
	*    		a: Yes. Proceed to 2.
	*    		b: No. Return error message.
	* 	2: Is Facebook ID in system?
	*     	a: Yes. Create session for that user.
	*     	b: No. Facebook ID not in system. Proceed to 3.
	*	3. Is this user already logged in?
	*		a. Yes. Link Facebook ID with that user. Fill in missing profile fields.
	*		b. No. Proceed to step 4.
	* 	4: Is email in system?
	*     	a: Yes. Create session for that user. Link Facebook ID with that user. 
	*		   Fill in missing profile fields.
	*     	b: No. Create new User.
	*
	*	@param object $data	JSON data received from Facebook
	*/
	function facebook( $data )
	{
		$this->CI->load->library('datamapper');
	
		// Only proceed if ID present
		if(!empty($data->id))
		{
			// Check for this Facebook ID in DB
			$step_two = $this->CI->db->where('facebook_id',$data->id)->from('users')->get();
			
			// Outcome 2a: Facebook ID already in system. Logging User In.
			if($step_two->num_rows==1)
			{
				$user = $step_two->row();
				$this->U = new User($user->id);
				$this->U->facebook_sync( $data );
				$this->new_session();
				redirect('');
			}
			// eof 2a
			
			// Outcome 3a: User exists and is logged in. Linking GiftFlow & Facebook
			// accounts.
			elseif(!empty($this->CI->session->userdata['user_id']))
      {
				$this->U = new User( $this->CI->session->userdata['user_id'] );
				
				// Sync Facebook data
				$this->U->facebook_sync($data);
				
				// Set success message, redirect back to Manage Linked Accounts page
				$this->CI->session->set_flashdata('success', 'Facebook account now linked with GiftFlow');
				redirect('account/links');
			}
			// eof 3a
			
			elseif(!empty($data->email))
			{
				// Check for existing accounts with provided email address
				$step_four = $this->CI->db->where('email',$data->email)->from('users')->get();
				
				// Outcome 4a: Existing user logging in for first time using Facebook
				if($step_four->num_rows==1)
        {
					$user = $step_four->row();
					$this->U = new User($user->id);
					$this->U->facebook_sync($data);
					
					// Create new session
					if($this->new_session())
					{
						$this->U->save();
						// Redirect to dashboard
						redirect('');
					}
				}
				// eof 4a
				
				// Outcome 4b: New user
				else
				{
					$this->U = new User();
					
					// Save user, sync with Facebook data
					if($this->U->facebook_sync( $data ))
					{
						// Create new session
						if($this->new_session())
						{
							// Redirect to facebook welcome page
							redirect('welcome/facebook');
						}
						else
						{
							// @todo bad session error
						}
					}
					else
          {
						// @todo facebook data sync error
					}
				}
				// eof 4b
			}
		}		
	}
	
	/**
	*	Logs in users authenticated by OpenID
	*/
	function openid_login( $U = NULL )
	{
	
		$this->CI->load->library('datamapper');

		if( empty( $U ) )
		{
			return false;
		}
		$this->U = $U;
		
		// Hook: 'user_logged_in'
		$this->hooks->call('user_logged_in', $this);
		$this->hooks->call('user_logged_in_openid', $this);

		// ... create new session
		$this->new_session();
		
		redirect('');
	}

	/**
	*	Logs in users manually, using the User object passed as first parameter
	*
	*	@param object $U 			User object to log in
	*	@param boolean $redirect	If true, redirect to index
	*/
	function manual_login( $U = NULL, $redirect = TRUE )
	{
		$this->CI->load->library('datamapper');
	
		if(empty($U))
		{
			return FALSE;
		}
		
		$this->U = $U;
		
		// Hook: 'user_logged_in'
		$this->hooks->call('user_logged_in', $this);
		$this->hooks->call('user_logged_in_openid', $this);

		// ... create new session
		$this->new_session();
		
		if( $redirect )
		{
			redirect('');
		}
	}
	
	/**
	*	Get access level based on provided $role
	*	@param string $role		User's role (e.g. "admin" or "user")
	*	@return int				User's access level
	*/
	function get_access_level($role)
	{
		if($role=="admin")
		{
			return 100;
		}
		else
		{
			return 1;
		}
	}
	
	/**
	*	Updates a session's location related userdata using data from
	* 	a provided location object
	*	@param object $location
	*/
	function update_session_location($location)
	{
		$properties = array(
			"latitude","longitude","address","city","state","country"
		);
		foreach($properties as $property)
		{
			if(!empty($location->$property))
			{
				$this->CI->session->set_userdata('location_'.$property,$location->$property);
			}
		}
	}
}
