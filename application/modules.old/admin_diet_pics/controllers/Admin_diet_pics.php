<?php 
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(0);
 date_default_timezone_set('Asia/Kolkata');
class Admin_diet_pics extends MX_Controller {

    function __construct() {
        parent::__construct();
		if($this->uri->segment(2) !="login") { 
					 Modules::run("security/is_admin");		 
					
		}
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
		$this->load->config("config");  
		$this->load->config("purchase_bills/config");  
		$this->load->config("api/dietpics_config");  
		$this->load->model("common/common_model");  
		$this->load->library("ci_jwt");  
	}
	function index() {
		 
		 
		  $this->form_validation->set_rules('school_date', 'School Date', 'required');    
		  $this->form_validation->set_rules('school_type', 'Report Type', 'required');    
		 
		if($this->form_validation->run() == true )
		{
			//print_a($this->input->post(),1);
			$school_date = date('Ymd',strtotime($this->input->post('school_date')));
			$school_type =  $this->input->post('school_type') ;
			$encoded_data = $this->ci_jwt->jwt_web_encode(array('school_date'=>$school_date,'school_type'=>$school_type));
			redirect('admin_diet_pics/sessionpics_report/'. $encoded_data);
			 die;
			 
		}
		  
        $data["school_code"] = "";
        $data["module"] = "admin_diet_pics";
        $data["view_file"] = "subadmin_sessionpics_form";
        echo Modules::run("template/admin", $data);
    }
	function sessionpics_report($encoded_data)
	{
		$decoded_data = $this->ci_jwt->jwt_web_decode($encoded_data);
		$date = $decoded_data->school_date;
		$result_type  =$decoded_data->school_type;
		
		//print_a($decoded_data);
		
		if(!in_array($result_type,array("all","missed")))
		{
			 $result_type = "all";
		}
		
		$data['result_type'] = $result_type;
		$data['input_date'] =  date('m/d/Y',strtotime($date));
		if($date==null)
				$date = date('Ymd');
			
		  $report_date = date('Y-m-d',strtotime($date));
		 
		  $pics_data = $this->common_model->get_diet_pic_path($report_date);
		  if( $pics_data == NULL)
					redirect();
				
		 $table  = $pics_data->table_name;
		 
		   $sql = "SELECT s.name, s.school_code,s.school_id, p.food_session_id, p.session_count   AS total_uploads
										FROM  schools s LEFT JOIN  
										(SELECT school_id,food_session_id,count(*) as session_count FROM  $table 
										where date_format(uploaded_date,'%Y-%m-%d')=?  group by school_id,food_session_id)

										p  ON p.school_id = s.school_id    ";
		$rs = $prs  = $this->db->query($sql,array($report_date));
	//	echo $this->db->last_query();
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
						$sql = "SELECT * from schools where district_id=? and  is_school='1' and school_code not like '%85000%'";
						$rs = $prs  = $this->db->query($sql,array($district_id)); 
					}
					else
					{
							$sql = "SELECT * from schools where  is_school='1' and school_code not like '%85000%'";
							$rs = $prs  = $this->db->query($sql);
					}

					
		
		$data["rset"] = $rs;
		
		$diet_pics_chk_rs = "select school_id,count(item_id) as noted_items from dietpic_cheker where entry_date=? group by school_id ";
		$dietnote_rs  = $this->db->query($diet_pics_chk_rs,array($report_date));
		$noted_items = array();
		foreach($dietnote_rs->result() as $drow)
		{
			$noted_items[$drow->school_id] = $drow->noted_items;
		}
		
		$data["noted_items"] =  $noted_items;
		$data["pic_main_path"] =  $pic_main_path;
		$data["choosendate"] = $date;
		$data["item_counts"] = $item_counts;
		$data["uploads"] = $uploads;
		 
		$data["module"] = "admin_diet_pics";
		
		
		if($result_type == "missed")
			$data["view_file"] = "subadmin_missed_session_pics_list";
		else 		
			$data["view_file"] = "subadmin_session_pics_list";
		
		
		
		echo Modules::run("template/admin", $data);
		
	}
	
	function viewpics($encoded_data)
	{
		//print_a($this->session->all_userdata());
		$decoded_data = $this->ci_jwt->jwt_web_decode($encoded_data);
		
		 
	 
		 
		  $data["school_id"] = $decoded_data->school_id;
		  $data["edate"] = $decoded_data->date;
		  $data['locked'] = false;
		  
		   if(!$this->is_allowed_to_update( $decoded_data->date))
					 {
						 $data['locked'] = true;
						  
					 }
		  
		$school_id = $decoded_data->school_id;
		$entry_date  =$decoded_data->date; 
		$entry_date = date('Y-m-d',strtotime($entry_date));
		
		 $data['pic_data'] =  $pic_data = $this->common_model->get_diet_pic_path($entry_date);
		 $table = $pic_data->table_name;
			$stu_info = $this->db->query("select * from schools where school_id=? ",array($school_id))->row();
		
		$rs = $this->db->query("select * from $table  where school_id=? and date(uploaded_date)=?",array($school_id,$entry_date));
		//echo $this->db->last_query();
		
		$balance_sheet_table = $this->common_model->get_stock_entry_table($entry_date);
		
		$sql = "SELECT it.item_name,it.telugu_name,bs.*,
									(
										(session_1_qty*session_1_price) +
										(session_2_qty*session_2_price)+ 
										(session_3_qty*session_3_price) + 
										(session_4_qty*session_4_price)
									) as today_consumed

									FROM $balance_sheet_table bs inner join items  it on bs.item_id=it.item_id WHERE `school_id`=? and 
									`entry_date`=? order by closing_quantity desc";
				$clist  = $this->db->query($sql,array($school_id,$entry_date));
			$data["item_list"] = 	$data["clist2"] = $data["clist"] = $clist;
			
			
			$sql = "SELECT it.item_name,it.telugu_name,dc.*  
									FROM `dietpic_cheker` dc inner join items  it on dc.item_id=it.item_id WHERE  school_id =? and 
									 entry_date =?  ";
				$diet_notes   = $this->db->query($sql,array($school_id,$entry_date));
				//echo $this->db->last_query();
				$data["diet_notes"] =$diet_notes;
				
				
				
		 // echo $this->db->last_query();
		  $data["encoded_data"] =  $encoded_data;
		  $data["pic_main_path"] = $pic_main_path;
		$data["school_name"] =$stu_info->school_code." - ".$stu_info->name;
		$data["edate"] =  date('d-M-Y',strtotime($entry_date));
		$data["pics_rset"] = $rs; 
		$data["module"] = "admin_diet_pics"; 
		$data["view_file"] = "dietpics_list"; 
		echo Modules::run("template/admin", $data);
		
	}
	function save_notes($encoded_data)
	{
		
		$decoded_data = $this->ci_jwt->jwt_web_decode($encoded_data);
		
		 
	 
		// print_a($decoded_data ,1);
		  $school_id = $decoded_data->school_id;
		  $entry_date = $decoded_data->date; 
		  
		  
		  if ($_POST) {
				$item_id = intval($this->input->post("item_id"));
				 
				
				$min_20 =    $this->input->post("min_20") ;
				$forth_people =   $this->input->post("forth_people") ;
				$dpc =   $this->input->post("dpc_approved") ;
				
				$uid = $this->session->userdata("user_id");
				 $result = true;
				 
				 if($item_id  ==0 && $min_20 ==0 && $forth_people ==0)
				 {
					 $this->userlib->show_ajax_output(FALSE, "Please fill all required fields");
				 }
				 else
				 {
					 if(!$this->is_allowed_to_update($entry_date))
					 {
						 $locked_date  = date('d-M-Y' ,strtotime($entry_date));
						 $this->userlib->show_ajax_output(FALSE, "Not Allowed to update. Entry date $locked_date is Locked ");
					 }
					

$balance_sheet_table = $this->common_model->get_stock_entry_table($entry_date);

					$drs = $this->db->query("select * from $balance_sheet_table where school_id=? and item_id=? and entry_date=? ",array($school_id,$item_id,$entry_date))->row();
					 
					 
					
					 
					 if($this->config->item("milk_exemption") ==true && $this->config->item("milk_item_id") == $item_id)
					 {
						 //only breakfast amount deduction for milk 
					  $total = ($drs->session_1_qty* $drs->session_1_price) ;
					 
					 }
					 else
					 {
						 $total = ($drs->session_1_qty* $drs->session_1_price) +($drs->session_2_qty* $drs->session_2_price) +($drs->session_3_qty* $drs->session_3_price) +($drs->session_4_qty* $drs->session_4_price) ; 
					 
					 }
						 
					 $amount = floatval($total);
					 
					 
					$rs  = $this->db->query("delete from dietpic_cheker where item_id=? and entry_date=? and school_id=? ",array($item_id,$entry_date,$school_id));
					
					$dataarray = array('school_id'=>$school_id,'created_by'=>$uid,'item_id'=>$item_id,'min_20'=>$min_20,'one_forth_total'=>$forth_people,'entry_date'=>$entry_date,'amount'=>$amount,'dpc_approved'=>$dpc);
					$this->db->insert("dietpic_cheker",$dataarray);
					 $this->userlib->show_ajax_output(TRUE, "Successfully Added");
				 
				 }
				 
           
        }
	}
    function ajax_notes_list($encoded_data)
	{
		$decoded_data = $this->ci_jwt->jwt_web_decode($encoded_data);
		
		 
	 
		// print_a($decoded_data ,1);
		  $school_id = $decoded_data->school_id;
		  $entry_date = $decoded_data->date; 
		  
		  		
			$sql = "SELECT it.item_name,it.telugu_name,dc.*  
									FROM `dietpic_cheker` dc inner join items  it on dc.item_id=it.item_id WHERE  school_id =? and 
									 entry_date =?  ";
				$diet_notes   = $this->db->query($sql,array($school_id,$entry_date));
				//echo $this->db->last_query();
				$data["diet_notes"] =$diet_notes;
				
				
		 $this->load->view("noted_list_tab",$data);
		  
	
	}
	
	/********************************************************
	
	
	***********************************************************/
	public function daywise_noted_items_popup($encoded_params)
	{
		
		$decoded_params = $this->ci_jwt->jwt_web_decode($encoded_params);
		 
		
		$school_id=$decoded_params->school_id;
		$date=$decoded_params->rdate;
		
		 
		$school_id = intval($school_id);
		if($school_id==0)
		{
			echo "<h1>Invalid School</h1>";
			return;
		}
		else
		{
			
			if($date==null)
				$date = date('Y-m-d');
			
		  $report_date = date('Y-m-d',strtotime($date));
		  
			 $sql = "select ";
			
			$rs = $this->db->query($sql,array($report_date,$school_id));
			$data['rdate'] = date('d-m-Y',strtotime($date));
			$data['rset'] = $rs;
			$data['sname'] = $this->db->query("select * from schools where school_id=?",array($school_id))->row()->name;
			$this->load->view("daywise_noted_items_popup",$data);
			
		}
		
		
		
	}
	
	private function is_allowed_to_update($date)
		{
			
			$is_future_date = $this->db->query(" SELECT ? > CURRENT_DATE  is_future_date ",array($date))->row()->is_future_date;		
			if($is_future_date==1)
			{
				return false;
			}
			$INTERVAL_DAYS = 2;//2 days 
			if($this->session->userdata("user_role") == "subadmin" && $this->session->userdata("is_dco") == "0" )
				$INTERVAL_DAYS = 100;//100 days 
			
			
				$is_greater_date = $this->db->query(" SELECT CURRENT_DATE >  DATE_ADD(last_day(?), INTERVAL ? DAY) is_greater_date     ",array($date,$INTERVAL_DAYS))->row()->is_greater_date;		
				  if($is_greater_date==1)
				  {
					return false;
				  }else
				  { 
					return true;
				  }
		}
}
