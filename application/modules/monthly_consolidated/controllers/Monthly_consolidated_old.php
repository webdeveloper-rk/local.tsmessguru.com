<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Monthly_consolidated extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "subadmin")
					{
						redirect("admin/login");
							die;
					}
					/*if($this->session->userdata("school_code") != "10100")
					{
						redirect("admin/login");
							die;
					}*/
		}
		$this->load->helper('url'); 
		$this->load->model('admin/school_model');
		 $this->load->library('excel'); 
		 $this->load->model('common/common_model');
		 $this->load->config('config.php');
	}

    function index() {
     
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2017]|less_than_equal_to['.date('Y') .']');  

	$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");		
		$data['months']  = $months ;
		$item_names   = array();
		$data = array();
			$report_data = array(); 
			
		//print_a($_POST);
		if($this->form_validation->run() == true    )
		{
			 
			$tyear =  intval($this->input->post('year') );
			$tmonth =  intval($this->input->post('month')) ;
			if($tmonth<10)
					$tmonth = "0".$tmonth;
			$report_date = $tyear ."-". $tmonth."-01"; 
			
		 
		 
			$days_count =  $this->common_model->get_month_days($report_date);
			 
			 
			
			$school_attendence = array();
			 
			$report_date_frmted = $months[$tmonth];
			$report_date_toted =  $tyear ;
			
			$month_year =  "$tmonth-$tyear";
			 
			if($this->session->userdata("is_dco") ==1)
					{
						$schools_rs =  $this->db->query("select * from schools where is_school=1 and district_id=?",array($this->session->userdata("district_id")));
					}
					else
					{
						$schools_rs = $this->db->query("select * from schools where is_school=1");						
					}
			
			//Add Deductions here
			$deductions = array();
			if($this->config->item("deductions_enabled") == true)
			{
				$deduction_rs = $this->db->query("select sc.school_id,sc.school_code,IFNULL(deduction_amount,0 )  deduction_amount  from schools sc left join  
(SELECT school_id  , sum(amount) as deduction_amount FROM    dietpic_cheker  where   DATE_FORMAT( entry_date,  '%m-%Y' ) = ?  and  min_20='no' group by  school_id)
 dm on sc.school_id = dm.school_id  ",array($month_year));			
				//echo $this->db->last_query();die;
				foreach($deduction_rs->result() as $school_item){
					$deductions[$school_item->school_code]=  floatval($school_item->deduction_amount);
				}
			}
			
			
			$is_school_list = array();
			
				foreach($schools_rs->result() as $school_item){
					$is_school_list[]=  $school_item->school_id;
				}
			   $attendece_allowed_table_sql  = " 
			 
			SELECT  school_id  ,
			sum(present_count) as attendence ,
			sum(cat1_attendence+ cat1_guest_attendence) as group1_att,
			sum(cat2_attendence+ cat2_guest_attendence) as group2_att,
			sum(cat3_attendence+ cat3_guest_attendence) as group3_att 
			FROM   school_attendence  where   DATE_FORMAT( entry_date,  '%m-%Y' ) = ? group by school_id  ;";
			 
			
			$attendence_rs =  $this->db->query($attendece_allowed_table_sql,array($month_year));
		 // echo $this->db->last_query();die;
			foreach($attendence_rs->result() as $atrow)
			{
				if(!in_array($atrow->school_id,$is_school_list))
						continue;
				$report_data[$atrow->school_id]['present_count'] = $atrow->attendence;
				$report_data[$atrow->school_id]['group1_att'] = $atrow->group1_att;
				$report_data[$atrow->school_id]['group2_att'] = $atrow->group2_att;
				$report_data[$atrow->school_id]['group3_att'] = $atrow->group3_att; 
			}
			 
			 
			 	 
				$price_sql = "select * from group_prices  where     ? between start_date and end_date";
				$price_rs = $this->db->query($price_sql,array($report_date));
				//echo $this->db->last_query();
				$school_prices = array();
				foreach($price_rs->result() as $stu_price){
					$school_prices[$stu_price->category][$stu_price->group_code] = $stu_price->amount;
				}
				
				$schools_rs = $this->db->query("select * from schools where is_school=1");
				foreach($schools_rs->result() as $school_item){
					$report_data[$school_item->school_id]['group1_amount_month'] = $school_prices[$school_item->amount_category]['gp_5_7'] ;
					$report_data[$school_item->school_id]['group2_amount_month'] = $school_prices[$school_item->amount_category]['gp_8_10'] ;
					$report_data[$school_item->school_id]['group3_amount_month'] = $school_prices[$school_item->amount_category]['gp_inter'] ;
					
					$report_data[$school_item->school_id]['group1_amount_perday'] = $school_prices[$school_item->amount_category]['gp_5_7']/$days_count ;
					$report_data[$school_item->school_id]['group2_amount_perday'] = $school_prices[$school_item->amount_category]['gp_8_10']/$days_count ;
					$report_data[$school_item->school_id]['group3_amount_perday'] = $school_prices[$school_item->amount_category]['gp_inter'] /$days_count;
					
					$report_data[$school_item->school_id]['school_code'] = $school_item->school_code;
					$report_data[$school_item->school_id]['school_type'] = $school_item->school_type;
					$report_data[$school_item->school_id]['name'] = $school_item->name;
					$report_data[$school_item->school_id]['region'] = $school_item->region;
					$report_data[$school_item->school_id]['district_name'] = $school_item->district_name;
					$report_data[$school_item->school_id]['district_name'] = $school_item->district_name;
					
					$report_data[$school_item->school_id]['group1_allowed_amount'] =$report_data[$school_item->school_id]['group1_att'] * $report_data[$school_item->school_id]['group1_amount_perday'];
					$report_data[$school_item->school_id]['group2_allowed_amount'] =  $report_data[$school_item->school_id]['group2_att'] * $report_data[$school_item->school_id]['group2_amount_perday'];
					$report_data[$school_item->school_id]['group3_allowed_amount'] = $report_data[$school_item->school_id]['group3_att'] * $report_data[$school_item->school_id]['group3_amount_perday'];
					
					$report_data[$school_item->school_id]['total_allowed'] = $report_data[$school_item->school_id]['group1_allowed_amount'] + 
																					$report_data[$school_item->school_id]['group2_allowed_amount'] + 
																					$report_data[$school_item->school_id]['group3_allowed_amount'];
 
				}
				
			  
			 
			 
			 
			$balance_sheet_table = $this->common_model->get_stock_entry_table($report_date);
			 
			  $consumed_table_sql  = "   SELECT  school_id as school_id,
						TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + 
						(`session_2_qty` * `session_2_price`) + 
						(`session_3_qty` * `session_3_price`) + 
						(`session_4_qty` * `session_4_price`)  ),2)
						as total_consumed from $balance_sheet_table where  DATE_FORMAT( entry_date,  '%m-%Y' ) =  ? group by school_id ";
				

			//echo $consumed_table_sql;die;
			$consumed_rs = $this->db->query($consumed_table_sql,array($month_year));
			//echo $this->db->last_query();die;
			foreach($consumed_rs->result() as $crow)
			{
				//print_a($crow,1);
				if(!in_array($crow->school_id,$is_school_list))
						continue;
				$report_data[$crow->school_id]['consumed_amount'] =$crow->total_consumed;
				$report_data[$crow->school_id]['remaing_amount'] = $report_data[$crow->school_id]['total_allowed'] - $crow->total_consumed ;

			}
				//unset other than district dco data 
				$report_data2 = $report_data;
			    foreach($report_data2  as $scid=>$scdata)
				{
						if(!in_array($scid,$is_school_list))
						{
						  unset($report_data[$scid]);
						}
							
				}
			    
					
				$report_data['deductions']  = $deductions; 
				$report_data['extra']['month_days'] = $days_count; 
				$report_data['extra']['sel_month'] = $tmonth; 
				$report_data['extra']['sel_year'] = $tyear; 

				
				// print_a($report_data,1); 
				if($this->input->post('submit')=="Download Report")
				{
					 
					$this->attendence_consumed_report($report_data);
					die;
				}
				else{
					 
					$data['attendencereport'] = $report_data;
				}

		}
		 
		$data["module"] = "monthly_consolidated";
        $data["view_file"] = "monthly_consolidated";
        echo Modules::run("template/admin", $data);
         
	}
	
	
	
	public function attendence_consumed_report($school_attendence,$report_date_frmted,$report_date_toted,$schools_info=array())
    {
		 
		$deductions = $school_attendence['deductions'];
		$extra_data = $school_attendence['extra'];
				unset($school_attendence['extra']);
				unset($school_attendence['deductions']);
          
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Attendance & Consumption Report');
               
                $this->excel->getActiveSheet()->setCellValue('A1', 'Attendance and Consumption Report  for month of  '.$months[$this->input->post('month')]." - ". $this->input->post('year')."  ( ".$extra_data['month_days']. "  days)");
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A1:G1');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				///////////////////////////////////////////////////////////////////////////////////////////////////
				
				
						$default_border = array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('rgb'=>'3396FF')
												);
						$style_header = array(
													'borders' => array(
													'bottom' => $default_border,
													'left' => $default_border,
													'top' => $default_border,
													'right' => $default_border,
													),
													'fill' => array(
													'type' => PHPExcel_Style_Fill::FILL_SOLID,
													'color' => array('rgb'=>'3396FF'),
													),
													'font' => array(
													'bold' => true,
													'color' =>  array('rgb'=>'FFFFFF'),
													)
											);

				$this->excel->getActiveSheet()->getStyle('A3:Z3')->applyFromArray( $style_header );

				
 			
				 
				
				
				
				////////////////////////////////////////////////////////////////////////////////////////////////
				$this->excel->getActiveSheet()->setCellValue('A3', 'School Name');
				$this->excel->getActiveSheet()->setCellValue('B3', 'School Code');		
				$this->excel->getActiveSheet()->setCellValue('C3', 'Region');
				$this->excel->getActiveSheet()->setCellValue('D3', 'District');				
				 
				$this->excel->getActiveSheet()->setCellValue('E3', 'Cat 1 Attendence');
				$this->excel->getActiveSheet()->setCellValue('F3', 'Cat 1 Per Day');
				$this->excel->getActiveSheet()->setCellValue('G3', 'Cat 1 Amount');
				
				$this->excel->getActiveSheet()->setCellValue('H3', 'Cat 2 Attendence');
				$this->excel->getActiveSheet()->setCellValue('I3', 'Cat 2 Per Day');
				$this->excel->getActiveSheet()->setCellValue('J3', 'Cat 2 Amount');
				
					$this->excel->getActiveSheet()->setCellValue('K3', 'Cat 3 Attendence');
				$this->excel->getActiveSheet()->setCellValue('L3', 'Cat 3 Per Day');
				$this->excel->getActiveSheet()->setCellValue('M3', 'Cat 3 Amount');
				
				
				$this->excel->getActiveSheet()->setCellValue('N3', 'Attendence');				
				
				$this->excel->getActiveSheet()->setCellValue('O3', 'Allowed Amount');				
				$this->excel->getActiveSheet()->setCellValue('P3', 'Consumption Amoun');
				$this->excel->getActiveSheet()->setCellValue('Q3', 'Remaining Amount');
				
				 
					if($this->config->item("deductions_enabled") == true && $this->session->userdata("is_dco") ==0 )
					{
						$this->excel->getActiveSheet()->setCellValue('R3', 'Deduction Amount');
						$this->excel->getActiveSheet()->setCellValue('S3', 'DIET AMOUNT TO BE RELEASED');
						$this->excel->getActiveSheet()->setCellValue('T3', 'School Type');
					}else{
						$this->excel->getActiveSheet()->setCellValue('R3', 'School Type');
					}
				
				
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:S3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                //$this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				foreach($school_attendence as $school_code=>$school_data){ 
				 
					if($school_data['school_code'] == "85000" || $school_data['school_code'] == "")
								continue;
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data['name']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_data['school_code']  );
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_data['region'] );
					$this->excel->getActiveSheet()->setCellValue('D'.$i,   $school_data['district_name']);
					
					
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $school_data['group1_att']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $school_data['group1_amount_perday']);
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $school_data['group1_amount_month']);
					
					
					$this->excel->getActiveSheet()->setCellValue('H'.$i, $school_data['group2_att']);
					$this->excel->getActiveSheet()->setCellValue('I'.$i, $school_data['group2_amount_perday']);
					$this->excel->getActiveSheet()->setCellValue('J'.$i, $school_data['group2_amount_month']);
					
					$this->excel->getActiveSheet()->setCellValue('K'.$i, $school_data['group3_att']);
					$this->excel->getActiveSheet()->setCellValue('L'.$i, $school_data['group3_amount_perday']);
					$this->excel->getActiveSheet()->setCellValue('M'.$i, $school_data['group3_amount_month']);
					
					
					$this->excel->getActiveSheet()->setCellValue('N'.$i,$school_data['present_count']);
					
					
					$this->excel->getActiveSheet()->setCellValue('O'.$i, $school_data['total_allowed']);
					$this->excel->getActiveSheet()->setCellValue('P'.$i,   $school_data['consumed_amount']);
					$this->excel->getActiveSheet()->setCellValue('Q'.$i,   $school_data['remaing_amount']);
					 
					if($this->config->item("deductions_enabled") == true && $this->session->userdata("is_dco") ==0)
					{
						
						$min_which_ever_less = min($school_data['total_allowed'],$school_data['consumed_amount']);
							$ded_amt  = $deductions[ $school_data['school_code']];
							$tobe_releases  = $min_which_ever_less - $ded_amt;
							
							
						 
						$this->excel->getActiveSheet()->setCellValue('R'.$i,$ded_amt);
						$this->excel->getActiveSheet()->setCellValue('S'.$i,  $tobe_releases);
						$this->excel->getActiveSheet()->setCellValue('T'.$i,  $school_code['school_type']);
					}
					else{
						$this->excel->getActiveSheet()->setCellValue('R'.$i,  $school_code['school_type']);
					}
					
					
					  $this->excel->getActiveSheet()->getStyle('S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					 
					$i++;$sno++;
				}
	 
				 
                
              
                $filename='report_'.date('d-M-Y')	.'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
                 
    }
	 
	

	
}
