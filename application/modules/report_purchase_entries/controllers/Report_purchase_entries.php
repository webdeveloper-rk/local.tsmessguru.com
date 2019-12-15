<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_purchase_entries extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "school")
					{
						redirect("admin/login");
							die;
					}
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
		$this->load->library("excel");  
			$this->load->model("common/common_model");  
	}
	function index()
	{
		   
		redirect(current_url()."/itewise_purchase_entries");
	
	}
    
		/*************************************************************************
	
	
	
	*****************************************************************************/
	function itewise_purchase_entries($type='state',$tval='0') {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		

		$cyear = date('Y');
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');              
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2017]|less_than_equal_to['.$cyear .']');  
		$this->form_validation->set_rules('item_id', 'Item ', 'required|numeric');  
		
		if($this->form_validation->run() == true )
		{
			 
			$data['school_id']=    $school_id =  intval($this->session->userdata("school_id"));
			$data['item_id']=    $item_id = intval($this->input->post('item_id')); 
			 $month = intval($this->input->post('month'));
			 if($month<10)
					$month = "0".$month;
			 
			$data['month']=    $month;
			$data['year']=    intval($year = $this->input->post('year')); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = 'school'; 
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			 
			$condition = " ";
			$school_rs = $this->db->query("select  name,school_code from schools where   school_id=? ",array($school_id))->row();
			$report_for =  $school_rs->school_code."-".$school_rs->name;;
			 
				    $stock_entry_table = $this->common_model->get_stock_entry_table($year."-".$month."-01");
			 
			  $sql  = "select  
								sum(purchase_quantity) as purchase_quantity,
								sum(purchase_price) as purchase_price,
								date_format(entry_date,'%d-%M-%Y') as entry_date
								from $stock_entry_table where month(entry_date)=? and
								YEAR(entry_date)=?  and school_id=? and item_id=?
								group by entry_date order by entry_date asc ";
			$rdata['rset'] = $this->db->query($sql,array($month,$year,$school_id,$item_id));	
			
			$items_rs = $this->db->query("select concat(telugu_name,'-',item_name) as name from items where item_id=?",array($item_id))->row();
			 //print_a($items_rs,0);
			$rdata['item_name'] = $items_rs->name;
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$this->download_purchase_report($rdata);
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
        
        $data["module"] = "report_purchase_entries";
        $data["view_file"] = "school_itemwise_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	private function download_purchase_report($rdata)
    {
				//print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Purchase Report ';
				$headtitle = 'Purchase Report '.$rdata['item_name']."-".$rdata['month_name']."-".$rdata['report_for'];
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
				$this->excel->getActiveSheet()->setCellValue('B2',  'Purchase Quantity');	
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$purchased_total = 0;
				foreach($rdata['rset']->result()  as  $purchase_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $purchase_data->entry_date);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $purchase_data->purchase_quantity);
					 
					 $purchased_total = $purchased_total + $purchase_data->purchase_quantity;
					 
					 
					$i++;$sno++;
				}
	 
				 $this->excel->getActiveSheet()->setCellValue('A'.$i, 'Total Purchased');
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $purchased_total);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':S'.$i)->getFont()->setBold(true);
                
              
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
	/************************************************************************************/
	
	
	
	
	
}
