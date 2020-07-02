<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Subadmin extends MX_Controller {

    function __construct() {
        parent::__construct();
		redirect(site_url('dashboard'));
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					if ($this->session->userdata("is_loggedin") != TRUE || $this->session->userdata("user_id") == "" ) {
							redirect("admin/login");
							die;
					}
					$admin_roles = array('subadmin','secretary','subadmin2');	
					if(!in_array($this->session->userdata("user_role"),$admin_roles))
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
		 redirect("school_day_report");
		  
    }
	function today_report($school_id=0,$date=null)
	{
		if($date==null)
				$date = date('Ymd');
			
		$report_date = date('Y-m-d',strtotime($date));
		
		if($school_id==0)
				$school_id = $this->session->userdata("school_id");
			
		$sql = "SELECT it.item_name,it.telugu_name,bs.*,
					TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + 
						(`session_2_qty` * `session_2_price`) + 
						(`session_3_qty` * `session_3_price`) + 
						(`session_4_qty` * `session_4_price`)  ),2)
						as today_consumed


		FROM `balance_sheet` bs inner join items  it on bs.item_id=it.item_id WHERE `school_id`='$school_id' and `entry_date`='$report_date' group by bs.item_id";
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
	
	function purchasebills() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 $school_code = $this->input->post('school_code');
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			 
			 
			 redirect('admin/subadmin/purchasebills_report/'. $school_date);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "admin";
        $data["view_file"] = "subadmin_purchase_bills";
        echo Modules::run("template/admin", $data);
    }
	function purchasebills_report($date=null)
	{
		if($date==null)
				$date = date('Ymd');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 

		$sql = "SELECT s.name, s.school_code,s.school_id, COUNT( p.bill_id ) AS total_uploads
										FROM  schools s LEFT JOIN  purchase_bills p  ON p.school_id = s.school_id where date(p.uploaded_time)='$report_date' and s.is_school = 1 
										GROUP BY p.school_id  ";
		$rs = $prs  = $this->db->query($sql);
		$uploads = array();
		foreach($prs->result() as $row){
			$uploads[$row->school_id] = $row->total_uploads;
		}
		////////////////////////////////////
		
		  $sql = "SELECT school_id, COUNT( * ) AS item_count
								FROM balance_sheet
								WHERE entry_date =  '$report_date' and purchase_quantity > 0 
								GROUP BY school_id ";
		$itrs  = $this->db->query($sql);
		$item_counts = array();
		foreach($itrs->result() as $row){
			$item_counts[$row->school_id] = $row->item_count;
		}
//print_a($uploads);
		
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		
		
					$uid  = $this->session->userdata("user_id");
					$district_id  = $this->session->userdata("district_id");
					if($this->session->userdata("is_dco")==0)
					{
							$sql = "SELECT * from schools where  is_school='1' and school_code not like '%85000%' ";
							$rs = $prs  = $this->db->query($sql);
					}
					else
					{
										$sql = "SELECT * from schools where district_id='$district_id' and  is_school='1' and school_code not like '%85000%'";
										$rs = $prs  = $this->db->query($sql);
					}

					
		
		$data["rset"] = $rs;
		
		
		$data["item_counts"] = $item_counts;
		$data["uploads"] = $uploads;
		$data["module"] = "admin";
		$data["module"] = "admin";
		$data["view_file"] = "subadmin_purchase_bills_list.php";
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
		 	//print_a($_POST);die;
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
				
				//print_a($items);
				
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
					
					// echo $this->session->userdata("school_code"),"---";
					//print_r($this->session->all_userdata());
					if($this->session->userdata("user_role") =='subadmin'){
						$data["school_rs"] = $this->db->query("select s.*,d.name as district_name  from schools s inner join districts d on s.district_id = d.district_id  and s.is_school ='1'");
					}
					else
					{
						  $sch_id = $this->session->userdata("school_id");
						$district_id = $this->session->userdata("district_id");
						// $school_info = $this->school_model->get_schooldata($sch_id);
						// print_a( $school_info);
						$data["school_rs"] = $this->db->query("select s.*,d.name as district_name  from schools s inner join districts d on s.district_id = d.district_id and s.district_id='$district_id' and s.is_school ='1'");
					}
					
					
					
					
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
		
		$schools_in = implode(",",$school_ids);
		$item_ids_in = implode(",",$item_ids);
		//print_a($schools_in);
		//print_a($item_ids_in);
		$item_rs =  $this->db->query("select * from items");
		$item_count =  $item_rs->num_rows();
		
		$school_rs =  $this->db->query("select * from schools");
		$school_count =  $school_rs->num_rows();
		
		if(count($school_ids)==$school_count)
		{
			$school_condition = " ";
		}
		else{
			$school_condition = " and school_id in ($schools_in)  ";
		}
		
		if(count($item_ids)==$item_count)
		{
			$item_condition = " ";
		}
		else{
			$item_condition = " and item_id in ($item_ids_in)   ";
		}
		
		$tbl_purchased = "tbl_purchased_".uniqid();
		    $sql   = "		create temporary table  $tbl_purchased 	
						select school_id,item_id,sum(purchase_quantity) as purchase_qty   from balance_sheet consolidated_purchased 
						where entry_date between '$from_date' and '$to_date' $school_condition $item_condition  group by school_id,item_id";
		
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
							where entry_date between '$from_date' and '$to_date' $school_condition $item_condition  group by school_id,item_id";
		
	  $this->db->query($sql);
		
		$tbl_opening_balances = "tbl_opening_balances".uniqid();
		$sql   = "	create temporary table  $tbl_opening_balances 
		select bs_opening.school_id, bs_opening.item_id,bs_opening.entry_date,opening_entries_selected.entry_date max_selected_date,bs_opening.closing_quantity as opening_quantity from 
		balance_sheet bs_opening inner join 
		(SELECT school_id,item_id,max(entry_date) as entry_date from balance_sheet where entry_date<'$from_date'
		$school_condition $item_condition group by school_id,item_id)  as opening_entries_selected
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
		// $this->school_model->initiate_item($school_id,$item_id,$date);
		if($this->input->post('submit')=="Submit")
		{
		
			/*  $qrs = "select * from balance_sheet where school_id='$school_id' and item_id='$item_id' and entry_date='$date'";
			 
			$qp_rs = $this->db->query($qrs);
			if($qp_rs->num_rows()==0)				
			{
				$sql = "insert into balance_sheet set 
							purchase_quantity='".$this->input->post('pqty')."',
							purchase_price='".$this->input->post('pprice')."',

							
							session_1_new_qty='".$this->input->post('bf_qty')."',
							session_1_new_price='".$this->input->post('bf_price')."',							
							session_1_qty='".$this->input->post('bf_qty')."',
							session_1_price='".$this->input->post('bf_price')."',
							
							
							session_2_new_qty='".$this->input->post('lu_qty')."',
							session_2_new_price='".$this->input->post('lu_price')."',
							session_2_qty='".$this->input->post('lu_qty')."',
							session_2_price='".$this->input->post('lu_price')."',

							session_3_new_qty='".$this->input->post('sn_qty')."',
							session_3_new_price='".$this->input->post('sn_price')."',
							session_3_qty='".$this->input->post('sn_qty')."',
							session_3_price='".$this->input->post('sn_price')."',

							session_4_new_qty='".$this->input->post('di_qty')."',
							session_4_new_price='".$this->input->post('di_price')."',
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

							session_1_new_qty='".$this->input->post('bf_qty')."',
							session_1_new_price='".$this->input->post('bf_price')."',							
							session_1_qty='".$this->input->post('bf_qty')."',
							session_1_price='".$this->input->post('bf_price')."',
							
							
							session_2_new_qty='".$this->input->post('lu_qty')."',
							session_2_new_price='".$this->input->post('lu_price')."',
							session_2_qty='".$this->input->post('lu_qty')."',
							session_2_price='".$this->input->post('lu_price')."',

							session_3_new_qty='".$this->input->post('sn_qty')."',
							session_3_new_price='".$this->input->post('sn_price')."',
							session_3_qty='".$this->input->post('sn_qty')."',
							session_3_price='".$this->input->post('sn_price')."',

							session_4_new_qty='".$this->input->post('di_qty')."',
							session_4_new_price='".$this->input->post('di_price')."',
							session_4_qty='".$this->input->post('di_qty')."',
							session_4_price='".$this->input->post('di_price')."' 
							
							where 
							school_id='$school_id' and 	item_id='$item_id' and entry_date='$date'
						";
			 }*/
			 
			$entry_id = $this->input->post('entry_id');
			$sql = "update balance_sheet set 
							purchase_quantity='".$this->input->post('pqty')."',
							purchase_price='".$this->input->post('pprice')."',

							session_1_new_qty='".$this->input->post('bf_qty')."',
							session_1_new_price='".$this->input->post('bf_price')."',							
							session_1_qty='".$this->input->post('bf_qty')."',
							session_1_price='".$this->input->post('bf_price')."',
							
							
							session_2_new_qty='".$this->input->post('lu_qty')."',
							session_2_new_price='".$this->input->post('lu_price')."',
							session_2_qty='".$this->input->post('lu_qty')."',
							session_2_price='".$this->input->post('lu_price')."',

							session_3_new_qty='".$this->input->post('sn_qty')."',
							session_3_new_price='".$this->input->post('sn_price')."',
							session_3_qty='".$this->input->post('sn_qty')."',
							session_3_price='".$this->input->post('sn_price')."',

							session_4_new_qty='".$this->input->post('di_qty')."',
							session_4_new_price='".$this->input->post('di_price')."',
							session_4_qty='".$this->input->post('di_qty')."',
							session_4_price='".$this->input->post('di_price')."' 
							
							where  entry_id='".$entry_id."'"; 
						
						
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
				$form_data['entry_id'] = $qp_data->entry_id;	
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
			  $year = $this->input->post('year');
			  redirect('admin/subadmin/bulkdataentry/'.$school_id."/".$item_id."/".$month_id."/".$year);
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
	function bulkdataentry($school_id,$item_id,$month_id,$year=2017)
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
		$data["year"] = $year ;
	 
	 
		$it_sql= "select * from items where status='1' and item_id='$item_id' order by priority asc";
		$itrs = $this->db->query($it_sql);
		$data["items_details"] = $itrs->row() ;
			
		  $qrs = "select * from balance_sheet where school_id='$school_id' and item_id='$item_id'  and MONTH(entry_date)='$month_id' and YEAR(entry_date)='$year'";
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
                $this->excel->getActiveSheet()->setCellValue('A1',$this->config->item('society_name') );
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
				ob_end_clean();ob_start();
                $objWriter->save('php://output');
                 
    }
 function today_consumed_balancenew_old() {
		 $school_id = 0;
		 $school_code = 
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
	
	
	
	
	
	
	
	
	
	
	
	
	   function today_consumed_balancenew() {
		 $school_id = 0;
		 $school_code = '';
		 
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
		
		$trs_sql  = "select sum(round(((session_1_qty*session_1_price) +(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+(session_4_qty*session_4_price)),2)) as consumed_total
					from balance_sheet where school_id='$school_id' and entry_date='$school_date'";
		
		/*if($_GET['test']==1)
			echo $trs_sql ;
		*/
		$trs = $this->db->query($trs_sql);
					
		if($trs->num_rows()>0)
		{
			$tdata = $trs->row();
			$today_consumed_Amount = $tdata->consumed_total;
		}
		/**********Calculate attendence ***************/
		
		$daily_amount  = 0.00;
		
		/*
			Get Amounts				
		
		$price_sql = "select * from group_prices";
		$price_rs = $this->db->query($price_sql);
		$student_prices = array();
		foreach($price_rs->result() as $stu_price){
			$student_prices[$stu_price->group_code] = $stu_price->amount;
		}

*/ 
		
		$srs = $this->db->query("select * from schools where school_id='$school_id'");
		$sch_data = $srs->row();
		$school_amount_category = $sch_data->amount_category;
		$price_sql = "select * from group_prices  where category=? and ? between start_date and end_date";
		$price_rs = $this->db->query($price_sql,array($school_amount_category,$school_date));
		//echo $this->db->last_query();
		$student_prices = array();
		foreach($price_rs->result() as $stu_price){
			$student_prices[$stu_price->group_code] = $stu_price->amount;
		}
		//print_a($student_prices);
		$data['student_prices'] = $student_prices;

		/******get attendence ******/
			
		$attendece  = 0;
		$group_1_attendence = 0;
		$group_2_attendence = 0;
		$group_3_attendence = 0;
		$group_1_price = 0;
		$group_2_price = 0;
		$group_3_price = 0;
		
		$group_1_perday_price = 0;
		$group_2_perday_price = 0;
		$group_3_perday_price = 0;
		
		
		$days_sql = "SELECT DAY( LAST_DAY( '$school_date' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		  $days_count = $days_row->days ;
		//print_a($student_prices);
		
		$group_1_perday_price = ($student_prices['gp_5_7']/$days_count);
		$group_2_perday_price =($student_prices['gp_8_10']/$days_count);
		$group_3_perday_price = ($student_prices['gp_inter']/$days_count);
		
		$atters_sql = "select * from school_attendence where school_id='$school_id' and entry_date='$school_date'";
		$atters = $this->db->query($atters_sql);
		if($atters->num_rows()>0)
		{
			$attedata = $atters->row();
			
			$group_1_attendence =  $attedata->cat1_attendence  +  $attedata->cat1_guest_attendence;
			$group_2_attendence =  $attedata->cat2_attendence  + $attedata->cat2_guest_attendence;
			$group_3_attendence =  $attedata->cat3_attendence  + $attedata->cat3_guest_attendence;
			 
			$group_1_price =  $group_1_attendence * ($student_prices['gp_5_7']/$days_count);
			$group_2_price =  $group_2_attendence * ($student_prices['gp_8_10']/$days_count);
			$group_3_price =  $group_3_attendence *  ($student_prices['gp_inter']/$days_count);
			
			
			
			$attendece =  $attedata->present_count;
			$attendece2 = $group_1_attendence + $group_2_attendence + $group_3_attendence;
		}
		$data['student_prices'] = $student_prices;
		$data['days_count'] = $days_count;
		$data['group_1_attendence'] = $group_1_attendence;
		$data['group_2_attendence']= $group_2_attendence ;
		$data['group_3_attendence'] = $group_3_attendence ;
		$data['group_1_price'] = number_format($group_1_price,2);
		$data['group_2_price'] = number_format($group_2_price,2);
		$data['group_3_price'] = number_format($group_3_price,2);
		
		$data['group_1_perday_price'] = number_format($group_1_perday_price,4);
		$data['group_2_perday_price'] = number_format($group_2_perday_price,4);
		$data['group_3_perday_price'] = number_format($group_3_perday_price,4);
		 
		//echo $attendece2 ;
	//	echo $group_1_attendence ,"--",$group_2_attendence ,"---",$group_3_attendence ,"---"  ;
		//echo "<br>",$group_1_price ,"--",$group_2_price ,"---",$group_3_price ,"---"  ;
		$today_allowed_Amount = $group_1_price + $group_2_price + $group_3_price;
		/**************************************/
		
        $data["reportdate"] =  date('d-M-Y',strtotime($this->input->post('school_date')));
        $data["school_name"] = $today_allowed_Amount;
        $data["per_stundent"] = $daily_amount;
        $data["attendence"] = $attendece;
		
        $data["today_allowed_Amount"] = number_format($today_allowed_Amount,2);
        $data["today_consumed_Amount"] = $today_consumed_Amount;
        $data["today_remaining_Amount"] = number_format($today_allowed_Amount -  $today_consumed_Amount,2);
		
        $data["school_code"] = $school_code;
        $data["module"] = "admin";
        $data["view_file"] = "school_consumed";
        echo Modules::run("template/admin", $data);
    }
	
	
	function attendencereports_months($type_of_report='months')
	{
		$this->form_validation->set_rules('year', 'Year', 'required');              
		$this->form_validation->set_rules('month', 'Month', 'required');  
	$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");		
		$data['months']  = $months ;
		$item_names   = array();
		$data = array();
		//print_a($_POST);
		if($this->form_validation->run() == true && $this->input->post('year') !=""  )
		{
				//Get Group Amounts
			//	print_a($_POST);
			
			
			
			
			/*$price_sql = "select * from group_prices";
			$price_rs = $this->db->query($price_sql);
			$student_prices = array();
			foreach($price_rs->result() as $stu_price){
				$student_prices[$stu_price->group_code] = $stu_price->amount;
			}
			
			*/
			
			$year =  $this->input->post('year') ;
			$month =  $this->input->post('month') ;
			$school_date = $year."-".$month."-01";
		
		 
		$price_sql = "select * from group_prices  where category='normal' and ? between start_date and end_date";
		$price_rs = $this->db->query($price_sql,array($school_date));
		 //echo $this->db->last_query();
		$student_prices = array();
		foreach($price_rs->result() as $stu_price){
			$student_prices[$stu_price->group_code] = $stu_price->amount;
		}
			
			
			
			
			
			$tyear =  $this->input->post('year') ;
			$tmonth =  $this->input->post('month') ;
			$report_date = $this->input->post('year') ."-". $this->input->post('month')."-01"; 
			$days_sql = "SELECT DAY( LAST_DAY( '$report_date' ) ) as days";
			$days_row  = $this->db->query($days_sql)->row();
			$days_count = $days_row->days ;
			
			$group_1_per_day= $student_prices['gp_5_7']/$days_count;
			$group_2_per_day= $student_prices['gp_8_10']/$days_count;
			$group_3_per_day= $student_prices['gp_inter']/$days_count;
			
			
			$school_attendence = array();
				
			
			//$school_attendence['month_days'] = $days_count;
		//	$school_attendence['student_prices'] = $student_prices;
			  
		  
			 
			 
			$report_date_frmted = $months[$tmonth];
			$report_date_toted =  $tyear ;
			
			$attendece_allowed_table_temp = "tempatte_".rand(1000,1000000);
			   $attendece_allowed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $attendece_allowed_table_temp AS (
			 
			SELECT s.school_id,s.school_code,s.name,s.strength,
			
			
			truncate(sum(
				((cat1_attendence + cat1_guest_attendence) * $group_1_per_day) + 
				((cat2_attendence + cat2_guest_attendence)   * $group_2_per_day) + 
				((cat3_attendence+ cat3_guest_attendence)   * $group_3_per_day)  
			),2) as  allowed_amt ,
			sum(present_count) as attendence ,
			sum(cat1_attendence+ cat1_guest_attendence) as group1_att,
			sum(cat2_attendence+ cat2_guest_attendence) as group2_att,
			sum(cat3_attendence+ cat3_guest_attendence) as group3_att,
			
			sum((cat1_attendence+ cat1_guest_attendence)) *  $group_1_per_day as cat1_amount,
			sum((cat2_attendence+ cat2_guest_attendence)) *  $group_2_per_day as cat2_amount,
			sum((cat3_attendence+ cat3_guest_attendence)) *  $group_3_per_day as cat3_amount
			
			FROM `school_attendence` sa inner join schools s on s.school_id = sa.school_id and s.is_school=1 
					and  DATE_FORMAT( entry_date,  '%m-%Y' ) =  '$tmonth-$tyear' group by sa.school_id );";
			 
			 $this->db->query($attendece_allowed_table_sql);
			 
			 
			$consumed_table_temp  = "tempconsumed_".rand(1000,1000000);
			  $consumed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $consumed_table_temp AS ( SELECT  school_id,
						TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + 
						(`session_2_qty` * `session_2_price`) + 
						(`session_3_qty` * `session_3_price`) + 
						(`session_4_qty` * `session_4_price`)  ),2)
						as today_consumed from balance_sheet where  DATE_FORMAT( entry_date,  '%m-%Y' ) =  '$tmonth-$tyear' group by school_id); ";
						
			$this->db->query($consumed_table_sql);

				
//kiran
			  $user_id = $this->session->userdata("user_id") ;
			// print_a($this->session->all_userdata());
			if($this->session->userdata("school_code")!="10100" && $this->session->userdata("school_code")!="BC10100") {
				$dist_id = $this->session->userdata("district_id") ;
						$sch_scls = $this->db->query("select * from schools where district_id  = '$dist_id' ");
			}
			else{
				 
						$sch_scls = $this->db->query("select * from schools ");
			}
			 
			$schools_list = array();
			foreach($sch_scls->result() as $scrow)
			{
				$schools_list[] = $scrow->school_id;
			}
			
		  
			 $joined_ary = "select att.*,con.today_consumed from $attendece_allowed_table_temp att inner join $consumed_table_temp  con on att.school_id=con.school_id";
			 $trs  = $this->db->query($joined_ary);
			
				 foreach($trs->result() as $row)
				 {
					
					 if(!in_array($row->school_id,$schools_list))
							continue;
						 
					$school_attendence[$row->school_code] =  array(
							'school_id'=>$row->school_id,
							'name'=>$row->name,
							'strength'=>$row->strength,
							'days_count'=>$days_count,
							'grp1_att'=>$row->group1_att,
							
							'grp2_att'=>$row->group2_att,
							'grp3_att'=>$row->group3_att,
							'group_1_per_day'=>number_format($group_1_per_day,2),
							'group_2_per_day'=>number_format($group_2_per_day,2),
							'group_3_per_day'=>number_format($group_3_per_day,2),
							
							'cat1_amount'=>number_format($row->cat1_amount,2),
							'cat2_amount'=>number_format($row->cat2_amount,2),
							'cat3_amount'=>number_format($row->cat3_amount,2),
							
							'attendence'=>$row->attendence,
							
							'allowed_amt'=>$row->allowed_amt,
							'consumed_amt'=>$row->today_consumed,
							'remaining_amt'=>number_format($row->allowed_amt - $row->today_consumed,2),
							'rdate'=>$report_date_frmted,
							'tdate'=> $report_date_toted
							);
				 }
				 
					//$consumed_table_temp
					//print_a($school_attendence);
					ksort($school_attendence);
					unset($school_attendence['85000']);
				$school_attendence['extra']['month_days'] = $days_count;
				$school_attendence['extra']['student_prices'] = $student_prices;

				if($this->input->post('submit')=="Download Report")
				{
					$data['sel_month'] = $tmonth;
					$data['sel_year'] = $tyear;
					 
					$this->attendence_consumed_report($school_attendence,$report_date_frmted,$report_date_toted);
					die;
				}
				else{
					$data['sel_month'] = $tmonth;
					$data['sel_year'] = $tyear;
					$data['rdate'] = $report_date_frmted;
					$data['tdate'] =  $report_date_toted;
					$data['attendencereport'] = $school_attendence;
				}

		}
		$school_rs = $this->db->query("select * from schools ");
		$schools_info = array();
		foreach($school_rs->result() as $schrow)
		{
			$schools_info[$schrow->school_code]['district'] = $schrow->district_name;
			$schools_info[$schrow->school_code]['region'] = $schrow->region;
			$schools_info[$schrow->school_code]['school_type'] = $schrow->school_type;
		}
		$data["schools_info"] = $schools_info;
		$data["module"] = "admin";
		
		if($type_of_report=="dates")
		{
			$data["view_file"] = "school/attendencereports_dates";
		}
		else {
			$data["view_file"] = "school/attendencereports_months";
		}
		
		
        echo Modules::run("template/admin", $data);
         
	}
	

function attendencereports_old()
	{
		$this->form_validation->set_rules('fromdate', 'Date', 'required');              
		$this->form_validation->set_rules('todate', 'Date', 'required');              
		 
		$item_names   = array();
		$data = array();
		if($this->form_validation->run() == true && $this->input->post('fromdate') !=""  )
		{
			
			$from_month = date('m',strtotime($this->input->post('fromdate')));
			$to_month = date('m',strtotime($this->input->post('todate')));
			if($from_month != $to_month)
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Dates should be with in the same month.</div>');
				redirect('admin/subadmin/attendencereports');
			}
			
			
			$dco_schools = array();
			if($this->session->userdata("is_dco")=="1"){
				$trs= $this->db->query("select school_id,district_id  from schools where district_id='".$this->session->userdata("district_id")."'");
				foreach($trs->result() as $trow)
				{
					$dco_schools[] = $trow->school_id;
				}
			
			}
			
			
			 
			$report_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
			$report_todate = date('Y-m-d',strtotime($this->input->post('todate')));
			$report_date_frmted = date('d-M-Y',strtotime($this->input->post('fromdate')));
			$report_date_toted = date('d-M-Y',strtotime($this->input->post('todate')));
			
			$attendece_allowed_table_temp = "tempatte_".rand(1000,1000000);
			  $attendece_allowed_table_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $attendece_allowed_table_temp AS (
			SELECT s.school_id,s.school_code,s.name,s.strength, sum(present_count) as attendence,s.daily_amount,sum(sa.present_count) * s.daily_amount as allowed_amt FROM `school_attendence` sa inner join schools s on s.school_id = sa.school_id 
					and (entry_date between '$report_date' and '$report_todate') and s.is_school=1 group by sa.school_id );";
			 
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
			//print_a($dco_schools);
				 foreach($trs->result() as $row)
				 {
					 if($this->session->userdata("is_dco")=="1" &&  !in_array($row->school_id,$dco_schools)){
						 //skip if logged user is dco , skip if district_id not matches 
						 continue;
						 
					 }
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
					ksort($school_attendence);
					unset($school_attendence['85000']);
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
	
	
	function attendencereports()
	{
		$this->form_validation->set_rules('fromdate', 'Date', 'required');              
		$this->form_validation->set_rules('todate', 'Date', 'required');              
		 
		$item_names   = array();
		$data = array();
		if($this->form_validation->run() == true && $this->input->post('fromdate') !=""  )
		{
			
			$from_month = date('m',strtotime($this->input->post('fromdate')));
			$to_month = date('m',strtotime($this->input->post('todate')));
			$to_year= date('Y',strtotime($this->input->post('todate')));
			if($from_month != $to_month)
			{
				$this->session->set_flashdata('message', '<div class="alert alert-danger">Dates should be with in the same month.</div>');
				redirect('admin/subadmin/attendencereports');
			}
			
			
			$dco_schools = array();
			if($this->session->userdata("is_dco")=="1"){
				$trs= $this->db->query("select school_id,district_id  from schools where district_id='".$this->session->userdata("district_id")."'");
				foreach($trs->result() as $trow)
				{
					$dco_schools[] = $trow->school_id;
				}
			
			}
			
			
			 
			$report_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
			$report_todate = date('Y-m-d',strtotime($this->input->post('todate')));
			$report_date_frmted = date('d-M-Y',strtotime($this->input->post('fromdate')));
			$report_date_toted = date('d-M-Y',strtotime($this->input->post('todate')));
			
			 $schools_rs = $this->db->query("select * from schools where is_school='1' and school_code not like '%85000%' ");
			 $schools_list = array();
			 foreach($schools_rs->result() as $row)
			 {
				$schools_list[$row->school_id] = array( 
							'school_code'=>$row->school_code,
							'name'=>$row->name,
							'strength'=>$row->strength,
							'attendence'=>'0', 
							'allowed_amt'=>'0',
							'district_id'=>$row->district_id,
							'consumed_amt'=>'0',
							'remaining_amt'=>'0',
							'rdate'=>$report_date_frmted,
							'tdate'=> $report_date_toted);
			 }
			  
			 
			 $consumed_table_sql  = "  SELECT  school_id,
						TRUNCATE( sum( (`session_1_qty` * `session_1_price`) + 
						(`session_2_qty` * `session_2_price`) + 
						(`session_3_qty` * `session_3_price`) + 
						(`session_4_qty` * `session_4_price`)  ),2)
						as total_consumed from balance_sheet where (entry_date between '$report_date' and '$report_todate') group by school_id  ";
						
			$consumed_rs = $this->db->query($consumed_table_sql);
			foreach($consumed_rs->result() as $row)
			 {
				 
				$schools_list[$row->school_id]['consumed_amt'] =$row->total_consumed;
			 }


			
			 
			 $school_date = $to_year."-".$to_month."-01";
			$price_sql = "select * from group_prices  where category='normal' and ? between start_date and end_date";
			$price_rs = $this->db->query($price_sql,array($school_date)); 
			$student_prices = array();
			foreach($price_rs->result() as $stu_price){
				$student_prices[$stu_price->group_code] = $stu_price->amount;
			}
			
			$days_sql = "SELECT DAY( LAST_DAY( '$school_date' ) ) as days";
		$days_row  = $this->db->query($days_sql)->row();
		  $days_count = $days_row->days ;
			$group_1_per_day= $student_prices['gp_5_7']/$days_count;
			$group_2_per_day= $student_prices['gp_8_10']/$days_count;
			$group_3_per_day= $student_prices['gp_inter']/$days_count;
			
			$sql_attendence = "select school_id,
										truncate(sum(
											((cat1_attendence + cat1_guest_attendence) * $group_1_per_day) + 
											((cat2_attendence + cat2_guest_attendence)   * $group_2_per_day) + 
											((cat3_attendence+ cat3_guest_attendence)   * $group_3_per_day)  
										),2) as  allowed_amt ,
										sum(present_count) as attendence  from school_attendence where (entry_date between '$report_date' and '$report_todate') group by school_id ";
				 
				$allowed_rs = $this->db->query($sql_attendence);
			foreach($allowed_rs->result() as $row)
			 {
				$schools_list[$row->school_id]['attendence'] =$row->attendence;
				$schools_list[$row->school_id]['allowed_amt'] =$row->allowed_amt;
				$schools_list[$row->school_id]['remaining_amt'] =number_format($row->allowed_amt - $schools_list[$row->school_id]['consumed_amt'] ,2);
			 } 
				 
			$school_attendence = array();	
			foreach($schools_list as $sch_id=>$schdata)
			{
				if(count($dco_schools)>0 && !in_array($sch_id,$dco_schools))
				 {
					continue;
				 }else{
					 if($schdata['school_code']!=""){
								$school_attendence[$schdata['school_code']] = $schdata;
					 }
				 }
			}
				
				
				
				//$consumed_table_temp
				ksort($school_attendence);
				unset($school_attendence['85000']);
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] = date('d-M-Y',strtotime($this->input->post('todate')));
					$this->attendence_consumed_report_between_dates($school_attendence,$report_date_frmted,$report_date_toted);
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
	
	public function attendence_consumed_report($school_attendence,$report_date_frmted,$report_date_toted,$schools_info=array())
    {
		$school_rs = $this->db->query("select * from schools ");
		$schools_info = array();
		foreach($school_rs->result() as $schrow)
		{
			$schools_info[$schrow->school_code]['district'] = $schrow->district_name;
			$schools_info[$schrow->school_code]['region'] = $schrow->region;
			$schools_info[$schrow->school_code]['school_type'] = $schrow->school_type;
		}
		$data["schools_info"] = $schools_info;
		
		
		
		$extra_data = $school_attendence['extra'];
				unset($school_attendence['extra']);
          
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Attendance & Consumption Report');
               
                $this->excel->getActiveSheet()->setCellValue('A1', 'Attendance and Consumption Report  for month of  '.$months[$this->input->post('month')]." - ". $this->input->post('year')."  ( ".$extra_data['month_days']. ") days)");
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

				$this->excel->getActiveSheet()->getStyle('A3:Z3')->applyFromArray( $style_header );

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Category 1 Amount " );
				$this->excel->getActiveSheet()->setCellValue('B2',  $extra_data['student_prices']['gp_5_7']);	
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				$this->excel->getActiveSheet()->setCellValue('C2', " Category 2 Amount " );
				$this->excel->getActiveSheet()->setCellValue('D2',  $extra_data['student_prices']['gp_8_10']);	
				$this->excel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);				
				 
				
				$this->excel->getActiveSheet()->setCellValue('E2', " Category 3 Amount " );
				$this->excel->getActiveSheet()->setCellValue('F2',  $extra_data['student_prices']['gp_inter']);	
				$this->excel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);				
				 
				
				
				
				////////////////////////////////////////////////////////////////////////////////////////////////
				$this->excel->getActiveSheet()->setCellValue('A3', 'School Name');
				$this->excel->getActiveSheet()->setCellValue('B3', 'School Code');		
				$this->excel->getActiveSheet()->setCellValue('C3', 'Region');
				$this->excel->getActiveSheet()->setCellValue('D3', 'District');				
				 
				$this->excel->getActiveSheet()->setCellValue('E3', 'Cat 1 Attendence');
				$this->excel->getActiveSheet()->setCellValue('F3', 'Cat 1 Per Day');
				$this->excel->getActiveSheet()->setCellValue('G3', 'Cat 1 Amount');
				
				$this->excel->getActiveSheet()->setCellValue('H3', 'Cat 2 Attendence');
				$this->excel->getActiveSheet()->setCellValue('I3', 'Cat 2 Per Day');
				$this->excel->getActiveSheet()->setCellValue('J3', 'Cat 2 Amount');
				
					$this->excel->getActiveSheet()->setCellValue('K3', 'Cat 3 Attendence');
				$this->excel->getActiveSheet()->setCellValue('L3', 'Cat 3 Per Day');
				$this->excel->getActiveSheet()->setCellValue('M3', 'Cat 3 Amount');
				
				
				$this->excel->getActiveSheet()->setCellValue('N3', 'Attendence');				
				
				$this->excel->getActiveSheet()->setCellValue('O3', 'Allowed Amount');				
				$this->excel->getActiveSheet()->setCellValue('P3', 'Consumption Amoun');
				$this->excel->getActiveSheet()->setCellValue('Q3', 'Remaining Amount');
				$this->excel->getActiveSheet()->setCellValue('R3', 'School Type');
				
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:S3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                //$this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				foreach($school_attendence as $school_code=>$school_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data['name']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_code);
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $schools_info[$school_code]['region']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i,   $schools_info[$school_code]['district']);
					
					
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $school_data['grp1_att']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $school_data['group_1_per_day']);
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $school_data['cat1_amount']);
					
					
					$this->excel->getActiveSheet()->setCellValue('H'.$i, $school_data['grp2_att']);
					$this->excel->getActiveSheet()->setCellValue('I'.$i, $school_data['group_2_per_day']);
					$this->excel->getActiveSheet()->setCellValue('J'.$i, $school_data['cat2_amount']);
					
					$this->excel->getActiveSheet()->setCellValue('K'.$i, $school_data['grp3_att']);
					$this->excel->getActiveSheet()->setCellValue('L'.$i, $school_data['group_3_per_day']);
					$this->excel->getActiveSheet()->setCellValue('M'.$i, $school_data['cat3_amount']);
					
					
					$this->excel->getActiveSheet()->setCellValue('N'.$i,$school_data['attendence']);
					
					
					$this->excel->getActiveSheet()->setCellValue('O'.$i, $school_data['allowed_amt']);
					$this->excel->getActiveSheet()->setCellValue('P'.$i,   $school_data['consumed_amt']);
					$this->excel->getActiveSheet()->setCellValue('Q'.$i,   $school_data['remaining_amt']);
					$this->excel->getActiveSheet()->setCellValue('R'.$i,  $schools_info[$school_code]['school_type']);
					
					
					  $this->excel->getActiveSheet()->getStyle('S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					 
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
				ob_end_clean();ob_start();
                $objWriter->save('php://output');
                 
    }
	  function schoolattendence() {
		 $school_id = 0;
		 $school_code = '';
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		if($this->input->post('school_code')!="")
		 {
			  $school_code = $this->input->post('school_code');
			  
			 
			 $srs = $this->db->query("select * from users where school_code='$school_code'") ;
			  $school_data = $srs->row();
		
			 $school_id = $school_data->school_id;		
			 redirect('admin/subadmin/attendencelist/'.$school_id );
		 }
		 
		 $data["module"] = "admin";
        $data["view_file"] = "school_attendence";
        echo Modules::run("template/admin", $data);
		
	  }
	  
	  
	      

	  function itembalancesform() {
		 $school_id = 0;
		 $school_code = '';
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		if($this->input->post('items_id')!="")
		 {
			    $items_id = $this->input->post('items_id');
			 
			 
			 
			 redirect('admin/subadmin/schoolbalances/'.$items_id );
		 }
		  $items_sql = "select * from items ";
			  $items_rs =  $this->db->query($items_sql);
		 $data["items"] = $items_rs;
		 $data["module"] = "admin";
        $data["view_file"] = "iteml_balances_form";
        echo Modules::run("template/admin", $data);
		
	  }
	  function itembalances($school_id=0)
		{
			  $items_sql = "select * from items ";
			  $items_rs =  $this->db->query($items_sql);
			  $items_names = array();
			  foreach($items_rs->result() as $item_data)
			  {
				   $items_names[ $item_data->item_id] =  $item_data->telugu_name." - ".$item_data->item_name;
			  }
			  
			  
			  
			  $max_sql = "select max(entry_id) as entry_id from balance_sheet  	where  school_id='$school_id'  group by item_id  ";
			  $max_rs =  $this->db->query($max_sql);
			  $max_item_ids = array();
			  foreach($max_rs->result() as $maxdata)
			  {
				   $max_item_ids[] = $maxdata->entry_id;
			  }
			  $max_ids_implode = implode(",",$max_item_ids);
			  
			   $sql  = "select  item_id, closing_quantity    FROM balance_sheet WHERE  entry_id in ($max_ids_implode )  order by closing_quantity desc ";

			$rs  = $this->db->query($sql);

			
			$data["items_names"] = $items_names;
			$data["rset"] = $rs;
			$school_info = $this->school_model->get_schooldata($school_id);

			$data["school_info"] = $school_info;
			$data["module"] = "admin";
			$data["module"] = "admin";
			$data["view_file"] = "itembalances_report";
			echo Modules::run("template/admin", $data);
		}
		  function schoolbalancesform() {
		 $item_id = 0;
		 $school_code = '';
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		if($this->input->post('item_id')!="")
		 {
			    $item_id = $this->input->post('item_id'); 
			 redirect('admin/subadmin/schoolbalances/'.$item_id );
		 }
		 
		 $data["module"] = "admin";
        $data["view_file"] = "school_attendence";
        echo Modules::run("template/admin", $data);
		
	  }
	  
		function schoolbalances($item_id=0)
		{
			  $schools_sql  = "select * from schools ";
			  $schools_rs =  $this->db->query($schools_sql);
			  $school_names  = array();
			  $district_ids = array();
			  $school_codes = array();
			  foreach($schools_rs->result() as $school_data)
			  {
				   $school_names[ $school_data->school_id] =  $school_data->name;
				   $school_codes[ $school_data->school_id] =  $school_data->school_code;
				   $district_ids[ $school_data->school_id] =  $school_data->district_id;
			  }
			  
			 // print_a($district_ids,1);
			 
			  $districts_sql  = "select * from districts ";
			  $districts_rs =  $this->db->query($districts_sql);
			  
			  $district_names = array(); 
			  foreach($districts_rs->result() as $districts_data)
			  {
				  
				   $district_names[ $districts_data->district_id] =  $districts_data->name;
			  }
			 // print_a($district_names,1);
			  
			  $max_sql = "select max(entry_id) as entry_id from balance_sheet  	where  item_id='$item_id'  group by school_id  ";
			  $max_rs =  $this->db->query($max_sql);
			  $max_item_ids = array();
			  foreach($max_rs->result() as $maxdata)
			  {
				   $max_item_ids[] = $maxdata->entry_id;
			  }
			  $max_ids_implode = implode(",",$max_item_ids);
			  
			   $sql  = "select  school_id,item_id, closing_quantity    FROM balance_sheet WHERE  entry_id in ($max_ids_implode )  order by closing_quantity desc ";

			$rs  = $this->db->query($sql);
			
			$bs_data_rows = array();
			  foreach($rs->result() as $bs_data)
			  {
				   $bs_data_rows[  $school_codes[ $bs_data->school_id]  ] =  $bs_data;
				   
			  }
			
			$data["district_ids"] = $district_ids;
			$data["district_names"] = $district_names;
			$data["bs_data_rows"] = $bs_data_rows;
			$data["school_codes"] = $school_codes;
			$data["school_names"] = $school_names;
			$data["rset"] = $rs;
			 
			$data["item_info"] = $this->school_model->get_itemdetails($item_id);
			 
			$data["module"] = "admin";
			$data["module"] = "admin";
			$data["view_file"] = "school_balances";
			echo Modules::run("template/admin", $data);
		}
	  
	  
	    
		  function attendencelist($school_id ){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('school_attendence');
			$crud->where('school_id',$school_id );
			$crud->order_by('entry_date','desc');
			$crud->set_subject('Attendance');
			
			 $crud->callback_column('entry_date',array($this,'date_formatdisplay'));
			 $crud->callback_edit_field('entry_date',array($this,'date_formatdisplay'));
			//
			//echo  $this->session->userdata("user_id"),"--";
					if(  $this->session->userdata("user_id")>2)
					{
						$crud->unset_add(); 
						$crud->unset_edit(); 
					}						
			
			
			 
            $crud->unset_delete();
			$crud->columns(array('entry_date','present_count','cat1_attendence','cat1_guest_attendence','cat2_attendence','cat2_guest_attendence','cat3_attendence','cat3_guest_attendence'));
			
			$crud->display_as('cat1_guest_attendence','Category 1 Guest Attendance')
				->display_as('cat2_guest_attendence','Category 2 Guest Attendance')
				->display_as('cat3_guest_attendence','Category 3 Guest Attendance');
			$crud->display_as('cat1_attendence','Category 1 Attendance')
				->display_as('cat2_attendence','Category 2  Attendance')
				->display_as('cat3_attendence','Category 3 Attendance');
			$crud->display_as('present_count','Total');
			
			$crud->edit_fields(array('entry_date','cat1_attendence','cat2_attendence','cat3_attendence', 'cat1_guest_attendence', 'cat2_guest_attendence','cat3_guest_attendence'));
			$crud->callback_after_update(array($this, 'update_attendence_total_count'));
			 
			 
			//$crud->field_type('entry_date', 'readonly');
			// $crud->field_type('cat1_attendence', 'readonly' );
			// $crud->field_type('cat2_attendence', 'readonly' );
			// $crud->field_type('cat3_attendence', 'readonly' );
			 

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Attendance entries of " .$this-> get_school_name($school_id);
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	
	function get_school_name($sid){
		$srs = $this->db->query("select * from schools where school_id='$sid'") ;
			  $school_data = $srs->row();
		
			return $school_data->name;		
	}
	function update_attendence_total_count($post_array,$primary_key)
	{
			 
			 $update_attendence_sql= "update  school_attendence set present_count=(cat1_attendence+cat2_attendence+cat3_attendence+cat1_guest_attendence	+cat2_guest_attendence	+cat3_guest_attendence	) where attendence_id='$primary_key'";
			 $this->db->query( $update_attendence_sql);
			 return true;
	}
	
	
	 function itemdatesform() {
		 $school_id = 0;
		 $school_code = '';
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		 $data["display_report"] = false;
		if($this->input->post('items_id')!="")
		 {
			    $items_id = $this->input->post('items_id');
			  $data["display_report"] = true;
			 
			 
			 redirect('admin/subadmin/schoolbalances/'.$items_id );
		 }
		  $items_sql = "select * from items ";
			  $items_rs =  $this->db->query($items_sql);
		 $data["items"] = $items_rs;
		 $data["module"] = "admin";
        $data["view_file"] = "iteml_balances_dates_form";
        echo Modules::run("template/admin", $data);
		
	  }
	 /************************************************************************************************************
	 
	 
	 
	 
	 ****************************************************************************************************************/
	 
	 
	  function itembalancesformdates() {
		 $school_id = 0;
		 $school_code = '';
		 
		$today_allowed_Amount = '0.00';
		$today_consumed_Amount = '0.00';
		$today_remaining_Amount = '0.00';
		$data['result_flag']			  =  0;
		if($this->input->post('items_id')!="")
		 {
			 // print_r($_POST);
			// die;
			    $items_id = $this->input->post('items_id');
			    $from_db_date = get_db_date($this->input->post('from_date'));
			    $to_db_date = get_db_date($this->input->post('to_date'));
			    
			 $url = 'admin/subadmin/schoolbalancesbydates/'.$items_id."/".$from_db_date."/".$to_db_date ;
			 
			 //echo $url ;die;
			 redirect($url );
		 }
		  $items_sql = "select * from items ";
			  $items_rs =  $this->db->query($items_sql);
		 $data["items"] = $items_rs;
		 $data["module"] = "admin";
        $data["view_file"] = "iteml_balances_form_dates";
        echo Modules::run("template/admin", $data);
		
	  }
	  
	  
	  	function schoolbalancesbydates($item_id=0,$from_date,$to_date)
		{
			     $balance_sheet_tmp = "balance_sheet_temp_".rand(1,10000); 
			     $balance_sheet_temp_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $balance_sheet_tmp AS ( SELECT  * from balance_sheet where item_id='$item_id') ";
						
			$this->db->query($balance_sheet_temp_sql);
			
			 
			 $balance_sheet_tmp2 = "balance_sheet_temp2_".rand(1,10000); 
			     $balance_sheet_temp2_sql  = "CREATE TEMPORARY TABLE IF NOT EXISTS $balance_sheet_tmp2 AS ( SELECT  * from balance_sheet where item_id='$item_id') ";
						
			$this->db->query($balance_sheet_temp2_sql);
		
		
		  $sql   = "	 
					select school_id,item_id,opening_quantity from $balance_sheet_tmp  where entry_id in(
									SELECT  max(entry_id) as entry_id from $balance_sheet_tmp2 bt  where entry_date<='$from_date'
									  group by school_id  
								) 
				";
 
		 $opening_rs = $this->db->query($sql);
		 
  $balance_sheet_excel  = array();
			foreach($opening_rs->result() as $bs_dataa)
			  {
				  $balance_sheet_excel[$bs_dataa->school_id]['opening_quantity'] = $bs_dataa->opening_quantity;
			  }

  
		 
		$csql   = "	 
					select school_id,item_id,closing_quantity from $balance_sheet_tmp  where entry_id in(
									SELECT  max(entry_id) as entry_id  from $balance_sheet_tmp2 where entry_date<='$to_date'
									 group by school_id  
								)
				";

		 $closing_rs = $this->db->query($csql);
 
		   foreach($closing_rs->result() as $bs_dataa)
			  {
				 $balance_sheet_excel[$bs_dataa->school_id]['closing_quantity'] = $bs_dataa->closing_quantity;
			  }
			  
		 
			   $sql   = "	 
							select school_id, 
							sum((session_1_qty+session_2_qty+session_3_qty+session_4_qty)) as consumed_qty,
							sum(( 
							(session_1_qty*session_1_price) +
							(session_2_qty*session_2_price)+ 
							(session_3_qty*session_3_price) + 
							(session_4_qty*session_4_price)
							)) as consumed_amount

							from $balance_sheet_tmp consolidated_consumed 
							where entry_date between '$from_date' and '$to_date'     group by school_id ";
		
			 $consumed_rs = $this->db->query($sql); 
			 
			  
			    foreach($consumed_rs->result() as $bs_dataa)
			  {
				 $balance_sheet_excel[$bs_dataa->school_id]['consumed_qty'] = $bs_dataa->consumed_qty;
			  } 
			 // print_a( $balance_sheet_excel);
			 $data["balance_sheet_excel"] = $balance_sheet_excel;
			  
			  
			 $schools_sql  = "select * from schools ";
			  $schools_rs =  $this->db->query($schools_sql);
			  $school_names  = array();
			  $district_ids = array();
			  $school_codes = array();
			  foreach($schools_rs->result() as $school_data)
			  {
				   $school_names[ $school_data->school_id] =  $school_data->name;
				   $school_codes[ $school_data->school_id] =  $school_data->school_code;
				   $district_ids[ $school_data->school_id] =  $school_data->district_id;
			  }
			  
			 // print_a($district_ids,1);
			 
			  $districts_sql  = "select * from districts ";
			  $districts_rs =  $this->db->query($districts_sql);
			  
			  $district_names = array(); 
			  foreach($districts_rs->result() as $districts_data)
			  {
				  
				   $district_names[ $districts_data->district_id] =  $districts_data->name;
			  }
			 // print_a($district_names,1);
			  
			  $max_sql = "select max(entry_id) as entry_id from balance_sheet  	where  item_id='$item_id'  group by school_id  ";
			  $max_rs =  $this->db->query($max_sql);
			  $max_item_ids = array();
			  foreach($max_rs->result() as $maxdata)
			  {
				   $max_item_ids[] = $maxdata->entry_id;
			  }
			  $max_ids_implode = implode(",",$max_item_ids);
			  
			   $sql  = "select  school_id,item_id, closing_quantity    FROM balance_sheet WHERE  entry_id in ($max_ids_implode )  order by closing_quantity desc ";

			$rs  = $this->db->query($sql);
			
			$bs_data_rows = array();
			  foreach($rs->result() as $bs_data)
			  {
				   $bs_data_rows[  $school_codes[ $bs_data->school_id]  ] =  $bs_data;
				   
			  }
			
			$data["from_date"] =  get_display_date($from_date);
			$data["to_date"] = get_display_date($to_date);
			$data["district_ids"] = $district_ids;
			$data["district_names"] = $district_names;
			$data["bs_data_rows"] = $bs_data_rows;
			$data["school_codes"] = $school_codes;
			$data["school_names"] = $school_names;
			$data["rset"] = $rs;
			 
			$data["item_info"] = $this->school_model->get_itemdetails($item_id);
			 
			$data["module"] = "admin";
			$data["module"] = "admin";
			$data["view_file"] = "school_balances_bydates";
			echo Modules::run("template/admin", $data);
		}
	  
	  
	    
	  
	  	
	/*********************************************************************************************
	
	
	
	
	
	
	************************************************************************************************/
	
	
	public function  districts_prices($district_id=0)
	{
		
		$sql = "select * from districts where  district_id='$district_id'";
		$rs = $this->db->query($sql);
		$rowdata = $rs->row();
		$district_name = $rowdata->name;
		
		//print_a($rowdata);
		
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('flexigrid');
			 //$crud->set_theme('datatables');
			$crud->set_table('districts_prices');
			$crud->set_subject('District Prices');
			 $crud->unset_add();
           $crud->where('district_id',$district_id);
		   $crud->field_type('item_id', 'readonly');
 
		   $crud->display_as('item_id','Item');
			$crud->columns(array(  'item_id','amount'));  
			 $crud->unset_edit_fields(  'district_id','price_id' );
			 $crud->set_relation('item_id','items','telugu_name');			
				$crud->limit(500);

			$output = $crud->render();

			//$this->_example_output($output);
			$data["module"] = "cms";
			$data["view_file"] = "cms";
			$output->title =$district_name ." - Items   Prices ";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	/**************************************************	
	
	
	**************************************************/
	
	function sessionpics() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 $school_code = $this->input->post('school_code');
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			 $school_type =  $this->input->post('school_type') ;
			 
			 
			 redirect('admin/subadmin/sessionpics_report/'. $school_date."/".$school_type);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "admin";
        $data["view_file"] = "subadmin_sessionpics_form";
        echo Modules::run("template/admin", $data);
    }
	function sessionpics_report($date=null,$result_type='all')
	{
		$data['result_type'] = $result_type;
		$data['input_date'] =  date('m/d/Y',strtotime($date));
		if($date==null)
				$date = date('Ymd');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
		 $times_rs = $this->db->query("select session_id , concat('$report_date',' ',start_hour) < now() as allowed from food_pics_sessions ");
		 $times_allowed = array();
		 foreach($times_rs->result() as $timerow)
		 {
			 $times_allowed[$timerow->session_id] = $timerow->allowed;
		 }
		 $data['times_allowed'] = $times_allowed;
		 
		   $sql = "SELECT s.name, s.school_code,s.school_id, p.food_session_id, p.session_count   AS total_uploads
										FROM  schools s LEFT JOIN  
										(SELECT school_id,food_session_id,count(*) as session_count FROM  food_pics where date_format(uploaded_date,'%Y-%m-%d')='$report_date'  group by school_id,food_session_id)

										p  ON p.school_id = s.school_id    ";
		$rs = $prs  = $this->db->query($sql);
		$uploads = array();
		foreach($prs->result() as $row){
		//	print_a($row);//food_session_id


			$uploads[$row->school_id][intval($row->food_session_id)]= intval($row->total_uploads);
		}
 
		 $data['pics_uploaded'] = $uploads;
		
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		
		
					$uid  = $this->session->userdata("user_id");
					$school_code  = $this->session->userdata("school_code");
					$district_id  = $this->session->userdata("district_id");
					if( $this->session->userdata('is_dco') ==1)
					{
						$sql = "SELECT * from schools where district_id='$district_id' and  is_school='1' and school_code not like '%85000%'";
						$rs = $prs  = $this->db->query($sql); 
					}
					else
					{
							$sql = "SELECT * from schools where  is_school='1' and school_code not like '%85000%'";
							$rs = $prs  = $this->db->query($sql);
					}

					
		
		$data["rset"] = $rs;
		
		
		$data["choosendate"] = $date;
		$data["item_counts"] = $item_counts;
		$data["uploads"] = $uploads;
		$data["module"] = "admin";
		$data["module"] = "admin";
		
		
		if($result_type == "missed")
			$data["view_file"] = "subadmin_missed_session_pics_list";
		else 		
			$data["view_file"] = "subadmin_session_pics_list";
		
		
		
		echo Modules::run("template/admin", $data);
		
	}
	
	

	 /*************************************************888
	 
	 
	 
	 
	 *********************************************************/
	 
	 
	 
	function loadob2017()
	{
		$rs = $this->db->query("select * from schools ");
		foreach($rs->result() as $row)
		{
			$school_id= $row->school_id;
			$ttrs = $this->db->query("select * from items");
			foreach($ttrs->result() as $item_row)
				{
					$item_id = $item_row->item_id;
					
					$this->school_model->initiate_item_ob($school_id,$item_id,'2017-03-31');
				}
		}
		echo "Done";  
	}
	/*************************************************************************
	
	
	
	*****************************************************************************/
	function authorisations_entries() {
		 
		 if($this->input->post('school_date')!="")
		 {
			 $school_code = $this->input->post('school_code');
			 $school_date = date('Ymd',strtotime($this->input->post('school_date')));
			 
			 
			 redirect('admin/subadmin/authorisations_entries_report/'. $school_date);
			 die;
		 }
        $data["school_code"] = "";
        $data["module"] = "admin";
        $data["view_file"] = "authorise_date";
        echo Modules::run("template/admin", $data);
    }
	function authorisations_entries_report($date=null)
	{
		if($date==null)
				$date = date('Ymd');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
		 $district_condition = "";
		 
		 if($this->session->userdata("is_dco")=="1"){
				$district_condition = " and s.district_id='".$this->session->userdata("district_id")."'";
		 }
		 else{
				$district_condition = " ";
		 }
		 
			
		  $sql = "SELECT 
cc.*,s.name as schoolname,s.school_code,
date_format(session_1_time,'%d-%m-%Y %H:%i:%s') as session_1_time,	
date_format(session_2_time,'%d-%m-%Y %H:%i:%s') as session_2_time,	
date_format(session_3_time,'%d-%m-%Y %H:%i:%s') as session_3_time,	
date_format(session_4_time,'%d-%m-%Y %H:%i:%s') as session_4_time 	

 from 
caretaker_confirmation cc inner join schools s on cc.school_id= s.school_id and entry_date='$report_date' and is_school='1'  and school_code not like '%85000%'   $district_condition order by school_code asc ";
		$rs  = $this->db->query($sql);
		
		$report_date_formated = date('d-m-Y',strtotime($report_date));
		
		$data["report_date"] = $report_date_formated;
		$data["rset"] = $rs;
		$data["rdate"] = $report_date;
		
		
		$data["module"] = "admin";
		$data["module"] = "admin";
		$data["view_file"] = "school_authorised_entries_list";
		echo Modules::run("template/admin", $data);
		
	}
	
	function items()
	{
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('items');
			 
			$crud->set_subject('Items');
			
						$crud->unset_edit(); 
						$crud->unset_delete(); 
			
			$crud->columns(array('item_name','telugu_name','min_price','max_price'));
			
			
			$crud->add_fields(array('item_name','telugu_name','min_price','max_price'));
			 

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Items";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
/**************************************************************



*******************************************************************/

	  function mitems(){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('items');
		 
		 
			$crud->set_subject('Items');
			$crud->columns(array('telugu_name','item_name','min_price','max_price','item_type','item_special','status'));
			 
					 
			//$crud->unset_add(); 
            $crud->unset_delete();
			 
			
			$crud->edit_fields(array('telugu_name','item_name','min_price','max_price','item_special','status'));
			$crud->add_fields(array('telugu_name','item_name','min_price','max_price','item_type','item_special','status'));
			//$crud->field_type('item_name', 'readonly');
			 
			
			$crud->callback_after_insert(array($this, 'add_item_to_balancesheet'));

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Manage Items";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
		 
	function add_item_to_balancesheet($post_array,$primary_key)
	{
			 $entry_date = date('Y-m-d');
			 $sql = "insert into  balance_sheet(school_id,entry_date,item_id) select school_id,'$entry_date' as entry_date, '$primary_key' as item_id from schools ";
			//echo  $sql ;die;
			$this->db->query( $sql );
			 
			return true;
	}
 
	/*************************************************************************
	
	
	
	*****************************************************************************/
	function itewise_entries($type='state',$tval='0') {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 if($this->input->post('month')!="" && $this->input->post('year')!="" )
		 {
			$data['district_id']=   $district_id = $this->input->post('district_id');
			$data['school_id']=    $school_id = $this->input->post('school_id');
			$data['item_id']=    $item_id = $this->input->post('item_id');
			 
			$data['month']=    $month = $this->input->post('month');
			$data['year']=    $year = $this->input->post('year'); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			if($type=="district")
			{
					$condition = " and district_id=' $district_id' ";
					$district_rs = $this->db->query("select  name from districts where district_id='$district_id'")->row();
					$report_for =  $district_rs->name;;
					
			}
			else if($type=="school")
			{
					$condition = " and school_id=' $school_id' ";
					$school_rs = $this->db->query("select  name,school_code from schools where school_id='$school_id'")->row();
					$report_for =  $school_rs->school_code."-".$school_rs->name;;
			}
			else {
				$report_for = $this->config->item('society_name');
			}
			
			  $sql  = "select 
								 
								sum(purchase_quantity) as purchase_quantity,
								date_format(entry_date,'%d-%M-%Y') as entry_date
							from balance_sheet where month(entry_date)='$month' and YEAR(entry_date)='$year'  $condition and item_id='$item_id' group by entry_date order by entry_date asc ";
			$rdata['rset'] = $this->db->query($sql);	
			
			$items_rs = $this->db->query("select concat(telugu_name,'-',item_name) as name from items where item_id='$item_id'")->row();
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
        $data["schools_list"] = $this->db->query("select * from  schools where is_school='1'  and school_code not like '%85000%' order by school_code asc ");
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["school_code"] = "";
        
        $data["module"] = "admin";
        $data["view_file"] = "itemwise_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_purchase_report($rdata)
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
	
	
	
	/*************************************************************************
	
	
	
	*****************************************************************************/
	function school_item_balance_entries($type='state',$tval='0') {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 if($this->input->post('month')!="" && $this->input->post('year')!="" )
		 {
			 
			$data['school_id']=    $school_id = $this->input->post('school_id');
			$data['item_id']=    $item_id = $this->input->post('item_id');
			 
			$data['month']=    $month = $this->input->post('month');
			$data['year']=    $year = $this->input->post('year'); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			 
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			$condition = " and school_id=' $school_id' ";
			$school_rs = $this->db->query("select  name,school_code from schools where   school_id='$school_id' ")->row();
			$report_for =  $school_rs->school_code."-".$school_rs->name;;
			 
			 
			 //get max date from balance sheet get closing balance
			 $crs = $this->db->query("select max(entry_date)as max_date from balance_sheet where  school_id='$school_id' and  month(entry_date)='$month' and YEAR(entry_date)='$year'");
			 $close_rs_date = $crs->row()->max_date;
			 
			   $closing_sql    = "select  item_id,closing_quantity 								 
											from balance_sheet where   school_id='$school_id' and entry_date ='$close_rs_date'    ";
											
			 $close_rset = $this->db->query($closing_sql);	
			 $items_report = array();
			 foreach($close_rset->result() as $row)
			 {
				 $items_report[$row->item_id]['closing_balance']  = $row->closing_quantity;
			 }
			 
			 
			
			$opening_date  = $year."-".$month."-01";		
			$opening_sql    = "select 	 item_id,opening_quantity 								 
											from balance_sheet where  school_id='$school_id' and  entry_date ='$opening_date'    ";
							
			$open_rset = $this->db->query($opening_sql); 
			 foreach($open_rset->result() as $row)
			 {
				 $items_report[$row->item_id]['opening_quantity']  = $row->opening_quantity;
			 }
			 
			
			
			$purchase_consumption_sql   = "select 	bs.item_id,  
								 sum(purchase_quantity) as purchase_quantity,
								sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as consumed_quantity 
								 
							from balance_sheet bs 
							where   school_id='$school_id' and month(entry_date)='$month' and YEAR(entry_date)='$year'     group by bs.item_id  ";
			 
			$pu_con_rset = $this->db->query($purchase_consumption_sql); 
			 foreach($pu_con_rset->result() as $row)
			 {
				 $items_report[$row->item_id]['purchase_quantity']  = $row->purchase_quantity;
				 $items_report[$row->item_id]['consumed_quantity']  = $row->consumed_quantity;
			 }
			 
			$item_sql    = "select 	item_id, concat(telugu_name,'-',item_name) as item_name  from items where  status='1'   ";
							
			$items_rset = $this->db->query($item_sql); 
			 foreach($items_rset->result() as $row)
			 {
				 $items_report[$row->item_id]['item_name']  = $row->item_name;
			 }
			 
			 
			$filtered_items = array();
			foreach($items_report as $item_id=>$item_details)
			{
				if($item_details['closing_balance'] !=0)
				{
					$filtered_items[$item_id] = $item_details;
					$filtered_items[$item_id]['closing_balance'] = $item_details['closing_balance'];
				}
			}
			 
			 
			 
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			$rdata['list'] = $filtered_items; 
			if($submit=="download")
			{ 
				$this->download_itembalance_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
        //print_a($this->session->all_userdata());
		 
		if($this->session->userdata("is_dco")=="1"){
			//district_id
			$data["schools_list"] = $this->db->query("select * from  schools where is_school='1' and district_id='".$this->session->userdata("district_id")."'"); 
			
				        
		}
		else
		{
			
			$data["schools_list"] = $this->db->query("select * from  schools where is_school='1' and school_code not like '%85000%' order by school_code asc "); 
		}
        $data["module"] = "admin";
        $data["view_file"] = "itemwise_balance_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_itembalance_report($rdata)
    {
				//print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = 'Balance Report ';
				$headtitle = 'Items Balance Report -'.$rdata['month_name']."-".$rdata['report_for'];
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Item Name " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'Opening Qty');	 				
				$this->excel->getActiveSheet()->setCellValue('C2',  'Purchase Qty');	 				
				$this->excel->getActiveSheet()->setCellValue('D2',  'Total Qty');	 				
				$this->excel->getActiveSheet()->setCellValue('E2',  'Consumed Qty');	 				
				$this->excel->getActiveSheet()->setCellValue('F2',  'Closing Balance');	 				
				 	
				$this->excel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);		 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$consumed_total = 0;
				foreach($rdata['list']   as $key=>$item_data){ 
				 
		 	 
					//print_a($item_data,1);
					$total_qty = $item_data['purchase_quantity'] +  $item_data['opening_quantity'];
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $item_data['item_name']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $item_data['opening_quantity']);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $item_data['purchase_quantity']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $total_qty);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $item_data['consumed_quantity']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $item_data['closing_balance']); 
					 
					$i++;$sno++;
				}
	  
              
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
	
	
	
	
	
	
	/*************************************************************************
	
	
	
	*****************************************************************************/
	function itewise_consumption_entries($type='state',$tval='0') {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 if($this->input->post('month')!="" && $this->input->post('year')!="" )
		 {
			$data['district_id']=   $district_id = $this->input->post('district_id');
			$data['school_id']=    $school_id = $this->input->post('school_id');
			$data['item_id']=    $item_id = $this->input->post('item_id');
			 
			$data['month']=    $month = $this->input->post('month');
			$data['year']=    $year = $this->input->post('year'); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			if($type=="district")
			{
					$condition = " and district_id=' $district_id' ";
					$district_rs = $this->db->query("select  name from districts where district_id='$district_id'")->row();
					$report_for =  $district_rs->name;;
					
			}
			else if($type=="school")
			{
					$condition = " and school_id=' $school_id' ";
					$school_rs = $this->db->query("select  name,school_code from schools where school_id='$school_id'")->row();
					$report_for =  $school_rs->school_code."-".$school_rs->name;;
			}
			else {
				$report_for = $this->config->item('society_name');
			}
			
			  $sql  = "select 
								 
								sum(session_1_qty+session_2_qty+session_3_qty+session_4_qty) as consumed_quantity,
								date_format(entry_date,'%d-%M-%Y') as entry_date
							from balance_sheet where month(entry_date)='$month' and YEAR(entry_date)='$year'  $condition and item_id='$item_id' group by entry_date order by entry_date asc ";
			$rdata['rset'] = $this->db->query($sql);	
			
			$items_rs = $this->db->query("select concat(telugu_name,'-',item_name) as name from items where item_id='$item_id'")->row();
			 //print_a($items_rs,0);
			$rdata['item_name'] = $items_rs->name;
			$rdata['month_name'] = $months[$month] ."-".$year;
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$this->download_consumption_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
        $data["item_list"] = $this->db->query("select * from  items where status='1'");
        $data["schools_list"] = $this->db->query("select * from  schools where is_school='1' and school_code not like '%85000%' order by school_code asc ");
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["school_code"] = "";
        
        $data["module"] = "admin";
        $data["view_file"] = "itemwise_consumption_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_consumption_report($rdata)
    {
				//print_a($rdata,1);
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Date " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'Consumed Quantity');	
				$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$consumed_total = 0;
				foreach($rdata['rset']->result()  as  $purchase_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $purchase_data->entry_date);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $purchase_data->consumed_quantity);
					 
					 $consumed_total = $consumed_total + $purchase_data->consumed_quantity;
					 
					 
					$i++;$sno++;
				}
	 
				 $this->excel->getActiveSheet()->setCellValue('A'.$i, 'Total Purchased');
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $consumed_total);
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
	
/**************************************************************



*******************************************************************/

	  function fixed_rates(){
		
		try{
			$crud = new grocery_CRUD($this);
			 
			$crud->set_theme('flexigrid'); 
			$crud->set_table('fixed_rates');
		 
		 
			$crud->set_subject(' Fuel Charges');
			$crud->columns(array('school_code','school_id','item_name','amount' ));
			 
					 
			 $crud->unset_add(); 
            $crud->unset_delete();
			 
			
			$crud->edit_fields(array('school_code','school_id','item_name','amount' ));
			 

			$crud->set_relation('school_id','schools','name');
			$crud->field_type('school_id', 'readonly');
			$crud->field_type('school_code', 'readonly');
			$crud->field_type('item_name', 'readonly');
			$crud->display_as('school_id', 'School Name');
			 

			$output = $crud->render(); 
			$data["module"] = "cms";
			$data["extra_content"] = "";
			$data["view_file"] = "cms";
			$output->title = "Manage Fuel Charges ";
			$data["crud"] = $output;
			echo Modules::run("template/admin", $data);
			

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
		 
	
	
		/*************************************************************************
	
	
	
	*****************************************************************************/
	function itewise_consumption_dailyentries($type='state',$tval='0') {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 if($this->input->post('from_date')!="" && $this->input->post('to_date')!="" )
		 {
			$data['district_id']=   $district_id = $this->input->post('district_id');
			$data['school_id']=    $school_id = $this->input->post('school_id');
			$data['item_id']=    $item_id = $this->input->post('item_id');
			 
			$data['from_date']=      $this->input->post('from_date');
			$data['to_date']=      $this->input->post('to_date'); 
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			if($type=="district")
			{
					$condition = " and sc.district_id=' $district_id' ";
					$district_rs = $this->db->query("select  name from districts where district_id='$district_id'")->row();
					$report_for =  $district_rs->name;;
					
			}
			else if($type=="school")
			{
					$condition = " and sc.school_id=' $school_id' ";
					$school_rs = $this->db->query("select  name,school_code from schools where school_id='$school_id'")->row();
					$report_for =  $school_rs->school_code."-".$school_rs->name;;
			}
			else {
				$report_for = $this->config->item('society_name');
			}
			
			$db_from_date = date('Y-m-d',strtotime($data['from_date']));
			$db_to_date = date('Y-m-d',strtotime($data['to_date']));
			
			      $sql  = "select 
								 bs.school_id,
								 (session_1_qty+session_2_qty+session_3_qty+session_4_qty) as consumed_quantity,
								 (  (session_1_qty * session_1_price) +
										(session_2_qty * session_2_price) +
										(session_3_qty * session_3_price) +
										(session_4_qty * session_4_price)  
										) as amount,
								date_format(entry_date,'%d-%M-%Y') as entry_date
							from balance_sheet  bs inner join schools sc on sc.school_id = bs.school_id  where  (entry_date between '$db_from_date' and '$db_to_date' )   $condition 
							and item_id='$item_id' and (session_1_qty+session_2_qty+session_3_qty+session_4_qty)>0 order by school_code,entry_date asc ";
			$rdata['rset'] = $this->db->query($sql);	
			
			$items_rs = $this->db->query("select concat(telugu_name,'-',item_name) as name from items where item_id='$item_id'")->row();
			 //print_a($items_rs,0);
			$rdata['item_name'] = $items_rs->name;
			$rdata['month_name'] = 
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$this->download_consumption_dailyreport($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 
			 
		 }
		 
		 $data["schools_list"] = $schools_rs = $this->db->query("select * from  schools where is_school='1' and school_code not like '%85000%'order by school_code asc ");
		 $school_names_codes = array();
		 foreach($schools_rs->result() as $sch_row){
			 $school_names_codes[$sch_row->school_id] = array('code'=>$sch_row->school_code,'name'=>$sch_row->name);
		 }
		 $data['school_names_codes'] = $school_names_codes;
		 
		 
		 
        $data["item_list"] = $this->db->query("select * from  items where status='1'");
        
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["school_code"] = "";
        
		$data["display_dates"] = date('d-m-Y',strtotime($data['from_date']))." - ".date('d-m-Y',strtotime($data['to_date']));
			 
		
		
        $data["module"] = "admin";
        $data["view_file"] = "itemwise_consumption_daily_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_consumption_dailyreport($rdata)
    {
		
		 $schools_rs = $this->db->query("select * from  schools where is_school='1'");
		 $school_names_codes = array();
		 foreach($schools_rs->result() as $sch_row){
			 $school_names_codes[$sch_row->school_id] = array('code'=>$sch_row->school_code,'name'=>$sch_row->name);
		 }
		 
		 
				//print_a($rdata,1);
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Date " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'School Code ');	
				$this->excel->getActiveSheet()->setCellValue('C2',  'Name');	
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
				$consumed_total = 0;
				$consumed_total_amount = 0;
				foreach($rdata['rset']->result()  as  $purchase_data){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $purchase_data->entry_date);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_names_codes[$purchase_data->school_id]['code']);
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_names_codes[$purchase_data->school_id]['name']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $purchase_data->consumed_quantity);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $purchase_data->amount);
					 
					 $consumed_total = $consumed_total + $purchase_data->consumed_quantity;
					 $consumed_total_amount = $consumed_total_amount + $purchase_data->amount;
					 
					 
					$i++;$sno++;
				}
	 
				 $this->excel->getActiveSheet()->setCellValue('C'.$i, 'Total  ');
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $consumed_total);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $consumed_total_amount);
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
	
/**************************************************************



*******************************************************************/

	
		
	/*************************************************************************
	
	
	
	*****************************************************************************/
	function savings_consumption_entries($type='state',$tval='0') {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 if($this->input->post('savings_date')!=""  )
		 {
			$data['district_id']=   $district_id = $this->input->post('district_id');
			$data['school_id']=    $school_id = $this->input->post('school_id');
			$data['item_id']=    $item_id = $this->input->post('item_id');
			 
			$data['savings_date']=  $savings_date =   date('Y-m-d',strtotime( $this->input->post('savings_date')));
			
			$data['rdate_display'] =  date('d-m-Y',strtotime( $this->input->post('savings_date')));
			
			$data['submit']=    $submit = $this->input->post('submit'); 
			$data['type']=    $type = $this->input->post('type'); 
			$rdata = array();
			
			$condition = '';
			$report_for = '';
			if($type=="district")
			{
					$condition = " and bs.district_id='$district_id' ";
					$district_rs = $this->db->query("select  name from districts where district_id='$district_id'")->row();
					$report_for =  " District - ".$district_rs->name;;
					
			}
			else if($type=="school")
			{
					$condition = " and bs.school_id='$school_id' ";
					$school_rs = $this->db->query("select  name,school_code from schools where school_id='$school_id'")->row();
					$report_for =  " School - ". $school_rs->school_code."-".$school_rs->name;;
			}
			else {
				$report_for = $this->config->item('society_name');
			}
			 
			  $sql  = "select sc.name,sc.school_code,bs.school_id,sum(round(((session_1_qty*session_1_price) +
										(session_2_qty*session_2_price)+(session_3_qty*session_3_price)+
										(session_4_qty*session_4_price)),2)) as consumed_total
					from balance_sheet bs inner join schools sc on sc.school_id = bs.school_id  and sc.is_school=1 and sc.school_code not like '%85000%' where  bs.entry_date='$savings_date' $condition group by bs.school_id order by school_code asc ";
			$consumed_report = $this->db->query($sql);	
			
			$school_data = array();
			foreach( $consumed_report->result() as $crow)
			{
				$school_data[$crow->school_id]['consumed_total'] = $crow->consumed_total;
				$school_data[$crow->school_id]['school_code'] = $crow->school_code;
				$school_data[$crow->school_id]['name'] = $crow->name;
				$school_data[$crow->school_id]['eligible_amt'] = 0;
			}
			 
			
			$days_sql = "SELECT DAY( LAST_DAY( '$savings_date' ) ) as days";
			$days_row  = $this->db->query($days_sql)->row();
			$days_count = $days_row->days ;
		  
		  
			// $gsql ="SELECT * FROM `group_prices`";
			// $rs = $this->db->query($gsql);
			
			$price_sql = "select * from group_prices  where category='normal' and ? between start_date and end_date";
			$price_rs = $this->db->query($price_sql,array( $savings_date));
		
			 $group_prices = array("group_1"=>0,"group_2"=>0,"group_3"=>0);
			 foreach($price_rs->result() as $row)
			 {
				 if($row->group_code == "gp_5_7")
				 {
					 $group_prices["group_1"] = $row->amount/$days_count;
				 }
				 else if($row->group_code == "gp_8_10")
				 {
					 $group_prices["group_2"] = $row->amount/$days_count;
				 }
				 else if($row->group_code == "gp_inter")
				 {
					 $group_prices["group_3"] = $row->amount/$days_count;
				 }
				 
			 }
			 
			   $sql  = "select school_id, 
										round(
											( cat1_attendence * ". $group_prices["group_1"]. " ) +   
											(cat2_attendence * ". $group_prices["group_2"]. " ) +   
											(cat3_attendence * ". $group_prices["group_3"]. " ) 
										,2) as eligible_amt 
					from school_attendence  where  entry_date='$savings_date'  group by school_id";
			  $attendence_report = $this->db->query($sql);	
			
			
			 
			 foreach( $attendence_report->result() as $arow)
			{
				if(isset( $school_data[$arow->school_id])){
						  $school_data[$arow->school_id]['eligible_amt'] = $arow->eligible_amt;
						  
						  if(isset($school_data[$arow->school_id]['consumed_total'])){
								$school_data[$arow->school_id]['savings'] = floatval($arow->eligible_amt -  $school_data[$arow->school_id]['consumed_total']);
						  }
				}
			  
			}
			//print_a( $school_data);
			 
			
			$rdata['school_data'] = $school_data;
			$rdata['report_for'] = $report_for;
			
			
			
			
			if($submit=="download")
			{
				$rdata['rdate_display'] = $data['rdate_display'];
				$this->download_savings_report($rdata);
			}
			else  {
				$data['display_result'] = true ;
				$data['rdata'] = $rdata;
			} 
			 $data['school_data'] = $school_data;
			 
		 }
        $data["item_list"] = $this->db->query("select * from  items where status='1'");
        $data["schools_list"] = $this->db->query("select * from  schools where is_school='1' and school_code not like '%85000%' order by school_code asc ");
        $data["districts_list"] = $this->db->query("select * from  districts  ");
        $data["type"] = $type;
        $data["tval"] = $tval;
        $data["school_code"] = "";
        
        $data["module"] = "admin";
        $data["view_file"] = "savings_consumption_report";
        echo Modules::run("template/admin", $data);
    }
	
	/********************************************************************
	
	
	********************************************************************/
	public function download_savings_report($rdata)
    {
				// print_a($rdata,1);
				$this->excel->setActiveSheetIndex(0);
                //name the worksheet
				$title = ' Savings Report   ';
				$headtitle = 'Savings Report - '.$rdata['rdate_display']."-".$rdata['report_for'];
				 
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

				
				$this->excel->getActiveSheet()->setCellValue('A2', " Code " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'Name');	
				$this->excel->getActiveSheet()->setCellValue('C2',  'Consumption Amount');	
				$this->excel->getActiveSheet()->setCellValue('D2',  'Allowed Amount');	
				$this->excel->getActiveSheet()->setCellValue('E2',  'Savings Amount');	
				 		
				 
				 
				 
				
 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
			 
                $i = 3;
				$sno=1;
				$consumed_total = 0;
				foreach($rdata['school_data']  as  $school_id => $sdata){ 
				 
		 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $sdata['school_code']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $sdata['name']);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $sdata['consumed_total']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $sdata['eligible_amt']);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $sdata['savings']); 
					 
					 
					 
					 
					$i++;$sno++;
				}
	 
				 
              
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
	/*********************************************************************************
	
	
	
	
	
	*********************************************************************************/
	
		function schoolreports()
	{
		$this->form_validation->set_rules('school_id', 'School Name', 'required');              
		$this->form_validation->set_rules('fromdate', 'From Date', 'required');              
		$this->form_validation->set_rules('todate', 'To Date', 'required'); 
		$item_names   = array();
		$data = array();
		$data['exclude'] = $this->input->post('exclude');
		if($this->form_validation->run() == true && $this->input->post('fromdate') !="" && $this->input->post('todate') !="")
		{
				 
				$school_id = $this->input->post('school_id');			 
				$from_date = date('Y-m-d',strtotime($this->input->post('fromdate')));
				$to_date = date('Y-m-d',strtotime($this->input->post('todate')));
				
				$exclude =  $this->input->post('exclude');
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
				  $sql = "SELECT distinct bs.item_id,item_name   FROM `balance_sheet`  bs inner join items on items.item_id=bs.item_id  
				WHERE bs.school_id='".$school_id."' and  bs.entry_date between '".$from_date ."' and '".$to_date ."'";
				$rs  = $this->db->query($sql);
				$item_ids  = array();
				
				foreach($rs->result() as $row)
				{
					$item_ids[] = $row->item_id;
					$item_names[$row->item_id] = $row->item_name;
				}
				//print_a($item_ids);
				//get the opening balances of from date 
				  /*
				  calculate opening balances
				  */
				  foreach($item_ids as $opening_item_id){
								
								
								$tsql = "select *,truncate((opening_quantity*opening_price),2) as opening_total from balance_sheet 
								where school_id='".$school_id."' and 
								entry_date BETWEEN '".$from_date ."'  and '".$to_date ."'  and 
								item_id='".$opening_item_id."' order by entry_date asc limit 0,1";
								$trs  = $this->db->query($tsql);
								if($trs->num_rows()>0){
									$sdata = $trs->row();
										$items[$opening_item_id]['opening_quantity'] = $sdata->opening_quantity;
										$items[$opening_item_id]['opening_price'] = $sdata->opening_price;
										$items[$opening_item_id]['opening_total'] = $sdata->opening_total;
								}
								else{
										$tsql = "select *,truncate((opening_quantity*opening_price),2) as opening_total from balance_sheet where
													school_id='".$school_id."'
												and entry_date <='".$from_date ."' and item_id='".$opening_item_id."' 
												order by entry_date desc limit 0,1";
									$trs  = $this->db->query($tsql);
										if($trs->num_rows()>0){
											$sdata = $trs->row();
												$items[$opening_item_id]['opening_quantity'] = $sdata->opening_quantity;
												$items[$opening_item_id]['opening_price'] = $sdata->opening_price;
												$items[$opening_item_id]['opening_total'] = $sdata->opening_total;
										}
									
								}
		
					}
					/*
					End of opeinig balances
					*/
					/*
					calculate Closing balances
					*/
					
					foreach($item_ids as $opening_item_id){
								
								 
								$tsql = "select *,truncate((closing_quantity*closing_price),2) as closing_total from balance_sheet 
								where school_id='".$school_id."' and 
								entry_date BETWEEN '".$from_date ."'  and '".$to_date ."'  and 
								item_id='".$opening_item_id."' order by entry_date desc limit 0,1";
								$trs  = $this->db->query($tsql);
								if($trs->num_rows()>0){
									$sdata = $trs->row();
										$items[$opening_item_id]['closing_quantity'] = $sdata->closing_quantity;
										$items[$opening_item_id]['closing_price'] = $sdata->closing_price;
										$items[$opening_item_id]['closing_total'] = $sdata->closing_total;
								}
								else{
										$tsql =  "select *,(closing_quantity*closing_price) as closing_total from balance_sheet 
								where school_id='".$school_id."' and 
								entry_date <='".$from_date ."'  and item_id='".$opening_item_id."' 
								order by entry_date desc limit 0,1";
									$trs  = $this->db->query($tsql);
										if($trs->num_rows()>0){
											$sdata = $trs->row();
												$items[$opening_item_id]['closing_quantity'] = $sdata->closing_quantity;
												$items[$opening_item_id]['closing_price'] = $sdata->closing_price;
												$items[$opening_item_id]['closing_total'] = $sdata->closing_total;
										}
									
								}
		
					}
					
								
					/* end of closing balances */
					
				/* calculate consumption data 
				*/				
				$sql = "select item_id,sum(( session_1_qty+session_2_qty+session_3_qty+session_4_qty)) as consumed_qty ,
						truncate(sum(
							( 	(session_1_qty*session_1_price) + 
								(session_2_qty*session_2_price) + 
								(session_3_qty*session_3_price) + 
								(session_4_qty*session_4_price) )
							),2) as consumed_total
						from balance_sheet
					where school_id='".$school_id."' and 
					entry_date between '$from_date' and '$to_date' group by item_id";
				 $rs  = $this->db->query($sql);
				foreach($rs->result() as $row)
				{
					$items[$row->item_id]['consumed_quantity'] = $row->consumed_qty;
					$items[$row->item_id]['consumed_total'] = $row->consumed_total;
					 
				}	
		
		/*
		calculate purchase data 
		*/
		
		$sql ="select item_id,sum(purchase_quantity) as purchase_qty,	purchase_price,
		
							truncate(sum((purchase_quantity*purchase_price)),2) purchase_total from balance_sheet 
							where school_id='".$school_id."'and entry_date between '$from_date' and '$to_date' group by item_id";				 
		
		
		$rs  = $this->db->query($sql);
				foreach($rs->result() as $row)
				{
					$items[$row->item_id]['purchase_price'] = $row->purchase_price;
					$items[$row->item_id]['purchase_qty'] = $row->purchase_qty;
					$items[$row->item_id]['purchase_total'] = $row->purchase_total;
					 
				}					
				
				 /* end purchase data */
				 
				 //rearrange items array , check all keys exists or not if not add trhem
				 
				  $items_dup = $items;
				 foreach($items_dup as $item_id=>$itemobj)
				 {
					 
					 $cols =  array(
								'opening_quantity',
								'opening_price',
								'opening_total',
								'purchase_price',
								'closing_quantity',
								'closing_price',
								'closing_total',
								'consumed_quantity',
								'consumed_total',
								'purchase_qty',
								'purchase_total',
								'total_qty',
								'total_price');
								foreach($cols as $column){
										 if(!array_key_exists( $column,$itemobj))
												$items[$item_id][ $column] = '0';
								}
						
						
				 }
				 /* calaculate Total from opeinig balance and purchase balance */
				 $items_dup = $items;
				 foreach($items_dup as $item_id=>$itemobj)
				 {
					// echo $item_id;
					 
				//print_a($item_id,1);
						$items[$item_id]['total_qty'] =    ($itemobj['purchase_qty'] +  $itemobj['opening_quantity']);
						$items[$item_id]['total_price'] =   ($itemobj['purchase_total'] +  $itemobj['opening_total']);
				 }
				 
		
				$data['rfrom_date'] = $from_date;
				$data['rto_date'] = $to_date;
				$data['items'] = $items;
				
				if($this->input->post('submit')=="Download Report")
				{
					$filedata['fromdate'] = date('d-M-Y',strtotime($this->input->post('fromdate')));
					$filedata['todate'] =  date('d-M-Y',strtotime($this->input->post('todate')));
					$filedata['sname'] = $data['sname'] =   $this->db->query("select name from schools where school_id='".$school_id."'")->row()->name;
					$this->consolidated_report($items,$filedata,$item_names);
					die;
				}
				$data['sname'] =   $this->db->query("select name from schools where school_id='".$school_id."'")->row()->name;

		}
		
		
		
		$data["item_prices"] = $this->school_model->get_items_price($this->session->userdata("district_id"));		
		$data["today_purchases"] = $this->school_model->get_balance_entries_today($this->session->userdata("school_id"),date('Y-m-d'));
		$drs = $this->db->query("select * from  items where status='1'");         
        $data["rset"] = $drs;
        $data["itemnames"] = $item_names;
		
		
		$data["module"] = "admin";
        $data["view_file"] = "school_reports";
        echo Modules::run("template/admin", $data);
         
	}
	
	/*********************************************************************
	
	
	*********************************************************************/
		public function consolidated_report($rows,$metadata,$item_names)
    {
          
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Report');
                //set cell A1 content with some text
                $this->excel->getActiveSheet()->setCellValue('A1', $this->config->item('society_name').$metadata['sname']);
				 //merge cell A1 until Q1
                $this->excel->getActiveSheet()->mergeCells('A1:H1');
                $this->excel->getActiveSheet()->setCellValue('A2', 'DIET EXPENDITURE STATEMENT FOR dates between '.$metadata['fromdate']. " - ".$metadata['todate']);
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
				$this->excel->getActiveSheet()->setCellValue('B3', 'Item');				
				$this->excel->getActiveSheet()->setCellValue('C3', 'Opening Balance Qty');
				$this->excel->getActiveSheet()->setCellValue('D3', 'Purchase Qty');
				$this->excel->getActiveSheet()->setCellValue('E3', 'Total Qty');				
				$this->excel->getActiveSheet()->setCellValue('F3', 'Consumption Qty');				
				$this->excel->getActiveSheet()->setCellValue('G3', 'Closing Qty');				
				$this->excel->getActiveSheet()->setCellValue('H3', 'Consumption Amount');
			 
														 
				
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12);
                $this->excel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 4;
				$sno=1;
				$consumption_amount_total = 0;
				
				$exclude = $this->input->post('exclude');
				foreach($rows as $item_idd =>$rowitem)
				{
					//echo $item_idd;
				//print_a($rowitem);
				
				if($exclude=="exclude" && $rowitem['consumed_total']==0)
						continue;
					
				 
						$consumption_amount_total = $consumption_amount_total + 	$rowitem['consumed_total'];	
					$this->excel->getActiveSheet()->setCellValue('A'.$i,  $sno);
					$this->excel->getActiveSheet()->setCellValue('B'.$i, $item_names[$item_idd]);
					$this->excel->getActiveSheet()->setCellValue('C'.$i, $rowitem['opening_quantity']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i, $rowitem['purchase_qty']);
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $rowitem['total_qty']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $rowitem['consumed_quantity']);
					
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $rowitem['closing_quantity']);
					$this->excel->getActiveSheet()->setCellValue('H'.$i,  number_format($rowitem['consumed_total'],2));
					 
					$i++;$sno++;
				}
	 
					$this->excel->getActiveSheet()->setCellValue('G'.$i, "Total Consumption  Amount");
					$this->excel->getActiveSheet()->setCellValue('H'.$i,  number_format($consumption_amount_total,2));
				$this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getFont()->setBold(true);
                
              
                $filename='report_'.date('d-m-Y').'.xls'; //save our workbook as this file name
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
		
	/*************************************************************************
	
	
	
	*****************************************************************************/
	function special_entries($sdate=null) {
		 
		 $data['display_result'] = false ;
		 $data['months'] = $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
									
		 if($this->input->post('savings_date')!=""  )
		 {
			  $savings_date =   date('Y-m-d',strtotime( $this->input->post('savings_date')));
			  redirect('admin/subadmin/special_entries/'.$savings_date);
			
			
			
			
			
			 
				
		 }
		 if($sdate==null){ 
			 $savings_date =   date('Y-m-d'); 
		 }
		 else{
				$savings_date =   date('Y-m-d',strtotime($sdate));  
		 }
		  $data['savings_date']=   $savings_date;
		  $data['rdate_display'] =     date('d-m-Y',strtotime($savings_date));
		 
		 
		 
		 
		 $report_for = $this->config->item('society_name'); 
		   $sql  = "select ita.approval_id,ita.strength,ita.dpc_approved ,sc.name,sc.school_code,ita.school_id ,it.telugu_name,it.item_name,ita.status ,date_format(ita.entry_date,'%d-%m-%Y' ) entry_date , date_format(ita.requested_time,'%d-%m-%Y %r' ) requested_time 
												from item_approvals ita  inner join schools sc on sc.school_id = ita.school_id  inner join items it on it.item_id=ita.item_id
													where  date_format(ita.requested_time,'%Y-%m-%d')='$savings_date'   order by sc.school_code,ita.item_id asc";
			$sc_items_report = $this->db->query($sql);	
			
			 
			$data['report_for'] = $report_for; 

			$data['display_result'] = true ;
			$data['rdata'] = $sc_items_report;
         
        $data["type"] = $type;
        $data["tval"] = $tval; 
        
        $data["module"] = "admin";
        $data["view_file"] = "specilas_approvals_admin";
        echo Modules::run("template/admin", $data);
    }
	/********************************************************************************************
	
	
	
	********************************************************************************************/
	public function specials_action($status,$approval_id,$sdate)
	{
		$status = ucfirst($status);
		$sql = "update item_approvals set status='$status' where approval_id='$approval_id'";
		$this->db->query($sql);
		redirect('admin/subadmin/special_entries/'.$sdate);
		
	}
	
	/********************************************************************************************************* 
	
	
	*********************************************************************************************************/
		public function attendence_consumed_report_between_dates($school_attendence,$report_date_frmted,$report_date_toted,$schools_info=array())
    {
		 
		  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
		  
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                $this->excel->getActiveSheet()->setTitle('Attendance & Consumption Report');
               
                $this->excel->getActiveSheet()->setCellValue('A1', ' Attendance and Consumption Report between Dates of $report_date_frmted and $report_date_toted');
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

				$this->excel->getActiveSheet()->getStyle('A3:Z3')->applyFromArray( $style_header );

				
				$this->excel->getActiveSheet()->setCellValue('A2', " School Name " );
				$this->excel->getActiveSheet()->setCellValue('B2',  'School Code' );	
				$this->excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);				
				 
				$this->excel->getActiveSheet()->setCellValue('C2', " Strength " );
				$this->excel->getActiveSheet()->setCellValue('D2', " Attendence ");	
				$this->excel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);				
				 
				
				$this->excel->getActiveSheet()->setCellValue('E2', " Allowed Amount " );
				$this->excel->getActiveSheet()->setCellValue('F2',  'Consumption Amount');	
				$this->excel->getActiveSheet()->setCellValue('G2',  'Remaining Amount');	
				$this->excel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);				
				$this->excel->getActiveSheet()->getStyle('G2')->getFont()->setBold(true);				
				 
				
				 
			 
					 
			 
                 $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //make the font become bold
                $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
				$this->excel->getActiveSheet()->getStyle('A2:S2')->getFont()->setBold(true);
				
                $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12);
                //$this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                
                $i = 3;
				$sno=1;
				$consumption_amount_total = 0;
				foreach($school_attendence as $school_code=>$school_data){ 
				 
		 	 	 
					
					$this->excel->getActiveSheet()->setCellValue('A'.$i, $school_data['name']);
					$this->excel->getActiveSheet()->setCellValue('B'.$i,  $school_code);
					$this->excel->getActiveSheet()->setCellValue('C'.$i,  $school_data['strength']);
					$this->excel->getActiveSheet()->setCellValue('D'.$i,  $school_data['attendence']);
					
					
					$this->excel->getActiveSheet()->setCellValue('E'.$i, $school_data['allowed_amt']);
					$this->excel->getActiveSheet()->setCellValue('F'.$i, $school_data['consumed_amt']);
					$this->excel->getActiveSheet()->setCellValue('G'.$i, $school_data['remaining_amt']); 
					
					$this->excel->getActiveSheet()->getStyle('S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					 
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
				ob_end_clean();ob_start();
                $objWriter->save('php://output');
                 
    }


}
