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
	 * Mimics the you::index page except for not logged in users
	 * Focused on the location
	 */

	function home()
	{
		$this->load->library('Search/Good_search');
		$this->load->library('Search/User_search');
		$this->load->library('event_reader');

		//Load most recent users
		$P = new User_search();
		$this->data['new_peeps'] = $P->find(array(
			'limit' => 15,
			'order_by' => 'U.created',
			'sort' => 'DESC'
		));


		$this->data['nonprofits'] = $P->find(array(
			'type' => 'nonprofit',
			'limit'=> 10
		));

		$G = new Good_search();
		$this->data['goods'] = $G->find(array(
			'type' => NULL,
			'limit' => 15,
			'order_by' => 'G.created',
			'sort' => 'DESC'
		));

		$this->load->library('event_reader');
		$E = new Event_reader();
		$this->data['activity'] = $E->get_events(array(
			'event_type_id' => array(17,2),
			'limit' => 40
		));


		$this->data['title'] = "GiftFlow Home";
		
		// Load Views
		$this->load->view('header', $this->data);
		$this->load->view('home', $this->data);
		$this->load->view('footer', $this->data);
		
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
