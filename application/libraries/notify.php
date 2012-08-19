<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	The Notify library handles all notifications and alerts.
 *	
 *	Notifications are very complex and thus this class deals with two
 *	different kinds of notifications:
 *
 *	1. Alerts
 *	These are email alerts which are sent when an event takes place.
 *
 *	2. Notification feeds
 *	This is a feed of all the latest events relevant to a user. This is very
 *	similar to Facebook's notification feed system.
 *
 *	@author Brandon Jackson
 * 	@package Libraries
 */

class Notify
{
	/**
	*	CodeIgniter super-object
	*
	*	@var object
	*/
	protected $CI;
	

	/**
	*	Constructor function
	*/
	public function __construct()
	{
		// Load CI super object
		$this->CI =& get_instance();
		
		// Load relevant libraries
		$this->CI->load->library('util');
		$this->CI->load->library('email');
		$this->CI->load->library('alert');
	}
	
	/**
	*	Sends alert when new users register
	*
	*	Normally, at the beginning of an email alert function we would check 
	*	to see if user has signed up to receive notifications of this type. 
	*	If they have, continue. If not, return false.
	*
	*	In this case, though, notification emails are mandatory.
	*	
	*	@param array $params
	*	@param array $data
	*/
	function alert_user_registration_manual( $params, $data )
	{
		$A = new Alert();
		
      	// Map hook data onto email template parseables array
		$A->parseables = array(
			'user_email' => $data->U->email, 
			'activation_link' => site_url('member/activate/'.$data->U->activation_code),
			'subject' => "Please confirm your account",
		);
		
		// Set template name
		$A->template_name = 'email_confirmation';
		
		// Set recipient
		$A->to = $data->U->email;
      
      	// send email
		$A->send();
	}
	
	/**
	 * Sends message to user if a new posting matches one of their watches
	 * 
	 * @param type $watch
	 * @param type $good 
	 */
	function alert_user_watch_match($watch, $good) {
		
		log_message('debug', "Sending watch notification email to user " . $watch->screen_name . " for item " . $good->title);
		
		$A = new Alert();
		
      	// Map hook data onto email template parseables array
		$A->parseables = array(
			'subject' => "An item you may be interested was posted",
			'link' => site_url('gifts/'.$good->id),
			'title' => $good->title,
			'recipient_name' => $watch->screen_name
		);
		
		// Set template name
		$A->template_name = 'watch_match';
		
		// Set recipient
		$A->to = $watch->email;
      
		// send email
		$A->send();
	}
	
	/**
	*	Sends alert when user requests a gift
	*
	*	$data contains four relevant objects: 
	*
	*	$data->Demands: Arry of Demand objects
	*	$data->Demander: User object of the user who requested the good
	*	$data->Decider: User object of the user who owns the good
	*	$data->Transaction: Transaction object representing the request itself
	*	$data->Good: Good object of the gift requested
	*
	*	@param array $params
	*	@param array $data		$this passed from the controller
	*/
	function alert_transaction_new( $params, $data )
	{		
		$A = new Alert();

		$A->parseables = array(
			'demander_name' => $data->transaction->demander->screen_name,
			'decider_name' => $data->transaction->decider->screen_name,
			'decider_email' => $data->transaction->decider->email,
			'summary' => strip_tags($data->transaction->language->decider_summary),
			'subject' => strip_tags($data->transaction->language->decider_summary),
			'note' => $data->note
		);
	
		
		// Set template name
		$A->template_name = 'transaction_new';
		
		// Set recipient
		$A->to = $data->transaction->decider->email;
       
      	// send email
		$A->send();
	}
	
	function alert_transaction_activated( $params, $data )
	{
		$A = new Alert();
		
		$A->parseables = array(
			"message" => $data->message,
			"demander_name" => $data->transaction->demander->screen_name,
			"decider_name" => $data->transaction->decider->screen_name,
			"demander_summary" => strip_tags($data->transaction->language->demander_summary),
			"subject" => $data->transaction->decider->screen_name." has accepted your request!"
			);
			
		// Set template name
		$A->template_name = 'transaction_activated';
			
		//Set recipient	
		$A->to = $data->transaction->demander->email;
	
		//Send
		$A->send();
	
	}
	
	function alert_transaction_message( $params, $data )
	{
		$A = new Alert();
		
		// Get the latest message
		$M = $data->conversation->get_latest_message();

		$A->parseables = array(
			"message" => $M->body,
			"user_screen_name" => $this->CI->session->userdata('screen_name'),
			"subject" => $this->CI->session->userdata('screen_name')." sent you a message",
			"good_title" => $data->transaction->demands[0]->good->title
		);
		
		// Set template name
		$A->template_name = 'transaction_message';
		
		// Set recipient
		foreach($data->conversation->users as $user)
		{
			if($user->id != $this->CI->session->userdata('user_id'))
			{
				$A->to = $user->email;
				$A->parseables['recipient_name'] = $user->screen_name;
			}
		}

		$A->send();
	}
	
	function review_new($params, $data)
	{

		$A = new Alert();
		
		$A->parseables = array(
			"reviewed_screen_name" => $data->reviewed->screen_name,
			"reviewer_screen_name" => $data->reviewer->screen_name,
			"good_title" => $data->transaction->demands[0]->good->title,
			"subject" => $data->reviewer->screen_name." has written you a review."
		);
		
		$A->template_name = "review_new";
			
		$A->to = $data->reviewed->email;
		
		$A->send();
	}
	
	/**
	*	Mark notifications as read
	*	Note: handwritten SQL query used because the active record library
	*	appears not to support the usage of JOIN clauses in UPDATE queries
	*
	*	$data object contains two properties: user_id and transaction_id.
	*/
	function transaction_viewed($params, $data)
	{
		Console::logSpeed("Notify::transaction_viewed()");
		$this->CI->db->query("UPDATE `notifications` AS N JOIN events AS E ON N.event_id=E.id SET `N`.`enabled` = 0 WHERE `E`.`transaction_id` = ? AND `N`.`user_id` = ?", array($data->transaction_id, $data->user_id));		
	}
	
	/** 
	*	Email forgotten password code to user
	*
	*/
	function reset_password($params, $data)
	{
		$A = new Alert();
		
		$A->parseables = array(
			'password_reset_link' => site_url('member/reset_password/'.$data['forgotten_password_code']),
			'subject' => 'Reset your password',
			'screen_name' => $data['screen_name'],
			);
		$A->to = $data['email'];
		
		$A->template_name = "reset_password";
		
		$A->send();
	
	}
	
	/**
	* For admin purposes ONLY - sends email to admin with information about a given error
	* DOES NOT WORK 
	*/
	function report_error($params, $data)
	{
		$A = new Alert();
		$A->parseables = array (
			"subject" => $data['heading'],
			"message" => $data['message'],
			"page"=> $data['page']
		);
		
		$A->template_name = "report_error";
		
		$A->to = "admin@giftflow.org";
		
		$A->send();
	}
	
	/**
	*	For admin purposes ONLY - sends email from about/contact form to admin@giftflow
	*
	*/
	function contact_giftflow($params, $data)
	{
		$A= new Alert();
		
		$A->parseables = array (
			'subject' => 'Message from Outer Space',
			'message' => $data['message'],
			'email' => $data['email'],
			'name' => $data['name']
			);
			
		$A->template_name = 'contact_giftflow';
		
		$A->to = 'hans@giftflow.org';
		$A->send();
	
	}

	/**
	 * When a user 'thanks' another, this function sends the recipient an email with
	 * the text of the thank and 'approve/decline' buttons
	 * The buttons then call the thank controller which validates/disables the thankyou
	 */
	function thankyou($params, $data)
	{
		$A = new Alert();

		$A->parseables = array(
			'subject' => 'Someone wants to thank you',
			'message' => $data->transaction->reviews[0]->body,
			'rating' => $data->transaction->reviews[0]->rating,
			'reviewed_screen_name' => $data->reviewed->screen_name,
			'reviewer_screen_name' => $data->reviewer->screen_name,
			'gift' => $data->transaction->demands[0]->good->title
		);

		$A->template_name = 'thankyou';
		$A->to = 'info@giftflow.org';
		$A->send();
	}
	
}
