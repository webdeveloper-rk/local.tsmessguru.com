<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Admin_attendence extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") ! "subadmin")
					{
						redirect(site_url());
							die;
					}
					if($this->session->userdata("school_code") != "10100")
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('ci_jwt');
	 
	}

    function index() {
            
		  
		$this->form_validation->set_rules('school_id', 'School Name', 'required|numeric');     
		 
		if($this->form_validation->run() == true )
		{
				 
			 $school_id = intval($this->input->post('school_id'));	
			 
			 //check logged user can access this school id 
			 
			  $school_id = $this->ci_jwt->jwt_web_encode($school_id);
			 redirect('attendence/admin_attendence/alist/'.$school_id );
		 }
		 
		 $data["module"] = "attendence";
        $data["view_file"] = "school_attendence";
        echo Modules::run("template/admin", $data);
					 
	
    }
  
  
  
  	  function alist($school_id_encoded ){
		  
		    $school_id = $this->ci_jwt->jwt_web_decode($school_id_encoded);
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('school_attendence');
			$crud->where('school_id',$school_id );
			$crud->order_by('entry_date','desc');
			$crud->set_subject('Attendance');
			
			 $crud->callback_column('entry_date',array($this,'date_formatdisplay'));
			 $crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
			//
			//echo  $this->session->userdata("user_id"),"--";
					if(  $this->session->userdata("user_id")>2)
					{
						$crud->unset_add(); 
						$crud->unset_edit(); 
					}						
			
			
			 
            $crud->unset_delete();
			$crud->columns(array('entry_date','present_count','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence','cat3_attendence','cat3_guest_attendence'));
			
			$crud->display_as('cat1_guest_attendence','Category 1 Guest Attendance')
				->display_as('cat2_guest_attendence','Category 2 Guest Attendance')
				->display_as('cat3_guest_attendence','Category 3 Guest Attendance');
			$crud->display_as('cat1_attendence','Category 1 Attendance')
				->display_as('cat2_attendence','Category 2  Attendance')
				->display_as('cat3_attendence','Category 3 Attendance');
			$crud->display_as('present_count','Total');
			
			$crud->edit_fields(array('entry_date','cat1_attendence','cat2_attendence','cat3_attendence', 'cat1_guest_attendence', 'cat2_guest_attendence','cat3_guest_attendence'));
			$crud->callback_after_update(array($this, 'update_attendence_total_count'));
			 
			 
			//$crud->field_type('entry_date', 'readonly');
			// $crud->field_type('cat1_attendence', 'readonly' );
			// $crud->field_type('cat2_attendence', 'readonly' );
			// $crud->field_type('cat3_attendence', 'readonly' );
			 
			$school_info = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Attendance entries of " .$school_info->name;
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	
}
