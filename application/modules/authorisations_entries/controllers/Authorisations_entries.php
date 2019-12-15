<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0); 
 date_default_timezone_set('Asia/Kolkata');
class Authorisations_entries extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
			$this->load->helper('url');  
			$this->load->config("config.php");
			$this->load->library("ci_jwt");
			$this->load->model("common/common_model");

			if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
				redirect("admin/login");
				die;
			}

			if($this->session->userdata("user_role") != "subadmin")
			{
				redirect("admin/login");
				die;
			}
}

   	public function index(){
		
		  $this->authorisations_entries();
    }
	
		/*************************************************************************
	
	
	
	*****************************************************************************/
	function authorisations_entries() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			 if($school_date=="1970-01-01")
							 $school_date =   date('Y-m-d'); 
			 $school_date_encoded = $this->ci_jwt->jwt_web_encode( $school_date );
			 redirect('authorisations_entries/viewreport/'. $school_date_encoded);
			 die;
		 }
		  
		 
        $data["school_code"] = "";
        $data["module"] = "authorisations_entries";
        $data["view_file"] = "authorise_date";
        echo Modules::run("template/admin", $data);
    }
	function viewreport($date=null)
	{
		if($date==null)
				$date = date('Ymd');
		else
				$date =   date('Y-m-d',strtotime($this->ci_jwt->jwt_web_decode( $date )));  
		
		$report_date = date('Y-m-d',strtotime($date));
		 
		 $district_condition = "";
		 
		 if($this->session->userdata("is_dco")=="1"){
				$district_condition = " and s.district_id='".intval($this->session->userdata("district_id"))."'";
		 }
		 else{
				$district_condition = " ";
		 }
		 
			
		  $sql = "SELECT 
cc.*,s.name as schoolname,s.school_code,
date_format(session_1_time,'%d-%m-%Y %H:%i:%s') as session_1_time,	
date_format(session_2_time,'%d-%m-%Y %H:%i:%s') as session_2_time,	
date_format(session_3_time,'%d-%m-%Y %H:%i:%s') as session_3_time,	
date_format(session_4_time,'%d-%m-%Y %H:%i:%s') as session_4_time 	

 from 
caretaker_confirmation cc inner join schools s on cc.school_id= s.school_id and entry_date=? and is_school='1'  and school_code not like '%85000%'   $district_condition order by school_code asc ";
		$rs  = $this->db->query($sql,array($report_date));
		//echo $this->db->last_query();
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		$data["rdate"] = $report_date;
		
		$dsql = "select count(*) as logged_count,school_id  from   login_sessions where     operator_type='CT' and  date_format(log_time,'%Y-%m-%d') = ? group by school_id ";
		$drsw = $this->db->query($dsql,array($report_date));
		$school_logins = array();
		foreach($drsw->result() as $row)
		{
			$school_logins[$row->school_id] = $row->logged_count;
		}
					 
		 
		$data["school_logins"] = $school_logins;
		$data["module"] = "authorisations_entries";
		$data["view_file"] = "school_authorised_entries_list";
		echo Modules::run("template/admin", $data);
		
	}
	

}
