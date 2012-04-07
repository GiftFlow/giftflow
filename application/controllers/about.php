<?php

class About extends CI_Controller {

	var $data;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->data['menu'] = $this->load->view('about/includes/menu',$this->data, TRUE);
	}

	public function index()
	{
		$this->data['title'] = "About";
		$this->load->view('header', $this->data);
		$this->load->view('about/faq', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	public function tour()
	{
		$this->data['title'] = "The Tour";
		$this->load->view('header', $this->data);
		$this->load->view('about/tour', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	public function future()
	{
		$this->data['title'] = "Future";
		$this->load->view('header', $this->data);
		$this->load->view('about/future', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	public function faq()
	{
		$this->data['title'] = "FAQ";
		$this->load->view('header', $this->data);
		$this->load->view('about/faq', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	public function story()
	{
		$this->data['title'] = "Our Story";
		$this->load->view('header', $this->data);
		$this->load->view('about/story', $this->data);
		$this->load->view('footer', $this->data);
	}
	public function press()
	{
		$this->data['title'] = "In The Press";
		$this->load->view('header', $this->data);
		$this->load->view('about/press', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	public function donate()
	{
		$this->data['title'] = "Donate";
		$this->load->view('header', $this->data);
		$this->load->view('about/donate', $this->data);
		$this->load->view('footer', $this->data);
	}

	public function transparency()
	{
		$this->data['title'] = "Transparency";
		$this->load->view('header', $this->data);
		$this->load->view('about/transparency', $this->data);
		$this->load->view('footer', $this->data);
	}

	public function thankyou()
	{
		$this->data['title'] = "Thank you";
		$this->load->view('header', $this->data);
		$this->load->view('about/thankyou', $this->data);
		$this->load->view('footer', $this->data);
	}
	public function contact_giftflow()
	{
		if(!empty($_POST)) 
		{		
			$this->load->library('datamapper');
			$hook_data = array(
				'name' => $_POST['name'],
				'email' => $_POST['email'],
				'message' => $_POST['message']
				);
			$this->hooks->call('contact_giftflow', $hook_data);
			$this->session->set_flashdata('success','Message submitted. We will get back to you as soon as possible.');
			redirect('');
		}
		$this->contact_form();
	}
	public function contact_form()
	{
			$this->data['title'] = "Contact Us";
			$this->load->view('header', $this->data);
			$this->load->view('about/contact', $this->data);
			$this->load->view('footer', $this->data);
	}
}
