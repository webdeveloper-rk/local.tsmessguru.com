<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Purchase_consolidated_report extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
				 				
					if(!in_array($this->session->userdata("user_role"),array("school" ,"subadmin")))
					{
						redirect("admin/login");
							die;
					}
				 
				$this->load->library('excel');
					  $this->load->model('common/common_model');
	}
	}
    function index()  
	{
		$school_id= $this->session->userdata("school_id");
		//echo $this->session->userdata("user_role");die;
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
				
				if($this->session->userdata("user_role") == "subadmin")
				{
						$school_id= $this->input->post('school_id');
			 
				}
				else
				{
						$school_id= $this->session->userdata("school_id");
				}
					
		
				
				$data['item_type'] = $item_type = $this->input->post('item_type');
				$data['exclude'] = $this->input->post('exclude');
			 
				$from_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
				$to_date = date('Y-m-d',strtotime($this->input->post('todate')));
				
				$exclude = $this->input->post('exclude');
				$display_unused_items = true;
				if(isset($exclude))
				{
					$display_unused_items = false;
				}
				$data['display_unused_items'] = $display_unused_items;
				
				$opening_qty = 0;
				$opening_price = 0;
				$items = array();
				
				//get all items between dates 
				  $sql = "SELECT * from items  where item_type=?  ";
				$rs  = $this->db->query($sql,array($item_type));
				$item_ids  = array();
				
				foreach($rs->result() as $row)
				{
					$item_ids[] = $row->item_id;
					$item_names[$row->item_id] = $row->item_name;
				}
				if(count($item_ids)==0)
					$item_ids[] =0 ;
				 $item_con = implode(",",$item_ids);
	 
		
		/*
		calculate purchase data 
		*/
		$balance_sheet_table = $this->common_model->get_stock_entry_table($from_date);

		
		$sql ="select item_id,sum(purchase_quantity) as purchase_qty ,
		
							truncate(sum((purchase_quantity*purchase_price)),2) purchase_total from $balance_sheet_table 
							where  item_id in($item_con)   and  school_id=? and entry_date between ? and ?  group by item_id";				 
		
		

		$rs  = $this->db->query($sql,array($school_id,$from_date,$to_date));
		
		 
				foreach($rs->result() as $row)
				{
					 if($row->purchase_qty==0)
							continue;
					$items[$row->item_id]['purchase_qty'] = $row->purchase_qty;
					$items[$row->item_id]['purchase_amount'] = $row->purchase_total;
					 
				}					
				
				 /* end purchase data */
				 
				 
				 
		
				$data['rfrom_date'] = $from_date;
				$data['rto_date'] = $to_date;
				$data['items'] = $items;
				
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] =  date('d-M-Y',strtotime($this->input->post('todate')));
					 
					 $school_info = $this->db->query("select * from schools where school_id=?",$school_id)->row();
					$filedata['sname'] = $school_info->school_code." - ". $school_info->name;
					$this->purchaseconsolidated_report($items,$filedata,$item_names);
					die;
				}

		}
		
		
		
		 $drs = $this->db->query("select * from  items where status='1'");         
        $data["rset"] = $drs;
        $data["itemnames"] = $item_names;
		
		 $school_info = $this->db->query("select * from schools where school_id=?",$school_id)->row();
		$data['sname'] = $school_info->school_code." - ". $school_info->name;
		$data["module"] = "purchase_consolidated_report";
        $data["view_file"] = "purchase_customreports";
        echo Modules::run("template/admin", $data);
         
	}
	private function purchaseconsolidated_report($rows,$metadata,$item_names)
    {
				$typeof_items = ucfirst($this->input->post('item_type')) . " Items " ;
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name')."-".$metadata['sname']);
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->setCellValue('A2',  $typeof_items . ' - PURCHASE STATEMENT FOR dates between '.$metadata['fromdate']. " - ".$metadata['todate']);
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
				 
				$this->excel->getActiveSheet()->setCellValue('B3', 'Purchase Qty');
				$this->excel->getActiveSheet()->setCellValue('C3', 'Total Qty');				
				 
			 
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				foreach($rows as $item_idd =>$rowitem)
				{
					//echo $item_idd;
				// print_a($rowitem);die;
				 
						 	
					$this->excel->getActiveSheet()->setCellValue('A'.$i,  $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $item_names[$item_idd]);
					 
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem['purchase_qty']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem['purchase_amount']);
					 
					 $consumption_amount_total =  $consumption_amount_total +  $rowitem['purchase_amount'];
				 
					 
					$i++;$sno++;
				}
	 
					$this->excel->getActiveSheet()->setCellValue('C'.$i, "Total Purchase  Amount");
					$this->excel->getActiveSheet()->setCellValue('D'.$i,  number_format( $consumption_amount_total,2));
				$this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getFont()->setBold(true);
                
              
                $filename=$metadata['sname']. "-".$typeof_items  .'-purchasereport_'.date('d-m-Y').'.xls'; //save our workbook as this file name
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
