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
		$this->load->library('Search/Good_search');
		$this->load->library('Search/User_search');
	}


	/**
	 * Mimics the you::index page except for not logged in users
	 * Focused on the location
	 */

	function home()
	{
		$this->load->library('Search/Event_search');
		$location = $this->data['userdata']['location'];


		//Abstract bottom to lists
		$list_one = array();
		$list_two = array();

		//Load most recent users
		$P = new User_search();

		//load followers
		if($this->data['logged_in']) {
			$list_one['data'] = $P->following(array(
				'user_id'=>$this->data['userdata']['user_id'],
				'limit' => 14
			));
			$list_one['title'] = "Following";

		} else {
			$list_one['data']  = $P->find(array(
				'type' => 'nonprofit',
				'limit'=> 12,
				'location' => $location
			));
			$list_one['title'] = "Nonprofits";
		}

		$G = new Good_search();
		$this->data['goods'] = $G->find(array(
			'limit' => 100,
			'order_by' => 'G.created',
			'sort'=>'desc',
			'location' => $location
		));

		$E = new Event_search();
		$this->data['activity'] = $E->get_events(array(
			'event_type_id' => array(17,2),
			'limit' => 40
		));


			
		//For now the id of the featured user will just be hardcoded, edited inline. 
		//@todo make this something in the admin interface
		$featured_user_id = 1329;
		
		$this->data['featured'] = $P->get(array('user_id' => $featured_user_id));
		$this->data['featured']->gifts = $G->find(array('user_id' => $featured_user_id, 'type' => 'gift'));
		$this->data['featured']->needs = $G->find(array('user_id' => $featured_user_id, 'type' => 'need'));


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

	function following_goods()
	{

		$U = new User_search();
		$G = new Good_search();
		$goods = array();

		$following_users = $U->following(array(
			'user_id' => $this->data['logged_in_user_id']));

		$following_user_ids = array();

		foreach($following_users as $val)
		{
			$following_user_ids[] = $val->id;
		}

		if(!empty($following_user_ids))
		{
			$options = array(
				"user_id" => $following_user_ids,
				"order_by" => 'created',
				'limit' => 9,
				'status'=> 'active'
			);
			$goods = $G->find($options);
		}

		return $goods;

	}

}
