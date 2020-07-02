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
					 
					$menu_code_rs = $this->db->query("select * from menus where permission_code=?",array("circulars_admin"));
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
			$crud->columns(array('title','section','document_path', 'upload_date','status'));
			$crud->add_fields(array('title','section','document_path','upload_date' ,'uploaded_by'));
			$crud->edit_fields(array('title','section','document_path','updated_by' ,'status'));
			$crud->required_fields(array('title','section','document_path'));  
			$crud->field_type('upload_date','hidden',date('d-m-Y h:i:s a'));
			 
			$crud->field_type('updated_time','hidden',date('d-m-Y a:i:s a'));
			$crud->set_field_upload('document_path','assets/uploads/circulars');
			$crud->callback_after_insert(array($this, 'insert_mail_box'));
				
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
	
		public function  insert_mail_box($post_array,$primary_key)
	{
			 
			 $circular_id = $primary_key;
			 $insert_sql  = "insert into circulars_mailbox(school_id,circular_id) select school_id,? from schools   where is_school=1";
			 $this->db->query( $insert_sql,array($circular_id));
			// $this->db->last_query();die;
			 return true;
	}
	
	 
	 
}