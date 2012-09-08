<?php

class Ajax extends CI_Controller {

	var $data;
	var $G;
	var $location;
	var $object;
	var $format = 'JSON';
	var $source;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->load->library('finder');
	}
	
	/**
	*	Encodes a string into a usable location, then either returns
	*	the object or outputs the result as JSON
	*
	*	@param string $address
	*	@param boolean $JSON
	*/
	public function geocode( $address = NULL, $JSON = FALSE )
	{
		if(empty($address))
		{
			$address = $_POST['where'];
		}
		
		$this->load->library('geo');				
		$result = $this->geo->geocode($address);
				
		if($JSON)
		{
			echo json_encode($result);
		}
		else 
		{
			return $result;
		}
	}

	/**
	*	Get a GiftFlow based on provided source
	*
	*	The source variable should be passed along as part of either the 
	*	POST or GET data. this variable will directly determine which method 
	*	the Finder library uses to get the search results. Example values of 
	*	the source variable are followers (default), nearby_gifts and 
	*	latest_gifts.
	*
	*	@return JSON
	*/
	public function giftflow()
	{
		!empty($_REQUEST['source']) ? $this->source = $_REQUEST['source'] : $this->source = 'followers';
		!empty($_REQUEST['offset']) ? $offset = $_REQUEST['offset'] : $offset = 0;
		!empty($_REQUEST['limit']) ? $limit = $_REQUEST['limit'] : $limit = 10;
		//echo $this->finder->limit($limit, $offset)->giftflow();
		$this->location = $this->data['userdata']['location'];	
			
		//Load libraries
		$this->load->library('Search/Good_search');
		$G = new Good_search();
		$G->user_id = $this->data['logged_in_user_id'];
		$this->load->library('Search/User_search');
		$F_u = new User_search();
		$F_u->user_id = $this->data['logged_in_user_id'];
		
		if($this->source == "following")
		{			
			$following_users = $F_u->following($options = array('user_id' => $this->data['logged_in_user_id']));
			
			$following_user_ids = array();
			
			foreach($following_users as $val)
			{
				$following_user_ids[] = $val->id;
			}
			
			if(!empty($following_user_ids))
			{
				$options = array(
					"user_id" => $following_user_ids,
					'limit' => 10,
					'type' => 'gift',
				);
				$this->object = $G->find($options);
			}
			else
			{
				$this->object = NULL;
			}
			
		}
		elseif($this->source == "nearby_gifts")
		{
			
			$options = array(
				"location" => $this->data['userdata']['location'],
				"limit" => 10,
				"type" =>"gift"
			);
			$this->object = $G->find($options);
		}
    elseif($this->source == 'activity')
    {

      //Load event reader for activity feed
      $this->load->library('Event_reader');
      $E = new event_reader();
      $options = array(
        'event_type_id' => array(2,8),
        'limit' => 30,
        'location' => $this->data['userdata']['location']
        );
        
        $this->object = $E->get_events($options);

     } //close activity
			
			
      $this->location = $this->data['userdata']['location'];
		 	$data = $this->_render_output();
		 	$this->output->set_header("Content-Type:application/json");
			$this->output->set_output($data);

	}
	
	public function find_people()
	{
	
		$keyword = $_POST['keyword'];
		$this->load->library('Search/User_search');
		$U = new User_search();
		
    $options = array('keyword'=> $keyword);

		$this->object = $U->find($options);
			
			if(!empty($this->object))
			{
				foreach($this->object as $key=>$val)
				{
					$val->html = UI_Results::users(array(
						"results"=>$val,
						"include"=>array("created","location"),
						"row"=>TRUE,
						"mini" => FALSE
					));
				}
				
				$data['total_results'] = count($this->object);
				$data['results'] = ($this->object);
			}
			else
			{
				return FALSE;
			}
		$results = json_encode($data);
		$this->output->set_header("Content-Type:application/json");
		$this->output->set_output($results);
	
	}
	
	/**
	*	Searches for tags that match the user's input and returns the most
	*	popular matches. Used in the good creation and editing process.
	*/
	public function tags()
	{
		$keyword = trim($_POST['term']);
		
		$result = array();
		
		if(!empty($keyword))
		{
			$query = $this->db->select('Concat(t.name," (",COUNT(g.id), ")") AS label, t.name AS value ', FALSE)
				->from('goods_tags AS gt ')
				->join('goods AS g ', 'g.id=gt.good_id')
				->join('tags AS t ', 't.id = gt.tag_id')
		//		->where('g.type','gift')
				->like('t.name',$keyword, 'after')
				->group_by('t.name')
				->limit(10)
				->order_by('COUNT(g.id)', 'desc')
				->get();
			$result = $query->result_array();
		}
		
		echo json_encode($result);
	}

	
	public function add_tag()
	{
		if(empty($_POST['new_tag']))
		{
			return FALSE;
		}
		else
		{
			$this->load->library('datamapper');
			$G = new Good();
			$G->get_by_id($_POST['good_id']);
			$tags = explode(",", $_POST['new_tag']);
		
				foreach ( $tags as $tag )
				{
					if(!$G->add_tag(trim($tag)))
					{
						return FALSE;
					}
				}
		}
	}
	
	public function remove_tag()
	{
		if(empty($_POST['old_tag']))
		{
			return FALSE;
		}
		else
		{
			$this->load->library('datamapper');
			$G = new Good();
			$G->get_by_id($_POST['good_id']);
			$tag = $_POST['old_tag'];
			
			if(!$G->remove_tag($tag))
			{
				echo $G->error->string;
			}
		}
	}
	
	/**
	*	Change the location stored in a user's session by geocoding
	*	a user-input location string
	*	Used in Location section on the find page
	*	Location passed as $_POST['location']
	*/
	function relocate()
	{
		// Geocode user input
		$this->load->library('geo');				
		$location = $this->geo->geocode($_REQUEST['location']);
		
		if(empty($location))
		{
			return false;
		}

		// Update user's session with new location
		$this->auth->update_session_location($location);
		
		// Return JSON of geocoded location
		return $this->util->json(json_encode($location));
	}
	/**
	*	Renders the output of the query as either an object or JSON.
	*
	*	The type of the output depends on the $this->format field. By default,
	*	this function outputs JSON if the request was made via AJAX and it
	*	outputs an object if the request was not AJAX.
	*/
	protected function _render_output()
	{
			$this->_format_result();
			$data['center'] = (object) $this->location;
			$data['total_results'] = count($this->object);
			$data['results'] = $this->object;
			return json_encode($data);
	}
	
	protected function _format_result()
	{
		Console::logSpeed('Finder::_format_result()');

		if(!empty($this->object))
		{
			foreach($this->object as $key=>$val)
			{
        if($this->source != 'activity')
        {
          $val->html = UI_Results::goods(array(
            "results"=>$val,
            "include"=>array("author","location"),
            "row"=>TRUE
          ));
        }
        elseif($this->source == 'activity')
        {
          $val->html = UI_Results::events(array(
             "results" => $val,
             'mini' => FALSE,
           ));
        }
      }
		}
		else
		{
			return false;
		}
		
		Console::logSpeed('Finder::_format_result(): done.');
	}

}
