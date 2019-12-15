<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Monthly_consolidated_dates extends MX_Controller {

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
					$secretary_codes = array('secretary','10100');
				/*	if ( !in_array($this->session->userdata("school_code"), $secretary_codes))  
					{
						redirect("admin/login");
							die;
					}
					*/
		}
		$this->load->helper('url'); 
		$this->load->model('admin/school_model');
		 $this->load->library('excel'); 
		 $this->load->model('common/common_model');
	}

     function index()
	{
		$this->form_validation->set_rules('fromdate', 'Date', 'required');              
		$this->form_validation->set_rules('todate', 'Date', 'required');              
		 
		$item_names   = array();
		$data = array();
		if($this->form_validation->run() == true    )
		{
			
			$from_month = date('m',strtotime($this->input->post('fromdate')));
			$to_month = date('m',strtotime($this->input->post('todate')));
			$to_year= date('Y',strtotime($this->input->post('todate')));
			if($from_month != $to_month)
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Dates should be with in the same month.</div>');
				redirect(current_url());
			}
			if(!chk_date_format($this->input->post('fromdate')))
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Date format. ex: mm/dd/YYYY</div>');
				redirect(current_url());
			}
			if(!chk_date_format($this->input->post('todate')))
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Date format. ex: mm/dd/YYYY</div>');
				redirect(current_url());
			}
			
			$dco_schools = array();
			if($this->session->userdata("is_dco")=="1"){
				$trs= $this->db->query("select school_id,district_id  from schools where district_id=?",array($this->session->userdata("district_id")));
				foreach($trs->result() as $trow)
				{
					$dco_schools[] = $trow->school_id;
				}
			
			}
			
			
			 
			$report_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
			$report_todate = date('Y-m-d',strtotime($this->input->post('todate')));
			$report_date_frmted = date('d-M-Y',strtotime($this->input->post('fromdate')));
			$report_date_toted = date('d-M-Y',strtotime($this->input->post('todate')));
			
			 $schools_rs = $this->db->query("select * from schools where is_school='1' and school_code not like '%85000%' ");
			 $schools_list = array();
			 foreach($schools_rs->result() as $row)
			 {
				$schools_list[$row->school_id] = array( 
							'school_code'=>$row->school_code,
							'name'=>$row->name,
							'strength'=>$row->strength,
							'attendence'=>'0', 
							'allowed_amt'=>'0',
							'district_id'=>$row->district_id,
							'consumed_amt'=>'0',
							'remaining_amt'=>'0',
							'rdate'=>$report_date_frmted,
							'tdate'=> $report_date_toted);
			 }
			  
			 $balance_sheet_table = $this->common_model->get_stock_entry_table($report_date);
			 $consumed_table_sql  = "  SELECT  school_id,
						TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + 
						(`session_2_qty` * `session_2_price`) + 
						(`session_3_qty` * `session_3_price`) + 
						(`session_4_qty` * `session_4_price`)  ),2)
						as total_consumed from $balance_sheet_table where (entry_date between ? and ?) group by school_id  ";
						
			$consumed_rs = $this->db->query($consumed_table_sql,array($report_date,$report_todate));
			foreach($consumed_rs->result() as $row)
			 {
				 
				$schools_list[$row->school_id]['consumed_amt'] =$row->total_consumed;
			 }


			
			 
			 $school_date = $to_year."-".$to_month."-01";
			$price_sql = "select * from group_prices  where category='normal' and ? between start_date and end_date";
			$price_rs = $this->db->query($price_sql,array($school_date)); 
			$student_prices = array();
			foreach($price_rs->result() as $stu_price){
				$student_prices[$stu_price->group_code] = $stu_price->amount;
			}
			
			$days_sql = "SELECT DAY( LAST_DAY(?) ) as days";
		$days_row  = $this->db->query($days_sql,array($school_date))->row();
		  $days_count = $days_row->days ;
			$group_1_per_day= $student_prices['gp_5_7']/$days_count;
			$group_2_per_day= $student_prices['gp_8_10']/$days_count;
			$group_3_per_day= $student_prices['gp_inter']/$days_count;
			
			$sql_attendence = "select school_id,
										truncate(sum(
											((cat1_attendence + cat1_guest_attendence) * $group_1_per_day) + 
											((cat2_attendence + cat2_guest_attendence)   * $group_2_per_day) + 
											((cat3_attendence+ cat3_guest_attendence)   * $group_3_per_day)  
										),2) as  allowed_amt ,
										sum(present_count) as attendence  from school_attendence where (entry_date between ? and ?) group by school_id ";
				 
				$allowed_rs = $this->db->query($sql_attendence,array($report_date,$report_todate));
			foreach($allowed_rs->result() as $row)
			 {
				$schools_list[$row->school_id]['attendence'] =$row->attendence;
				$schools_list[$row->school_id]['allowed_amt'] =$row->allowed_amt;
				$schools_list[$row->school_id]['remaining_amt'] =number_format($row->allowed_amt - $schools_list[$row->school_id]['consumed_amt'] ,2);
			 } 
				 
			$school_attendence = array();	
			foreach($schools_list as $sch_id=>$schdata)
			{
				if(count($dco_schools)>0 && !in_array($sch_id,$dco_schools))
				 {
					continue;
				 }else{
					 if($schdata['school_code']!=""){
								$school_attendence[$schdata['school_code']] = $schdata;
					 }
				 }
			}
				
				
				
				//$consumed_table_temp
				ksort($school_attendence);
				unset($school_attendence['85000']);
				
				if($this->session->userdata("is_dco") ==1)
					{
						$schools_rs =  $this->db->query("select * from schools where is_school=1 and district_id=?",array($this->session->userdata("district_id")));
					}
					else
					{
						$schools_rs = $this->db->query("select * from schools where is_school=1");						
					}
			
			$is_school_list = array();
			
				foreach($schools_rs->result() as $school_item){
					$is_school_list[]=  $school_item->school_code;
				}
				
				$school_attendence2 = $school_attendence;
			    foreach($school_attendence2  as $sccode=>$scdata)
				{
						if(!in_array($sccode,$is_school_list))
						{
						  unset($school_attendence[$sccode]);
						}
							
				}
				
				
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] = date('d-M-Y',strtotime($this->input->post('todate')));
					$this->attendence_consumed_report_between_dates($school_attendence,$report_date_frmted,$report_date_toted);
					die;
				}
				else{
					$data['rdate'] = $report_date_frmted;
					$data['tdate'] =  $report_date_toted;
					$data['attendencereport'] = $school_attendence;
				}

		}
		$data["module"] = "monthly_consolidated_dates";
        $data["view_file"] = "monthly_consolidated_dates";
        echo Modules::run("template/admin", $data);
         
	}
	/********************************************************************************************************* 
	
	
	*********************************************************************************************************/
		public function attendence_consumed_report_between_dates($school_attendence,$report_date_frmted,$report_date_toted,$schools_info=array())
    {
		 
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Attendance & Consumption Report');
               
                $this->excel->getActiveSheet()->setCellValue('A1', " Attendance and Consumption Report between Dates of $report_date_frmted and $report_date_toted");
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " School Name " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'School Code' );	
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				 
				$this->excel->getActiveSheet()->setCellValue('C2', " Attendence ");	
				$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);				
				 
				
				$this->excel->getActiveSheet()->setCellValue('D2', " Allowed Amount " );
				$this->excel->getActiveSheet()->setCellValue('E2',  'Consumption Amount');	
				$this->excel->getActiveSheet()->setCellValue('F2',  'Remaining Amount');	
				$this->excel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);				
				 
				
				 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                //$this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 3;
				$sno=1;
				$consumption_amount_total = 0;
				foreach($school_attendence as $school_code=>$school_data){ 
				 
		 	 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data['name']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_code);
					//$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_data['strength']);
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_data['attendence']);
					
					
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $school_data['allowed_amt']);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $school_data['consumed_amt']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $school_data['remaining_amt']); 
					
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
				ob_end_clean(); 
                $objWriter->save('php://output');
                 
    }


	
}
