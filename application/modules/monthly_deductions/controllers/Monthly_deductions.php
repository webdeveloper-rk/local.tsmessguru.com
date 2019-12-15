<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Monthly_deductions extends MX_Controller {

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
		 $this->load->library('ci_jwt'); 
		 $this->load->model('common/common_model');
		 $this->load->config('config.php');
	}

    function index() {
     $data['display_result']  = false;
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
			$only_deducted =  intval($this->input->post('only_deducted')) ;
			if($tmonth<10)
					$tmonth = "0".$tmonth;
			$report_date = $tyear ."-". $tmonth."-01"; 
			
		 
		 
			$days_count =  $this->common_model->get_month_days($report_date);
			 
			 
			
			$school_attendence = array();
			 
			$report_date_frmted = $months[$tmonth];
			$report_date_toted =  $tyear ;
			
			$month_year =  "$tmonth-$tyear";
			 
			 $join_text = ' left join ';
			if($only_deducted ==1)
			{
				$join_text = " inner join  ";
			}
			 
				$deduction_rs = $this->db->query("select sc.school_id,sc.school_code,sc.name,IFNULL(deduction_amount,0 )  deduction_amount  from schools sc 
				$join_text
(SELECT school_id  , sum(amount) as deduction_amount FROM    dietpic_cheker  where     DATE_FORMAT( entry_date,  '%m-%Y' ) = ?  and  min_20='no' group by  school_id)
 dm on sc.school_id = dm.school_id $include_condition where  sc.is_school=1 and school_code !='85000'  ",array($month_year));			
				 // echo $this->db->last_query(); 
			 
				$data['deduction_rs']  = $deduction_rs;
				$data['display_result']  = true;
				
				$tot_rs = $this->db->query("SELECT   sum(amount) as monthly_deducted  FROM    
									dietpic_cheker dc inner join schools sc on sc.school_id=dc.school_id and sc.school_code !='85000'
									and DATE_FORMAT( entry_date,  '%m-%Y' ) = ?  and  min_20='no'  ",array($month_year));	
				 $data['monthly_deducted'] = $tot_rs->row()->monthly_deducted ;
				 
				if($this->input->post('submit')=="Download Report")
				{
					 
					$this->deductions_report($data);
					die;
				}
			 
 

		}
		 
		$data["module"] = "monthly_deductions";
        $data["view_file"] = "monthly_deductions";
        echo Modules::run("template/admin", $data);
         
	}
	
	
	
	public function deductions_report($rdata)
    {
		 
		 
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Monthly Deduction Report');
               
                $this->excel->getActiveSheet()->setCellValue('A1', 'Monthly Deduction Report  for month of  '.$months[$this->input->post('month')]." - ". $this->input->post('year') );
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
				$this->excel->getActiveSheet()->setCellValue('A3', 'School Code');
				$this->excel->getActiveSheet()->setCellValue('B3', 'School Name');		
				 $this->excel->getActiveSheet()->setCellValue('C3', 'Deduction Amount');
						 
				
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:S3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                //$this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				foreach($rdata['deduction_rs']->result() as  $school_data){ 
				 
		 	 if($school_data->school_code == "85000" || $school_data->school_code == "" || $school_data->deduction_amount ==0 )
					continue;
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data->school_code);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_data->name  );
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_data->deduction_amount );
				 
					 
					
					 
					$i++;$sno++;
				}
 
					 
				 
                
              
                $filename='monthly_deductions_report_'.date('d-M-Y')	.'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
                 
    }
	 
	

	function viewdeductions_popup($encoded_id='') {
		$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May", 
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
 

       
			$decoded_data = $this->ci_jwt->jwt_web_decode($encoded_id);	 
			$month =  $decoded_data->month;
			$year =  $decoded_data->year;
			$school_id =  $decoded_data->school_id;
			$sc_data = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
			$school_name = $sc_data->school_code."-".$sc_data->name;
			
			$month_year = $month."-".$year;
			$deduction_rs = $this->db->query("select dc.*,item_name, DATE_FORMAT( entry_date,  '%d-%m-%Y' ) as edate  FROM dietpic_cheker dc inner join items it on it.item_id=dc.item_id  where  school_id=? and DATE_FORMAT( entry_date,  '%m-%Y' ) = ?  and  min_20='no' order by  entry_date asc  ",array($school_id,$month_year));			
			//echo $this->db->last_query();
			$data['month_year_text'] = $months[$month]."-".$year;
			$data['year'] =$year;
			$data['deduction_rs'] =$deduction_rs;
			$data['school_name'] =$school_name;
			$this->load->view("school_deduction_list",$data);


			
		}
}
