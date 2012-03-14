<?php

class Api extends CI_Controller {

	var $data;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
	}

	public function member( $id, $method, $param = null )
	{
		if($method=="photo")
		{
			$this->_member_photo( $id, $param );
		}
		
	}
	protected function _member_photo( $id, $param = null )
	{
		$this->load->library('Search/User_search');
		$this->load->library('finder');
		$User_search = new User_search;
		$U = $User_search->get(array(
			"user_id"=>$id
		));
		$url = $this->finder->photo_url($U);
		if( $url != FALSE )
		{
			$ext = pathinfo($url, PATHINFO_EXTENSION);
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Content-type: image/'.$ext);
			header( "Location: ".$url);
			die();
		}
		else
		{
			echo "User not valid.";
			die();
		}
	}
}