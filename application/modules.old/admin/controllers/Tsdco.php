<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tsdco extends MX_Controller {

    function __construct() {
        parent::__construct();
		
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
				 
					if($this->session->userdata("user_role") != "tsdco")
					{
						redirect("admin/tsdco");
							die;
					}
					
					 
					
					 
		}
					
          $this->load->model('admin_model');
         $this->admin_model->set_table("users");
    }

   

    function index() {

      $this->report();

    }
	function report()
	{
		  $school_code = $this->session->userdata('school_code');
		  $user_id = $this->session->userdata('user_id');
		 
		 $sql = "select * from users where uid='$user_id'";
		$rs  = $this->db->query($sql);
		$user_info = $rs->row();
		$district_id = $user_info->district_id;
		
		 $schools_rs = $this->db->query("select * from schools");
		 $school_names  = array();
		 foreach($schools_rs->result() as $srow)
		 {
			  $school_names[$srow->school_id]  = $srow->name . " - ". $srow->school_code ;
		 }
		$data["school_names"] = $school_names;
		
		//echo "select * from dpcrates_approvals where school_id in (select school_id from schools  where district_id='$district_id')";
		$school_name_rs = $this->db->query("select *,date_format(school_submited_date,'%d-%m-%Y %r') as school_submited_date ,
		date_format(dpc_approved_time,'%d-%m-%Y %r') as dpc_approved_time 
		
		from dpcrates_approvals where school_id in (select school_id from schools  where district_id='$district_id')");
		 
		
		
		$data["schools_list"] = $school_name_rs;
		
		
		$data["module"] = "admin";
		$data["view_file"] = "school_approval_list";
		echo Modules::run("template/admin", $data);
		
	}
	    function viewschoolrates($school_dcode='') {
			
			$school_id= 0;
			$school_name = '';
			$school_rs= $this->db->query("select * from schools where md5(school_id) ='$school_dcode'");
			if($school_rs->num_rows()>0)
			{
				$sdata = $school_rs->row();
				$school_id  = $sdata->school_id;
				  $school_name  = $sdata->name. "- ". $sdata->school_code;
				$data['school_name']   = $sdata->school_name. "- ". $sdata->school_code;
			}
			else
			{
				die("invalid request");
			}

		 
			
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
			$dco_approved = $rsdata->dpc_approved;
	
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
			if($school_submitted!= 1)
			{
				$crud->unset_edit(); 
			} 
			else if($dco_approved == 1){
				$crud->unset_edit(); 
			}
			 
			$output = $crud->render();  
			 
			$data["school_submitted"] =  $school_submitted;
			
			
			if($school_submitted==0)
			{
				$extra_content = "<br>School Not Submitted the Rates. please contact school<br>";
			}
			if($school_submitted==1)
			{
				$extra_content = "<br>School Submitted the Rates. <br>";
			}
			
			if($school_submitted ==1 && $dco_approved==0)
			{
				$extra_content = "<a class='btn btn-info pull-right' onclick=\"return confirm('Are you sure to submit? ')\" href='".site_url('admin/tsdco/approverates/'.$school_dcode)."'>Click here to Approve</a>";
			}
			else if($school_submitted ==1 && $dco_approved==1)
			{
				$extra_content = "<h4 style='color:#0000FF'>Dpc Approved.</h4>";
			}
			$data["extra_content"] = $extra_content;
			$data["module"] = "admin";
			$data["view_file"] = "cms";
			$output->title =  $school_name . " - DPC Rates";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}

    } 
	
	function approverates($school_dcode='') {
			
			$school_id= 0;
			$school_name = '';
			$school_rs= $this->db->query("select * from schools where md5(school_id) ='$school_dcode'");
			if($school_rs->num_rows()>0)
			{
				$sdata = $school_rs->row();
				$school_id  = $sdata->school_id;
				  $school_name  = $sdata->name;
				$data['school_name']   = $sdata->school_name;
			}
			else
			{
				die("invalid request");
			}
		
		 
			
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
			
			$rs = $this->db->query("update  dpcrates_approvals set  dpc_approved_time=now() ,dpc_approved='1' where financial_year_id='$approval_year_id' and school_id='$school_id'");
			 
			redirect('admin/tsdco');
	}
	
 

}

