<?php

class Member extends CI_Controller {

	var $data;
	var $U;
	var $code;
	var $facebook;
	var $login_url;
	var $error_string = ' ';
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->load->library('datamapper');
		$this->load->library('Search/User_search');
		$this->data = $this->util->parse_globals();
		$this->config->load('account', TRUE);
		$fbook = $this->config->config['account'];

		//load the facebook sdk
		if(defined('FBOOK_APP_ID') && defined('FBOOK_SECRET'))
		{
			require_once('assets/facebook-php-sdk/src/facebook.php');
			$config = array (	
				"appId"=> FBOOK_APP_ID,
				"secret"=> FBOOK_SECRET,
				"fileUpload"=>true
			);
			$this->facebook = new Facebook($config);

			$params = array(
				'scope' => 'email, user_photos, publish_stream',
				'redirect_uri' => site_url('member/facebook')
			);
	
			$this->login_url = $this->facebook->getLoginUrl($params);
		}
	}

	function index()
	{
		// redirect to profile
		redirect('you/index');
	}

	/*
	 * Facebook callback function
	 * Login and registration facebook calls route back here
	 */
	function facebook()
	{
		$get = $this->input->get('redirect');
		$redirect = (!empty($get)) ? $get : 'welcome/home';

		//check if user is facebook authorized
		$user = $this->facebook->getUser();

		//facebook authorized
		if($user > 0) {
			try {
				$user_info = $this->facebook->api('/me');
			} catch (FacebookApiException $e) {
				show_error($e);
				$user_info = null;
				redirect('');
			}
				$user_info['token'] = $this->facebook->getAccessToken();
				$user_info['redirect'] = $redirect;
				return $this->auth->facebook($user_info);

		}else {
			$this->session->set_flashdata('error', "Error connecting with Facebook");
			redirect('member/login');
		}
	}

	/**
	*	Login page
	*	@param string $redirect
	*	redirect comes from two places. the hidden input of the header dropdown login form
	*	and the goods::visitor_redirect function which stores the redirect in the sesssion
	*/
	function login( $redirect = FALSE )
	{
		//set redirect before proceeding
		$sess_redirect = $this->session->userdata('visitor_redirect_url');

		if(!empty($_POST['redirect'])) {

			$redirect = $this->input->post('redirect');

			//if loggin in from index, redirect to welcome
			if($redirect == site_url() || $redirect == site_url('member/login') || $redirect == site_url('register')) {
				$redirect ='welcome/home';
			}

		} else if(!empty($sess_redirect)){
			$redirect = $sess_redirect;
		} else {
			$redirect = 'welcome/home';
		}

		
		// If form data POST is here, process login
		if(!empty($_POST))
		{
			$this->U = $this->auth->login($this->input->post());
			
			// Check for errors
			
			// If any errors, display error message
			if(count($this->U->error->all) > 0)
			{
				$this->session->set_flashdata('error', $this->U->error->string);
				$this->error_string = $this->U->error->string;
				$this->_login_form($redirect);
			}
			// No errors. Proceed.
			else
			{
				redirect($redirect);
			}
		} else {

			// If no form data, render login form
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
			$this->U = $this->auth->register($this->input->post());
			
			// Check for errors
			
			// If any errors, redirect back to form
			if(count($this->U->error->all) > 0)
			{
				$this->session->set_flashdata('error', $this->U->error->all);
				redirect('register');
			}
			
			// If no errors, check for waiting thanks and then send to success form
			else
			{

				//check thankyous table to thankInvites
				$T = new Thankyou();
				$thanks = $T->where('recipient_email', $this->U->email)->get();
				if($thanks->exists())
				{
					foreach($thanks as $val)
					{
						$val->recipient_id = $this->U->id;
						$val->save();
					}
				}

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
				redirect('you');
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
				$event_data = array(
					"email" => $this->U->email,
					"screen_name" => $this->U->screen_name,
					"user_id" => $this->U->id
				);
				//If user is old and doesn't have code already in database
				if(empty($this->U->forgotten_password_code))
				{
					$new_code = sha1('$newpassword%$'.microtime(TRUE));
					$event_data['forgotten_password_code'] = $new_code;
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
					$event_data['forgotten_password_code'] = $this->U->forgotten_password_code;
				}
				
				$this->load->library('event_logger');
				$this->event_logger->reset_password($event_data);
				
				$this->load->library('notify');
				$this->notify->reset_password( $event_data);
				
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
	
		if(isset($this->facebook))
		{
	
			$this->data['redirect'] = $redirect;
	
			$this->data['fbookUrl'] = $this->login_url;
		}

		$this->data['js'][] = 'jquery-validate.php';
		$this->data['title'] = "Login";
		$this->data['error_string'] = $this->error_string;
		$this->load->view('header', $this->data);
		$this->load->view('member/login', $this->data);
		$this->load->view('footer', $this->data);
	}


	/*
	* Loads register form
	* @param oldPost is an array containing previously submitted form data
	 */

	protected function _register_form($oldPost = NULL)
	{
		if(isset($this->facebook))
		{
			$this->data['registerUrl'] = $this->login_url;
		}

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
		$this->session->set_flashdata('success', "Congratulations! We sent you an email with a link to log you in");
		redirect('welcome/home');
	}
	
	/**
	*	After user submits their email to get their forgotten_password_code emailed to them
	*	they are sent here and told to look in their email
	*/
	
	protected function _reset_password_success()
	{
		$this->session->set_flashdata('success', "A confirmation email is on its way");
		redirect('welcome/home');
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
