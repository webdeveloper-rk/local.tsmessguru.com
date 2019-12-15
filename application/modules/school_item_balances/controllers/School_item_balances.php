<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class School_item_balances extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "subadmin" &&  $this->session->userdata("school_code") != "dsapswreis")
					{
						redirect("admin/login");
							die;
					}
		}
		$this->load->helper('url'); 
		 
		 $this->load->library('excel');
		 $this->load->library('ci_jwt');
		  $this->load->model('common/common_model');
	}

    function index() {
         		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		$cyear	= date('Y');	
		$this->form_validation->set_rules('school_id', 'School', 'required|numeric|greater_than[0]');              
		 
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2017]|less_than_equal_to['.$cyear .']');  

									
		 if($this->form_validation->run() == true)
		 {
			 
			$data['school_id']=    $school_id = intval($this->input->post('school_id'));
			$data['item_id']=    $item_id = intval($this->input->post('item_id'));
			 
			
			$data['year']=    $year = intval($this->input->post('year')); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			
			 $month = intval($this->input->post('month'));
			 if($month<10)
					$month = "0".$month;
			 
			 $data['month']=    $month  ;
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			$condition = " and school_id='$school_id' ";
			$school_rs = $this->db->query("select  name,school_code from schools where   school_id=? ",array($school_id))->row();
			$report_for =  $school_rs->school_code."-".$school_rs->name;;
			 
			 $balance_sheet_table = $this->common_model->get_stock_entry_table($year."-".$month."-01");
			 
			 //get max date from balance sheet get closing balance
			 $crs = $this->db->query("select max(entry_date)as max_date from $balance_sheet_table
											where  school_id=? and  month(entry_date)=? and YEAR(entry_date)=? ",array($school_id,$month,$year));
			 $close_rs_date = $crs->row()->max_date;
			 
			   $closing_sql    = "select  item_id,closing_quantity 	 from $balance_sheet_table where   school_id=?  and entry_date =?    ";
											
			 $close_rset = $this->db->query($closing_sql,array($school_id,$close_rs_date));	
			 $items_report = array();
			 foreach($close_rset->result() as $row)
			 {
				 $items_report[$row->item_id]['closing_balance']  = $row->closing_quantity;
			 }
			 
			 
			
			$opening_date  = $year."-".$month."-01";		
			$opening_sql    = "select 	 item_id,opening_quantity 								 
											from $balance_sheet_table where  school_id=? and  entry_date =? ";
							
			$open_rset = $this->db->query($opening_sql,array($school_id,$opening_date)); 
			 foreach($open_rset->result() as $row)
			 {
				 $items_report[$row->item_id]['opening_quantity']  = $row->opening_quantity;
			 }
			 
			
			
			$purchase_consumption_sql   = "select 	bs.item_id,  
								 sum(purchase_quantity) as purchase_quantity,
								sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as consumed_quantity  from $balance_sheet_table bs 
							where   school_id=? and month(entry_date)=? and YEAR(entry_date)=?     group by bs.item_id  ";
			 
			$pu_con_rset = $this->db->query($purchase_consumption_sql,array($school_id,$month,$year)); 
			
			 
			
			 foreach($pu_con_rset->result() as $row)
			 {
				 $items_report[$row->item_id]['purchase_quantity']  = $row->purchase_quantity;
				 $items_report[$row->item_id]['consumed_quantity']  = $row->consumed_quantity;
			 }
			 
			$item_sql    = "select 	item_id, concat(telugu_name,'-',item_name) as item_name  from items where  status='1'   ";
							
			$items_rset = $this->db->query($item_sql); 
			 foreach($items_rset->result() as $row)
			 {
				 $items_report[$row->item_id]['item_name']  = $row->item_name;
			 }
			 
			 
			$filtered_items = array();
			foreach($items_report as $item_id=>$item_details)
			{
				if($item_details['closing_balance'] !=0)
				{
					$filtered_items[$item_id] = $item_details;
					$filtered_items[$item_id]['closing_balance'] = $item_details['closing_balance'];
				}
			}
			 
			 
			 
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			$rdata['list'] = $filtered_items; 
			if($submit=="download")
			{ 
				$this->download_itembalance_report($rdata);
			}
			else  {
				 $data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
        //print_a($this->session->all_userdata());
		 
		if($this->session->userdata("is_dco")=="1"){
			//district_id
			$data["schools_list"] = $this->db->query("select * from  schools where is_school='1' and district_id=?",array($this->session->userdata("district_id"))); 
			
				        
		}
		else
		{
			
			$data["schools_list"] = $this->db->query("select * from  schools where is_school='1' and school_code not like '%85000%' order by school_code asc "); 
		}
        $data["module"] = "school_item_balances";
        $data["view_file"] = "itemwise_balance_report";
        echo Modules::run("template/admin", $data);
		
	}
		/********************************************************************
	
	
	********************************************************************/
	public function download_itembalance_report($rdata)
    {
				//print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Balance Report ';
				$headtitle = 'Items Balance Report -'.$rdata['month_name']."-".$rdata['report_for'];
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
				$this->excel->getActiveSheet()->setCellValue('B2',  'Opening Qty');	 				
				$this->excel->getActiveSheet()->setCellValue('C2',  'Purchase Qty');	 				
				$this->excel->getActiveSheet()->setCellValue('D2',  'Total Qty');	 				
				$this->excel->getActiveSheet()->setCellValue('E2',  'Consumed Qty');	 				
				$this->excel->getActiveSheet()->setCellValue('F2',  'Closing Balance');	 				
				 	
				$this->excel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);		 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$consumed_total = 0;
				foreach($rdata['list']   as $key=>$item_data){ 
				 
		 	 
					//print_a($item_data,1);
					$total_qty = $item_data['purchase_quantity'] +  $item_data['opening_quantity'];
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $item_data['item_name']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $item_data['opening_quantity']);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $item_data['purchase_quantity']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $total_qty);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $item_data['consumed_quantity']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $item_data['closing_balance']); 
					 
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
	
	
	
	
}
