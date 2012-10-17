<?php

class Member extends CI_Controller {

	var $data;
	var $U;
	var $code;
	var $facebook;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->load->library('datamapper');
		$this->load->library('Search/User_search');
		$this->data = $this->util->parse_globals();
		$this->hooks =& load_class('Hooks');
		$this->config->load('account', TRUE);
		$fbook = $this->config->config['account'];

		//load the facebook sdk
		require_once('assets/facebook-php-sdk/src/facebook.php');
		$config = array (	
			"appId"=> FBOOK_APP_ID,
			"secret"=> FBOOK_SECRET,
			"fileUpload"=>true
		);
		$this->facebook = new Facebook($config);

	}

	function index()
	{
		// redirect to profile
		redirect('you/index');
	}
	
	/**
	*	Login page
	*	@param string $redirect
	*	redirect can also be passed via a ?redirect GET param
	*/
	function login( $redirect = FALSE )
	{

		if(!empty($_GET['redirect']))
		{
			$redirect = $this->input->get('redirect');
		} else {
			$redirect = 'you';
		}

		//check if user is facebook authorized
		$user = $this->facebook->getUser();
		//facebook authorized
		if($user > 0)
		{
			$user_info = $this->facebook->api('/me','GET');
			$user_info['token'] = $this->facebook->getAccessToken();
		
			$userJson = json_encode($user_info);
			$userObj = json_decode($userJson);

			$userObj->redirect = $redirect;

			$this->auth->facebook($userObj);
		}

		// If form data POST is here, process login
		else if(!empty($_POST))
		{
			$this->U = $this->auth->login();
			$redirect = $this->input->post('redirect');
			
			
			// Check for errors
			
			// If any errors, display error message
			if(count($this->U->error->all) > 0)
			{
				$this->session->set_flashdata('error', $this->U->error->string);
				$this->_login_form($redirect);
			}
			// No errors. Proceed.
			else
			{
				redirect($redirect);
			}
		}

		
		// If no form data, render login form
		else
		{
			$this->_login_form($redirect);
		}
	}
	
	/**
	*	Logs user out
	*/
	function logout()
	{
		$this->auth->logout();
		redirect('');
	}
	
	/**
	*	Registration form display and processing
	*/
	function register ()
	{
		$this->load->library('recaptcha');

		if(!empty($_GET['code']))
		{
			$user_info = $this->facebook->api('/me','GET');
			$user_info['token'] = $this->facebook->getAccessToken();
		
			$userJson = json_encode($user_info);
			$userObj = json_decode($userJson);

			$this->auth->facebook($userObj);
		}

		// If form data is present, save new user
		if(!empty($_POST))
		{
			// Validate recaptcha answer
			if (!$this->recaptcha->check_answer($this->input->ip_address(),$this->input->post('recaptcha_challenge_field'),$this->input->post('recaptcha_response_field')))
			{
				$this->session->set_flashdata('error', "You did not correctly input the words in the image. Please try again.");
				$this->_register_form($this->input->post());
			}
			
			// Perform registration routine
			$this->U = $this->auth->register();
			
			// Check for errors
			
			// If any errors, redirect back to form
			if(count($this->U->error->all) > 0)
			{
				$this->session->set_flashdata('error', $this->U->error->all);
				redirect('register');
			}
			
			// If no errors, send to success form
			else
			{
				$this->_register_success();
			}
		}
		
		// If no form data, display registration form
		else
		{
			$this->_register_form();
		}
	}

	/**
	*	Processes activation link to confirm user's account, then redirects to
	*	welcome page
	*
	*	@param string $code
	*/
	function activate( $code )
	{
		if(empty($code))
		{
			$this->session->set_flashdata('error','Your activation code did not work');
			redirect('');
		}
		else
		{
			$U = new User();
			$U->where('activation_code', $code)->get();
			if(count($U->all)==1)
			{
				// Update user data
				$U->activate();
				
				// Log user in, redirect to welcome page
				$this->auth->manual_login($U, FALSE);
				$this->session->set_flashdata('success','Welcome to GiftFlow!');
				redirect('welcome');
			}
			else
			{
				$this->session->set_flashdata('error','Your activation code did not work');
				redirect('');
			}
		}
	}
	
	
	/**
	*	Email reset_password link to user
	*
	*/
	function forgot_password()
	{
		if(!empty($_POST))
		{
			$email = $_POST['email'];
			$this->load->library('Search/User_search');
			$U = new User_search();
			$options = array(
				'email' => $email, 
				'include_forgotten_password_code' => TRUE
			);
			
			$this->U = $U->get($options);
			
			if($this->U->email == $email)
			{
				$hook_data = array(
					"email" => $this->U->email,
					"screen_name" => $this->U->screen_name,
					"user_id" => $this->U->id
				);
				//If user is old and doesn't have code already in database
				if(empty($this->U->forgotten_password_code))
				{
					$new_code = sha1('$newpassword%$'.microtime(TRUE));
					$hook_data['forgotten_password_code'] = $new_code;
					$U_d = new User();
					$U_d->where('email', $email)->get();
					
					$U_d->forgotten_password_code = $new_code;
						
						if(!$U_d->save())
						{
							$this->session->set_flashdata('error','Please try again');
							redirect('');
						}
				}
				else
				{
					$hook_data['forgotten_password_code'] = $this->U->forgotten_password_code;
				}
				
				
				$this->hooks->call('reset_password', $hook_data);
				$this->_reset_password_success();
			}
			else  // TODO: Give a better error message for accounts that do not exist
			{
				$this->session->set_flashdata('error','Sorry an error occured');
				redirect('');
			}
			
		}
		else
		{
			//Load view to enter email to get new password
			$this->data['js'][] = 'jquery-validate.php';
			$this->data['title'] = "Forgot Your Password?";
			$this->load->view('header', $this->data);
			$this->load->view('member/forgot_password', $this->data);
			$this->load->view('footer', $this->data);		
		}
	}
	
	
	/**
	* Process reset_password link, then redirect to reset password page
	*
	*/
	function reset_password( $code )
	{
		if(empty($code))
		{
			$this->session->set_flashdata('error','Your forgotten code did not work');
			redirect('');
		}
		else
		{
			$this->U = new User();
			$this->U->where('forgotten_password_code', $code)->get();
			
			if(count($this->U->all)==1)
			{
				$this->auth->manual_login($this->U, FALSE);
				return $this->enter_new_password();
				$this->session->set_flashdata('success','Now you can reset your password');
			}
			else
			{
				$this->session->set_flashdata('error','Your forgotten password code did not work');
				redirect('');
			}
				
		}
	
	
	}
	
	function enter_new_password()
	{
		if(!empty($_POST))
		{
			$this->load->library('Search/User_search');
			$U = new User_search();
			$this->U = $U->get($options = array('user_id' => $this->data['userdata']['user_id']));
			
			if($this->U->email == $_POST['email'])
			{
				$this->load->library('auth');
				$A = new Auth();
				if($A->reset_password($this->U))
				{
					$this->session->set_flashdata('success','New password saved!');
					$this->hooks->call('new_password', $this->U);
					redirect('you');
				}
			}
			else
			{
				$this->session->set_flashdata('error','You entered the wrong email');
				$this->new_password_form();
			}
			
		}
		else
		{
			$this->new_password_form();
		}
	}
	
	protected function new_password_form()
	{
		//Load view to enter new password
		$this->data['js'][] = 'jquery-validate.php';
		$this->data['title'] = "Reset Password";
		$this->load->view('header', $this->data);
		$this->load->view('member/new_password', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	
	protected function _login_form($redirect)
	{
		$params = array(
			'scope' => 'email, user_photos, publish_stream',
			'redirect_uri' => site_url('member/login/?redirect=').$redirect
		);
		$loginUrl = $this->facebook->getLoginUrl($params);

		$this->data['redirect'] = $redirect;

		$this->data['fbookUrl'] = $loginUrl;
		$this->data['js'][] = 'jquery-validate.php';
		$this->data['title'] = "Login";
		$this->load->view('header', $this->data);
		$this->load->view('member/login', $this->data);
		$this->load->view('footer', $this->data);
	}

	protected function _register_form($oldPost = NULL)
	{
		$params = array(
			'scope' => 'email, user_photos, publish_stream',
			'redirect_uri' => site_url('member/register')
		);

		$this->data['registerUrl'] = $this->facebook->getLoginUrl($params);


		if(empty($this->U))
		{
			$this->U = new User();
		}

		$fields = array('email','screen_name', 'city');
		
		$this->data['recaptchaError'] = (empty($oldPost))? FALSE : TRUE;

		foreach($fields as $val) {
			$this->data['form'][$val] = (empty($oldPost[$val]))? '' : $oldPost[$val];
		}	

		$this->data['js'][] = 'jquery-validate.php';
		$this->data['js'][] = 'GF.Locations.js';
		$this->data['facebook_sdk'] = $this->load->view('includes/facebook_sdk',NULL,TRUE);
		$this->data['recaptcha'] = $this->recaptcha->get_html();
		$this->data['u'] = $this->U;
		$this->data['title'] = "Register";
		$this->load->view('header', $this->data);
		$this->load->view('member/register', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	/**
	*	After user has registered account (but before they receive
	*	their confirmation email) they are sent here
	*/
	protected function _register_success()
	{
		$this->data['title'] = "Registration Successful";
		$this->load->view('header',$this->data);
		$this->load->view('member/registration_success', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	/**
	*	After user submits their email to get their forgotten_password_code emailed to them
	*	they are sent here and told to look in their email
	*/
	
	protected function _reset_password_success()
	{
		$this->data['title'] = "Email Confirmation on its way";
		$this->load->view('header',$this->data);
		$this->load->view('member/reset_success', $this->data);
		$this->load->view('footer', $this->data);
	}


	function terms()
	{
		$this->data['title'] = "Terms of Service";
		$this->load->view('header',$this->data);
		$this->load->view('member/terms', $this->data);
		$this->load->view('footer', $this->data);
		
	}
}

/* End of file member.php */
/* Location: ./system/application/controllers/member.php */
