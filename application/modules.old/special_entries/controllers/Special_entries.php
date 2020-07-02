<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0); 
 date_default_timezone_set('Asia/Kolkata');
class Special_entries extends MX_Controller {

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
		
		 $this->listv();
	}
	 
	 	/*************************************************************************
	
	
	
	*****************************************************************************/
	function listv($sdate=null) {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 if($this->input->post('savings_date')!=""  )
		 {
			$savings_date =   date('Y-m-d',strtotime( $this->input->post('savings_date')));
			if($savings_date=="1970-01-01")
							 $savings_date =   date('Y-m-d'); 
							
			$savings_date_encoded = $this->ci_jwt->jwt_web_encode( $savings_date );
			redirect('special_entries/listv/'.$savings_date_encoded); 
		 }
		 if($sdate==null){ 
			 $savings_date =   date('Y-m-d'); 
		 }
		 else{
				
				$savings_date =   date('Y-m-d',strtotime($this->ci_jwt->jwt_web_decode( $sdate )));  
		 }
		  $data['savings_date']=   $savings_date;
		  $data['rdate_display'] =     date('d-m-Y',strtotime($savings_date));
		 
		 
		 
		 
		 $report_for = $this->config->item('society_name'); 
		   $sql  = "select ita.approval_id,ita.strength,ita.dpc_approved ,sc.name,sc.school_code,ita.school_id ,it.telugu_name,it.item_name,ita.status ,date_format(ita.entry_date,'%d-%m-%Y' ) entry_date , date_format(ita.requested_time,'%d-%m-%Y %r' ) requested_time 
												from item_approvals ita  inner join schools sc on sc.school_id = ita.school_id  inner join items it on it.item_id=ita.item_id
													where  date_format(ita.requested_time,'%Y-%m-%d')=?   order by sc.school_code,ita.item_id asc";
			$sc_items_report = $this->db->query($sql,array($savings_date));	
			
			 
			$data['report_for'] = $report_for; 

			$data['display_result'] = true ;
			$data['rdata'] = $sc_items_report;
         
        $data["type"] = $type;
        $data["tval"] = $tval; 
        
        $data["module"] = "special_entries";
        $data["view_file"] = "specilas_approvals_admin";
        echo Modules::run("template/admin", $data);
    }
	
	
}
