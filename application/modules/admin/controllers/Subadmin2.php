<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Subadmin2 extends MX_Controller {

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
		}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->model('school_model');
		 $this->load->library('excel');
		 $this->load->library('table');
	}

    function index() {
        $data["module"] = "admin";
        $data["view_file"] = "subadmin_dashboard";
        echo Modules::run("template/admin", $data);
    }
	
	function attendencereports_months()
	{
		$this->form_validation->set_rules('year', 'Year', 'required');              
		$this->form_validation->set_rules('month', 'Month', 'required');  
	$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");		
		$data['months']  = $months ;
		$item_names   = array();
		$data = array();
		//print_a($_POST);
		if($this->form_validation->run() == true && $this->input->post('year') !=""  )
		{
				//Get Group Amounts
			//	print_a($_POST);
			  $price_sql = "select * from group_prices";
			$price_rs = $this->db->query($price_sql);
			$student_prices = array();
			foreach($price_rs->result() as $stu_price){
				$student_prices[$stu_price->group_code] = $stu_price->amount;
			}
			$tyear =  $this->input->post('year') ;
			$tmonth =  $this->input->post('month') ;
			$report_date = $this->input->post('year') ."-". $this->input->post('month')."-01"; 
			$days_sql = "SELECT DAY( LAST_DAY( '$report_date' ) ) as days";
			$days_row  = $this->db->query($days_sql)->row();
			$days_count = $days_row->days ;
			
			$group_1_per_day= $student_prices['gp_5_7']/$days_count;
			$group_2_per_day= $student_prices['gp_8_10']/$days_count;
			$group_3_per_day= $student_prices['gp_inter']/$days_count;
			
			
			$school_attendence = array();
				
			
			//$school_attendence['month_days'] = $days_count;
		//	$school_attendence['student_prices'] = $student_prices;
			  
		  
			 
			 
			$report_date_frmted = $months[$tmonth];
			$report_date_toted =  $tyear ;
			
			$attendece_allowed_table_temp = "tempatte_".rand(1000,1000000);
			   $attendece_allowed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $attendece_allowed_table_temp AS (
			 
			SELECT s.school_id,s.school_code,s.name,s.strength, 
			sp.cat1_amount/$days_count as cat1_per_day,sp.cat2_amount/$days_count as cat2_per_day,sp.cat3_amount/$days_count as cat3_per_day,
			
			truncate(sum(
				((cat1_attendence + cat1_guest_attendence) * sp.cat1_amount/$days_count) + 
				((cat2_attendence + cat2_guest_attendence)   * sp.cat2_amount/$days_count) + 
				((cat3_attendence+ cat3_guest_attendence)   *sp.cat3_amount/$days_count)  
			),2) as  allowed_amt ,
			sum(present_count) as attendence ,
			sum(cat1_attendence+ cat1_guest_attendence) as group1_att,
			sum(cat2_attendence+ cat2_guest_attendence) as group2_att,
			sum(cat3_attendence+ cat3_guest_attendence) as group3_att,
			
			sum((cat1_attendence+ cat1_guest_attendence)) *  sp.cat1_amount/$days_count as cat1_amount,
			sum((cat2_attendence+ cat2_guest_attendence)) *  sp.cat2_amount/$days_count as cat2_amount,
			sum((cat3_attendence+ cat3_guest_attendence)) *  sp.cat3_amount/$days_count as cat3_amount
			
			FROM `school_attendence` sa inner join schools s on s.school_id = sa.school_id inner join school_prices sp on sp.school_id= sa.school_id and sp.month='$tmonth' and sp.year='$tyear' 
					and  DATE_FORMAT( entry_date,  '%m-%Y' ) =  '$tmonth-$tyear' group by sa.school_id );";
			 
			 //echo $attendece_allowed_table_sql;die;
			 $this->db->query($attendece_allowed_table_sql);
			 
			 /*
			 select s.school_id,con.* from schools s left join ( SELECT  school_id as sid,  TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + (`session_2_qty` * `session_2_price`) + (`session_3_qty` * `session_3_price`) + (`session_4_qty` * `session_4_price`) ),2) as today_consumed from balance_sheet where DATE_FORMAT( entry_date, '%m-%Y' ) = '12-2017' group by school_id) as con on  con.sid = s.school_id
			 
			 
			 */
			$consumed_table_temp  = "tempconsumed_".rand(1000,1000000);
			  $consumed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $consumed_table_temp AS 
			  
			  select s.school_id,con.* from schools s left join 
			  ( SELECT  school_id as sid,
						TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + 
						(`session_2_qty` * `session_2_price`) + 
						(`session_3_qty` * `session_3_price`) + 
						(`session_4_qty` * `session_4_price`)  ),2)
						as today_consumed from balance_sheet where  DATE_FORMAT( entry_date,  '%m-%Y' ) =  '$tmonth-$tyear' group by school_id) 
						as    con on  con.sid = s.school_id 
						
						
						; ";
						
			$this->db->query($consumed_table_sql);

				
//kiran
			$user_id = $this->session->userdata("user_id") ;
			
			if($this->session->userdata("user_id")>10) {
				$dist_id = $this->session->userdata("district_id") ;
						$sch_scls = $this->db->query("select * from schools where district_id  = '$dist_id' ");
			}
			else{
						$sch_scls = $this->db->query("select * from schools ");
			}
			$schools_list = array();
			foreach($sch_scls->result() as $scrow)
			{
				$schools_list[] = $scrow->school_id;
			}
			
			 
			 $joined_ary = "select att.*,con.today_consumed from $attendece_allowed_table_temp att inner join $consumed_table_temp  con on att.school_id=con.school_id";
			 $trs  = $this->db->query($joined_ary);
			
				 foreach($trs->result() as $row)
				 {
					
					 if(!in_array($row->school_id,$schools_list))
							continue;
						 
					$school_attendence[$row->school_code] =  array(
							'school_id'=>$row->school_id,
							'name'=>$row->name,
							'strength'=>$row->strength,
							'days_count'=>$days_count,
							'grp1_att'=>$row->group1_att,
							
							'grp2_att'=>$row->group2_att,
							'grp3_att'=>$row->group3_att,
							'group_1_per_day'=>number_format($row->cat1_per_day,2),
							'group_2_per_day'=>number_format($row->cat2_per_day,2),
							'group_3_per_day'=>number_format($row->cat3_per_day,2),
							
							'cat1_amount'=>number_format($row->cat1_amount,2),
							'cat2_amount'=>number_format($row->cat2_amount,2),
							'cat3_amount'=>number_format($row->cat3_amount,2),
							
							'attendence'=>$row->attendence,
							
							'allowed_amt'=>$row->allowed_amt,
							'consumed_amt'=>$row->today_consumed,
							'remaining_amt'=>number_format($row->allowed_amt - $row->today_consumed,2),
							'rdate'=>$report_date_frmted,
							'tdate'=> $report_date_toted
							);
				 }
				 
					//$consumed_table_temp
					//print_a($school_attendence);
					
				$school_attendence['extra']['month_days'] = $days_count;
				$school_attendence['extra']['student_prices'] = $student_prices;

				if($this->input->post('submit')=="Download Report")
				{
					$data['sel_month'] = $tmonth;
					$data['sel_year'] = $tyear;
					 
					$this->attendence_consumed_report($school_attendence,$report_date_frmted,$report_date_toted);
					die;
				}
				else{
					$data['sel_month'] = $tmonth;
					$data['sel_year'] = $tyear;
					$data['rdate'] = $report_date_frmted;
					$data['tdate'] =  $report_date_toted;
					$data['attendencereport'] = $school_attendence;
				}

		}
		$school_rs = $this->db->query("select * from schools ");
		$schools_info = array();
		foreach($school_rs->result() as $schrow)
		{
			$schools_info[$schrow->school_code]['district'] = $schrow->district_name;
			$schools_info[$schrow->school_code]['region'] = $schrow->region;
			$schools_info[$schrow->school_code]['school_type'] = $schrow->school_type;
		}
		$data["schools_info"] = $schools_info;
		$data["module"] = "admin";
        $data["view_file"] = "school/attendencereports_months";
        echo Modules::run("template/admin", $data);
         
	}
	

function attendencereports()
	{
		$this->form_validation->set_rules('fromdate', 'Date', 'required');              
		$this->form_validation->set_rules('todate', 'Date', 'required');              
		 
		$item_names   = array();
		$data = array();
		if($this->form_validation->run() == true && $this->input->post('fromdate') !=""  )
		{
			 
			$report_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
			$report_todate = date('Y-m-d',strtotime($this->input->post('todate')));
			$report_date_frmted = date('d-M-Y',strtotime($this->input->post('fromdate')));
			$report_date_toted = date('d-M-Y',strtotime($this->input->post('todate')));
			
			$attendece_allowed_table_temp = "tempatte_".rand(1000,1000000);
			  $attendece_allowed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $attendece_allowed_table_temp AS (
			SELECT s.school_id,s.school_code,s.name,s.strength, sum(present_count) as attendence,s.daily_amount,sum(sa.present_count) * s.daily_amount as allowed_amt FROM `school_attendence` sa inner join schools s on s.school_id = sa.school_id 
					and (entry_date between '$report_date' and '$report_todate') group by sa.school_id );";
			 
			 $this->db->query($attendece_allowed_table_sql);
			 
			 
			$consumed_table_temp  = "tempconsumed_".rand(1000,1000000);
			  $consumed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $consumed_table_temp AS ( SELECT  school_id,
						TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + 
						(`session_2_qty` * `session_2_price`) + 
						(`session_3_qty` * `session_3_price`) + 
						(`session_4_qty` * `session_4_price`)  ),2)
						as today_consumed from balance_sheet where (entry_date between '$report_date' and '$report_todate') group by school_id); ";
						
			$this->db->query($consumed_table_sql);

				


			$school_attendence = array();
			 
			   $joined_ary = "select att.*,con.today_consumed from $attendece_allowed_table_temp att inner join $consumed_table_temp  con on att.school_id=con.school_id";
			 $trs  = $this->db->query($joined_ary);
			
				 foreach($trs->result() as $row)
				 {
					$school_attendence[$row->school_code] =  array(
							'school_id'=>$row->school_id,
							'name'=>$row->name,
							'strength'=>$row->strength,
							'attendence'=>$row->attendence,
							'daily_amount'=>$row->daily_amount,
							'allowed_amt'=>$row->allowed_amt,
							'consumed_amt'=>$row->today_consumed,
							'remaining_amt'=>number_format($row->allowed_amt - $row->today_consumed,2),
							'rdate'=>$report_date_frmted,
							'tdate'=> $report_date_toted
							);
				 }
				 
					//$consumed_table_temp
					
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] = date('d-M-Y',strtotime($this->input->post('todate')));
					$this->attendence_consumed_report($school_attendence,$report_date_frmted,$report_date_toted);
					die;
				}
				else{
					$data['rdate'] = $report_date_frmted;
					$data['tdate'] =  $report_date_toted;
					$data['attendencereport'] = $school_attendence;
				}

		}
		$data["module"] = "admin";
        $data["view_file"] = "school/attendencereports";
        echo Modules::run("template/admin", $data);
         
	}
	
	public function attendence_consumed_report($school_attendence,$report_date_frmted,$report_date_toted,$schools_info=array())
    {
		$school_rs = $this->db->query("select * from schools ");
		$schools_info = array();
		foreach($school_rs->result() as $schrow)
		{
			$schools_info[$schrow->school_code]['district'] = $schrow->district_name;
			$schools_info[$schrow->school_code]['region'] = $schrow->region;
			$schools_info[$schrow->school_code]['school_type'] = $schrow->school_type;
		}
		$data["schools_info"] = $schools_info;
		
		
		
		$extra_data = $school_attendence['extra'];
				unset($school_attendence['extra']);
          
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Attendance & Consumption Report');
               
                $this->excel->getActiveSheet()->setCellValue('A1', 'Attendance and Consumption Report  for month of  '.$months[$this->input->post('month')]." - ". $this->input->post('year')."  ( ".$extra_data['month_days']. ") days)");
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Category 1 Amount " );
				$this->excel->getActiveSheet()->setCellValue('B2',  $extra_data['student_prices']['gp_5_7']);	
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				$this->excel->getActiveSheet()->setCellValue('C2', " Category 2 Amount " );
				$this->excel->getActiveSheet()->setCellValue('D2',  $extra_data['student_prices']['gp_8_10']);	
				$this->excel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);				
				 
				
				$this->excel->getActiveSheet()->setCellValue('E2', " Category 3 Amount " );
				$this->excel->getActiveSheet()->setCellValue('F2',  $extra_data['student_prices']['gp_inter']);	
				$this->excel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);				
				 
				
				
				
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
				$this->excel->getActiveSheet()->setCellValue('R3', 'School Type');
				
			 
					 
			 
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
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data['name']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_code);
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $schools_info[$school_code]['region']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i,   $schools_info[$school_code]['district']);
					
					
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $school_data['grp1_att']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $school_data['group_1_per_day']);
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $school_data['cat1_amount']);
					
					
					$this->excel->getActiveSheet()->setCellValue('H'.$i, $school_data['grp2_att']);
					$this->excel->getActiveSheet()->setCellValue('I'.$i, $school_data['group_2_per_day']);
					$this->excel->getActiveSheet()->setCellValue('J'.$i, $school_data['cat2_amount']);
					
					$this->excel->getActiveSheet()->setCellValue('K'.$i, $school_data['grp3_att']);
					$this->excel->getActiveSheet()->setCellValue('L'.$i, $school_data['group_3_per_day']);
					$this->excel->getActiveSheet()->setCellValue('M'.$i, $school_data['cat3_amount']);
					
					
					$this->excel->getActiveSheet()->setCellValue('N'.$i,$school_data['attendence']);
					
					
					$this->excel->getActiveSheet()->setCellValue('O'.$i, $school_data['allowed_amt']);
					$this->excel->getActiveSheet()->setCellValue('P'.$i,   $school_data['consumed_amt']);
					$this->excel->getActiveSheet()->setCellValue('Q'.$i,   $school_data['remaining_amt']);
					$this->excel->getActiveSheet()->setCellValue('R'.$i,  $schools_info[$school_code]['school_type']);
					
					
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
