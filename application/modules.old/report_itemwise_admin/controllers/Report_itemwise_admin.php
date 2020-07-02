<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Report_itemwise_admin extends MX_Controller {

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
		 $this->load->library('excel');
		 $this->load->model("common/common_model");  
	}
	function index()
	{
 
		$this->form_validation->set_rules('school_id', 'School', 'required|numeric|greater_than[0]');              
		$this->form_validation->set_rules('item_id', 'Item', 'required|numeric|greater_than[0]');              
		$this->form_validation->set_rules('month_year', 'Month year', 'required');              
		 
		
		if($this->form_validation->run() == true && $this->input->post('month_year') !=""  )
		{
				//print_a($_POST,1);
				$item_id = intval($this->input->post('item_id'));
				$month_year =  $this->input->post('month_year') ."-01";
				$school_id =  $this->input->post('school_id');
				$start_date = date('Y-m-d',strtotime($month_year));
				$submit = $this->input->post('submit');
				$dates= $this->db->query("select DATE_ADD(DATE_ADD(LAST_DAY(?),INTERVAL 1 DAY),INTERVAL - 1 MONTH)  as start_date,LAST_DAY(?) as end_date ",array($month_year,$month_year))->row();;
				
				 
				$start_date = $dates->start_date;
				$end_date = $dates->end_date; 
				 
				 
				
				 
				if($submit == "Get Report")
					$type = "display";
				else
					$type = "download";
				
				$school_rs  = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
				$school_name = $school_rs->school_code . "-".$school_rs->name."-".$school_rs->district_name; 
		
				 $encoded_input =  $this->ci_jwt->jwt_web_encode(array('item_id'=>$item_id,'school_id'=>$school_id,'start_date'=>$start_date ,'end_date'=>$end_date ,'type'=>$type,'school_name'=>$school_name));	
				redirect('report_itemwise_admin/itemreport/'. $encoded_input );
		}
		
		 
		$drs = $this->db->query("select * from  items where status='1' order by priority asc");         
        $data["rset"] = $drs;
		
		
		$data["module"] = "report_itemwise_admin";
        $data["view_file"] = "item_wise_report";
        echo Modules::run("template/admin", $data);
         
	}
	
	
	function itemreport($encoded_data)
	{
			$decoded_data =  $this->ci_jwt->jwt_web_decode($encoded_data);	
		 	//print_a($decoded_data,1);
			$item_id = $decoded_data->item_id;
			$school_id = $decoded_data->school_id;
			$start_date= $decoded_data->start_date; 
			$end_date= $decoded_data->end_date; 
			$type=  $decoded_data->type;
			if($type==null)
					$type = 'display';
		
			$data["from_date_dp"] = date('d-M-Y',strtotime($start_date));
			$data["to_date_dp"] =date('d-M-Y',strtotime($end_date));
			
			 $att_rs = $this->db->query("select * from school_attendence where entry_date between ? and ? and school_id=?",array($start_date,$end_date,$school_id));
				 $attendence = array();
				 foreach($att_rs->result() as $row)
				 {
					 $attendence[$row->entry_date] = $row->present_count;
				 }
				  $data["attendence"] = $attendence;
			
			
		$columns = array();
		
		 
		 $stock_entry_table = $this->common_model->get_stock_entry_table($start_date);
	 
			   $sqry =  " select bs.item_id,items.item_name,session_1_qty,session_2_qty,session_3_qty,session_4_qty,
								DATE_FORMAT(entry_date,'%d-%M-%Y') as entry_date_dp, 
								 entry_date , 
								opening_quantity,purchase_quantity,purchase_price,
								(opening_quantity+ purchase_quantity) as total_qty,
								(session_1_qty+	session_2_qty+	session_3_qty+ session_4_qty) as consumed_qty,
									closing_quantity,
								((session_1_qty*session_1_price) +(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price)) as consumed_total 
						from $stock_entry_table  bs inner join items on  bs.item_id= items.item_id where
						school_id=? and entry_date between ? and last_day(?) and bs.item_id=? and entry_date>'2016-08-31' order by entry_date asc";
				  
				 $daily_item_details = $this->db->query( $sqry,array($school_id,$start_date,$end_date,$item_id));
		
		 
		$item_data = $this->db->query("select * from items where item_id=?",array($item_id))->row();
		$school_rs  = $this->db->query("select * from schools where school_id=?",array($school_id))->row();
		$school_name = $school_rs->school_code . "-".$school_rs->name."-".$school_rs->district_name; 
		    $data["school_name"] = $school_name; 
 			 
		if($type=="download"){
			$extra_params = array('from_date_dp'=>$data["from_date_dp"], 'to_date_dp'=>$data["to_date_dp"],'school_name'=>$school_name);
				$this->download_item_report($daily_item_details,$item_data,$extra_params,$data["attendence"]);
		}else {
			//print_a($list );
			$data['daily_item_details'] = $daily_item_details;
	
			$data["school_id"] = $school_id; 
			$data["school_name"] = $school_name; 
			$data["module"] = "report_itemwise_admin";
			$data["from_date"] = $from_date;
			$data["to_date"] = $to_date;
			$data["item_id"] = $item_id;
			$data["item_details"] = $item_data;
			$data["view_file"] = "item_report";
			$data["encoded_data"] = $encoded_data;
			
			 $download_link =  $this->ci_jwt->jwt_web_encode(array('item_id'=>$item_id,'start_date'=>$start_date,'end_date'=>$end_date,'type'=>'download','school_name'=>$school_name,'school_id'=>$school_id));	
			$data["download_link"] = $download_link ;
			echo Modules::run("template/admin", $data);
		}
         
	}

 	public function download_item_report($list,$item_data,$extra_params,$attendence)
    {
         // print_a($extra_params,1);
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle($item_data->item_name."-".'Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name').$extra_params['school_name'] );
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:I1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'DIET EXPENDITURE STATEMENT FOR '.$item_data->item_name  ." - Dates between ".$extra_params['from_date_dp']." to ".$extra_params['to_date_dp'] );
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A2:I2');
				
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
				
				$this->excel->getActiveSheet()->setCellValue('C3', 'Opening Qty');
				$this->excel->getActiveSheet()->setCellValue('D3', 'Purchase Qty');
				
				$this->excel->getActiveSheet()->setCellValue('E3', 'Purchase Price');				
				$this->excel->getActiveSheet()->setCellValue('F3', 'Total Qty');		
				
				$this->excel->getActiveSheet()->setCellValue('G3', 'Consumption Qty');
				$this->excel->getActiveSheet()->setCellValue('H3', 'Consumption Avg Price (KG/Litre)');
				
				$this->excel->getActiveSheet()->setCellValue('I3', 'Attendance  ');
				$this->excel->getActiveSheet()->setCellValue('J3', 'Avg used Qty in kgs  ');



				
				$this->excel->getActiveSheet()->setCellValue('K3', 'Closing Qty');
				
				
				$this->excel->getActiveSheet()->setCellValue('L3', 'Total Consumed Price');				
				 
				
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$total_amount_consumed = 0;
				$total_qty_consumed = 0;
				foreach($list->result() as $item_idd =>$rowitem)
				{
					$total_amount_consumed = $total_amount_consumed + $rowitem->consumed_total;
					$total_qty_consumed =  $total_qty_consumed + $rowitem->consumed_qty;
				 
						//print_a($rowitem,1);
						//Date	Opening Qty	Purchase Qty	Purchase Price	Total Qty	Consumption Qty	Closing Qty	Total Consumed Price
						 
						   
					$this->excel->getActiveSheet()->setCellValue('A'.$i,  $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $rowitem->entry_date_dp);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem->opening_quantity);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem->purchase_quantity);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $rowitem->purchase_price);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $rowitem->total_qty);
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $rowitem->consumed_qty);
					$this->excel->getActiveSheet()->setCellValue('H'.$i, number_format($rowitem->consumed_total/$rowitem->consumed_qty,2));
					
					
					$this->excel->getActiveSheet()->setCellValue('I'.$i,  $attendence[$rowitem->entry_date] );//att
					
					$avg  = 0 ;
					if( $attendence[$rowitem->entry_date]==0) { $avg = "0.00";}
					else {
					   $avg = round($rowitem->consumed_qty/$attendence[$rowitem->entry_date] ,3);
					}
					
					$this->excel->getActiveSheet()->setCellValue('J'.$i, $avg);//avg 
					
					
					
					$this->excel->getActiveSheet()->setCellValue('K'.$i, $rowitem->closing_quantity);
					$this->excel->getActiveSheet()->setCellValue('L'.$i, $rowitem->consumed_total); 
					$i++;$sno++;
				}
				
					$this->excel->getActiveSheet()->setCellValue('F'.$i, 'Total Consumed Qty');
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, number_format($total_qty_consumed,3));
					$this->excel->getActiveSheet()->setCellValue('H'.$i, 'Total Consumed Amount');
					$this->excel->getActiveSheet()->setCellValue('I'.$i, number_format($total_amount_consumed ,2)); 
					$this->excel->getActiveSheet()->getStyle(('A'.$i.':O'.$i))->getFont()->setBold(true);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
              
                $filename= $extra_params['school_name']." - ".$item_data->item_name.'-report_'.date('d-m-Y').'.xls'; //save our workbook as this file name
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
	 function recalculate($encoded_data)
	 {
			$decoded_data =  $this->ci_jwt->jwt_web_decode($encoded_data);	
		 	//print_a($decoded_data,1);
			$item_id = $decoded_data->item_id;
			$school_id = $decoded_data->school_id;
			$start_date= $decoded_data->start_date;  
			$this->common_model->update_entries($school_id,$item_id,$start_date,'temp_procedure');
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>'); 
			redirect('report_itemwise_admin/itemreport/'. $encoded_data );
	 }
	 
	 function start_recalculate($encoded_data)
	 {
			$decoded_data =  $this->ci_jwt->jwt_web_decode($encoded_data);	
		 	// print_a($decoded_data,1);
			$item_id = $decoded_data->item_id;
			$school_id = $decoded_data->school_id;
			$start_date= '2018-10-01';  
			 $this->common_model->update_entries($school_id,$item_id,$start_date,'temp_procedure');
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>'); 
			redirect('report_itemwise_admin/itemreport/'. $encoded_data ); 
	 }

}
