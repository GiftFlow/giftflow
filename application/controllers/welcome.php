<?php
/**
*	Welcome section
*	These pages are used when a user first creates their account.
*/
class Welcome extends CI_Controller {

	var $data;
	
	var $U;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->hooks =& load_class('Hooks');
		$this->load->library('datamapper');
	}

	/**
	*	Loaded after user first creates GiftFlow account
	*/
	function index()
	{
		if(!empty($this->data['logged_in_user_id']))
		{
			$this->U = new User($this->data['logged_in_user_id']);
		}
		redirect('you/welcome');
	}
	
	/**
	*	Loaded after user successfully creates account using Facebook
	*/
	function facebook()
	{
		redirect('you/welcome');
	}
	
	function hide_welcome()
	{
		//form question is 'Show this page at log in?'
		if($_POST['hide_welcome'] == "no")
		{
			$hook_data = array(
				"user_id" => $this->data['logged_in_user_id']
				);
			$this->hooks->call('hide_welcome', $hook_data);
			$this->session->set_flashdata('success', 'Next time you log in, you won\'t see the Welcome page');
		}

		redirect('you');
	}
	
}
