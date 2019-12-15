<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Bill_collections extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
				 				
					if(!in_array($this->session->userdata("user_role"),array( "school","subadmin")))
					{
						redirect("admin/login");
							die;
					}
					$this->load->library('grocery_CRUD');
					$this->load->library('excel');
					 
	}
	}
    	  function index(){
			 	if(!in_array($this->session->userdata("user_role"),array( "school")))
					{
						redirect("admin/login");
							die;
					}
		try{
			$crud = new grocery_CRUD($this);

			$crud->set_theme('datatables'); 
			$crud->set_table('extra_bills');
			$crud->where('school_id',$this->session->userdata("school_id"));
			$crud->order_by('entry_date','desc');
			$crud->set_subject('Bill Collections');
			
			 $crud->callback_column('entry_date',array($this,'date_formatdisplay'));
			 $crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
			$crud->unset_add(); 
			 
			
			
            $crud->unset_delete();
			$crud->columns(array('entry_date','no_of_guests','amount'));
			$crud->edit_fields(array('entry_date','no_of_guests','amount','biil_desc'));
			$crud->required_fields(array(  'no_of_guests','amount'));
			 
			$crud->field_type('entry_date', 'readonly');
			
			 //$crud->callback_view_field('entry_date',array($this,'date_formatdisplay'));
			
			 
			$crud->display_as('no_of_guests',' Guest Count');
			 

			$output = $crud->render();
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Bill Collection entries";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	function date_formatdisplay($value, $row)
		{
			 return date('d-M-Y',strtotime($value));
		}
		
	function extrabillreports()
	{
		if($this->session->userdata("user_role") == "subadmin")
		{
				$this->form_validation->set_rules('school_id', 'School ', 'required'); 
			$school_id=				10;
		}
		else{
				$school_id= $this->session->userdata("school_id");
		}
		
		
		$this->form_validation->set_rules('fromdate', 'From Date', 'required');              
		$this->form_validation->set_rules('todate', 'To Date', 'required'); 
		$item_names   = array();
		$data = array();
		$data['exclude'] = '';
		if($this->form_validation->run() == true && $this->input->post('fromdate') !="" && $this->input->post('todate') !="")
		{
				 
			 
				$from_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
				$to_date = date('Y-m-d',strtotime($this->input->post('todate')));
				if($this->session->userdata("user_role") == "subadmin")
				{
						$school_id= $this->input->post('school_id');
			 
				}
				else
				{
						$school_id= $this->session->userdata("school_id");
				}
				 
				 $school_id=  intval($school_id);
		
				$sql ="select *  from extra_bills  where  school_id=? and entry_date between ? and ?  and no_of_guests>0 order by entry_date desc";				 
				$rs  = $this->db->query($sql,array($school_id,$from_date,$to_date)); 
				$data["rs"] = $rs;
				$data['rfrom_date'] = $from_date;
				$data['rto_date'] = $to_date;
				 
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] =  date('d-M-Y',strtotime($this->input->post('todate')));
					 $school_info = $this->db->query("select * from schools where school_id=?",$school_id)->row();
					$filedata['sname'] = $school_info->school_code." - ". $school_info->name;
					//$this->purchaseconsolidated_report($items,$filedata,$item_names);
					$this->extrabills_report_download($rs,$filedata);
					die;
				}

		}
		
		
		
		 
        $school_info = $this->db->query("select * from schools where school_id=?",$school_id)->row();
		$data['sname'] = $school_info->school_code." - ". $school_info->name;
		
		
		$data["module"] = "bill_collections";
        $data["view_file"] = "extrabills_report";
        echo Modules::run("template/admin", $data);
         
	}
	
	//purchaseconsolidated_report
		public function extrabills_report_download($rset,$metadata)
    {
				 
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name') ." - ".$metadata['sname']);
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->setCellValue('A2',    '  BILL collection Report -  dates between '.$metadata['fromdate']. " - ".$metadata['todate']);
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
				
				
				
				 
				
				$this->excel->getActiveSheet()->setCellValue('A3', 'SLNO');
				 
				$this->excel->getActiveSheet()->setCellValue('B3', 'Date');
				$this->excel->getActiveSheet()->setCellValue('C3', 'No of Guests');				
				$this->excel->getActiveSheet()->setCellValue('D3', 'Amount');				
				 
			 
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$total_amt  = 0;
				foreach($rset->result()  as $rowitem)
				{
					//echo $item_idd;
				// print_a($rowitem);die;
				 
						 	
					$this->excel->getActiveSheet()->setCellValue('A'.$i,  $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, date('d-m-Y',strtotime($rowitem->entry_date)));
					 
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem->no_of_guests);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem->amount);
					 
					 $total_amt =  $total_amt +  $rowitem->amount;
				 
					 
					$i++;$sno++;
				}
	 
					$this->excel->getActiveSheet()->setCellValue('C'.$i, "Total Purchase  Amount");
					$this->excel->getActiveSheet()->setCellValue('D'.$i,  number_format(  $rowitem->amount,2));
				$this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getFont()->setBold(true);
                
              
                $filename=$metadata['sname']."-".$typeof_items  .'Billcollection-'.date('d-m-Y').'.xls'; //save our workbook as this file name
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
