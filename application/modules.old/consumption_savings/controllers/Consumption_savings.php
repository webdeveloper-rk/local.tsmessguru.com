<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0); 
 date_default_timezone_set('Asia/Kolkata');
class Consumption_savings extends MX_Controller {

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
		
 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		
		$this->form_validation->set_rules('type', 'Report Type  ', 'required|in_list[state,district,school]');  
		$this->form_validation->set_rules('savings_date', 'Date ', 'required');   
		 
		if($this->input->post('type') == "district")
			$this->form_validation->set_rules('district_id', 'District ', 'required|numeric|greater_than[0]');  
		if($this->input->post('type') == "school")
			$this->form_validation->set_rules('school_id', 'School ', 'required|numeric|greater_than[0]');  
		 
		if($this->form_validation->run() == true    )
		{
			$data['district_id']=   $district_id = intval($this->input->post('district_id'));
			$data['school_id']=    $school_id = intval($this->input->post('school_id')); 
			 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			$data['display_result'] = true ;						
		 
			 
			 
			$data['savings_date']=  $savings_date =   date('Y-m-d',strtotime( $this->input->post('savings_date')));
			
			$data['rdate_display'] =  date('d-m-Y',strtotime( $this->input->post('savings_date')));
			
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			if($type=="district")
			{
					$condition = " and bs.district_id='$district_id' ";
					$district_rs = $this->db->query("select  name from districts where district_id=?" ,array($district_id))->row();
					$report_for =  " District - ".$district_rs->name;;
					
			}
			else if($type=="school")
			{
					$condition = " and bs.school_id='$school_id' ";
					$school_rs = $this->db->query("select  name,school_code from schools where school_id=?",array($school_id))->row();
					$report_for =  " School - ". $school_rs->school_code."-".$school_rs->name;;
			}
			else {
				$report_for = $this->config->item('society_name');
			}
			 
			 
			 $balance_sheet_table = $this->common_model->get_stock_entry_table($savings_date);
			 
			 
			  $sql  = "select sc.name,sc.school_code,bs.school_id,sum(round(((session_1_qty*session_1_price) +
										(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+
										(session_4_qty*session_4_price)),2)) as consumed_total
					from $balance_sheet_table bs inner join schools sc on sc.school_id = bs.school_id  
					and sc.is_school=1 and sc.school_code not like '%85000%' where
					bs.entry_date=? $condition group by bs.school_id order by school_code asc ";
			$consumed_report = $this->db->query($sql,array($savings_date));	
			
			//echo $this->db->last_query();die;
			
			$school_data = array();
			foreach( $consumed_report->result() as $crow)
			{
				$school_data[$crow->school_id]['consumed_total'] = $crow->consumed_total;
				$school_data[$crow->school_id]['school_code'] = $crow->school_code;
				$school_data[$crow->school_id]['name'] = $crow->name;
				$school_data[$crow->school_id]['eligible_amt'] = 0;
			}
			 
			
			$days_sql = "SELECT DAY( LAST_DAY(? ) ) as days";
			$days_row  = $this->db->query($days_sql,array($savings_date))->row();
			$days_count = $days_row->days ;
		  
		  
			// $gsql ="SELECT * FROM `group_prices`";
			// $rs = $this->db->query($gsql);
			
			$price_sql = "select * from group_prices  where category='normal' and ? between start_date and end_date";
			$price_rs = $this->db->query($price_sql,array( $savings_date));
		
			 $group_prices = array("group_1"=>0,"group_2"=>0,"group_3"=>0);
			 foreach($price_rs->result() as $row)
			 {
				 if($row->group_code == "gp_5_7")
				 {
					 $group_prices["group_1"] = $row->amount/$days_count;
				 }
				 else if($row->group_code == "gp_8_10")
				 {
					 $group_prices["group_2"] = $row->amount/$days_count;
				 }
				 else if($row->group_code == "gp_inter")
				 {
					 $group_prices["group_3"] = $row->amount/$days_count;
				 }
				 
			 }
			 
			   $sql  = "select school_id, 
										round(
											( cat1_attendence * ". $group_prices["group_1"]. " ) +   
											(cat2_attendence * ". $group_prices["group_2"]. " ) +   
											(cat3_attendence * ". $group_prices["group_3"]. " ) 
										,2) as eligible_amt 
					from school_attendence  where  entry_date=?  group by school_id";
			  $attendence_report = $this->db->query($sql,array($savings_date));	
			
			
			 
			 foreach( $attendence_report->result() as $arow)
			{
				if(isset( $school_data[$arow->school_id])){
						  $school_data[$arow->school_id]['eligible_amt'] = $arow->eligible_amt;
						  
						  if(isset($school_data[$arow->school_id]['consumed_total'])){
								$school_data[$arow->school_id]['savings'] = floatval($arow->eligible_amt -  $school_data[$arow->school_id]['consumed_total']);
						  }
				}
			  
			}
			//print_a( $school_data);
			 
			
			$rdata['school_data'] = $school_data;
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$rdata['rdate_display'] = $data['rdate_display'];
				$this->download_savings_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 $data['school_data'] = $school_data;
			 
		 }
        $data["item_list"] = $this->db->query("select * from  items where status='1'");
        $data["schools_list"] = $this->db->query("select * from  schools where is_school='1' and school_code not like '%85000%' order by school_code asc ");
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["school_code"] = "";
        
        $data["module"] = "consumption_savings";
        $data["view_file"] = "savings_consumption_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_savings_report($rdata)
    {
				// print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = ' Savings Report   ';
				$headtitle = 'Savings Report - '.$rdata['rdate_display']."-".$rdata['report_for'];
				 
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Code " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'Name');	
				$this->excel->getActiveSheet()->setCellValue('C2',  'Consumption Amount');	
				$this->excel->getActiveSheet()->setCellValue('D2',  'Allowed Amount');	
				$this->excel->getActiveSheet()->setCellValue('E2',  'Savings Amount');	
				 		
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$consumed_total = 0;
				foreach($rdata['school_data']  as  $school_id => $sdata){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $sdata['school_code']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $sdata['name']);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $sdata['consumed_total']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $sdata['eligible_amt']);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $sdata['savings']); 
					 
					 
					 
					 
					$i++;$sno++;
				}
	 
				 
              
                $filename=$headtitle .date('d-M-Y')	.'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
				ob_end_clean();ob_start();
                $objWriter->save('php://output');
                 
    }
	/*********************************************************************************/
	
}
