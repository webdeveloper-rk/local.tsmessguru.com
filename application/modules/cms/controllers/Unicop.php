<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unicop extends MX_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

 
					
            
		 
	}

	public function _example_output($output = null)
	{
		$this->load->view('example.php',$output);
	}

	 
	public function index()
	{
		$this->updateattendence();
	}
	 
	
	  function updateattendence() {
		 $school_id = 0;
		 $school_code = '';
		 
		$data['frame_load'] = false;
		
		if($this->input->post('school_code')!="")
		{
			 
			$school_code = $this->input->post('school_code'); 
			  $attendence_date = $this->input->post('attendence_date'); 
			
			$attendence_split = explode("/",	$attendence_date);
			
			//m-d-Y
			
			$year = $attendence_split[2]; 
			$month = $attendence_split[1]; 
			$day = $attendence_split[0]; 
			
			$choosen_date = $year."-".$month."-".$day;
			
			
			 $url = site_url()."tribal_newattendence/?date=".$choosen_date;
			    $data['frame_url'] = $url;
			$data['frame_load'] = true;
		}
		 
		$this->load->view("cms/unicop_admin_school_attendence",$data);
	  }
	  
	    
	 function attendencelist($school_id ){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('school_attendence');
			$crud->where('school_id',$school_id );
			$crud->order_by('entry_date','desc');
			$crud->set_subject('Attendance');
			
			 
			$crud->unset_add(); 
			$crud->unset_edit(); 
			$crud->unset_delete();
			
			$crud->columns(array('entry_date','present_count','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence','cat3_attendence','cat3_guest_attendence'));
			
			$crud->display_as('cat1_guest_attendence','Category 1 Guest Attendance')
				->display_as('cat2_guest_attendence','Category 2 Guest Attendance')
				->display_as('cat3_guest_attendence','Category 3 Guest Attendance');
			$crud->display_as('cat1_attendence','Category 1 Attendance')
				->display_as('cat2_attendence','Category 2  Attendance')
				->display_as('cat3_attendence','Category 3 Attendance');
			$crud->display_as('present_count','Total');
			
			 
			 
			//$crud->field_type('entry_date', 'readonly');
			// $crud->field_type('cat1_attendence', 'readonly' );
			// $crud->field_type('cat2_attendence', 'readonly' );
			// $crud->field_type('cat3_attendence', 'readonly' );
			 

			  $srs = $this->db->query("select * from schools  where school_id='$school_id'") ;
			  $school_data = $srs->row();
		
			 $school_name = $school_data->name;		
			 
			 
			 
			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Attendance entries of  $school_name ";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	 	 
	  
	
	 
}