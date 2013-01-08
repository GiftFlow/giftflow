<?php
/**
*	Find and search pages routed through this controller.
*
*	@author Brandon Jackson
*	@package Controllers
*/
class Find extends CI_Controller {

	var $data;
	
	var $G;

	/**
	*	@var array
	*/
	var $args = array(
		"q"=>"",
		"type"=>"gift",
		"location"=>NULL,
		"category_id"=>NULL,
		"radius"=>10000,
		"limit"=>50,
		"offset"=>0,
		'order_by' => NULL,
		'profile_type' => NULL
	);
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->load->library('Search/Good_search');
		$this->load->library('Search/User_search');
	}
	
	function gifts()
	{
		$this->_items("gift");
	}
	
	function people()
	{
		$this->_items("people");
	}
	
	function needs()
	{
		$this->_items("need");
	}
	
	function _items($type)
	{
		Console::logSpeed("Find::items()");
		
		$this->args["type"] = $type;
		
		$this->_set_args();
		
		$this->_search();
		
		// Page Title
		$this->data['title'] = "Find";
				
		// Type of data being searched
		$this->data['type'] = $this->args["type"];
		
		// Search keyword
		$this->data['keyword'] = $this->args["q"];
		
		// Search args
		$this->data['args'] = $this->args;

		//Store searh radius
		$this->data['radius'] = $this->args["radius"];

		$this->data['display'] = (empty($this->data['results']))? 'no_results' : 'results';

		$more_available = (count($this->data['results']) == $this->args['limit']);
		$this->data['more_available'] = json_encode($more_available);



		// Load category data
		$this->data['categories']= $this->db->order_by("name","ASC")
			->where('parent_category_id',NULL)
			->get('categories')
			->result();
		
		// Load Menu
		$this->data['menu'] = $this->load->view('find/includes/menu', $this->data, TRUE);

		$this->data['category_menu'] = $this->load->view('find/includes/categories.php', $this->data, TRUE);

		$this->data['people_menu'] = $this->load->view('people/includes/submenu.php', $this->data, TRUE);

		$this->data['js'][] = 'masonry.js';

		// Load views
		$this->load->view('header',$this->data);
		//$this->load->view('find/includes/header',$this->data);
		$this->load->view('find/masonry', $this->data);
		$this->load->view('footer', $this->data);
		
		Console::logSpeed("Find::items(): done.");
	}
	
	public function ajaxRequest()
	{
		$this->args["type"] = $_REQUEST['type'];
		
		$this->_set_args();
		
		Console::logSpeed("Find::starting_query");
		
		$this->_search();
		
		return $this->util->json($this->data['results_json']);
	}

	public function index()
	{
		$this->gifts();
	}
	
	/**
	*	Performs search
        *       uses $this->args for parameters
        *      sets $this->data['results'] and $this->data['results_json']
        *      does not return anything
        *      
	*/
	function _search ()
	{
		Console::logSpeed("Find::_search()");
		
		$this->data['results'] = array();
		
		if($this->args["type"]=="people")
		{
			Console::logSpeed("Find::_search(): starting User_search...");
			$options= array(
				"keyword"=>$this->args["q"],
				"location"=>$this->args['location'],
				"radius"=>$this->args['radius'],
				"limit" => $this->args['limit'],
				"order_by"=>$this->args['order_by'],
				"sort" => $this->args['sort'],
				'status' => 'active',
				'type' => $this->args['profile_type']
			);
			
			$US = new User_search;
			$results = $US->find($options);
			$this->data['results'] = $this->factory->users_ajax($results, $this->args['order_by']);
		}
		else
		{
			Console::logSpeed("Find::_search(): starting goods search...");
			
			$GS = new Good_search;
			
			$options = array(
				"location"=>$this->args["location"],
				"keyword"=>$this->args["q"],
				"type"=>$this->args["type"],
				"category_id"=>$this->args["category_id"],
				"order_by"=>$this->args["order_by"],
				"status"=>"active",
				'sort' =>$this->args['sort'],
				'radius' => $this->args['radius'],
				'limit' =>$this->args['limit'],
				"limit" => $this->args['limit'],
				'offset' => $this->args['offset']
			);
			
			$results = $GS->find($options);
			$this->data['results'] = $this->factory->goods_ajax($results, $this->args['order_by']);
		}
		
		// Encode results into JSON
		$data = array(
			"center"=>$this->args["location"],
			"total_results"=>count($this->data['results']),
			"results"=>$this->data['results']
		);

		$this->data['results_json'] = json_encode($data);
	}
	
        /**
         *  Sets search parameters
         *  First scans through URL segments - set in util::parse_globals
         *  Then scans the $_REQUEST array 
         *  Matches up inputs with search options  
         */
	function _set_args()
	{
		Console::logSpeed("Find::_set_args()");
		
		// Scan 3rd URL segment for `q`
		if(!empty($this->data['segment'][3]))
		{
			$this->args["q"] = $this->data["segment"][3];
		}
		
		// Get Data from $_GET and $_POST arrays
		foreach($this->args as $key=>$val)
		{
			if(!empty($_REQUEST[$key]))
			{
				$this->args[$key] = $_REQUEST[$key];
			}
		}
		
                
		// Set order by clause, Figure out Location parameters
		// UI passes a value of either "newest" or "nearby"
		// Search lib requires values of either "G.created" or 
		// "location_distance"

		$this->args['order_by'] = ($this->args['type'] != 'people') ? 'location_distance' : 'U.created';
		$this->args['sort'] = 'ASC';
		
		// Encode "nearby" as "location_distance" if found
		if(!empty($_REQUEST["order_by"]) && $_REQUEST["order_by"]=="nearby")
		{
			$this->args["order_by"] = "location_distance";
			$this->args['sort'] = 'ASC';
		}
		// If location consists only of a string, geocode it
		if(!empty($this->args["location"]) && !is_object($this->args["location"]))
		{
			$this->load->library('geo');
			$this->args['location'] = $this->geo->process($this->args['location']);

		}
		//if location isn't provided, then don't use it!
		elseif(empty($this->args["location"]) && !empty($this->data['userdata']['location']))
		{
			$this->args['location'] = $this->data['userdata']['location'];
		}
		elseif(empty($this->args['location']))
		{
			$this->args['sort'] = 'DESC';

			$this->args['order_by'] = ($this->args['type'] != 'people') ? 'G.created' : 'U.created';

		}
		
		if(!empty($_REQUEST['radius']))
		{
			$this->args['location']->radius = $_REQUEST['radius'];
			$this->args['radius'] = $_REQUEST['radius'];
			$this->radius = $_REQUEST['radius'];
		}
		
	}
}
