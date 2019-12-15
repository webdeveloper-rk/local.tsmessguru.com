<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_schoolwise_consumption extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
		//print_a($this->session->all_userdata(),1);
		if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					 			
					if($this->session->userdata("user_role") != "subadmin" &&  $this->session->userdata("school_code") != "dsapswreis")
					{
						redirect("admin/login");
							die;
					}
		$this->load->helper('url');  
		$this->load->config("config.php");  
		$this->load->library("ci_jwt");  
		$this->load->library("excel");  
	}
	function index()
	{
 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		$this->form_validation->set_rules('type', 'Report Type  ', 'required|in_list[state,district,school]');  
		$this->form_validation->set_rules('month', 'Month ', 'required|numeric|greater_than[0]|less_than_equal_to[12]');  
		$this->form_validation->set_rules('year', 'Year ', 'required|numeric|greater_than_equal_to[2017]|less_than_equal_to['.date('Y') .']');  
		$this->form_validation->set_rules('item_id', 'Item ', 'required|numeric|greater_than[0]');  
		if($this->input->post('type') == "district")
			$this->form_validation->set_rules('district_id', 'District ', 'required|numeric|greater_than[0]');  
		if($this->input->post('type') == "school")
			$this->form_validation->set_rules('school_id', 'School ', 'required|numeric|greater_than[0]');  
		 
		if($this->form_validation->run() == true    )
		{
			$data['district_id']=   $district_id = intval($this->input->post('district_id'));
			$data['school_id']=    $school_id = intval($this->input->post('school_id'));
			$data['item_id']=    $item_id = intval($this->input->post('item_id'));
			 
			$data['month']=    $month = intval($this->input->post('month'));
			$data['year']=    $year = intval($this->input->post('year')); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			 $data['display_result'] = true ;
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			if($type=="district")
			{
					$condition = " and district_id='$district_id' ";
					$district_rs = $this->db->query("select  name from districts where district_id=?",array($district_id));
					if($district_rs->num_rows()==0)
					{
						redirect(current_url());
					}
					$report_for =  $district_rs->row()->name;;
					
			}
			else if($type=="school")
			{
					$condition = " and school_id='$school_id' ";
					$school_rs = $this->db->query("select  name,school_code from schools where school_id=?",array($school_id));
					if($school_rs->num_rows()==0)
					{
						redirect(current_url());
					}
					
					$report_for =  $school_rs->row()->school_code."-".$school_rs->row()->name;;
			}
			else {
				$report_for = $this->config->item('society_name');
			}
			$items_rs = $this->db->query("select concat(telugu_name,'-',item_name) as name from items where item_id=?",array($item_id));
			 if($items_rs->num_rows()==0)
					{
						redirect(current_url());
					}
							$sql  = "
							select sc.name,sc.school_code,sc.district_name,bs.consumed_quantity,bs.consumed_amount from schools sc  inner join  
							
							(select school_id,
												sum(session_1_qty + session_2_qty +session_3_qty +session_4_qty ) as consumed_quantity,
												sum( 	(session_1_qty * session_1_price) + 
														(session_2_qty * session_2_price ) +
														 (session_3_qty * session_3_price)+
														 (session_4_qty *session_1_price)
												  ) as consumed_amount,
												 
												from balance_sheet where 
												month(entry_date)=? and YEAR(entry_date)=?  $condition and item_id=?
												group by school_id) bs  on bs.school_id = sc.school_id    ";
			$rdata['rset'] = $this->db->query($sql,array($month,$year,$item_id));	
			
			$items_rs = $this->db->query("select concat(telugu_name,'-',item_name) as name from items where item_id=?",array($item_id))->row();
			 //print_a($items_rs,0);
			$rdata['item_name'] = $items_rs->name;
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$this->download_consumed_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
        $data["item_list"] = $this->db->query("select * from  items where status='1'");
        $data["schools_list"] = $this->db->query("select * from  schools where is_school='1'  and school_code not like '%85000%' order by school_code asc ");
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["school_code"] = "";
        
        $data["module"] = "report_schoolwise_consumption";
        $data["view_file"] = "itemwise_report";
        echo Modules::run("template/admin", $data);
    }
		/********************************************************************
	
	
	********************************************************************/
	public function download_consumed_report($rdata)
    {
				 
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " School name" );
				$this->excel->getActiveSheet()->setCellValue('B2', " School code" );
				$this->excel->getActiveSheet()->setCellValue('C2', " District name" );
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
				$purchased_total = 0;
				foreach($rdata['rset']->result()  as  $consumed_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $consumed_data->name);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $consumed_data->school_code);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $consumed_data->district_name);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $consumed_data->consumed_quantity);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $consumed_data->consumed_amount);
					 
					 $purchased_total = $purchased_total + $consumed_data->purchase_quantity;
					 
					 
					$i++;$sno++;
				}
	 
					$this->excel->getActiveSheet()->setCellValue('D'.$i, 'Total Consumed');
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $purchased_total);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':S'.$i)->getFont()->setBold(true);
                
              
                $filename=$headtitle .date('d-M-Y')	.'.xls'; //save our workbook as this file name
             
			   header('Content-Type: application/vnd.ms-excel',true,200); //mime type
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
