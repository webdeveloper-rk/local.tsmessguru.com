<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MX_Controller {

	function __construct()
	{
		parent::__construct() ;
		$this->load->library('Image_CRUD');
	}
	
	function send_output($output = null)
	{
		$this->load->view('example.php',$output);	
	}
	
	function index()
	{
		//$this->send_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		//$this-> projectgallery();
		 
	}	
	
 

	function purchasegallery()
	{
		 $school_id = 12;
		  
	$image_crud = new image_CRUD();
	
		$image_crud->set_primary_key_field('id');
		$image_crud->set_url_field('url');
		$image_crud->set_title_field('title');
		$image_crud->set_table('purchase_bills'); 
		$image_crud->set_ordering_field('dateposted');
		$image_crud->unset_delete();
		$image_crud->set_image_path('assets/uploads/gallery'); 
		
		//$image_crud->set_relation_field(12);
		$output = $image_crud->render(); 
		 //$this->_example_output($output);
			$data["module"] = "gallery";
			$data["view_file"] = "manage";
			$output->title = " Purchase  Bills";
			
			//echo "<pre>";			print_r($output);die;
			$data["output"] = $output;
			echo Modules::run("template/admin", $data);
			 
	}
	
	 
}