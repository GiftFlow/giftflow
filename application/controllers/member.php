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
		require_once('assets/facebook/facebook.php');
	}

	function index()
	{
		// redirect to profile
		redirect('you/profile');
	}
	
	/**
	*	Login page
	*	@param string $redirect
	*/
	function login( $redirect = FALSE )
	{


		// If form data POST is here, process login
		if(!empty($_POST))
		{
			$this->U = $this->auth->login();
			
			// Check for errors
			
			// If any errors, display error message
			if(count($this->U->error->all) > 0)
			{
				$this->session->set_flashdata('error', $this->U->error->string);
				redirect('login');
			}
			// No errors. Proceed.
			else
			{
				// If there is a redirect adress set, send the authorized user there
				//-hans - unclear what this bit does
				if($this->input->post('redirect'))
				{
					$q = $this->db->where('id', $this->input->post('redirect'))->get('redirects',1);
					$r = $q->row();
					redirect($r->url);
				}
				else
				{
					redirect('you/welcome');
				}
			}
		}
		
		// If no form data, render login form
		else
		{
			$this->data['redirect'] = $redirect;
			$this->_login_form();
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
	function register()
	{
		$this->load->library('recaptcha');

		// If form data is present, save new user
		if(!empty($_POST))
		{
			// Validate recaptcha answer
			if (!$this->recaptcha->check_answer($this->input->ip_address(),$this->input->post('recaptcha_challenge_field'),$this->input->post('recaptcha_response_field')))
			{
				$this->session->set_flashdata('error', "You did not correctly input the words in the image. Please try again.");
				redirect('register');
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
	
	
	protected function _login_form()
	{
		$this->data['js'][] = 'jquery-validate.php';
		$this->data['title'] = "Login";
		$this->load->view('header', $this->data);
		$this->load->view('member/login', $this->data);
		$this->load->view('footer', $this->data);
	}

	protected function _register_form()
	{
		if(empty($this->U))
		{
			$this->U = new User();
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
	

	function facebook( $key1 = null, $val1 = null, $key2 = null, $val2 = null )
	{
		
		$config = array (	
			"appId"=>'111637438874755',
			"secret"=>'797a827203a1ad62cace9fa429100567',
			"fileUpload"=>true
		);

		$this->facebook = new Facebook($config);

		$user = $this->facebook->getUser();

		$status = $this->facebook->getLoginStatusUrl();

		if($user != 0) {
			$logoutUrl = $this->facebook->getLogoutUrl();
		} else {
			$loginUrl = $this->facebook->getLoginUrl();
			redirect($loginUrl);
		}

		redirect("https://www.facebook.com/dialog/oauth?
					client_id=111637438874755
					&redirect_uri=http://www.giftflow.org/member/facebook_authorize
					&scope=email, user_photos, publish_stream
					&state=hanslbean");
		
		// Once the user has been authorized, this code parses the authorization code and sends 
		// the necessary data to the Auth class.
//		else
//		{
//			$access = $key1.'='.$val1;
//			if(!empty($key2)&&!empty($val2))
//			{
//				$access .= $key2.'='.$val2;
//			}
//			$facebook_data = json_decode(file_get_contents("https://graph.facebook.com/me?".$access));
//			
//			if($key1=="access_token")
//			{
//				$facebook_data->token = $val1;
//			}
//			
//			$this->auth->facebook($facebook_data);
//		}
	}

	//Callback from facebook authorization
	function facebook_authorize($data = null, $code = null)
	{
		var_dump($data);
		echo 'yayryayr';
		var_dump($code);
		if(!empty($code))
		{
		redirect("https://www.graph.facebook.com/oauth/access_token?
				client_id=111637438874755
				&redirect_uri=http://www.giftflow.org/member/facebook_two
				&client_sectet=797a827203a1ad62cace9fa429100567
				&code=$code");
		}
	}

	function facebook_two()
	{
		print_r('hello');
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
