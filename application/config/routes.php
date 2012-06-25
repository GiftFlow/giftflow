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


/**
*	i18n Routing
*	Full language list: 'en|es|fr|de|it|nl|sv|no|da|fi|is|ru|et|lv|pl|pt|ja'
*	Currently supported languages: 'en|es|fr|de|it|nl'
*/ 

// example: '/en/about' -> use controller 'about'

// '/en' and '/fr' -> use default controller
$route['^(en|es|fr|de|it|nl)$'] = $route['default_controller'];

/**
*	Custom routes
*/
$route['^(en|es|fr|de|it|nl)/login'] = 'member/login';
$route['(en|es|fr|de|it|nl)/login/(:num)'] = 'member/login/$1';
$route['(en|es|fr|de|it|nl)/register'] = 'member/register';
$route['(en|es|fr|de|it|nl)/logout'] = 'member/logout';
$route['(en|es|fr|de|it|nl)/profile/:num'] = 'people/profile/$1';
$route['(en|es|fr|de|it|nl)/people/:num'] = 'people/profile/$1';
$route['(en|es|fr|de|it|nl)/you/profile'] = 'people/profile';
$route['(en|es|fr|de|it|nl)/gifts/:num'] = "goods/view";
$route['(en|es|fr|de|it|nl)/watches/:num/delete'] = "watches/delete/:num";
$route['(en|es|fr|de|it|nl)/gifts/:num/:any'] = "goods/view";
$route['(en|es|fr|de|it|nl)/needs/:num'] = 'goods/view';
$route['(en|es|fr|de|it|nl)/needs/:num/:any'] = 'goods/view';
$route['(en|es|fr|de|it|nl)/tag/(:any)'] = "find/index/$1";
$route['(en|es|fr|de|it|nl)/find/(:any)'] = 'find/index/$1';
$route['(en|es|fr|de|it|nl)/find/(:any)/(:any)'] = 'find/index/$1/$2';
$route['(en|es|fr|de|it|nl)/you/gifts/add'] = 'you/add_good/gift';
$route['(en|es|fr|de|it|nl)/you/needs/add'] = 'you/add_good/need';
$route['(en|es|fr|de|it|nl)/occupynewhaven'] = 'people/profile/1127';
$route['(en|es|fr|de|it|nl)/giftflow'] = 'people/profile/482';
$route['(en|es|fr|de|it|nl)/newhavenreads'] = 'people/profile/1277';
$route['(en|es|fr|de|it|nl)/GNHHA'] = 'people/profile/1322';
$route['(en|es|fr|de|it|nl)/DevilsGear'] = 'people/profile/1326';
$route['(en|es|fr|de|it|nl)/DESK'] = 'people/profile/1329';
$route['(en|es|fr|de|it|nl)/brucafe'] = 'people/profile/1384';
$route['(en|es|fr|de|it|nl)/miyassushi'] = 'people/profile/1383';
$route['(en|es|fr|de|it|nl)/lost'] = 'root/lost';
$route['(en|es|fr|de|it|nl)/restricted'] = 'root/restricted';
$route['(en|es|fr|de|it|nl)/donate'] = 'about/donate';


// Catch-all route
$route['^(en|es|fr|de|it|nl)/(.+)$'] = "$2";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */
