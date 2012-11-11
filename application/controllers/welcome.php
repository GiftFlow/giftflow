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
		$this->load->library('Search/Event_search');

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

		$E = new Event_search();
		$this->data['activity'] = $E->get_events(array(
			'event_type_id' => array(17,2),
			'limit' => 40
		));

		//For now the id of the featured user will just be hardcoded, edited inline. 
		//@todo make this something in the admin interface
		$this->data['featured'] = $P->get(array('user_id' => 9));
		$this->data['featured']->gifts = $G->find(array('user_id' => 9, 'type' => 'gift'));
		$this->data['featured']->needs = $G->find(array('user_id' => 9, 'type' => 'need'));


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
	
}
