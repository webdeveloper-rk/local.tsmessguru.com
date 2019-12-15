<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Specials extends MX_Controller {

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
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD'); 
		$this->load->library('ci_jwt'); 
		 
		 
	}

    function index() {
        redirect("specials/today_approvals");
    }
  	/************************************************************************************
	
	
	
	
	************************************************************************************/
	
	function today_approvals()
	{
	 
		$datetime = new DateTime('tomorrow');
		$tommorow =  $datetime->format('Y-m-d');

		$school_id = $this->session->userdata("school_id");

		$sql = "SELECT  ita.dpc_approved,it.item_id, it.telugu_name,it.item_name,ita.strength, DATE_FORMAT(ita.entry_date, '%d-%m-%Y') entry_date,
						DATE_FORMAT(ita.requested_time, '%d-%m-%Y %r') requested_time, ita.status as item_status FROM  items it  left join  item_approvals
						ita  on  ita.item_id = it.item_id and ita.entry_date=? 
						and ita.school_id =?  where  it.item_special='special' 
						ORDER By ita.requested_time desc ";
		$rs  = $this->db->query($sql,array($tommorow,$school_id));
		
		$report_date_formated = date('d-m-Y',strtotime($tommorow));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		 
		$school_name_rs = $this->db->query("select * from users where school_id=?",array($school_id));
		$school_data  = $school_name_rs->row();
		
		
		$data["school_name"] = $school_data->name;
		$data["module"] = "specials"; 
		$data["view_file"] = "school_approvals";
		echo Modules::run("template/admin", $data);
		
	}
	
	/************************************************************************************
	
	
	
	
	************************************************************************************/
	
	function approvals_list()
	{
	 
		  

		$school_id = $this->session->userdata("school_id");

		$sql = "SELECT  it.item_id, it.telugu_name,it.item_name,ita.strength, ita.dpc_approved,
						DATE_FORMAT(ita.entry_date, '%d-%m-%Y') entry_date,DATE_FORMAT(ita.requested_time, '%d-%m-%Y %r') requested_time,
						ita.status as item_status FROM  items it  inner join  item_approvals ita  on  ita.item_id = it.item_id		 
						and ita.school_id =?  where  it.item_special='special' ORDER By ita.entry_date desc ";
		$rs  = $this->db->query($sql,array($school_id)); 
		$data["rset"] = $rs;
		 
		$school_name_rs = $this->db->query("select * from users where school_id=?",array($school_id));
		$school_data  = $school_name_rs->row();
		
		
		$data["school_name"] = $school_data->name;
		 
		$data["module"] = "specials";
		$data["view_file"] = "school_approvals_list";
		echo Modules::run("template/admin", $data);
		
	}
	/**************************************************************************************
	
	
	
	
	
	**************************************************************************************/
		
		/******************************************************
		
		
		
		/******************************************************/
		
	 function approval_entryform($enc_item_id=null)
	{
		$item_id =  $this->ci_jwt->jwt_web_decode($enc_item_id);	
		$this->form_validation->set_rules('dpc_approved', 'DPC Approved', 'required');              
		$this->form_validation->set_rules('strength', 'Strength', 'required|numeric|greater_than[0]'); 
	
	
			
		
		$school_id	=	$this->session->userdata("school_id"); 
		$datetime = new DateTime('tomorrow');
		$tommorow =  $datetime->format('Y-m-d');			 
		$tommorow_formated  =  $datetime->format('d-m-Y');		
	
		$message = '';
		 
			$rs = $this->db->query("select * from  item_approvals where  school_id=? and  item_id=? and entry_date=?",array($school_id,$item_id, $tommorow));
			if($rs->num_rows()>0){
				redirect("specials");
			}
		 
		if($this->form_validation->run() == true  &&  $this->input->post('action')=="submit")
		 {
			 
				$school_id	=	$this->session->userdata("school_id");	 
				$dpc_approved 		=  $this->input->post('dpc_approved') ; 
				$strength 		= 	 $this->input->post('strength') ;  
				$rs = $this->db->query("select * from  item_approvals where  school_id=? and  item_id=? and entry_date=?",array($school_id,$item_id, $tommorow));
				if($rs->num_rows()==0)
				{
					if($dpc_approved  == "yes")
						$dpc_status = "Approved";
					else 
						$dpc_status = "Not Approved";
					
					//$skip_items_per_approval  = array(277,54,110,167,313);//Skip item ids , default its approved even dpc is no itesm List : Date,Chiken,Dry Dates
					$skip_items_per_approval  = array();
					
					if(in_array($item_id,$skip_items_per_approval))
						$dpc_status = "Approved";
					
					$qry= "insert into  item_approvals set  
												school_id=? ,
												item_id=?,
												entry_date=?,
												requested_time=now(),
												status=?,
												dpc_approved=?,
												strength=?
												
												";
											//	echo $qry;die;
					$this->db->query($qry,array($school_id,$item_id,$tommorow,$dpc_status,$dpc_approved,$strength));
					$this->session->set_flashdata('message', 'Successfully submitted');
					redirect('specials/today_approvals');
					
				}
				
				
				
				
				
				
		 }
        $data["date"] = $tommorow_formated; 
        $data["item_id"] = $item_id;
        $data["session_id"] = $session_id;
        $data["item_details"] = $this->db->query("select * from items where item_id=?",array($item_id))->row(); 
        $data["module"] = "specials";
        $data['data_entry_allowed']=$eligibility; 
		$data['data_entry_text']=$message;  
       
        $data["view_file"] = "approval_form";
        echo Modules::run("template/admin", $data);
         
	}
	
	
	
	  
	
}
