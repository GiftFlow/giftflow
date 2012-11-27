<?php
/**
*	Email Alerts Library
*/
class Alert {

	/**
	*	CodeIgniter super-object
	*
	*	@var object
	*/
	protected $CI;

	/**
	*	Array of tags and variables which templates will be parsed for
	*
	*	@var array
	*/
	public $parseables;
	
	/**
	*	Name of the template to load. 
	*	This corresponds to the database field "name" in the terms table.
	*
	*	@var string
	*/
	public $template_name;
	
	/**
	*	Email address to send the alert to
	*	@var string
	*/
	public $to;
	
	/**
	*	Message of the alert email
	*
	*	@var string
	*/
	public $message;
	
	/** 
	* Note added by user
	*
	* @var string
	*/
	
	public $note;
	
	/**
	*	Subject of the alert email
	*
	*	@var string
	*/
	public $subject;
	
	/**
	*	Prefix for the alert subject
	*	For example, by default an email with the subject "nofication" will
	*	actually be sent as "[GiftFlow] notification"
	*
	*	@var string
	*/
	public $subject_prefix = "[GiftFlow] ";
	
	/**
	*	Left delimiter used for parsing templates
	*	For example, if the left delimiter is {{, the parser will look for
	*	tags which begin with "{{", such as "{{user_name}}"
	*
	*	@var string
	*/
	public $left_delimiter = "{{";
	
	/**
	*	Right delimiter used for parsing templates
	*	For example, if the right delimiter is }}, the parser will look for 
	*	tags which end with "}}", such as "{{user_name}}"
	*
	*	@var string
	*/
	public $right_delimiter = "}}";


	function __construct()
	{
		// Load CI super object
		$this->CI =& get_instance();
	}
	
	/**
	*	Prepares and sends email alerts.
	*
	*	Before this function is used, $this->template_name and 
	*	$this->parseables should be set. This function will load the 
	*	template, parse it, and send the email.
	*/
	function send()
	{
		// Parse the template
		$this->generate();
		
		$config = array();
		
		// Set the options for the outgoing email
		$config["subject"] = $this->subject;
		$config['message'] = $this->message;
		
		$header = $this->CI->load->view('email/header',FALSE,TRUE);
		$footer = $this->CI->load->view('email/footer',FALSE,TRUE);
		$config['message_html'] = $header.$this->message.$footer;
		
		$config["to"] = $this->to;
		$config["reply_to_email"] = '';
		$config["reply_to_name"] = '';
		$config["cc"] = '';
		$config["bcc"] = '';
		
		// Send the alert email
		$this->email( $config );
	}

	/** 
	*	Generate message and subject by parsing the alert template
	*	and the data found in $this->parseables
	*/
	function generate()
	{

		//this is used for sending emails where entire body is prepared beforehand like remind
		if(isset($this->message))
		{
			$this->subject = $this->parseables['subject'];
			return;
		}

		$this->CI->config->load("email_templates");
		$templates = $this->CI->config->item('email_templates');
		
		// If term not found, show error
		if (!array_key_exists($this->template_name, $templates) ||
				!array_key_exists("en", $templates[$this->template_name]))
		{
			show_error("Alert::generate(): Email Template `".$this->template_name."` not found.");
		}
		
		$T = $templates[$this->template_name]["en"];
		
		// Parse template
		if(!empty($this->parseables) && is_array($this->parseables))
		{
			// Loop through the parseables array
			foreach ($this->parseables as $key => $val)
			{
				// Generate list of tags to look for using delimiters
				$find_arr[]    = $this->left_delimiter . $key . $this->right_delimiter;
				
				// Generate list of values to replace tags with
				$replace_arr[] = htmlspecialchars($val,ENT_COMPAT,"UTF-8");
			}
			// Replace tags with values, set message and subject fields
			$this->message = str_replace($find_arr, $replace_arr, $T);
		}
		
		// If nothing to parse, set message to be body field from database
		else
		{
			$this->message = $T;
		}
	}
	
	/**
	*	Low-level function which actually sends the message
	*	@todo create new library to handle this?
	*/
	function email( $config )
	{
		$this->CI->load->library('postmark');
		$Mail = $this->CI->postmark;
		
		// Set email recipient
		$Mail->to( $config['to'] );
		
		// Set reply-to email if provided
		if ($config['reply_to_email'] != "")
		{
			$Mail->reply_to($config['reply_to_email'], $config['reply_to_name']);
		}
		
		// Add CC and BCC if needed
		if ( !empty($config['cc']) )
		{
			$Mail->cc( $config['cc'] );
		}
		if ($config['bcc'] != "")
		{
			$Mail->bcc( $config['bcc'] );
		}
		
		// Set the subject using subject prefix
		$Mail->subject( $this->subject_prefix . $config['subject']);
		
		// Set the message "Thanks" added for plain, b/c its not included in the Terms
		$Mail->message_plain( $config['message']." Thanks, The GiftFlow Team");
		
		$Mail->message_html($config['message_html']);
		
		$info = $Mail->send();
		
		$this->CI->load->library('datamapper');
		$E = new Event();
		
		$E->type = 'email';
		$E->data = json_encode($info);
		$E->save();
		
		if($info['return']->Message != 'OK')
		{
			return FALSE;
		}
	}
}
?>
