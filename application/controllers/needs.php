<?php

class Needs extends CI_Controller {

	/**
	*	View data
	*
	*	@var array
	*/
	var $data;
	
	/**
	*	Good object
	*
	*	@var Object
	*/
	var $G;
	
	/**
	*	User object
	*
	*	@var Object
	*/
	var $U;
	
	/**
	*	Transaction object
	*
	*	@var Object
	*/
	var $T;
	
	/**
	*	Good ID / Second URL Segment
	*
	*	@var int
	*/
	var $good_id;
	
	/**
	*	Method name / Third URL Segment
	*
	*	@var string
	*/
	var $method;
	
	/**
	*	Extra parameter / Fourth URL Segment
	*
	*	@var string
	*/
	var $param;
		
	function __construct()
	{
		parent::__construct();
		
		Console::logSpeed('Needs::_construct()');

		// Load external classes
		$this->load->helper('elements');
		$this->hooks =& load_class('Hooks');		
		$this->load->library('Search/Good_search');
				
		$this->util->config();
		$this->data = $this->util->parse_globals(array(
			"geocode_ip"=>TRUE
		));

		// Set some class-wide variables
		$this->good_id = $this->data["segment"][2];
		$this->method = $this->data["segment"][3];
		$this->param = $this->data["segment"][4];

	}
	
	/**
	*	Index page
	*/
	function index()
	{
		Console::logSpeed("Needs::index()");
		
		// Load and instantiate Tag_search library
		$this->load->library('Search/Tag_search');
		$this->load->library('finder');
		$T = new Tag_search;
		
		// Get popular tags
		$tag_search_options = array(
			"type"=>"need"
		);

		// Filter tag search by location if location data available
		if(!empty($this->data['userdata']['location']))
		{
			$tag_search_options["location"] = $this->data['userdata']['location'];
		}
		
		// Execute tag search
		$this->data['tags'] = $T->popular_tags($tag_search_options);
		
		// Get most recent needs
		$N = new Good_search();
		$this->data['new_needs'] = $N->latest_goods(array(
			"type"=>"need"
		));

		
		if(!empty($this->data['userdata']['location']))
		{
			$this->data['nearby_needs'] = $N->find(array(
				"type"=> "need",
				"location"=>$this->data['userdata']['location']
			));
		}
		else
		{
			$this->data['nearby_needs'] = array();
		}
		// Set view variables
		$this->data['title'] = "Needs";
		
		// Load Menu
		$this->data['menu'] = $this->load->view('needs/includes/menu',$this->data, TRUE);

		// Breadcrumb
		$this->data['breadcrumbs'][] = array(
			"title"=>"Needs",
			"href"=>site_url("needs")
		);
		$this->data['breadcrumbs'][] = array(
			"title"=>"Latest Needs"
		);

		
		// Load Views
		$this->load->view('header',$this->data);
		$this->load->view('needs/index', $this->data);
		$this->load->view('footer', $this->data);
	}
	
}
