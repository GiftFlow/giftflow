<?php
class Root extends CI_Controller {

	var $data;
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
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
		$this->load->library('Search/Good_search');

		$E = new Event_reader();

		$options = array(
		  'event_type_id' => array(2,8,4,16),
		  'limit' => 6,
		  );

		$this->data['events'] = $E->get_events($options);

		$G = new Good_search();

		$options = array(
			'sort' => 'newest',
			'limit' => '6',
		);
		$this->data['goods'] = $G->find($options);

				

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
