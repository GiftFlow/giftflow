<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Global
|--------------------------------------------------------------------------
*/
$config['ssl_enabled'] = FALSE;


/*
|--------------------------------------------------------------------------
| OpenID
|--------------------------------------------------------------------------
*/
$config['openid_file_store_path'] 	= 'system/cache';
$config['openid_google_discovery_endpoint'] = 'https://www.google.com/accounts/o8/id';

/*
|--------------------------------------------------------------------------
| Third Party Auth
|--------------------------------------------------------------------------
*/
$config['third_party_auth_providers'] = array('google', 'facebook', 'twitter');
$config['openid_what_is_url'] 	= 'http://openidexplained.com/';
$config['openid_policy'] = 'openid_auth/policy';
$config['openid_required'] = array('nickname');
$config['openid_optional'] = array('fullname', 'email');
$config['openid_site_root'] = 'http://www.giftflow.org';

$config['openid_request_to'] = $config['openid_site_root'] . '/account/link/google/2';



/*
|--------------------------------------------------------------------------
| OAuth + Google
|--------------------------------------------------------------------------
 */

$config['oauth2_client_id'] = OAUTH2_CLIENT_ID;
$config['oauth2_client_secret'] = OAUTH2_CLIENT_SECRET;
$config['oauth2_redirect_uri'] = 'http://www.giftflow.org/account/link/google/2';


/*
|--------------------------------------------------------------------------
|Facebook
|--------------------------------------------------------------------------
 */

$config['appId'] = '';
$config['secret'] = '';
$config['fileUpload'] = TRUE;


		
/* End of file account.php */
/* Location: ./system/application/modules/account/config/account.php */
