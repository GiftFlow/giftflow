<?php
class Root extends CI_Controller {

	var $data;
	var $facebook;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->config->load('account', TRUE);
		$fbook = $this->config->config['account'];

		//load the facebook sdk
		require_once('assets/facebook/facebook.php');
		$config = array (	
			"appId"=> $fbook['appId'],
			"secret"=> $fbook['secret'],
			"fileUpload"=>true
		);
		$this->facebook = new Facebook($config);
	}


	function index()
	{
		if(!$this->auth->validate(1))
		{
			$this->_signup();
		}
		else
		{
			redirect('you');
		}
	}
	
	function _signup()
	{

		$this->load->library('event_reader');
		$E = new Event_reader();

		$options = array(
		  'event_type_id' => array(2,8,4),
		  'limit' => 100,
		  'location' => $this->data['userdata']['location']
		  );

		$this->data['events'] = $E->get_events($options);
				

		$params = array(
			'scope' => 'email, user_photos, publish_stream',
			'redirect_uri' => 'http://mvp.giftflow.org/member/login'
		);

		$this->data['fbookUrl'] = $this->facebook->getLoginUrl($params);

		$this->data['title'] = "Welcome";
		$this->load->view('header', $this->data);
		$this->load->view('index');
		$this->load->view('footer', $this->data);
	}
	
	function lost()
	{
		$this->data['title'] = "Oops! Page cannot be found.";

		$this->load->view('header', $this->data);
		$this->load->view('404', $this->data);
		$this->load->view('footer', $this->data);
	}
	function restricted()
	{
		$this->data['title'] = "Restricted area";

		$this->load->view('header', $this->data);
		$this->load->view('restricted', $this->data);
		$this->load->view('footer', $this->data);
	}
}
