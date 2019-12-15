<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0); 
 date_default_timezone_set('Asia/Kolkata');
class Report_purchase_bills extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
			$this->load->helper('url');  
			$this->load->config("config.php");
			$this->load->library("ci_jwt");
			$this->load->library("excel");
			$this->load->model("common/common_model");
			$this->load->config("purchase_bills/config");

			if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
				redirect("admin/login");
				die;
			}

			if($this->session->userdata("user_role") != "subadmin" &&  $this->session->userdata("school_code") != "dsapswreis")
			{
				redirect("admin/login");
				die;
			}
			
		$this->load->library('Image_crud');
}

   	function index() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			  $school_date = $this->ci_jwt->jwt_web_encode($school_date);
			 
			 redirect('report_purchase_bills/purchasebills_report/'. $school_date);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "report_purchase_bills";
        $data["view_file"] = "subadmin_purchase_bills";
        echo Modules::run("template/admin", $data);
    }
	function purchasebills_report($encoded_date=null)
	{
		 $date = $this->ci_jwt->jwt_web_decode($encoded_date);
		if($date==null)
				$date = date('Ymd');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 

		$sql = "SELECT s.name, s.school_code,s.school_id, COUNT( p.id ) AS total_uploads
										FROM  schools s LEFT JOIN  purchase_bills_old p  ON p.school_id = s.school_id where date(p.dateposted)=? and s.is_school = 1 
										GROUP BY p.school_id  ";
		$rs = $prs  = $this->db->query($sql,array($report_date));
		$uploads = array();
		foreach($prs->result() as $row){
			$uploads[$row->school_id] = $row->total_uploads;
		}
		////////////////////////////////////
		$balance_sheet_table = $this->common_model->get_stock_entry_table($report_date);
		
		  $sql = "SELECT school_id, COUNT( * ) AS item_count
								FROM $balance_sheet_table
								WHERE entry_date =  ? and purchase_quantity > 0 
								GROUP BY school_id ";
		$itrs  = $this->db->query($sql,array($report_date));
		$item_counts = array();
		foreach($itrs->result() as $row){
			$item_counts[$row->school_id] = $row->item_count;
		}
//print_a($uploads);
		
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		
		
					$uid  = $this->session->userdata("user_id");
					$district_id  = $this->session->userdata("district_id");
					if($this->session->userdata("is_dco")==0)
					{
							$sql = "SELECT * from schools where  is_school='1' and school_code not like '%85000%' ";
							$rs = $prs  = $this->db->query($sql);
					}
					else
					{
										$sql = "SELECT * from schools where district_id=?  and  is_school='1' and school_code not like '%85000%'";
										$rs = $prs  = $this->db->query($sql,array($district_id));
					}

					
		
		$data["rset"] = $rs;
		
		
		$data["encoded_date"] = $encoded_date;
		$data["item_counts"] = $item_counts;
		$data["uploads"] = $uploads;
		 
		$data["module"] = "report_purchase_bills";
		$data["view_file"] = "subadmin_purchase_bills_list";
		echo Modules::run("template/admin", $data);
		
	}

	/*********************************************************************************/
	function admingallery($school_id=0,$datechoosen=null)
	{
		   $school_id = intval($school_id);
		$datechoosen = $this->ci_jwt->jwt_web_decode($datechoosen);
		$schol_rs = $this->db->query("select * from schools where school_id=?",array($school_id));		
		$rsc_row =	$schol_rs->row();	
		  
		$data["date_choosen"] = date('d-m-Y',strtotime($datechoosen));
		$data["school_info"] = $rsc_row;
		$data["item_counts"] = $item_counts;
		$data["item_counts"] = $item_counts;
		$data["uploads"] = $uploads;
		 
		$data["bills_rset"] =  $this->db->query("select * from purchase_bills_old where school_id=? and date(dateposted)   = ?",array($school_id,$datechoosen));
		$data["module"] = "report_purchase_bills";
		$data["view_file"] = "purchasebills_list";
		echo Modules::run("template/admin", $data); 
			 
	}
	
		function spaces_bills() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			  $school_date = $this->ci_jwt->jwt_web_encode($school_date);
			 
			 redirect('report_purchase_bills/purchasebills_report_spaces/'. $school_date);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "report_purchase_bills";
        $data["view_file"] = "purchasebills_spaces";
        echo Modules::run("template/admin", $data);
    }
	function purchasebills_report_spaces($encoded_date=null)
	{
		 $date = $this->ci_jwt->jwt_web_decode($encoded_date);
		if($date==null)
				$date = date('Ymd');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 

		$sql = "SELECT s.name, s.school_code,s.school_id, COUNT( p.bill_id ) AS total_uploads
										FROM  schools s LEFT JOIN  purchase_bills p  ON p.school_id = s.school_id where date(p.uploaded_time)=? and s.is_school = 1 
										GROUP BY p.school_id  ";
		$rs = $prs  = $this->db->query($sql,array($report_date));
		//echo $this->db->last_query();
		$uploads = array();
		foreach($prs->result() as $row){
			$uploads[$row->school_id] = $row->total_uploads;
		}
		////////////////////////////////////
		$balance_sheet_table = $this->common_model->get_stock_entry_table($report_date);
		  $sql = "SELECT school_id, COUNT( * ) AS item_count
								FROM $balance_sheet_table
								WHERE entry_date =  ? and purchase_quantity > 0 
								GROUP BY school_id ";
		$itrs  = $this->db->query($sql,array($report_date));
		$item_counts = array();
		foreach($itrs->result() as $row){
			$item_counts[$row->school_id] = $row->item_count;
		}
//print_a($uploads);
		
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		
		
					$uid  = $this->session->userdata("user_id");
					$district_id  = $this->session->userdata("district_id");
					if($this->session->userdata("is_dco")==0)
					{
							$sql = "SELECT * from schools where  is_school='1' and school_code not like '%85000%' ";
							$rs = $prs  = $this->db->query($sql);
					}
					else
					{
										$sql = "SELECT * from schools where district_id=?  and  is_school='1' and school_code not like '%85000%'";
										$rs = $prs  = $this->db->query($sql,array($district_id));
					}

					
		
		$data["rset"] = $rs;
		
		
		$data["encoded_date"] = $encoded_date;
		$data["item_counts"] = $item_counts;
		$data["uploads"] = $uploads;
		 
		$data["module"] = "report_purchase_bills";
		$data["view_file"] = "spaces_bills_list";
		echo Modules::run("template/admin", $data);
		
	}
	
	function spacesgallery($school_id=0,$datechoosen=null)
	{
		   $school_id = intval($school_id);
		$datechoosen = $this->ci_jwt->jwt_web_decode($datechoosen);
		$schol_rs = $this->db->query("select * from schools where school_id=?",array($school_id));		
		$rsc_row =	$schol_rs->row();	
		  
		$data["date_choosen"] = date('d-m-Y',strtotime($datechoosen));
		$data["school_info"] = $rsc_row;
		$data["item_counts"] = $item_counts;
		$data["item_counts"] = $item_counts;
		$data["uploads"] = $uploads;
		 
		$data["bills_rset"] =  $this->db->query("select * from purchase_bills where school_id=? and date(uploaded_time)   = ?",array($school_id,$datechoosen));
		$data["module"] = "report_purchase_bills";
		$data["view_file"] = "space_purchasebills";
		echo Modules::run("template/admin", $data); 
			 
	}
}
