<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Subadmin extends MX_Controller {

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
		}
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->model('school_model');
		 $this->load->library('excel');
		 $this->load->library('table');
	}

    function index() {
        $data["module"] = "admin";
        $data["view_file"] = "subadmin_dashboard";
        echo Modules::run("template/admin", $data);
    }
	 function schoolreporttoday() {
		 
		 if($this->input->post('school_code')!="")
		 {
			 $school_code = $this->input->post('school_code');
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			 
			 $srs = $this->db->query("select * from users where school_code='$school_code'");
			 $school_data = $srs->row();
			 $school_id = $school_data->school_id;
			 redirect('admin/subadmin/today_report/'. $school_id."/". $school_date);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "admin";
        $data["view_file"] = "subadmin_reportform";
        echo Modules::run("template/admin", $data);
    }
	function today_report($school_id=0,$date=null)
	{
		if($date==null)
				$date = date('Ymd');
			
		$report_date = date('Y-m-d',strtotime($date));
		
		if($school_id==0)
				$school_id = $this->session->userdata("school_id");
			
		$sql = "SELECT it.item_name,it.telugu_name,bs.* FROM `balance_sheet` bs inner join items  it on bs.item_id=it.item_id WHERE `school_id`='$school_id' and `entry_date`='$report_date'";
		$rs  = $this->db->query($sql);
		
		$report_date_formated = date('d-m-Y',strtotime($date));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		
		$school_name_rs = $this->db->query("select * from users where school_id='$school_id'");
		$school_data  = $school_name_rs->row();
		
		
		$data["school_name"] = $school_data->name;
		
		$data["module"] = "admin";
		$data["module"] = "admin";
		$data["view_file"] = "school_today_report";
		echo Modules::run("template/admin", $data);
		
	}
function entriestoday() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 $school_code = $this->input->post('school_code');
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			 
			 
			 redirect('admin/subadmin/missedentriues_report/'. $school_date);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "admin";
        $data["view_file"] = "subadmin_schoolentries";
        echo Modules::run("template/admin", $data);
    }
	function missedentriues_report($date=null)
	{
		if($date==null)
				$date = date('Ymd');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
			
		  $sql = "select school_id,school_code,name,	village from schools where school_id not in (SELECT DISTINCT  `school_id` FROM  `balance_sheet`  WHERE  `entry_date` = '$report_date') ";
		$rs  = $this->db->query($sql);
		
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		
		
		$data["module"] = "admin";
		$data["module"] = "admin";
		$data["view_file"] = "school_missed_entries_list";
		echo Modules::run("template/admin", $data);
		
	}
	function district_form()
	{
		$data["district_rs"] = $this->db->query("select * from districts");
		$data["items"] = $this->db->query("select * from items where status='1'");
		$data["module"] = "admin";
        $data["view_file"] = "reports/district_form";
        echo Modules::run("template/admin", $data);
		
	}
	function reports($type='district')
	{
		if($this->input->post('submit')!="")
		{
		//	print_a($_POST);
			$schools = array();
			$items = array();
				if($type=="district"){
					$sql = "select * from schools where district_id in (".implode(",",$this->input->post("district_selected")).")";
					$rs = $this->db->query($sql);
					foreach($rs->result() as $row){
						$schools[] = $row->school_id;
					}
					
					$sql = "select * from districts where district_id in (".implode(",",$this->input->post("district_selected")).")";
					$rs = $this->db->query($sql);
					$dst_names = array();
					foreach($rs->result() as $row){
						$dst_names[] = $row->name;
					}
					$selected_text  = "Districts : ".implode(" , ",$dst_names);
				}
				else{
					
					$schools = $this->input->post("school_selected");
					
					
					$sql = "select * from schools where school_id in (".implode(",",$this->input->post("school_selected")).")";
					$rs = $this->db->query($sql);
					$sch_names = array();
					foreach($rs->result() as $row){
						$sch_names[] = $row->name."-".$row->village;
					}
					$selected_text  = "Schols : ".implode(" , ",$sch_names);
				}
				$items = $this->input->post("items_selected");
				$from_date = date("Y-m-d",strtotime($this->input->post("from_date")));
				$to_date = date("Y-m-d",strtotime($this->input->post("to_date")));
				
				$type=$this->input->post('submit');
				if($type=="Download Report")
					$type="download";
				else
					$type="display";
					
				$this->get_report_values($schools,$items,$from_date,$to_date,array('type'=>$type,'selected_text'=>$selected_text));
		}
		
		 
		if($type=="district"){
						$data["district_rs"] = $this->db->query("select * from districts");
						$data["items"] = $this->db->query("select * from items where status='1'  order by priority asc 	");
						$data["module"] = "admin";
						$data["view_file"] = "reports/district_form";
		}
		else if($type=="school"){
					$data["school_rs"] = $this->db->query("select s.*,d.name as district_name  from schools s inner join districts d on s.district_id = d.district_id");
					$data["items"] = $this->db->query("select * from items where status='1' order by priority asc ");
					$data["module"] = "admin";
					$data["view_file"] = "reports/school_form";
		}
		else{
			redirect("admin/subadmin");
		}
		
        echo Modules::run("template/admin", $data);
		
		
	}
	//old reports link
	function get_report_values($school_ids,$item_ids,$from_date,$to_date,$extra=array('type'=>'display'))
	{
		/*	$school_ids = array(3,6,1,4);
				$item_ids = array('1','4','5','6','9','11','12','13','16','49','102','104','107','108','110','112','114','116','118');
				
				$from_date = '2016-09-01';
				$to_date = '2016-09-31';
		*/

		$schools_in = implode(",",$school_ids);
		$item_ids_in = implode(",",$item_ids);
		//print_a($schools_in);
		//print_a($item_ids_in);
		
		$tbl_purchased = "tbl_purchased_".uniqid();
		  $sql   = "		create temporary table  $tbl_purchased 	
						select school_id,item_id,sum(purchase_quantity) as purchase_qty   from balance_sheet consolidated_purchased 
						where entry_date between '$from_date' and '$to_date' and school_id in ($schools_in) and 
						item_id in ($item_ids_in) group by school_id,item_id";
		
		  $this->db->query($sql);
		
		$tbl_consumed = "tbl_consumed".uniqid();
		  $sql   = "	create temporary table  $tbl_consumed 
							select school_id,item_id,
							sum((session_1_qty+session_2_qty+session_3_qty+session_4_qty)) as consumed_qty,
							sum(( 
									(session_1_qty*session_1_price) +
									(session_2_qty*session_2_price)+ 
									(session_3_qty*session_3_price) + 
									(session_4_qty*session_4_price)
									)) as consumed_amount

							from balance_sheet consolidated_consumed 
							where entry_date between '$from_date' and '$to_date' and
							school_id in ($schools_in) and item_id in ($item_ids_in) group by school_id,item_id";
		
	  $this->db->query($sql);
		
		$tbl_opening_balances = "tbl_opening_balances".uniqid();
		$sql   = "	create temporary table  $tbl_opening_balances 
		select bs_opening.school_id, bs_opening.item_id,bs_opening.entry_date,opening_entries_selected.entry_date max_selected_date,bs_opening.closing_quantity as opening_quantity from 
		balance_sheet bs_opening inner join 
		(SELECT school_id,item_id,max(entry_date) as entry_date from balance_sheet where entry_date<'$from_date'
		and school_id in ($schools_in) and item_id in ($item_ids_in) group by school_id,item_id)  as opening_entries_selected
		on bs_opening.entry_date  = opening_entries_selected.entry_date  and 
		bs_opening.school_id = opening_entries_selected.school_id and 
		bs_opening.item_id = opening_entries_selected.item_id 		 
		group by bs_opening.school_id, bs_opening.item_id";

		 $this->db->query($sql);

		$tbl_closed_balances = "tbl_closed_balances".uniqid();
		$sql   = " create temporary table  $tbl_closed_balances 
		select bs_close.school_id, bs_close.item_id,bs_close.entry_date,closeing_entries_selected.entry_date max_selected_date,bs_close.`closing_quantity` from 
		balance_sheet bs_close inner join 
		(SELECT school_id,item_id,max(entry_date) as entry_date from balance_sheet where 
		entry_date<='$to_date'   and school_id in ($schools_in) and item_id in ($item_ids_in) group by school_id,item_id)  as closeing_entries_selected

		on bs_close.entry_date  = closeing_entries_selected.entry_date  and 
		bs_close.school_id = closeing_entries_selected.school_id and 
		bs_close.item_id = closeing_entries_selected.item_id 

		group by bs_close.school_id, bs_close.item_id ";
		
		  $this->db->query($sql);
		
		$consolidate_table = "consolidated_".uniqid();
		  $sql = "create temporary table $consolidate_table 
		  select tp.school_id,tp.item_id,top.opening_quantity,tp.purchase_qty,tc.consumed_qty ,tc.consumed_amount ,tcb.closing_quantity from  
					$tbl_purchased tp  inner join $tbl_consumed tc on  tp.item_id=tc.item_id and tp.school_id = tc.school_id
								inner join $tbl_opening_balances top on tp.item_id = top.item_id and tp.school_id = top.school_id 
								inner join $tbl_closed_balances tcb on tcb.item_id = tp.item_id and tp.school_id = tcb.school_id  
		
				";
		  $this->db->query($sql);
		$csql = "select * from $consolidate_table order by school_id";
		$crs = $this->db->query($csql); 
		// echo $this->table->generate($crs);
		//echo "<br>**********************<br>";
		$sql = "select ct.item_id,items.item_name,sum(opening_quantity) as opening_quantity ,
						sum(purchase_qty) as purchase_qty,
						sum(consumed_qty) as consumed_qty,
						sum(consumed_amount) as consumed_amount,
						sum(closing_quantity) as closing_quantity from $consolidate_table ct inner join items on items.item_id = ct.item_id  group by ct.item_id
		";
		$sumrs = $this->db->query($sql );
		
				$i=1; 
				$total_amount = 0;
				$total_qty = 0;
				$output_rows = array();
				
				 
				foreach($sumrs->result() as $printitem){ 
				 $total_amount = $total_amount + $printitem->consumed_amount;
				
				$total_qty = 0;				 
				$total_qty = $printitem->opening_quantity   + $printitem->purchase_qty;
				$output_rows[$i]['sno'] = $i;
				$output_rows[$i]['item_name'] =  $printitem->item_name;
				$output_rows[$i]['opening_quantity'] = number_format($printitem->opening_quantity,3,'.', '');
				$output_rows[$i]['purchase_qty'] = number_format($printitem->purchase_qty,3,'.', '');
				$output_rows[$i]['total_qty'] =  number_format($total_qty ,3,'.', '');
				$output_rows[$i]['consumed_qty'] = number_format($printitem->consumed_qty,3,'.', '');
				$output_rows[$i]['closing_quantity'] = number_format($printitem->closing_quantity ,3,'.', '');
				$output_rows[$i]['consumed_amount'] =number_format($printitem->consumed_amount ,2,'.', '');
			 $i++;
				}
			  
			$data["items"] = $output_rows ;
			$data["total_amount"] = $total_amount ;
			$data["from_date"] = date('d/M/Y',strtotime($from_date)) ;
			$data["to_date"] = date('d/M/Y',strtotime($to_date)) ;
			$data["selected_text"] =  $extra['selected_text'];
		 if($extra['type']=="display")
		 {
			
			
			$data["module"] = "admin";
			$data["view_file"] = "reports/report_display";
			echo Modules::run("template/admin", $data);
			die;
		 }
		 else{
			 //Download Report
			  $this->download_schools_report($data);
			 die;
		 }

	}
	
	
	public function _example_output($output = null)
	{
		  $data["module"] = "admin";
        $data["view_file"] = "example";
        echo Modules::run("template/admin", $data);
		//$this->load->view('example.php',$output);
	}
	function date_formatdisplay($value, $row)
		{
			 return date('d-M-Y',strtotime($value));
		}
     
	/*
	*/
	function dataentry($school_id,$item_id,$date)
	{
		if($this->input->post('submit')=="Submit")
		{
		
			$qrs = "select * from balance_sheet where school_id='$school_id' and item_id='$item_id' and entry_date='$date'";
			$qp_rs = $this->db->query($qrs);
			if($qp_rs->num_rows()==0)
			{
				$sql = "insert into balance_sheet set 
							purchase_quantity='".$this->input->post('pqty')."',
							purchase_price='".$this->input->post('pprice')."',

							session_1_qty='".$this->input->post('bf_qty')."',
							session_1_price='".$this->input->post('bf_price')."',

							session_2_qty='".$this->input->post('lu_qty')."',
							session_2_price='".$this->input->post('lu_price')."',

							session_3_qty='".$this->input->post('sn_qty')."',
							session_3_price='".$this->input->post('sn_price')."',

							session_4_qty='".$this->input->post('di_qty')."',
							session_4_price='".$this->input->post('di_price')."',
							
							school_id='$school_id',
							item_id='$item_id',
							entry_date='$date',
							created_time=now();
						";
			}
			else
			{
				$sql = "update balance_sheet set 
							purchase_quantity='".$this->input->post('pqty')."',
							purchase_price='".$this->input->post('pprice')."',

							session_1_qty='".$this->input->post('bf_qty')."',
							session_1_price='".$this->input->post('bf_price')."',

							session_2_qty='".$this->input->post('lu_qty')."',
							session_2_price='".$this->input->post('lu_price')."',

							session_3_qty='".$this->input->post('sn_qty')."',
							session_3_price='".$this->input->post('sn_price')."',

							session_4_qty='".$this->input->post('di_qty')."',
							session_4_price='".$this->input->post('di_price')."'
							
							where 
							school_id='$school_id' and 	item_id='$item_id' and entry_date='$date'
						";
			}
			//echo $sql;
			//die; 
			if(ip_allowed_to_edit($this->input->ip_address())){
				//echo $sql;
				//die;
			}
			$this->db->query($sql);
			$this->school_model->update_entries($school_id,$item_id,$date);
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>');
			redirect('admin/subadmin/data_entry_school_selection');
		}
		
		//print_a($data["today_consumes"]);
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id and s.school_id='$school_id'");         
        $data["school_info"] = $drs->row();
        
		$data["date_selected"] = date('d-M-Y',strtotime($date));
		$data["date"] = $date ;
		$data["school_id"] = $school_id ;
		$data["item_id"] = $item_id ;
		$data["item_details"] = $this->school_model->get_itemdetails($item_id) ;
		//print_a($data["item_details"]);
			
		$qrs = "select * from balance_sheet where school_id='$school_id' and item_id='$item_id' and entry_date='$date'";
		$qp_rs = $this->db->query($qrs);
		$form_data = array('pqty'=>0,'pprice'=>'0','bf_qty'=>'0','bf_price'=>'0','lu_qty'=>'0','lu_price'=>'0','sn_qty'=>'0','sn_price'=>'0','di_qty'=>'0','di_price'=>'0');
		if($qp_rs->num_rows()>0)
		{
				$qp_data = $qp_rs->row();
				//print_a($qp_data)				;
				$form_data['pqty'] = $qp_data->purchase_quantity;	
				$form_data['pprice'] = $qp_data->purchase_price;	
				$form_data['bf_qty'] = $qp_data->session_1_qty;	
				$form_data['bf_price'] = $qp_data->session_1_price;	
				$form_data['lu_qty'] = $qp_data->session_2_qty;	
				$form_data['lu_price'] = $qp_data->session_2_price;	
				$form_data['sn_qty'] = $qp_data->session_3_qty;	
				$form_data['sn_price'] = $qp_data->session_3_price;	
				$form_data['di_qty'] = $qp_data->session_4_qty;	
				$form_data['di_price'] = $qp_data->session_4_price;	
		}
		
		 
        $data["form_data"] = $form_data;        
        $data["module"] = "admin";        
        $data["view_file"] = "school/data_entry_form";
        echo Modules::run("template/admin", $data);
	}
	function data_entry_school_selection()
	{
		$this->form_validation->set_rules('school_id', 'School', 'required');              
		$this->form_validation->set_rules('item_id', 'Item', 'required'); 
		$this->form_validation->set_rules('todate', 'Date', 'required');  
		 
		if($this->form_validation->run() == true && $this->input->post('submit')=="Submit")
		{
			  $todate = date('Y-m-d',strtotime($this->input->post('todate')));
			  $school_id = $this->input->post('school_id');
			  $item_id = $this->input->post('item_id');
			  redirect('admin/subadmin/dataentry/'.$school_id."/".$item_id."/".$todate);
			  die;
			  
		}
		
		//print_a($data["today_consumes"]);
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id");         
        $data["schools"] = $drs;
		
		
		$itemsrs = $this->db->query("SELECT * FROM  items where  status='1'");         
        $data["items"] = $itemsrs;
		
		 
		
		$data["module"] = "admin";
        $data["view_file"] = "school/purchase_entry";
        
		
		$data['users_count']=0; 
        $data['banks_count']=0;
        $data['plans_count']=0;
        $data['payments_count']=0;
        
		 
        $data["module"] = "admin";        
        $data["view_file"] = "school/data_entry_school_selection";
        echo Modules::run("template/admin", $data);
	}
	 function data_entry_bulk_selection()
	{
		
		$this->form_validation->set_rules('school_id', 'School', 'required');              
		$this->form_validation->set_rules('item_id', 'Item', 'required'); 
		$this->form_validation->set_rules('month', 'Month', 'required'); 

		
		 
		if($this->form_validation->run() == true && $this->input->post('submit')=="Submit")
		{
			  $todate = date('Y-m-d',strtotime($this->input->post('todate')));
			  $school_id = $this->input->post('school_id');
			  $item_id = $this->input->post('item_id');
			  $month_id = $this->input->post('month');
			  redirect('admin/subadmin/bulkdataentry/'.$school_id."/".$item_id."/".$month_id);
			  die;
			  
		}
		
		//print_a($data["today_consumes"]);
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id");         
        $data["schools"] = $drs;
		
		
		$itemsrs = $this->db->query("SELECT * FROM  items where  status='1'");         
        $data["items"] = $itemsrs;
		
		 
		
		 
        
		
		$data['users_count']=0; 
        $data['banks_count']=0;
        $data['plans_count']=0;
        $data['payments_count']=0;
        
		 
        $data["module"] = "admin";        
        $data["view_file"] = "school/data_entry_bulk_selection";
        echo Modules::run("template/admin", $data);
	}
	function bulkdataentry($school_id,$item_id,$month_id)
	{
		if($this->input->post('submit')=="Submit")
		{
			//print_a($_POST,1);
			
			$purchase_qty =$this->input->post('pqty');
			$purchase_price =$this->input->post('pprice');
			
			$bf_qty =$this->input->post('bf_qty');
			$bf_price =$this->input->post('bf_price');
			
			$lu_qty =$this->input->post('lu_qty');
			$lu_price =$this->input->post('lu_price');
			
			$sn_qty =$this->input->post('sn_qty');
			$sn_price =$this->input->post('sn_price');
			
			$dn_qty =$this->input->post('di_qty');
			$dn_price =$this->input->post('di_price');
			//print_a($dn_qty,1);
			foreach($purchase_qty as $rowdate=>$purchase_qty_value){
				//echo "--",$rowdate;
				if($purchase_qty[$rowdate] !=0 || $bf_qty[$rowdate] !=0 ||$lu_qty[$rowdate] !=0 ||$sn_qty[$rowdate] !=0||$dn_qty[$rowdate] !=0)
				{
					 
							  $qrs = "select * from balance_sheet where school_id='$school_id' and item_id='$item_id' and entry_date='$rowdate'";
							$qp_rs = $this->db->query($qrs);
							if($qp_rs->num_rows()==0)
							{
								$sql = "insert into balance_sheet set 
											purchase_quantity='".$purchase_qty[$rowdate]."',
											purchase_price='".$purchase_price[$rowdate]."',

											session_1_qty='".$bf_qty[$rowdate]."',
											session_1_price='".$bf_price[$rowdate]."',

											session_2_qty='".$lu_qty[$rowdate]."',
											session_2_price='".$lu_price[$rowdate]."',

											session_3_qty='".$sn_qty[$rowdate]."',
											session_3_price='".$sn_price[$rowdate]."',

											session_4_qty='".$dn_qty[$rowdate]."',
											session_4_price='".$dn_price[$rowdate]."',
											
											school_id='$school_id',
											item_id='$item_id',
											entry_date='$rowdate',
											created_time=now();
										";
							}
							else
							{
								$sql = "update balance_sheet set 
											purchase_quantity='".$purchase_qty[$rowdate]."',
											purchase_price='".$purchase_price[$rowdate]."',

											session_1_qty='".$bf_qty[$rowdate]."',
											session_1_price='".$bf_price[$rowdate]."',

											session_2_qty='".$lu_qty[$rowdate]."',
											session_2_price='".$lu_price[$rowdate]."',

											session_3_qty='".$sn_qty[$rowdate]."',
											session_3_price='".$sn_price[$rowdate]."',

											session_4_qty='".$dn_qty[$rowdate]."',
											session_4_price='".$dn_price[$rowdate]."' 
											
											where 
											school_id='$school_id' and 	item_id='$item_id' and entry_date='$rowdate'
										";
							}
			 
						$this->db->query($sql);
				}
				
				}	
				
			$update_items_date = date('Y')."-".$month_id."-01";
			$this->school_model->update_entries($school_id,$item_id,$update_items_date);
						
			$this->session->set_flashdata('message', '<div class="alert alert-success">Updated Successfully.</div>');
			redirect('admin/subadmin/data_entry_bulk_selection');
		}
		
		//print_a($data["today_consumes"]);
		$drs = $this->db->query("SELECT s.*,d.name as dname,s.name as sname FROM  schools  s inner join districts d on s.`district_id`=d.district_id and s.school_id='$school_id'");         
        $data["school_info"] = $drs->row();
        
		 
		$data["month_id"] = $month_id ;
		$data["school_id"] = $school_id ;
	 
	 
		$it_sql= "select * from items where status='1' and item_id='$item_id' order by priority asc";
		$itrs = $this->db->query($it_sql);
		$data["items_details"] = $itrs->row() ;
			
		$qrs = "select * from balance_sheet where school_id='$school_id' and item_id='$item_id'  and MONTH(entry_date)='$month_id'";
		$qp_rs = $this->db->query($qrs);
		$balance_sheet_entries= array();
		foreach($qp_rs->result() as $rowdata)
		{
			$mysqldate  = $rowdata->entry_date;
			$balance_sheet_entries[$mysqldate] = $rowdata;
		}
		      
        $data["balance_sheet_entries"] = $balance_sheet_entries;        
        $data["item_id"] = $item_id;        
        $data["module"] = "admin";        
        $data["view_file"] = "school/data_entry_bulk_form";
        echo Modules::run("template/admin", $data);
	}
	
	public function download_schools_report($list)
    {
        //   print_a($list,1);
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', 'APSWRSCHOOLS' );
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'DIET EXPENDITURE STATEMENT of Dates between '.$list['from_date']." to ".$list['to_date'] );
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
				
				
															
				$this->excel->getActiveSheet()->setCellValue('A4', 'SLNO');
				$this->excel->getActiveSheet()->setCellValue('B4', 'Item');
				
				$this->excel->getActiveSheet()->setCellValue('C4', 'Opening Balance Qty');
				$this->excel->getActiveSheet()->setCellValue('D4', 'Purchase Qty');
				
				$this->excel->getActiveSheet()->setCellValue('E4', 'Total Qty');				
				$this->excel->getActiveSheet()->setCellValue('F4', 'Consumption Qty');		
				
				$this->excel->getActiveSheet()->setCellValue('G4', 'Closing Qty');				
				$this->excel->getActiveSheet()->setCellValue('H4', 'Consumption Amount');
				
				
				 $this->excel->getActiveSheet()->setCellValue('A3',$list['selected_text'] );
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A3:H3');
				 $this->excel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);
				
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                 $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A4:H4')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 5;
				$sno=1;
				$total_amount_consumed = 0;
				$total_qty_consumed = 0;
				foreach($list['items']  as $sno =>$rowitem)
				{
					 
                  
						//print_a($rowitem,1);
						//Date	Opening Qty	Purchase Qty	Purchase Price	Total Qty	Consumption Qty	Closing Qty	Total Consumed Price
						 
						   
					$this->excel->getActiveSheet()->setCellValue('A'.$i,  $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $rowitem['item_name']);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem['opening_quantity']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem['purchase_qty']);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $rowitem['total_qty']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $rowitem['consumed_qty']);
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $rowitem['closing_quantity']);
					$this->excel->getActiveSheet()->setCellValue('H'.$i, $rowitem['consumed_amount']); 
					$i++; 
				}
				
					$this->excel->getActiveSheet()->setCellValue('G'.$i, 'Total Consumed Amount');
					$this->excel->getActiveSheet()->setCellValue('H'.$i, number_format($list['total_amount'],2));
					
					$this->excel->getActiveSheet()->getStyle(('A'.$i.':O'.$i))->getFont()->setBold(true);
					$this->excel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
              
                $filename= 'report_'.date('d-m-Y').'.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
 
                //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                //if you want to save it as .XLSX Excel 2007 format
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
                 
    }
 function today_consumed_balancenew() {
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		if($this->input->post('school_code')!="")
		 {
			 $school_code = $this->input->post('school_code');
			 $school_date = date('Y-m-d',strtotime($this->input->post('school_date')));
			 
			 $srs = $this->db->query("select * from users where school_code='$school_code'");
			 $school_data = $srs->row();
			 $data['school_info'] =  $school_data;
			 $school_id = $school_data->school_id;		
			$data['result_flag']			  =  1;
		 }
		 
		if($this->session->userdata("user_role")=="school")
		{
			$school_id = $this->session->userdata("school_id");
			$srs = $this->db->query("select * from schools where school_id='$school_id'");
			$sch_data = $srs->row();
			 $data['school_info'] =  $sch_data;
			$school_code = $sch_data->school_code;	
			
		}
		$school_date = date('Y-m-d',strtotime($this->input->post('school_date')));
		if($this->input->post('school_date')=="")
		{
			$school_date = date('Y-m-d');
		}
		/* calculate balances */
		
		$trs_sql  = "select round(sum(((session_1_qty*session_1_price) +(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price))),2) as consumed_total
					from balance_sheet where school_id='$school_id' and entry_date='$school_date'";
		$trs = $this->db->query($trs_sql);
					
		if($trs->num_rows()>0)
		{
			$tdata = $trs->row();
			$today_consumed_Amount = $tdata->consumed_total;
		}
		/**********Calculate attendence ***************/
		
		$daily_amount  = 0.00;
		$schrs_sql = "select * from schools where school_id='$school_id'";
		$schrs = $this->db->query($schrs_sql);
		if($schrs->num_rows()>0)
		{
			$schdata = $schrs->row();
			$daily_amount = $schdata->daily_amount;
		}
		/******get attendence ******/
			
		$attendece  = 0;
		$atters_sql = "select * from school_attendence where school_id='$school_id' and entry_date='$school_date'";
		$atters = $this->db->query($atters_sql);
		if($atters->num_rows()>0)
		{
			$attedata = $atters->row();
			$attendece = $attedata->present_count;
		}
		$today_allowed_Amount = $attendece  * $daily_amount;
		/**************************************/
		
        $data["reportdate"] =  date('d-M-Y',strtotime($this->input->post('school_date')));
        $data["school_name"] = $today_allowed_Amount;
        $data["per_stundent"] = $daily_amount;
        $data["attendence"] = $attendece;
		
        $data["today_allowed_Amount"] = $today_allowed_Amount;
        $data["today_consumed_Amount"] = $today_consumed_Amount;
        $data["today_remaining_Amount"] = $today_allowed_Amount -  $today_consumed_Amount;
		
        $data["school_code"] = $school_code;
        $data["module"] = "admin";
        $data["view_file"] = "school_consumed";
        echo Modules::run("template/admin", $data);
    }
function attendencereports()
	{
		$this->form_validation->set_rules('fromdate', 'Date', 'required');              
		$this->form_validation->set_rules('todate', 'Date', 'required');              
		 
		$item_names   = array();
		$data = array();
		if($this->form_validation->run() == true && $this->input->post('fromdate') !=""  )
		{
			 
			$report_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
			$report_todate = date('Y-m-d',strtotime($this->input->post('todate')));
			$report_date_frmted = date('d-M-Y',strtotime($this->input->post('fromdate')));
			$report_date_toted = date('d-M-Y',strtotime($this->input->post('todate')));
			
			$attendece_allowed_table_temp = "tempatte_".rand(1000,1000000);
			  $attendece_allowed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $attendece_allowed_table_temp AS (
			SELECT s.school_id,s.school_code,s.name,s.strength, sum(present_count) as attendence,s.daily_amount,sum(sa.present_count) * s.daily_amount as allowed_amt FROM `school_attendence` sa inner join schools s on s.school_id = sa.school_id 
					and (entry_date between '$report_date' and '$report_todate') group by sa.school_id );";
			 
			 $this->db->query($attendece_allowed_table_sql);
			 
			 
			$consumed_table_temp  = "tempconsumed_".rand(1000,1000000);
			  $consumed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $consumed_table_temp AS ( SELECT  school_id,
						TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + 
						(`session_2_qty` * `session_2_price`) + 
						(`session_3_qty` * `session_3_price`) + 
						(`session_4_qty` * `session_4_price`)  ),2)
						as today_consumed from balance_sheet where (entry_date between '$report_date' and '$report_todate') group by school_id); ";
						
			$this->db->query($consumed_table_sql);

				


			$school_attendence = array();
			 
			   $joined_ary = "select att.*,con.today_consumed from $attendece_allowed_table_temp att inner join $consumed_table_temp  con on att.school_id=con.school_id";
			 $trs  = $this->db->query($joined_ary);
			
				 foreach($trs->result() as $row)
				 {
					$school_attendence[$row->school_code] =  array(
							'school_id'=>$row->school_id,
							'name'=>$row->name,
							'strength'=>$row->strength,
							'attendence'=>$row->attendence,
							'daily_amount'=>$row->daily_amount,
							'allowed_amt'=>$row->allowed_amt,
							'consumed_amt'=>$row->today_consumed,
							'remaining_amt'=>number_format($row->allowed_amt - $row->today_consumed,2),
							'rdate'=>$report_date_frmted,
							'tdate'=> $report_date_toted
							);
				 }
				 
					//$consumed_table_temp
					
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] = date('d-M-Y',strtotime($this->input->post('todate')));
					$this->attendence_consumed_report($school_attendence,$report_date_frmted,$report_date_toted);
					die;
				}
				else{
					$data['rdate'] = $report_date_frmted;
					$data['tdate'] =  $report_date_toted;
					$data['attendencereport'] = $school_attendence;
				}

		}
		$data["module"] = "admin";
        $data["view_file"] = "school/attendencereports";
        echo Modules::run("template/admin", $data);
         
	}
	
	public function attendence_consumed_report($school_attendence,$report_date_frmted,$report_date_toted)
    {
          
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Attendence & Consumption Report');
               
                $this->excel->getActiveSheet()->setCellValue('A1', 'Attendence and Consumption Report between dates of  '.$report_date_frmted." and ". $report_date_toted);
				//merge cell A2 until Q2
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
				
				$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
				
				
				$this->excel->getActiveSheet()->setCellValue('A2', 'School Name');
				$this->excel->getActiveSheet()->setCellValue('B2', 'School Code');				
				 
				$this->excel->getActiveSheet()->setCellValue('C2', 'Strength');
				$this->excel->getActiveSheet()->setCellValue('D2', 'Attendence');				
				$this->excel->getActiveSheet()->setCellValue('E2', 'Per Day Amt');				
				$this->excel->getActiveSheet()->setCellValue('F2', 'Allowed Amount');				
				$this->excel->getActiveSheet()->setCellValue('G2', 'Consumption Amoun');
				$this->excel->getActiveSheet()->setCellValue('H2', 'Remaining Amount');
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:O2')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 3;
				$sno=1;
				$consumption_amount_total = 0;
				foreach($school_attendence as $school_code=>$school_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data['name']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_code);
					
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $school_data['strength']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i,$school_data['attendence']);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $school_data['daily_amount']);
					
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $school_data['allowed_amt']);
					$this->excel->getActiveSheet()->setCellValue('G'.$i,   $school_data['consumed_amt']);
					$this->excel->getActiveSheet()->setCellValue('H'.$i,   $school_data['remaining_amt']);
					
					  $this->excel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					 
					$i++;$sno++;
				}
	 
				 
                
              
                $filename='report_'.date('d-M-Y')	.'.xls'; //save our workbook as this file name
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
