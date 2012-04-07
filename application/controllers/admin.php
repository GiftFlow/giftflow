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
		$this->load->view('admin/index', $this->data);
		$this->load->view('admin/admin_javascript', $this->data);
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
		$this->data['css'][] = 'datatables.css';
		$this->data['css'][] = 'impromptu.css';
		$this->load->view('header', $this->data);
		$this->load->view('admin/index', $this->data);
		$this->load->view('admin/users', $this->data);
		$this->load->view('admin/admin_javascript', $this->data);
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
		$this->load->view('admin/index', $this->data);
		$this->load->view('admin/tags', $this->data);
		$this->load->view('admin/admin_javascript', $this->data);
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
		$this->load->view('admin/index', $this->data);
		$this->load->view('admin/goods', $this->data);
		$this->load->view('admin/admin_javascript', $this->data);
		$this->load->view('footer', $this->data);
	}

	function alert_templates()
	{
		$this->data['title'] = "Admin | Alert Templates";
		$template = new Term();
		$template->where('type','alert_template')->order_by('id', 'asc')->get();
		$this->data['tags'] = $template->all;
		$this->data['js'][] = 'jquery-datatables.php';
		$this->data['css'][] = 'datatables.css';
		$this->data['css'][] = 'impromptu.css';
		$this->load->view('header', $this->data);
		$this->load->view('admin/index', $this->data);
		$this->load->view('admin/alert_templates', $this->data);
		$this->load->view('admin/admin_javascript', $this->data);
		$this->load->view('footer', $this->data);

	}
	// AJAX functions

	function delete_alert_template()
	{
		// Parse $_POST array
		$template_id = $_POST['template_id'];

		// Load alert template object
		$template = new Term( $template_id );
		
		// Then delete it
		$template->delete();
	}

    function is_term_unique()
    {
      $template_id = intval($_POST['template_id']);
      $template_name = $_POST['template_name'];
      //$template_name = "haloqq";
      $template = new Term();
      $isset_template = $template->where('name', $template_name)->get();

      echo ($isset_template->name == "" ||
           ($template_id != 0 && $template_id == $isset_template->id)) ? "true" : "false";

    }

	function edit_alert_template()
	{
		// Parse $_POST array
		$template_id = $_POST['template_id'];
		$template_name = $_POST['template_name'];
		$template_subject = $_POST['template_subject'];
		$template_body = $_POST['template_body'];
		
		// New alert template object
		$template = new Term($template_id);
		$template->where('id', $template_id)->update('name', $template_name);
		$template->where('id', $template_id)->update('subject', $template_subject);
		$template->where('id', $template_id)->update('body', $template_body);
	}

	function add_alert_template()
	{
		// Parse $_POST array
		$template_name = $_POST['template_name'];
		$template_body = $_POST['template_body'];
		$template_subject = $_POST['template_subject'];

		// New alert template object
		$template = new Term();
		$template->name = $template_name;
		$template->body = $template_body;
		$template->subject = $template_subject;
		$template->save();
	}

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
