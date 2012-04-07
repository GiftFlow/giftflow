<?php
/**
*	Test Controller used to figure stuff out
*
*	@author Brandon Jackson
*	@package Controllers
*/
class Test extends CI_Controller {

	var $data;
	var $G;
	var $U;
	var $q = 'to';

	
	function __construct()
	{
		parent::__construct();
    $this->CI =& get_instance();
		$this->util->config();
    $this->auth->bouncer(100);
		$this->data = $this->util->parse_globals(array(
			"geocode_ip"=>TRUE
		));
		$this->load->library('geo');
		$this->load->library('datamapper');
		$this->load->library('Search/User_search');
		$this->load->library('Search/Good_search');
		$this->load->library('Search/Transaction_search');
		
		if(!empty($this->data['logged_in_user_id']))
		{
			$this->U = new User($this->data['logged_in_user_id']);
		}
	}
	
	function index()
	{
		//$this->keyword_test('people');
		//$this->keyword_test('goods');
    $this->ip_magic();
	}
	
	
	function keyword_test($type)
	{
		//Test Find page first
		
		switch ($type) {
			case 'people':
				$Search = new User_search();
				$field_one = 'screen_name';
				$field_two = 'bio';
								
			case 'goods':
				$Search = new Good_search();
				$field_one = 'title';
				$field_two = 'description';
			}
		
		$options = array(
			'keyword' => $this->q
			);
		
		$results = $Search->find($options);
		
		echo "Searching ".$type."<br/>";
		
		foreach($results as $key=>$val)
		{
			
			$one = strpos($val->$field_one, $this->q);
			
			$two = strpos($val->$field_two, $this->q);
			
			if(is_bool($one) && is_bool($two))
			{
				echo $val->id;
				echo ' FALSE';
				var_dump($one);
				var_dump($two);

				echo "<br />";
			}
			elseif(is_int($one) || is_int($two))
			{
				echo $val->id;
				echo ' TRUE';
				echo "<br />";
			}
			else
			{
				echo "??????";
			}
		}	
	}

  public function ip_magic()
  {
    Console::logSpeed("test::ip_magic()");
    $this->CI->db->select("
      U.id AS user_id,
      U.screen_name AS screen_name,
      U.ip_address AS ip_address, 
      U.default_location_id AS default_location_id,
      U.email AS email")
      ->from("users AS U")
      ->where('U.default_location_id IS NULL')
      ->order_by('updated','ASC')
      ->limit(200);

	  $unknowns = $this->CI->db->get()->result();

    $geo = new geo(); 
    echo count($unknowns)." unknowns!";
    $Geo = new geo();           
    $i=0;

    foreach($unknowns as $key => $val)
    {
 //     echo $val->screen_name;
      $location=$Geo->geocode_ip($val->ip_address);
      if(isset($location->state) && isset($location->city))
      {
       $this->CI->db->select('L.city,L.state,L.id')
                    ->from('locations AS L')
                    ->where('L.state', $location->state)
                    ->limit(1);
      $match = $this->CI->db->get()->result();

      $data = array();

      if(!empty($match))
      {
        foreach($match as $hah)
        {

          $data = array('default_location_id' => $hah->id);
        }
           $this->CI->db->where('id',$val->user_id);
           $this->CI->db->update('users',$data);
            $i++;
            echo "done ".$i;
      }
      }
    }
  }

} 
	

