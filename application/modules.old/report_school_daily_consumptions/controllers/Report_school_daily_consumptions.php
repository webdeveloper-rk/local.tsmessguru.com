<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_school_daily_consumptions extends MX_Controller {

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
		$this->load->config("config.php");  
		$this->load->library("ci_jwt"); 
 $this->load->model('common/common_model');		
	}
	function index()
	{
		 $school_id = 0;
		 $school_code = '';
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']  =  0;
	 
		 $data['posted_school_id'] = 0;
		$data['posted_school_date'] =  '';
 
		$this->form_validation->set_rules('school_id', 'School  ', 'required|numeric|greater_than[0]');  
		$this->form_validation->set_rules('school_date', 'Date ', 'required');  

		
		if($this->form_validation->run() == true    )
		{
		 
			 $school_id = 	intval($this->input->post('school_id'));
			 $school_date = date('Y-m-d',strtotime($this->input->post('school_date')));
			 
			 if( $school_date=="1970-01-01")
			 {
			  $school_date = date('Y-m-d');
			 }
			 if($school_id ==0)
			 {
				redirect(current_url());
			 }
			 
			 $data['posted_school_id'] =  $school_id;
			 $data['posted_school_date'] =   date('m/d/Y',strtotime($school_date));
			 $data['human_school_date'] =   date('d-m-Y',strtotime($school_date));
			 $data['result_flag']  =  1;
			  
		/* calculate balances */
		$balance_sheet_table = $this->common_model->get_stock_entry_table($school_date);

		
		$trs_sql  = "select sum(round(((session_1_qty*session_1_price) +(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price)),2)) as consumed_total
					from $balance_sheet_table where school_id=? and entry_date=?";
	 
		$trs = $this->db->query($trs_sql,array($school_id,$school_date));
			 
	
		if($trs->num_rows()>0)
		{
			$tdata = $trs->row();
			$today_consumed_Amount = $tdata->consumed_total;
		}
		/**********Calculate attendence ***************/
		
		$daily_amount  = 0.00;
		
		 
		
		$srs = $this->db->query("select * from schools where school_id=?",array($school_id));
		
		if($srs->num_rows()==0)
		{
			redirect(current_url());
		}
		$sch_data = $srs->row();
		$data['school_info']= $sch_data;
		$school_amount_category = $sch_data->amount_category;
		$price_sql = "select * from group_prices  where category=? and ? between start_date and end_date";
		$price_rs = $this->db->query($price_sql,array($school_amount_category,$school_date));
		//echo $this->db->last_query();
		$student_prices = array();
		foreach($price_rs->result() as $stu_price){
			$student_prices[$stu_price->group_code] = $stu_price->amount;
		}
		//print_a($student_prices);
		$data['student_prices'] = $student_prices;

		/******get attendence ******/
			
		$attendece  = 0;
		$group_1_attendence = 0;
		$group_2_attendence = 0;
		$group_3_attendence = 0;
		$group_1_price = 0;
		$group_2_price = 0;
		$group_3_price = 0;
		
		$group_1_perday_price = 0;
		$group_2_perday_price = 0;
		$group_3_perday_price = 0;
		
		
		$days_sql = "SELECT DAY( LAST_DAY( '$school_date' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		  $days_count = $days_row->days ;
		//print_a($student_prices);
		
		$group_1_perday_price = ($student_prices['gp_5_7']/$days_count);
		$group_2_perday_price =($student_prices['gp_8_10']/$days_count);
		$group_3_perday_price = ($student_prices['gp_inter']/$days_count);
		
		$atters_sql = "select * from school_attendence where school_id=? and entry_date=?";
		$atters = $this->db->query($atters_sql,array($school_id,$school_date));
		if($atters->num_rows()>0)
		{
			$attedata = $atters->row();
			
			$group_1_attendence =  $attedata->cat1_attendence  +  $attedata->cat1_guest_attendence;
			$group_2_attendence =  $attedata->cat2_attendence  + $attedata->cat2_guest_attendence;
			$group_3_attendence =  $attedata->cat3_attendence  + $attedata->cat3_guest_attendence;
			 
			$group_1_price =  $group_1_attendence * ($student_prices['gp_5_7']/$days_count);
			$group_2_price =  $group_2_attendence * ($student_prices['gp_8_10']/$days_count);
			$group_3_price =  $group_3_attendence *  ($student_prices['gp_inter']/$days_count);
			
			
			
			$attendece =  $attedata->present_count;
			$attendece2 = $group_1_attendence + $group_2_attendence + $group_3_attendence;
		}
		$data['student_prices'] = $student_prices;
		$data['days_count'] = $days_count;
		$data['group_1_attendence'] = $group_1_attendence;
		$data['group_2_attendence']= $group_2_attendence ;
		$data['group_3_attendence'] = $group_3_attendence ;
		$data['group_1_price'] = number_format($group_1_price,2);
		$data['group_2_price'] = number_format($group_2_price,2);
		$data['group_3_price'] = number_format($group_3_price,2);
		
		$data['group_1_perday_price'] = number_format($group_1_perday_price,4);
		$data['group_2_perday_price'] = number_format($group_2_perday_price,4);
		$data['group_3_perday_price'] = number_format($group_3_perday_price,4);
		 
		 
		$today_allowed_Amount = $group_1_price + $group_2_price + $group_3_price;
		/**************************************/
		
        $data["reportdate"] =  date('d-M-Y',strtotime($this->input->post('school_date')));
        $data["school_name"] = $today_allowed_Amount;
        $data["per_stundent"] = $daily_amount;
        $data["attendence"] = $attendece;
		
        $data["today_allowed_Amount"] = number_format($today_allowed_Amount,2);
        $data["today_consumed_Amount"] = $today_consumed_Amount;
		$data["today_remaining_Amount"] = number_format($today_allowed_Amount -  $today_consumed_Amount,2);
	
		}
		
        
		
        $data["school_code"] = $school_code;
        $data["module"] = "report_school_daily_consumptions";
        $data["view_file"] = "school_consumed";
        echo Modules::run("template/admin", $data);
	}
    
	
}
