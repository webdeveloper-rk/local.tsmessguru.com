<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tssocial_dpcrates extends MX_Controller {

    function __construct() {
        parent::__construct();
		
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					if($this->session->userdata("user_role") != "school")
					{
						 redirect("admin"); 
					}
					 
					
					
					 
		}
					
          $this->load->model('admin_model'); 
		  	$this->load->library('grocery_CRUD');
    }

   

    function index() {

			$school_id = $this->session->userdata("school_id");
			
			$rs  = $this->db->query("select * from  dpcrates_years where status='active' limit 0,1 ");//table must have single record as active 
			$financial_year_id = 0 ;
			if($rs->num_rows()>0)
			{
				$financial_year_id = $rs->row()->financial_year_id;
			}
			
			$ra_approval	= $this->db->query("select da.financial_year_id,da.dpc_approved,da.school_submitted from 
												dpcrates_years dy inner join dpcrates_approvals da  on da.financial_year_id=dy.financial_year_id 
													and dy.status='active' where school_id='$school_id'");
				
			$rsdata  = $ra_approval->row() 	;
			$approval_year_id = $rsdata->financial_year_id;
			$school_submitted = $rsdata->school_submitted;
	
        try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid'); 
			$crud->set_table('dpc_rates');
			$crud->set_subject('Dpc Rates');
			
			$crud->set_relation('item_id','items','<b>{telugu_name} - {	item_name} </b>');
			$crud->display_as('item_id','Item Name');
			
			$crud->columns(array( 'item_id','amount'));  			
			$crud->where('school_id',$school_id);
			$crud->where('financial_year_id',$financial_year_id);
			$crud->field_type('item_id', 'readonly');
			$crud->edit_fields(array('item_id','amount'));
			
			$crud->unset_add(); 
			$crud->unset_delete(); 
			if($school_submitted== 1)
			{
				$crud->unset_edit(); 
			} 
			$output = $crud->render();  
			 
			$data["school_submitted"] =  $school_submitted;
			if($school_submitted==0)
			{
				$extra_content = "<a class='btn btn-info pull-right' onclick=\"return confirm('Are you sure to submit? ')\" href='".site_url('admin/Tssocial_dpcrates/submitrates')."'>Click here to submit</a>";
			}
			else
			{
				$extra_content = "<h4 style='color:#0000FF'>Submitted</h4>";
			}
			$data["extra_content"] = $extra_content;
			$data["module"] = "admin";
			$data["view_file"] = "cms";
			$output->title = "Manage Dpc rates";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}

    } 
	function submitrates()
	{
		$school_id = $this->session->userdata("school_id");
			
			$rs  = $this->db->query("select * from  dpcrates_years where status='active' limit 0,1 ");//table must have single record as active 
			$financial_year_id = 0 ;
			if($rs->num_rows()>0)
			{
				$financial_year_id = $rs->row()->financial_year_id;
			}
			
			$ra_approval	= $this->db->query("select da.financial_year_id,da.dpc_approved from 
												dpcrates_years dy inner join dpcrates_approvals da  on da.financial_year_id=dy.financial_year_id 
													and dy.status='active' where school_id='$school_id'");
				
			$rsdata  = $ra_approval->row() 	;
			$approval_year_id = $rsdata->financial_year_id;
			$dpc_approved = $rsdata->dpc_approved;
			//echo   "update  dpcrates_approvals set school_submitted='1' where financial_year_id='$approval_year_id' and school_id='$school_id'";	die;
			
			$rs = $this->db->query("update  dpcrates_approvals set  school_submited_date=now() ,school_submitted='1' where financial_year_id='$approval_year_id' and school_id='$school_id'");
			 
			redirect('admin/Tssocial_dpcrates');
	}
}

