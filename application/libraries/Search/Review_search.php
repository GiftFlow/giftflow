<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Review model for select operations
*	
*	@author Hans Schoenburg
*	@package Search
*/

class Review_search extends Search
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	var $reviewer_id;
	
	var $reviewed_id;
	
	var $user_id;
	
	var $transaction_id;
	
	/**
	* Return array
	* @var array
	*/
	var $result;
	
	
	/**
	*	Constructor
	*/
	public function __construct()
	{
		parent::__construct();
		
		$this->CI =& get_instance();
	}
	
	//Use cases
	//give user id - get all their transactions - then their reviews
	

	
	/**
	*	Retrieve single Transaction object
	*/
	
	public function find($options = array())
	{
		$this->CI->load->library('Search/Transaction_search');
		$default_options = array(
			"transaction_id"=>'',
			"user_id" => '',
			"reviewed_id"=>'',
			"reviewer_id"=>'',
			"transaction_status"=>"completed",
			"rating" => '',
			"include_transactions" => FALSE
			);
		
		$options = (object) array_merge ($default_options, $options);
		$this->basic_query();
		
	//Add where clauses
		if(!empty($options->reviewer_id))
		{
			$this->CI->db->where('R.reviewer_id', $options->reviewer_id);
		}
		if(!empty($options->reviewed_id))
		{
			$this->CI->db->where('R.reviewed_id', $options->reviewed_id);
		}
		if(!empty($options->transaction_id))
		{
			$this->CI->db->where('R.transaction_id', $options->transaction_id);
		}
		if(!empty($options->rating))
		{
			$this->CI->db->where('R.rating', $options->rating);
		}
		if(!empty($options->user_id))
		{
			$this->CI->db->where('R.reviewer_id', $options->user_id);
			$this->CI->db->or_where('R.reviewed_id', $options->user_id);
		}
		$query = $this->CI->db->get();
		$this->result['reviews'] = $query->result();
		
		if($options->include_transactions)
		{
			$transaction_ids = array_map( function($trans){ return $trans->transaction_id; }, $this->result['reviews']);
			
			$T = new Transaction_search;
			
			$this->result['transactions'] = $T->find(array (
				"transaction_id" => $transaction_ids,
				"transaction_status" => "completed"
			));
		}
		
		return $this->result;
		
	}
	
	protected function basic_query()
	{
		$this->CI->db->select(
			"R.reviewer_id AS reviewer_id,
			R.reviewed_id AS reviewed_id,
			R.transaction_id AS transaction_id,
			R.rating AS rating,
      UR.screen_name AS user_reviewer_name,
			R.body AS body")
			->from("reviews R")
      ->join("users AS UR", "R.reviewer_id = UR.id", 'left')
			->order_by("R.transaction_id");
	}
}
