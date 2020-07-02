<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms extends MX_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}

	 

	public function index()
	{
		$this->cmsmgmt();
	}

	public function cmsmgmt()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('cms');
			$crud->set_subject('Content Management');
			$crud->required_fields('title','description');
			$crud->columns('title','description','meta_title','meta_description','meta_keywords','meta_tags');

			$output = $crud->render();

			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	public function _example_output($output = null)
	{
		  $data["module"] = "admin";
        $data["view_file"] = "example";
        echo Modules::run("template/admin", $data);
		//$this->load->view('example.php',$output);
	}
 
}