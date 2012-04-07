<?php
/** 
 * Contact controller
 * Connects to Google Analytics.
 */ 
class Contact extends CI_Controller 
{    
    function Contact() 
    {
        parent::__construct();
		$this->U = new User($this->session->userdata('user_id'));
		$this->data = $this->util->parse_globals();
		$this->data['U'] = $this->U;
		$this->util->config();
    }
    
    function index()
    {
       $user_id = $this->session->userdata('user_id');
        
        // include the OAuth library.        
        include_once APPPATH . "libraries/oauth/OAuthStore.php";
        include_once APPPATH . "libraries/oauth/OAuthServer.php";
        include_once APPPATH . "libraries/oauth/OAuthRequester.php";
        
        // Initiate the store.
        $options = array('server' => $this->db->hostname, 'username' => $this->db->username, 'password' => $this->db->password, 'database' => $this->db->database);
        $store   = OAuthStore::instance('MySQL', $options);
        
        // Google
        $consumer_key = 'giftflow.org'; // fill with your public key 
        $consumer_secret = 'sFc4iZ9NjbbLqAM+44mtnMsw'; // fill with your secret key
        $server_uri = "http://google.com/"; // fill with the url for the oauth service
        $request_uri = "https://www.google.com/accounts/OAuthGetRequestToken";
        $authorize_uri = "https://www.google.com/accounts/OAuthAuthorizeToken";
        $access_uri = "https://www.google.com/accounts/OAuthGetAccessToken";
            
        // The server description
        $server_data = array(
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret,
            'server_uri' => $server_uri,
            'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),
            'request_token_uri' => $request_uri,
            'authorize_uri' => $authorize_uri,
            'access_token_uri' => $access_uri
        );
        
        // Check if this consumer is on this server.
        $servers = $store->listServers($server_uri, $user_id);
        
        // They're not...
        if(empty($servers))
        {
            // Save the server in the the OAuthStore
            //$consumer_key = $store->updateServer($server_data, $user_id);
            
            echo "You're not authenticated yet. Want to be?";
        }
            
        // They are! Let's get Analytical.
        else
        {
            // But first, we check if they have an access token.
            $token = $store->getSecretsForSignature($server_uri, $user_id);
            if( ! $token)
            {
                echo 'no';
            }
            else
            {
                // Check to see if user has been authorized.
                // The request uri being called.
                
                // Parameters, appended to the request depending on the request method.
                // Will become the POST body or the GET query string.
                $params = array();
                
                // Obtain a request object for the request we want to make
                $req = new OAuthRequester($server_uri, 'GET', $params);
                
                // Sign the request, perform a curl request and return the results, throws OAuthException exception on an error
                $result = $req->doRequest($user_id);
                echo '<pre>' . print_r($result, TRUE) . '</pre>';
                
                // $result is an array of the form: array ('code'=>int, 'headers'=>array(), 'body'=>string)
            }
        }
    }
}

/* End of file Contact.php */
/* Location: ./system/application/controllers/analytics.php */  