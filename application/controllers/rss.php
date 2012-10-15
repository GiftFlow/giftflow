<?php

class Rss extends CI_Controller {
	
	/**
	*	@var array
	*/
	var $data;
	
	/**
	*	List of parameters accepted in each method
	*	@var array
	*/
	var $legal_params;
	
	/**
	*	Result from the finder query
	*	@var object
	*/
	var $result;
	
	var $params;
	
	/**
	*	Constructor
	*/
	function __construct()
	{
		parent::__construct();
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->load->library('finder');
		$this->load->library('Search/Good_search');
		$this->load->library('datamapper');

		$this->params = $_GET;
	}
	
	function index()
	{
		$logged_in_user_id = $this->session->userdata('user_id');
		
		// If user logged in, Load user's RSS feed 
		if(!empty($logged_in_user_id))
		{
			$this->user($logged_in_user_id);
		}
		
		// Load universal feed
		
		if(!empty($_GET))
		{
			$location = $_GET['location'];
			$this->universal_feed('gift', $location);
		}
		else
		{
			$this->universal_feed('gifts');
		}
		
		
	}
	
	/**
	*	Function gifts returns the ten latest gifts from the 
	*	universal GiftFlow
	*/
	function gifts()
	{
		// List of parameters that are acceptable
		$this->legal_params = array( 'limit', 'order_by', 'format', 'user','location','type');
		
		$this->params['type'] = 'gift';
		$this->_prep_query();
		$this->result = $this->finder->query();
		
		if(!empty($this->result))
		{
			$this->data['giftflow'] = $this->result;
			$this->data['feed_title'] = "Latest Gifts";
			$this->data['feed_description'] = "All the latest gifts on GiftFlow.";
			$this->data['type'] = "gifts";
			$this->load->view('rss/feed', $this->data);
		}
		else
		{
			echo "Error.";
		}
	}
	
	/**
	*	function needs returns the latest needs from the 
	*	universal GiftFlow
	*/
	function needs()
	{
		// List of parameters that are acceptable
		$this->legal_params = array( 'limit', 'order_by', 'format', 'user','location','type');
		
		$this->params['type'] = 'need';
		$this->_prep_query();
		$this->result = $this->finder->query();
		
		if(!empty($this->result))
		{
			$this->data['giftflow'] = $this->result;
			$this->data['feed_title'] = "Latest Needs";
			$this->data['feed_description'] = "All the latest needs on GiftFlow.";
			$this->data['type'] = "needs";
			$this->load->view('rss/feed', $this->data);
		}
		else
		{
			echo "Error.";
		}
	}
	
	/**
	*	RSS feed for individual users
	*
	*	@param int $user_id 		the ID of the user for whom the feed 
	*						was requested
	*	@param string $request		Type of goods to display (eg gifts or 
	*						needs)
	*/
	function user($user_id = 0, $request = 'gifts')
	{
		// Use logged in user id if not passed in URL
		if($user_id == 0)
		{
			$user_id = $this->session->userdata('user_id');
		}

		// Set legal parameters
		$this->legal_params = array( 'user','limit', 'order_by', 'format', 'type');
		
		// Set params data
		$this->params['user'] = $user_id;
		$this->params['type'] = $request;
		
		// Send params to Finder library
		$this->_prep_query();
		
		// Run search in Finder library
		$this->result = $this->finder->user_search();
		
		// Render results
		if (empty($this->result))
		{
			echo "You did not enter a valid user ID.";
		}
		else
		{
			$this->data['giftflow'] = $this->result;
			$this->data['feed_title'] = $this->result[0]->user->screen_name . "'s Latest ".ucfirst($request);
			$this->data['feed_description'] = "All the latest ".$request." from ".$this->result[0]->user->screen_name. " on GiftFlow.";
			$this->data['type'] = $request;
			$this->load->view('rss/feed', $this->data);
		}
	}

	/**
	*	Returns the latest 10 gifts or needs in the giftflow universe
	*/
	function universal_feed($request,$location=NULL)
	{
		
		if(!empty($location))
		{
			$L= new Location();
			$this->load->library('geo');
			$Geo = new geo();
			$full_location = $Geo->geocode($location);

			foreach($full_location as $key=>$val)
			{
				$L->$key = $val;
			}
		}
		else
		{
			$L = '';
		}
		
		
		$G = new Good_search();
		
		$options = array(
			'location' => $L,
			'type'=> 'gift',
			'limit' => 20,
			'order_by' => 'G.created',
			'sort' => 'DESC'
		);
		
		$this->data['giftflow'] = $G->find($options);
		$this->data['feed_title'] = "Latest Gifts on GiftFlow";
		$this->data['feed_description'] = "All the latest Gifts on GiftFlow";
		$this->data['type'] = 'Gifts';
		$this->load->view('rss/feed', $this->data);
	}
	
	/**
	*	Compiles filters, processes $_GET parameters
	*/
	protected function _prep_query()
	{		
		// Limit
		if( $this->_is_parseable('limit') )
		{
			// add limit clause
			$this->finder->limit($this->params['limit']);
		}
		
		// Order by
		if( $this->_is_parseable('order_by') )
		{
			$this->finder->order_by_field = $this->params['order_by'];
		}
		
		// Sort / order by direction
		if( $this->_is_parseable('sort') )
		{
			$this->finder->order_by_direction = $this->params['sort'];
		}
		
		// Set format
		if( $this->_is_parseable('format') )
		{
			// ignore this for now
		}

		// Filter by location
		if( $this->_is_parseable('location') )
		{
			$this->finder->location->address = $this->params['location'];
			
			// Set search radius
			$this->finder->radius = 50;
			
			// Geocodes and sets the location-related finder properties, such as...
			// 	$this->latitude
			//	$this->longitude
			//	$this->address
			$this->finder->geocode();
		}
		
		// Filter by user
		if( $this->_is_parseable('user') )
		{
			if (is_numeric($this->params['user']) || is_array($this->params['user']))
			{
				$this->finder->users = $this->params['user'];
			}
			elseif (is_string($this->params['user']))
			{
				$this->finder->users = explode(",", $this->params['user']);
			}
		}
		
		// Filter by type
		if( $this->_is_parseable('type') )
		{
			// add where clause based on type
			$this->finder->where_type( $this->_parse_type($this->params['type']));
		}
	}
	
	/**
	*	Determines whether a field should be filtered for a specific method
	*	@param string $field
	*	@return boolean
	*/
	protected function _is_parseable( $field )
	{
		if( !empty($this->params[$field]) && in_array($field, $this->legal_params) )
		{
			return TRUE;
		}
		return FALSE;
	}
	
	function _parse_type($type)
	{
		$type = strtolower($type);
		
		if($type=="gifts")
		{
			return "gift";
		}
		
		if($type=="needs")
		{
			return "need";
		}
		
		return $type;
	}
	
	/**
	*	coordinate_feed is the function that will return the last 10 gifts/needs near a pair of coordinates
	*/
	/*function coordinate_feed($user_id)
	{
		$what_r_u = $_GET['id'];
		echo $what_r_u;
		die();
	}*/
}

/* End of file rss.php */
/* Location: ./system/application/controllers/rss.php */