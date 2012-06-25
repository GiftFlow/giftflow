<?php

class Gifts extends CI_Controller {

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
		
		Console::logSpeed('Gifts::_construct()');

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
		redirect('find/gifts');
		// Load and instantiate Tag_search and Good_search libraries
		$this->load->library('Search/Tag_search');
		$this->load->library('finder');
		$this->load->library("datamapper");
		
		$GS = new Good_search();
		$T = new Tag_search;

		// Get latest goods
		$this->data['new_gifts'] = $GS->latest_goods(array(
			"type"=>"gift"
		));
		
		
		if(!empty($this->data['userdata']['location']))
		{
			$this->data['nearby_gifts'] = $GS->find(array(
				"type"=> "gift",
				"location"=>$this->data['userdata']['location'],
				"order_by"=>"location_distance"
			));
		}
		else
		{
			$this->data['nearby_gifts'] = array();
		}
		
		// Get popular tags
		$tag_search_options = array(
			"type"=>"gift",
			"limit"=>20
		);
		
		// Filter tag search by location if location data available
		if(!empty($this->data['userdata']['location']))
		{
			$tag_search_options["location"] = $this->data['userdata']['location'];
		}
		
		// Execute tag search
		$this->data['tags'] = $T->popular_tags($tag_search_options);
	
		// Set view variables
		$this->data['title'] = "Gifts";
		
		// Load Menu
		$this->data['menu'] = $this->load->view('gifts/includes/menu',$this->data, TRUE);

		// Load views
		$this->load->view('header',$this->data);
		$this->load->view('gifts/includes/header',$this->data);
		$this->load->view('gifts/index', $this->data);
		$this->load->view('footer', $this->data);
	}
	
}
