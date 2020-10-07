<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Monthly_consolidated_tsmess_free_distribution extends MX_Controller {

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
		 $this->load->model('common/common_model');
		 $this->load->config('config.php');
	}

    function index() {
     
		 
			
				 
			 
				$free_distributions = "   SELECT  sc.school_id as school_id,sc.name,sc.school_code,sc.district_name,
						TRUNCATE(sum(quantity * price ),2) as total_distributed  
						  from free_distributions fd inner join schools sc on sc.school_id=fd.school_id  where school_code!='85000' group by school_id "; 
			 $free_distributions_rs = $this->db->query($free_distributions); 
			 
				 
				$data['free_distributions_rs']  = $free_distributions_rs; 
				 

				
				 
				if($this->input->get('download')=="download")
				{
					 
					$this->attendence_consumed_report($free_distributions_rs);
					die;
				}
				 
		 
		 
		 
		$data["module"] = "monthly_consolidated_tsmess_free_distribution";
        $data["view_file"] = "monthly_consolidated";
        echo Modules::run("template/admin", $data);
         
	}
	
	
	
	public function attendence_consumed_report($free_distributions_rs)
    {
		 
	 
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Item Distribution Report');
               
                $this->excel->getActiveSheet()->setCellValue('A1', '  Item Distribution Report');
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

				$this->excel->getActiveSheet()->getStyle('A3:AZ3')->applyFromArray( $style_header );

				
 			
				 
				
				
				
				////////////////////////////////////////////////////////////////////////////////////////////////
				$this->excel->getActiveSheet()->setCellValue('A3', 'School Name');
				$this->excel->getActiveSheet()->setCellValue('B3', 'School Code'); 
				$this->excel->getActiveSheet()->setCellValue('C3', 'District');				
				 
				$this->excel->getActiveSheet()->setCellValue('D3', 'Distribution Amount');  
						
					 
				
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:S3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                
				
				$total_amount = 0;
				$i=4;
				 foreach( $free_distributions_rs->result() as $school_data){ 
				 	$total_amount = 	$total_amount + $school_data->total_distributed; 
				 
				 
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data->name);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_data->school_code   );
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_data->district_name );
					$this->excel->getActiveSheet()->setCellValue('D'.$i,   $school_data->total_distributed);
					
					
					 
					  $this->excel->getActiveSheet()->getStyle('S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					 
					$i++;$sno++;
				}
	 
				 
                
              
                $filename='Item_distribution_report_'.date('d-M-Y')	.'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
                 
    }
	function schoolview($school_id)
	{
		
		$data['items'] = $items = $this->db->query("select fd.*,date_format(entry_date,'%d-%M-%Y') as entry_date_dp,it.item_name  from free_distributions fd inner join items it on it.item_id = fd.item_id where school_id=? order by entry_date asc ",array($school_id));				
		$data['school_id'] = $school_id;
		$school_info = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
		$data['school_info'] = $school_info;
		$data["module"] = "monthly_consolidated_tsmess_free_distribution";
        $data["view_file"] = "school_distributed_items";
        echo Modules::run("template/admin", $data);
		
		//school_distributed_items
	}
	 
	

	
}
