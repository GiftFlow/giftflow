<?php 

class Metrics extends CI_Controller {

var $month_name=array( 
		1=> "January",
		2=> "February",
		3=> "March",
		4=> "April",
		5=> "May",
		6=> "June",
		7=> "July",
		8=> "August",
		9=> "September",
		10=> "October",
		11=> "November",
		12=> "December"
		);

	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
	}

	function index()
	{

		$this->data['monthly_users'] = $this->monthly_users();
		
		$this->load->view('header', $this->data);
		$this->load->view('metrics/metrics', $this->data);
		$this->load->view('footer', $this->data);
	}


	function monthly_users()
	{
		$this->data['x']= $this->month_name;
		$results = array();


		for($x=10; $x<14; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				$month = $this->db->select('U.created')
						->from('users AS U')
						->where('U.created >', "20".$x."-".$i."-01 12:00:00")
						->where('U.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();



				$results[$x][$i] = count($month);
			}
		}
			
		return $results;
		//$this->data['monthly_users'] = $results;

	//	$this->load->view('header', $this->data);
	//	$this->load->view('metrics/monthly_users', $this->data);
	//	$this->load->view('footer', $this->data);
	}

	function gifts_needs_monthly()
	{
		$this->data['x']= $this->month_name;
		$gift_results = array();
		$need_results = array();


		for($x=10; $x<14; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				//Gifts
				$gifts = $this->db->select ('G.created')
						->from('goods AS G')
						->where('G.created >', "20".$x."-".$i."-01 12:00:00")
						->where('G.created <', "20".$x."-".$y."-01 12:00:00")
						->where('G.type =','gift')
						->get()
						->result();
				//Needs
				$needs = $this->db->select ('G.created')
						->from('goods AS G')
						->where('G.created >', "20".$x."-".$i."-01 12:00:00")
						->where('G.created <', "20".$x."-".$y."-01 12:00:00")
						->where('G.type =', 'need')
						->get()
						->result();

				
				$gift_results[$x][$i] = count($gifts);
				$need_results[$x][$i] = count($needs);
			}
		}

			$this->data['gifts_monthly'] = $gift_results;
			$this->data['needs_monthly'] = $need_results;

		$this->load->view('header', $this->data);
		$this->load->view('metrics/gifts_needs_monthly', $this->data);
		$this->load->view('footer', $this->data);
	}

	function transactions_monthly()
	{
		$this->data['x']= $this->month_name;

		$new_transactions_results = array();
		$completed_transactions_results = array();
		$active_transactions_results = array();
		$cancelled_transactions_results = array();
		$declined_transactions_results = array();


		for($x=10; $x<14; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				//number of transactions started each month
				$new_transactions = $this->db->select ('T.created')
						->from('transactions as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();
				//completed transactions each month
				$completed_transactions = $this->db->select ('T.created')
						->from('transactions as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->where('T.status =','completed')
						->get()
						->result();
				$active_transactions = $this->db->select ('T.created')
						->from('transactions as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->where('T.status =','active')
						->get()
						->result();
				$cancelled_transactions = $this->db->select ('T.created')
						->from('transactions as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->where('T.status =','cancelled')
						->get()
						->result();
				$declined_transactions = $this->db->select ('T.created')
						->from('transactions as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->where('T.status =','declined')
						->get()
						->result();

			$new_transactions_results [$x][$i]= count($new_transactions);
			$completed_transactions_results [$x][$i]= count($completed_transactions);
			$active_transactions_results [$x][$i]= count($active_transactions);
			$cancelled_transactions_results [$x][$i]= count($cancelled_transactions);
			$declined_transactions_results [$x][$i]= count($declined_transactions);
			
			}
		}

			$this->data['new_transactions_monthly'] = $new_transactions_results;
			$this->data['completed_transactions_monthly'] = $completed_transactions_results;
			$this->data['active_transactions_monthly'] = $active_transactions_results;
			$this->data['cancelled_transactions_monthly'] = $cancelled_transactions_results;
			$this->data['declined_transactions_monthly'] = $declined_transactions_results;

		$this->load->view('header', $this->data);
		$this->load->view('metrics/transactions_monthly', $this->data);
		$this->load->view('footer', $this->data);
	}

	function monthly_reviews()
	{
		$this->data['x']= $this->month_name;
		$results = array();


		for($x=10; $x<13; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				$reviews = $this->db->select('R.created')
						->from('reviews AS R')
						->where('R.created >', "20".$x."-".$i."-01 12:00:00")
						->where('R.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();



				$results[$x][$i] = count($reviews);
			}
		}

		$this->data['monthly_reviews'] = $results;

		$this->load->view('header', $this->data);
		$this->load->view('metrics/monthly_reviews', $this->data);
		$this->load->view('footer', $this->data);
	}

	function monthly_thankyous()
	{
		$this->data['x']= $this->month_name;
		$results = array();


		for($x=10; $x<13; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				$thankyous = $this->db->select('T.created')
						->from('thankyous as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();



				$results[$x][$i] = count($thankyous);
			}
		}

		$this->data['monthly_thankyous'] = $results;

		$this->load->view('header', $this->data);
		$this->load->view('metrics/monthly_thankyous', $this->data);
		$this->load->view('footer', $this->data);
	}

	function monthly_messages()
	{
		$this->data['x']= $this->month_name;
		$results = array();


		for($x=10; $x<13; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				$messages = $this->db->select('M.created')
						->from('messages AS M')
						->where('M.created >', "20".$x."-".$i."-01 12:00:00")
						->where('M.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();



				$results[$x][$i] = count($messages);
			}
		}

		$this->data['monthly_messages'] = $results;

		$this->load->view('header', $this->data);
		$this->load->view('metrics/monthly_messages', $this->data);
		$this->load->view('footer', $this->data);
	}


}
