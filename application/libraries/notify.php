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
 *
 *	@param data is always an object
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
	function alert_user_registration_manual($data)
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
	 * @param object $watch
	 * @param oobjectt $good 
	 */
	function alert_user_watch_match($watch, $good) {
		
		log_message('debug', "Sending watch notification email to user " . $watch->screen_name . " for item " . $good->title);
		
		$A = new Alert();
		
		// Map hook data onto email template parseables array
		$A->parseables = array(
			'subject' => "Someone posted a new gift that might interest you",
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
	*	@param array $data		$this passed from the controller
	*/
	function alert_transaction_new($data)
	{		
		$A = new Alert();

		$A->parseables = array(
			'demander_name' => $data->transaction->demander->screen_name,
			'decider_name' => $data->transaction->decider->screen_name,
			'decider_email' => $data->transaction->decider->email,
			'summary' => strip_tags($data->transaction->language->decider_summary),
			'subject' => strip_tags($data->transaction->language->decider_summary),
			'note' => $data->note,
			'return_url' => $data->return_url
		);
	
		
		// Set template name
		$A->template_name = 'transaction_new';
		
		// Set recipient
		$A->to = $data->transaction->decider->email;

		// send email
		$A->send();
	}
	

	/**
	 * Called when user Accepts a transaction
	 * param object $data
	 */
	function alert_transaction_completed($data)
	{
		$A = new Alert();

		$other_user = ($data->transaction->demander->id == $this->CI->session->userdata['user_id'])? $data->transaction->decider : $data->transaction->demander;

		$summary = ($data->transaction->demander->id == $this->CI->session->userdata['user_id'])? $data->transaction->language->decider_summary: $data->transaction->language->demander_summary;
		
		$A->parseables = array(
			"completer_name" => $this->CI->session->userdata['screen_name'],
			"receiver_name" => $other_user->screen_name,
			"summary" => strip_tags($summary),
			"subject" => $data->transaction->decider->screen_name." has marked your gift as complete",
			'return_url' => $data->return_url
			);
			
		// Set template name
		$A->template_name = 'transaction_completed';
			
		//Set recipient	
		$A->to = $other_user->email;
	
		//Send
		$A->send();
	
	}

	/**
	 * param object $data
	 */
	
	function alert_transaction_message($data)
	{
		$A = new Alert();
		
		// Get the latest message

		$A->parseables = array(
			"message" => $data->message,
			"user_screen_name" => $this->CI->session->userdata('screen_name'),
			"recipient_name" => $data->recipient,
			"subject" => $this->CI->session->userdata('screen_name')." sent you a message",
			"good_title" => $data->transaction->demands[0]->good->title,
			"return_url" => $data->return_url
		);
		
		// Set template name
		$A->template_name = 'transaction_message';
		
		$A->to = $data->recipient_email;

		$A->send();
	}

	/**
	 * param object $data
	 */

	function alert_user_message($data)
	{
		$A = new Alert();

		$A->parseables = array(
			'message' => $data->message,
			'user_screen_name' =>$this->CI->session->userdata('screen_name'),
			'subject' => $data->subject,
			'recipient_name' => $data->recipient,
			'return_url' => $data->return_url
		);

		$A->template_name = 'user_message';
		$A->to = $data->recipient_email;

		$A->send();
	}

	/**
	 * param object $data
	 */
	
	function review_new($data)
	{

		$A = new Alert();
		
		$A->parseables = array(
			"reviewed_screen_name" => $data->reviewed->screen_name,
			"reviewer_screen_name" => $data->reviewer->screen_name,
			"good_title" => $data->transaction->demands[0]->good->title,
			"subject" => $data->reviewer->screen_name." has written you a review.",
			"return_url" => $data->return_url
		);
		
		$A->template_name = "review_new";
			
		$A->to = $data->reviewed->email;
		
		$A->send();
	}

	/** 
	*	Email forgotten password code to user
	*	param object $data
	*
	*/
	function reset_password($data)
	{
		$A = new Alert();
		
		$A->parseables = array(
			'password_reset_link' => site_url('member/reset_password/?code='.$data->forgotten_password_code),
			'subject' => 'Reset your password',
			'screen_name' => $data->screen_name,
			);
		$A->to = $data->email;
		
		$A->template_name = "reset_password";
		
		$A->send();
	
	}
	
	/*
	*	For admin purposes ONLY - sends email from about/contact form to admin@giftflow
	*	param object $data
	*
	*/
	function contact_giftflow($data)
	{
		$A= new Alert();
		
		$A->parseables = array (
			'subject' => 'Message from Outer Space',
			'message' => $data->message,
			'email' => $data->email,
			'name' => $data->name
			);
			
		$A->template_name = 'contact_giftflow';
		
		$A->to = 'hans@giftflow.org';
		$A->send();
	}

	/**
	 * When a user 'thanks' another, this function sends the recipient an email with
	 * the text of the thank and 'approve/decline' buttons
	 * The buttons then call the thank controller which validates/disables the thankyou
	 * param object $data
	 */
	function thankyou($data)
	{
		$A = new Alert();

		$A->parseables = array(
			'subject' => $data->thanker_screen_name.' wants to thank you for '.$data->gift_title,
			'body' => $data->body,
			'gift_title' => $data->gift_title,
			'recipient_screen_name' => $data->recipient_screen_name,
			'thanker_screen_name' => $data->thanker_screen_name,
			'return_url' => $data->return_url
		);

		$A->template_name = 'thankyou';
		$A->to = $data->recipient_email;
		$A->send();
	}


	/*
	 * param object $data
	 */

	function thankyou_updated($data)
	{
		$A = new Alert();

		$A->parseables = array(
			'subject' => $data->recipient_screen_name.' has '.$data->decision.' your Thank.',
			'body' => $data->body,
			'gift_title' => $data->gift_title,
			'screen_name' => $data->thanker_screen_name,
			'recipient_screen_name' => $data->recipient_screen_name,
			'return_url' => site_url('/you/view_thankyou/'.$data->id)
		);
		
		$A->template_name = 'thankyou_updated';
		$A->to = $data->recipient_email;
		$A->send();
	}


	/*
	 * param array $data
	 */ 

	function remind($data)
	{
		$A = new Alert();

		$A->parseables = array(
			'subject' => 'Your unfinished gifts',
			'body' => $data['body'],
			'screen_name' => $data['screen_name'],
			'return_url' => site_url('login')
		);
		$A->message = $data['body'];
		$A->template_name = 'transaction_reminder';
		$A->to = $data['email'];
		$A->send();
	}


	/*
	 * param object $data
	 */

	function send_matches($data) 
	{
		$A = new Alert();

		$A->parseables = array(
			'subject' => 'Matches for your gifts and needs',
			'screen_name' => $data->screen_name,
			'return_url' => site_url('login')
		);
		$A->message = $data->body;
		$A->template_name = 'goods_match';
		$A->to = $data->email;
		$A->send();

	}	

	/*
	 * param array $data
	 */

	function thank_invite($data)
	{
		$A = new Alert();

		$A->parseables = $data;

		$A->template_name = 'thank_invite';
		$A->to = $data['recipient_email'];
			
		$A->send();
	}

}
