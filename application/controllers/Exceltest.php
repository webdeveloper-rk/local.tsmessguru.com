<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Exceltest extends CI_Controller {
 
    public function __construct() {
            parent::__construct();
            $this->load->library('excel');
        }
 
        public function index()
    {
          
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', 'APSWRSCHOOL[B],name of school');
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:Q1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'DIET EXPENDITURE STATEMENT FOR dates between ');
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:Q2');
				
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
				
				
				
				
				$this->excel->getActiveSheet()->setCellValue('C3', 'OPENING BALANCE ');
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('C3:E3');
				
				$this->excel->getActiveSheet()->setCellValue('F3', 'PURCHASE BALANCE');
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('F3:H3');
				
				
				
				
				$this->excel->getActiveSheet()->setCellValue('I3', 'TOTAL');
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('I3:J3');
				
				
				$this->excel->getActiveSheet()->setCellValue('K3', 'CONSUMPTION');
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('K3:L3');
				
				
				$this->excel->getActiveSheet()->setCellValue('O3', 'CLOSING BALANCE');
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('O3:q3');
				
				
				
				
                 
                
                
				
				
       for($col = ord('A'); $col <= ord('C'); $col++){
                //set column dimension
                $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
                 //change the font size
                $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                 
                $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
                //retrive contries table data
         /*       $rs = $this->db->get('countries');
                $exceldata="";
        foreach ($rs->result_array() as $row){
                $exceldata[] = $row;
        }*/
		for($i=0;$i<11;$i++){
                $exceldata[] = array(1,2,4);
        }
                //Fill data 
                $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A4');
                 
                $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                 
                $filename='PHPExcelDemo.xls'; //save our workbook as this file name
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
 
/* End of file welcome.php */
/* Location: ./application/controllers/home.php */