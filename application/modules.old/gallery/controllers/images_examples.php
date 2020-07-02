<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images_examples extends MX_Controller {

	function __construct()
	{
		parent::__construct() 
		$this->load->library('Image_CRUD');
	}
	
	function send_output($output = null)
	{
		$this->load->view('example.php',$output);	
	}
	
	function index()
	{
		$this->send_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}	
	
 

	function projectgallery($project_id)
	{
		$image_crud = new image_CRUD();
	
		$image_crud->set_primary_key_field('id');
		$image_crud->set_url_field('url');
		$image_crud->set_title_field('title');
		$image_crud->set_table('project_gallery')
		->set_ordering_field('priority')
		->set_image_path('assets/uploads/projectgallery'); 
		$output = $image_crud->render(); 
		$this->send_output($output);
	}
	
	 
}