<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Itewise_consumption_dailyentries extends MX_Controller {

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
		$this->load->library("excel");  
		$this->load->model('common/common_model');

	}
	function index()
	{
  
  
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		$this->form_validation->set_rules('type', 'Report Type  ', 'required|in_list[state,district,school]');  
		$this->form_validation->set_rules('from_date', 'From Date ', 'required');   
		$this->form_validation->set_rules('to_date', 'To Date ', 'required');   
		$this->form_validation->set_rules('item_id', 'Item ', 'required|numeric');   
		 
		if($this->input->post('type') == "district")
			$this->form_validation->set_rules('district_id', 'District ', 'required|numeric|greater_than[0]');  
		if($this->input->post('type') == "school")
			$this->form_validation->set_rules('school_id', 'School ', 'required|numeric|greater_than[0]');  
		 
		if($this->form_validation->run() == true    )
		{
			$data['district_id']=   $district_id = intval($this->input->post('district_id'));
			$data['school_id']=    $school_id = intval($this->input->post('school_id')); 
			$data['item_id']=    $item_id = intval($this->input->post('item_id')); 
			 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			$data['display_result'] = true ;						
		 
			 
			 
			$data['from_date']=  $from_date =   date('Y-m-d',strtotime( $this->input->post('from_date')));
			$data['to_date']=  $to_date =   date('Y-m-d',strtotime( $this->input->post('to_date')));
			
			 
			
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
			 
			$db_from_date = date('Y-m-d',strtotime($data['from_date']));
			$db_to_date = date('Y-m-d',strtotime($data['to_date']));
			
			
			$balance_sheet_table = $this->common_model->get_stock_entry_table($db_from_date );
			
			
			      $sql  = "select 
								 bs.school_id,
								 (session_1_qty+session_2_qty+session_3_qty+session_4_qty) as consumed_quantity,
								 (  (session_1_qty * session_1_price) +
										(session_2_qty * session_2_price) +
										(session_3_qty * session_3_price) +
										(session_4_qty * session_4_price)  
										) as amount,
								date_format(entry_date,'%d-%M-%Y') as entry_date
							from $balance_sheet_table  bs inner join schools sc on sc.school_id = bs.school_id  where  (entry_date between ? and ? )   $condition 
							and item_id=? and (session_1_qty+session_2_qty+session_3_qty+session_4_qty)>0 order by school_code,entry_date asc ";
			$rdata['rset'] = $this->db->query($sql,array($db_from_date,$db_to_date,$item_id));	
			
			$items_rs = $this->db->query("select concat(telugu_name,'-',item_name) as name from items where item_id='$item_id'")->row();
			 //print_a($items_rs,0);
			$rdata['item_name'] = $items_rs->name;
			$rdata['month_name'] = 
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$this->download_consumption_dailyreport($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
		 
		 $data["schools_list"] = $schools_rs = $this->db->query("select * from  schools where is_school='1' and school_code not like '%85000%'order by school_code asc ");
		 $school_names_codes = array();
		 foreach($schools_rs->result() as $sch_row){
			 $school_names_codes[$sch_row->school_id] = array('code'=>$sch_row->school_code,'name'=>$sch_row->name);
		 }
		 $data['school_names_codes'] = $school_names_codes;
		 
		 
		 
        $data["item_list"] = $this->db->query("select * from  items where status='1'");
        
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["school_code"] = "";
        
		$data["display_dates"] = date('d-m-Y',strtotime($data['from_date']))." - ".date('d-m-Y',strtotime($data['to_date']));
			 
		
		
        $data["module"] = "itewise_consumption_dailyentries";
        $data["view_file"] = "itemwise_consumption_daily_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_consumption_dailyreport($rdata)
    {
		
		 $schools_rs = $this->db->query("select * from  schools where is_school='1'");
		 $school_names_codes = array();
		 foreach($schools_rs->result() as $sch_row){
			 $school_names_codes[$sch_row->school_id] = array('code'=>$sch_row->school_code,'name'=>$sch_row->name);
		 }
		 
		 
				//print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Consumption Report ';
				$headtitle = 'Consumption Report '.$rdata['item_name']."-".$rdata['month_name']."-".$rdata['report_for'];
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Date " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'School Code ');	
				$this->excel->getActiveSheet()->setCellValue('C2',  'Name');	
				$this->excel->getActiveSheet()->setCellValue('D2',  'Consumed Quantity');	
				$this->excel->getActiveSheet()->setCellValue('E2',  'Consumed Amount');	
				 
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$consumed_total = 0;
				$consumed_total_amount = 0;
				foreach($rdata['rset']->result()  as  $purchase_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $purchase_data->entry_date);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_names_codes[$purchase_data->school_id]['code']);
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_names_codes[$purchase_data->school_id]['name']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $purchase_data->consumed_quantity);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $purchase_data->amount);
					 
					 $consumed_total = $consumed_total + $purchase_data->consumed_quantity;
					 $consumed_total_amount = $consumed_total_amount + $purchase_data->amount;
					 
					 
					$i++;$sno++;
				}
	 
				 $this->excel->getActiveSheet()->setCellValue('C'.$i, 'Total  ');
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $consumed_total);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $consumed_total_amount);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':S'.$i)->getFont()->setBold(true);
                
              
                $filename=$headtitle .date('d-M-Y')	.'.xls'; //save our workbook as this file name
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
