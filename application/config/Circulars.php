<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Circulars extends MX_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		 
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 
					if ($this->session->userdata("user_role") != "subadmin" ) {
							redirect("dashboard");
							die;
					}
					
		}
					
            
		 
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	function index()
	{
		//print_a($this->session->all_userdata());
		 
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			$crud->set_theme('datatables');
			$crud->set_table('circulars');
			$crud->set_subject('Circulars');
			//$crud->unset_add();
			$crud->unset_delete();
			$crud->columns(array('title','document_path', 'upload_date','updated_time','status'));
			$crud->add_fields(array('title','document_path','upload_date' ,'uploaded_by'));
			$crud->edit_fields(array('title','document_path','updated_by','updated_time','status'));
			$crud->required_fields(array('title','document_path'));  
			$crud->field_type('upload_date','hidden',date('d-m-Y h:i:s a'));
			 
			$crud->field_type('updated_time','hidden',date('d-m-Y a:i:s a'));
			$crud->set_field_upload('document_path','assets/uploads/documents');
			// $crud->set_relation('uploaded_by','users','name');
			 //$crud->set_relation('updated_by','users','name');
			
				$crud->field_type('uploaded_by','hidden',$this->session->userdata("user_id"));
			$crud->field_type('updated_by','hidden',$this->session->userdata("user_id"));
			$crud->order_by('upload_date','desc');
			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = "Manage Circulars";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	 
	 
}