<?php

class Admin extends CI_Controller {

	var $data;
	var $u;
	
	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->auth->bouncer(100);
		$this->load->library('datamapper');
		if( !empty($this->session->userdata['profiler']) && $this->session->userdata['profiler'] == TRUE )
		{
			$this->data['profiler'] = TRUE;
		}
		else
		{
			$this->data['profiler'] = FALSE;
		}
	}

	function index()
	{
		$this->data['title'] = "Admin | Control Panel";		
		$this->load->view('header', $this->data);
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/index', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	function users()
	{
		$this->data['title'] = "Admin | Users";
		$U = new User();
		$U->include_related_count('good');
		$U->order_by('created', 'asc')->get();
		$this->data['users'] = $U->all;
		$this->data['js'][] = 'jquery-datatables.php';
		$this->data['js'][] = 'includes/jquery-impromptu.min.php';
		$this->data['css'][] = 'datatables.css';
		$this->data['css'][] = 'impromptu.css';
		$this->load->view('header', $this->data);
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/users', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	function tags()
	{
		$this->data['title'] = "Admin | Tags";
		$T = new Tag();
		$T->include_related_count('good');
		$T->order_by('name', 'asc')->get();
		$this->data['tags'] = $T->all;
		$this->data['js'][] = 'jquery-datatables.php';
		$this->data['css'][] = 'datatables.css';
		$this->data['css'][] = 'impromptu.css';
		$this->load->view('header', $this->data);
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/tags', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	function gifts()
	{
		$this->_goods("Admin | Gifts", "gift");
	}
	
	function needs()
	{
		$this->_goods("Admin | Needs", "need");
	}

	function _goods($title, $type) {
		$this->data['title'] = $title;
		$G = new Good();
		$G->include_related('location', array('address'), FALSE, TRUE); // resolve the address
		$G->include_related_count("tag"); // include the tag count
		$G->order_by('type', 'asc')
		->where('type', $type)
		->limit(500)
		->get();
		$this->data['goods'] = $G->all;
		$this->data['js'][] = 'jquery-datatables.php';
		$this->data['css'][] = 'datatables.css';
		$this->load->view('header', $this->data);
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/goods', $this->data);
		$this->load->view('admin/includes/footer', $this->data);
		$this->load->view('footer', $this->data);
	}

	// AJAX functions

	function toggleUserDisable($userId) {
		$U = new User();
		$U->where('id', $userId)->get();

		if ($U->status == "disabled")
			$U->where('id', $userId)->update('status', 'active');
		else
			$U->where('id', $userId)->update('status', 'disabled');
	}

	function deleteTag($tagId) {
		$T = new Tag($tagId);
		$T->delete();
		// TODO: check to see if the delete worked and then return a true/false confirmation
	}

	function renameTag($tagId, $renameTo) {
		$T = new Tag();
		$T->where('id', $tagId)->update('name', $renameTo);
	}

	function mergeTag($tagId, $mergeToName) {
		$Tag_A = new Tag($tagId);
		$Tag_B = new Tag();
		$Tag_B->where('name', $mergeToName)->get();
		if (count($Tag_B->all) == 0)
		{
			return;
		}

		// find all goods that use the tag
		$G = new Good();
		$G->where_related_tag($Tag_A)->get();
		echo count($G->all);

		foreach ($G as $good)
		{
			$good->add_tag($Tag_B);		// add the new tag to the good
			$good->remove_tag($Tag_A->name);	// remove the old tag from the good
		}

		// If the old tag is no longer being used, remove the tag from the database
		$G = new Good();
		$G->where_related_tag($Tag_A)->get();

		// TODO: handle this with foreign keys and cascading deletes
		//if (count($G->all) == 0)
		//{
		//	$Tag_A->delete();
		//}
	}

	function tagSelectList(){
		$T = new Tag();

		$T->order_by('name', 'asc')->get();
		$tags = $T->all;
		$output = "";
		foreach($tags as $key=>$val) {
			$output .= "<option id=$val->id>$val->name</option>\n";
		}
		echo $output;
	}
	
	function toggle_profiler()
	{
		if( !empty($this->session->userdata['profiler']) && $this->session->userdata['profiler'] == TRUE )
		{
			$this->session->set_flashdata('success', 'Profiler disabled.');
		 	$this->session->set_userdata('profiler', FALSE);
		 }
		 else
		 {
		 	$this->session->set_flashdata('success', 'Profiler enabled!');
		 	$this->session->set_userdata('profiler', TRUE);
		}
		redirect('admin');
	}
}

/* End of file member.php */
/* Location: ./system/application/controllers/member.php */
