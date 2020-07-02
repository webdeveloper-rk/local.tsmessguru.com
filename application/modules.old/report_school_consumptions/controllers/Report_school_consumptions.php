<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_school_consumptions extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
				/*	if($this->session->userdata("user_role") != "subadmin")
					{
						redirect("admin/login");
							die;
					}*/
					
					
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
		$this->load->library("excel");
		$this->load->model('common/common_model');		
	}
	function index()
	{
		   
 
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		

		$cyear = date('Y');
		  

					if($this->session->userdata("user_role") != "school")
					{
							$this->form_validation->set_rules('school_id', 'School ', 'required|numeric');   
					}				
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2017]|less_than_equal_to['.$cyear .']');  
		if($this->config->item("site_name")=="apsocial"){ 
			$this->form_validation->set_rules('report_type', 'Report Type ', 'required');  
		} 
		 
		if($this->form_validation->run() == true )
		{
		//	print_a($_POST);
			 $data['display_result'] = true ;
			 
			 
					if($this->session->userdata("user_role") != "school")
					{
							$data['school_id']=    $school_id =  intval($this->input->post('school_id'));  
					}	else{
						
						$data['school_id']=    $school_id =  intval($this->session->userdata('school_id'));  
					}
					
					
			
			 $month = intval($this->input->post('month'));
			 if($month<10)
					$month = "0".$month;
			 
			$data['month']=    $month;
			$data['year']=   $year =  intval($this->input->post('year')); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			if($this->config->item("site_name")=="apsocial"){ 
				$data['report_type']=    $report_type = $this->input->post('report_type'); 
				} else { 
				
					$data['report_type']=    $report_type = '';
				}
			
			 if($report_type=="local")
			 {
				$report_type_condition = " and consumption_type='local'  ";
				$report_type_title = " LOCAL ITEMS ";
			 }
			 else  if($report_type=="central")
			 {
				$report_type_condition = " and consumption_type='central'  ";
				$report_type_title = " CENTRAL ITEMS ";
			 }
			 else {
				$report_type_condition = "   ";//both 
				$report_type_title = " ALL ITEMS ";
			 }
			 
			 
			
			$data['type']=    $type = 'school'; 
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			 
			$condition = " ";
			$school_rs = $this->db->query("select  name,school_code from schools where   school_id=? ",array($school_id))->row();
			$report_for =  $school_rs->school_code."-".$school_rs->name  ." - ".$months[$month]."-".$year . " - ". $report_type_title;
			 
			 $report_date = $year."-".$month."-01";
			 $balance_sheet_table = $this->common_model->get_stock_entry_table($report_date);
			 
			
			  $sql  = "select  bs.item_id,it.item_name,it.telugu_name,
								sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as consumed_quantity,
								sum(session_1_qty*session_1_price +session_2_qty * session_2_price +session_3_qty * session_3_price +session_4_qty * session_4_price) as consumed_price 								 
								from $balance_sheet_table bs inner join items it on bs.item_id= it.item_id where  DATE_FORMAT(entry_date, '%Y-%m')=?  and school_id=?   $report_type_condition 
								group by DATE_FORMAT(entry_date, '%Y-%m')  ,bs.item_id having  consumed_quantity>0";
			$rdata['rset'] = $this->db->query($sql,array("$year-$month",$school_id));	
			//echo $this->db->last_query();
			
		 
		 
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$this->download_consumption_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
        $data["item_list"] = $this->db->query("select * from  items where status='1'");
        $data["schools_list"] = $this->db->query("select * from  schools where is_school='1'");
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["school_code"] = "";
        
        $data["module"] = "report_school_consumptions";
        $data["view_file"] = "school_consumptions_itemwise_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	private function download_consumption_report($rdata)
    {
				 //print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Consumption  Report ';
				$headtitle = 'Consumption Report of '. $rdata['report_for'];
                $this->excel->getActiveSheet()->setTitle( );
               
                $this->excel->getActiveSheet()->setCellValue('A1', $headtitle);
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

				$this->excel->getActiveSheet()->getStyle('A2:Z2')->applyFromArray( $style_header );

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Item Name " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'Consumed Quantity in kgs');	
				$this->excel->getActiveSheet()->setCellValue('C2',  'Consumed Amount');	
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);				
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$total_consumed = 0;
				foreach($rdata['rset']->result()  as  $consumed_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $consumed_data->telugu_name."-".$consumed_data->item_name);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, number_format($consumed_data->consumed_quantity,3) );
					$this->excel->getActiveSheet()->setCellValue('C'.$i, number_format($consumed_data->consumed_price,2));
					
					$total_consumed  =  $total_consumed  + $consumed_data->consumed_price;
					 
					 
					$i++;$sno++;
				}
	
				 $this->excel->getActiveSheet()->setCellValue('B'.$i, 'Total Consumed');
					$this->excel->getActiveSheet()->setCellValue('C'.$i, number_format($total_consumed,2));
					$this->excel->getActiveSheet()->getStyle('A'.$i.':S'.$i)->getFont()->setBold(true);
               
             
                $filename=$headtitle .date('d-M-Y')	.'.xls'; //save our workbook as this file name
                
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
				
				header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                 $objWriter->save('php://output'); /**/  
                 
    }
	/************************************************************************************/
	
	
	
	
	
}
