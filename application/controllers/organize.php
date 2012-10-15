<?php

class Organize extends CI_Controller {

/*  
*	var to hold gift results
*	@var object
*/
var $gifts;

var $needs;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('Search/Good_search');
		$this->load->library('datamapper');
		$this->data = $this->util->parse_globals();
		$this->util->config();
	}

	function index()
	{
		$this->data['show_form'] = TRUE;
		if(!empty($_GET) || !empty($_POST))
		{
		
			$this->data['show_form'] = FALSE;
			//Geocode location input from form
			$L= new Location();
			$this->load->library('geo');
			$Geo = new geo();
			
			if(!empty($_POST))
			{
				$full_location = $Geo->geocode($this->input->post('location'));
			}
			elseif(!empty($_GET))
			{
				$full_location = $Geo->geocode($this->input->get('location'));
			}
			
			foreach($full_location as $key=>$val)
			{
				$L->$key = $val;
			}
	
			//Location set
			$this->data['L'] = $L;
			
			//Now load lists
			//Gifts
			$G = new Good_Search();
			
			$options = array(
				'type'=> 'gift',
				'location' => $L,
				'order_by' => "G.created",
				'limit' => 20,
				'radius' => 100,
				'sort' => 'DESC'
			);
			
			$this->data['gifts'] = $G->find($options);
		
			//Needs
			unset($options);
			$options = array(
				'type'=> 'need',
				'location' => $L,
				'order_by' => "G.created",
				'limit' => 20,
				'radius' => 100,
				'sort' => 'DESC'
			);
			$this->data['needs'] = $G->find($options);
			
		}		
		
		$this->data['hide_header'] = TRUE;
		$this->data['title'] = 'Organizing Tools';
		$this->load->view('header', $this->data);
		$this->load->view('organizing');
		$this->load->view('footer', $this->data);	
		
		
	}
	


}