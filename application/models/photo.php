<?php
class Photo extends DataMapperExtension {


	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;

	var $table = "photos";
	var $created_field = 'created';

  
	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array(
		"user",
		"good",
		"default_user"=>array(	
			"class"=>"user", 
			"other_field"=>"default_photo"
		),
		"default_good"=>array(
			"class"=>"good",
			"other_field"=>"default_photo"
		)
	);
	
	var $has_many = array();
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'url' => array(
			'rules' => array(
				'required',
			//	'local_url',
				'is_photo'
			), 
			'label' => 'URL'
		)
	);

	function __construct( $id = NULL )
	{
		parent::__construct( $id );
		$this->CI =& get_instance();
	}
	
	function _local_url( $field )
	{
		$url = parse_url($this->{$field}, PHP_URL_HOST);
		$base = parse_url($this->config->item('base_url'), PHP_URL_HOST);
		if( $url != $base)
			return false;
		else
			return true;
	}
	
	function _is_photo( $field )
	{
		$url = $this->{$field};
		
		$ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
		if ( $ext == "jpg" || $ext == "png" || $ext == "gif" )
			return true;
		else
			return false;
	}
		
	function add($data)
	{
		//set placeholder data to save in order to get id
		$this->url = "placeholder.jpg";
		$this->thumb_url = "placeholder.jpg";
		
		if(!$this->save())
		{
			$this->session->set_flashdata('error', $this->error->string);
			redirect('account/photos');	
		}
	
		// re-set data using id
		$original = "uploads/".$data['file_name'];
		$new = "uploads/".$this->id.$data['file_ext'];
	
		$this->load->library('thumb');
		$options = array('resizeUp' => true, 'jpegQuality' => 60);
		$thumb = Thumb::create($original, $options);
		$thumb->adaptiveResize(1024, 768);
		$thumb->save($new, 'jpg');
		
		if(!unlink($original))
		{
			$this->session->set_flashdata('error', 'failed to delete original file');
			redirect('account/photos');
		}
		
		$this->url = $new;
		if(!$this->save())
		{
			$this->session->set_flashdata('error', $this->error->string);
			redirect('account/photos');
		}
		
		$this->thumbnail();
	}
	
	function thumbnail( $size = 150 )
	{
		$original =	$this->url;
		$new = "uploads/thumb/".$this->id.".jpg";
	
		//$this->load->library('thumb');
		$options = array('resizeUp' => true, 'jpegQuality' => 60);
		$thumb = Thumb::create($original, $options);
		$thumb->adaptiveResize($size, $size);
		$thumb->save($new, 'jpg');
	
		$this->thumb_url = $new;
		if(!$this->save())
		{
			$this->session->set_flashdata('error', $this->error->string);
			redirect('account/photos');
		}
	}
}
?>
