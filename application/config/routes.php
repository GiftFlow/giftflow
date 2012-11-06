<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "root";
$route['scaffolding_trigger'] = "";

// Custom routes
$route['login'] = 'member/login';
$route['login/(:num)'] = 'member/login/$1';
$route['register'] = 'member/register';
$route['logout'] = 'member/logout';
$route['profile/:num'] = 'people/profile/$1';
$route['people/:num'] = 'people/profile/$1';
$route['you/profile'] = 'people/profile';
$route['gifts/:num'] = "goods/view";
$route['watches/:num/delete'] = "watches/delete/:num";
$route['gifts/:num/:any'] = "goods/view";
$route['needs/:num'] = 'goods/view';
$route['needs/:num/:any'] = 'goods/view';
$route['tag/(:any)'] = "find/index/$1";
#$route['find/(:any)'] = 'find/index/$1';
#$route['find/(:any)/(:any)'] = 'find/index/$1/$2';
$route['you/gifts/add'] = 'you/add_good/gift';
$route['you/needs/add'] = 'you/add_good/need';
$route['occupynewhaven'] = 'people/profile/1127';
$route['giftflow'] = 'people/profile/482';
$route['newhavenreads'] = 'people/profile/1277';
$route['GNHHA'] = 'people/profile/1322';
$route['DevilsGear'] = 'people/profile/1326';
$route['DESK'] = 'people/profile/1329';
$route['brucafe'] = 'people/profile/1384';
$route['miyassushi'] = 'people/profile/1383';




$route['lost'] = 'root/lost';
$route['restricted'] = 'root/restricted';
$route['donate'] = 'about/donate';
/* End of file routes.php */
/* Location: ./system/application/config/routes.php */
