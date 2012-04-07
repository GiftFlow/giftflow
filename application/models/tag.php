<?php
class Tag extends DataMapperExtension {
	
	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;


	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array();
	
	var $has_many = array(
		"good"
	);
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'name' => array(
			'rules' => array(
				'required'
			),
			'label' => 'Tag'
		),
		'count' => array(
			'rules'=>array(
				'set_count',
				'required'
			),
			'label' => 'Tag Count'
		)
	);

	function __construct($id = NULL)
	{
		parent::__construct($id);
		$this->CI =& get_instance();
	}
	
	/**
	*	Regenerates the `count` field for every tag in the database
	*/
	function count_all()
	{
		$Tags = $this->db->select('id')
			->from('tags')
			->get()
			->result();
			
		
		// Start SQL transaction
		$this->db->trans_start();
		
		// Loop over each tag
		foreach($Tags as $key=>$Tag)
		{
			// Perform UPDATE
			$id = $Tag->id;
			$this->db->query("UPDATE tags SET count = (SELECT COUNT(*) FROM goods_tags AS GT JOIN goods AS G ON GT.good_id = G.id WHERE G.status='active' AND GT.tag_id=?) WHERE id=?",array($id,$id));
		}
		
		// Finish SQL transaction
		$this->db->trans_complete();
		
		return TRUE;
	}
	
	/**
	*	Deletes orphaned tags (tags that have no relationships with goods)
	*/
	function delete_orphans()
	{
		$orphans = $this->CI->db->query("SELECT T.id FROM tags AS T LEFT JOIN goods_tags AS GT ON T.id=GT.tag_id WHERE GT.id IS NULL")->result_array();
		
		$orphan_ids = array_map(function($tag){ return $tag['id']; },$orphans);
		
		if(count($orphan_ids)>0)
		{
			$this->CI->db->where_in("id",$orphan_ids)
				->from('tags')
				->delete();
		}
	}
	
	function _set_count($field)
	{
		if(!empty($this->id))
		{
			$this->CI->db->select("COUNT(*) AS count")
				->from('goods_tags AS GT ')
				->join('goods AS G ','G.id=GT.good_id')
				->where('G.status','active')
				->where('GT.tag_id',$this->id);
			$this->{$field} = $this->CI->db->count_all_results() + 1;
		}
		else
		{
			$this->{$field} = 1;
		}
	}
}
?>
