<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Apsocial_indent extends MX_Controller {

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
			$this->load->model("common/common_model");  
		
		
		
	}
	function index()
	{
		 
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('INDENT Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name') );
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'INDENT STATEMENT FOR dates between Nov 10th 2018 - Dec 20th 2018');
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:H2');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
				
				
				
				 
				
				 
				$this->excel->getActiveSheet()->setCellValue('A3', 'School Code');				
				$this->excel->getActiveSheet()->setCellValue('B3', 'School Name');				
				$this->excel->getActiveSheet()->setCellValue('C3', 'Item Telugu name');
				$this->excel->getActiveSheet()->setCellValue('D3', 'Item English name ');
				$this->excel->getActiveSheet()->setCellValue('E3', 'Used Qty');	 
			 
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				
				$sql = "SELECT ind.school_id,sc.name school_name,sc.school_code,it.item_id,telugu_name,item_name ,used_qty FROM `nov_dec_indent` ind inner join items it on it.item_id=ind.item_id and it.indent=1 inner join schools sc on sc.school_id = ind.school_id  order by school_code ,item_name   ";
				$rs = $this->db->query($sql);
				$school_id = 0;
				foreach($rs->result() as $rowitem)
				{
					if($rowitem->school_code =="85000")
							continue;
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $rowitem->school_code);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $rowitem->school_name);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem->telugu_name);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem->item_name );
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $rowitem->used_qty );  
					$i++;
				}
	 
					 
				$this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getFont()->setBold(true);
                
              
                $filename='indent_report_nov10th_dec_20th_2018_report_'.date('d-m-Y H:i:s').'.xls'; //save our workbook as this file name
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
	
function state()
	{
		 
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('INDENT Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name') );
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'STATE INDENT STATEMENT FOR dates between Nov 10th 2018 - Dec 20th 2018');
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:H2');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				
				$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
				
				
			 			
				$this->excel->getActiveSheet()->setCellValue('A3', 'Item Telugu name');
				$this->excel->getActiveSheet()->setCellValue('B3', 'Item English name ');
				$this->excel->getActiveSheet()->setCellValue('C3', 'Used Qty');	 
			 
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				
				$sql = "SELECT  it.item_id,telugu_name,item_name ,sum(used_qty) total_qty FROM `nov_dec_indent` ind inner join items it on it.item_id=ind.item_id and it.indent=1 group by item_id   ";
				$rs = $this->db->query($sql);
				$school_id = 0;
				foreach($rs->result() as $rowitem)
				{
					 
				 
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $rowitem->telugu_name);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $rowitem->item_name );
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem->total_qty );  
					$i++;
				}
	 
					 
				$this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getFont()->setBold(true);
                
              
                $filename='state_indent_report_nov10th_dec_20th_2018_report_'.date('d-m-Y H:i:s').'.xls'; //save our workbook as this file name
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
