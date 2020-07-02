<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Indendent extends MX_Controller {

    function __construct() {
        parent::__construct(); 
         $this->load->config("config.php");
         $this->load->model("admin/school_model");
		
		
		$this->load->library("ci_jwt");
		$this->load->model("common/common_model");
		 $this->load->library('excel');
		 
		 //print_a($this->session->all_userdata(),1);
    }

   

    function index() {

	
	 $allowed_roles = array("school","subadmin","gcc");
		 if(!in_array($this->session->userdata("user_role"),$allowed_roles))
		{
				redirect("admin/general/logout"); 
		}
		$this->listindendent();

    }
	function metadata()
	{
		$month = date('m');
		$year = date('Y');
		if($month<10)
		{
			$month = "0".$month;
		}
		$sql = "select * from schools where is_school='1'" ;
		$rs = $this->db->query($sql);
		foreach($rs->result() as $rdata)
		{
			$school_id= $rdata->school_id;
			$strength =  $rdata->strength;
			$chkrs = $this->db->query("select * from indendent_sch_strength where school_id= ? and month=? and year=?",array($school_id,$month,$year));
			if($chkrs->num_rows()==0)
			{
				$ins_data = array('school_id'=>$school_id,'month'=>$month,'year'=>$year,'strength'=>$strength);
				$this->db->insert('indendent_sch_strength',$ins_data);
			}
		
		}
		echo "Done";
	
	
	}
	function listindendent()
	{
		
		 $allowed_roles = array("school","subadmin","gcc");
		 if(!in_array($this->session->userdata("user_role"),$allowed_roles))
		{
				redirect("admin/general/logout"); 
		}
		
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
									
		$schools_list = array();
		$data['school_selection'] = false;
		$data['display_result'] = false;
		if(in_array($this->session->userdata("user_role"),array("subadmin","gcc")))
		{
			$condition = '';
			$district_id= intval($this->session->userdata("district_id"));
			if($this->session->userdata("is_dco") ==1 || $this->session->userdata("user_role")=="gcc")
			{
				$condition = " and district_id='$district_id' ";
			}
			$schools_list = $this->db->query("select school_id,school_code,name from schools where is_school='1'  $condition  "); 
			$data['school_selection'] = true;
		}
		if($data['school_selection'] == true)
		{
			$this->form_validation->set_rules('school_id', 'School', 'required|numeric'); 
		} 
		$start_year = 2018;
		$end_year = date('Y') ;
		$this->form_validation->set_rules('month', 'Month', 'required|numeric|less_than_equal_to[12]|greater_than_equal_to[1]');  
		$this->form_validation->set_rules('year', 'Year', "required|numeric|less_than_equal_to[$end_year]|greater_than_equal_to[$start_year]");  
		
		$indendent_start_date = $this->config->item("indendent_start_date");
		if($this->form_validation->run() == true )
		{
			
			if($this->session->userdata("user_role")=="subadmin" || $this->session->userdata("user_role")=="gcc" )
			{
				$school_id = intval($this->input->post('school_id')); 
			}
			else
			{
				$school_id = intval($this->session->userdata("school_id"));
			}
			
			
			if($this->session->userdata("is_dco") ==1 || $this->session->userdata("user_role")=="gcc")
			{
				//print_a($this->session->all_userdata() );
				$dco_district_id = $this->session->userdata("district_id");
				
				$dco_school_info = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
			 
				//print_a($dco_school_info,1 );
				if($dco_school_info->district_id != $dco_district_id)
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger">Access Denied.</div>');
					redirect("indendent");
				}
			}
			
			$month = intval($this->input->post('month')); 
			if($month<10)
				$month="0".$month;
			$year = intval($this->input->post('year'));
			$date = $year."-".$month."-01";
			if($date == $this->config->item("indendent_start_date"))
			{
				$date = $year."-".$month."-09";
			}
				
			
			$datecheck_qry =  "select  ? between  ? and CURRENT_DATE  as isvalid "; 
			  $isvalid = $this->db->query($datecheck_qry ,array($date,$indendent_start_date))->row()->isvalid;
			 if($isvalid ==0)
			 {
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Data is not avilable for selected month and year .</div>');
				redirect("indendent");
			 }
			 
			$opening_rs = $this->db->query("select bs.item_id,concat(it.telugu_name,'-',it.item_name) as item_name,bs.opening_quantity,bs.opening_price,  bs.opening_quantity*bs.opening_price as opening_amount  from balance_sheet bs inner join items it 
												on bs.item_id=it.item_id and bs.entry_date=? and school_id=? order by item_name ",array($date,$school_id));
			$list = $opening_rs->result_object();	
			$schools_data = array();
			foreach($list as $key=>$scdata)
			{
				$schools_data[$scdata->item_id] = $scdata;
			}
			$number_of_days  = $this->db->query("select day(last_day('$date')) as last_day")->row()->last_day; 
			
			 
			
			
			
			
			$items_rs= $this->db->query("select 
indt.item_id,it.item_name ,sum(strength) as total_strength,
sum((grams * strength * ? )) as required_qty from 
( select scs.school_id,scs.class_id,item_id,grams,strength from 
school_class_strengths scs inner join class_indent_grams grams on scs.class_id = grams.class_id where  scs.school_id=? 
) as indt   inner join  items it on indt.item_id = it.item_id group by  item_id

 ",array($number_of_days,$school_id));
			foreach($items_rs->result() as $itemobj)
			{
 
				 
				$schools_data[$itemobj->item_id]->number_of_days  =  $number_of_days ;
 
				$schools_data[$itemobj->item_id]->school_strength  = $itemobj->total_strength  ;
				$schools_data[$itemobj->item_id]->monthly_required_qty   = $itemobj->required_qty  ;
				$schools_data[$itemobj->item_id]->balance_qty   = $itemobj->required_qty  - $schools_data[$itemobj->item_id]->opening_quantity;  
				 
			}
				$data['schools_data'] = $schools_data; 
				$data['month'] = $month; 
				$data['month_name'] =  $months[$month]; 
				$data['year'] = $year; 
				$data['school_info'] = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
				
				 
				if(strtolower(trim($this->input->post('submit')))=="download")
				{
					 
					$this->download_indent_report($data);
					 
					die;
				}
				
				
				
				$data['display_result'] = true;
			  
		}
		
	 
		$data['schools_list'] = $schools_list; 
		  
		$data["module"] = "indendent"; 
        $data["view_file"] = "indendent"; 
        echo Modules::run("template/admin", $data);
	}
	
	
	
	
	
function download_indent_report($data){

	   
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = "INDENT Report for ".ucfirst($data['month_name'])."-".$data['year']. " [ ".$data['school_info']->school_code."-".$data['school_info']->name." ] ";
                $this->excel->getActiveSheet()->setTitle("Indent Report");
               
                $this->excel->getActiveSheet()->setCellValue('A1',  $title);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
				
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

				 $this->excel->getActiveSheet()->getStyle('A1:Z1')->applyFromArray( $style_header );
				$this->excel->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);				
				 
				
				
				
				////////////////////////////////////////////////////////////////////////////////////////////////
				$this->excel->getActiveSheet()->setCellValue('A2', 'SNO');
				$this->excel->getActiveSheet()->setCellValue('B2', 'Item Name');		
				 
				$this->excel->getActiveSheet()->setCellValue('C2', 'No Of Days');
				$this->excel->getActiveSheet()->setCellValue('D2', 'Strength');
				$this->excel->getActiveSheet()->setCellValue('E2', 'Opening Balance Qty');
				
				$this->excel->getActiveSheet()->setCellValue('F2', 'Monthly Required Qty');
				$this->excel->getActiveSheet()->setCellValue('G2', 'Required Qty'); 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                //$this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 3;
				$sno=1;
				$consumption_amount_total = 0;
				$schools_data = $data['schools_data'];
				 foreach($schools_data as $item_id=>$item) {
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $item->item_name); 
					$this->excel->getActiveSheet()->setCellValue('C'.$i,   $item->number_of_days);
					
					
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $item->school_strength);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $item->opening_quantity);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $item->monthly_required_qty);
					
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $item->balance_qty); 
					 
					
					  $this->excel->getActiveSheet()->getStyle('S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					 
					$i++;$sno++;
				}
	 
				 
					
				$filename =  $data['school_info']->school_code."-".$data['school_info']->name."-". ucfirst($data['month_name'])."-".$data['year'] ."-IndentReport.xls";
                
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

