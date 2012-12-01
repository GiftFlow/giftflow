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
	}
	
	
	public function users()
	{
		if(!empty($_POST['term']))
		{	
			$keyword = $_POST['term'];
			
				$query = "SELECT CONCAT(U.screen_name,', ',U.email) AS label, U.email as value
						FROM users AS U 
						WHERE U.id !=".$this->data['userdata']['user_id']."
						AND U.status = 'active'
						AND (U.screen_name LIKE '%".$keyword."%'
						OR U.email LIKE '%".$keyword."%')
						LIMIT 10";


			$result = $this->db->query($query)->result_array();
			echo json_encode($result);
		}
			
	}
	

	/*
	 * Matches string with cities in database
	 */

	public function locations()
	{
		$post = $this->input->post();
		$keyword = trim($post['term']);

		$result=array();

		if(!empty($keyword))
		{
			$query = $this->db->select("DISTINCT(CONCAT(L.city, ', ', L.state)) AS label", FALSE, 'L.address AS value')
					->from('locations AS L')
					->where('L.latitude !=', 'NULL')
					->where('L.longitude !=', 'NULL')
					->where('L.address !=', 'NULL')
					->or_like('L.address', $keyword)
					->or_like('L.state', $keyword)
					->limit(10)
					->get();
			$result = $query->result_array();
		}
		echo json_encode($result);
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
	

}
