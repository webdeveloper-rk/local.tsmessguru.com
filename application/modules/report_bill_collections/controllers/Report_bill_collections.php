<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_bill_collections extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "subadmin")
					{
						redirect("admin/login");
							die;
					}
		$this->load->helper('url');  
		$this->load->library("ci_jwt");  
		$this->load->library("excel");  
	}
	function index()
	{
		   
		 $school_id = 0;
		 $school_code = '';
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		if($this->input->post('school_code')!="")
		 {
			  $school_code = $this->input->post('school_code');
			  
			 
			 $srs = $this->db->query("select * from users where school_code=?",array($school_code)) ;
			  $school_data = $srs->row();
		
			 $school_id = $school_data->school_id;		
			 //redirect('admin/subadmin/attendencelist/'.$school_id );
			 redirect('report_bill_collections/billcollectionlist/'.$school_id );
		 }
		 
		 $data["module"] = "report_bill_collections";
        $data["view_file"] = "school_billcollection";
        echo Modules::run("template/admin", $data);
	
	}
    
	 	  function billcollectionlist($school_id ){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('datatables'); 
			$crud->set_table('extra_bills');
			$crud->where('school_id',$school_id );
			$crud->order_by('entry_date','desc');
			$crud->set_subject('BillCollection');
			
			 $crud->callback_column('entry_date',array($this,'date_formatdisplay'));
			 $crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
			//
			 
						$crud->unset_add(); 
						 
			 
            $crud->unset_delete();
			$crud->columns(array('entry_date','no_of_guests','amount'));
			
			$crud->display_as('no_of_guests','  Guest Count');
			
			$crud->edit_fields(array('entry_date','no_of_guests','amount','biil_desc'));

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Billcollection entries of " .$this->db->query("select * from schools where school_id=?",array($school_id))->row()->name;
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	
		function date_formatdisplay($value, $row)
		{
			 return date('d-M-Y',strtotime($value));
		}
     
	
}
