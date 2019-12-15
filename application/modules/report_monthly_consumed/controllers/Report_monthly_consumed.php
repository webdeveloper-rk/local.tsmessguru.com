<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_monthly_consumed extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if(!in_array($this->session->userdata("user_role") ,array( "school","subadmin")))
					{
						redirect("admin/login");
							die;
					}
					
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
		$this->load->model("common/common_model");  
	}
	function index()
	{
		   
		   $cyear = date('Y');
		$this->form_validation->set_rules('month', 'Month ', 'required|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|greater_than_equal_to[2017]|less_than_equal_to['.$cyear .']');  
		if($this->session->userdata("user_role")=="subadmin"){
				$this->form_validation->set_rules('school_code', 'School  ', 'required');  
		}	
		 
		if($this->form_validation->run() == true )
		{
			
			$month = intval($this->input->post('month'));
			$year = intval($this->input->post('year'));
			if($month<10)
					$month = "0".$month;
			
			$school_date = $year."-".$month."-01";
		}
		else
		{ 
			$month = date('m'); 
			$year = date('Y');
			 $school_date = date('Y-m-d');
		}
			$data['result_flag']			  =  0;
		
		if($this->session->userdata("user_role")=="subadmin"){
				if($this->input->post('school_code')!="")
				 {
					 $school_code = $this->input->post('school_code');
					 
					 
					 $srs = $this->db->query("select * from schools where school_code=?",array($school_code));
					 $school_data = $srs->row();
					 $data['school_info'] =  $school_data;
					 $school_id = $school_data->school_id;		
					$data['result_flag']			  =  1;
				 }
		}
		 
		if($this->session->userdata("user_role")=="school")
		{
			$school_id = intval($this->session->userdata("school_id"));
			$srs = $this->db->query("select * from schools where school_id=?",array($school_id));
			$sch_data = $srs->row();
			 $data['school_info'] =  $sch_data;
			$school_code = $sch_data->school_code;	
			$data['result_flag']			  =  1;
			
		}
		 
		 
		 
		$srs = $this->db->query("select * from schools where school_id=?",array($school_id));
		$sch_data = $srs->row();
		$school_amount_category = $sch_data->amount_category;
		$price_sql = "select * from group_prices  where category=? and ? between start_date and end_date";
		$price_rs = $this->db->query($price_sql,array($school_amount_category,$school_date));
		// echo $this->db->last_query();
		$student_prices = array();
		foreach($price_rs->result() as $stu_price){
			$student_prices[$stu_price->group_code] = $stu_price->amount;
		}
		//print_a($student_prices);
		$data['student_prices'] = $student_prices;
		
		
		
		$days_sql = "SELECT DAY( LAST_DAY( '$year-$month-01' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		$days_count = $days_row->days ;
		$data['days_count'] = $days_count;
		
		/*********************/
		$group_1_per_day= $student_prices['gp_5_7']/$days_count;
		$group_2_per_day= $student_prices['gp_8_10']/$days_count;
		$group_3_per_day= $student_prices['gp_inter']/$days_count;
		
		
		$data['group_1_per_day'] = $group_1_per_day;
		$data['group_2_per_day'] = $group_2_per_day;
		$data['group_3_per_day'] = $group_3_per_day;
		
		/*//////////////////////////*/
		/******get attendence ******/
			
		$attendece  = 0;
		$group_1_attendence = 0;
		$group_2_attendence = 0;
		$group_3_attendence = 0;
		$group_1_price = 0;
		$group_2_price = 0;
		$group_3_price = 0;
		
		$school_date = '$year-$month-01';
		$days_sql = "SELECT DAY( LAST_DAY( '$school_date' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		  $days_count = $days_row->days ;
		//print_a($student_prices);
		  $atters_sql = "select 
								sum(cat1_attendence + cat1_guest_attendence) as grp1_count,
								sum(cat2_attendence + cat2_guest_attendence) as grp2_count,
								sum(cat3_attendence + cat3_guest_attendence) as grp3_count 
								from school_attendence where school_id=?  and month(`entry_date`) =?  and year(`entry_date`) = ? ";
		$atters = $this->db->query($atters_sql,array($school_id, $month,$year));
		if($atters->num_rows()>0)
		{
			$attedata = $atters->row();
			
			$group_1_attendence =  $attedata->grp1_count  ;
			$group_2_attendence =  $attedata->grp2_count;
			$group_3_attendence  =  $attedata->grp3_count;
			
			$data['group_1_attendence'] =  $group_1_attendence;
			$data['group_2_attendence'] =  $group_2_attendence;
			$data['group_3_attendence']  =  $group_3_attendence;
			
			 
			$group_1_price =  $group_1_attendence * $group_1_per_day;
			$group_2_price =  $group_2_attendence * $group_2_per_day;
			$group_3_price =  $group_3_attendence * $group_3_per_day;
			
			$data['group_1_price'] =  $group_1_price;
			$data['group_2_price'] =  $group_2_price;
			$data['group_3_price'] =  $group_3_price;
			
			
			$attendece = $group_1_attendence + $group_2_attendence + $group_3_attendence;
			$data['attendence'] = $attendece;
		}
		  $allowed_amount  = $group_1_price + $group_2_price + $group_3_price;
		
		
		$total_attendence = $attendece ;
		$mdate = $year."-$month"."-01";
		 $stock_entry_table = $this->common_model->get_stock_entry_table($mdate);
		 
		  $asql = "SELECT  sum( 
								(`session_1_qty`*`session_1_price`) + 
								(`session_2_qty`*`session_2_price`) + 
								(`session_3_qty`*`session_3_price`) + 
								(`session_4_qty`*`session_4_price`)  
							) as consumed_amount 
				from   $stock_entry_table  where school_id=? and  month(`entry_date`) =? and year(`entry_date`) = ?";
		$asrs  = $this->db->query($asql,array($school_id, $month,$year));
		 
		$asdata = $asrs->row();
		$consumed_amount  = $asdata->consumed_amount;
		
		$balance = $allowed_amount - $consumed_amount;
		
		$data['month'] = $month;
		$data['year'] = $year;
		$data['balance'] = $balance;
		$data['allowed_amount'] = $allowed_amount;
		$data['consumed_amount'] = $consumed_amount;
		$data['attendece'] =  $attendece;
		
		//$data['allowed_amount'] = 0;
		// $data['balance'] = 0;
		
		$data["module"] = "report_monthly_consumed";
        $data["view_file"] = "monthly_consumed";
        echo Modules::run("template/admin", $data);
 
	
	}
    
	
}
