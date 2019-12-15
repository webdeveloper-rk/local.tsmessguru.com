<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumption_excemptions extends MX_Controller {

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
					
					if($this->session->userdata("user_role") != "subadmin")
					{
						 
							redirect("admin/login");
						 
					}
					 
					/*$menu_code_rs = $this->db->query("select * from menus where permission_code=?",array("consumption_excemptions"));
					if($menu_code_rs->num_rows()==0)
					{
						redirect("general/logout");
						die;
					}
					$menu_id = $menu_code_rs->row()->menu_id;
					$menu_access_rs = $this->db->query("select * from menu_roles where menu_id=?",array($menu_id));
					if($menu_code_rs->num_rows()==0)
					{
						redirect("general/logout");
						die;
					}
					*/
					
					 
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
			$crud->set_table('consumption_entry_excemptions');
			$crud->set_subject('Consumption entry excemption Schools');
			  $crud->unique_fields(array('school_code' ));
			 
			$crud->required_fields(array('school_code'));  
			 
			$crud->order_by('school_code','asc');
			
			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "manage";
			$data["view_file"] = "cms";
			$output->title = "Consumption entry excemption Schools";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	 
	
	 
	 
}