<?php
if (!defined('BASEPATH'))
      exit('No direct script access allowed');
/**
 * 	OpenAuth Library
 *
 *	Handles OpenID and OAuth requests.
 *
 * 	@author Brandon Jackson
 */
class Openauth
{
	/**
	*	CodeIgniter super object
	*	@var object
	*/
	var $CI;
	
	/**
	* 	Current Logged-in User
	*	@var object
	*/
	var $U;
	
	/**
	*	Path to the OpenID tmp files
	*	@var string
	*/
	var $store_path;
	
	/**
	*	URL for OpenID providers to redirect back to
	*	@var string
	*/
	var $request_to;
	
	/**
	*	OpenID discovery URL
	*	@var string
	*/
	var $endpoint;
	
	/**
	*	Query string to attach to OpenID request
	*	Used to store OAuth+OpenID parameters in google hybrid protocol
	*	@var string
	*/
	var $openid_request_extension;
	
	/**
	*	OAuthSignatureMethod_HMAC_SHA1
	*	@var object
	*/
	var $oauth_hmac;
	
	/**
	*	OAuthConsumer Object
	*	@var object
	*/
	var $oauth_consumer;
	
	/**
	*	Consumer key, ie giftflow.org
	*	@var string
	*/
	var $oauth_consumer_key;
	
	/**
	*	Consumer secret key
	*	@var string
	*/
	var $oauth_consumer_secret;
	
	/**
	*	OAuthToken Object
	*	@var object
	*/
	var $oauth_request_token;
	
	/**
	*	OAuthToken Object
	*	@var object
	*/
	var $oauth_access_token;
	
	/**
	*	@var string
	*/
	var $oauth_access_token_key;
	
	/**
	*	@var string
	*/
	var $oauth_access_token_secret;
	
	/**
	*	OpenID / OAuth Provider name
	*	ie google
	*	@var string
	*/
	var $service;
      
      
	function __construct()
	{
		$this->CI =& get_instance();
		
		// Load OpenID classes
		set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());
		require_once "Auth/OpenID/Consumer.php";
		require_once "Auth/OpenID/FileStore.php";
		require_once "Auth/OpenID/SReg.php";
		require_once "Auth/OpenID/PAPE.php";
		require_once "Auth/OpenID/AX.php";
		
		// Load account config file
		$this->CI->config->load('account');
		
		// Load auth library
		$this->CI->load->library('auth');
		
		// Define some basic variables
		$this->store_path = $this->CI->config->item('openid_file_store_path');
		$this->request_to = $this->CI->config->item('openid_request_to');
		$this->site_root  = $this->CI->config->item('openid_site_root');
		
		// Start a session (but also suppress errors in case it has already started)
		@session_start();
		
		// Get the currrently logged in user (if he exists...)
		$this->_get_current_user();
		log_message('debug', "OpenAuth Class Initialized");
	}
      
      
      function start($endpoint = NULL)
      {
            if (!empty($endpoint))
            {
                  $this->endpoint = $endpoint;
            }
            if (empty($this->endpoint))
            {
                  return FALSE;
            }
            
            // Get OpenID store object
            $store = new Auth_OpenID_FileStore($this->store_path);
            
            // Get OpenID consumer object
            $consumer = new Auth_OpenID_Consumer($store);
            
            // Begin OpenID authentication process
            
            $auth_request = $consumer->begin($this->endpoint);
            
            // If there is an error, where should we send them?
            if (!$auth_request)
            {
                  // If logged in, to the Linked accounts screen
                  if ( !empty( $this->U) )
                  {
                        $this->CI->session->set_flashdata('linked_error', sprintf($this->CI->lang->line('sign_in_invalid_discovery'), $this->CI->lang->line('sign_in_google')));
                        redirect('account/links');
                  }
                  // Else, to the login screen
                  else
                  {
                        $this->CI->session->set_flashdata('sign_in_error', sprintf($this->CI->lang->line('sign_in_invalid_discovery'), $this->CI->lang->line('sign_in_google')));
                        redirect('login');
                  }
            }
            
            // There isn't an error! Onwards!
            
            // Create ax request (Attribute Exchange)
            if ($this->service == "google")
            {
                  $ax_request = new Auth_OpenID_AX_FetchRequest;
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/friendly', 1, TRUE, 'username'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/email', 1, TRUE, 'email'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson', 1, TRUE, 'fullname'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/birthDate', 1, TRUE, 'dateofbirth'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/person/gender', 1, TRUE, 'gender'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/postalCode/home', 1, TRUE, 'postalcode'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/country/home', 1, TRUE, 'country'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/pref/language', 1, TRUE, 'language'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/pref/timezone', 1, TRUE, 'timezone'));
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/first', 1, TRUE, 'firstname')); // google only
                  $ax_request->add(Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/last', 1, TRUE, 'lastname')); // google only
                  $auth_request->addExtension($ax_request);
            }
            
            // Built redirect URL
            $redirect_url = $auth_request->redirectURL($this->site_root, $this->request_to);
            
            // Attaches extensions to the request parameters, such as Google's OAuth parameters
            if (!empty($this->openid_request_extension))
            {
                  $redirect_url .= $this->openid_request_extension;
            }
            // Page (or possibly popup) redirect
            header("Location: " . $redirect_url);
      }
      
      function verify()
      {
            // Get OpenID store object
            $store = new Auth_OpenID_FileStore($this->store_path);
            
            // Get OpenID consumer object
            $consumer = new Auth_OpenID_Consumer($store);
            
            // Complete authentication process using server response
            $response = $consumer->complete($this->request_to);
            
            // Check the response status
            
            // If success....
            if ($response->status == Auth_OpenID_SUCCESS)
            {
				// Extract claimed identifier
				$openid = $response->getDisplayIdentifier();
				
				// Create OpenID object using result
				$OpenID = new User_openid();
				$OpenID->where('openid', $openid)->get();
				if( ! $OpenID->exists() )
				{
					$OpenID->openid = $openid;
					$OpenID->save();
				}
				
				// Create user object for the user whose ID we've loaded (if he exists...)
				$Linked_user = new User();
				$Linked_user->where_related_user_openid($OpenID)->get();
                  
                  
				// Is this OpenID already linked to a user?
				if ($Linked_user->exists())
				{
					// Is this user already logged in? 
					if (!empty($this->U))
					{
						// Already linked with this account. User both logged in 
						// and the OpenID matches the user's account. Display 
						// already linked to this account message
						if ($Linked_user->id == $this->U->id)
						{
							if( !$this->google_oauth_test() )
							{
								$this->_save_google_access_token();
							}	
								$this->CI->session->set_flashdata('success', sprintf($this->CI->lang->line('linked_linked_with_this_account'), $this->CI->lang->line('sign_in_google')));
						}
                              
						// User logged in but the OpenID belongs to someone else. Throw an error and redirect them back to the login page.
						else
						{
							$this->CI->session->set_flashdata('error', sprintf($this->CI->lang->line('linked_linked_with_another_account'), $this->CI->lang->line('sign_in_google')));
						}
					}
					// User isn't signed in. Begin login routine.
					else
					{
						$this->CI->auth->openid_login($Linked_user);
					}
				}
                  
                  // OpenID isn't linked to GiftFlow. Begin linking routine.
                  else
                  {
                        // Save data gleaned from request to the session
                        $openid_google['openid'] = $openid;
                        if ($ax_args = Auth_OpenID_AX_FetchResponse::fromSuccessResponse($response))
                        {
                              $ax_args = $ax_args->data;
                              if (isset($ax_args['http://axschema.org/namePerson/friendly'][0]))
                              {
                                    $openid_google['username'] = $ax_args['http://axschema.org/namePerson/friendly'][0];
                              }
                              if (isset($ax_args['http://axschema.org/contact/email'][0])){
                                    $openid_google['email'] = $ax_args['http://axschema.org/contact/email'][0];
                              }
                              if (isset($ax_args['http://axschema.org/namePerson'][0]))
                              {
                                    $openid_google['fullname'] = $ax_args['http://axschema.org/namePerson'][0];
                              }
                              if (isset($ax_args['http://axschema.org/birthDate'][0]))
                              {
                                    $openid_google['dateofbirth'] = $ax_args['http://axschema.org/birthDate'][0];
                              }
                              if (isset($ax_args['http://axschema.org/person/gender'][0]))
                              {
                                    $openid_google['gender'] = $ax_args['http://axschema.org/person/gender'][0];
                              }
                              if (isset($ax_args['http://axschema.org/contact/postalCode/home'][0]))
                              {
                                    $openid_google['postalcode'] = $ax_args['http://axschema.org/contact/postalCode/home'][0];
                              }
                              if (isset($ax_args['http://axschema.org/contact/country/home'][0]))
                              {
                                    $openid_google['country'] = $ax_args['http://axschema.org/contact/country/home'][0];
                              }
                              if (isset($ax_args['http://axschema.org/pref/language'][0]))
                              {
                                    $openid_google['language'] = $ax_args['http://axschema.org/pref/language'][0];
                              }
                              if (isset($ax_args['http://axschema.org/pref/timezone'][0]))
                              {
                                    $openid_google['timezone'] = $ax_args['http://axschema.org/pref/timezone'][0];
                              }
                              if (isset($ax_args['http://axschema.org/namePerson/first'][0]))
                              {
                                    $openid_google['firstname'] = $ax_args['http://axschema.org/namePerson/first'][0]; // google only
                              }
                              if (isset($ax_args['http://axschema.org/namePerson/last'][0]))
                              {
                                    $openid_google['lastname'] = ' ' . $ax_args['http://axschema.org/namePerson/last'][0]; // google only
                              }
                        }
                        $this->CI->session->set_userdata('openid_google', $openid_google);
                        
                        // Is user logged in?
                        if (!empty($this->U))
                        {
                              $this->_save_google_access_token();

                        	// Save the relationship between this OpenID and the logged in user
                        	if( $this->U->save($OpenID) )
                        	{
						$this->CI->session->set_flashdata('success', 'Your Google account has been linked successfully!');
						redirect('account/links');
                        	}
                        }
                       	
                       	// Not logged in, begin registration + login routine
                        else
                        {
                        	// @todo: handle registration
                        }
                  }
            }
            
            // Request cancelled
            elseif ($response->status == Auth_OpenID_CANCEL)
            {
                  if (!empty($this->U))
                  {
                        $this->CI->session->set_flashdata('linked_error', sprintf($this->CI->lang->line('sign_in_cancelled'), $this->CI->lang->line('sign_in_google')));
                  }
                  else
                  {
                        $this->CI->session->set_flashdata('sign_in_error', sprintf($this->CI->lang->line('sign_in_cancelled'), $this->CI->lang->line('sign_in_google')));
                  }
            }
            // Request failed. Prep error mesages.
            else
            {
                  if (!empty($this->U))
                  {
                        $this->CI->session->set_flashdata('linked_error', sprintf($this->CI->lang->line('sign_in_failed'), $this->CI->lang->line('sign_in_google')));
                  }
                  else
                  {
                        $this->CI->session->set_flashdata('sign_in_error', sprintf($this->CI->lang->line('sign_in_failed'), $this->CI->lang->line('sign_in_google')));
                  }
            }
            
            // Redirect to the final destination
            if (!empty($this->U))
            {
                  redirect('account/links');
            }
            else
            {
                  redirect('member/login');
            }
      }
      
	/**
	*	Loads an XML object containing a user's contacts from the Google 
	*	Contacts API
	*	@param array $params	Parameters
	*	@return string $xml		XML object
	*/
	function google_contacts($params = array())
	{
		$base_uri  = 'http://www.google.com/m8/feeds/contacts/default/full';
		$params['max-results'] = 10000;
		$raw = $this->google_request($base_uri, $params, FALSE);
		$formatted = str_replace("gd:email", "email", $raw);
		$xml = new SimpleXMLElement($formatted);
		return $xml;
	}
      
	/**
	*	Gets a list of user's email contacts
	*	@return array $list
	*/
	function google_contacts_emails()
	{
		Console::logSpeed("Openauth::google_contacts_emails()");
      	$XML = $this->google_contacts();
      	$list = array();
      	
      	foreach( $XML->entry as $key=>$val )
		{
			foreach( $val->email as $email)
			{
				$list[] = "".$email['address']."";
			}
		}
		Console::logSpeed("Openauth::google_contacts_emails(): done.");
		return $list;

      }
      
      function google_oauth_test()
      {
            $base_uri  = 'http://www.google.com/m8/feeds/contacts/default/full';
            $params['max-results'] = 1;
            $response = $this->google_request($base_uri, $params);
            if( $response != FALSE )
            {
            	return TRUE;
            }
            else
            {
            	return FALSE;
            }
      }
      
      /**
      *	Send data requests to Google
      *
      *	@param string $base_uri		URL of resource to request
      *	@param array $params		Parameters to send to API
      *	@param boolean $return_xml	Return results in XML format?
      *	@return SimpleXML
      */
      function google_request( $base_uri, $params = NULL, $return_xml = TRUE )
      {
      	// Get everything ready
            $this->google_oauth_prep();

            if(!empty($params))
            {
           		$URI = $base_uri . "?" . http_build_query($params);
           	}
           	else
           	{
           		$URI = $base_uri;
           	}
            
            $req = OAuthRequest::from_consumer_and_token($this->oauth_consumer, $this->oauth_access_token, 'GET', $base_uri, $params);
            
            $req->sign_request($this->oauth_hmac, $this->oauth_consumer, $this->oauth_access_token);
            
            $auth_header = $req->to_header();
            
            $response = $this->_send_signed_request("GET", $URI, $auth_header, NULL, FALSE);
		
		if( $return_xml )
		{
			// Try to parse response into XML. If errors will return false
			$xml = simplexml_load_string( $response );
			
			if( !empty( $xml->BODY->H2 ) && stripos($xml->BODY->H2, "Error") !== FALSE)
			{
				$this->oauth_error = $xml->BODY->H2;
				return FALSE;
			}
			else
			{
				return $xml;
			}
		}
		else
		{
			return $response;
		}
      }
      
      /**
       *	Preps for a Google Data API request
       */
      function google_oauth_prep()
      {
            // Sets Google as the service
            $this->service = "google";
            
            // Define endpoint
            $this->endpoint = $this->CI->config->item('openid_google_discovery_endpoint');
            
            // Defines API keys
            $this->oauth_consumer_key    = $this->CI->config->item('oauth_google_consumer_key');
            $this->oauth_consumer_secret = $this->CI->config->item('oauth_google_consumer_secret');
            
            // Defines custom string to add to URL request
            $this->openid_request_extension = "&openid.ns.ext2=http://specs.openid.net/extensions/oauth/1.0&openid.ext2.consumer=" . $this->oauth_consumer_key . "&openid.ext2.scope=" . urlencode('http://www.google.com/m8/feeds/contacts/default/full');
            
            $this->_load_oauth();
            
            return $this;
      }
      
      /**
      *	Load OAuth library, create required objects
      */
      protected function _load_oauth()
      {
            // Begin OAuth routine
            include_once('./application/libraries/Auth/OAuth.php');

            $this->oauth_hmac     = new OAuthSignatureMethod_HMAC_SHA1();
            $this->oauth_consumer = new OAuthConsumer($this->oauth_consumer_key, $this->oauth_consumer_secret);

            // Set the access token
            $this->_set_access_token();
      }
      
      /**
       *	Exchange Google Data request token for an Access Token
       */
      protected function _save_google_access_token()
      {
            // Create request token from GET data
            $request_token = new OAuthToken($_GET['openid_ext2_request_token'], NULL);
            
            // URL that will exchange the request token for an access token
            $token_endpoint = 'https://www.google.com/accounts/OAuthGetAccessToken';
            
            // Create new request object
            $request = OAuthRequest::from_consumer_and_token($this->oauth_consumer, $request_token, 'GET', $token_endpoint);
            
            // Generate signed authorization headers
            $request->sign_request($this->oauth_hmac, $this->oauth_consumer, $request_token);
            
            // Execute the request
            $response = $this->_send_signed_request($request->get_normalized_http_method(), $token_endpoint, $request->to_header(), NULL, FALSE);

            // Parse out oauth_token (access token) and oauth_token_secret
            preg_match('/oauth_token=(.*)&oauth_token_secret=(.*)/', $response, $matches);
            
            // Keys
            $this->oauth_access_token_key    = urldecode($matches[1]);
            $this->oauth_access_token_secret = urldecode($matches[2]);
            
            // Save keys to database
            $this->U->google_token        = $this->oauth_access_token_key;
            $this->U->google_token_secret = $this->oauth_access_token_secret;
            $this->U->save();
            
            // Token object
            $this->oauth_access_token = new OAuthToken($this->oauth_access_token_key, $this->oauth_access_token_secret);
            
            // Return OAuthToken object
            return $this->oauth_access_token;
      }
      
      /**
       *	Loads the current user into $this->U
       *	@return boolean
       */
      protected function _get_current_user()
      {
      	// If user is logged in, set the current user.
            if ($this->CI->session->userdata('user_id'))
            {
            	// First looks to the CI data array
                  if (!empty($this->CI->data['U']))
                  {
                        $this->U = $this->CI->data['U'];
                  }
                  // Then manually instantiates object using user_id from session class
                  else
                  {
                        $this->U = new User($this->CI->session->userdata['user_id']);
                  }
                  
                  // Success
                  return TRUE;
            }
            // User isn't logged in
            else
            {
                  return FALSE;
            }
      }
      
      protected function _set_access_token()
      {
      	$this->_get_current_user();
      	
            if (!empty($this->U->google_token))
            {
                  $this->oauth_access_token_key = $this->U->google_token;
                  
                  if (!empty($this->U->google_token_secret))
                  {
                        $this->oauth_access_token_secret = $this->U->google_token_secret;
                        
                        // Create token object
                        $this->oauth_access_token = new OAuthToken($this->oauth_access_token_key, $this->oauth_access_token_secret);
                        return TRUE;
                  }
            }
            return FALSE;
      }
      
      /**
       *	Send signed HTTP request
       *
       *	@access protected
       *	@param string $http_method		HTTP method to use (ie GET, POST, etc)
       *	@param string $url				URL of request
       *	@param string $auth_header		Authorization header
       *	@param array $postData			Data to include in the body of a POST request
       *	@param boolean $returnResponseHeaders	If true, returns response headers as string
       *	@return string
       */
      protected function _send_signed_request($http_method, $url, $auth_header = null, $postData = null, $returnResponseHeaders = TRUE)
      {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_FAILONERROR, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            
            if ($returnResponseHeaders)
            {
                  curl_setopt($curl, CURLOPT_HEADER, TRUE);
            }
            
            switch ($http_method)
            {
                  case 'GET':
                        if ($auth_header)
                        {
                              curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                    $auth_header
                              ));
                        }
                        break;
                  case 'POST':
                        $headers = array(
                              'Content-Type: application/atom+xml',
                              $auth_header
                        );
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_POST, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                        break;
                  case 'PUT':
                        $headers = array(
                              'Content-Type: application/atom+xml',
                              $auth_header
                        );
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $http_method);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                        break;
                  case 'DELETE':
                        $headers = array(
                              $auth_header
                        );
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $http_method);
                        break;
            }
            $response = curl_exec($curl);
            if (!$response)
            {
                  $response = curl_error($curl);
            }
            curl_close($curl);
            return $response;
      }
}
?>
