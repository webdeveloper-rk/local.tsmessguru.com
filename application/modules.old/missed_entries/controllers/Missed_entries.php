<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Missed_entries extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 	//print_a($this->session->all_userdata(),1);	
						
						$roles = array('subadmin','secretary');
					if(!in_array($this->session->userdata("user_role"),$roles))
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url');
		$this->load->model('common/common_model');
	 
	}

    function index() {
			$this->listschools();
		}
		
	function listschools()
	{
		
		if($this->input->post('school_date')!="")
		 {
			   $date = date('Ymd',strtotime($this->input->post('school_date'))); 
		 }else {
		 
				$date = date('Ymd');
		 }
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
		 $condition_dco =  '';
		 if($this->session->userdata("is_dco") == 1) 
		{
						$condition_dco = " and  district_id = '".intval($this->session->userdata("district_id"))."'   ";
		}
			$balance_sheet_table = $this->common_model->get_stock_entry_table($report_date);
			
		  $sql = "select sc.school_id,sc.school_code,sc.name ,sc.is_school,t1.total_purchase,t1.total_qty from schools sc left join 
		  ( SELECT `school_id`,sum(purchase_quantity) as total_purchase,sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as total_qty
			FROM $balance_sheet_table WHERE `entry_date` = ? group by school_id 
			having(sum(purchase_quantity))>0 or sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty)>0 ) as t1 on sc.school_id = t1.school_id 
			where sc.is_school=1 and t1.total_purchase is NULL and t1.total_qty is NULL and sc.school_code not like '%85000%' $condition_dco order by sc.school_code 
		  ";
		$rs  = $this->db->query($sql,array($report_date));
		//echo $this->db->last_query();
		
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		
		
		$data["module"] = "missed_entries"; 
		$data["view_file"] = "missed_schools";
		echo Modules::run("template/admin", $data);
		
	}
	
	
	function monthly()
	{
		
		if($this->input->post('month')!="" && $this->input->post('year')!="")
		 {
			  $year_month = $this->input->post('year')."-".$this->input->post('month');
		 }else {
		 
				$year_month = date('Y-m');
		 }
			
		 //echo $year_month;die;
			
		  $sql = " select sc.school_id ,school_code,name ,  count(*) as missed_days from missed_monthly ms inner join schools sc on sc.school_id=ms.school_id
			WHERE DATE_FORMAT(entry_date,'%Y-%m') = ?  and sc.is_school='1' and sc.school_code !='85000' group by ms.school_id   ";
		$rs  = $this->db->query($sql,array($year_month));
		 
		$report_date_formated = date('M-Y',strtotime($year_month."-01"));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		
		
		$data["module"] = "missed_entries"; 
		$data["view_file"] = "monthly_missed";
		echo Modules::run("template/admin", $data);
		
	}
}
 