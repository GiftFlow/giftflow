<?
require_once '../system/core/CodeIgniter.php';

new Unit_tests();

Class Unit_tests 

{

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('unit_test');

	
	}
	
	function index()
	{
		echo $this->find_test();
	
	}
	
	function find_test()
	{
		
		$options= array(
				"screen_name"=>'Cadman', 
				"first_name"=>'Cadman', 
				"last_name"=>'Nash', 
				"bio"=>'Cadman',
				"occupation"=>'Cadman',
				"location"=>'New Haven',
				"radius"=>100,
				"limit"=>50,
				"order_by"=>"location_distance",
				"sort" => 'ASC'
			);
			
			$this->load->library('Search/User_search');
			$U = new User_search();
			
				$results = $U->find($options);
			return('foo');
			
			 assert(is_string($results[0]->default_photo->thumb_url)
					&&(is_object($results[0]->location))
			);
		
			
			
			
			
// 			[0] => stdClass Object
//         (
//             [location] => stdClass Object
//                 (
//                     [address] => Baton Rouge, LA, USA
//                     [city] => Baton Rouge
//                     [state] => LA
//                     [latitude] => 30.1583
//                     [longitude] => -90.8403
//                 )
// 
//             [default_photo] => stdClass Object
//                 (
//                     [thumb_url] => http://localhost/giftflow/assets/images/user.png
//                     [url] => http://localhost/giftflow/assets/images/user.png
//                 )
// 
//             [id] => 1323
//             [email] => CadmanMaddox@gondor.com
//             [screen_name] => Cadman Maddox
//             [first_name] => Cadman
//             [last_name] => Maddox
//             [photo_source] => giftflow
//             [photo_id] => 
//             [facebook_id] => 
//             [created] => 2006-03-03 00:00:00
//             [am_following] => 0
//             [is_follower] => 0
//         )
// 		
	


	}







}