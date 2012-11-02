<?php
/**
*	The User model
*
*	@author Brandon Jackson
*	@package Models
*/
class User extends DataMapperExtension {
	
	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	var $mail_inbox;
	var $mail_sent;
	var $inbox;
	var $default_photo_id;
	
	var $created_field = 'created';	
	var $updated_field = 'updated';
	
	var $error_prefix = ' ';
	var $error_suffix = ' ';

	var $total_completed_gifts;
	var $total_completed_requests;
	var $total_interactions;
	var $total_followers;
	var $total_following;

//   	var $hooks;


	/**	
	*	Has one relationships.
	*	@var array
	*/	

	var $has_one = array(
			'default_photo' => array(
			'class' => 'photo',
			'other_field'=>'default_user'
			),
		'default_location' => array(
			'class' => 'location',
			'other_field'=>'default_user'
			),
		'user_setting'
	);
	
	/**	
	*	Has many relationships.
	*	@var array
	*/	

	var $has_many = array(	  
		'photo',
		'location',
		'message',
		'notification',
		'thread',
		'good',
		'comment',
		"transaction",
		'following'=>array(
			'class'=>'user',
			'other_field'=>'user'
		),
		'user'=>array(
			'other_field'=>'following'
		),
		'watch',
		'user_openid'
	);
	
	/**	
	*	Validation array. Add validation requirements, such as 'required', for 
	*	your fields.
	*
	*	@var array
	*/	
	var $validation = array(
		'username' => array(
			'rules' => array(
				'trim',
				'strtolower',
				'min_length' => 4,
				'max_length' => 25,
				'unique'
			),
			'label' => 'Username'
		),
		'email' => array(
			'rules' => array(
				'required',
				'trim',
				'valid_email',
				'max_length' => 255,
				'unique'
			),
			'label' => 'Email Address'
		),
		'password' => array(
			'rules' => array(
				'trim',
				'min_length' => 5,
				'max_length' => 45,
				'encrypt',
				'required'
			),
			'label' => 'Password',
			'type' => 'password'
		),
		'confirm_password' => array(
			'rules' => array(
				'trim',
				'encrypt',
			),
			'label' => 'Confirm Password',
			'type' => 'password'
		),
		'type'=> array(
			'label'=>'Profile Type',
			'type'=>'dropdown',
			'values' => array ( 
				"individual"=>"Personal / Individual", 
				'nonprofit'=>"Non-Profit Institution", 
				'business'=>"Business"
			)
		),
		'screen_name' => array(
			'rules' => array( "trim" ),
			'label' => "Name"
		),
		'bio' => array(
			'label' => 'About Me',
			'type' => 'textarea'
		),
		'first_name' => array( 'label' => 'First Name'),
		'last_name' => array ( 'label'=>'Last Name'),
		'occupation' => array ('label'=>'Occupation'),
		'phone' => array ('label' => 'Phone Number'),
		'url' => array( 'label' => "URL"),
		'aim' => array ('label' => 'AIM Screen Name'),
		'skype' => array ('label' => "Skype Name"),
		'facebook' => array ('label'=>'Facebook URL'),
		'myspace' => array('label'=>'MySpace URL'),
		'linkedin'=> array ('label' => 'LinkedIn URL'),
		
	);

	function __construct( $id = NULL )
	{
		parent::__construct( $id );
		Console::logMemory();
		Console::logSpeed("creating User");
//		$this->hooks =& load_class('Hooks');
		$this->CI =& get_instance();
	}
	
	function test()
	{
		Console::logSpeed("static!");
		//die();
	}
	 
	/**
	*	Checks to see if login credentials provided match the user's
	*	database entry
	*
	*	@return boolean
	*/
    	function login()
		{
			// Create a temporary user object
			$U = new User();
	
			// Get this users stored record via their username
			$U->where('email', $this->email)->get();
		
		if (empty($U->all))
		{
			$this->error_message('login', 'Email not found.');
			return FALSE;
		}
			
			// Give this user their stored salt
			$this->salt = $U->salt;
	
			// Validate and get this user by their property values,
			// this will see the 'encrypt' validation run, encrypting the password with the salt
			$this->validate()->get();
	
			// If the username and encrypted password matched a record in the database,
			// this user object would be fully populated, complete with their ID.
	
			// If there was no matching record, this user would be completely cleared so their id would be empty.
			if (empty($this->id))
			{
					// Login failed, so set a custom error message
					$this->error_message('login', 'Email or password invalid');
					return FALSE;
			}
			
			// Tests to see if this account has been activated
			elseif (!empty($this->activation_code))
			{
				$this->error_message('login', 'This account has not yet been activated.');
				return FALSE;
			}
			else
			{
				// Login succeeded
				return TRUE;
			}
		}

		/**
		*	This function should be used when a new user is being saved (as 
		*	opposed to being updated. This is so we can call the user_new hook.
		*
		*	@return boolean
		*/
		function register()
		{
			// This stores the existing ID of this User object. If 
			// it is empty, that means that this is a new user. If 
			// not, then it is an update.
			
			// Save the user
			if($this->save())
			{
				// If truly a new registration...
				if(empty($this->id))
				{
					$this->load->library('event_logger');
					$this->CI->event_logger->user_new(array("user_id"=> $this->id));
				}
				
				// Return true if saved/updated successfully
				return TRUE;
			}
		
			// Return FALSE if failed
			else
			{
				return FALSE;
			}
    	}

	/**
	*	Deactivate user, and generate activation to send
	*	to user for confirmation via email
	*/
	function deactivate()
	{
		$activation_code = sha1(md5(microtime()));
		$this->activation_code = $activation_code;
		$this->save();
	}

	/**
	*	Remove the activation code, to indicate it is been activated
	*/
	function activate()
	{
		$this->activation_code = "0";
		$this->status = "active";
		$this->save();
	}
	
	/** 
	* Update password and salt from old and forgotten to NEW 
	*
	*/
	function save_new_password()
	{
		if($this->save())
		{
			return TRUE;
		}
		else
		{
			echo $this->error->string;
			return FALSE;
		}
	
	}
	
	/**
	* 	Returns the user's current gifts
	*
	*	@return array
	*/
	function gifts()
	{
		Console::logSpeed('start User::gifts()');

		$query = $this->CI->db
			->select('G.*, COUNT(T.id) AS transaction_count')
			->from('goods_view AS G')
			->join('demands AS D ','G.good_id=D.good_id', 'left')
			->join("transactions AS T ","D.transaction_id=T.id AND T.status='pending'", "left")
			->where('G.user_id', $this->id)
			->where('G.good_type','gift')
			->group_by('G.good_id')
			->order_by('G.good_created')
			->get();

		// Build object from DB results
		$result = Factory::good($query->result());
		
		// Build related transactions
		Console::logSpeed("building transactions");
		/*foreach($result as $key=>$val)
		{
			$T = new Transaction();
			$T	->where_related_good('id', $val->id)
				->where('status', 'pending')
				->get();
			$val->requests = $T->all;
		}*/
		Console::logSpeed('end User::gifts()');
		return $result;
	}	
	/**
	* 	Returns the user's current needs
	*
	*	@return array
	*/
	function needs()
	{
	
		Console::logSpeed('start User::needs()');
		
		//$G = new Good();
		// Get gift objects
		Console::logSpeed("good created");
		$query = $this->CI->db->where('user_id', $this->id)
				->where('good_type','need')
				->order_by('good_created')
				->from('goods_view')
				->get();
		
		
		/*$G->where('user_id', $this->id);
		$G->where('type', 'need')
			->include_related('location', "*", NULL, FALSE);
		Console::logSpeed("about to include user");
		$G->include_related('user', "id, email", NULL, FALSE)
			->order_by('created', 'desc');
		Console::logSpeed("querying...");
			$G->get_iterated();*/
		$r = array();
		
		Console::logSpeed("getting transactions...");
		
		// Build related has-many fields
		foreach($query->result() as $key=>$val)
		{
			//print_r($val);
			$T = new Transaction();
			$T	->where_related_good('id', $val->good_id)
				->where('status', 'pending')
				->get();
			$val->requests = $T->all;
			/*$P = new Photo();
			$P->where_related_good('id', $val->good_id)->get();
			$val->photo = $P;*/
			$r[] = $val;
		}
		
		Console::logSpeed('end User::needs()');
		
		return $r;
	}

	/**
	* 	Returns the user's current pending requests
	*
	*	@return array
	*/
	function requests()
	{
		Console::logSpeed('start User::requests()');

		$T = new Transaction();
		$T	->where_related_user('id', $this->id)
			->where('status','pending')
			->order_by('created', 'desc')
			->include_related('user', "*", NULL, TRUE)
			->include_related('good', '*', NULL, TRUE)
			->get();
			
		Console::logSpeed('end User::requests()');
		
		return $T->all;
	}
	
	/**
	* 	Returns the user's completed requests (ie gifts that the user has received)
	*
	*	@return array
	*/
	function completed_requests()
	{
		$T = new Transaction();
		$T	->where('status','completed')
			->where_related_user('id',$this->id)
			->order_by('created', 'desc')
			->get();
		return $T->all;
	}	
	
	/**
	* 	Compiles stats about the user
	*
	*	@return boolean
	*/
	function stats()
	{
		if(empty($this->id))
		{
			return FALSE;
		}
		
		//$this->total_completed_gifts = count($this->completed_gifts());
		//$this->total_completed_requests = count($this->completed_requests());
		//$this->total_interactions = $this->total_completed_requests + $this->total_completed_gifts;
		
		$F = new User();
		$this->total_followers = $F->where_related_following('id', $this->id)->count();
		$this->total_following =$this->following->count();
		
		return TRUE;
	}
	
	/**
	*	Boolean test that tells whether this user is following a certain $id
	*
	*	@param int $id	user_id of the user who this user may be following
	*	@return boolean
	*/
	function is_following ( $id )
	{
		$count = $this->db->from('users AS U ')
			->join('followings_users AS FU ', 'U.id = FU.user_id')
			->where('FU.following_id', $id)
			->where('U.id', $this->id)
			->count_all_results();
		if($count==0) 
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/**
	*	Boolean test that tells whether this user is being followed by a user with the id $id
	*	@param int $id	user_id of the user who may be following the current user
	*	@return boolean
	*/
	function is_followed_by( $id )
	{
		$count = $this->db->from('users AS U ')
					->join('followings_users AS FU ', 'U.id = FU.user_id')
					->where('FU.following_id', $this->id)
					->where('U.id', $id)
					->count_all_results();
		if($count==0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/**
	* 	Facebook sync function
	* 	This is used each time a user logs in via Facebook. It checks to see if 
	* 	there are any empty fields in the database which could be populated by 
	*	Facebook data. If so, it fills them. It does not, however, overwrite any 
	*	database data with Facebook data.
	*
	*	@param JSON $data	 JSON object returned by Facebook
	*/
	function facebook_sync( $data )
	{
		// Check to see if Facebook ID is already stored.
		// If not, then mark as "newly linked". This will be used later when the user is saved
		// so we can call the facebook_linked hook instead of the facebook_synced callback.
		if(!empty($data->id)&&empty($this->facebook_id))
		{
			$newly_linked = TRUE;
			$this->facebook_id = $data->id;
		}
		else
		{
			$newly_linked = FALSE;
		}
			
		// Sync various properties
		
		if(!empty($data->token)&&empty($this->facebook_token))
		{
			$this->facebook_token = $data->token;
		}
		
		if(!empty($data->email)&&empty($this->email))
		{
			$this->email = $data->email;
		}
		
		if(!empty($data->name)&&empty($this->screen_name))
		{
			$this->screen_name = $data->name;
		}
		
		if(!empty($data->first_name)&&empty($this->first_name))
		{
			$this->first_name = $data->first_name;
		}
		
		if(!empty($data->last_name)&&empty($this->last_name))
		{
			$this->last_name = $data->last_name;
		}
		
		if(!empty($data->link)&&empty($this->facebook_link))
		{
			$this->facebook_link = $data->link;			
		}
		
		if(empty($this->facebook_data))
		{
			$this->facebook_data = json_encode($data);
		}
		
		if(empty($this->password))
		{
			$this->password = uniqid();
		}
		
		if(empty($this->role))
		{
			$this->role = "user";
		}
		
		if(empty($this->ip_address))
		{
			$this->ip_address = $this->CI->input->ip_address();
		}
		
		if(empty($this->salt))
		{
			$this->salt = sha1('~'.$this->email.'~'.microtime(TRUE));
		}
		
		if(empty($this->registration_type))
		{
			$this->registration_type = 'facebook';
		}
		
		if(empty($this->photo_source))
		{
			$this->photo_source = "facebook";
		}
		
		$this->validate();
		
		// New registration
		if(empty($this->id))
		{
			// Register user
			if($this->register())
			{
				// Hook: 'user_registration_facebook'
				//$this->hooks->call('user_registration_facebook', $this);
				
				// Hook: 'facebook_linked'
				//$this->hooks->call('facebook_linked', $this);

				return TRUE;
			}
			else
			{
				// Regisration failure
				return FALSE;
			}
		}
		
		// Update existing data
		else
		{
			if($this->save())
			{
				if($newly_linked)
				{
					// Hook: 'facebook_linked'
					//$this->hooks->call('facebook_linked', $this);
				}
				else
				{
					// Hook: 'facebook_synced'
					//$this->hooks->call('facebook_synced', $this);
				}
				return TRUE;
			}
			else
			{
				// Data did not save
				return FALSE;
			}
		}
	}
	
	/**
	*	Remove all of the user's Facebook information
	*
	*	@return boolean
	*/
	function facebook_unlink()
	{
		if( ! $this->user_setting->exists() )
		{
			$this->user_setting->get();
		}
		$this->facebook_id = '';
		$this->facebook_token = '';
		$this->facebook_data = '';
		$this->facebook_link = '';
		$this->photo_source = 'giftflow';
		if( $this->save() && $this->user_setting->save() )
		{
			// Hook: 'facebook_unlinked'
			//$this->hooks->call('facebook_unlinked', $this);
			
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	*	Remove all of the user's Google information
	*
	*	@return boolean
	*/
	function google_unlink()
	{
		$this->google_token = '';
		$this->google_token_secret = '';
		if( $this->save() )
		{
			// Hook: 'google_unlinked'
			//$this->hooks->call('google_unlinked', $this);
			
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	* Get either a Gravatar URL or complete image tag for a specified email address.
	*
	* @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
	* @param boole $img True to return a complete IMG tag False for just the URL
	* @param array $atts Optional, additional key/value attributes to include in the IMG tag
	* @return String containing either just a URL or a complete image tag
	* @source http://gravatar.com/site/implement/images/php/
	*/
	function get_gravatar( $s = 80, $img = false, $atts = array() )
	{
		// Maximum rating (inclusive) [ g | pg | r | x ]
		$r = "g";
		
		// Default imageset [ 404 | mm | identicon | monsterid | wavatar ]
		$d = base_url()."assets/images/user.png";
		
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $this->email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if($img)
		{
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val )
			{
				$url .= ' ' . $key . '="' . $val . '"';
			}
			$url .= ' />';
		}
		return $url;
	}
		
	/**
	*	Custom validation rule that encrypts passwords using the 
	*	salt.
	*
	*	@param string $field
	*/
	function _encrypt($field)
	{
		if (!empty($this->{$field}))
		{
			if (empty($this->salt))
			{
				$this->salt = md5(uniqid(rand(), TRUE).microtime(TRUE));
			}

			$this->{$field} = sha1($this->salt . $this->{$field});
        }
    }
	}
